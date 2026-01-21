<?php

namespace App\Http\Controllers\Venue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Venue;

class PengaturanController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $user = Auth::user();
        
        // DEBUG EXTRA
        Log::info('=== VENUE SETTINGS INDEX - DEBUG ===');
        Log::info('User ID: ' . $user->id);
        Log::info('User Name: ' . $user->name);
        Log::info('Profile photo from DB: ' . ($user->profile_photo ?: 'NULL'));
        
        // CEK FILE DI STORAGE
        if ($user->profile_photo) {
            $storagePath = storage_path('app/public/profile-photos/' . $user->profile_photo);
            $publicPath = public_path('storage/profile-photos/' . $user->profile_photo);
            
            Log::info('Storage path: ' . $storagePath);
            Log::info('Storage file exists: ' . (file_exists($storagePath) ? 'YES' : 'NO'));
            Log::info('Public path: ' . $publicPath);
            Log::info('Public file exists: ' . (file_exists($publicPath) ? 'YES' : 'NO'));
            
            // Coba buat symlink jika belum ada
            if (!file_exists($publicPath) && file_exists($storagePath)) {
                try {
                    // Pastikan directory public/storage/profile-photos ada
                    $publicDir = public_path('storage/profile-photos');
                    if (!file_exists($publicDir)) {
                        mkdir($publicDir, 0755, true);
                    }
                    
                    // Buat symlink atau copy file
                    symlink($storagePath, $publicPath);
                    Log::info('Created symlink to public storage');
                } catch (\Exception $e) {
                    Log::error('Failed to create symlink: ' . $e->getMessage());
                }
            }
        }
        
        // Ambil venue user
        $venue = Venue::where('user_id', $user->id)->first();
        
        // Ambil data schedule dari database
        $schedule = null;
        if ($venue) {
            $schedule = Jadwal::where('venue_id', $venue->id)->first();
        }
        
        // Jika tidak ada schedule, buat default object
        if (!$schedule) {
            $schedule = (object)[
                'id' => null,
                'venue_id' => $venue->id ?? null,
                'day' => 'setiap hari',
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '22:00:00',
                'status' => 'Available',
                'catatan' => null,
                'created_at' => null,
                'updated_at' => null
            ];
        }
        
        // Ambil pengaturan notifikasi (contoh sederhana)
        $notificationSettings = [
            'email_booking' => true,
            'email_review' => true,
            'email_payment' => true,
            'browser_notifications' => false
        ];
        
        return view('venue.pengaturan', compact('user', 'schedule', 'notificationSettings', 'venue'));
    }
    
    /**
     * Update profile venue - FIXED VERSION
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        // START DEBUG
        Log::info('=== VENUE PROFILE UPDATE START ===');
        Log::info('User ID: ' . $user->id);
        Log::info('Has profile_photo file: ' . ($request->hasFile('profile_photo') ? 'YES' : 'NO'));
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'venue_name' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'social_media' => 'nullable|string|max:100',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            Log::error('Validation errors:', $validator->errors()->toArray());
            return redirect()->route('venue.pengaturan')
                ->withErrors($validator)
                ->with('activeSection', 'profil-akun')
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            Log::info('Transaction started');
            
            // Update data user
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->venue_name = $request->venue_name;
            $user->description = $request->description;
            $user->social_media = $request->social_media;
            
            // Update password jika diisi
            if ($request->filled('new_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return redirect()->route('venue.pengaturan')
                        ->with('error', 'Password saat ini salah')
                        ->with('activeSection', 'profil-akun');
                }
                $user->password = Hash::make($request->new_password);
                Log::info('Password updated');
            }
            
            // Handle upload foto profil - FIXED VERSION
            if ($request->hasFile('profile_photo')) {
                Log::info('Processing photo upload...');
                
                try {
                    $file = $request->file('profile_photo');
                    
                    // Hapus foto lama jika ada
                    if ($user->profile_photo) {
                        $oldPhotoPath = storage_path('app/public/profile-photos/' . $user->profile_photo);
                        $oldPublicPath = public_path('storage/profile-photos/' . $user->profile_photo);
                        
                        if (file_exists($oldPhotoPath)) {
                            unlink($oldPhotoPath);
                        }
                        if (file_exists($oldPublicPath)) {
                            unlink($oldPublicPath);
                        }
                        Log::info('Old photo deleted');
                    }
                    
                    // Generate nama file
                    $filename = 'venue_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                    Log::info('Generated filename: ' . $filename);
                    
                    // Simpan file ke storage
                    $path = $file->storeAs('profile-photos', $filename, 'public');
                    
                    if ($path) {
                        Log::info('File saved to storage: ' . $path);
                        
                        // Update database
                        $user->profile_photo = $filename;
                        Log::info('Database updated with filename: ' . $filename);
                        
                        // Buat symlink ke public storage
                        $storagePath = storage_path('app/public/' . $path);
                        $publicPath = public_path('storage/profile-photos/' . $filename);
                        
                        // Pastikan directory public storage ada
                        $publicDir = public_path('storage/profile-photos');
                        if (!file_exists($publicDir)) {
                            mkdir($publicDir, 0755, true);
                        }
                        
                        // Hapus symlink lama jika ada
                        if (file_exists($publicPath) && is_link($publicPath)) {
                            unlink($publicPath);
                        }
                        
                        // Buat symlink
                        try {
                            symlink($storagePath, $publicPath);
                            Log::info('Symlink created: ' . $publicPath);
                        } catch (\Exception $e) {
                            // Fallback: copy file
                            copy($storagePath, $publicPath);
                            Log::info('File copied to public storage (fallback)');
                        }
                    } else {
                        Log::error('Failed to save file to storage');
                    }
                    
                } catch (\Exception $e) {
                    Log::error('UPLOAD EXCEPTION: ' . $e->getMessage());
                    throw $e;
                }
            }
            
            // Save user
            $user->save();
            Log::info('User saved successfully');
            
            // Reload user untuk memastikan data fresh
            $user->refresh();
            Log::info('After refresh - profile_photo: ' . ($user->profile_photo ?: 'NULL'));
            
            DB::commit();
            Log::info('Transaction committed');
            
            // Debug akhir
            Log::info('Final check - Photo URL should be: ' . 
                ($user->profile_photo ? asset('storage/profile-photos/' . $user->profile_photo) : 'NULL'));
            
            return redirect()->route('venue.pengaturan')
                ->with('success', 'Profil berhasil diperbarui!')
                ->with('activeSection', 'profil-akun')
                ->with('profile_photo_updated', true); // Tambahkan flag khusus
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());
            return redirect()->route('venue.pengaturan')
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                ->with('activeSection', 'profil-akun');
        }
    }
    
    /**
     * Update schedule venue
     */
    public function updateSchedule(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'day' => 'required|string',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('venue.pengaturan')
                ->withErrors($validator)
                ->with('activeSection', 'jadwal-slot')
                ->withInput();
        }
        
        // Cari venue user
        $venue = Venue::where('user_id', $user->id)->first();
        
        if (!$venue) {
            return redirect()->route('venue.pengaturan')
                ->with('error', 'Venue tidak ditemukan!')
                ->with('activeSection', 'jadwal-slot');
        }
        
        // Simpan jadwal ke database
        $jadwal = Jadwal::where('venue_id', $venue->id)->first();
        
        if ($jadwal) {
            // Update jadwal yang sudah ada
            $jadwal->waktu_mulai = $request->open_time . ':00';
            $jadwal->waktu_selesai = $request->close_time . ':00';
            $jadwal->catatan = 'Jadwal operasional: ' . $request->day;
            $jadwal->save();
        } else {
            // Buat jadwal baru
            Jadwal::create([
                'venue_id' => $venue->id,
                'tanggal' => now()->format('Y-m-d'),
                'waktu_mulai' => $request->open_time . ':00',
                'waktu_selesai' => $request->close_time . ':00',
                'status' => 'Available',
                'catatan' => 'Jadwal operasional: ' . $request->day
            ]);
        }
        
        return redirect()->route('venue.pengaturan')
            ->with('success', 'Jadwal berhasil diperbarui!')
            ->with('activeSection', 'jadwal-slot');
    }
    
    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        // Simpan pengaturan notifikasi ke session
        $settings = [
            'email_booking' => $request->has('email_booking'),
            'email_review' => $request->has('email_review'),
            'email_payment' => $request->has('email_payment'),
            'browser_notifications' => $request->has('browser_notifications')
        ];
        
        session(['notification_settings' => $settings]);
        
        return redirect()->route('venue.pengaturan')
            ->with('success', 'Pengaturan notifikasi berhasil diperbarui!')
            ->with('activeSection', 'notifikasi');
    }
    
    /**
     * Update security (password)
     */
    public function updateSecurity(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('venue.pengaturan')
                ->withErrors($validator)
                ->with('activeSection', 'keamanan')
                ->withInput();
        }
        
        // Validasi password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('venue.pengaturan')
                ->with('error', 'Password saat ini salah!')
                ->with('activeSection', 'keamanan');
        }
        
        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return redirect()->route('venue.pengaturan')
            ->with('success', 'Password berhasil diperbarui!')
            ->with('activeSection', 'keamanan');
    }
    
    /**
     * Logout from other sessions
     */
    public function logoutOtherSessions(Request $request)
    {
        $user = Auth::user();
        
        // Validasi password
        if (!Hash::check($request->current_password ?? '', $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah!'
            ], 401);
        }
        
        // Logout dari semua device lain
        Auth::logoutOtherDevices($request->current_password);
        
        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout dari semua sesi lain'
        ]);
    }
    
    /**
     * Direct database update test
     */
    public function testUpdate(Request $request)
    {
        $user = Auth::user();
        
        Log::info('=== DIRECT TEST UPDATE ===');
        Log::info('User ID: ' . $user->id);
        
        // Try direct DB update
        $result = DB::table('users')
            ->where('id', $user->id)
            ->update([
                'profile_photo' => 'test_' . time() . '.jpg',
                'updated_at' => now()
            ]);
            
        Log::info('Direct DB update result: ' . ($result ? 'SUCCESS' : 'FAILED'));
        
        $user->refresh();
        Log::info('After direct update - profile_photo: ' . ($user->profile_photo ?: 'NULL'));
        
        return response()->json([
            'success' => $result,
            'profile_photo' => $user->profile_photo
        ]);
    }
    
    /**
     * Check database connection and table
     */
    public function checkDb()
    {
        try {
            $user = Auth::user();
            
            // Check if we can read from users table
            $userRecord = DB::table('users')->where('id', $user->id)->first();
            
            // Check table structure
            $columns = DB::select("
                SELECT column_name, data_type, is_nullable 
                FROM information_schema.columns 
                WHERE table_name = 'users' 
                AND table_schema = 'public'
                ORDER BY ordinal_position
            ");
            
            return response()->json([
                'db_connection' => 'OK',
                'user_record' => [
                    'id' => $userRecord->id,
                    'name' => $userRecord->name,
                    'profile_photo' => $userRecord->profile_photo,
                ],
                'table_structure' => $columns
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}