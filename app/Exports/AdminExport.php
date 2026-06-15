<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class AdminExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            // kosong → hanya template
        ];
    }

public function headings(): array
{
    return [
        'nama',
        'username',
        'password',
        'role',
        'level'
    ];
}
}