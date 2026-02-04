<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($venue->name); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ==== VARIABLES ==== */
        :root {
            --primary-color: #6293c4ff;
            --primary-hover: #4a7cb0;
            --text-dark: #1A202C;
            --text-light: #718096;
            --bg-light: #EDF2F7;
            --card-bg: #FFFFFF;
            --success: #1AC42E;
            --danger: #FE2222;
            --warning: #D69E2E;
            --secondary-color: #6c757d;
            --secondary-hover: #5a6268;
        }

        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            min-height: 100vh;
        }

        /* ==== MAIN CONTAINER ==== */
        .booking-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
        }

        /* ==== HEADER SECTION ==== */
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            padding: 0 20px;
        }

        .back-button {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 14px;
            border: 2px solid var(--secondary-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .back-button:hover {
            background: var(--secondary-hover);
            border-color: var(--secondary-hover);
            transform: translateY(-50%) translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 12px;
            padding: 0 80px;
        }

        .page-subtitle {
            font-size: 16px;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
            padding: 0 80px;
        }

        /* ==== PAYMENT DETAIL CARD ==== */
        .venue-detail-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            margin-bottom: 40px;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }

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

        /* ==== VENUE IMAGE ==== */
        .venue-image-section {
            position: relative;
            height: 350px;
            overflow: hidden;
        }

        .venue-image-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .venue-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }

        .badge-category {
            background: rgba(98, 147, 196, 0.9);
        }

        .badge-rating {
            background: rgba(251, 191, 36, 0.9);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ==== VENUE CONTENT ==== */
        .venue-content {
            padding: 30px;
        }

        .venue-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .venue-name {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .venue-price {
            text-align: right;
        }

        .price-label {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 4px;
        }

        .price-tag {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
        }

        /* ==== VENUE INFO ==== */
        .venue-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px 0;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .info-item i {
            color: var(--primary-color);
            font-size: 16px;
            width: 20px;
        }

        /* ==== BOOKING DETAILS SECTION ==== */
        .booking-details-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary-color);
        }

        .booking-details-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* TIGA KOLOM DALAM SATU BARIS */
            gap: 20px;
            margin-top: 15px;
        }

        .booking-detail-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .booking-detail-item:hover {
            background: #f1f5f9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .booking-detail-label {
            font-size: 12px;
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .booking-detail-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* ==== PAYMENT METHODS SECTION ==== */
        .payment-methods-section {
            margin-bottom: 30px;
        }

        /* PERUBAHAN UTAMA: METODE PEMBAYARAN 1 BARIS */
        .methods-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* TIGA KOLOM DALAM SATU BARIS */
            gap: 20px;
            margin-top: 15px;
        }

        .method-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 25px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .method-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .method-card.selected {
            border-color: var(--primary-color);
            background: rgba(98, 147, 196, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(98, 147, 196, 0.15);
        }

        .method-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .method-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* ==== PAYMENT SUMMARY SECTION ==== */
        .payment-summary-section {
            margin-bottom: 30px;
        }

        .summary-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin-top: 15px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: var(--text-light);
            font-size: 15px;
        }

        .summary-value {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 15px;
        }

        .total-row {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            color: white;
        }

        .total-label {
            font-size: 18px;
            font-weight: 700;
        }

        .total-value {
            font-size: 22px;
            font-weight: 800;
        }

        /* ==== PAYMENT ACTIONS SECTION ==== */
        .payment-actions-section {
            padding: 25px 30px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .booking-info {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .booking-label {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 4px;
        }

        .booking-price {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .security-note {
            color: var(--text-light);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .security-note i {
            color: var(--primary-color);
        }

        .booking-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .action-btn {
            padding: 14px 25px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 2px solid transparent;
            text-align: center;
            justify-content: center;
        }

        .btn-back {
            background: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }

        .btn-back:hover {
            background: var(--secondary-hover);
            border-color: var(--secondary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        .btn-pay {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .btn-pay:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(98, 147, 196, 0.3);
        }

        .btn-pay:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* ==== LOADING ANIMATION ==== */
        .loading {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ==== ANIMATIONS ==== */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .back-button {
            animation: slideInLeft 0.5s ease-out;
        }

        /* ==== RESPONSIVE DESIGN (MINIMAL) ==== */
        @media (max-width: 992px) {
            .booking-container {
                padding: 20px;
            }
            
            .page-header {
                padding: 0 10px;
            }
            
            .back-button {
                position: relative;
                margin-bottom: 20px;
                transform: none;
                left: auto;
                top: auto;
                display: inline-flex;
            }
            
            .page-title {
                padding: 0;
                font-size: 28px;
            }
            
            .page-subtitle {
                padding: 0;
                font-size: 14px;
            }
            
            .methods-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .booking-details-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .methods-grid {
                grid-template-columns: 1fr;
            }
            
            .booking-details-grid {
                grid-template-columns: 1fr;
            }
            
            .payment-actions-section {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .booking-actions {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .booking-container {
                padding: 15px;
            }
            
            .venue-content {
                padding: 20px;
            }
            
            .method-card {
                padding: 20px 15px;
            }
            
            .action-btn {
                padding: 12px 20px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="desktop-container">

        <!-- Payment Detail Card -->
        <div class="venue-detail-card">
            <!-- Venue Image -->
            <div class="venue-image-section">
                <img src="<?php echo e($venue->photo ?? $venue->getDefaultPhotoUrl()); ?>" 
                     alt="<?php echo e($venue->name); ?>">
                <div class="venue-overlay">
                    <span class="badge badge-category"><?php echo e(strtoupper($venue->category ?? 'SPORT')); ?></span>
                    <span class="badge badge-rating">
                        <i class="fas fa-star"></i>
                        <?php echo e(number_format($venue->averageRating ?? 4.5, 1)); ?>/5
                    </span>
                </div>
            </div>
            
            <!-- Venue Content -->
            <div class="venue-content">
                <!-- Header dengan nama dan harga -->
                <div class="venue-header">
                    <h2 class="venue-name"><?php echo e($venue->name ?? 'Arena Futsal Corner'); ?></h2>
                    <div class="venue-price">
                        <div class="price-label">Harga per jam</div>
                        <div class="price-tag">Rp <?php echo e(number_format($venue->price_per_hour ?? 120000, 0, ',', '.')); ?></div>
                    </div>
                </div>

                <!-- Informasi venue -->
                <div class="venue-info">
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo e($venue->address ?? 'Bintaro, Jakarta Selatan'); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <span>Buka: 06.00–23.00</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-basketball"></i>
                        <span>Kategori: <?php echo e($venue->category ?? 'Futsal'); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-star"></i>
                        <span>Rating: <?php echo e(number_format($venue->averageRating ?? 4.5, 1)); ?>/5.0</span>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="booking-details-section">
                    <h3 class="section-title">
                        <i class="far fa-calendar-alt"></i>
                        Detail Booking
                    </h3>
                    <!-- PERUBAHAN: DETAIL BOOKING 1 BARIS -->
                    <div class="booking-details-grid">
                        <div class="booking-detail-item">
                            <div class="booking-detail-label">Tanggal</div>
                            <div class="booking-detail-value" id="booking-date"><?php echo e($date ?? 'Minggu, 2 Nov 2023'); ?></div>
                        </div>
                        <div class="booking-detail-item">
                            <div class="booking-detail-label">Waktu</div>
                            <div class="booking-detail-value" id="booking-time"><?php echo e($time ?? '07.00'); ?></div>
                        </div>
                        <div class="booking-detail-item">
                            <div class="booking-detail-label">Durasi</div>
                            <div class="booking-detail-value" id="booking-duration"><?php echo e($duration ?? '2 Jam'); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="payment-methods-section">
                    <h3 class="section-title">
                        <i class="far fa-credit-card"></i>
                        Metode Pembayaran
                    </h3>
                    <!-- PERUBAHAN UTAMA: METODE PEMBAYARAN 1 BARIS -->
                    <div class="methods-grid">
                        <div class="method-card" onclick="selectPayment(this, 'credit_card')">
                            <div class="method-icon">
                                <i class="far fa-credit-card"></i>
                            </div>
                            <div class="method-name">Kartu Kredit/Debit</div>
                        </div>
                        <div class="method-card" onclick="selectPayment(this, 'e_wallet')">
                            <div class="method-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="method-name">E-Wallet</div>
                        </div>
                        <div class="method-card" onclick="selectPayment(this, 'bank_transfer')">
                            <div class="method-icon">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="method-name">Transfer Bank</div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="payment-summary-section">
                    <h3 class="section-title">
                        <i class="fas fa-receipt"></i>
                        Rincian Biaya
                    </h3>
                    <div class="summary-container">
                        <div class="summary-row">
                            <div class="summary-label">Sewa lapangan (<?php echo e($duration ? str_replace(' Jam', '', $duration) : '2'); ?> jam)</div>
                            <div class="summary-value" id="venue-cost">Rp <?php echo e(number_format($total ?? 240000, 0, ',', '.')); ?></div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">Biaya admin</div>
                            <div class="summary-value" id="admin-fee">Rp <?php echo e(number_format($adminFee ?? 5000, 0, ',', '.')); ?></div>
                        </div>
                        <div class="summary-row total-row">
                            <div class="total-label">Total Pembayaran</div>
                            <div class="total-value" id="total-payment">Rp <?php echo e(number_format($totalPayment ?? 245000, 0, ',', '.')); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Payment Actions -->
                <div class="payment-actions-section">
                    <div class="booking-info">
                        <div class="security-note">
                            <i class="fas fa-lock"></i>
                            <span>Pembayaran Anda aman dan terenkripsi</span>
                        </div>
                    </div>
                    <div class="booking-actions">
                        <button class="action-btn btn-back" onclick="goBack()">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </button>
                        <button class="action-btn btn-pay" id="pay-button" onclick="processPayment()">
                            <i class="fas fa-lock"></i>
                            Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variabel global untuk menyimpan metode pembayaran yang dipilih
        let selectedPaymentMethod = 'credit_card';
        
        // Fungsi untuk kembali ke halaman sebelumnya
        function goBack() {
            window.history.back();
        }
        
        // Fungsi untuk memilih metode pembayaran
        function selectPayment(element, method) {
            // Hapus class selected dari semua opsi pembayaran
            document.querySelectorAll('.method-card').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Tambah class selected ke opsi pembayaran yang diklik
            element.classList.add('selected');
            
            // Simpan metode pembayaran yang dipilih
            selectedPaymentMethod = method;
        }
        
        // Fungsi untuk memproses pembayaran
        function processPayment() {
            const button = document.getElementById('pay-button');
            const originalText = button.innerHTML;
            
            // Tampilkan loading
            button.innerHTML = '<span class="loading"></span> Memproses...';
            button.disabled = true;
            
            // Ambil data dari tampilan
            const date = document.getElementById('booking-date').textContent;
            const time = document.getElementById('booking-time').textContent;
            const duration = document.getElementById('booking-duration').textContent;
            
            // Ekstrak total dari tampilan
            const totalText = document.getElementById('total-payment').textContent;
            const total = parseInt(totalText.replace(/[^0-9]/g, ''));
            
            const venueName = "<?php echo e($venue->name ?? 'Arena Futsal Corner'); ?>";
            const address = "<?php echo e($venue->address ?? 'Bintaro, Jakarta Selatan'); ?>";
            
            // Generate booking code
            const bookingCode = 'BK-' + Math.random().toString(36).substr(2, 8).toUpperCase();
            
            // Buat URL redirect ke riwayat booking DENGAN SEMUA PARAMETER
            const redirectUrl = "<?php echo e(route('pesan.riwayat-booking')); ?>?" + new URLSearchParams({
                status: 'success',
                booking_code: bookingCode,
                date: date,
                time: time,
                duration: duration,
                total: total,
                venue_name: venueName,
                address: address,
                payment_method: selectedPaymentMethod
            }).toString();
            
            // Simulasi proses pembayaran (loading effect)
            setTimeout(function() {
                // Langsung redirect ke halaman riwayat booking dengan semua data
                window.location.href = redirectUrl;
            }, 1500);
        }
        
        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Pilih otomatis opsi pembayaran pertama
            const firstPaymentOption = document.querySelector('.method-card');
            if (firstPaymentOption) {
                firstPaymentOption.classList.add('selected');
            }
        });
    </script>
</body>
</html><?php /**PATH D:\CariArena\resources\views/user/pesan/bayar.blade.php ENDPATH**/ ?>