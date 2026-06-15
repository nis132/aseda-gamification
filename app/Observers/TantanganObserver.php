<?php

namespace App\Observers;

use App\Models\Tantangan;
use App\Models\Soal;

class TantanganObserver
{
    public function updated(Tantangan $tantangan): void
    {
        if ($tantangan->is_pengayaan) return;
        if ($tantangan->status !== 'published') return;
        if (is_null($tantangan->batas_waktu) || $tantangan->batas_waktu > now()) return;
        if ($tantangan->wasChanged('updated_at') && !$tantangan->wasChanged('batas_waktu')) return;

        $sudahAda = Tantangan::where('parent_tantangan_id', $tantangan->id)
            ->where('is_pengayaan', 1)
            ->exists();

        if (!$sudahAda) {
            Tantangan::withoutEvents(function () use ($tantangan) {
                $pengayaan = Tantangan::create([
                    'judul'               => '[Pengayaan] ' . $tantangan->judul,
                    'deskripsi'           => 'Pengayaan untuk: ' . $tantangan->deskripsi,
                    'mapel_id'            => $tantangan->mapel_id,
                    'guru_id'             => $tantangan->guru_id,
                    'kelas_id'            => $tantangan->kelas_id,
                    'status'              => 'published',
                    'batas_waktu'         => null,
                    'poin'                => (int) ($tantangan->poin * 0.5),
                    'urutan'              => $tantangan->urutan,
                    'difficulty'          => $tantangan->difficulty,
                    'bab'                 => $tantangan->bab,
                    'is_pengayaan'        => 1,
                    'parent_tantangan_id' => $tantangan->id,
                ]);

                // Salin semua soal dari tantangan asli
                foreach ($tantangan->soal as $soal) {
                    Soal::create([
                        'tantangan_id'    => $pengayaan->id,
                        'pertanyaan'      => $soal->pertanyaan,
                        'tipe'            => $soal->tipe,
                        'opsi_a'          => $soal->opsi_a,
                        'opsi_b'          => $soal->opsi_b,
                        'opsi_c'          => $soal->opsi_c,
                        'opsi_d'          => $soal->opsi_d,
                        'jawaban_benar'   => $soal->jawaban_benar,
                        'kiri_items'      => $soal->kiri_items,
                        'kanan_items'     => $soal->kanan_items,
                        'matching_pairs'  => $soal->matching_pairs,
                        'matching_count'  => $soal->matching_count,
                    ]);
                }
            });
        }
    }
}