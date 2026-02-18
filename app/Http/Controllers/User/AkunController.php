<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AkunController extends Controller
{
    /**
     * ✅ Display account settings page
     */
    public function index()
    {
        $user = Auth::user();
        
        \Log::info("=== AKUN PAGE (USER) ===");
        \Log::info("User ID: {$user->id}");
        \Log::info("User Email: {$user->email}");
        \Log::info("User Role: user");
        
        return view('user.akun', [
            'user' => $user,
        ]);
    }

    /**
     * ✅ UPDATE PROFILE - Edit Profil Section
     * Storage: storage/app/public/profile-photos/users/{user_id}/
     */
    public function updateProfile(Request $request)
    {
        \Log::info("=== UPDATE PROFILE START (USER) ===");
        \Log::info("Request data:", $request->except(['_token', 'profile_photo']));
        
        $user = Auth::user();
        
        // ✅ Validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-\s\(\)]+$/'],
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

            // ✅ Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                \Log::info("Profile photo detected (USER)");
                
                // ✅ ROLE-BASED PATH: users/{user_id}/
                $userFolder = 'profile-photos/users/' . $user->id;
                
                // ✅ Delete old photo if exists
                if ($user->profile_photo) {
                    // Check old path format first
                    if (Storage::exists('public/profile-photos/' . $user->profile_photo)) {
                        Storage::delete('public/profile-photos/' . $user->profile_photo);
                        \Log::info("Old photo deleted (root): {$user->profile_photo}");
                    }
                    // Check new role-based path
                    if (Storage::exists($userFolder . '/' . $user->profile_photo)) {
                        Storage::delete($userFolder . '/' . $user->profile_photo);
                        \Log::info("Old photo deleted (user folder): {$user->profile_photo}");
                    }
                }

                // ✅ Store new photo in user-specific folder
                $file = $request->file('profile_photo');
                $filename = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Store in storage/app/public/profile-photos/users/{user_id}/
                $file->storeAs($userFolder, $filename, 'public');
                
                $user->profile_photo = $filename;
                \Log::info("New photo uploaded (USER): {$filename}");
                \Log::info("Storage path: {$userFolder}/{$filename}");
            }

            $user->save();

            \Log::info("✅ Profile updated successfully (USER)!");

            return redirect()->back()
                ->with([
                    'success' => 'Profil berhasil diperbarui!',
                    'activeSection' => 'edit-profil'
                ]);

        } catch (\Exception $e) {
            \Log::error("❌ Error updating profile (USER): " . $e->getMessage());
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
        \Log::info("=== DELETE PHOTO START (USER) ===");
        
        $user = Auth::user();
        
        try {
            if ($user->profile_photo) {
                $userFolder = 'profile-photos/users/' . $user->id;
                
                // ✅ Delete from user-specific folder
                if (Storage::exists($userFolder . '/' . $user->profile_photo)) {
                    Storage::delete($userFolder . '/' . $user->profile_photo);
                    \Log::info("Photo deleted (USER): {$user->profile_photo}");
                }
                
                // Also check old location for backward compatibility
                if (Storage::exists('public/profile-photos/' . $user->profile_photo)) {
                    Storage::delete('public/profile-photos/' . $user->profile_photo);
                    \Log::info("Photo deleted (old location): {$user->profile_photo}");
                }

                $user->profile_photo = null;
                $user->save();

                \Log::info("✅ Photo removed from database (USER)");

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
            \Log::error("❌ Error deleting photo (USER): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus foto'
            ], 500);
        }
    }

    /**
     * ✅ UPDATE PASSWORD - Keamanan Section
     */
    public function updatePassword(Request $request)
    {
        \Log::info("=== UPDATE PASSWORD START (USER) ===");
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
                \Log::warning("⚠️ Wrong current password (USER) - User {$user->id}");
                
                return redirect()->back()
                    ->with([
                        'error' => 'Password saat ini salah!',
                        'activeSection' => 'keamanan'
                    ])
                    ->withInput($request->except(['current_password', 'new_password', 'new_password_confirmation']));
            }

            if (Hash::check($validated['new_password'], $user->password)) {
                \Log::warning("⚠️ New password same as current (USER) - User {$user->id}");
                
                return redirect()->back()
                    ->with([
                        'error' => 'Password baru tidak boleh sama dengan password saat ini!',
                        'activeSection' => 'keamanan'
                    ]);
            }

            $user->password = Hash::make($validated['new_password']);
            $user->save();

            \Log::info("✅ Password updated successfully (USER) - User {$user->id}");

            return redirect()->back()
                ->with([
                    'success' => 'Password berhasil diperbarui!',
                    'activeSection' => 'keamanan'
                ]);

        } catch (\Exception $e) {
            \Log::error("❌ Error updating password (USER): " . $e->getMessage());

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
        \Log::info("=== UPDATE NOTIFICATIONS START (USER) ===");
        
        $user = Auth::user();

        try {
            $notificationSettings = [
                'email_notifications' => $request->has('email_notifications'),
                'push_notifications' => $request->has('push_notifications'),
                'sms_notifications' => $request->has('sms_notifications'),
                'marketing_emails' => $request->has('marketing_emails'),
            ];

            session(['notification_settings' => $notificationSettings]);

            \Log::info("✅ Notification settings updated (USER)");

            return redirect()->back()
                ->with([
                    'success' => 'Pengaturan notifikasi berhasil diperbarui!',
                    'activeSection' => 'notifikasi'
                ]);

        } catch (\Exception $e) {
            \Log::error("❌ Error updating notifications (USER): " . $e->getMessage());

            return redirect()->back()
                ->with([
                    'error' => 'Terjadi kesalahan saat memperbarui pengaturan notifikasi.',
                    'activeSection' => 'notifikasi'
                ]);
        }
    }

    /**
     * ✅ DELETE ACCOUNT - Dangerous Action
     */
    public function deleteAccount(Request $request)
    {
        \Log::info("=== DELETE ACCOUNT START (USER) ===");
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
                \Log::warning("⚠️ Wrong password for account deletion (USER) - User {$user->id}");
                
                return redirect()->back()
                    ->with([
                        'error' => 'Password salah! Akun tidak dapat dihapus.',
                        'activeSection' => 'pengaturan'
                    ]);
            }

            // ✅ Delete profile photo from user-specific folder
            if ($user->profile_photo) {
                $userFolder = 'profile-photos/users/' . $user->id;
                
                if (Storage::exists($userFolder . '/' . $user->profile_photo)) {
                    Storage::delete($userFolder . '/' . $user->profile_photo);
                    \Log::info("Profile photo deleted (USER): {$user->profile_photo}");
                }
                
                // Delete entire user folder
                Storage::deleteDirectory($userFolder);
                \Log::info("User folder deleted: {$userFolder}");
            }

            \Log::warning("⚠️ ACCOUNT DELETED (USER) - User ID: {$user->id}, Email: {$user->email}");

            Auth::logout();
            $user->delete();

            \Log::info("✅ Account deleted successfully (USER)");

            return redirect()->route('beranda')
                ->with('success', 'Akun Anda telah berhasil dihapus.');

        } catch (\Exception $e) {
            \Log::error("❌ Error deleting account (USER): " . $e->getMessage());

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
        \Log::info("=== EXPORT DATA START (USER) ===");
        
        $user = Auth::user();

        try {
            // ✅ Build photo URL with role-based path
            $photoUrl = null;
            if ($user->profile_photo) {
                $photoUrl = asset('storage/profile-photos/users/' . $user->id . '/' . $user->profile_photo);
            }
            
            $userData = [
                'personal_info' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'profile_photo' => $photoUrl,
                    'created_at' => $user->created_at->toDateTimeString(),
                    'updated_at' => $user->updated_at->toDateTimeString(),
                ],
                'bookings' => $user->bookings ?? [],
                'favorites' => $user->favorites ?? [],
            ];

            $filename = 'user_data_' . $user->id . '_' . now()->format('Ymd_His') . '.json';
            
            \Log::info("✅ Data exported (USER) - User {$user->id}");

            return response()->json($userData)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            \Log::error("❌ Error exporting data (USER): " . $e->getMessage());

            return response()->json([
                'error' => 'Terjadi kesalahan saat mengekspor data'
            ], 500);
        }
    }
}