@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Statistik Utama -->
    <div class="cards mb-4">
        <div class="card">
            <small>Total Pengguna</small>
            <h3>{{ number_format($totalPengguna ?? 0) }}</h3>
            <small>Pengguna terdaftar</small>
        </div>

        <div class="card">
            <small>Total Venue</small>
            <h3>{{ number_format($totalVenue ?? 0) }}</h3>
            <small>Lapangan tersedia</small>
        </div>

        <div class="card">
            <small>Total Pemesanan</small>
            <h3>{{ number_format($totalPemesanan ?? 0) }}</h3>
            <small>
                <span class="{{ $peningkatanPemesanan >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="fas fa-arrow-{{ $peningkatanPemesanan >= 0 ? 'up' : 'down' }}"></i>
                    {{ $peningkatanPemesanan >= 0 ? '+' : '' }}{{ $peningkatanPemesanan ?? 0 }}% dari kemarin
                </span>
            </small>
        </div>

        <div class="card">
            <small>Tingkat Okupansi</small>
            <h3>{{ $tingkatOkupansi ?? 0 }}%</h3>
            <small class="text-success">
                <i class="fas fa-arrow-up"></i>+5% bulan ini
            </small>
        </div>
    </div>

    <!-- 4 Sub Menu Utama dengan Tinggi Sama -->
    <div class="row equal-height-row">
        <!-- Kolom Kiri -->
        <div class="col-xl-8 col-lg-12">
            <div class="row equal-height-row">
                <!-- Pemesanan Terbaru -->
                <div class="col-12 main-content-section">
                    <div class="dashboard-section">
                        <div class="section-header d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-calendar-alt me-2"></i>Booking Terbaru</h5>
                            <!-- SOLUSI: Gunakan URL langsung untuk pemesanan -->
                            <a href="/admin/pemesanan" class="lihat-semua-btn">
                                Lihat Semua <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="section-body">
                            @forelse($pemesananTerbaru as $pemesanan)
                            <div class="booking-item">
                                <div class="booking-info">
                                    <strong>
                                        @if(isset($pemesanan->nama_customer))
                                            {{ $pemesanan->nama_customer }}
                                        @else
                                            Customer
                                        @endif
                                    </strong>
                                    <p>
                                        @if(isset($pemesanan->venue->name))
                                            {{ $pemesanan->venue->name }}
                                        @else
                                            Venue #{{ $pemesanan->venue_id ?? 'N/A' }}
                                        @endif
                                        — 
                                        @if(isset($pemesanan->tanggal_booking))
                                            {{ \Carbon\Carbon::parse($pemesanan->tanggal_booking)->format('d M Y') }}
                                            @if(isset($pemesanan->waktu_booking))
                                                {{ \Carbon\Carbon::parse($pemesanan->waktu_booking)->format('H:i') }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                        @if(isset($pemesanan->end_time))
                                            – {{ \Carbon\Carbon::parse($pemesanan->end_time)->format('H:i') }}
                                        @elseif(isset($pemesanan->durasi))
                                            – {{ \Carbon\Carbon::parse($pemesanan->waktu_booking)->addHours($pemesanan->durasi)->format('H:i') }}
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    @if(isset($pemesanan->total_biaya) && $pemesanan->total_biaya > 0)
                                        <strong>Rp {{ number_format($pemesanan->total_biaya) }}</strong>
                                    @else
                                        <strong>-</strong>
                                    @endif
                                    <div class="status {{ in_array($pemesanan->status, ['confirmed', 'terkonfirmasi', 'selesai', 'Terkonfirmasi', 'completed']) ? 'confirmed' : 
                                                         (in_array($pemesanan->status, ['pending', 'menunggu', 'Menunggu']) ? 'pending' : 
                                                         ($pemesanan->status == 'dibatalkan' ? 'cancelled' : 'pending')) }}">
                                        @if($pemesanan->status == 'confirmed' || $pemesanan->status == 'terkonfirmasi' || $pemesanan->status == 'Terkonfirmasi' || $pemesanan->status == 'completed')
                                            Terkonfirmasi
                                        @elseif($pemesanan->status == 'pending' || $pemesanan->status == 'menunggu' || $pemesanan->status == 'Menunggu')
                                            Menunggu
                                        @elseif($pemesanan->status == 'selesai')
                                            Selesai
                                        @elseif($pemesanan->status == 'dibatalkan')
                                            Dibatalkan
                                        @else
                                            {{ $pemesanan->status ?? 'Unknown' }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                <p>Tidak ada pemesanan terbaru</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Venue Populer -->
                <div class="col-12 main-content-section">
                    <div class="dashboard-section">
                        <div class="section-header d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-trophy me-2"></i>Venue Populer</h5>
                            <!-- SOLUSI: Gunakan URL langsung untuk venue -->
                            <a href="/admin/venue" class="lihat-semua-btn">
                                Lihat Semua <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="section-body">
                            @forelse($venuePopuler as $venue)
                            <div class="venue-item">
                                <div>
                                    <strong>{{ $venue->name ?? 'Nama tidak tersedia' }}</strong>
                                    <p class="mb-0 text-muted small">
                                        {{ $venue->category ?? 'Umum' }} 
                                        @if(isset($venue->facilities) && is_array($venue->facilities) && count($venue->facilities) > 0)
                                            • {{ implode(', ', array_slice($venue->facilities, 0, 2)) }}{{ count($venue->facilities) > 2 ? '...' : '' }}
                                        @elseif(isset($venue->facilities) && is_string($venue->facilities))
                                            • {{ Str::limit($venue->facilities, 30) }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-end">
                                    <strong>{{ $venue->total_pemesanans ?? 0 }}x dipesan</strong>
                                    <div class="small">
                                        <i class="fas fa-star text-warning"></i> 
                                        {{ $venue->rating ?? '0.0' }}
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-store-slash fa-2x mb-2"></i>
                                <p>Tidak ada data venue</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-xl-4 col-lg-12">
            <div class="row equal-height-row">
                <!-- Notifikasi Terbaru -->
                <div class="col-12 main-content-section">
                    <div class="dashboard-section">
                        <div class="section-header d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-bell me-2"></i>Notifikasi</h5>
                            <!-- PERBAIKI LINK: Gunakan route yang benar -->
                            <a href="{{ route('admin.dashboard.notifikasi') }}" class="lihat-semua-btn">
                                Lihat Semua <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="section-body notification-small">
                            @forelse($notifications as $notification)
                            <div class="notification-item d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    @switch($notification->type ?? 'sistem')
                                        @case('pembayaran')
                                            <i class="fas fa-money-bill-wave text-success me-2 mt-1"></i>
                                            @break
                                        @case('ulasan')
                                            <i class="fas fa-star text-warning me-2 mt-1"></i>
                                            @break
                                        @case('booking')
                                            <i class="fas fa-calendar-plus text-primary me-2 mt-1"></i>
                                            @break
                                        @case('venue')
                                            <i class="fas fa-store text-info me-2 mt-1"></i>
                                            @break
                                        @case('user')
                                            <i class="fas fa-user-plus text-secondary me-2 mt-1"></i>
                                            @break
                                        @default
                                            <i class="fas fa-cog text-muted me-2 mt-1"></i>
                                    @endswitch
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-1">{{ $notification->title ?? 'Notifikasi Sistem' }}</h6>
                                    <p class="mb-0 text-muted small">{{ $notification->message ?? 'Tidak ada pesan' }}</p>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-bell-slash fa-lg mb-2"></i>
                                <p class="small mb-0">Tidak ada notifikasi</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Statistik Minggu Ini -->
                <div class="col-12 main-content-section">
                    <div class="dashboard-section">
                        <div class="section-header d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-chart-bar me-2"></i>Statistik Minggu Ini</h5>
                            <!-- SOLUSI: Gunakan URL langsung untuk laporan -->
                            <a href="/admin/laporan" class="lihat-semua-btn">
                                Lihat Semua <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="section-body">
                            <div class="stat-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">Total Booking</span>
                                </div>
                                <div class="text-end">
                                    <span class="stat-value text-primary">{{ $statistikMingguIni['pemesanan'] ?? 0 }}</span>
                                    <small class="{{ ($statistikMingguIni['peningkatan_pemesanan'] ?? 0) >= 0 ? 'change-positive' : 'change-negative' }} ms-1">
                                        <i class="fas fa-arrow-{{ ($statistikMingguIni['peningkatan_pemesanan'] ?? 0) >= 0 ? 'up' : 'down' }} me-1"></i>
                                        {{ ($statistikMingguIni['peningkatan_pemesanan'] ?? 0) >= 0 ? '+' : '' }}{{ $statistikMingguIni['peningkatan_pemesanan'] ?? 0 }}%
                                    </small>
                                </div>
                            </div>
                            <div class="stat-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">Pendapatan</span>
                                </div>
                                <div class="text-end">
                                    <span class="stat-value text-success">Rp {{ number_format($statistikMingguIni['pendapatan'] ?? 0) }}</span>
                                    <small class="{{ ($statistikMingguIni['peningkatan_pendapatan'] ?? 0) >= 0 ? 'change-positive' : 'change-negative' }} ms-1">
                                        <i class="fas fa-arrow-{{ ($statistikMingguIni['peningkatan_pendapatan'] ?? 0) >= 0 ? 'up' : 'down' }} me-1"></i>
                                        {{ ($statistikMingguIni['peningkatan_pendapatan'] ?? 0) >= 0 ? '+' : '' }}{{ $statistikMingguIni['peningkatan_pendapatan'] ?? 0 }}%
                                    </small>
                                </div>
                            </div>
                            <div class="stat-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">Okupansi</span>
                                </div>
                                <div class="text-end">
                                    <span class="stat-value text-warning">{{ $statistikMingguIni['okupansi'] ?? 0 }}%</span>
                                    <small class="change-positive ms-1">
                                        <i class="fas fa-arrow-up me-1"></i>+{{ $statistikMingguIni['peningkatan_okupansi'] ?? 0 }}%
                                    </small>
                                </div>
                            </div>
                            <div class="stat-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">Pengguna Baru</span>
                                </div>
                                <div class="text-end">
                                    <span class="stat-value text-info">{{ $statistikMingguIni['pengguna_baru'] ?? 0 }}</span>
                                    <small class="{{ ($statistikMingguIni['peningkatan_pengguna'] ?? 0) >= 0 ? 'change-positive' : 'change-negative' }} ms-1">
                                        <i class="fas fa-arrow-{{ ($statistikMingguIni['peningkatan_pengguna'] ?? 0) >= 0 ? 'up' : 'down' }} me-1"></i>
                                        {{ ($statistikMingguIni['peningkatan_pengguna'] ?? 0) >= 0 ? '+' : '' }}{{ $statistikMingguIni['peningkatan_pengguna'] ?? 0 }}%
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Styles dari Manajemen Pengguna untuk cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
            max-width: 100%;
        }

        .card {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            min-height: 120px;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Border left warna-warni untuk setiap card */
        .card:nth-child(1) {
            border-left: 4px solid #4299E1;
        }
        
        .card:nth-child(2) {
            border-left: 4px solid #48BB78;
        }
        
        .card:nth-child(3) {
            border-left: 4px solid #ED8936;
        }
        
        .card:nth-child(4) {
            border-left: 4px solid #9F7AEA;
        }

        /* Warna teks untuk setiap card */
        .card:nth-child(1) h3 {
            color: #4299E1;
        }
        
        .card:nth-child(2) h3 {
            color: #48BB78;
        }
        
        .card:nth-child(3) h3 {
            color: #ED8936;
        }
        
        .card:nth-child(4) h3 {
            color: #9F7AEA;
        }

        .card small {
            color: var(--text-light);
            font-size: 13px;
            display: block;
            font-weight: 500;
        }

        .card h3 {
            margin: 10px 0;
            font-size: 26px;
            font-weight: 700;
            line-height: 1.2;
        }

        .card small:last-of-type {
            font-size: 12px;
            color: var(--text-light);
            opacity: 0.8;
            margin-top: 5px;
        }

        /* Dashboard section styles */
        .dashboard-section {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            transition: all 0.3s;
            border: none;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .dashboard-section:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .section-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 0 0 15px 0;
            margin-bottom: 15px;
            flex-shrink: 0;
        }

        .section-header h5 {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            color: var(--primary-color);
        }

        .section-body {
            padding: 0;
            flex: 1;
        }

        .booking-item, .venue-item, .notification-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.2s;
        }

        .booking-item:hover, .venue-item:hover, .notification-item:hover {
            background-color: #F9FAFB;
        }

        .booking-item:last-child, .venue-item:last-child, .notification-item:last-child {
            border-bottom: none;
        }

        .booking-info strong {
            font-size: 14px;
            display: block;
            margin-bottom: 4px;
        }

        .booking-info p {
            font-size: 13px;
            color: var(--text-light);
            margin: 0;
        }

        .status {
            font-size: 12px;
            border-radius: 6px;
            padding: 3px 8px;
            font-weight: 500;
            display: inline-block;
            margin-top: 5px;
        }

        .confirmed {
            background: #C6F6D5;
            color: var(--success);
        }

        .pending {
            background: #FEFCBF;
            color: var(--warning);
        }

        .cancelled {
            background: #FED7D7;
            color: var(--danger);
        }

        .notification-small .notification-item {
            padding: 8px 0;
        }

        .notification-small .notification-item h6 {
            font-size: 0.9rem;
            margin-bottom: 2px;
        }

        .notification-small .notification-item p {
            font-size: 0.8rem;
            margin-bottom: 2px;
        }

        .notification-small .notification-item small {
            font-size: 0.7rem;
        }

        .stat-item {
            padding: 12px 0;
            border-bottom: 1px solid #F3F4F6;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-value {
            font-size: 1.125rem;
            font-weight: 700;
        }

        .change-positive {
            color: var(--success);
        }

        .change-negative {
            color: var(--danger);
        }

        .equal-height-row {
            display: flex;
            flex-wrap: wrap;
        }

        .equal-height-row > [class*="col-"] {
            display: flex;
            flex-direction: column;
        }

        .main-content-section {
            margin-bottom: 24px;
        }

        .lihat-semua-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .lihat-semua-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 179, 237, 0.3);
            color: white;
            text-decoration: none;
        }

        .lihat-semua-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .lihat-semua-btn i {
            margin-left: 5px;
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .lihat-semua-btn:hover i {
            transform: translateX(3px);
        }

        /* Responsive improvements for dashboard */
        @media (max-width: 1200px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .main-content-section {
                margin-bottom: 20px;
            }
            
            .dashboard-section {
                padding: 18px;
            }
        }

        @media (max-width: 992px) {
            .dashboard-section {
                padding: 16px;
                border-radius: 12px;
            }
            
            .section-header h5 {
                font-size: 15px;
            }
        }

        @media (max-width: 768px) {
            .cards {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .equal-height-row > [class*="col-"] {
                display: block;
            }
            
            .dashboard-section {
                min-height: auto !important;
                margin-bottom: 1rem;
                padding: 15px;
            }
            
            .booking-item, .venue-item, .notification-item {
                padding: 10px 0;
                flex-direction: column;
                align-items: flex-start;
            }
            
            .booking-item > div:last-child, 
            .venue-item > div:last-child {
                margin-top: 8px;
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .section-header {
                padding-bottom: 12px;
                margin-bottom: 12px;
            }
            
            .lihat-semua-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .card {
                padding: 15px;
            }
            
            .dashboard-section {
                padding: 12px;
                border-radius: 10px;
            }
            
            .section-header h5 {
                font-size: 14px;
            }
            
            .booking-info strong, .venue-item strong {
                font-size: 13px;
            }
            
            .booking-info p, .venue-item p {
                font-size: 12px;
            }
            
            .status {
                font-size: 11px;
                padding: 2px 6px;
            }
            
            .lihat-semua-btn {
                padding: 5px 10px;
                font-size: 0.75rem;
            }
        }
    </style>
@endsection