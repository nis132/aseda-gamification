<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tantangan extends Model
{
    use HasFactory;

    protected $table = 'tantangan';
    
    // ✅ SESUAI DATABASE
    protected $fillable = [
        'judul', 'deskripsi', 'mapel_id', 'guru_id', 'kelas_id', 
        'tipe', 'batas_waktu', 'poin', 'status'
    ];

    protected $casts = [
        'batas_waktu' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // RELATIONSHIPS
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id'); // Asumsi guru = user
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function soal()
    {
        return $this->hasMany(Soal::class, 'tantangan_id');
        // BUKAN belongsToMany() → hasMany()
    }

    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswa::class, 'tantangan_id');
    }

    public function nilaiTantangan()
    {
        return $this->hasMany(NilaiTantangan::class, 'tantangan_id');
    }

    // SCOPES
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
