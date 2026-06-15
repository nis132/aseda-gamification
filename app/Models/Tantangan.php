<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NilaiTantangan;

class Tantangan extends Model
{
    use HasFactory;

    protected $table = 'tantangan';
    
    protected $fillable = [
        'judul', 'deskripsi', 'mapel_id', 'guru_id', 'kelas_id',
        'tipe', 'batas_waktu', 'poin', 'status', 'urutan', 'difficulty', 'bab'
    ];

    /**
     * Level minimum yang dibutuhkan untuk tiap difficulty.
     * 8 BAB = Level 1-8
     */
    public static function levelRequired(): array
    {
        return [
            'chapter_1' => 1,   // Bab 1 = Level 1
            'chapter_2' => 2,   // Bab 2 = Level 2
            'chapter_3' => 3,   // Bab 3 = Level 3
            'chapter_4' => 4,   // Bab 4 = Level 4
            'chapter_5' => 5,   // Bab 5 = Level 5
            'chapter_6' => 6,   // Bab 6 = Level 6
            'chapter_7' => 7,   // Bab 7 = Level 7
            'chapter_8' => 8,   // Bab 8 = Level 8
            
            // Legacy support
            'easy'   => 1,
            'medium' => 2,
            'hard'   => 3,
            'expert' => 4,
        ];
    }

    /**
     * Label tampilan difficulty beserta warna badge (8 BAB).
     */
    public static function difficultyConfig(): array
    {
        return [
            'chapter_1' => ['label' => 'Bab 1 - Pengantar',     'color' => '#d1fae5', 'text' => '#065f46', 'icon' => 'fa-book', 'level' => 1],
            'chapter_2' => ['label' => 'Bab 2 - Dasar',         'color' => '#dcfce7', 'text' => '#166534', 'icon' => 'fa-book', 'level' => 2],
            'chapter_3' => ['label' => 'Bab 3 - Pengembangan',  'color' => '#dbeafe', 'text' => '#0c2d48', 'icon' => 'fa-book', 'level' => 3],
            'chapter_4' => ['label' => 'Bab 4 - Pendalaman',    'color' => '#e0e7ff', 'text' => '#3730a3', 'icon' => 'fa-book', 'level' => 4],
            'chapter_5' => ['label' => 'Bab 5 - Integrasi',     'color' => '#ede9fe', 'text' => '#5b21b6', 'icon' => 'fa-book', 'level' => 5],
            'chapter_6' => ['label' => 'Bab 6 - Aplikasi',      'color' => '#fce7f3', 'text' => '#9d174d', 'icon' => 'fa-book', 'level' => 6],
            'chapter_7' => ['label' => 'Bab 7 - Analisis',      'color' => '#fee2e2', 'text' => '#7c2d12', 'icon' => 'fa-book', 'level' => 7],
            'chapter_8' => ['label' => 'Bab 8 - Mastery',       'color' => '#fef3c7', 'text' => '#92400e', 'icon' => 'fa-crown', 'level' => 8],
            
            // Legacy
            'easy'   => ['label' => 'Mudah',   'color' => '#d1fae5', 'text' => '#065f46', 'icon' => 'fa-seedling', 'level' => 1],
            'medium' => ['label' => 'Sedang',  'color' => '#dbeafe', 'text' => '#1e40af', 'icon' => 'fa-fire-alt', 'level' => 2],
            'hard'   => ['label' => 'Sulit',   'color' => '#fef3c7', 'text' => '#92400e', 'icon' => 'fa-dragon', 'level' => 3],
            'expert' => ['label' => 'Ahli',    'color' => '#fce7f3', 'text' => '#9d174d', 'icon' => 'fa-crown', 'level' => 4],
        ];
    }

    /**
     * Cek apakah tantangan ini terkunci berdasarkan level siswa.
     *
     * Aturan lock:
     *  - tipe 'uts'     : butuh level >= 4 (Bab 1–4 sudah selesai)
     *  - tipe 'uas'     : butuh level >= 8 (semua Bab sudah selesai)
     *  - bab kosong/null dengan judul mengandung UTS/UAS → pakai judul sebagai pendeteksi
     *  - tipe 'reguler' : Bab N butuh level >= N (Bab 1 selalu terbuka)
     *
     * Level siswa naik setelah menyelesaikan minimal 3 tantangan per-bab
     * (lihat User::hitungLevel). Pengayaan tidak di-lock karena sudah
     * difilter di controller sebelum metode ini dipanggil.
     */
    public function isBabLockedFor(int $siswaLevel): bool
    {
        $tipe = $this->tipe ?? 'reguler';

        // Deteksi UTS/UAS dari tipe ATAU dari bab kosong + judul
        // (untuk data lama yang belum di-update tipe-nya di DB)
        $babVal = (string) ($this->bab ?? '');
        if ($tipe === 'uts' || ($babVal === '' && stripos($this->judul ?? '', 'UTS') !== false)) {
            return $siswaLevel < 4;
        }

        if ($tipe === 'uas' || ($babVal === '' && stripos($this->judul ?? '', 'UAS') !== false)) {
            return $siswaLevel < 8;
        }

        // Reguler: lock per-bab
        if ($babVal === '' || !is_numeric($babVal)) {
            return false; // bab tidak dikenali → tidak di-lock
        }

        $babIni = (int) $babVal;

        // Bab 1 selalu terbuka
        if ($babIni <= 1) {
            return false;
        }

        // Bab N butuh level >= N
        return $siswaLevel < $babIni;
    }

