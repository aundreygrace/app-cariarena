<?php

use Illuminate\Support\Facades\Route;

// ==============================
// IMPORT SPATIE MIDDLEWARE
// ==============================
use Spatie\Permission\Middleware\RoleMiddleware;

// ==============================
// CONTROLLERS ADMIN
// ==============================
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VenueController as AdminVenueController;
use App\Http\Controllers\Admin\PemesananController as AdminPemesananController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\PenggunaController;

// ==============================
// CONTROLLERS VENUE
// ==============================
use App\Http\Controllers\Venue\DashboardController as VenueDashboardController;
use App\Http\Controllers\Venue\VenueController;
use App\Http\Controllers\Venue\BookingController;
use App\Http\Controllers\Venue\JadwalController;
use App\Http\Controllers\Venue\UlasanController;
use App\Http\Controllers\Venue\LaporanController;
use App\Http\Controllers\Venue\PengaturanController as VenuePengaturanController;

// ==============================
// CONTROLLERS USER
// ==============================
use App\Http\Controllers\User\BerandaController;
use App\Http\Controllers\User\PesanController;
use App\Http\Controllers\User\UserAkunController;
use App\Http\Controllers\User\RiwayatController;
use App\Http\Controllers\User\NotifikasiController;

// ==============================
// CONTROLLER AUTH GABUNGAN
// ==============================
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// ==============================
// ROOT REDIRECT
// ==============================
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    if (!$user->hasRole('owner') && !$user->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }

    if ($user->hasRole('admin')) return redirect()->route('admin.dashboard');
    if ($user->hasRole('owner')) return redirect()->route('venue.dashboard');

    return redirect()->route('beranda');
});

// ==============================
// FALLBACK HOME
// ==============================
Route::get('/home', function () {
    if (auth()->check()) {

        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('owner')) {
            return redirect()->route('venue.dashboard');
        } else {
            return redirect()->route('beranda');
        }
    }
    return redirect()->route('login');
})->name('home');

// ===========================
// AUTH GUEST
// ===========================
Route::middleware('guest')->group(function () {
    // LOGIN
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    // REGISTER
    Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==============================
// ROUTES PASSWORD RESET
// ==============================
// Halaman lupa password (input email)
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

    // Kirim email reset password
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    // Halaman reset password
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    // Submit password baru
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])
        ->name('password.update');
    Route::post('/forgot-password/resend', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.resend');

// ===========================
// EMAIL VERIFICATION
// ===========================
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Email verifikasi telah dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ==============================
// ROUTES COMPATIBILITY
// ==============================
Route::get('/admin/login', function () {
    return redirect()->route('login');
});
Route::get('/venue/login', function () {
    return redirect()->route('login');
});
Route::get('/user/login', function () {
    return redirect()->route('login');
});


// ==============================
// ROUTES UNTUK USER
// ==============================
    // PERBAIKAN: Gunakan RoleMiddleware dengan format yang benar
    Route::middleware(['auth', 'verified', RoleMiddleware::class.':user'])->group(function () {
    // BERANDA
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
    Route::get('/user/beranda', [BerandaController::class, 'index'])->name('user.beranda');
    Route::get('/venues/category/{category}', [BerandaController::class, 'filterByCategory'])->name('venues.byCategory');
    Route::get('/venues/search', [BerandaController::class, 'search'])->name('venues.search');

    // Routes untuk pesan venue
    Route::prefix('pesan')->name('pesan.')->group(function () {
        Route::get('/', [PesanController::class, 'index'])->name('index');
        Route::get('/pesan-sekarang/{id}', [PesanController::class, 'pesanSekarang'])
            ->name('pesan-sekarang')
            ->where('id', '[0-9]+');
        Route::get('/booking/{id}', [PesanController::class, 'booking'])->name('booking');
        
        //Route untuk pembayaran dan riwayat
        Route::get('/bayar/{booking_code}', [PesanController::class, 'bayar'])->name('bayar');
        Route::get('/pembayaran/{id}', [PesanController::class, 'pembayaran'])->name('pembayaran');
        Route::post('/proses-bayar', [PesanController::class, 'prosesBayar'])->name('proses-bayar');
        
        // Route riwayat-booking
        Route::get('/riwayat-booking', [PesanController::class, 'riwayatBooking'])->name('riwayat-booking');

        Route::get('/ulasan/{id}', [PesanController::class, 'ulasan'])->name('ulasan');
        Route::post('/process-booking', [PesanController::class, 'storeBooking'])->name('process-booking');
        
        // API routes untuk booking system
        Route::get('/available-slots', [PesanController::class, 'getAvailableSlots'])->name('available-slots');
        Route::get('/existing-bookings', [PesanController::class, 'getExistingBookings'])->name('existing-bookings');
        Route::get('/month-bookings', [PesanController::class, 'getMonthBookings'])->name('month-bookings');
    });

    // Riwayat Booking Routes
    Route::prefix('riwayat')->name('riwayat.')->group(function () {
        // View riwayat page
        Route::get('/', [RiwayatController::class, 'index'])->name('index');
        
        // Cancel booking (AJAX)
        Route::post('/cancel/{id}', [RiwayatController::class, 'cancelBooking'])->name('cancel');
        
        // Submit review (AJAX)
        Route::post('/review/{id}', [RiwayatController::class, 'submitReview'])->name('review');
    });

    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');

    // AKUN
    Route::get('/akun', [UserAkunController::class, 'index'])->name('akun');
      Route::put('/profile/update', [UserAkunController::class, 'updateProfile'])->name('profile.update');

    // NOTIFIKASI
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/mark-all-read', [NotifikasiController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('/{id}', [NotifikasiController::class, 'delete'])->name('delete');
    });
});

