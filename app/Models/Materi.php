<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';
    protected $fillable = [
        'judul', 'deskripsi', 'file_url', 'video_url', 'link_referensi',
        'guru_id', 'mapel_id', 'kelas_id', 'level_required', 'bab'
    ];

    /**
     * DEPRECATED: Materi sudah tidak di-lock per bab.
     * Semua materi adalah hak semua siswa untuk belajar.
     * 
     * Return false untuk semua siswa (tidak ada lock).
     */
    public function isBabLockedFor(int $siswaId): bool
    {
        return false; // Materi tidak terkunci lagi
    }

    /**
     * DEPRECATED: Materi sudah tidak di-lock per urutan.
     * Semua materi adalah hak semua siswa untuk belajar.
     * 
     * Return false untuk semua siswa (tidak ada lock).
     */
    public function isLockedFor(int $siswaId): bool
    {
        return false; // Materi tidak terkunci lagi
    }

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function kelas() 
    { 
        return $this->belongsTo(Kelas::class, 'kelas_id'); 
    }

    /**
     * Konversi URL YouTube biasa ke format embed.
     * Mendukung: youtube.com/watch?v=xxx, youtu.be/xxx, youtube.com/shorts/xxx
     */
    public function youtubeEmbedUrl(): ?string
    {
        if (!$this->video_url) return null;

        $url = trim($this->video_url);
        $videoId = null;

        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $m)) {
            $videoId = $m[1];
        } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $m)) {
            $videoId = $m[1];
        } elseif (preg_match('/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/', $url, $m)) {
            $videoId = $m[1];
        } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $m)) {
            $videoId = $m[1];
        }

        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
    }
}