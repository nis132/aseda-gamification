<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduler: Notifikasi Orang Tua Siswa Tidak Aktif
|--------------------------------------------------------------------------
| Dikirim setiap hari pukul 07.00 WIB (UTC+7 = 00:00 UTC).
| Kirim WA via Twilio jika siswa memiliki ≥ 3 tantangan expired
| yang belum dikerjakan.
|
| Pastikan cron job sudah dipasang di server:
|   * * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1
|--------------------------------------------------------------------------
*/
Schedule::command('notify:inactive-students --daily')
    ->dailyAt('07:00')        // WIB: sesuaikan timezone di config/app.php
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping()    // hindari double-run jika sebelumnya masih jalan
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error(
            '[Scheduler] notify:inactive-students GAGAL dijalankan pada ' . now()
        );
    })
    ->onSuccess(function () {
        \Illuminate\Support\Facades\Log::info(
            '[Scheduler] notify:inactive-students selesai pada ' . now()
        );
    });