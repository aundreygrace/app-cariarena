<?php

namespace App\Http\Controllers\Venue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\Review; // Gunakan model Review
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UlasanController extends Controller
{
    /**
     * Menampilkan halaman ulasan untuk venue milik user
     */
    public function index()
    {
        try {
            // Ambil user yang sedang login
            $user = Auth::user();
            
            // Ambil venue yang dimiliki oleh user ini
            $venues = Venue::where('user_id', $user->id)->get();
            
            // Cek apakah tabel reviews ada
            if (!$this->checkReviewTableExists()) {
                return $this->handleTableNotExists($venues);
            }
            
            // Jika user tidak memiliki venue, tampilkan pesan
            if ($venues->isEmpty()) {
                return view('venue.ulasan', [
                    'ulasans' => collect(),
                    'venues' => $venues,
                    'statistics' => $this->getEmptyStatistics()
                ]);
            }
            
            // Ambil semua reviews dari venue milik user
            $reviews = Review::whereIn('venue_id', $venues->pluck('id'))
                            ->with('venue')
                            ->latest()
                            ->get();
            
            // Hitung statistik
            $statistics = $this->calculateStatistics($reviews, $venues);
            
            // Tambahkan avatar class dan initials untuk setiap review
            $reviews = $this->addAvatarData($reviews);
            
            return view('venue.ulasan', compact(
                'reviews', // Ubah dari 'ulasans' menjadi 'reviews'
                'venues',
                'statistics'
            ));
            
        } catch (\Exception $e) {
            // Fallback jika ada error
            return $this->handleError($e);
        }
    }

    /**
     * Cek apakah tabel reviews ada di database
     */
    private function checkReviewTableExists()
    {
        try {
            // Cek apakah tabel 'reviews' ada
            return DB::getSchemaBuilder()->hasTable('reviews');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Handle ketika tabel tidak ada
     */
    private function handleTableNotExists($venues)
    {
        return view('venue.ulasan', [
            'reviews' => collect(), // Ubah dari 'ulasans' menjadi 'reviews'
            'venues' => $venues,
            'statistics' => $this->getEmptyStatistics(),
            'error' => 'Tabel ulasan belum tersedia. Silakan hubungi administrator.'
        ]);
    }

    /**
     * Handle error umum
     */
    private function handleError($exception)
    {
        // Log error untuk debugging
        logger()->error('UlasanController Error: ' . $exception->getMessage());
        
        return view('venue.ulasan', [
            'reviews' => collect(), // Ubah dari 'ulasans' menjadi 'reviews'
            'venues' => collect(),
            'statistics' => $this->getEmptyStatistics(),
            'error' => 'Terjadi kesalahan saat memuat data ulasan.'
        ]);
    }

    /**
     * Statistik kosong
     */
    private function getEmptyStatistics()
    {
        return [
            'average_rating' => 0,
            'total_reviews' => 0,
            'five_star_count' => 0,
            'five_star_percent' => 0,
            'response_rate' => 0
        ];
    }

    /**
     * Menghitung statistik reviews
     */
    private function calculateStatistics($reviews, $venues)
    {
        $totalReviews = $reviews->count();
        
        if ($totalReviews === 0) {
            return $this->getEmptyStatistics();
        }

        $totalRating = $reviews->sum('rating');
        $averageRating = round($totalRating / $totalReviews, 1);
        $fiveStarCount = $reviews->where('rating', 5)->count();
        $fiveStarPercent = round(($fiveStarCount / $totalReviews) * 100);
        
        // Hitung response rate
        $responseRate = $this->calculateResponseRate($reviews);

        return [
            'average_rating' => $averageRating,
            'total_reviews' => $totalReviews,
            'five_star_count' => $fiveStarCount,
            'five_star_percent' => $fiveStarPercent,
            'response_rate' => $responseRate
        ];
    }

    /**
     * Menghitung tingkat respons
     */
    private function calculateResponseRate($reviews)
    {
        // Hitung berdasarkan reviews yang sudah dibalas
        $repliedCount = $reviews->whereNotNull('reply_message')->count();
        $totalCount = $reviews->count();
        
        return $totalCount > 0 ? round(($repliedCount / $totalCount) * 100) : 0;
    }

    /**
     * Tambahkan data avatar untuk reviews
     */
    private function addAvatarData($reviews)
    {
        $avatarClasses = [
            'avatar-red', 'avatar-blue', 'avatar-green', 'avatar-yellow', 
            'avatar-purple', 'avatar-pink', 'avatar-indigo', 'avatar-teal', 
            'avatar-orange', 'avatar-cyan'
        ];
        
        return $reviews->map(function($review, $index) use ($avatarClasses) {
            $review->avatarClass = $avatarClasses[$index % count($avatarClasses)];
            $review->initials = $this->getInitials($review->customer_name);
            return $review;
        });
    }

    /**
     * Mendapatkan inisial dari nama customer
     */
    private function getInitials($name)
    {
        if (empty($name)) {
            return 'GU';
        }
        
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty(trim($word))) {
                $initials .= strtoupper(substr(trim($word), 0, 1));
            }
        }
        
        return substr($initials, 0, 2) ?: 'GU';
    }

    /**
     * Filter reviews berdasarkan venue dan rating (AJAX)
     */
    public function filter(Request $request)
    {
        try {
            $user = Auth::user();
            $venues = Venue::where('user_id', $user->id)->get();
            
            if (!$this->checkReviewTableExists() || $venues->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'reviews' => [], // Ubah dari 'ulasans' menjadi 'reviews'
                    'statistics' => $this->getEmptyStatistics()
                ]);
            }
            
            $query = Review::whereIn('venue_id', $venues->pluck('id'))
                            ->with('venue');
            
            // Filter berdasarkan venue
            if ($request->has('venue_id') && $request->venue_id !== 'all') {
                $query->where('venue_id', $request->venue_id);
            }
            
            // Filter berdasarkan rating
            if ($request->has('rating') && $request->rating !== 'all') {
                $query->where('rating', $request->rating);
            }
            
            $reviews = $query->latest()->get();
            $reviews = $this->addAvatarData($reviews);
            
            return response()->json([
                'success' => true,
                'reviews' => $reviews, // Ubah dari 'ulasans' menjadi 'reviews'
                'statistics' => $this->calculateStatistics($reviews, $venues)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memfilter data.',
                'reviews' => [], // Ubah dari 'ulasans' menjadi 'reviews'
                'statistics' => $this->getEmptyStatistics()
            ], 500);
        }
    }

    /**
     * Menampilkan form untuk membalas review
     */
    public function showReplyForm($id)
    {
        try {
            $review = Review::with('venue')->findOrFail($id);
            
            // Pastikan review ini milik venue user yang login
            $userVenueIds = Venue::where('user_id', Auth::id())->pluck('id');
            if (!$userVenueIds->contains($review->venue_id)) {
                abort(403, 'Unauthorized action.');
            }
            
            return view('venue.reply_ulasan', compact('review'));
            
        } catch (\Exception $e) {
            return redirect()->route('venue.ulasan.index')
                            ->with('error', 'Review tidak ditemukan.');
        }
    }

    /**
     * Menyimpan balasan untuk review
     */
    public function storeReply(Request $request, $id)
    {
        $request->validate([
            'reply_message' => 'required|string|max:1000'
        ]);
        
        try {
            $review = Review::findOrFail($id);
            
            // Pastikan review ini milik venue user yang login
            $userVenueIds = Venue::where('user_id', Auth::id())->pluck('id');
            if (!$userVenueIds->contains($review->venue_id)) {
                abort(403, 'Unauthorized action.');
            }
            
            // Simpan balasan
            $review->update([
                'reply_message' => $request->reply_message,
                'replied_at' => now(),
                'replied_by' => Auth::id()
            ]);
            
            return redirect()->route('venue.ulasan.index')
                            ->with('success', 'Balasan berhasil dikirim.');
            
        } catch (\Exception $e) {
            return redirect()->route('venue.ulasan.index')
                            ->with('error', 'Gagal mengirim balasan.');
        }
    }

    /**
     * Menghapus balasan review
     */
    public function deleteReply($id)
    {
        try {
            $review = Review::findOrFail($id);
            
            // Pastikan review ini milik venue user yang login
            $userVenueIds = Venue::where('user_id', Auth::id())->pluck('id');
            if (!$userVenueIds->contains($review->venue_id)) {
                abort(403, 'Unauthorized action.');
            }
            
            $review->update([
                'reply_message' => null,
                'replied_at' => null,
                'replied_by' => null
            ]);
            
            return redirect()->route('venue.ulasan.index')
                            ->with('success', 'Balasan berhasil dihapus.');
            
        } catch (\Exception $e) {
            return redirect()->route('venue.ulasan.index')
                            ->with('error', 'Gagal menghapus balasan.');
        }
    }
}