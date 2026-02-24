<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // Semua transaksi (semua venue) — admin melihat seluruh data
        $query = Transaksi::query();
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan metode pembayaran
        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('transaction_date', '>=', $request->tanggal_mulai);
        }
        
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('transaction_date', '<=', $request->tanggal_selesai);
        }

        // Filter berdasarkan pencarian nama/nomor transaksi
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('transaction_number', 'like', '%' . $request->search . '%')
                  ->orWhere('pengguna', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_venue', 'like', '%' . $request->search . '%');
            });
        }
        
        $transaksis = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Statistik keseluruhan (semua venue)
        $statistik = [
            'total_hari_ini'      => Transaksi::whereDate('transaction_date', today())->count(),
            'pendapatan_hari_ini' => Transaksi::whereDate('transaction_date', today())
                ->whereIn('status', ['completed', 'paid'])
                ->sum('amount'),
            'pending_count'       => Transaksi::where('status', 'pending')->count(),
            'success_count'       => Transaksi::whereIn('status', ['completed', 'paid'])->count(),
        ];
        
        return view('admin.transaksi', compact('transaksis', 'statistik'));
    }

    /**
     * ✅ TAMBAHAN: Method show yang hilang (dipanggil di web.php)
     */
    public function show($id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            
            // Jika request AJAX, kembalikan JSON
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data'    => $transaksi
                ]);
            }

            return view('admin.transaksi.show', compact('transaksi'));
            
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            return redirect()->route('admin.transaksi.index')
                ->with('error', 'Transaksi tidak ditemukan.');
        }
    }

    /**
     * ✅ TAMBAHAN: Method filter (dipanggil di web.php via POST /filter)
     * Redirect ke index dengan parameter filter sebagai query string
     */
    public function filter(Request $request)
    {
        $params = $request->only([
            'status',
            'metode_pembayaran',
            'tanggal_mulai',
            'tanggal_selesai',
            'search',
        ]);

        // Hapus nilai kosong agar URL bersih
        $params = array_filter($params, fn($v) => $v !== null && $v !== '');

        return redirect()->route('admin.transaksi.index', $params);
    }
    
    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        $request->validate([
            'pengguna'          => 'required|string',
            'nama_venue'        => 'required|string',
            'metode_pembayaran' => 'required|string',
            'amount'            => 'required|numeric|min:0',
            // ✅ FIX: Status yang valid sesuai model Transaksi
            'status'            => 'required|in:pending,paid,failed,cancelled,refunded,completed,processing',
        ]);
        
        $transaksi->update([
            'pengguna'          => $request->pengguna,
            'nama_venue'        => $request->nama_venue,
            'metode_pembayaran' => $request->metode_pembayaran,
            'amount'            => $request->amount,
            'status'            => $request->status,
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui'
            ]);
        }

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);
        }

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    /**
     * ✅ FIX: Di web.php route namanya 'confirm' → method confirmPayment
     * Sesuai: Route::post('/{transaksi}/confirm', [AdminTransaksiController::class, 'confirmPayment'])
     */
    public function confirmPayment($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update(['status' => 'completed']);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dikonfirmasi'
            ]);
        }

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil dikonfirmasi');
    }
    
    /**
     * ✅ FIX: Di web.php route namanya 'reject' → method rejectPayment
     * Sesuai: Route::post('/{transaksi}/reject', [AdminTransaksiController::class, 'rejectPayment'])
     */
    public function rejectPayment($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update(['status' => 'cancelled']);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil ditolak'
            ]);
        }

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil ditolak');
    }

    // Alias untuk kompatibilitas jika ada kode lama yang memanggil confirm/reject langsung
    public function confirm($id) { return $this->confirmPayment($id); }
    public function reject($id)  { return $this->rejectPayment($id); }
}