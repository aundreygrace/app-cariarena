<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User; // TAMBAHKAN INI
use App\Models\Pemesanan; // TAMBAHKAN INI
use App\Models\Venue; // TAMBAHKAN INI


class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transactions'; // Nama table di database

    protected $fillable = [
        'transaction_number',
        'customer_id', 
        'pengguna',
        'nama_venue',
        'metode_pembayaran',
        'amount',
        'transaction_date',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ========== ACCESSORS ==========
    
    /**
     * Accessor untuk format status transaksi
     */
    public function getStatusFormattedAttribute()
    {
        if (!$this->status) return 'Unknown';
        
        $statusMap = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan',
            'completed' => 'Selesai',
            'processing' => 'Diproses'
        ];

        return $statusMap[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Accessor untuk format amount (rupiah)
     */
    public function getAmountFormattedAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function booking()
    {
        return $this->belongsTo(
            Pemesanan::class,
            'booking_id',
            'id'
        );
    }


    /**
     * Accessor untuk format tanggal transaksi
     */
    public function getTanggalTransaksiFormattedAttribute()
    {
        try {
            return $this->transaction_date ? Carbon::parse($this->transaction_date)->translatedFormat('d F Y H:i') : '-';
        } catch (\Exception $e) {
            return $this->transaction_date ? date('d F Y H:i', strtotime($this->transaction_date)) : '-';
        }
    }

    /**
     * Accessor untuk format tanggal singkat
     */
    public function getTanggalSingkatAttribute()
    {
        try {
            return $this->transaction_date ? Carbon::parse($this->transaction_date)->translatedFormat('d M Y') : '-';
        } catch (\Exception $e) {
            return $this->transaction_date ? date('d M Y', strtotime($this->transaction_date)) : '-';
        }
    }

    /**
     * Accessor untuk waktu relatif
     */
    public function getWaktuRelatifAttribute()
    {
        return $this->transaction_date ? $this->transaction_date->diffForHumans() : '';
    }

    /**
     * Accessor untuk CSS class status
     */
    public function getCssStatusAttribute()
    {
        if (!$this->status) return 'default';
        
        $statusMap = [
            'pending' => 'pending',
            'paid' => 'success',
            'failed' => 'danger',
            'cancelled' => 'warning',
            'refunded' => 'info',
            'completed' => 'completed',
            'processing' => 'processing'
        ];
        
        return $statusMap[$this->status] ?? 'default';
    }

    /**
     * Accessor untuk warna badge status
     */
    public function getColorStatusAttribute()
    {
        if (!$this->status) return 'secondary';
        
        $colors = [
            'pending' => 'warning',
            'paid' => 'success',
            'failed' => 'danger',
            'cancelled' => 'secondary',
            'refunded' => 'info',
            'completed' => 'primary',
            'processing' => 'info'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Accessor untuk icon status
     */
    public function getIconStatusAttribute()
    {
        if (!$this->status) return '📋';
        
        $icons = [
            'pending' => '⏳',
            'paid' => '✅',
            'failed' => '❌',
            'cancelled' => '🚫',
            'refunded' => '↩️',
            'completed' => '🏁',
            'processing' => '🔄'
        ];

        return $icons[$this->status] ?? '📋';
    }

    /**
     * Accessor untuk label status frontend
     */
    public function getLabelStatusAttribute()
    {
        if (!$this->status) return 'Unknown';
        
        $labelMap = [
            'pending' => 'Menunggu',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan',
            'completed' => 'Selesai',
            'processing' => 'Diproses'
        ];
        
        return $labelMap[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Accessor untuk metode pembayaran formatted
     */
    public function getMetodePembayaranFormattedAttribute()
    {
        if (!$this->metode_pembayaran) return 'Unknown';
        
        $metode = [
            'cash' => 'Tunai',
            'credit_card' => 'Kartu Kredit',
            'debit_card' => 'Kartu Debit',
            'transfer' => 'Transfer Bank',
            'ewallet' => 'E-Wallet',
            'qris' => 'QRIS'
        ];

        return $metode[$this->metode_pembayaran] ?? ucfirst($this->metode_pembayaran);
    }

    /**
     * Accessor untuk data pemesanan dengan fallback
     */
    public function getPemesananDataAttribute()
    {
        if ($this->relationLoaded('pemesanan') && $this->pemesanan) {
            return $this->pemesanan;
        }
        
        return (object)[
            'id' => $this->customer_id,
            'nama_customer' => $this->pengguna,
            'total_biaya' => $this->amount,
            'status' => 'Terkonfirmasi'
        ];
    }

    /**
     * Accessor untuk data venue dengan fallback
     */
    public function getVenueDataAttribute()
    {
        if ($this->relationLoaded('venue') && $this->venue) {
            return $this->venue;
        }
        
        return (object)[
            'name' => $this->nama_venue,
            'location' => 'Lokasi Tidak Diketahui'
        ];
    }

    /**
     * Accessor untuk data user dengan fallback
     */
    public function getUserDataAttribute()
    {
        if ($this->relationLoaded('user') && $this->user) {
            return $this->user;
        }
        
        return (object)[
            'name' => $this->pengguna,
            'email' => null,
            'phone' => null
        ];
    }

    // ========== RELASI DENGAN TABEL LAIN ==========
    


    /**
     * Relasi dengan model User melalui pengguna (nama)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'pengguna', 'name');
    }

    /**
     * Relasi dengan model Venue melalui nama_venue
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'nama_venue', 'name');
    }

    // ========== SCOPE UNTUK SEMUA ROLE ==========

    /**
     * Scope untuk transaksi sukses (paid, completed)
     */
    public function scopeSukses($query)
    {
        return $query->whereIn('status', ['paid', 'completed']);
    }

    /**
     * Scope untuk transaksi pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk transaksi gagal
     */
    public function scopeGagal($query)
    {
        return $query->whereIn('status', ['failed', 'cancelled']);
    }

    /**
     * Scope untuk transaksi hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('transaction_date', now()->toDateString());
    }

    /**
     * Scope untuk transaksi minggu ini
     */
    public function scopeMingguIni($query)
    {
        return $query->whereBetween('transaction_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope untuk transaksi bulan ini
     */
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('transaction_date', now()->month)
                    ->whereYear('transaction_date', now()->year);
    }

    /**
     * Scope untuk transaksi berdasarkan metode pembayaran
     */
    public function scopeByMetodePembayaran($query, $metode)
    {
        if (is_array($metode)) {
            return $query->whereIn('metode_pembayaran', $metode);
        }
        return $query->where('metode_pembayaran', $metode);
    }

    /**
     * Scope untuk transaksi berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        if (is_array($status)) {
            return $query->whereIn('status', $status);
        }
        return $query->where('status', $status);
    }

    /**
     * Scope untuk transaksi berdasarkan rentang tanggal
     */
    public function scopeRentangTanggal($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    /**
     * Scope untuk transaksi terbaru
     */
    public function scopeTerbaru($query, $limit = 10)
    {
        return $query->orderBy('transaction_date', 'desc')->limit($limit);
    }

    /**
     * Scope untuk transaksi berdasarkan customer_id (pemesanan)
     */
    public function scopeByPemesanan($query, $pemesananId)
    {
        return $query->where('customer_id', $pemesananId);
    }

    /**
     * Scope untuk transaksi berdasarkan nama venue
     */
    public function scopeByVenueName($query, $venueName)
    {
        if (is_array($venueName)) {
            return $query->whereIn('nama_venue', $venueName);
        }
        return $query->where('nama_venue', $venueName);
    }

    // ========== SCOPE KHUSUS ROLE ==========

    /**
     * Scope untuk User - transaksi milik user yang login
     */
    public function scopeForUser($query)
    {
        $user = Auth::user();
        if (!$user) return $query;
        
        return $query->where(function($q) use ($user) {
            // Cari berdasarkan nama pengguna
            $q->where('pengguna', $user->name);
            
            // Atau cari melalui relasi pemesanan
            $q->orWhereHas('pemesanan', function($q2) use ($user) {
                $q2->where('user_id', $user->id)
                   ->orWhere('customer_phone', $user->phone)
                   ->orWhere('nama_customer', $user->name);
            });
        });
    }

    /**
     * Scope untuk Venue - transaksi untuk venue milik user
     */
    public function scopeForVenueOwner($query)
    {
        $user = Auth::user();
        if (!$user) return $query;
        
        // Periksa apakah model User memiliki method venues()
        if (method_exists($user, 'venues')) {
            $venueNames = $user->venues()->pluck('name')->toArray();
            return $query->whereIn('nama_venue', $venueNames);
        }
        
        return $query;
    }

    /**
     * Scope untuk Admin - semua transaksi
     */
    public function scopeForAdmin($query)
    {
        return $query;
    }

    /**
     * Scope otomatis berdasarkan role user
     */
    public function scopeForCurrentUser($query)
    {
        $user = Auth::user();
        if (!$user) return $query;

        // Cek role user - asumsi Anda menggunakan package seperti spatie/laravel-permission
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('admin')) {
                return $query->forAdmin();
            } elseif ($user->hasRole('venue')) {
                return $query->forVenueOwner();
            }
        }
        
        // Default untuk user biasa
        return $query->forUser();
    }

    // ========== METHOD BISNIS LOGIC ==========

    /**
     * Method untuk mengecek apakah transaksi bisa dibatalkan
     */
    public function bisaDibatalkan()
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    /**
     * Method untuk mengecek apakah transaksi bisa direfund
     */
    public function bisaDirefund()
    {
        return in_array($this->status, ['paid', 'completed']) && 
               $this->transaction_date && 
               $this->transaction_date->addDays(7)->isFuture();
    }

    /**
     * Method untuk mengecek apakah transaksi sudah lunas
     */
    public function sudahLunas()
    {
        return in_array($this->status, ['paid', 'completed']);
    }

    /**
     * Method untuk mengecek apakah transaksi gagal
     */
    public function isFailed()
    {
        return in_array($this->status, ['failed', 'cancelled']);
    }

    /**
     * Method untuk mengecek apakah transaksi sedang diproses
     */
    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    /**
     * Method untuk update status transaksi
     */
    public function updateStatus($newStatus, $notes = null)
    {
        $allowedStatuses = ['pending', 'paid', 'failed', 'cancelled', 'refunded', 'completed', 'processing'];
        
        if (!in_array($newStatus, $allowedStatuses)) {
            return false;
        }

        $oldStatus = $this->status;
        $this->status = $newStatus;
        
        // Catat perubahan status
        if ($notes) {
            $this->notes = $this->notes 
                ? $this->notes . "\nStatus berubah dari {$oldStatus} ke {$newStatus}: " . $notes
                : "Status berubah dari {$oldStatus} ke {$newStatus}: " . $notes;
        }

        return $this->save();
    }

    /**
     * Method untuk mark as paid
     */
    public function markAsPaid($paymentMethod = null)
    {
        $this->status = 'paid';
        if ($paymentMethod) {
            $this->metode_pembayaran = $paymentMethod;
        }
        $this->transaction_date = now();
        
        return $this->save();
    }

    /**
     * Method untuk mark as cancelled
     */
    public function markAsCancelled($reason = 'Dibatalkan oleh pengguna')
    {
        $this->status = 'cancelled';
        $this->notes = $this->notes 
            ? $this->notes . "\nDibatalkan: " . $reason . " pada " . now()->format('Y-m-d H:i:s')
            : "Dibatalkan: " . $reason . " pada " . now()->format('Y-m-d H:i:s');
        
        return $this->save();
    }

    /**
     * Method untuk generate nomor transaksi
     */
    public static function generateTransactionNumber()
    {
        $prefix = 'TRX';
        $date = date('Ymd');
        
        do {
            $random = strtoupper(substr(md5(uniqid()), 0, 8));
            $number = "{$prefix}-{$date}-{$random}";
        } while (self::where('transaction_number', $number)->exists());

        return $number;
    }

    // ========== METHOD STATISTIK ==========

    /**
     * Method untuk menghitung total pendapatan
     */
    public static function getTotalRevenue($venueNames = null, $startDate = null, $endDate = null)
    {
        $query = self::whereIn('status', ['paid', 'completed']);

        if ($venueNames) {
            if (is_array($venueNames)) {
                $query->whereIn('nama_venue', $venueNames);
            } else {
                $query->where('nama_venue', $venueNames);
            }
        }

        if ($startDate && $endDate) {
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        }

        return $query->sum('amount');
    }

    /**
     * Method untuk statistik transaksi
     */
    public static function getTransactionStats($venueNames = null, $period = 'month')
    {
        $query = self::selectRaw('status, COUNT(*) as count, SUM(amount) as total');

        if ($venueNames) {
            if (is_array($venueNames)) {
                $query->whereIn('nama_venue', $venueNames);
            } else {
                $query->where('nama_venue', $venueNames);
            }
        }

        // Filter periode
        if ($period === 'today') {
            $query->whereDate('transaction_date', today());
        } elseif ($period === 'week') {
            $query->whereBetween('transaction_date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereMonth('transaction_date', now()->month)
                  ->whereYear('transaction_date', now()->year);
        }

        $results = $query->groupBy('status')->get();
        
        $stats = [];
        foreach ($results as $result) {
            $stats[$result->status] = [
                'count' => (int) $result->count,
                'total' => (float) $result->total
            ];
        }
        
        return $stats;
    }

    /**
     * Method untuk mendapatkan transaksi terbaru
     */
    public static function getRecentTransactions($limit = 10, $venueNames = null)
    {
        $query = self::with(['pemesanan' => function($q) {
            $q->select('id', 'nama_customer', 'booking_code', 'tanggal_booking', 'waktu_booking');
        }])
        ->orderBy('transaction_date', 'desc');

        if ($venueNames) {
            if (is_array($venueNames)) {
                $query->whereIn('nama_venue', $venueNames);
            } else {
                $query->where('nama_venue', $venueNames);
            }
        }

        return $query->limit($limit)->get();
    }

    // ========== METHOD KHUSUS USER ==========

    /**
     * Dapatkan history transaksi user
     */
    public static function getUserTransactionHistory($userId = null, $limit = 10)
    {
        $user = $userId ? User::find($userId) : Auth::user();
        if (!$user) return collect();

        return self::with(['pemesanan' => function($q) {
                $q->select('id', 'nama_customer', 'booking_code', 'tanggal_booking', 'waktu_booking');
            }])
            ->where(function($q) use ($user) {
                $q->where('pengguna', $user->name)
                  ->orWhereHas('pemesanan', function($q2) use ($user) {
                      $q2->where('user_id', $user->id)
                         ->orWhere('customer_phone', $user->phone)
                         ->orWhere('nama_customer', $user->name);
                  });
            })
            ->orderBy('transaction_date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Hitung total transaksi user
     */
    public static function countUserTransactions($userId = null)
    {
        $user = $userId ? User::find($userId) : Auth::user();
        if (!$user) return 0;

        return self::where(function($q) use ($user) {
            $q->where('pengguna', $user->name)
              ->orWhereHas('pemesanan', function($q2) use ($user) {
                  $q2->where('user_id', $user->id)
                     ->orWhere('customer_phone', $user->phone)
                     ->orWhere('nama_customer', $user->name);
              });
        })->count();
    }

    /**
     * Hitung total pengeluaran user
     */
    public static function getTotalPengeluaranUser($userId = null)
    {
        $user = $userId ? User::find($userId) : Auth::user();
        if (!$user) return 0;

        return self::where(function($q) use ($user) {
            $q->where('pengguna', $user->name)
              ->orWhereHas('pemesanan', function($q2) use ($user) {
                  $q2->where('user_id', $user->id)
                     ->orWhere('customer_phone', $user->phone)
                     ->orWhere('nama_customer', $user->name);
              });
        })
        ->whereIn('status', ['paid', 'completed'])
        ->sum('amount');
    }

    // ========== METHOD KHUSUS VENUE ==========

    /**
     * Hitung total transaksi hari ini untuk venue
     */
    public static function countTransactionsHariIni($venueName)
    {
        return self::where('nama_venue', $venueName)
                  ->hariIni()
                  ->count();
    }

    /**
     * Hitung total pendapatan hari ini untuk venue
     */
    public static function totalPendapatanHariIni($venueName)
    {
        return self::where('nama_venue', $venueName)
                  ->hariIni()
                  ->sukses()
                  ->sum('amount');
    }

    /**
     * Ambil transaksi terbaru untuk venue
     */
    public static function getTransactionsTerbaru($venueName, $limit = 5)
    {
        return self::with(['pemesanan' => function($q) {
                $q->select('id', 'nama_customer', 'booking_code');
            }])
            ->where('nama_venue', $venueName)
            ->orderBy('transaction_date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Statistik transaksi untuk venue
     */
    public static function getStatistikTransaksi($venueName, $days = 30)
    {
        $startDate = now()->subDays($days)->startOfDay();
        $endDate = now()->endOfDay();

        return self::where('nama_venue', $venueName)
                  ->selectRaw('DATE(transaction_date) as date, COUNT(*) as count, SUM(amount) as revenue')
                  ->whereBetween('transaction_date', [$startDate, $endDate])
                  ->sukses()
                  ->groupBy('date')
                  ->orderBy('date')
                  ->get();
    }

    /**
     * Statistik cepat untuk dashboard venue
     */
    public static function getStatistikCepat($venueName)
    {
        return [
            'hari_ini' => self::where('nama_venue', $venueName)->hariIni()->count(),
            'minggu_ini' => self::where('nama_venue', $venueName)->mingguIni()->count(),
            'bulan_ini' => self::where('nama_venue', $venueName)->bulanIni()->count(),
            'pendapatan_hari_ini' => self::where('nama_venue', $venueName)->hariIni()->sukses()->sum('amount'),
            'pendapatan_bulan_ini' => self::where('nama_venue', $venueName)->bulanIni()->sukses()->sum('amount'),
            'pending' => self::where('nama_venue', $venueName)->pending()->count(),
            'sukses' => self::where('nama_venue', $venueName)->sukses()->count(),
        ];
    }

    // ========== METHOD KHUSUS ADMIN ==========

    /**
     * Statistik keseluruhan untuk admin
     */
    public static function getStatistikAdmin()
    {
        return [
            'total_transaksi' => self::count(),
            'transaksi_hari_ini' => self::hariIni()->count(),
            'transaksi_minggu_ini' => self::mingguIni()->count(),
            'total_pendapatan' => self::sukses()->sum('amount'),
            'pendapatan_bulan_ini' => self::bulanIni()->sukses()->sum('amount'),
            'transaksi_pending' => self::pending()->count(),
            'transaksi_gagal' => self::gagal()->count(),
        ];
    }

    /**
     * Top venues berdasarkan transaksi untuk admin
     */
    public static function getTopVenuesByTransaction($limit = 5)
    {
        return self::selectRaw('nama_venue, COUNT(*) as total_transaksi, SUM(amount) as total_pendapatan')
                  ->sukses()
                  ->groupBy('nama_venue')
                  ->orderBy('total_pendapatan', 'desc')
                  ->limit($limit)
                  ->get();
    }

    /**
     * Trend transaksi bulanan untuk admin
     */
    public static function getTrendTransaksiBulanan()
    {
        return self::selectRaw('YEAR(transaction_date) as year, MONTH(transaction_date) as month, COUNT(*) as count, SUM(amount) as revenue')
                  ->sukses()
                  ->groupBy('year', 'month')
                  ->orderBy('year', 'desc')
                  ->orderBy('month', 'desc')
                  ->limit(12)
                  ->get();
    }

    /**
     * Dapatkan semua transaksi dengan pagination untuk admin
     */
    public static function getAllTransactionsForAdmin($perPage = 15)
    {
        return self::with(['pemesanan' => function($q) {
                $q->select('id', 'nama_customer', 'booking_code');
            }])
            ->orderBy('transaction_date', 'desc')
            ->paginate($perPage);
    }

    // ========== METHOD UTILITAS ==========

    /**
     * Cek apakah transaksi milik user tertentu
     */
    public function isOwnedByUser($user = null)
    {
        $user = $user ?: Auth::user();
        if (!$user) return false;

        // Cek langsung melalui kolom pengguna
        if ($this->pengguna === $user->name) {
            return true;
        }

        // Cek melalui relasi pemesanan
        if ($this->relationLoaded('pemesanan') && $this->pemesanan) {
            return $this->pemesanan->user_id === $user->id ||
                   $this->pemesanan->customer_phone === $user->phone ||
                   $this->pemesanan->nama_customer === $user->name;
        }

        return false;
    }

    /**
     * Cek apakah transaksi untuk venue milik user tertentu
     */
    public function isOwnedByVenueOwner($user = null)
    {
        $user = $user ?: Auth::user();
        if (!$user) return false;

        // Cek apakah user memiliki method venues()
        if (method_exists($user, 'venues')) {
            $venueNames = $user->venues()->pluck('name')->toArray();
            return in_array($this->nama_venue, $venueNames);
        }

        return false;
    }

    /**
     * Dapatkan permissions berdasarkan role
     */
    public function getPermissions($user = null)
    {
        $user = $user ?: Auth::user();
        if (!$user) return [];

        $permissions = [
            'can_view' => false,
            'can_edit' => false,
            'can_cancel' => false,
            'can_refund' => false,
            'can_confirm' => false,
        ];

        if ($user->hasRole('admin')) {
            $permissions = [
                'can_view' => true,
                'can_edit' => true,
                'can_cancel' => $this->bisaDibatalkan(),
                'can_refund' => $this->bisaDirefund(),
                'can_confirm' => in_array($this->status, ['pending', 'processing']),
            ];
        } elseif ($user->hasRole('venue') && $this->isOwnedByVenueOwner($user)) {
            $permissions = [
                'can_view' => true,
                'can_edit' => true,
                'can_cancel' => $this->bisaDibatalkan(),
                'can_refund' => $this->bisaDirefund(),
                'can_confirm' => in_array($this->status, ['pending', 'processing']),
            ];
        } elseif ($this->isOwnedByUser($user)) {
            $permissions = [
                'can_view' => true,
                'can_edit' => false,
                'can_cancel' => $this->bisaDibatalkan(),
                'can_refund' => $this->bisaDirefund(),
                'can_confirm' => false,
            ];
        }

        return $permissions;
    }

    /**
     * Load relasi untuk view transaksi
     */
    public function loadForTransaksiView()
    {
        return $this->load(['pemesanan' => function($q) {
            $q->select('id', 'nama_customer', 'booking_code', 'tanggal_booking', 'waktu_booking', 'user_id', 'customer_phone');
        }, 'venue' => function($q) {
            $q->select('name', 'location', 'kategori');
        }]);
    }

    /**
     * Method untuk mendapatkan data transaksi dalam format array
     */
    public function toTransaksiArray()
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'customer_id' => $this->customer_id,
            'pengguna' => $this->pengguna,
            'nama_venue' => $this->nama_venue,
            'metode_pembayaran' => $this->metode_pembayaran,
            'metode_pembayaran_formatted' => $this->metode_pembayaran_formatted,
            'amount' => $this->amount,
            'amount_formatted' => $this->amount_formatted,
            'transaction_date' => $this->transaction_date,
            'tanggal_transaksi_formatted' => $this->tanggal_transaksi_formatted,
            'tanggal_singkat' => $this->tanggal_singkat,
            'status' => $this->status,
            'status_formatted' => $this->status_formatted,
            'label_status' => $this->label_status,
            'css_status' => $this->css_status,
            'color_status' => $this->color_status,
            'icon_status' => $this->icon_status,
            'waktu_relatif' => $this->waktu_relatif,
            'pemesanan' => $this->pemesanan_data,
            'venue' => $this->venue_data,
            'user' => $this->user_data,
            'bisa_dibatalkan' => $this->bisaDibatalkan(),
            'bisa_direfund' => $this->bisaDirefund(),
            'sudah_lunas' => $this->sudahLunas(),
            'is_failed' => $this->isFailed(),
            'is_processing' => $this->isProcessing(),
            'permissions' => $this->getPermissions(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    /**
     * Method untuk mendapatkan data transaksi dalam format sederhana
     */
    public function toSimpleArray()
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'nama_venue' => $this->nama_venue,
            'pengguna' => $this->pengguna,
            'amount_formatted' => $this->amount_formatted,
            'tanggal_singkat' => $this->tanggal_singkat,
            'status' => $this->label_status,
            'status_color' => $this->color_status,
            'status_icon' => $this->icon_status
        ];
    }
}