<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 🔄 SISTEM PENGHAPUSAN JADWAL OTOMATIS
        // ═════════════════════════════════════════════════════════════
        // Hapus jadwal yang sudah lewat SETIAP HARI pada pukul 00:30 (jam 12:30 pagi)
        // 
        // PENJELASAN:
        // - daily()         : Jalankan sekali per hari
        // - at('00:30')     : Pada pukul 00:30 (jam 12:30 pagi, tepat setelah tengah malam)
        // - schedule:cleanup: Command yang didefinisikan di app/Console/Commands/DeleteExpiredLessons.php
        //
        // CONTOH:
        // Hari Jumat, jadwal untuk tanggal Kamis sudah lewat
        // - Pada jam 00:30 Jumat pagi → system otomatis hapus semua jadwal Kamis
        // - Jadwal bisa dibuat jauh sebelumnya (misal seminggu, sebulan sebelumnya)
        // - Jadwal baru tetap bisa dibuat kapan saja
        //
        $schedule->command('schedule:cleanup')
                 ->daily()
                 ->at('00:30')
                 ->withoutOverlapping()
                 ->onFailure(function () {
                     // Callback jika command gagal
                     \Illuminate\Support\Facades\Log::error('schedule:cleanup FAILED at ' . now());
                 })
                 ->onSuccess(function () {
                     // Callback jika command berhasil
                     \Illuminate\Support\Facades\Log::info('schedule:cleanup SUCCESS at ' . now());
                 });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
