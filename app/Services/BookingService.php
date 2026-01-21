<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class BookingService
{
    public static function isBentrok(
        int $venueId,
        string $tanggal,
        string $jamMulai,
        string $jamSelesai
    ): bool {
        return DB::table('booking')
            ->where('venue_id', $venueId)
            ->where('tanggal_booking', $tanggal)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->whereRaw('? < end_time', [$jamMulai])
                  ->whereRaw('? > waktu_booking', [$jamSelesai]);
            })
            ->exists();
    }
}
