<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class PengaturanController extends Controller
{
    /**
     * Menampilkan halaman pengaturan
     */
    public function index()
    {
        // Cek apakah user adalah admin
        if (!Auth::user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access.');
        }

        // Ambil semua admin
        $admins = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'superadmin']);
        })
        ->with('roles')
        ->orderBy('created_at', 'desc')
        ->get();
        
        // Ambil active section dari session
        $activeSection = session('activeSection', 'profil-akun');
        
        return view('admin.pengaturan', compact('admins', 'activeSection'));
    }
    
public function updateProfile(Request $request)
{
    $user = Auth::user();
    
    \Log::info('=== UPDATE PROFILE REQUEST START ===');
    \Log::info('User ID: ' . $user->id);
    
    // Validasi
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'current_password' => 'required_with:new_password',
        'new_password' => 'nullable|min:8|confirmed',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'remove_photo' => 'nullable|in:0,1',
    ]);
    
    if ($validator->fails()) {
        \Log::error('Validation failed:', $validator->errors()->toArray());
        return back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Validasi gagal')
            ->with('activeSection', 'profil-akun');
    }
    
    try {
        DB::beginTransaction();
        
        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        
        // Update password jika diisi
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->with('error', 'Password saat ini salah')
                    ->with('activeSection', 'profil-akun')
                    ->withInput();
            }
            
            $user->password = Hash::make($request->new_password);
            \Log::info('Password updated');
        }
        
        // Handle remove photo request
        if ($request->has('remove_photo') && $request->remove_photo == '1') {
            \Log::info('Remove photo requested');
            
            // Hapus file dari storage
            if ($user->profile_photo) {
                // Coba beberapa path yang mungkin
                $possiblePaths = [
                    'profile-photos/' . $user->profile_photo,  // path asli
                    'public/profile-photos/' . $user->profile_photo,  // path jika menggunakan public
                ];
                
                foreach ($possiblePaths as $oldPath) {
                    if (Storage::exists($oldPath)) {
                        Storage::delete($oldPath);
                        \Log::info('Deleted old photo from: ' . $oldPath);
                        break;
                    }
                }
            }
            
            // Set profile_photo ke null di database
            $user->profile_photo = null;
            \Log::info('Set profile_photo to NULL');
        }
        // Handle upload foto profil baru
        elseif ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            
            \Log::info('Processing new photo upload:', [
                'original_name' => $photo->getClientOriginalName(),
                'extension' => $photo->getClientOriginalExtension(),
                'size' => $photo->getSize(),
            ]);
            
            // Hapus foto lama jika ada
            if ($user->profile_photo) {
                // Coba hapus dari semua kemungkinan lokasi
                $possibleOldPaths = [
                    'profile-photos/' . $user->profile_photo,
                    'public/profile-photos/' . $user->profile_photo,
                ];
                
                foreach ($possibleOldPaths as $oldPath) {
                    if (Storage::exists($oldPath)) {
                        Storage::delete($oldPath);
                        \Log::info('Deleted old photo from: ' . $oldPath);
                        break;
                    }
                }
            }
            
            // Generate nama file unik
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
            
            // Simpan file ke storage - GUNAKAN PATH TANPA 'public/'
            $path = $photo->storeAs('profile-photos', $filename);
            \Log::info('Photo stored at: ' . $path);
            
            // Verifikasi file tersimpan
            if (!Storage::exists('profile-photos/' . $filename)) {
                \Log::error('File not saved properly: ' . $filename);
                throw new \Exception('Gagal menyimpan file foto');
            }
            
            // Update field di database - hanya simpan nama file
            $user->profile_photo = $filename;
            \Log::info('Updated user profile_photo to: ' . $filename);
        }
        
        // Simpan perubahan user
        $user->save();
        \Log::info('User saved successfully. Profile photo: ' . ($user->profile_photo ?: 'NULL'));
        
        DB::commit();
        
        // Clear cache
        Cache::forget('user_profile_' . $user->id);
        
        \Log::info('Profile update successful for user: ' . $user->id);
        \Log::info('=== UPDATE PROFILE REQUEST END ===');
        
        return back()
            ->with('success', 'Profil berhasil diperbarui!')
            ->with('activeSection', 'profil-akun');
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error updating profile: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        return back()
            ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
            ->with('activeSection', 'profil-akun')
            ->withInput();
    }
}
    

/**
 * Get profile photo for display
 */
