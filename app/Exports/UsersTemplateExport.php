<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class UsersTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['nama', 'username', 'email', 'role', 'password'], // header
            ['John Doe', 'johndoe', 'john@admin.com', 'admin', '123456'], // contoh
        ];
    }
}
