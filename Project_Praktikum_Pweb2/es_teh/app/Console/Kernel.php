<?php

namespace App\Console; // Pastikan namespace ini benar

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
// Import command Anda jika namespace-nya berbeda atau untuk kejelasan (opsional jika namespace standar)
// use App\Console\Commands\PruneOldActivityLogs;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    // protected $commands = [ // Di Laravel versi baru, command biasanya otomatis terdeteksi
    //     // Jika command tidak terdeteksi otomatis, Anda bisa daftarkan di sini:
    //     // \App\Console\Commands\PruneOldActivityLogs::class,
    // ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // Contoh schedule bawaan:
        // $schedule->command('inspire')->hourly();

        // Jadwalkan command Anda untuk menghapus log lama
        // Jalankan setiap hari pada pukul tertentu (misalnya, 02:00 pagi)
        $schedule->command('activitylog:prune-old')->dailyAt('02:00');

        // Atau jika hanya ingin setiap hari (waktu ditentukan oleh kapan cron job utama berjalan):
        // $schedule->command('activitylog:prune-old')->daily();

        // Anda juga bisa menambahkan output ke file log scheduler jika diinginkan
        // $schedule->command('activitylog:prune-old')
        //          ->dailyAt('02:00')
        //          ->appendOutputTo(storage_path('logs/scheduler.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands'); // Ini akan me-load semua command di folder app/Console/Commands

        require base_path('routes/console.php'); // Untuk console routes
    }
}
