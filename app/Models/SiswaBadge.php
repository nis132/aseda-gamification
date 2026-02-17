<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaBadge extends Model
{
    protected $table = 'siswa_badge';

    protected $fillable = [
        'siswa_id',
        'badge_id',
        'diterima_pada'
    ];

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
