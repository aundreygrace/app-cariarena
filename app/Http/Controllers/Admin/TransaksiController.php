<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi; // Model Transaksi.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar
        $query = Transaksi::query();
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan metode pembayaran
        if ($request->has('metode_pembayaran') && $request->metode_pembayaran != '') {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }
        
        // Filter berdasarkan tanggal
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai != '') {
            $query->whereDate('transaction_date', '>=', $request->tanggal_mulai);
        }
        
        if ($request->has('tanggal_selesai') && $request->tanggal_selesai != '') {
            $query->whereDate('transaction_date', '<=', $request->tanggal_selesai);
        }
        
        $transaksis = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Statistik
        $statistik = [
            'total_hari_ini' => Transaksi::whereDate('transaction_date', today())->count(),
            'pendapatan_hari_ini' => Transaksi::whereDate('transaction_date', today())
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_count' => Transaksi::where('status', 'pending')->count(),
            'success_count' => Transaksi::where('status', 'completed')->count(),
        ];
        
        return view('admin.transaksi', compact('transaksis', 'statistik'));
    }
    
    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        $request->validate([
            'pengguna' => 'required|string',
            'nama_venue' => 'required|string',
            'metode_pembayaran' => 'required|string',
            'amount' => 'required|numeric',
            'status' => 'required|string',
        ]);
        
        $transaksi->update([
            'pengguna' => $request->pengguna,
            'nama_venue' => $request->nama_venue,
            'metode_pembayaran' => $request->metode_pembayaran,
            'amount' => $request->amount,
            'status' => $request->status,
        ]);
        
        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        
        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }
    
    public function confirm($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update(['status' => 'completed']);
        
        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil dikonfirmasi');
    }
    
    public function reject($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update(['status' => 'cancelled']);
        
        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil ditolak');
    }
}