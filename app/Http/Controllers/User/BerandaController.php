<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Venue;

class BerandaController extends Controller
{
    protected $fallbackVenues;

    public function __construct()
    {
        // Data fallback
        $this->fallbackVenues = collect([
            [
                'id' => 1,
                'name' => 'Corner Futsal', 
                'location' => 'Jl. Bluru Kidul, Sidoarjo', 
                'sport' => 'Futsal', 
                'category' => 'futsal',
                'price' => '15.000', 
                'rating' => 4.2, 
                'status' => 'Tersedia', 
                'reviews_count' => 5,
                'image' => 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'
            ],
            [
                'id' => 2,
                'name' => 'GOR Badminton Senayan', 
                'location' => 'Senayan, Jakarta Pusat', 
                'sport' => 'Badminton', 
                'category' => 'badminton',
                'price' => '120.000', 
                'rating' => 4.6, 
                'status' => 'Tersedia', 
                'reviews_count' => 8,
                'image' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'
            ],
        ]);
    }

    public function index()
    {
        \Log::info('BerandaController accessed by user: ' . (auth()->check() ? auth()->id() : 'guest'));

        $venues = $this->fallbackVenues;

        try {
            // Test database connection
            DB::connection()->getPdo();
            \Log::info('Database connection successful');
            
            // Get venues from database - SESUAIKAN DENGAN KOLOM TABEL
            $dbVenues = Venue::where('status', 'Aktif') // Sesuaikan dengan nilai status yang ada
                ->orderBy('rating', 'desc')
                ->orderBy('reviews_count', 'desc')
                ->limit(6)
                ->get();

            if ($dbVenues->isNotEmpty()) {
                $venues = $dbVenues->map(function($venue) {
                    // Gunakan kolom yang sesuai dengan struktur tabel
                    return [
                        'id' => $venue->id,
                        'name' => $venue->name,
                        'location' => $this->formatLocation($venue->address),
                        'sport' => $venue->category,
                        'category' => strtolower(str_replace(' ', '-', $venue->category)),
                        'price' => number_format($venue->price_per_hour, 0, ',', '.'),
                        'rating' => $venue->rating ?? 0, // Langsung dari kolom rating
                        'status' => 'Tersedia',
                        'reviews_count' => $venue->reviews_count ?? 0, // Langsung dari kolom reviews_count
                        'image' => $venue->photo ?? $this->getDefaultImage($venue->category) // Kolom photo bukan image_url
                    ];
                });
                \Log::info('Loaded ' . $venues->count() . ' venues from database');
            } else {
                \Log::info('No venues in database, using fallback data');
            }

        } catch (\Exception $e) {
            \Log::error('Database error in BerandaController: ' . $e->getMessage());
            // Tetap menggunakan fallback data jika error
            $venues = $this->fallbackVenues;
        }

        // Ambil notifikasi untuk user yang login
        $notifications = [];
        if (auth()->check()) {
            try {
                $notifications = DB::table('notifications')
                    ->where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                \Log::error('Error fetching notifications: ' . $e->getMessage());
            }
        }

        return view('user.beranda', compact('venues', 'notifications'));
    }

    public function filterByCategory($category)
    {
        try {
            $query = Venue::where('status', 'Aktif');

            if ($category !== 'all') {
                $dbCategory = $this->convertCategoryToDatabase($category);
                $query->where('category', $dbCategory);
            }

            $venues = $query->orderBy('rating', 'desc')
                ->orderBy('reviews_count', 'desc')
                ->get()
                ->map(function($venue) {
                    return [
                        'id' => $venue->id,
                        'name' => $venue->name,
                        'location' => $this->formatLocation($venue->address),
                        'sport' => $venue->category,
                        'category' => strtolower(str_replace(' ', '-', $venue->category)),
                        'price' => number_format($venue->price_per_hour, 0, ',', '.'),
                        'rating' => $venue->rating ?? 0,
                        'status' => 'Tersedia',
                        'reviews_count' => $venue->reviews_count ?? 0,
                        'image' => $venue->photo ?? $this->getDefaultImage($venue->category)
                    ];
                });

            return response()->json($venues);

        } catch (\Exception $e) {
            \Log::error('Filter error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function search(Request $request)
    {
        try {
            $searchTerm = $request->input('q');

            $venues = Venue::where('status', 'Aktif')
                ->where(function($query) use ($searchTerm) {
                    $query->where('name', 'ILIKE', "%{$searchTerm}%")
                        ->orWhere('category', 'ILIKE', "%{$searchTerm}%")
                        ->orWhere('address', 'ILIKE', "%{$searchTerm}%");
                })
                ->orderBy('rating', 'desc')
                ->orderBy('reviews_count', 'desc')
                ->get()
                ->map(function($venue) {
                    return [
                        'id' => $venue->id,
                        'name' => $venue->name,
                        'location' => $this->formatLocation($venue->address),
                        'sport' => $venue->category,
                        'category' => strtolower(str_replace(' ', '-', $venue->category)),
                        'price' => number_format($venue->price_per_hour, 0, ',', '.'),
                        'rating' => $venue->rating ?? 0,
                        'status' => 'Tersedia',
                        'reviews_count' => $venue->reviews_count ?? 0,
                        'image' => $venue->photo ?? $this->getDefaultImage($venue->category)
                    ];
                });

            return response()->json($venues);

        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    // Method untuk mark notification as read
    public function markNotificationAsRead($id)
    {
        try {
            DB::table('notifications')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->update(['is_read' => true]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    // Method untuk get semua notifications
    public function getNotifications()
    {
        try {
            $notifications = DB::table('notifications')
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            \Log::error('Error fetching all notifications: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    private function formatLocation($address)
    {
        if (empty($address)) {
            return 'Lokasi tidak tersedia';
        }
        
        $address = trim($address);
        $parts = explode(',', $address);
        if (count($parts) > 1) {
            return trim($parts[0]) . ', ' . trim($parts[1]);
        }
        return $address;
    }

    private function convertCategoryToDatabase($urlCategory)
    {
        $categoryMap = [
            'futsal' => 'Futsal',
            'badminton' => 'Badminton',
            'sepak-bola' => 'Sepak Bola',
            'basket' => 'Basket',
            'bulu-tangkis' => 'Badminton',
            'tennis' => 'Tennis',
            'voli' => 'Voli'
        ];

        return $categoryMap[$urlCategory] ?? $urlCategory;
    }

    private function getDefaultImage($category)
    {
        $defaultImages = [
            'Futsal' => 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            'Badminton' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            'Sepak Bola' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            'Basket' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            'Tennis' => 'https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            'Voli' => 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
        ];

        return $defaultImages[$category] ?? 'https://images.unsplash.com/photo-1551632811-561732d1e306?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60';
    }
}