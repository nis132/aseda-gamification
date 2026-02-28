<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Mapel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GuruImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $user = User::create([
            'nama' => $row['nama'],
            'username' => $row['username'],
            'password' => Hash::make($row['password']),
            'role' => 'guru',
            'total_poin' => 0,
            'level' => 1,
        ]);

        $mapel = Mapel::where('nama_mapel', $row['mata_pelajaran'])->first();

        if ($mapel) {
            $user->mapel()->attach($mapel->id);
        }

        return $user;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'mata_pelajaran' => 'required'
        ];
    }
}