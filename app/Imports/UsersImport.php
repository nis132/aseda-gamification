<?php
namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Hash;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new User([
            'nama' => $row['nama'],
            'username' => $row['username'],
            'password' => Hash::make($row['username']), // Password = username default
            'role' => $row['role'], // admin/guru/siswa
            'total_poin' => $row['total_poin'] ?? 0,
            'level' => $row['level'] ?? 1,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'role' => 'required|in:admin,guru,siswa',
            'total_poin' => 'nullable|integer|min:0|max:999999',
            'level' => 'nullable|integer|min:1|max:100',
        ];
    }
}
