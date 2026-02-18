<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use Carbon\Carbon;

class CleanupExpiredSlots
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Jalankan cleanup di background (non-blocking)
        $this->cleanupInBackground();

        return $next($request);
    }

    /**
     * Cleanup expired bookings dan jadwal locks
     * Method ini dijalankan secara asynchronous agar tidak menghambat request
     */
    private function cleanupInBackground()
    {
        try {
            // 1. Cleanup expired payment bookings
            Pemesanan::cleanupExpiredBookings();

            // 2. Cleanup expired jadwal locks
            Jadwal::cleanupExpiredLocks();

            // 3. Cleanup past available slots (optional, bisa dijalankan via cron saja)
            // $this->cleanupPastSlots();

        } catch (\Exception $e) {
            // Log error tapi jangan stop request
            \Log::error('Cleanup middleware error: ' . $e->getMessage());
        }
    }

    /**
     * Cleanup past slots (opsional)
     */
    private function cleanupPastSlots()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        // Hapus slot yang tanggalnya sudah lewat
        Jadwal::where('status', Jadwal::STATUS_AVAILABLE)
            ->where('tanggal', '<', $today)
            ->delete();

        // Hapus slot hari ini yang jamnya sudah lewat
        Jadwal::where('status', Jadwal::STATUS_AVAILABLE)
            ->where('tanggal', $today)
            ->where('waktu_mulai', '<', $currentTime)
            ->delete();
    }
}