    /**
     * Kembalikan level minimum yang dibutuhkan untuk tantangan ini.
     * Berguna untuk ditampilkan di UI (tooltip / badge lock).
     */
    public function levelMinimum(): int
    {
        $tipe   = $this->tipe ?? 'reguler';
        $babVal = (string) ($this->bab ?? '');

        if ($tipe === 'uts' || ($babVal === '' && stripos($this->judul ?? '', 'UTS') !== false)) {
            return 4;
        }
        if ($tipe === 'uas' || ($babVal === '' && stripos($this->judul ?? '', 'UAS') !== false)) {
            return 8;
        }

        return max(1, (int) $babVal);
    }

    /**
     * Cek apakah tantangan ini terkunci karena tantangan sebelumnya dalam BAB yang sama
     * belum diselesaikan. Urutan dihitung per-bab (1, 2, 3 dalam setiap bab).
     * 
     * PERBAIKAN: Jika tantangan sebelumnya belum selesai tapi ada pengayaan dan
     * pengayaan sudah diselesaikan, dianggap tidak locked.
     */
    public function isLockedFor(int $siswaId): bool
    {
        if (!$this->urutan || $this->urutan <= 1) {
            return false; // urutan pertama dalam bab selalu terbuka
        }

        // Cari tantangan sebelumnya dalam BAB yang SAMA (urutan tepat di bawah)
        $sebelumnya = self::where('mapel_id', $this->mapel_id)
            ->where('kelas_id', $this->kelas_id)
            ->where('bab', $this->bab)
            ->where('urutan', $this->urutan - 1)
            ->where('status', 'published')
            ->first();

        if (!$sebelumnya) {
            return false;
        }

        // Cek apakah tantangan sebelumnya sudah selesai ATAU sudah expired
        $sudahSelesai = NilaiTantangan::where('siswa_id', $siswaId)
            ->where('tantangan_id', $sebelumnya->id)
            ->exists();

        if ($sudahSelesai) {
            return false; // Sudah dikerjakan → tidak locked
        }

        // Jika sebelumnya sudah expired (waktu habis), siswa tidak perlu menunggu
        // → dianggap "dilewati otomatis", task berikutnya terbuka
        if ($sebelumnya->batas_waktu && $sebelumnya->batas_waktu <= now()) {
            return false;
        }

        // Jika belum selesai, cek apakah ada pengayaan dan pengayaan sudah selesai
        $pengayaan = self::where('parent_tantangan_id', $sebelumnya->id)
            ->where('is_pengayaan', 1)
            ->first();
        
        if ($pengayaan) {
            $pengayaanSelesai = NilaiTantangan::where('siswa_id', $siswaId)
                ->where('tantangan_id', $pengayaan->id)
                ->exists();

            if ($pengayaanSelesai) {
                return false; // Pengayaan sudah selesai → tidak locked
            }
        }

        return true; // Belum selesai dan tidak ada pengayaan yang selesai → locked
    }

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

    public function publishKelas()
    {
        return $this->hasMany(TantanganKelas::class, 'tantangan_id');
    }

    public function parentTantangan()
    {
        return $this->belongsTo(Tantangan::class, 'parent_tantangan_id');
    }

   public function remedial(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Tantangan::class, 'parent_tantangan_id')
                    ->where('is_remedial', 1);
    }
 
    /**
     * Relasi ke pengayaan (satu tantangan → satu pengayaan).
     * (Tidak berubah dari sebelumnya, hanya pastikan filter is_pengayaan=1)
     */
    public function pengayaan(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Tantangan::class, 'parent_tantangan_id')
                    ->where('is_pengayaan', 1);
    }
 
    /**
     * Apakah siswa berhak dapat remedial?
     * Kondisi: expired tanpa mengerjakan ATAU nilai < 60.
     */
    public function butuhRemedialFor(int $siswaId): bool
    {
        $nilai = \App\Models\NilaiTantangan::where('siswa_id', $siswaId)
            ->where('tantangan_id', $this->id)
            ->first();
 
        // Expired & belum dikerjakan
        if (!$nilai && $this->batas_waktu && $this->batas_waktu <= now()) {
            return true;
        }
 
        // Sudah dikerjakan tapi nilai < 60
        if ($nilai && $nilai->total_nilai < 60) {
            return true;
        }
 
        return false;
    }
}