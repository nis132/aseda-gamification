<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    protected $table = 'leaderboard';
    
    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'total_poin',
        'rank',
        'level'
    ];

    protected $casts = [
        'total_poin' => 'integer',
        'rank' => 'integer',
        'level' => 'integer'
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
