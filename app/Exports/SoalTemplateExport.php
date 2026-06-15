<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class SoalTemplateExport implements FromArray
{
    protected $tipe;

    public function __construct($tipe)
    {
        $this->tipe = $tipe;
    }

    public function array(): array
    {
        if ($this->tipe === 'pg') {
            return [
                ['pertanyaan', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'jawaban_benar'],
                ['Ibukota Indonesia?', 'Jakarta', 'Bandung', 'Surabaya', 'Medan', 'A'],
            ];
        }

        if ($this->tipe === 'essay') {
            return [
                ['pertanyaan', 'jawaban_benar'],
                ['Jelaskan pengertian ekosistem', 'Hubungan makhluk hidup dengan lingkungan'],
            ];
        }

        return [
            ['pertanyaan', 'kiri_items', 'kanan_items'],
            ['Jodohkan buah dengan warna', 'Apel,Jeruk,Pisang', 'Merah,Oranye,Kuning'],
        ];
    }
}
