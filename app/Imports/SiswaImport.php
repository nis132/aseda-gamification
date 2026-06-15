<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use App\Models\Kelas;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function model(array $row)
    {
        if (
            empty($row['nama']) &&
            empty($row['username']) &&
            empty($row['password'])
        ) {
            return null;
        }

        $kelasNama = trim(strtolower($row['kelas']));
        $kelas = \App\Models\Kelas::whereRaw('LOWER(nama_kelas) = ?', [$kelasNama])->first();

        if (!$kelas) {
            throw new \Exception("Kelas '{$row['kelas']}' tidak ditemukan");
        }

        $user = User::create([
            'nama'     => $row['nama'],
            'nis'      => $row['nis'] ?? null,
            'username' => $row['username'],
            'password' => Hash::make($row['password']),
            'role'     => 'siswa',
            'level'    => $row['level'] ?? 1,
        ]);

        $user->kelas()->attach($kelas->id);

        return $user;
    }

    public function rules(): array
    {
        return [
            'nama'     => 'required',
            'nis'      => 'required|unique:users,nis',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'kelas'    => 'required',
        ];
    }
}
