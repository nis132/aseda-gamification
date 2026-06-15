<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TantanganKelas extends Model
{
    protected $table = 'tantangan_kelas';

    protected $fillable = [
        'tantangan_id',
        'kelas_id',
        'guru_id',
        'batas_waktu',
        'status'
    ];

    protected $casts = [
        'batas_waktu' => 'datetime'
    ];

    public function tantangan()
    {
        return $this->belongsTo(Tantangan::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class);
    }

}