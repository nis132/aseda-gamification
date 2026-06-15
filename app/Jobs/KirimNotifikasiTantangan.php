<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Tantangan;
use App\Notifications\TantanganBaruNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class KirimNotifikasiTantangan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 120;

    // Simpan ID saja, bukan object Eloquent — aman untuk serialisasi queue
    protected int $tantanganId;
    protected int $kelasId;

    public function __construct(Tantangan $tantangan, int $kelasId)
    {
        $this->tantanganId = $tantangan->id;
        $this->kelasId     = $kelasId;
    }

    public function handle(): void
    {
        // Ambil ulang dari DB saat job dieksekusi
        $tantangan = Tantangan::find($this->tantanganId);

        if (!$tantangan) {
            return; // Tantangan sudah dihapus, skip
        }

        // Pakai whereHas agar result adalah Eloquent User model
        // yang punya method pushSubscriptions() dan notify()
        $siswas = User::where('role', 'siswa')
            ->whereHas('kelas', function ($q) {
                $q->where('kelas.id', $this->kelasId);
            })
            ->get();

        foreach ($siswas as $siswa) {
            if ($siswa->pushSubscriptions()->exists()) {
                $siswa->notify(new TantanganBaruNotification($tantangan));
            }
        }
    }
}