@extends('layouts.user')
@section('title', 'Detail Booking')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    body:has(.main-content-container) .volley-icon-main,
    body:has(.booking-container) .volley-icon-main {
        margin-left: auto !important;
        margin-right: auto !important;
        display: block !important;
        float: none !important;
        text-align: center !important;
        position: relative !important;
        left: 0 !important;
        right: 0 !important;
    }

    body:has(.main-content-container) .center-header,
    body:has(.booking-container) .center-header {
        text-align: center !important;
        display: block !important;
        width: 100% !important;
    }

    /* ========== VARIABLES ========== */
    :root {
        --primary-color: #6293c4;
        --primary-hover: #4a7cb0;
        --primary-light: #E8F4FD;
        --text-dark: #1A202C;
        --text-light: #64748b;
        --bg-light: #f8fafc;
        --card-bg: #FFFFFF;
        --success: #1AC42E;
        --danger: #FE2222;
        --warning: #F59E0B;
        --info: #6293c4;
        --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        --card-radius: 16px;
        --secondary-color: #6c757d;
        --secondary-hover: #5a6268;
        --border-color: #e2e8f0;
    }

/* ==== MAIN CONTAINER ==== */
    .main-content-container {
        height: 100%;
        background: #f8fafc;
        min-height: 100vh;
        padding: 25px 20px 40px;
    }

    /* ========== BOOKING CONTAINER ========== */
    .booking-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    .booking-content {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-color);
        animation: fadeInUp 0.6s ease-out;
    }

    /* ========== BOOKING STATUS ========== */
    .booking-status {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1px solid #bae6fd;
        min-height: 80px;
    }

    .status-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .status-icon {
        width: 50px;
        height: 50px;
        background: var(--success);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(26, 196, 46, 0.3);
    }

    .status-text h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .status-text p {
        font-size: 14px;
        color: var(--text-light);
        line-height: 1.4;
    }

    .booking-id-display {
        text-align: right;
        min-width: 200px;
    }

    .booking-id-label {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 6px;
        font-weight: 600;
    }

    .booking-id-value {
        font-size: 22px;
        font-weight: 700;
        color: var(--primary-color);
        font-family: 'Courier New', monospace;
    }

    /* ========== VENUE SECTION ========== */
    .venue-section {
        margin-bottom: 30px;
        padding-bottom: 25px;
        border-bottom: 1px solid var(--border-color);
    }

    .venue-header {
        display: flex;
        align-items: flex-start;
        gap: 25px;
        margin-bottom: 20px;
    }

    .venue-image {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
        text-align: center;
        padding: 15px;
        box-shadow: 0 4px 15px rgba(98, 147, 196, 0.3);
    }

    .venue-details {
        flex: 1;
        padding-top: 10px;
    }

    .venue-name {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--text-dark);
        line-height: 1.3;
    }

    .venue-meta {
        display: flex;
        gap: 12px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--text-dark);
        padding: 8px 12px;
        background: var(--bg-light);
        border-radius: 8px;
        font-weight: 600;
        border: 1px solid var(--border-color);
    }

    .meta-item i {
        color: var(--primary-color);
        font-size: 14px;
    }

    .venue-address {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 14px;
        color: var(--text-light);
        line-height: 1.5;
        padding: 15px;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 3px solid var(--primary-color);
    }

    .venue-address i {
        color: var(--primary-color);
        margin-top: 2px;
        font-size: 16px;
        flex-shrink: 0;
    }

    /* ========== BOOKING DETAILS GRID ========== */
    .booking-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .detail-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        border: 2px solid var(--border-color);
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        min-height: 120px;
    }

    .detail-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        border-color: var(--primary-color);
    }

    .detail-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .detail-icon {
        width: 40px;
        height: 40px;
        background: var(--primary-color);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(98, 147, 196, 0.3);
    }

    .detail-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
    }

    .detail-content {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 6px;
        padding-left: 55px;
    }

    .detail-subtitle {
        font-size: 13px;
        color: var(--text-light);
        padding-left: 55px;
    }

    /* ========== IMPORTANT NOTES ========== */
    .important-section {
        background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        border-left: 4px solid var(--warning);
        box-shadow: 0 2px 8px rgba(251, 191, 36, 0.2);
    }

    .important-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
    }

    .important-header i {
        color: var(--warning);
        font-size: 20px;
    }

    .important-title {
        font-size: 16px;
        font-weight: 700;
        color: #92400e;
    }

    .important-list {
        list-style-type: none;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .important-list li {
        margin-bottom: 0;
        padding: 10px 0 10px 25px;
        position: relative;
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #92400e;
        font-weight: 500;
        border-bottom: 1px solid rgba(251, 191, 36, 0.4);
    }

    .important-list li:last-child {
        border-bottom: none;
    }

    .important-list li::before {
        content: "•";
        position: absolute;
        left: 0;
        color: var(--warning);
        font-size: 24px;
        font-weight: bold;
        line-height: 1;
    }

    /* ========== CONTACT SECTION ========== */
    .contact-section {
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border-color);
    }

    .section-title i {
        color: var(--primary-color);
        font-size: 18px;
    }

    .contact-info {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .contact-item {
        display: flex;
        align-items: center;
        padding: 15px;
        background: white;
        border-radius: 10px;
        transition: all 0.3s ease;
        border: 2px solid var(--border-color);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        min-height: 80px;
    }

    .contact-item:hover {
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        border-color: var(--primary-color);
    }

    .contact-icon {
        width: 50px;
        height: 50px;
        background: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: white;
        font-size: 20px;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(98, 147, 196, 0.3);
    }

    .contact-text {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-dark);
    }

    /* ========== ACTION BUTTONS ========== */
    .action-section {
        padding-top: 25px;
        border-top: 2px solid var(--border-color);
        margin-top: 25px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 2px solid transparent;
        min-width: 180px;
    }

    .btn-secondary {
        background: var(--secondary-color);
        color: white;
        border-color: var(--secondary-color);
    }

    .btn-secondary:hover {
        background: var(--secondary-hover);
        border-color: var(--secondary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(98, 147, 196, 0.3);
    }

    /* Tombol Kembali ke Beranda */
    .btn-home {
        background: white;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .btn-home:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(98, 147, 196, 0.3);
    }

    /* ========== RESPONSIVE DESIGN ========== */
    @media (max-width: 1199px) {
        .booking-details {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .important-list {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 991px) {
        .venue-header {
            flex-direction: column;
            gap: 20px;
        }
        
        .venue-image {
            width: 100%;
            height: 150px;
            font-size: 24px;
        }
        
        .venue-meta {
            gap: 10px;
        }
        
        .meta-item {
            padding: 6px 10px;
            font-size: 12px;
        }
        
        .booking-details {
            grid-template-columns: 1fr;
        }
        
        .contact-info {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }
        
        .btn {
            width: 100%;
            max-width: 300px;
        }
    }

    @media (max-width: 767px) {
        .main-content-container {
            padding: 30px 15px;
        }
        
        .booking-container {
            padding: 0;
        }
        
        .booking-content {
            padding: 20px;
        }
        
        .booking-status {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
            padding: 15px;
        }
        
        .booking-id-display {
            text-align: left;
            min-width: auto;
            width: 100%;
        }
        
        .status-info {
            width: 100%;
        }
        
        .venue-name {
            font-size: 20px;
        }
        
        .detail-content {
            font-size: 18px;
            padding-left: 0;
        }
        
        .detail-subtitle {
            padding-left: 0;
        }
    }

    @media (max-width: 480px) {
        .main-content-container {
            padding: 20px 12px;
        }
        
        .booking-content {
            padding: 15px;
        }
        
        .venue-name {
            font-size: 18px;
        }
        
        .venue-address {
            font-size: 13px;
            padding: 12px;
        }
        
        .detail-card {
            padding: 15px;
        }
        
        .detail-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .detail-icon {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }
        
        .detail-content {
            font-size: 16px;
        }
        
        .important-section {
            padding: 15px;
        }
        
        .important-list li {
            font-size: 13px;
            padding: 8px 0 8px 20px;
        }
        
        .contact-item {
            padding: 12px;
        }
        
        .contact-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
            margin-right: 12px;
        }
        
        .contact-text {
            font-size: 14px;
        }
        
        .btn {
            padding: 10px 15px;
            font-size: 13px;
            min-width: auto;
        }
    }

    @media (max-width: 360px) {
        .venue-image {
            height: 120px;
            font-size: 20px;
        }
        
        .meta-item {
            font-size: 11px;
            padding: 5px 8px;
        }
        
        .detail-content {
            font-size: 15px;
        }
        
        .btn {
            font-size: 12px;
            padding: 8px 12px;
        }
    }

    /* ========== ANIMATIONS ========== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('content')
<!-- Main Content Container -->
<div class="main-content-container">
    <!-- Booking Detail Card -->
    <div class="booking-container">
        <div class="booking-content">
            <!-- Booking Status -->
            <div class="booking-status">
                <div class="status-info">
                    <div class="status-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="status-text">
                        <h3>PEMBAYARAN BERHASIL</h3>
                        <p>Booking Anda telah dikonfirmasi dan siap digunakan</p>
                    </div>
                </div>
                <div class="booking-id-display">
                    <div class="booking-id-label">ID BOOKING</div>
                    <div class="booking-id-value" id="booking-code">JD196652</div>
                </div>
            </div>

            <!-- Venue Section -->
            <div class="venue-section">
                <div class="venue-header">
                    <div class="venue-image" id="venue-image">
                        ARENA FUTSAL
                    </div>
                    <div class="venue-details">
                        <h2 class="venue-name" id="venue-name">ARENA FUTSAL CORNER</h2>
                        <div class="venue-meta">
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span id="venue-category">FUTSAL</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-star"></i>
                                <span>4.8 / 5.0</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>06.00 - 23.00</span>
                            </div>
                        </div>
                        <div class="venue-address">
                            <i class="fas fa-location-dot"></i>
                            <span id="venue-address">Bintaro, Jakarta Selatan - Jalan Sultan Agung No. 45</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Details Grid -->
            <div class="booking-details">
                <div class="detail-card">
                    <div class="detail-header">
                        <div class="detail-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="detail-title">TANGGAL BOOKING</div>
                    </div>
                    <div class="detail-content" id="booking-date">MINGGU, 2 NOVEMBER 2025</div>
                    <div class="detail-subtitle">Tanggal pemesanan venue</div>
                </div>

                <div class="detail-card">
                    <div class="detail-header">
                        <div class="detail-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="detail-title">WAKTU & DURASI</div>
                    </div>
                    <div class="detail-content" id="booking-time-duration">07.00 (2 JAM)</div>
                    <div class="detail-subtitle">Durasi penggunaan venue</div>
                </div>

                <div class="detail-card">
                    <div class="detail-header">
                        <div class="detail-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="detail-title">JUMLAH PEMAIN</div>
                    </div>
                    <div class="detail-content" id="booking-players">10 ORANG</div>
                    <div class="detail-subtitle">Kapasitas maksimal venue</div>
                </div>

                <div class="detail-card">
                    <div class="detail-header">
                        <div class="detail-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="detail-title">TOTAL PEMBAYARAN</div>
                    </div>
                    <div class="detail-content" id="booking-total">RP 240.000</div>
                    <div class="detail-subtitle">Sudah termasuk pajak & admin</div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="important-section">
                <div class="important-header">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="important-title">PENTING UNTUK DIINGAT</div>
                </div>
                <ul class="important-list">
                    <li>Datang 15 menit sebelum waktu booking</li>
                    <li>Bawa ID booking dan kartu identitas</li>
                    <li>Hubungi venue jika ada perubahan rencana</li>
                    <li>Booking dapat dibatalkan maksimal 2 jam sebelumnya</li>
                </ul>
            </div>

            <!-- Contact Section -->
            <div class="contact-section">
                <h3 class="section-title">
                    <i class="fas fa-phone-alt"></i>
                    KONTAK VENUE
                </h3>
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-text">(021) 7543-2109</div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="contact-text">+62 812-3456-7890</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-section">
                <div class="action-buttons">
                    <a href="{{ route('riwayat') }}" class="btn btn-secondary">
                        <i class="fas fa-history"></i>
                        RIWAYAT BOOKING
                    </a>
                    <a href="{{ route('beranda') }}" class="btn btn-home">
                        <i class="fas fa-home"></i>
                        KEMBALI KE BERANDA
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>
<script>
    // ========== FIX TAMBAHAN UNTUK BOLA VOLI ==========
    function forceVolleyIconCenter() {
        const volleyIcon = document.querySelector('.volley-icon-main');
        const centerHeader = document.querySelector('.center-header');
        
        if (volleyIcon && centerHeader) {
            volleyIcon.style.cssText += `
                margin-left: auto !important;
                margin-right: auto !important;
                display: block !important;
                float: none !important;
                text-align: center !important;
                position: relative !important;
                left: 0 !important;
                right: 0 !important;
            `;
            
            centerHeader.style.cssText += `
                text-align: center !important;
                display: block !important;
                width: 100% !important;
            `;
        }
    }

    // Fungsi untuk mendapatkan parameter URL
    function getUrlParams() {
        const params = new URLSearchParams(window.location.search);
        const result = {};
        for (const [key, value] of params) {
            result[key] = decodeURIComponent(value);
        }
        return result;
    }

    // Fungsi untuk mengupdate data dari parameter URL
    function updateDataFromUrlParams() {
        const params = getUrlParams();
        
        console.log('URL Parameters received:', params);
        
        if (params.status === 'success') {
            // Update booking code
            if (params.booking_code) {
                document.getElementById('booking-code').textContent = params.booking_code;
            }
            
            // Update tanggal booking
            if (params.date) {
                document.getElementById('booking-date').textContent = params.date.toUpperCase();
            }
            
            // Update waktu dan durasi booking
            if (params.time && params.duration) {
                const timeText = params.time;
                const durationText = params.duration.replace('Jam', '').trim();
                document.getElementById('booking-time-duration').textContent = `${timeText.toUpperCase()} (${durationText} JAM)`;
            } else if (params.time) {
                document.getElementById('booking-time-duration').textContent = `${params.time.toUpperCase()} (2 JAM)`;
            }
            
            // Update nama venue
            if (params.venue_name) {
                const venueName = params.venue_name.toUpperCase();
                document.getElementById('venue-name').textContent = venueName;
                
                // Update venue image text
                const venueInitials = venueName.split(' ').map(word => word[0]).join('');
                document.getElementById('venue-image').textContent = venueInitials.substring(0, 4) || 'VENUE';
            }
            
            // Update alamat venue
            if (params.address) {
                document.getElementById('venue-address').textContent = params.address;
            }
            
            // Update kategori venue
            if (params.category) {
                document.getElementById('venue-category').textContent = params.category.toUpperCase();
            }
            
            // Update jumlah pemain
            if (params.players) {
                document.getElementById('booking-players').textContent = `${params.players} ORANG`;
            }
            
            // Update total pembayaran
            if (params.total) {
                const total = parseInt(params.total);
                
                // Format currency
                const formatCurrency = (amount) => {
                    return `RP ${amount.toLocaleString('id-ID')}`;
                };
                
                // Update booking total
                document.getElementById('booking-total').textContent = formatCurrency(total);
            }
        }
    }

    // JavaScript untuk mengontrol tampilan
    document.addEventListener('DOMContentLoaded', function() {
        // Fix bola voli position
        forceVolleyIconCenter();
        
        // Update data dari parameter URL
        updateDataFromUrlParams();

        // Jika ada data dari session PHP, update juga
        @if(session('success'))
            alert('{{ session("success") }}');
        @endif

        @if(isset($successData) && $successData)
            const successData = @json($successData);
            
            if (successData.booking_code) {
                document.getElementById('booking-code').textContent = successData.booking_code;
            }
            
            if (successData.date) {
                document.getElementById('booking-date').textContent = successData.date.toUpperCase();
            }
            
            if (successData.time && successData.duration) {
                document.getElementById('booking-time-duration').textContent = `${successData.time.toUpperCase()} (${successData.duration})`;
            }
            
            if (successData.venue_name) {
                const venueName = successData.venue_name.toUpperCase();
                document.getElementById('venue-name').textContent = venueName;
                const venueInitials = venueName.split(' ').map(word => word[0]).join('');
                document.getElementById('venue-image').textContent = venueInitials.substring(0, 4) || 'VENUE';
            }
            
            if (successData.address) {
                document.getElementById('venue-address').textContent = successData.address;
            }
            
            if (successData.category) {
                document.getElementById('venue-category').textContent = successData.category.toUpperCase();
            }
            
            if (successData.players) {
                document.getElementById('booking-players').textContent = `${successData.players} ORANG`;
            }
            
            if (successData.total) {
                const total = parseInt(successData.total);
                
                const formatCurrency = (amount) => {
                    return `RP ${amount.toLocaleString('id-ID')}`;
                };
                
                document.getElementById('booking-total').textContent = formatCurrency(total);
            }
        @endif
    });

    // Fix tambahan saat halaman selesai load
    window.addEventListener('load', function() {
        setTimeout(forceVolleyIconCenter, 100);
    });
</script>
@endsection