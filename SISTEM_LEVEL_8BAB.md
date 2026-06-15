# Sistem Level 8 BAB - Sinkronisasi Materi & Tantangan

## Struktur Sistem

**Total: 8 Level = 8 BAB**

Setiap BAB memiliki:
- Materi (minimal 1)
- Tantangan (minimal 1)
- Level requirement sama

### Mapping BAB → Level

| BAB | Level | Difficulty | Requirement |
|-----|-------|-----------|-------------|
| BAB 1 | Level 1 | `chapter_1` | Materi ≥ 1 & Tantangan ≥ 1 |
| BAB 2 | Level 2 | `chapter_2` | Materi ≥ 2 & Tantangan ≥ 2 |
| BAB 3 | Level 3 | `chapter_3` | Materi ≥ 3 & Tantangan ≥ 3 |
| BAB 4 | Level 4 | `chapter_4` | Materi ≥ 4 & Tantangan ≥ 4 |
| BAB 5 | Level 5 | `chapter_5` | Materi ≥ 5 & Tantangan ≥ 5 |
| BAB 6 | Level 6 | `chapter_6` | Materi ≥ 6 & Tantangan ≥ 6 |
| BAB 7 | Level 7 | `chapter_7` | Materi ≥ 7 & Tantangan ≥ 7 |
| BAB 8 | Level 8 | `chapter_8` | Materi ≥ 8 & Tantangan ≥ 8 |

---

## Cara Menaikkan Level

### Level Calculation
```php
$completedPairs = min($materiSelesai, $tantanganSelesai);

if ($completedPairs >= 8) return 8;
if ($completedPairs >= 7) return 7;
// ... dst
return 1;
```

### Contoh Skenario

**Siswa A:**
- Materi selesai: 3 (BAB 1, 2, 3)
- Tantangan selesai: 2 (BAB 1, 2)
- **Level = 2** (minimal dari 3 & 2)

**Siswa B:**
- Materi selesai: 5 (BAB 1-5)
- Tantangan selesai: 5 (BAB 1-5)
- **Level = 5** (minimal dari 5 & 5)

---

## Fitur di Halaman Profil

Siswa dapat melihat:
1. **Level Saat Ini** (1-8)
2. **Progress Bar** Materi (0-8)
3. **Progress Bar** Tantangan (0-8)
4. **Requirement Level Berikutnya** dengan jelas
   - "Butuh 3 Materi & 3 Tantangan untuk Level 3"
5. **Indikator 8 Level** dengan warna gradient

---

## Flow Untuk Guru

### Membuat Materi
1. Tentukan BAB (BAB 1-8)
2. Sistem otomatis: BAB 1 = Level 1
3. Siswa butuh Level 1 untuk akses

### Membuat Tantangan
1. Pilih **Difficulty** = chapter_1 s/d chapter_8
2. chapter_1 = Level 1, chapter_2 = Level 2, dst
3. Pilih **BAB** yang sama untuk grouping
4. Siswa butuh Level sesuai chapter untuk mengerjakan

---

## Implementasi Teknis

### Model: User.php
```php
// Hitung level berdasarkan pasangan materi-tantangan
public function hitungLevel(): int
{
    $completedPairs = min(
        MateriSelesai::where('siswa_id', $this->id)->count(),
        NilaiTantangan::where('siswa_id', $this->id)->count()
    );
    
    return min($completedPairs, 8); // Max level 8
}

// Info untuk level berikutnya
public function getNextLevelRequirement(): array
{
    $nextLevel = min($this->hitungLevel() + 1, 8);
    // return data progress
}
```

### Model: Tantangan.php
```php
public static function levelRequired(): array
{
    return [
        'chapter_1' => 1,
        'chapter_2' => 2,
        // ... dst
        'chapter_8' => 8,
    ];
}
```

### Controller: TantanganController.php
```php
// Validate difficulty hanya 8 pilihan
'difficulty' => 'in:chapter_1,chapter_2,...,chapter_8'
```

---

## Level Metadata

| Level | Label | Icon | Warna |
|-------|-------|------|-------|
| 1 | Pemula Paripurna | seedling | #4CAF50 |
| 2 | Pelajar Dasar | sprout | #8BC34A |
| 3 | Pelajar Maju | leaf | #CDDC39 |
| 4 | Pandai | lightbulb | #FFC107 |
| 5 | Pandai Luar Biasa | star | #FF9800 |
| 6 | Cendekia | brain | #FF5722 |
| 7 | Cendekia Unggul | bolt | #E91E63 |
| 8 | Mahir Mastery | fire | #9C27B0 |

---

## Checklist Implementasi

- [x] Update `hitungLevel()` di User model (max 8)
- [x] Update `getNextLevelRequirement()` method
- [x] Update Tantangan difficulty config (8 chapter)
- [x] Update form guru tantangan (8 BAB + 8 difficulty)
- [x] Update profil siswa (indikator 8 level)
- [x] Update validation di controller (chapter_1-8)
- [x] Update view profil dengan penjelasan level

---

## Testing

### Test Case 1: Siswa Level 1
- Materi selesai: 1
- Tantangan selesai: 1
- Expected: Level 1 ✓

### Test Case 2: Siswa Pending Level 3
- Materi selesai: 3
- Tantangan selesai: 2
- Expected: Level 2 (tunggu tantangan ke-3) ✓

### Test Case 3: Siswa Maksimal
- Materi selesai: 8
- Tantangan selesai: 8
- Expected: Level 8 ✓
