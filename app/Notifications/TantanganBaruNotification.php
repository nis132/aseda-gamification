<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class TantanganBaruNotification extends Notification
{
    use Queueable;

    protected $tantangan;

    public function __construct($tantangan)
    {
        $this->tantangan = $tantangan;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

public function toWebPush($notifiable, $notification)
{
    return (new WebPushMessage)
        ->title('Tantangan Baru Tersedia!')
        ->icon('/storage/logo_aseda.webp')
        ->body("Ayo kerjakan tantangan: {$this->tantangan->judul}")
        ->data(['url' => route('siswa.tantangan', $this->tantangan->id)]);
}
}