<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search filter
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('venue_name', 'like', '%' . $request->search . '%');
        }
        
        // Type filter
        if ($request->has('type') && $request->type != '') {
            if ($request->type == 'venue') {
                $query->whereNotNull('venue_name')->where('venue_name', '!=', '');
            } elseif ($request->type == 'user') {
                $query->where(function($q) {
                    $q->whereNull('venue_name')->orWhere('venue_name', '');
                });
            } elseif ($request->type == 'admin') {
                $query->where('email', 'like', '%admin%');
            }
        }
        
        $users = $query->latest()->paginate(10);
        
        // Statistics
        $totalPengguna = User::count();
        $penggunaAktif = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $penggunaBaru = User::whereMonth('created_at', Carbon::now()->month)->count();
        $tingkatKeterlibatan = $totalPengguna > 0 ? round(($penggunaAktif / $totalPengguna) * 100) : 0;
        
        return view('admin.manajemen_pengguna', compact(
            'users', 
            'totalPengguna', 
            'penggunaAktif', 
            'penggunaBaru', 
            'tingkatKeterlibatan'
        ));
    }
    
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        return response()->json([
            'user' => $user
        ]);
    }
}