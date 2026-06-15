<?php

namespace App\Services;

use App\Models\User;
use App\Models\Tantangan;
use App\Models\NilaiTantangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParentNotificationService
{
    /**
     * Cek siswa yang tidak mengerjakan tantangan dan kirim notif ke orang tua.
     * 
     * Kriteria: Siswa tidak mengerjakan minimal 3 tantangan dalam 1 minggu terakhir
     * dan tidak mengerjakan tantangan di bab saat ini.
     */
public static function checkAndNotifyInactiveStudents(): void
{
    $siswaUsers = User::where('role', 'siswa')
        ->whereNotNull('nomor_wa_orang_tua')
        ->where('nomor_wa_orang_tua', '!=', '')
        ->get();

    foreach ($siswaUsers as $siswa) {
        // Hitung tantangan yang sudah TERLAMBAT (lewat batas_waktu) tapi belum dikerjakan
        $terlambatCount = Tantangan::where('status', 'published')
            ->where('batas_waktu', '<', now())  // sudah lewat deadline
            ->whereDoesntHave('nilaiTantangan', function ($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            })
            ->count();

        // Kirim notif hanya jika terlambat 3 kali atau lebih
        if ($terlambatCount >= 3) {
            self::notifyParent($siswa, $terlambatCount);
        }
    }
}

    /**
     * Kirim notifikasi ke orang tua siswa via Twilio WhatsApp.
     */
    public static function notifyParent(User $siswa, int $siswaNotWorkingCount): void
    {
        if (!$siswa->nomor_wa_orang_tua) {
            Log::warning("Parent contact not found for student: {$siswa->nama}");
            return;
        }

        try {
            $namaOrangTua = $siswa->nama_orang_tua ?? 'Orang Tua';
            $namaSiswa = $siswa->nama;
            $levelSiswa = $siswa->hitungLevel();

$message = "Halo {$namaOrangTua},\n\n" .
          "Kami ingin menginformasikan bahwa {$namaSiswa} memiliki {$siswaNotWorkingCount} tantangan yang sudah MELEWATI batas waktu dan belum dikerjakan.\n\n" .
          "📊 Status:\n" .
          "• Level: {$levelSiswa}\n" .
          "• Tantangan Terlambat: {$siswaNotWorkingCount}\n\n" .
          "Mohon segera ingatkan anak Anda untuk mengejar ketertinggalan.\n\n" .
          "Terima kasih,\nSistem Gamifikasi SMP 2 Semen";
          
            self::sendViaWhatsAppTwilio($siswa->nomor_wa_orang_tua, $message);

            Log::info("Parent Notification Sent via Twilio", [
                'parent_name' => $namaOrangTua,
                'parent_wa' => $siswa->nomor_wa_orang_tua,
                'student_name' => $namaSiswa,
                'student_id' => $siswa->id,
                'uncompleted_challenges' => $siswaNotWorkingCount,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send parent notification via Twilio", [
                'student_id' => $siswa->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Kirim pesan via Twilio WhatsApp Sandbox menggunakan cURL.
     */
    public static function sendViaWhatsAppTwilio(string $phoneNumber, string $message): void
    {
        $accountSid = config('twilio.account_sid');
        $authToken = config('twilio.auth_token');
        $fromPhone = config('twilio.whatsapp_phone');

        if (!$accountSid || !$authToken || !$fromPhone) {
            Log::error("Twilio credentials not configured in .env");
            return;
        }

        try {
            $phoneFormatted = self::formatPhoneNumber($phoneNumber);

            // Twilio API endpoint
            $url = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json";

            // Prepare data
            $data = [
                'From' => $fromPhone,
                'To' => "whatsapp:{$phoneFormatted}",
                'Body' => $message,
            ];

            // Create basic auth header
            $auth = base64_encode($accountSid . ':' . $authToken);

            // Initialize cURL
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Basic ' . $auth,
                'Accept: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                Log::error("Twilio cURL Error: {$error}", [
                    'phone' => $phoneNumber,
                ]);
                return;
            }

            $result = json_decode($response, true);

            if ($httpCode >= 200 && $httpCode < 300) {
                Log::info("WhatsApp message sent successfully via Twilio", [
                    'to' => $phoneFormatted,
                    'message_sid' => $result['sid'] ?? null,
                    'timestamp' => now(),
                ]);
            } else {
                Log::error("Twilio API Error", [
                    'http_code' => $httpCode,
                    'phone' => $phoneNumber,
                    'response' => $result,
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp via Twilio: " . $e->getMessage(), [
                'phone' => $phoneNumber,
            ]);
        }
    }

    /**
     * Cek siswa yang tidak mengerjakan tantangan di bab tertentu.
     * 
     * Gunakan ini untuk notif real-time ketika siswa tidak progres.
     */
    public static function checkStudentInBab(int $siswaId, int $bab): bool
    {
        $siswa = User::find($siswaId);
        if (!$siswa || $siswa->role !== 'siswa') {
            return false;
        }

        $tantanganSelesai = NilaiTantangan::where('siswa_id', $siswaId)
            ->whereHas('tantangan', fn($q) => $q->where('bab', $bab))
            ->count();

        // Jika tidak ada tantangan yang diselesaikan di bab ini dan ada tantangan tersedia
        $totalTantanganBab = Tantangan::where('bab', $bab)
            ->where('status', 'published')
            ->count();

        return $tantanganSelesai === 0 && $totalTantanganBab > 0;
    }

    /**
     * Template WhatsApp message format untuk reference.
     * Sudah diimplementasikan dalam sendViaWhatsAppTwilio().
     */
    private static function getWhatsAppTemplate(User $siswa, int $uncompletedCount): string
    {
        $namaOrangTua = $siswa->nama_orang_tua ?? 'Orang Tua';
        $namaSiswa = $siswa->nama;
        $level = $siswa->hitungLevel();

        return "Halo {$namaOrangTua},\n\n" .
               "Kami ingin menginformasikan bahwa anak Anda {$namaSiswa} belum mengerjakan {$uncompletedCount} tantangan pembelajaran.\n\n" .
               "📊 Status:\n" .
               "• Level: {$level}\n" .
               "• Tantangan Tertunda: {$uncompletedCount}\n\n" .
               "Mohon pastikan anak Anda aktif mengerjakan tantangan untuk meningkatkan level pembelajaran.\n\n" .
               "Terima kasih,\nSistem Gamifikasi SMP 2 Semen";
    }

    /**
     * Format nomor telepon ke format WhatsApp (dengan country code).
     */
    private static function formatPhoneNumber(string $phone): string
    {
        // Hapus semua karakter selain angka
        $cleaned = preg_replace('/\D/', '', $phone);

        // Jika dimulai dengan 0, ganti dengan 62
        if (substr($cleaned, 0, 1) === '0') {
            $cleaned = '62' . substr($cleaned, 1);
        }

        // Jika belum 62, tambahkan 62
        if (substr($cleaned, 0, 2) !== '62') {
            $cleaned = '62' . $cleaned;
        }

        return $cleaned;
    }
}
