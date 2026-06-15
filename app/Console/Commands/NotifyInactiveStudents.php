<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ParentNotificationService;

class NotifyInactiveStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:inactive-students
                            {--daily : Jalankan daily notification}
                            {--manual : Manual trigger untuk testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi ke orang tua siswa yang tidak mengerjakan tantangan (3+ tantangan tidak dikerjakan)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔔 Memulai pengecekan siswa tidak aktif...');

        try {
            ParentNotificationService::checkAndNotifyInactiveStudents();
            $this->info('✅ Notifikasi berhasil dikirim!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