// ==============================
// ROUTES UNTUK ADMIN
// ==============================
// PERBAIKAN: Gunakan RoleMiddleware dengan format yang benar
Route::prefix('admin')->name('admin.')->middleware(['auth', RoleMiddleware::class.':admin'])->group(function () {
    // Dashboard Routes
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/notifikasi', [AdminDashboardController::class, 'notifikasi'])->name('dashboard.notifikasi');
    Route::get('/catatan-aktivitas', [AdminDashboardController::class, 'catatanAktivitas'])->name('catatan-aktivitas.index');
    Route::get('/jadwal-lapangan', [AdminDashboardController::class, 'jadwalLapangan'])->name('jadwal-lapangan.index');

    // Manajemen Pengguna
    Route::prefix('pengguna')->name('pengguna.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/statistics', [UserController::class, 'getStatistics'])->name('statistics');
    });

    // Manajemen Venue
    Route::prefix('venue')->name('venue.')->group(function () {
        Route::get('/', [AdminVenueController::class, 'index'])->name('index');
        Route::get('/create', [AdminVenueController::class, 'create'])->name('create');
        Route::post('/', [AdminVenueController::class, 'store'])->name('store');
        Route::get('/{venue}', [AdminVenueController::class, 'show'])->name('show');
        Route::get('/{venue}/edit', [AdminVenueController::class, 'edit'])->name('edit');
        Route::put('/{venue}', [AdminVenueController::class, 'update'])->name('update');
        Route::delete('/{venue}', [AdminVenueController::class, 'destroy'])->name('destroy');
        Route::get('/tambah', [AdminVenueController::class, 'create'])->name('tambah');
        Route::post('/simpan', [AdminVenueController::class, 'store'])->name('simpan');
        Route::delete('/{venue}/hapus', [AdminVenueController::class, 'destroy'])->name('hapus');
    });

    // Manajemen Pemesanan
    Route::prefix('pemesanan')->name('pemesanan.')->group(function () {
        Route::get('/', [AdminPemesananController::class, 'index'])->name('index');
        Route::post('/', [AdminPemesananController::class, 'store'])->name('store');
        Route::get('/{pemesanan}', [AdminPemesananController::class, 'show'])->name('show');
        Route::get('/{pemesanan}/edit', [AdminPemesananController::class, 'edit'])->name('edit');
        Route::put('/{pemesanan}', [AdminPemesananController::class, 'update'])->name('update');
        Route::delete('/{pemesanan}', [AdminPemesananController::class, 'destroy'])->name('destroy');
        Route::post('/{pemesanan}/confirm', [AdminPemesananController::class, 'confirm'])->name('confirm');
        Route::post('/{pemesanan}/cancel', [AdminPemesananController::class, 'cancel'])->name('cancel');
        Route::post('/{pemesanan}/complete', [AdminPemesananController::class, 'complete'])->name('complete');
    });

    // Manajemen Transaksi
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/', [AdminTransaksiController::class, 'index'])->name('index');
        Route::get('/{transaksi}', [AdminTransaksiController::class, 'show'])->name('show');
        Route::post('/filter', [AdminTransaksiController::class, 'filter'])->name('filter');
        Route::put('/{transaksi}', [AdminTransaksiController::class, 'update'])->name('update');
        Route::delete('/{transaksi}', [AdminTransaksiController::class, 'destroy'])->name('destroy');
        Route::post('/{transaksi}/confirm', [AdminTransaksiController::class, 'confirmPayment'])->name('confirm');
        Route::post('/{transaksi}/reject', [AdminTransaksiController::class, 'rejectPayment'])->name('reject');
    });

    // Manajemen Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        // ROUTE UTAMA
        Route::get('/', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('index');
        
        // ROUTE KHUSUS - HARUS SEBELUM ROUTE PARAMETER
        Route::get('/create', [\App\Http\Controllers\Admin\LaporanController::class, 'create'])->name('create');
        Route::get('/analitik', [\App\Http\Controllers\Admin\LaporanController::class, 'analitik'])->name('analitik');
        Route::get('/chart-data', [\App\Http\Controllers\Admin\LaporanController::class, 'getChartData'])->name('chart-data');
                
        // ROUTE EXPORT VIA GET (UNTUK DOWNLOAD LANGSUNG)
        Route::get('/export-pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [\App\Http\Controllers\Admin\LaporanController::class, 'exportExcel'])->name('export-excel');

        // ROUTE EXPORT VIA POST (untuk compatibility)
        Route::post('/export-pdf-post', [\App\Http\Controllers\Admin\LaporanController::class, 'exportPdfPost'])->name('export-pdf.post');
        Route::post('/export-excel-post', [\App\Http\Controllers\Admin\LaporanController::class, 'exportExcelPost'])->name('export-excel.post');
            
        Route::post('/quick-generate', [\App\Http\Controllers\Admin\LaporanController::class, 'quickGenerate'])->name('quick-generate');
        Route::get('/{id}/detail', [\App\Http\Controllers\Admin\LaporanController::class, 'detail'])->name('detail');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\LaporanController::class, 'edit'])->name('edit');
        Route::post('/', [\App\Http\Controllers\Admin\LaporanController::class, 'store'])->name('store');
        Route::put('/{id}', [\App\Http\Controllers\Admin\LaporanController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\LaporanController::class, 'destroy'])->name('destroy');
    });

    // Settings Routes
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/', [PengaturanController::class, 'index'])->name('index');
        Route::post('/profile/update', [PengaturanController::class, 'updateProfile'])->name('profile.update');
        Route::post('/system/update', [PengaturanController::class, 'updateSystem'])->name('system.update');
        Route::post('/admin/add', [PengaturanController::class, 'addAdmin'])->name('admin.add');
        Route::post('/admin/update', [PengaturanController::class, 'updateAdmin'])->name('admin.update');
        Route::post('/admin/delete', [PengaturanController::class, 'deleteAdmin'])->name('admin.delete');
        Route::post('/notifications/update', [PengaturanController::class, 'updateNotifications'])->name('notifications.update');
        Route::post('/security/update', [PengaturanController::class, 'updateSecurity'])->name('security.update');
        Route::post('/backup/now', [PengaturanController::class, 'backupNow'])->name('backup.now');
        Route::post('/faq/update', [PengaturanController::class, 'updateFaq'])->name('faq.update');
        Route::get('/profile-photo/{filename}', [PengaturanController::class, 'getProfilePhoto'])->name('profile.photo'); 
    });
});

