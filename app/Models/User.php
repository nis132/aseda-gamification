<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; // Perbaiki ini
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use Notifiable, HasPushSubscriptions;

    protected $fillable = [
        'nama', 'username', 'password', 'role', 'total_poin', 'level'
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
}
