<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers\User;

use App\Models\Booking;
use App\Models\Venue;
use App\Models\Transaksi;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function process(Request $request)
{
    $request->validate([
        'booking_code'   => 'required|exists:booking,booking_code',
        'payment_method' => 'required|in:transfer,qris',
    ]);

    $booking = Pemesanan::where('booking_code', $request->booking_code)
        ->where('user_id', auth()->id())
        ->with('venue')
        ->firstOrFail();

    $adminFee   = 5000;
    $venueTotal = $booking->venue->price_per_hour * $booking->durasi;
    $grandTotal = $venueTotal + $adminFee;

    // ✅ SIMPAN TRANSAKSI (SUMBER UANG)
    $transaksi = Transaksi::create([
        'transaction_number' => Transaksi::generateTransactionNumber(),
        'booking_id'         => $booking->id, // 🔑 PENTING
        'customer_id'        => auth()->id(),
        'pengguna'           => auth()->user()->name,
        'nama_venue'         => $booking->venue->name,
        'metode_pembayaran'  => $request->payment_method,
        'amount'             => $grandTotal,
        'transaction_date'   => now(),
        'status'             => 'paid',
    ]);

    // ✅ UPDATE BOOKING (STATUS SAJA)
    $booking->update([
        'status'         => Pemesanan::STATUS_CONFIRMED,
        'payment_method'=> $request->payment_method,
        'paid_at'        => now(),
    ]);

    return redirect()
        ->route('pesan.riwayat-booking.detail', $booking->booking_code)
        ->with('success', 'Pembayaran berhasil');
}
}