<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $user = User::create([
            'nama' => $row['nama'],
            'username' => $row['username'],
            'password' => Hash::make($row['password']),
            'role' => 'siswa',
            'total_poin' => 0,
            'level' => 1,
        ]);

        $kelas = Kelas::where('nama_kelas', $row['kelas'])->first();

        if ($kelas) {
            $user->kelas()->attach($kelas->id);
        }

        return $user;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'kelas' => 'required'
        ];
    }
}