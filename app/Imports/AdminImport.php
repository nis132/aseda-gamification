<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AdminImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new User([
            'nama' => $row['nama'],
            'username' => $row['username'],
            'password' => Hash::make($row['password']),
            'role' => 'admin',
            'level' => 1,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
        ];
    }
}