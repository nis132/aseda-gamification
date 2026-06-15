<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; // Perbaiki ini
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use Notifiable, HasPushSubscriptions;

    protected $fillable = [
        'nama', 'nis', 'nip', 'username', 'password', 'role', 'total_poin', 'level',
        'nama_orang_tua', 'nomor_wa_orang_tua', 'email_orang_tua'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isAdmin() { return $this->hasRole('admin'); }
    public function isGuru() { return $this->hasRole('guru'); }
    public function isSiswa() { return $this->hasRole('siswa'); }

    public function hitungLevel(?int $kelasId = null): int
    {
        $kelasId = $kelasId ?? $this->kelasIds()->first();
        $level   = 1;
 
        for ($bab = 1; $bab <= 8; $bab++) {
            // Ambil tantangan reguler (bukan pengayaan) di bab ini
            $tantanganBab = \App\Models\Tantangan::where('bab', $bab)
                ->where('is_pengayaan', 0)
                ->where('kelas_id', $kelasId)
                ->where('status', 'published')
                ->get(['id', 'batas_waktu']);
 
            if ($tantanganBab->isEmpty()) {
                break; // tidak ada tantangan di bab ini → berhenti
            }
 
            // Hitung yang sudah dikerjakan (ada NilaiTantangan)
            $dikerjakanIds = \App\Models\NilaiTantangan::where('siswa_id', $this->id)
                ->whereIn('tantangan_id', $tantanganBab->pluck('id'))
                ->pluck('tantangan_id')
                ->toArray();
 
            $selesaiAsli = count($dikerjakanIds);
 
            $selesaiPengayaan = \App\Models\NilaiTantangan::where('siswa_id', $this->id)
                ->whereHas('tantangan', fn($q) => $q
                    ->where('bab', $bab)
                    ->where('is_pengayaan', 1)
                    ->where('kelas_id', $kelasId)
                )->count();

            $totalBab   = $tantanganBab->count();
            $expiredBab = $tantanganBab->filter(
                fn($t) => $t->batas_waktu && $t->batas_waktu <= now()
            )->count();

            $babSelesai = ($selesaiAsli + $expiredBab) >= $totalBab
                    || $selesaiAsli >= 3
                    || $selesaiPengayaan > 0;
 
            if ($babSelesai) {
                $level = min($bab + 1, 8);
            } else {
                break;
            }
        }
 
        return $level;
    }

    public function getNextLevelRequirement(): array
    {
        $currentLevel = $this->hitungLevel();
        $targetBab    = $currentLevel; // Bab yang sedang dikerjakan = level saat ini
        $nextLevel    = min($currentLevel + 1, 8);

        // Hitung berapa banyak tantangan di bab saat ini yang sudah diselesaikan
        $tantanganSelesai = \App\Models\NilaiTantangan::where('siswa_id', $this->id)
            ->whereHas('tantangan', fn($q) => $q->where('bab', $targetBab))
            ->count();

        // Hitung total tantangan yang tersedia di bab saat ini
        $totalTantangan = \App\Models\Tantangan::where('bab', $targetBab)->count();

        // Materi sudah tidak penting untuk level up (hanya info)
        $materiSelesai = \App\Models\MateriSelesai::where('siswa_id', $this->id)
            ->whereHas('materi', fn($q) => $q->where('bab', $targetBab))
            ->count();

        return [
            'currentLevel'      => $currentLevel,
            'nextLevel'         => $nextLevel,
            'targetBab'         => $targetBab,
            'materiSelesai'     => $materiSelesai,
            'tantanganSelesai'  => $tantanganSelesai,
            'materiNeeded'      => 0, // Materi tidak perlu untuk level up
            'tantanganNeeded'   => 3, // Perlu 3 tantangan minimum untuk naik level
            'materiProgress'    => 100, // Materi tidak ada syarat
            'tantanganProgress' => $totalTantangan > 0 ? min(100, ($tantanganSelesai / $totalTantangan) * 100) : 0,
        ];
    }

    public function mapel()
    {
        return $this->belongsToMany(Mapel::class, 'guru_mapel', 'guru_id', 'mapel_id');
    }

public function guruMapel()
{
    return $this->hasMany(GuruMapel::class, 'guru_id');
}

public function tantangan()
{
    return $this->hasMany(Tantangan::class, 'guru_id');
}

public function materi()
{
    return $this->hasMany(Materi::class, 'guru_id');
}
public function jawabanSiswa()
{
    return $this->hasMany(JawabanSiswa::class, 'siswa_id');
}

public function nilaiTantangan()
{
    return $this->hasMany(NilaiTantangan::class, 'siswa_id');
}

public function kelasIds()
{
    return SiswaKelas::where('siswa_id', $this->id)->pluck('kelas_id');
}

public function siswaKelas()
{
    return $this->hasMany(SiswaKelas::class, 'siswa_id');
}
public function badge()
{
    return $this->belongsTo(Badge::class);
}
public function kelas()
{
    return $this->belongsToMany(Kelas::class, 'siswa_kelas', 'siswa_id', 'kelas_id');
}

public function mengajar()
{
    return $this->hasMany(GuruMapelKelas::class, 'guru_id');
}
}