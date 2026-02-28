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

    public function siswa()
    {
        return $this->belongsToMany(
            \App\Models\User::class,
            'siswa_kelas',
            'kelas_id',
            'siswa_id'
        );
    }

}
