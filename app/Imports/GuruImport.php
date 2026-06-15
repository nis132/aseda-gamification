<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Mapel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class GuruImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function model(array $row)
    {
        $user = new User([
            'nama'     => $row['nama'],
            'nip'      => $row['nip'] ?? null,
            'username' => $row['username'],
            'password' => Hash::make($row['password']),
            'role'     => 'guru',
        ]);

        return $user;
    }

    public function rules(): array
    {
        return [
            'nama'     => 'required|string|max:255',
            'nip'      => 'required|unique:users,nip',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
        ];
    }
}
