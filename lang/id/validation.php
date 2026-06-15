<?php

return [
    'accepted'        => ':attribute harus diterima.',
    'active_url'      => ':attribute bukan URL yang valid.',
    'after'           => ':attribute harus tanggal setelah :date.',
    'alpha'           => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'      => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'       => ':attribute hanya boleh berisi huruf dan angka.',
    'array'           => ':attribute harus berupa array.',
    'before'          => ':attribute harus tanggal sebelum :date.',
    'between'         => [
        'numeric' => ':attribute harus di antara :min dan :max.',
        'file'    => ':attribute harus di antara :min dan :max kilobytes.',
        'string'  => ':attribute harus di antara :min dan :max karakter.',
        'array'   => ':attribute harus di antara :min dan :max item.',
    ],
    'boolean'         => ':attribute harus bernilai true atau false.',
    'confirmed'       => 'Konfirmasi :attribute tidak sesuai.',
    'date'            => ':attribute bukan tanggal yang valid.',
    'date_format'     => ':attribute tidak sesuai format :format.',
    'different'       => ':attribute dan :other harus berbeda.',
    'digits'          => ':attribute harus :digits digit.',
    'digits_between'  => ':attribute harus di antara :min dan :max digit.',
    'email'           => ':attribute harus alamat email yang valid.',
    'exists'          => ':attribute tidak ditemukan.',
    'filled'          => ':attribute wajib diisi.',
    'image'           => ':attribute harus berupa gambar.',
    'in'              => ':attribute yang dipilih tidak valid.',
    'integer'         => ':attribute harus berupa angka bulat.',
    'ip'              => ':attribute harus alamat IP yang valid.',
    'max'             => [
        'numeric' => ':attribute maksimal :max.',
        'file'    => ':attribute maksimal :max kilobytes.',
        'string'  => 'Maksimal :max karakter.',
        'array'   => ':attribute maksimal :max item.',
    ],
    'min'             => [
        'numeric' => ':attribute minimal :min.',
        'file'    => ':attribute minimal :min kilobytes.',
        'string'  => 'Minimal :min karakter.',
    ],
    'not_in'          => ':attribute yang dipilih tidak valid.',
    'numeric'         => ':attribute harus berupa angka.',
    'required'        => 'Kolom ini wajib diisi.',
    'unique'          => 'Data sudah digunakan.',
    'url'             => 'Format URL :attribute tidak valid.',

    'custom' => [
        'username' => [
            'required' => 'Username tidak boleh kosong untuk masuk ke realm.',
        ],
        'password' => [
            'required' => 'Password wajib diisi agar akses terbuka.',
        ],
    ],

    'attributes' => [
        'nama_kelas' => 'Nama Kelas',
        'nama_mapel' => 'Mata Pelajaran',
        'guru_id'    => 'Guru',
    ],
];