public function getProfilePhoto($filename)
{
    try {
        // Coba beberapa lokasi file
        $possiblePaths = [
            storage_path('app/profile-photos/' . $filename),  // path tanpa 'public'
            storage_path('app/public/profile-photos/' . $filename),  // path dengan 'public'
        ];
        
        foreach ($possiblePaths as $path) {
            \Log::info('Checking photo at: ' . $path);
            if (file_exists($path)) {
                \Log::info('Photo found at: ' . $path);
                return response()->file($path);
            }
        }
        
        \Log::error('Profile photo not found in any location: ' . $filename);
        
        // Return default image
        $defaultPath = public_path('images/default-profile.jpg');
        if (file_exists($defaultPath)) {
            return response()->file($defaultPath);
        }
        
        // Jika default image tidak ada, return 404
        abort(404);
        
    } catch (\Exception $e) {
        Log::error('Error getting profile photo: ' . $e->getMessage());
        
        // Return default image
        $defaultPath = public_path('images/default-profile.jpg');
        if (file_exists($defaultPath)) {
            return response()->file($defaultPath);
        }
        
        abort(404);
    }
}
    
    /**
     * Update pengaturan sistem
     */
    public function updateSystem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:100',
            'app_email' => 'required|email',
            'language' => 'required|in:id,en',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('error', 'Validasi gagal')
                ->with('activeSection', 'pengaturan-sistem')
                ->withInput();
        }
        
        try {
            // Simpan ke cache
            Cache::put('system_settings', [
                'app_name' => $request->app_name,
                'app_email' => $request->app_email,
                'language' => $request->language,
                'timezone' => $request->timezone,
                'date_format' => $request->date_format,
            ], now()->addDays(30));
            
            Log::info('System settings updated by user: ' . Auth::id());
            
            return back()
                ->with('success', 'Pengaturan sistem berhasil diperbarui!')
                ->with('activeSection', 'pengaturan-sistem');
                
        } catch (\Exception $e) {
            Log::error('Error updating system: ' . $e->getMessage());
            return back()
                ->with('error', 'Gagal memperbarui pengaturan sistem: ' . $e->getMessage())
                ->with('activeSection', 'pengaturan-sistem')
                ->withInput();
        }
    }
    
    /**
     * Tambah admin baru
     */
    public function addAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,superadmin',
        ]);
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal')
                ->with('activeSection', 'manajemen-admin');
        }
        
        try {
            DB::beginTransaction();
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'email_verified_at' => now(),
            ]);
            
            $user->assignRole($request->role);
            
            DB::commit();
            
            Log::info('New admin added: ' . $user->email . ' by user: ' . Auth::id());
            
            return back()
                ->with('success', 'Admin berhasil ditambahkan!')
                ->with('activeSection', 'manajemen-admin');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding admin: ' . $e->getMessage());
            return back()
                ->with('error', 'Gagal menambahkan admin: ' . $e->getMessage())
                ->withInput()
                ->with('activeSection', 'manajemen-admin');
        }
    }
    
    /**
     * Update data admin
     */
    public function updateAdmin(Request $request)
    {
        $adminId = $request->input('id');
        
        if (!$adminId) {
            return back()
                ->with('error', 'ID admin tidak ditemukan')
                ->with('activeSection', 'manajemen-admin');
        }
        
        $user = User::find($adminId);
        
        if (!$user) {
            return back()
                ->with('error', 'Admin tidak ditemukan')
                ->with('activeSection', 'manajemen-admin');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,superadmin',
            'status' => 'required|in:aktif,nonaktif',
            'password' => 'nullable|min:6|confirmed',
        ]);
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal')
                ->with('activeSection', 'manajemen-admin');
        }
        
        try {
            DB::beginTransaction();
            
            // Update data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            
            // Update status
            $user->email_verified_at = ($request->status === 'aktif') ? now() : null;
            
            // Update password jika diisi
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();
            
            // Update role
            $user->syncRoles([$request->role]);
            
            DB::commit();
            
            Log::info('Admin updated: ' . $user->email . ' by user: ' . Auth::id());
            
            return back()
                ->with('success', 'Data admin berhasil diperbarui!')
                ->with('activeSection', 'manajemen-admin');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating admin: ' . $e->getMessage());
            return back()
                ->with('error', 'Gagal memperbarui data admin: ' . $e->getMessage())
                ->with('activeSection', 'manajemen-admin');
        }
    }
    
    /**
     * Hapus admin
     */
    public function deleteAdmin(Request $request)
    {
        $adminId = $request->input('id');
        
        if (!$adminId) {
            return back()
                ->with('error', 'ID admin tidak ditemukan')
                ->with('activeSection', 'manajemen-admin');
        }
        
        try {
            $user = User::find($adminId);
            
            if (!$user) {
                return back()
                    ->with('error', 'Admin tidak ditemukan')
                    ->with('activeSection', 'manajemen-admin');
            }
            
            // Cek jika admin mencoba menghapus dirinya sendiri
            if ($user->id === Auth::id()) {
                return back()
                    ->with('error', 'Tidak dapat menghapus akun sendiri')
                    ->with('activeSection', 'manajemen-admin');
            }
            
            // Hapus foto profil jika ada
            if ($user->profile_photo) {
                $photoPath = 'profile-photos/' . $user->profile_photo;
                if (Storage::exists($photoPath)) {
                    Storage::delete($photoPath);
                    Log::info('Deleted admin photo: ' . $photoPath);
                }
            }
            
            $user->delete();
            
            Log::info('Admin deleted: ' . $user->email . ' by user: ' . Auth::id());
            
            return back()
                ->with('success', 'Admin berhasil dihapus')
                ->with('activeSection', 'manajemen-admin');
            
        } catch (\Exception $e) {
            Log::error('Error deleting admin: ' . $e->getMessage());
            return back()
                ->with('error', 'Gagal menghapus admin: ' . $e->getMessage())
                ->with('activeSection', 'manajemen-admin');
        }
    }
    
    /**
     * Update pengaturan notifikasi
     */
    public function updateNotifications(Request $request)
    {
        try {
            $notificationSettings = [
                'email_booking' => $request->has('email_booking'),
                'email_payment' => $request->has('email_payment'),
                'email_report' => $request->has('email_report'),
                'email_venue' => $request->has('email_venue'),
                'email_support' => $request->has('email_support'),
                'frequency' => $request->notification_frequency ?? 'realtime',
            ];
            
            // Simpan ke session
            session(['notification_settings' => $notificationSettings]);
            
            // Simpan ke cache
            Cache::put('notification_settings_' . Auth::id(), $notificationSettings, now()->addDays(30));
            
            Log::info('Notification settings updated by user: ' . Auth::id());
            
            return back()
                ->with('success', 'Pengaturan notifikasi berhasil diperbarui!')
                ->with('activeSection', 'pengaturan-notifikasi');
                
        } catch (\Exception $e) {
            Log::error('Error updating notifications: ' . $e->getMessage());
            return back()
                ->with('error', 'Gagal memperbarui pengaturan notifikasi: ' . $e->getMessage())
                ->with('activeSection', 'pengaturan-notifikasi');
        }
    }
    
    /**
     * Update pengaturan keamanan
     */
    public function updateSecurity(Request $request)
    {
        $user = Auth::user();
        
        // Update password
        if ($request->filled('new_password')) {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ]);
            
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->with('error', 'Validasi gagal')
                    ->with('activeSection', 'keamanan-backup')
                    ->withInput();
            }
            
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->with('error', 'Password saat ini tidak sesuai')
                    ->with('activeSection', 'keamanan-backup')
                    ->withInput();
            }
            
            $user->password = Hash::make($request->new_password);
            $user->save();
            
            Cache::flush();
            
            Log::info('Password updated for user: ' . $user->id);
            
            return back()
                ->with('success', 'Password berhasil diperbarui!')
                ->with('activeSection', 'keamanan-backup');
        }
        
        // Update backup settings
        if ($request->has('backup_frequency')) {
            $backupSettings = [
                'frequency' => $request->backup_frequency,
                'retention' => $request->backup_retention ?? 30,
            ];
            
            Cache::put('backup_settings', $backupSettings, now()->addDays(30));
            Log::info('Backup settings updated by user: ' . Auth::id());
        }
        
        return back()
            ->with('success', 'Pengaturan keamanan berhasil diperbarui!')
            ->with('activeSection', 'keamanan-backup');
    }
    
    /**
     * Backup sekarang
     */
    public function backupNow(Request $request)
    {
        try {
            // Simulasi backup
            $backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            
            Log::info('Backup created by user: ' . Auth::id() . ' - File: ' . $backupFile);
            
            return back()
                ->with('success', 'Backup berhasil dilakukan! File: ' . $backupFile)
                ->with('activeSection', 'keamanan-backup');
                
        } catch (\Exception $e) {
            Log::error('Error creating backup: ' . $e->getMessage());
            return back()
                ->with('error', 'Gagal melakukan backup: ' . $e->getMessage())
                ->with('activeSection', 'keamanan-backup');
        }
    }
    
    /**
     * Update FAQ - placeholder
     */
    public function updateFaq(Request $request)
    {
        try {
            Log::info('FAQ updated by user: ' . Auth::id());
            
            return back()
                ->with('success', 'FAQ berhasil diperbarui!')
                ->with('activeSection', 'pusat-bantuan');
                
        } catch (\Exception $e) {
            Log::error('Error updating FAQ: ' . $e->getMessage());
            return back()
                ->with('error', 'Gagal memperbarui FAQ: ' . $e->getMessage())
                ->with('activeSection', 'pusat-bantuan');
        }
    }
}