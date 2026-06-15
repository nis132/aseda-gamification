<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class TantanganBaruNotification extends Notification
{
    // TIDAK pakai Queueable dan TIDAK implement ShouldQueue
    // karena sudah di-handle oleh Job (KirimNotifikasiTantangan)
    // Kalau double-queue, serialisasi $tantangan akan gagal

    public string $judul;
    public int    $tantanganId;
    public string $urlTantangan;

    public function __construct($tantangan)
    {
        // Simpan hanya data primitif, bukan object Eloquent
        // Ini mencegah error serialisasi saat Job di-serialize ke queue
        $this->judul        = $tantangan->judul;
        $this->tantanganId  = $tantangan->id;
        $this->urlTantangan = route('siswa.tantangan.kerjakan', $tantangan->id);

    }

    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Tantangan Baru!')
            ->icon('/storage/logo_aseda.webp')
            ->body($this->judul)
            ->data(['url' => $this->urlTantangan])
            ->action('Kerjakan Sekarang', 'open');
    }
}