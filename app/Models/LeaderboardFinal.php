<?php
// app/Models/LeaderboardFinal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaderboardFinal extends Model
{
    protected $table = 'leaderboard_final';

    protected $fillable = [
        'kelas_id',
        'siswa_id',
        'total_poin',
        'jumlah_selesai',
        'rank',
        'periode',
        'di_kunci_oleh',
        'dikunci_pada',
    ];

    protected $casts = [
        'total_poin'    => 'integer',
        'jumlah_selesai'=> 'integer',
        'rank'          => 'integer',
        'dikunci_pada'  => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function dikunciOleh()
    {
        return $this->belongsTo(User::class, 'di_kunci_oleh');
    }
}
