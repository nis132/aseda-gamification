<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class SiswaExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [];
    }

public function headings(): array
{
    return [
        'nama',
        'username',
        'password',
        'role',
        'mapel_id',
        'kelas_id',
        'total_poin',
        'level'
    ];
}
}