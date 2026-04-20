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
        // cari mapel berdasarkan nama
        $mapel = Mapel::where('nama_mapel', $row['mapel'])->first();

        $user = new User([
            'nama'       => $row['nama'],
            'username'   => $row['username'],
            'password'   => Hash::make($row['password']),
            'role'       => 'guru',
            'total_poin' => $row['total_poin'] ?? 0,
            'level'      => $row['level'] ?? 1,
        ]);

        $user->save();

        // attach ke pivot (many-to-many)
        if ($mapel) {
            $user->mapel()->attach($mapel->id);
        }

        return $user;
    }

    public function rules(): array
    {
        return [
            'nama'     => 'required|string|max:255',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'mapel'    => 'required|exists:mapel,nama_mapel', // 🔥 validasi nama
        ];
    }
}