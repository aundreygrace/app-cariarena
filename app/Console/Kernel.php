<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // ✅ REGISTER COMMAND BARU
        Commands\CleanupExpiredBookings::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // ✅ CLEANUP BARU - Menggunakan command yang lebih lengkap
        // Jalankan setiap 5 menit untuk efisiensi
        $schedule->command('bookings:cleanup-expired')
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->runInBackground();

        // ❌ HAPUS/COMMENT YANG LAMA (sudah digantikan dengan command di atas)
        /*
        $schedule->call(function () {
            $count = \App\Models\Booking::cleanupExpiredBookings();
            if ($count > 0) {
                \Log::info("Cleaned up {$count} expired bookings at " . now());
            }
        })->everyMinute();
        
        $schedule->call(function () {
            $count = \App\Models\Jadwal::releaseExpiredLocks();
            if ($count > 0) {
                \Log::info("Released {$count} expired jadwal locks at " . now());
            }
        })->everyMinute();
        
        $schedule->call(function () {
            \App\Models\Pemesanan::cleanupExpiredBookings();
        })->everyMinute();
        */
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