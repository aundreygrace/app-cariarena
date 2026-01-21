<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Venue;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        // Validasi data
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'booking_id' => 'nullable|exists:booking,id',
            'total_biaya' => 'required|numeric',
            'durasi' => 'required|integer',
            'tanggal_booking' => 'required|date',
            'waktu_booking' => 'required',
            'payment_method' => 'required|in:cash,transfer,card',
        ]);

        try {
            // Ambil data venue
            $venue = Venue::findOrFail($request->venue_id);
            
            // Generate transaction number
            $transactionNumber = 'TRX-' . Str::upper(Str::random(8));
            
            // Buat transaksi baru di tabel transactions
            $transaction = Transaction::create([
                'transaction_number' => $transactionNumber,
                'customer_id' => Auth::id(),
                'pengguna' => Auth::user()->email,
                'nama_venue' => $venue->name,
                'metode_pembayaran' => $request->payment_method,
                'amount' => $request->total_biaya + 5000,
                'transaction_date' => $request->tanggal_booking,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update status booking jika ada booking_id
            if ($request->booking_id) {
                $booking = Booking::find($request->booking_id);
                if ($booking) {
                    $booking->update([
                        'status' => 'Terkonfirmasi',
                        'updated_at' => now(),
                    ]);
                }
            }

            // Redirect ke halaman riwayat booking dengan data transaksi
            return redirect()->route('pesan.riwayat-booking')->with([
                'success' => 'Pembayaran berhasil!',
                'transaction' => $transaction,
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}