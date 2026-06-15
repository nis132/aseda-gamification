# Setup Twilio WhatsApp Sandbox untuk Notifikasi Orang Tua

## 1. Buat Akun Twilio (Gratis)
1. Ke https://www.twilio.com/
2. Sign up dengan email
3. Verify email dan phone
4. Selesai, dapat free credit $15

## 2. Setup WhatsApp Sandbox

### Via Dashboard Twilio:
1. Go to: https://console.twilio.com/
2. Navigate ke **Messaging** → **Services** → **Create a Service** (atau gunakan default Messaging)
3. Di bagian WhatsApp, pilih **Try WhatsApp**
4. Pilih **Use Sandbox**

### Setup Sandbox:
1. Akan muncul format: `whatsapp:+1234567890`
2. Copy semua ini (termasuk prefix `whatsapp:`)
3. **IMPORTANT**: Kirim pesan ke sandbox number terlebih dahulu dari WhatsApp personal
   - Message format: `join <TWO-RANDOM-WORDS>`
   - Contoh: `join kind-whale` atau `join green-tiger`
   - Cek dashboard Twilio untuk join code yang benar
4. Setelah join, bisa menerima & mengirim notifikasi

## 3. Setup di Laravel (.env)

Edit `.env` file dan tambahkan:

```bash
TWILIO_ACCOUNT_SID=AC... (copy dari Twilio Console → Account)
TWILIO_AUTH_TOKEN=... (copy dari Twilio Console → Auth Token)
TWILIO_WHATSAPP_PHONE=whatsapp:+1234567890 (dari sandbox setup)
```

### Cari Credentials:
1. https://console.twilio.com/
2. Account section → Show API Credentials
3. Copy **Account SID** dan **Auth Token**

## 4. Test Integration

### Masuk ke Tinker:
```bash
php artisan tinker
```

### Setup Test Data:
```php
$siswa = User::where('role', 'siswa')->first();
$siswa->update([
    'nama_orang_tua' => 'Budi Santoso',
    'nomor_wa_orang_tua' => '08123456789',  // nomor HP personal
    'email_orang_tua' => 'budi@email.com'
]);
exit
```

### Trigger Command:
```bash
php artisan notify:inactive-students
```

### Lihat Log:
```bash
tail -f storage/logs/laravel.log
```

Akan terlihat:
```
[2026-05-28 10:30:00] local.INFO: WhatsApp message sent successfully via Twilio {
  "to": "628123456789",
  "timestamp": "2026-05-28T10:30:00+07:00"
}
```

## 5. Testing Skenario Lengkap

### Scenario 1: Siswa Tidak Mengerjakan 3+ Tantangan
```php
// Di tinker
$siswa = User::where('role', 'siswa')->first();
$siswa->update(['nomor_wa_orang_tua' => '08123456789']);

// Trigger notifikasi
ParentNotificationService::checkAndNotifyInactiveStudents();
```

### Scenario 2: Trigger Manual untuk Siswa Tertentu
```php
$siswa = User::find(1);
ParentNotificationService::notifyParent($siswa, 5); // Notif ada 5 tantangan belum dikerjakan
```

### Scenario 3: Check Siswa di Bab Tertentu
```php
$result = ParentNotificationService::checkStudentInBab(1, 3);
// Return: true jika tidak ada tantangan di bab 3, false jika sudah ada
```

## 6. Production Setup

Untuk production (non-sandbox):
1. Upgrade Twilio account ke paid
2. Buy WhatsApp Business Account verification
3. Update credentials ke production API keys
4. Setup dalam `app/Console/Kernel.php` untuk schedule otomatis

### Setup Scheduler:
Edit `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('notify:inactive-students')
        ->dailyAt('19:00')  // Kirim setiap hari jam 7 malam
        ->timezone('Asia/Jakarta');
}
```

Jalankan:
```bash
php artisan schedule:work  # untuk development
# atau di production: setup cron job yang jalankan "php artisan schedule:run"
```

## 7. Troubleshooting

### Error: "Twilio credentials not configured"
- Pastikan `.env` sudah ada 3 variable:
  - `TWILIO_ACCOUNT_SID`
  - `TWILIO_AUTH_TOKEN`
  - `TWILIO_WHATSAPP_PHONE`

### Error: "Failed to send message"
- Pastikan nomor hape yang menerima sudah join sandbox
- Cek format nomor: harus +62XXXXXXXXXX (replace 0 dengan 62)
- Cek log untuk error message detail: `tail storage/logs/laravel.log`

### Nomor tidak valid
- Format harus bisa diformat ke +62
- Cek di formatter: `ParentNotificationService::formatPhoneNumber('08123456789')`
- Hasilnya harus: `628123456789`

## 8. Limit & Info

- **Sandbox**: Max 1 verified number (HP personal yang join)
- **Free credit**: $15 untuk testing
- **Cost**: ~$0.0075 per message outbound (bisa berbeda per region)
- **Sandbox timeout**: Auto logout setelah 3 hari inactivity
