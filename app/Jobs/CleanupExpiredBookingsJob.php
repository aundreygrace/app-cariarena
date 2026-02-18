<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use Carbon\Carbon;

class CleanupExpiredBookingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('🔄 CleanupExpiredBookingsJob started');

        try {
            // 1. Cleanup expired payment bookings
            $expiredCount = Pemesanan::cleanupExpiredBookings();
            \Log::info("✅ Cleaned up {$expiredCount} expired bookings");

            // 2. Cleanup expired jadwal locks
            $unlockedCount = Jadwal::cleanupExpiredLocks();
            \Log::info("✅ Unlocked {$unlockedCount} expired locks");

            // 3. Cleanup past available slots
            $pastSlotsCount = $this->cleanupPastSlots();
            \Log::info("✅ Removed {$pastSlotsCount} past slots");

            // 4. Mark completed bookings
            $completedCount = $this->markCompletedBookings();
            \Log::info("✅ Marked {$completedCount} bookings as completed");

        } catch (\Exception $e) {
            \Log::error('❌ CleanupExpiredBookingsJob error: ' . $e->getMessage());
            throw $e;
        }

        \Log::info('✅ CleanupExpiredBookingsJob completed');
    }

    /**
     * Cleanup past available slots
     * 
     * @return int
     */
    private function cleanupPastSlots()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        // Hapus slot yang tanggalnya sudah lewat
        $pastDatesCount = Jadwal::where('status', Jadwal::STATUS_AVAILABLE)
            ->where('tanggal', '<', $today)
            ->delete();

        // Hapus slot hari ini yang jamnya sudah lewat
        $pastTimeTodayCount = Jadwal::where('status', Jadwal::STATUS_AVAILABLE)
            ->where('tanggal', $today)
            ->where('waktu_mulai', '<', $currentTime)
            ->delete();

        return $pastDatesCount + $pastTimeTodayCount;
    }

    /**
     * Mark past confirmed bookings as completed
     * 
     * @return int
     */
    private function markCompletedBookings()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        $bookings = Pemesanan::where('status', Pemesanan::STATUS_CONFIRMED)
            ->where(function($query) use ($today, $currentTime) {
                $query->where('tanggal_booking', '<', $today)
                    ->orWhere(function($q) use ($today, $currentTime) {
                        $q->where('tanggal_booking', $today)
                          ->where('end_time', '<', $currentTime);
                    });
            })
            ->get();

        $count = 0;
        foreach ($bookings as $booking) {
            $booking->markAsCompleted();
            $count++;
        }

        return $count;
    }
}
