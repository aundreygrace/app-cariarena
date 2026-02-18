<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use Carbon\Carbon;

class CleanupExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cleanup-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup expired bookings and unlock their jadwal slots';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Starting cleanup process...');

        // ========== 1. CLEANUP EXPIRED PAYMENT BOOKINGS ==========
        $this->info('');
        $this->info('📌 Step 1: Cleaning up expired payment bookings...');
        
        $expiredCount = Pemesanan::cleanupExpiredBookings();
        
        if ($expiredCount > 0) {
            $this->info("✅ Cleaned up {$expiredCount} expired payment bookings");
        } else {
            $this->info("ℹ️  No expired payment bookings found");
        }

        // ========== 2. CLEANUP EXPIRED JADWAL LOCKS ==========
        $this->info('');
        $this->info('📌 Step 2: Cleaning up expired jadwal locks...');
        
        $unlockedCount = Jadwal::cleanupExpiredLocks();
        
        if ($unlockedCount > 0) {
            $this->info("✅ Unlocked {$unlockedCount} expired jadwal locks");
        } else {
            $this->info("ℹ️  No expired jadwal locks found");
        }

        // ========== 3. CLEANUP PAST AVAILABLE SLOTS ==========
        $this->info('');
        $this->info('📌 Step 3: Cleaning up past available slots...');
        
        $pastSlotsCount = $this->cleanupPastAvailableSlots();
        
        if ($pastSlotsCount > 0) {
            $this->info("✅ Removed {$pastSlotsCount} past available slots");
        } else {
            $this->info("ℹ️  No past available slots found");
        }

        // ========== 4. MARK COMPLETED BOOKINGS ==========
        $this->info('');
        $this->info('📌 Step 4: Marking past confirmed bookings as completed...');
        
        $completedCount = $this->markPastBookingsAsCompleted();
        
        if ($completedCount > 0) {
            $this->info("✅ Marked {$completedCount} bookings as completed");
        } else {
            $this->info("ℹ️  No bookings to mark as completed");
        }

        // ========== SUMMARY ==========
        $this->info('');
        $this->info('══════════════════════════════════════');
        $this->info('✨ Cleanup Summary:');
        $this->info("   • Expired bookings: {$expiredCount}");
        $this->info("   • Unlocked jadwal: {$unlockedCount}");
        $this->info("   • Past slots removed: {$pastSlotsCount}");
        $this->info("   • Bookings completed: {$completedCount}");
        $this->info('══════════════════════════════════════');
        $this->info('');

        return Command::SUCCESS;
    }

    /**
     * Cleanup past available slots yang sudah lewat
     * 
     * @return int
     */
    private function cleanupPastAvailableSlots()
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
     * Mark booking yang sudah lewat waktu end_time sebagai completed
     * 
     * @return int
     */
    private function markPastBookingsAsCompleted()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        $bookings = Pemesanan::where('status', Pemesanan::STATUS_CONFIRMED)
            ->where(function($query) use ($today, $currentTime) {
                // Booking hari sebelumnya
                $query->where('tanggal_booking', '<', $today)
                    // Atau booking hari ini yang end_time sudah lewat
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
