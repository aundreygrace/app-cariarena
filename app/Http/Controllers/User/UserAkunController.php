<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Pemesanan;
use App\Models\Venue;
use App\Models\Transaksi;
use App\Models\Notifikasi;
use App\Models\Review;

class UserAkunController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $activeSection = $request->get('section', 'edit-profil');
        
        // Get user stats from database (sesuai nama model)
        $totalPemesanan = Pemesanan::where('user_id', $user->id)->count();
        
        $venueCount = DB::table('booking')
            ->where('user_id', $user->id)
            ->distinct('venue_id')
            ->count('venue_id');
        
        $totalTransaksi = Transaksi::where('pengguna', $user->email)
            ->where('status', 'completed')
            ->sum('amount');
        
        // Get favorite venues (venues that user has booked)
        $venueIds = DB::table('booking')
            ->where('user_id', $user->id)
            ->distinct()
            ->pluck('venue_id')
            ->toArray();
            
        $favoriteVenues = collect();
        if (!empty($venueIds)) {
            $favoriteVenues = Venue::whereIn('id', $venueIds)
                ->select('id', 'name', 'category', 'status', 'address', 'rating', 'reviews_count', 'price_per_hour', 'photo')
                ->limit(10)
                ->get();
        }
        
        // Get notifications from database
        $notifications = Notifikasi::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get payment methods from transactions
        $transactions = Transaksi::where('pengguna', $user->email)
            ->where('status', 'completed')
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();
        
        // Get reviews by user
        $userReviews = Review::where('customer_name', $user->name)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Check if user has profile photo
        $hasPhoto = !empty($user->profile_photo);
        $photoUrl = null;
        
        if ($hasPhoto) {
            // Check if file exists in storage
            if (Storage::exists('public/profile-photos/' . $user->profile_photo)) {
                $photoUrl = asset('storage/profile-photos/' . $user->profile_photo);
            } elseif (file_exists(public_path('storage/profile-photos/' . $user->profile_photo))) {
                $photoUrl = asset('storage/profile-photos/' . $user->profile_photo);
            }
            
            // Add timestamp for cache busting if photo was recently updated
            if (session('profile_photo_updated')) {
                $photoUrl .= '?t=' . time();
            }
        }
        
        return view('user.akun', compact(
            'user',
            'activeSection',
            'totalPemesanan',
            'venueCount',
            'totalTransaksi',
            'favoriteVenues',
            'notifications',
            'transactions',
            'userReviews',
            'hasPhoto',
            'photoUrl'
        ));
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'venue_name' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'social_media' => 'nullable|string|max:100',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Update user data
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'] ?? $user->phone;
            $user->venue_name = $validated['venue_name'] ?? $user->venue_name;
            $user->description = $validated['description'] ?? $user->description;
            $user->social_media = $validated['social_media'] ?? $user->social_media;
            
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->profile_photo && Storage::exists('public/profile-photos/' . $user->profile_photo)) {
                    Storage::delete('public/profile-photos/' . $user->profile_photo);
                }
                
                $photo = $request->file('profile_photo');
                $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('public/profile-photos', $filename);
                $user->profile_photo = $filename;
                
                // Update name in bookings if user has bookings
                Pemesanan::where('nama_customer', $user->getOriginal('name'))
                    ->update(['nama_customer' => $validated['name']]);
                    
                // Update name in reviews if user has reviews
                Review::where('customer_name', $user->getOriginal('name'))
                    ->update(['customer_name' => $validated['name']]);
                    
                // Update name in transactions if user has transactions
                Transaksi::where('pengguna', $user->getOriginal('email'))
                    ->update(['pengguna' => $validated['email']]);
            }
            
            // Handle photo removal
            if ($request->has('remove_photo') && $request->remove_photo == '1') {
                if ($user->profile_photo && Storage::exists('public/profile-photos/' . $user->profile_photo)) {
                    Storage::delete('public/profile-photos/' . $user->profile_photo);
                }
                $user->profile_photo = null;
            }
            
            $user->save();
            
            DB::commit();
            
            // PERBAIKAN INI: Redirect ke route 'akun' bukan 'profile'
            return redirect()->route('akun', ['section' => 'edit-profil'])
                ->with('success', 'Profil berhasil diperbarui!')
                ->with('profile_photo_updated', true);
                
        } catch (\Exception $e) {
            DB::rollBack();
            // PERBAIKAN INI: Redirect ke route 'akun' bukan 'profile'
            return redirect()->route('akun', ['section' => 'edit-profil'])
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }
    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ]);
        
        $user = Auth::user();
        
        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah!'])
                ->with('activeSection', 'keamanan');
        }
        
        // Check if new password is same as current
        if (Hash::check($request->new_password, $user->password)) {
            return back()->withErrors(['new_password' => 'Password baru tidak boleh sama dengan password lama!'])
                ->with('activeSection', 'keamanan');
        }
        
        try {
            // Update password
            $user->password = Hash::make($request->new_password);
            $user->save();
            
            // Logout other sessions
            Auth::logoutOtherDevices($request->new_password);
            
            // PERBAIKAN INI: Redirect ke route 'akun' bukan 'profile'
            return redirect()->route('akun', ['section' => 'keamanan'])
                ->with('success', 'Password berhasil diubah!');
                
        } catch (\Exception $e) {
            return back()->withErrors(['new_password' => 'Gagal mengubah password: ' . $e->getMessage()])
                ->with('activeSection', 'keamanan');
        }
    }
    
    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
        ]);
        
        try {
            $user = Auth::user();
            $venueId = $request->venue_id;
            
            // Check if venue exists in user's bookings
            $hasBooked = Pemesanan::where('user_id', $user->id)
                ->where('venue_id', $venueId)
                ->exists();
            
            if (!$hasBooked) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum pernah memesan venue ini',
                ], 400);
            }
            
            // Here you can implement favorite logic
            // For example, add to a favorites table or use a JSON column in users table
            $favorites = json_decode($user->favorites ?? '[]', true);
            
            if (in_array($venueId, $favorites)) {
                // Remove from favorites
                $favorites = array_diff($favorites, [$venueId]);
                $message = 'Venue dihapus dari favorit';
            } else {
                // Add to favorites
                $favorites[] = $venueId;
                $favorites = array_unique($favorites);
                $message = 'Venue ditambahkan ke favorit';
            }
            
            $user->favorites = json_encode($favorites);
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_favorite' => in_array($venueId, $favorites)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function updateNotifications(Request $request)
    {
        try {
            $user = Auth::user();
            
            $settings = [
                'booking_confirmation' => $request->booking_confirmation ?? false,
                'schedule_changes' => $request->schedule_changes ?? false,
                'payment_receipt' => $request->payment_receipt ?? false,
                'game_reminder' => $request->game_reminder ?? false,
                'email_notifications' => $request->email_notifications ?? false,
                'push_notifications' => $request->push_notifications ?? false,
                'sound_notifications' => $request->sound_notifications ?? false,
                'vibrate_notifications' => $request->vibrate_notifications ?? false,
            ];
            
            // Save notification preferences to user settings
            $user->notification_settings = json_encode($settings);
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengaturan notifikasi berhasil disimpan',
                'settings' => $settings
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pengaturan: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function markNotificationRead($id)
    {
        try {
            $notification = Notifikasi::find($id);
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notifikasi tidak ditemukan'
                ], 404);
            }
            
            if ($notification->user_id != Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak'
                ], 403);
            }
            
            $notification->is_read = true;
            $notification->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi ditandai sebagai sudah dibaca'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function addPaymentMethod(Request $request)
    {
        $request->validate([
            'card_number' => 'required|string|size:16',
            'expiry_date' => 'required|date_format:m/y',
            'cvv' => 'required|string|size:3',
            'card_holder' => 'required|string|max:100',
            'payment_type' => 'required|in:credit_card,debit_card,ewallet'
        ]);
        
        try {
            $user = Auth::user();
            
            // Encrypt sensitive data
            $encryptedCard = encrypt($request->card_number);
            $encryptedCVV = encrypt($request->cvv);
            
            // Save payment method to database
            DB::table('user_payment_methods')->insert([
                'user_id' => $user->id,
                'card_number' => $encryptedCard,
                'expiry_date' => $request->expiry_date,
                'cvv' => $encryptedCVV,
                'card_holder' => $request->card_holder,
                'payment_type' => $request->payment_type,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Metode pembayaran berhasil ditambahkan'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan metode pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function removePaymentMethod($id)
    {
        try {
            $deleted = DB::table('user_payment_methods')
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->delete();
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Metode pembayaran berhasil dihapus'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Metode pembayaran tidak ditemukan'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus metode pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function endOtherSessions(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Get current session ID
            $currentSessionId = Session::getId();
            
            // Delete all other sessions for this user
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('id', '!=', $currentSessionId)
                ->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Semua sesi lain telah dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus sesi: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
            'confirmation' => 'required|in:HAPUS'
        ]);
        
        try {
            $user = Auth::user();
            
            DB::beginTransaction();
            
            // Soft delete user account
            $user->delete();
            
            // Logout user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            DB::commit();
            
            return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus. Terima kasih telah menggunakan layanan kami.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }
    
    public function getVenueDetails($id)
    {
        try {
            $venue = Venue::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'venue' => [
                    'id' => $venue->id,
                    'name' => $venue->name,
                    'category' => $venue->category,
                    'address' => $venue->address,
                    'rating' => $venue->rating,
                    'reviews_count' => $venue->reviews_count,
                    'price_per_hour' => $venue->price_per_hour,
                    'status' => $venue->status,
                    'photo' => $venue->photo ? asset('storage/venues/' . $venue->photo) : null,
                    'facilities' => json_decode($venue->facilities, true) ?? []
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Venue tidak ditemukan'
            ], 404);
        }
    }
    
    public function updateAppSettings(Request $request)
    {
        try {
            $user = Auth::user();
            
            $settings = [
                'dark_mode' => $request->dark_mode ?? false,
                'language' => $request->language ?? 'id',
                'timezone' => $request->timezone ?? 'Asia/Jakarta',
                'currency' => $request->currency ?? 'IDR',
                'date_format' => $request->date_format ?? 'd/m/Y',
                'time_format' => $request->time_format ?? '24h'
            ];
            
            $user->app_settings = json_encode($settings);
            $user->save();
            
            // Update session language if changed
            if ($request->language) {
                app()->setLocale($request->language);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Pengaturan aplikasi berhasil diperbarui',
                'settings' => $settings
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pengaturan: ' . $e->getMessage(),
            ], 500);
        }
    }
}