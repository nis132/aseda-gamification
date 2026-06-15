<?php

namespace App\Services;

class PointCalculationService
{
    /**
     * Hitung poin yang didapat siswa berdasarkan:
     * - Base poin dari tantangan
     * - Score/nilai siswa (0-100)
     * - Difficulty level
     * 
     * Formula:
     * - Easy: poin_didapat = basePoin * (0.5 + (nilai/100 * 0.5))
     *   Range: 50% - 100% dari base poin
     * - Medium: poin_didapat = basePoin * (0.5 + (nilai/100 * 0.5)) * 1.5
     *   Range: 75% - 150% dari base poin
     * - Hard: poin_didapat = basePoin * (0.5 + (nilai/100 * 0.5)) * 2
     *   Range: 100% - 200% dari base poin
     */
    public static function calculatePoints(int $basePoin, int $nilai, string $difficulty): int
    {
        // Pastikan nilai dalam range 0-100
        $nilai = max(0, min(100, $nilai));
        
        // Multiplier berdasarkan difficulty
        $multipliers = [
            // Chapter system (8 bab, makin tinggi makin besar reward)
            'chapter_1' => 1.0,
            'chapter_2' => 1.2,
            'chapter_3' => 1.4,
            'chapter_4' => 1.6,
            'chapter_5' => 1.8,
            'chapter_6' => 2.0,
            'chapter_7' => 2.2,
            'chapter_8' => 2.5,

            // Legacy
            'easy'   => 1.0,
            'medium' => 1.5,
            'hard'   => 2.0,
            'expert' => 2.5,
        ];
        
        $multiplier = $multipliers[$difficulty] ?? 1.0;
        
        // Perhitungan: base poin * (minimum 50% + variable hingga 100%)
        $scorePercentage = 0.5 + ($nilai / 100 * 0.5);  // 0.5 to 1.0
        $calculatedPoints = (int) round($basePoin * $scorePercentage * $multiplier);
        
        return $calculatedPoints;
    }

    /**
     * Hitung bonus poin untuk waktu yang cepat (opsional)
     * Jika siswa menyelesaikan dalam 50% dari waktu batas
     */
    public static function calculateTimeBonus(int $basePoin, ?\DateTime $startTime = null, ?\DateTime $submitTime = null): int
    {
        if (!$startTime || !$submitTime) {
            return 0;
        }

        // Bonus 10% jika selesai cepat
        return (int) round($basePoin * 0.1);
    }

    /**
     * Informasi range poin per difficulty
     */
    public static function getPointRanges(): array
    {
        return [
            'easy' => [
                'label' => 'Mudah',
                'min_multiplier' => 0.5,      // Minimum 50% dari base poin
                'max_multiplier' => 1.0,      // Maximum 100% dari base poin
            ],
            'medium' => [
                'label' => 'Sedang',
                'min_multiplier' => 0.75,     // Minimum 75% dari base poin
                'max_multiplier' => 1.5,      // Maximum 150% dari base poin
            ],
            'hard' => [
                'label' => 'Sulit',
                'min_multiplier' => 1.0,      // Minimum 100% dari base poin
                'max_multiplier' => 2.0,      // Maximum 200% dari base poin
            ],
            'expert' => [
                'label' => 'Ahli',
                'min_multiplier' => 1.25,     // Minimum 125% dari base poin
                'max_multiplier' => 2.5,      // Maximum 250% dari base poin
            ],
        ];
    }
}
