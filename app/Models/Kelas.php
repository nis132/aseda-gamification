<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelas',
        'jumlah_siswa',
        'keterangan'
    ];

    // Relasi ke User (Siswa & Guru)
    public function siswa()
    {
        return $this->hasMany(User::class, 'kelas', 'nama');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'wali_kelas', 'nama');
    }
}
