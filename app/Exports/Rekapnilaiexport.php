<?php

namespace App\Exports;

use App\Models\NilaiTantangan;
use App\Models\Tantangan;
use App\Models\User;
use App\Models\SiswaKelas;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RekapNilaiExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $tantanganList;
    protected $siswaList;
    protected $nilaiMap;
    protected $namaKelas;
    protected $namaMapel;

    public function __construct($tantanganList, $siswaList, $nilaiMap, $namaKelas, $namaMapel)
    {
        $this->tantanganList = $tantanganList;
        $this->siswaList     = $siswaList;
        $this->nilaiMap      = $nilaiMap;
        $this->namaKelas     = $namaKelas;
        $this->namaMapel     = $namaMapel;
    }

    public function title(): string
    {
        return 'Rekap Nilai';
    }

    public function headings(): array
    {
        $headers = ['No', 'NIS', 'Nama Siswa'];

        foreach ($this->tantanganList as $t) {
            $headers[] = $t->judul;
        }

        $headers[] = 'Rata-rata';
        $headers[] = 'Keterangan';

        return $headers;
    }

    public function array(): array
    {
        $rows = [];
        $no = 1;

        foreach ($this->siswaList as $siswa) {
            $row = [
                $no++,
                $siswa->nis ?? '-',
                $siswa->nama,
            ];

            $nilaiArr = [];

            foreach ($this->tantanganList as $t) {
                $nilai = $this->nilaiMap[$siswa->id][$t->id] ?? null;
                $row[]     = $nilai !== null ? (float) $nilai : '-';
                if ($nilai !== null) $nilaiArr[] = (float) $nilai;
            }

            $rata = count($nilaiArr) > 0
                ? round(array_sum($nilaiArr) / count($nilaiArr), 1)
                : '-';

            $row[] = $rata;
            $row[] = ($rata !== '-' && $rata >= 75) ? 'Tuntas' : ($rata !== '-' ? 'Belum Tuntas' : '-');

            $rows[] = $row;
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4e73df']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            "A1:{$lastCol}{$lastRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color'       => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ],
        ];
    }
}