// ==============================
// ROUTES UNTUK VENUE/OWNER
// ==============================
// PERBAIKAN: Gunakan RoleMiddleware dengan format yang benar
Route::prefix('venue')->name('venue.')->middleware(['auth', RoleMiddleware::class.':owner'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [VenueDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/lihat-review', [VenueDashboardController::class, 'lihatReview'])->name('ulasan');
    
    // Route untuk notifikasi
    Route::get('/notifikasi', [VenueDashboardController::class, 'notifikasi'])
        ->name('notifikasi');
    Route::post('/notifikasi/{id}/baca', [VenueDashboardController::class, 'markAsRead'])
        ->name('notifikasi.baca');
    Route::delete('/notifikasi/{id}/hapus', [VenueDashboardController::class, 'destroyNotifikasi'])
        ->name('notifikasi.hapus');

    // VENUE CRUD
    Route::get('/venue-saya', [VenueController::class, 'index'])->name('venue-saya');
    Route::post('/tambah-venue', [VenueController::class, 'store'])->name('tambah');
    Route::get('/venue/{id}', [VenueController::class, 'show'])->name('detail');
    Route::get('/venue/{id}/edit', [VenueController::class, 'edit'])->name('edit');
    Route::put('/venue/{id}', [VenueController::class, 'update'])->name('update');
    Route::delete('/venue/{id}/hapus', [VenueController::class, 'destroy'])->name('hapus');
    Route::post('/venue/{id}/toggle-status', [VenueController::class, 'toggleStatus'])->name('toggle-status');

    // JADWAL ROUTES
    Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('/', [JadwalController::class, 'index'])->name('index');
        Route::get('/atur', [JadwalController::class, 'aturJadwal'])->name('atur');
        Route::post('/store', [JadwalController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [JadwalController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [JadwalController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [JadwalController::class, 'destroy'])->name('delete');
        Route::get('/monthly-status', [JadwalController::class, 'getMonthlyStatus'])->name('monthly-status');
        Route::get('/daily-schedule', [JadwalController::class, 'getDailySchedule'])->name('daily-schedule');
    });

    // BOOKING ROUTES
    Route::prefix('booking-masuk')->name('booking.masuk.')->group(function () {
        Route::get('/', [BookingController::class, 'masuk'])->name('index');
        Route::get('/data', [BookingController::class, 'getBookings'])->name('data');
        Route::get('/detail/{id}', [BookingController::class, 'detail'])->name('detail');
        Route::post('/store', [BookingController::class, 'store'])->name('store');
        Route::put('/update/{id}', [BookingController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BookingController::class, 'delete'])->name('destroy');
        Route::get('/venues', [BookingController::class, 'getVenues'])->name('venues');
    });
    
    // Route fallback untuk kompatibilitas
    Route::get('/booking', function () {
        return redirect()->route('venue.booking.masuk.index');
    })->name('booking');
    
    // ROUTES ULASAN - HANYA SATU VERSI
    Route::prefix('ulasan')->name('ulasan.')->group(function () {
        Route::get('/', [UlasanController::class, 'index'])->name('index');
        Route::post('/filter', [UlasanController::class, 'filter'])->name('filter');
        Route::get('/{id}/reply', [UlasanController::class, 'showReplyForm'])->name('reply');
        Route::post('/{id}/reply', [UlasanController::class, 'storeReply'])->name('store-reply');
        Route::delete('/{id}/reply', [UlasanController::class, 'deleteReply'])->name('delete-reply');
    });

    // ROUTES LAPORAN
        Route::get('/reports', [LaporanController::class, 'index'])
        ->name('reports');

        Route::prefix('reports')->name('reports.')->group(function ()
         {

            Route::get('/chart-data', [LaporanController::class, 'chartData'])
                ->name('chart-data');

            Route::get('/export-excel', [LaporanController::class, 'exportExcel'])
                ->name('export-excel');

            Route::get('/export-pdf', [LaporanController::class, 'exportPdf'])
                ->name('export-pdf');

            Route::get('/download/{filename}', [LaporanController::class, 'downloadFile'])
                ->name('download.file');

            Route::get('/{id}', [LaporanController::class, 'show'])
                ->name('show');
        });

        // Halaman pengaturan
        Route::get('/pengaturan', [VenuePengaturanController::class, 'index'])->name('pengaturan');
        
        // Update profil venue
        Route::post('/pengaturan/profile/update', [VenuePengaturanController::class, 'updateProfile'])->name('pengaturan.profile.update');

        // Di routes/web.php dalam group venue
        Route::get('/venue/pengaturan/debug', [VenuePengaturanController::class, 'debug'])
            ->name('venue.pengaturan.debug');

        // Di dalam group venue, tambahkan:
        Route::get('/venue/pengaturan/debug', [VenuePengaturanController::class, 'debug'])->name('venue.pengaturan.debug');
        Route::post('/venue/pengaturan/debug', [VenuePengaturanController::class, 'debug']);
        
        // Update jadwal venue
        Route::post('/pengaturan/schedule/update', [VenuePengaturanController::class, 'updateSchedule'])->name('pengaturan.schedule.update');
        
        // Update notifikasi venue
        Route::post('/pengaturan/notifications/update', [VenuePengaturanController::class, 'updateNotifications'])->name('pengaturan.notifications.update');
        
        // Update keamanan venue (password)
        Route::post('/pengaturan/security/update', [VenuePengaturanController::class, 'updateSecurity'])->name('pengaturan.security.update');
        
        // Logout dari sesi lain
        Route::post('/pengaturan/logout-other-sessions', [VenuePengaturanController::class, 'logoutOtherSessions'])->name('pengaturan.logout-other-sessions');
    });

// ==============================
// TEST ROUTE - untuk debugging
// ==============================
Route::get('/test-routes', function() {
    echo "<h1>Route List</h1>";
    echo "<ul>";
    foreach (Route::getRoutes() as $route) {
        echo "<li>" . $route->uri() . " → " . ($route->getName() ?: 'No name') . "</li>";
    }
    echo "</ul>";
});

// ==============================
// ROUTE CLEAR CACHE
// ==============================
Route::get('/clear-cache', function() {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return "Cache cleared successfully!";
})->name('clear.cache');

Route::get('/debug-jadwal/{venueId}/{date}', function($venueId, $date) {
    
    echo "<h1>DEBUG JADWAL</h1>";
    echo "<p>Venue ID: {$venueId}</p>";
    echo "<p>Date: {$date}</p>";
    echo "<hr>";
    
    // Test 1: Raw SQL
    echo "<h2>Test 1: Raw SQL Query</h2>";
    $rawResults = DB::select("
        SELECT id, venue_id, tanggal, waktu_mulai, status, locked_until
        FROM jadwal
        WHERE venue_id = ? AND tanggal = ?
    ", [$venueId, $date]);
    
    echo "<p>Raw SQL Results: " . count($rawResults) . " rows</p>";
    echo "<pre>" . print_r($rawResults, true) . "</pre>";
    echo "<hr>";
    
    // Test 2: Query Builder
    echo "<h2>Test 2: Query Builder</h2>";
    $qbResults = DB::table('jadwal')
        ->where('venue_id', $venueId)
        ->where('tanggal', $date)
        ->get();
    
    echo "<p>Query Builder Results: " . $qbResults->count() . " rows</p>";
    echo "<pre>" . print_r($qbResults->toArray(), true) . "</pre>";
    echo "<hr>";
    
    // Test 3: Eloquent
    echo "<h2>Test 3: Eloquent Model</h2>";
    $eloquentResults = \App\Models\Jadwal::where('venue_id', $venueId)
        ->where('tanggal', $date)
        ->get();
    
    echo "<p>Eloquent Results: " . $eloquentResults->count() . " rows</p>";
    echo "<pre>" . print_r($eloquentResults->toArray(), true) . "</pre>";
    echo "<hr>";
    
    // Test 4: whereDate
    echo "<h2>Test 4: whereDate</h2>";
    $whereDateResults = \App\Models\Jadwal::where('venue_id', $venueId)
        ->whereDate('tanggal', $date)
        ->get();
    
    echo "<p>whereDate Results: " . $whereDateResults->count() . " rows</p>";
    echo "<pre>" . print_r($whereDateResults->toArray(), true) . "</pre>";
    echo "<hr>";
    
    // Test 5: Check tanggal format
    echo "<h2>Test 5: Tanggal Format Check</h2>";
    $allJadwal = DB::table('jadwal')
        ->where('venue_id', $venueId)
        ->select('id', 'tanggal', 'waktu_mulai', 'status')
        ->get();
    
    echo "<p>All jadwal for venue {$venueId}: " . $allJadwal->count() . " rows</p>";
    foreach ($allJadwal as $j) {
        $match = $j->tanggal === $date ? '✅ MATCH' : '❌ NO MATCH';
        echo "<p>ID {$j->id}: tanggal='{$j->tanggal}' vs '{$date}' → {$match}</p>";
    }
    echo "<hr>";
    
    // Test 6: Simulated Controller Query
    echo "<h2>Test 6: Simulated Controller Query</h2>";
    $controllerQuery = DB::table('jadwal')
        ->where('venue_id', $venueId)
        ->where('tanggal', $date)
        ->where('status', 'Available')
        ->where(function($query) {
            $query->whereNull('locked_until')
                  ->orWhere('locked_until', '<', now());
        })
        ->orderBy('waktu_mulai')
        ->get();
    
    echo "<p>Controller Simulated Results: " . $controllerQuery->count() . " rows</p>";
    echo "<pre>" . print_r($controllerQuery->toArray(), true) . "</pre>";
    echo "<hr>";
    
    // Test 7: Check data types
    echo "<h2>Test 7: Data Type Check</h2>";
    $firstJadwal = DB::table('jadwal')
        ->where('venue_id', $venueId)
        ->first();
    
    if ($firstJadwal) {
        echo "<p>First jadwal:</p>";
        echo "<pre>";
        echo "ID: " . var_export($firstJadwal->id, true) . "\n";
        echo "Venue ID: " . var_export($firstJadwal->venue_id, true) . " (type: " . gettype($firstJadwal->venue_id) . ")\n";
        echo "Tanggal: " . var_export($firstJadwal->tanggal, true) . " (type: " . gettype($firstJadwal->tanggal) . ")\n";
        echo "Status: " . var_export($firstJadwal->status, true) . "\n";
        echo "Locked Until: " . var_export($firstJadwal->locked_until, true) . "\n";
        echo "</pre>";
    }
    
    echo "<hr>";
    echo "<h2>Summary</h2>";
    echo "<p>Request Date: {$date} (type: " . gettype($date) . ")</p>";
    echo "<p>Database Date: " . ($firstJadwal ? $firstJadwal->tanggal : 'N/A') . "</p>";
    echo "<p>Match: " . ($firstJadwal && $firstJadwal->tanggal === $date ? 'YES' : 'NO') . "</p>";
    
})->middleware('auth');