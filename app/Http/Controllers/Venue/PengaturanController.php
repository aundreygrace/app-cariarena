<?php

namespace App\Http\Controllers\Venue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Venue;

class PengaturanController extends Controller
{
    /**
     * ✅ Display settings page
     */
    public function index()
    {
        $user = Auth::user();
        
        \Log::info("=== PENGATURAN PAGE (OWNER) ===");
        \Log::info("User ID: {$user->id}");
        \Log::info("User Email: {$user->email}");
        \Log::info("User Role: owner");
        
        // Ambil venue user
        $venue = Venue::where('user_id', $user->id)->first();
        
        return view('venue.pengaturan', compact('user', 'venue'));
    }

    /**
     * ✅ UPDATE PROFILE - Owner Venue
     * Storage: storage/app/public/profile-photos/owners/{user_id}/
     */
    public function updateProfile(Request $request)
    {
        \Log::info("=== UPDATE PROFILE START (OWNER) ===");
        \Log::info("Request data:", $request->except(['_token', 'profile_photo']));
        
        $user = Auth::user();
        
        // ✅ Validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-\s\(\)]+$/'],
            'venue_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];

        $messages = [
            'name.required' => 'Nama wajib diisi',
            'name.min' => 'Nama minimal 3 karakter',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan oleh akun lain',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'profile_photo.image' => 'File harus berupa gambar',
            'profile_photo.mimes' => 'Foto harus berformat: jpeg, png, jpg, atau gif',
            'profile_photo.max' => 'Ukuran foto maksimal 2MB',
        ];

        $validated = $request->validate($rules, $messages);

        try {
            // ✅ Update basic info
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'] ?? null;
            $user->venue_name = $validated['venue_name'] ?? null;
            $user->description = $validated['description'] ?? null;

            // ✅ Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                \Log::info("Profile photo detected (OWNER)");
                
                // ✅ ROLE-BASED PATH: owners/{user_id}/
                $ownerFolder = 'profile-photos/owners/' . $user->id;
                
                // ✅ Delete old photo if exists
                if ($user->profile_photo) {
                    // Check old path format first
                    if (Storage::exists('public/profile-photos/' . $user->profile_photo)) {
                        Storage::delete('public/profile-photos/' . $user->profile_photo);
                        \Log::info("Old photo deleted (root): {$user->profile_photo}");
                    }
                    // Check new role-based path
                    if (Storage::disk('public')->exists($ownerFolder . '/' . $user->profile_photo)) {
                        Storage::delete($ownerFolder . '/' . $user->profile_photo);
                        \Log::info("Old photo deleted (owner folder): {$user->profile_photo}");
                    }
                }

                // ✅ Store new photo in owner-specific folder
                $file = $request->file('profile_photo');
                $filename = 'owner_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Store in storage/app/public/profile-photos/owners/{user_id}/
                $file->storeAs($ownerFolder, $filename, 'public');
                
                $user->profile_photo = $filename;
                \Log::info("New photo uploaded (OWNER): {$filename}");
                \Log::info("Storage path: {$ownerFolder}/{$filename}");
            }

            $user->save();

            \Log::info("✅ Profile updated successfully (OWNER)!");

            return redirect()->back()
                ->with([
                    'success' => 'Profil berhasil diperbarui!',
                    'activeSection' => 'edit-profil'
                ]);

        } catch (\Exception $e) {
            \Log::error("❌ Error updating profile (OWNER): " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());

            return redirect()->back()
                ->with([
                    'error' => 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage(),
                    'activeSection' => 'edit-profil'
                ])
                ->withInput();
        }
    }

    /**
     * ✅ DELETE PROFILE PHOTO
     */
    public function deletePhoto(Request $request)
    {
        \Log::info("=== DELETE PHOTO START (OWNER) ===");
        
        $user = Auth::user();
        
        try {
            if ($user->profile_photo) {
                $ownerFolder = 'profile-photos/owners/' . $user->id;
                
                // ✅ Delete from owner-specific folder
                if (Storage::disk('public')->exists($ownerFolder . '/' . $user->profile_photo)) {
                    Storage::disk('public')->delete($ownerFolder . '/' . $user->profile_photo);
                }
                
                // Also check old location for backward compatibility
                if (Storage::exists('public/profile-photos/' . $user->profile_photo)) {
                    Storage::delete('public/profile-photos/' . $user->profile_photo);
                    \Log::info("Photo deleted (old location): {$user->profile_photo}");
                }

                $user->profile_photo = null;
                $user->save();

                \Log::info("✅ Photo removed from database (OWNER)");

                return response()->json([
                    'success' => true,
                    'message' => 'Foto profil berhasil dihapus'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada foto untuk dihapus'
            ]);

        } catch (\Exception $e) {
            \Log::error("❌ Error deleting photo (OWNER): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus foto'
            ], 500);
        }
    }

    /**
     * ✅ UPDATE PASSWORD
     */
    public function updatePassword(Request $request)
    {
        \Log::info("=== UPDATE PASSWORD START (OWNER) ===");
        \Log::info("User ID: " . Auth::id());
        
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
            'new_password.min' => 'Password minimal 8 karakter',
        ]);

        try {
            if (!Hash::check($validated['current_password'], $user->password)) {
                \Log::warning("⚠️ Wrong current password (OWNER) - User {$user->id}");
                
                return redirect()->back()
                    ->with([
                        'error' => 'Password saat ini salah!',
                        'activeSection' => 'keamanan'
                    ])
                    ->withInput($request->except(['current_password', 'new_password', 'new_password_confirmation']));
            }

            if (Hash::check($validated['new_password'], $user->password)) {
                \Log::warning("⚠️ New password same as current (OWNER) - User {$user->id}");
                
                return redirect()->back()
                    ->with([
                        'error' => 'Password baru tidak boleh sama dengan password saat ini!',
                        'activeSection' => 'keamanan'
                    ]);
            }

            $user->password = Hash::make($validated['new_password']);
            $user->save();

            \Log::info("✅ Password updated successfully (OWNER) - User {$user->id}");

            return redirect()->back()
                ->with([
                    'success' => 'Password berhasil diperbarui!',
                    'activeSection' => 'keamanan'
                ]);

        } catch (\Exception $e) {
            \Log::error("❌ Error updating password (OWNER): " . $e->getMessage());

            return redirect()->back()
                ->with([
                    'error' => 'Terjadi kesalahan saat memperbarui password.',
                    'activeSection' => 'keamanan'
                ]);
        }
    }

    /**
     * ✅ UPDATE NOTIFICATION SETTINGS
     */
    public function updateNotifications(Request $request)
    {
        \Log::info("=== UPDATE NOTIFICATIONS START (OWNER) ===");
        
        $user = Auth::user();

        try {
            $notificationSettings = [
                'email_booking' => $request->has('email_booking'),
                'email_review' => $request->has('email_review'),
                'email_payment' => $request->has('email_payment'),
                'browser_notifications' => $request->has('browser_notifications'),
            ];

            session(['notification_settings_owner' => $notificationSettings]);

            \Log::info("✅ Notification settings updated (OWNER)");

            return redirect()->back()
                ->with([
                    'success' => 'Pengaturan notifikasi berhasil diperbarui!',
                    'activeSection' => 'notifikasi'
                ]);

        } catch (\Exception $e) {
            \Log::error("❌ Error updating notifications (OWNER): " . $e->getMessage());

            return redirect()->back()
                ->with([
                    'error' => 'Terjadi kesalahan saat memperbarui pengaturan notifikasi.',
                    'activeSection' => 'notifikasi'
                ]);
        }
    }

    /**
     * ✅ DELETE ACCOUNT
     */
    public function deleteAccount(Request $request)
    {
        \Log::info("=== DELETE ACCOUNT START (OWNER) ===");
        \Log::info("User ID: " . Auth::id());
    
        $user = Auth::user();
    
        $request->validate([
            'password' => ['required', 'string'],
            'confirmation' => ['required', 'accepted'],
        ], [
            'password.required' => 'Password wajib diisi untuk menghapus akun',
            'confirmation.accepted' => 'Anda harus mencentang konfirmasi untuk menghapus akun',
        ]);
    
        try {
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->back()
                    ->with([
                        'error' => 'Password salah! Akun tidak dapat dihapus.',
                        'activeSection' => 'pengaturan'
                    ]);
            }
    
            // ✅ OWNER FOLDER
            $ownerFolder = 'profile-photos/owners/' . $user->id;
    
            // ✅ Delete photo folder (if exists)
            if ($user->profile_photo && Storage::disk('public')->exists($ownerFolder)) {
                Storage::disk('public')->deleteDirectory($ownerFolder);
                \Log::info("Owner photo folder deleted: {$ownerFolder}");
            }
    
            Auth::logout();
            $user->delete();
    
            \Log::warning("⚠️ ACCOUNT DELETED (OWNER) - User ID: {$user->id}");
    
            return redirect()->route('beranda')
                ->with('success', 'Akun Anda telah berhasil dihapus.');
    
        } catch (\Exception $e) {
            \Log::error("❌ Error deleting account (OWNER): " . $e->getMessage());
    
            return redirect()->back()
                ->with([
                    'error' => 'Terjadi kesalahan saat menghapus akun.',
                    'activeSection' => 'pengaturan'
                ]);
        }
    }
    

    /**
     * ✅ EXPORT USER DATA
     */
    public function exportData(Request $request)
    {
        \Log::info("=== EXPORT DATA START (OWNER) ===");
        
        $user = Auth::user();

        try {
            // ✅ Build photo URL with role-based path
            $photoUrl = null;
            if ($user->profile_photo) {
                $photoUrl = asset('storage/profile-photos/owners/' . $user->id . '/' . $user->profile_photo);
            }
            
            $userData = [
                'personal_info' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'venue_name' => $user->venue_name,
                    'description' => $user->description,
                    'profile_photo' => $photoUrl,
                    'created_at' => $user->created_at->toDateTimeString(),
                    'updated_at' => $user->updated_at->toDateTimeString(),
                ],
                'venues' => $user->venues ?? [],
            ];

            $filename = 'owner_data_' . $user->id . '_' . now()->format('Ymd_His') . '.json';
            
            \Log::info("✅ Data exported (OWNER) - User {$user->id}");

            return response()->json($userData)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            \Log::error("❌ Error exporting data (OWNER): " . $e->getMessage());

            return response()->json([
                'error' => 'Terjadi kesalahan saat mengekspor data'
            ], 500);
        }
    }
}