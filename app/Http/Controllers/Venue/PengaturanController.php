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
    public function index()
    {
        $user = Auth::user();
        $venue = Venue::where('user_id', $user->id)->first();
        return view('venue.pengaturan', compact('user', 'venue'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255', 'min:3'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone'         => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-\s\(\)]+$/'],
            'venue_name'    => ['nullable', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], [
            'name.required'         => 'Nama wajib diisi',
            'name.min'              => 'Nama minimal 3 karakter',
            'email.required'        => 'Email wajib diisi',
            'email.email'           => 'Format email tidak valid',
            'email.unique'          => 'Email sudah digunakan oleh akun lain',
            'phone.regex'           => 'Format nomor telepon tidak valid',
            'profile_photo.image'   => 'File harus berupa gambar',
            'profile_photo.mimes'   => 'Foto harus berformat: jpeg, png, jpg, atau gif',
            'profile_photo.max'     => 'Ukuran foto maksimal 2MB',
        ]);

        try {
            $user->name        = $validated['name'];
            $user->email       = $validated['email'];
            $user->phone       = $validated['phone'] ?? null;
            $user->venue_name  = $validated['venue_name'] ?? null;
            $user->description = $validated['description'] ?? null;

            if ($request->hasFile('profile_photo')) {
                // Hapus foto lama
                if ($user->profile_photo) {
                    $this->deleteProfilePhoto($user->id, $user->profile_photo, 'owners');
                }

                // Upload foto baru
                $filename = $this->uploadProfilePhoto(
                    $request->file('profile_photo'),
                    $user->id,
                    'owners'
                );
                $user->profile_photo = $filename;
            }

            $user->save();

            return redirect()->back()->with([
                'success' => 'Profil berhasil diperbarui!',
                'activeSection' => 'edit-profil'
            ]);

        } catch (\Exception $e) {
            \Log::error("Error updating profile (OWNER): " . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage(),
                'activeSection' => 'edit-profil'
            ])->withInput();
        }
    }

    public function deletePhoto(Request $request)
    {
        $user = Auth::user();
        
        try {
            if ($user->profile_photo) {
                $this->deleteProfilePhoto($user->id, $user->profile_photo, 'owners');
                $user->profile_photo = null;
                $user->save();
            }

            return response()->json(['success' => true, 'message' => 'Foto profil berhasil dihapus']);
        } catch (\Exception $e) {
            \Log::error("Error deleting photo (OWNER): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus foto'], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => [
                'required', 'string', 'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required'     => 'Password baru wajib diisi',
            'new_password.confirmed'    => 'Konfirmasi password tidak cocok',
        ]);

        try {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()->with([
                    'error' => 'Password saat ini salah!',
                    'activeSection' => 'keamanan'
                ])->withInput($request->except(['current_password', 'new_password', 'new_password_confirmation']));
            }

            if (Hash::check($validated['new_password'], $user->password)) {
                return redirect()->back()->with([
                    'error' => 'Password baru tidak boleh sama dengan password saat ini!',
                    'activeSection' => 'keamanan'
                ]);
            }

            $user->password = Hash::make($validated['new_password']);
            $user->save();

            return redirect()->back()->with([
                'success' => 'Password berhasil diperbarui!',
                'activeSection' => 'keamanan'
            ]);

        } catch (\Exception $e) {
            \Log::error("Error updating password (OWNER): " . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Terjadi kesalahan saat memperbarui password.',
                'activeSection' => 'keamanan'
            ]);
        }
    }

    public function updateNotifications(Request $request)
    {
        try {
            $notificationSettings = [
                'email_booking'        => $request->has('email_booking'),
                'email_review'         => $request->has('email_review'),
                'email_payment'        => $request->has('email_payment'),
                'browser_notifications'=> $request->has('browser_notifications'),
            ];
            session(['notification_settings_owner' => $notificationSettings]);

            return redirect()->back()->with([
                'success' => 'Pengaturan notifikasi berhasil diperbarui!',
                'activeSection' => 'notifikasi'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => 'Terjadi kesalahan saat memperbarui pengaturan notifikasi.',
                'activeSection' => 'notifikasi'
            ]);
        }
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password'     => ['required', 'string'],
            'confirmation' => ['required', 'accepted'],
        ], [
            'password.required'     => 'Password wajib diisi untuk menghapus akun',
            'confirmation.accepted' => 'Anda harus mencentang konfirmasi untuk menghapus akun',
        ]);

        try {
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->back()->with([
                    'error' => 'Password salah! Akun tidak dapat dihapus.',
                    'activeSection' => 'pengaturan'
                ]);
            }

            // Hapus folder foto profil
            if ($user->profile_photo) {
                $this->deleteProfilePhotoFolder($user->id, 'owners');
            }

            Auth::logout();
            $user->delete();

            return redirect()->route('beranda')->with('success', 'Akun Anda telah berhasil dihapus.');

        } catch (\Exception $e) {
            \Log::error("Error deleting account (OWNER): " . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Terjadi kesalahan saat menghapus akun.',
                'activeSection' => 'pengaturan'
            ]);
        }
    }

    public function exportData(Request $request)
    {
        $user = Auth::user();

        try {
            $userData = [
                'personal_info' => [
                    'name'         => $user->name,
                    'email'        => $user->email,
                    'phone'        => $user->phone,
                    'venue_name'   => $user->venue_name,
                    'description'  => $user->description,
                    'profile_photo'=> $user->profile_photo_url,
                    'created_at'   => $user->created_at->toDateTimeString(),
                    'updated_at'   => $user->updated_at->toDateTimeString(),
                ],
                'venues' => $user->venues ?? [],
            ];

            $filename = 'owner_data_' . $user->id . '_' . now()->format('Ymd_His') . '.json';

            return response()->json($userData)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            \Log::error("Error exporting data (OWNER): " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengekspor data'], 500);
        }
    }

    // ===================== PRIVATE HELPERS =====================

    /**
     * ✅ Upload profile photo ke S3 (Supabase) atau local
     */
    private function uploadProfilePhoto($file, $userId, $role)
    {
        $disk = config('filesystems.default');
        $filename = $role . '_' . $userId . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = "profile-photos/{$role}/{$userId}/{$filename}";

        if ($disk === 's3') {
            Storage::disk('s3')->put($path, file_get_contents($file), 'public');
        } else {
            $file->storeAs("profile-photos/{$role}/{$userId}", $filename, 'public');
        }

        return $filename;
    }

    /**
     * ✅ Hapus profile photo dari S3 (Supabase) atau local
     */
    private function deleteProfilePhoto($userId, $filename, $role)
    {
        if (empty($filename)) return;

        $path = "profile-photos/{$role}/{$userId}/{$filename}";
        $disk = config('filesystems.default');

        if ($disk === 's3') {
            Storage::disk('s3')->delete($path);
        } else {
            Storage::disk('public')->delete($path);
            // Backward compat: cek lokasi lama
            Storage::disk('public')->delete("profile-photos/{$filename}");
        }
    }

    /**
     * ✅ Hapus seluruh folder foto profil
     */
    private function deleteProfilePhotoFolder($userId, $role)
    {
        $folderPath = "profile-photos/{$role}/{$userId}";
        $disk = config('filesystems.default');

        if ($disk === 's3') {
            // Hapus semua file dalam folder di S3
            $files = Storage::disk('s3')->files($folderPath);
            Storage::disk('s3')->delete($files);
        } else {
            Storage::disk('public')->deleteDirectory($folderPath);
        }
    }
}