<?php

namespace App\Console\Commands; // Pastikan namespace ini benar

use Illuminate\Console\Command;
use App\Models\ActivityLog; // Import model ActivityLog Anda
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal

class PruneOldActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // Signature ini akan digunakan untuk memanggil command dari terminal: php artisan activitylog:prune-old
    protected $signature = 'activitylog:prune-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune (delete) activity log entries older than one month.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int // Menggunakan return type int untuk Laravel 9+
    {
        $this->info('Memulai proses penghapusan log aktivitas lama...');

        // Tentukan batas waktu (misalnya, 1 bulan yang lalu)
        $cutoffDate = Carbon::now()->subMonth();
        // Atau jika Anda ingin lebih spesifik, misal 30 hari:
        // $cutoffDate = Carbon::now()->subDays(30);

        $this->comment("Log aktivitas yang lebih tua dari {$cutoffDate->format('Y-m-d H:i:s')} akan dihapus.");

        // Hapus log aktivitas yang lebih tua dari tanggal batas
        $deletedCount = ActivityLog::where('created_at', '<=', $cutoffDate)->delete();

        if ($deletedCount > 0) {
            $this->info("Berhasil menghapus {$deletedCount} entri log aktivitas lama.");
        } else {
            $this->info('Tidak ada log aktivitas lama yang perlu dihapus.');
        }

        return Command::SUCCESS; // Mengindikasikan command berhasil
    }
}
