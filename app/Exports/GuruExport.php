<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class GuruExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['35060110','M. Yahya', 'm_yahya', 'password']
        ];
    }

    public function headings(): array
    {
        return [
            'nip',
            'nama',
            'username',
            'password'
        ];
    }
}