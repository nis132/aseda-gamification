<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaBadge extends Model
{
    protected $table = 'siswa_badge';

    protected $fillable = [
        'siswa_id',
        'badge_id',
        'tantangan_id', // Tambahkan ini
        'is_new',       // Tambahkan ini
        'diterima_pada'
    ];

    // Relasi ke Badge
    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    // Tambahkan relasi ke Tantangan agar data lebih lengkap
    public function tantangan()
    {
        return $this->belongsTo(Tantangan::class);
    }
}