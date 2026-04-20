<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class GuruExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            // contoh isi biar user paham
            ['M. Yahya', 'm_yahya', 'pass123', 'Matematika', 0, 1]
        ];
    }

    public function headings(): array
    {
        return [
            'nama',
            'username',
            'password',
            'mapel', // 🔥 ganti dari mapel_id
            'total_poin',
            'level'
        ];
    }
}