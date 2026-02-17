<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tantangan extends Model
{
    protected $table = 'tantangan';
    protected $fillable = [
        'judul', 'deskripsi', 'mapel_id', 'guru_id', 'kelas_id', 
        'tipe', 'batas_waktu', 'poin'
    ];

    protected $casts = [
        'batas_waktu' => 'datetime'
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function soal()
    {
        return $this->hasMany(Soal::class);
    }


    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswa::class);
    }

    public function nilaiTantangan()
    {
        return $this->hasMany(NilaiTantangan::class);
    }

}
