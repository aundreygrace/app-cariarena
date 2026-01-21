<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        // Query dasar
        $query = User::query();
        
        // Filter pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('venue_name', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan tipe
        if ($request->has('type') && !empty($request->type)) {
            if ($request->type == 'admin') {
                $query->where('email', 'like', '%admin%');
            } elseif ($request->type == 'venue') {
                $query->whereNotNull('venue_name')
                      ->where('venue_name', '!=', '[null]');
            } elseif ($request->type == 'user') {
                $query->where(function($q) {
                    $q->whereNull('venue_name')
                      ->orWhere('venue_name', '[null]')
                      ->orWhere('venue_name', '');
                })->where('email', 'not like', '%admin%');
            }
        }
        
        // Filter berdasarkan status (contoh: Aktif/Nonaktif berdasarkan email_verified_at)
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status == 'Aktif') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status == 'Nonaktif') {
                $query->whereNull('email_verified_at');
            }
        }
        
        // Sorting
        $query->orderBy('created_at', 'desc');
        
        // Pagination atau semua data
        $perPage = $request->get('per_page', 10);
        if ($perPage == 'all') {
            $users = $query->get();
        } else {
            $users = $query->paginate($perPage)->withQueryString();
        }
        
        // Hitung statistik
        $totalPengguna = User::count();
        $pemilikVenue = User::whereNotNull('venue_name')
                           ->where('venue_name', '!=', '[null]')
                           ->count();
        $penggunaBaru = User::whereMonth('created_at', now()->month)
                           ->whereYear('created_at', now()->year)
                           ->count();
        $totalAdmin = User::where('email', 'like', '%admin%')->count();
        
        // Tampilkan view
        return view('admin.manajemen_pengguna', compact(
            'users',
            'totalPengguna',
            'pemilikVenue',
            'penggunaBaru',
            'totalAdmin'
        ));
    }
    
    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.pengguna.create');
    }
    
    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,venue,admin',
            'venue_name' => 'nullable|string|max:100',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Tipe pengguna wajib dipilih.',
            'role.in' => 'Tipe pengguna tidak valid.',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'venue_name' => $request->venue_name,
                'email_verified_at' => now(), // Auto verify untuk admin-created users
            ]);
            
            // Assign role berdasarkan tipe
            $roleName = $request->role;
            $role = Role::where('name', $roleName)->first();
            
            if (!$role) {
                // Jika role belum ada, buat role baru
                $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);
            }
            
            $user->assignRole($role);
            
            // Log activity (optional)
            // activity()
            //     ->causedBy(auth()->user())
            //     ->performedOn($user)
            //     ->log('menambahkan pengguna baru: ' . $user->name);
            
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil ditambahkan!',
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error creating user: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pengguna: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display the specified user.
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Pengguna tidak ditemukan.'
            ], 404);
        }
    }
    
    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.pengguna.edit', compact('user'));
    }
    
    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'venue_name' => 'nullable|string|max:100',
            'status' => 'nullable|in:active,inactive',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'venue_name' => $request->venue_name,
        ]);
        
        // Update status (contoh: berdasarkan email_verified_at)
        if ($request->has('status')) {
            if ($request->status == 'active') {
                $user->email_verified_at = now();
            } else {
                $user->email_verified_at = null;
            }
            $user->save();
        }
        
        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }
    
    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Cek jika user memiliki relasi penting
            if ($user->venues()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus pengguna yang memiliki venue.'
                ], 400);
            }
            
            // Hapus role relationships terlebih dahulu
            $user->roles()->detach();
            
            // Hapus user
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dihapus.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pengguna: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get user statistics for dashboard.
     */
    public function getStatistics()
    {
        $totalPengguna = User::count();
        $pemilikVenue = User::whereNotNull('venue_name')
                           ->where('venue_name', '!=', '[null]')
                           ->count();
        $penggunaBaru = User::whereMonth('created_at', now()->month)
                           ->whereYear('created_at', now()->year)
                           ->count();
        $totalAdmin = User::where('email', 'like', '%admin%')->count();
        
        return response()->json([
            'totalPengguna' => $totalPengguna,
            'pemilikVenue' => $pemilikVenue,
            'penggunaBaru' => $penggunaBaru,
            'totalAdmin' => $totalAdmin
        ]);
    }
}