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
            grid-template-columns: repeat(3, 1fr);
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

        /* Payment option section */
        .payment-option-section {
            margin-bottom: 20px;
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }

        .method-card {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .method-card:hover {
            border-color: var(--primary-color);
            background: rgba(98, 147, 196, 0.05);
        }

        .method-card.selected {
            border-color: var(--primary-color);
            background: rgba(98, 147, 196, 0.1);
        }

        .method-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            margin-right: 15px;
        }

        .method-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            flex: 1;
        }

        .method-arrow {
            color: var(--text-light);
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        .method-card.active .method-arrow {
            transform: rotate(180deg);
        }

        /* Bank selection styles */
        .bank-options {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .bank-selection-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        .banks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .bank-option {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .bank-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .bank-option.selected {
            border-color: var(--primary-color);
            background: rgba(98, 147, 196, 0.1);
        }

        .bank-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            margin-right: 15px;
        }

        .bank-info {
            flex: 1;
        }

        .bank-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .bank-account {
            font-size: 12px;
            color: var(--text-light);
        }

        .bank-check {
            color: var(--primary-color);
            font-size: 18px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .bank-option.selected .bank-check {
            opacity: 1;
        }

        /* Virtual Account Info */
        .virtual-account-info {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid #cbd5e0;
        }

        .va-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: var(--primary-color);
            font-weight: 600;
        }

        .va-header i {
            margin-right: 10px;
            font-size: 18px;
        }

        .va-number {
            background: white;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            text-align: center;
            letter-spacing: 2px;
            border: 2px dashed #cbd5e0;
            margin-bottom: 15px;
        }

        .va-instruction {
            background: rgba(76, 175, 80, 0.1);
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid #4CAF50;
        }

        .va-instruction p {
            margin: 5px 0;
            color: #2d3748;
            font-size: 14px;
        }

        .va-instruction i {
            color: #4CAF50;
            margin-right: 8px;
        }

        /* QRIS Styles */
        .qris-options {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .qris-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: start;
        }

        @media (max-width: 768px) {
            .qris-container {
                grid-template-columns: 1fr;
            }
        }

        .qris-code {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .qris-code img {
            max-width: 200px;
            width: 100%;
            height: auto;
            margin-bottom: 15px;
        }

        .qris-refresh {
            margin-top: 15px;
        }

        .refresh-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .refresh-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .qris-instruction {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }

        .qris-instruction h4 {
            color: var(--primary-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qris-instruction ol {
            padding-left: 20px;
            margin: 0;
        }

        .qris-instruction li {
            margin-bottom: 10px;
            color: var(--text-dark);
        }

        .payment-expiry {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 12px 16px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #856404;
        }

        .payment-expiry i {
            color: #ffc107;
        }

        /* Payment Selected Info */
        .payment-selected {
            background: rgba(76, 175, 80, 0.1);
            border-radius: 8px;
            padding: 10px 15px;
            margin-top: 10px;
            display: none;
        }

        .payment-selected.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .payment-selected-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .payment-method-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .payment-method-info i {
            color: #6293c4ff;
            font-size: 18px;
        }

        .payment-change {
            color: var(--primary-color);
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
        }

        .payment-change:hover {
            text-decoration: underline;
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

        .payment-timer {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 12px 16px;
            margin-top: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #856404;
        }
        
        .payment-timer i {
            color: #ffc107;
        }
        
        .spinner-border {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: text-bottom;
            border: 0.15em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border 0.75s linear infinite;
        }
        
        .spinner-border-sm {
            width: 0.875rem;
            height: 0.875rem;
            border-width: 0.1em;
        }
        
        @keyframes spinner-border {
            to { transform: rotate(360deg); }
        }

        /* ==== RESPONSIVE DESIGN ==== */
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
            
            .booking-details-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .banks-grid {
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
                <?php echo e(number_format($venue->rating ?? 4.5, 1)); ?>/5
            </span>
        </div>
    </div>
    
    <!-- Venue Content -->
    <div class="venue-content">
        <!-- Header dengan nama dan harga -->
        <div class="venue-header">
            <h2 class="venue-name"><?php echo e($venue->name); ?></h2>
            <div class="venue-price">
                <div class="price-label">Harga per jam</div>
                <div class="price-tag">Rp <?php echo e(number_format($venue->price_per_hour, 0, ',', '.')); ?></div>
            </div>
        </div>

        <!-- Informasi venue -->
        <div class="venue-info">
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span><?php echo e($venue->address); ?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-clock"></i>
                <span>Buka: <?php echo e($venue->opening_hours ?? '06.00–23.00'); ?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-basketball"></i>
                <span>Kategori: <?php echo e($venue->category); ?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-star"></i>
                <span>Rating: <?php echo e(number_format($venue->rating ?? 4.5, 1)); ?>/5.0</span>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="booking-details-section">
            <h3 class="section-title">
                <i class="far fa-calendar-alt"></i>
                Detail Booking
            </h3>
            <div class="booking-details-grid">
                <div class="booking-detail-item">
                    <div class="booking-detail-label">Kode Booking</div>
                    <div class="booking-detail-value"><strong><?php echo e($booking->booking_code); ?></strong></div>
                </div>
                <div class="booking-detail-item">
                    <div class="booking-detail-label">Tanggal</div>
                    <div class="booking-detail-value"><?php echo e(\Carbon\Carbon::parse($booking->tanggal_booking)->isoFormat('dddd, D MMMM YYYY')); ?></div>
                </div>
                <div class="booking-detail-item">
                    <div class="booking-detail-label">Waktu</div>
                    <div class="booking-detail-value"><?php echo e(\Carbon\Carbon::parse($booking->waktu_booking)->format('H:i')); ?></div>
                </div>
                <div class="booking-detail-item">
                    <div class="booking-detail-label">Durasi</div>
                    <div class="booking-detail-value"><?php echo e($booking->durasi); ?> Jam</div>
                </div>
            </div>
            
            <!-- Timer Countdown -->
            <?php if($remainingMinutes > 0): ?>
            <div class="payment-timer">
                <i class="fas fa-clock"></i>
                <span>Selesaikan pembayaran dalam: <strong id="countdown-timer"><?php echo e($remainingMinutes); ?> menit</strong></span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Payment Methods -->
        <form id="payment-form" action="<?php echo e(route('pesan.proses-bayar')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="booking_code" value="<?php echo e($booking->booking_code); ?>">
            <input type="hidden" name="payment_method" id="payment_method" value="">
            <input type="hidden" name="bank_code" id="bank_code" value="">
            
            <div class="payment-methods-section">
                <h3 class="section-title">
                    <i class="far fa-credit-card"></i>
                    Metode Pembayaran
                </h3>
                
                <!-- Opsi Transfer Bank -->
                <div class="payment-option-section">
                    <div class="method-card" onclick="togglePaymentOption('transfer')">
                        <div class="method-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="method-name">Transfer Bank</div>
                        <div class="method-arrow">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    
                    <div id="bank-selection" class="bank-options" style="display: none;">
                        <h4 class="bank-selection-title">Pilih Bank</h4>
                        <div class="banks-grid">
                            <div class="bank-option" onclick="selectBank('bca', this)">
                                <div class="bank-logo">
                                    <i class="fas fa-landmark"></i>
                                </div>
                                <div class="bank-info">
                                    <div class="bank-name">BCA</div>
                                    <div class="bank-account">Virtual Account</div>
                                </div>
                                <div class="bank-check">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            
                            <div class="bank-option" onclick="selectBank('mandiri', this)">
                                <div class="bank-logo">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="bank-info">
                                    <div class="bank-name">Mandiri</div>
                                    <div class="bank-account">Virtual Account</div>
                                </div>
                                <div class="bank-check">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            
                            <div class="bank-option" onclick="selectBank('bri', this)">
                                <div class="bank-logo">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="bank-info">
                                    <div class="bank-name">BRI</div>
                                    <div class="bank-account">Virtual Account</div>
                                </div>
                                <div class="bank-check">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            
                            <div class="bank-option" onclick="selectBank('bni', this)">
                                <div class="bank-logo">
                                    <i class="fas fa-columns"></i>
                                </div>
                                <div class="bank-info">
                                    <div class="bank-name">BNI</div>
                                    <div class="bank-account">Virtual Account</div>
                                </div>
                                <div class="bank-check">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="virtual-account-info" id="va-info" style="display: none;">
                            <div class="va-header">
                                <i class="fas fa-credit-card"></i>
                                <span>Nomor Virtual Account</span>
                            </div>
                            <div class="va-number" id="va-number">
                                <!-- Nomor VA akan ditampilkan di sini -->
                            </div>
                            <div class="va-instruction">
                                <p><i class="fas fa-info-circle"></i> Bayar sebelum: <strong><?php echo e(\Carbon\Carbon::now()->addHours(1)->format('H:i')); ?></strong></p>
                                <p><i class="fas fa-clock"></i> Berlaku selama 1 jam</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Opsi QRIS -->
                <div class="payment-option-section">
                    <div class="method-card" onclick="togglePaymentOption('qris')">
                        <div class="method-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div class="method-name">QRIS</div>
                        <div class="method-arrow">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    
                    <div id="qris-display" class="qris-options" style="display: none;">
                        <div class="qris-container">
                            <div class="qris-code">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo e(urlencode('BOOKING-' . $booking->booking_code . '-' . $booking->total_biaya)); ?>" 
                                     alt="QRIS Code" id="qris-image">
                                <div class="qris-refresh">
                                    <button type="button" onclick="refreshQRIS()" class="refresh-btn">
                                        <i class="fas fa-sync-alt"></i> Refresh QR
                                    </button>
                                </div>
                            </div>
                            
                            <div class="qris-instruction">
                                <h4><i class="fas fa-mobile-alt"></i> Cara Bayar:</h4>
                                <ol>
                                    <li>Buka aplikasi e-wallet atau mobile banking</li>
                                    <li>Pilih fitur <strong>Scan QRIS</strong></li>
                                    <li>Arahkan kamera ke kode QR di atas</li>
                                    <li>Konfirmasi pembayaran</li>
                                    <li>Pembayaran akan diverifikasi otomatis</li>
                                </ol>
                            </div>
                        </div>
                        
                        <div class="payment-expiry">
                            <i class="fas fa-clock"></i>
                            <span>QRIS berlaku selama: <strong id="qris-timer">15:00</strong> menit</span>
                        </div>
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
                        <div class="summary-label">Sewa lapangan (<?php echo e($booking->durasi); ?> jam)</div>
                        <div class="summary-value">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">Biaya admin</div>
                        <div class="summary-value">Rp <?php echo e(number_format($adminFee, 0, ',', '.')); ?></div>
                    </div>
                    <div class="summary-row total-row">
                        <div class="total-label">Total Pembayaran</div>
                        <div class="total-value">Rp <?php echo e(number_format($totalPayment, 0, ',', '.')); ?></div>
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
                    <div class="payment-selected" id="payment-selected">
                        <!-- Menampilkan metode yang dipilih -->
                    </div>
                </div>
                <div class="booking-actions">
                    <a href="<?php echo e(route('pesan.riwayat-booking')); ?>" class="action-btn btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                    <button type="submit" class="action-btn btn-pay" id="pay-button" disabled>
                        <i class="fas fa-lock"></i>
                        Lanjutkan Pembayaran
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

<script>
    // Variabel global untuk pembayaran
    let selectedPaymentMethod = '';
    let selectedBank = '';
    let qrisTimer = null;
    let qrisTimeLeft = 900; // 15 menit dalam detik

    // Fungsi untuk memilih opsi pembayaran
    function togglePaymentOption(method) {
        event.preventDefault();
        
        // Reset semua
        document.querySelectorAll('.method-card').forEach(el => {
            el.classList.remove('active', 'selected');
        });
        
        // Sembunyikan semua opsi
        document.getElementById('bank-selection').style.display = 'none';
        document.getElementById('qris-display').style.display = 'none';
        
        // Aktifkan metode yang dipilih
        const activeCard = event.currentTarget.closest('.method-card');
        activeCard.classList.add('active', 'selected');
        
        // Tampilkan opsi yang sesuai
        if (method === 'transfer') {
            document.getElementById('bank-selection').style.display = 'block';
            document.getElementById('payment_method').value = 'transfer';
            selectedPaymentMethod = 'transfer';
            
            // Reset bank selection
            document.querySelectorAll('.bank-option').forEach(el => {
                el.classList.remove('selected');
            });
            document.getElementById('va-info').style.display = 'none';
            
        } else if (method === 'qris') {
            document.getElementById('qris-display').style.display = 'block';
            document.getElementById('payment_method').value = 'qris';
            selectedPaymentMethod = 'qris';
            
            // Mulai timer QRIS
            startQRISTimer();
            
            // Generate QRIS code
            generateQRIS();
        }
        
        // Reset tombol bayar
        document.getElementById('pay-button').disabled = true;
        updatePaymentSelected();
    }

    // Fungsi untuk memilih bank
    function selectBank(bankCode, element) {
        event.preventDefault();
        
        // Reset pilihan bank
        document.querySelectorAll('.bank-option').forEach(el => {
            el.classList.remove('selected');
        });
        
        // Aktifkan bank yang dipilih
        element.classList.add('selected');
        selectedBank = bankCode;
        document.getElementById('bank_code').value = bankCode;
        
        // Tampilkan info Virtual Account
        showVirtualAccount(bankCode);
        
        // Aktifkan tombol bayar
        document.getElementById('pay-button').disabled = false;
        updatePaymentSelected();
    }

    // Fungsi untuk menampilkan nomor Virtual Account
    function showVirtualAccount(bankCode) {
        const vaInfo = document.getElementById('va-info');
        const vaNumber = document.getElementById('va-number');
        
        // Generate nomor VA berdasarkan booking code
        const bookingCode = "<?php echo e($booking->booking_code); ?>";
        const vaPrefix = getVAPrefix(bankCode);
        const vaNumberStr = generateVANumber(vaPrefix, bookingCode);
        
        vaNumber.textContent = vaNumberStr;
        vaInfo.style.display = 'block';
    }

    // Fungsi untuk mendapatkan prefix VA berdasarkan bank
    function getVAPrefix(bankCode) {
        const prefixes = {
            'bca': '827',
            'mandiri': '888',
            'bri': '801',
            'bni': '809'
        };
        return prefixes[bankCode] || '888';
    }

    // Fungsi untuk generate nomor VA
    function generateVANumber(prefix, bookingCode) {
        // Ambil 6 digit terakhir booking code
        const bookingDigits = bookingCode.replace(/[^0-9]/g, '').slice(-6);
        // Gabungkan dengan prefix dan tambahkan digit acak
        const randomDigits = Math.floor(1000 + Math.random() * 9000);
        return `${prefix}${bookingDigits}${randomDigits}`;
    }

    // Fungsi untuk generate QRIS
    function generateQRIS() {
        const bookingCode = "<?php echo e($booking->booking_code); ?>";
        const totalAmount = <?php echo e($totalPayment); ?>;
        const timestamp = Date.now();
        
        // Update QRIS image dengan timestamp untuk menghindari cache
        const qrisImage = document.getElementById('qris-image');
        qrisImage.src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(`BOOKING-${bookingCode}-${totalAmount}-${timestamp}`)}`;
    }

    // Fungsi untuk refresh QRIS
    function refreshQRIS() {
        event.preventDefault();
        generateQRIS();
        // Reset timer
        clearInterval(qrisTimer);
        qrisTimeLeft = 900;
        startQRISTimer();
        
        // Tampilkan notifikasi
        showNotification('QRIS telah diperbarui', 'success');
    }

    // Fungsi untuk memulai timer QRIS
    function startQRISTimer() {
        clearInterval(qrisTimer);
        
        qrisTimer = setInterval(() => {
            qrisTimeLeft--;
            
            const minutes = Math.floor(qrisTimeLeft / 60);
            const seconds = qrisTimeLeft % 60;
            
            document.getElementById('qris-timer').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (qrisTimeLeft <= 0) {
                clearInterval(qrisTimer);
                showNotification('QRIS telah kadaluarsa, silakan refresh', 'warning');
            }
        }, 1000);
    }

    // Fungsi untuk update tampilan metode yang dipilih
    function updatePaymentSelected() {
        const paymentSelected = document.getElementById('payment-selected');
        
        if (selectedPaymentMethod && (selectedPaymentMethod === 'qris' || selectedBank)) {
            let methodText = '';
            let icon = '';
            
            if (selectedPaymentMethod === 'transfer' && selectedBank) {
                const bankNames = {
                    'bca': 'BCA Virtual Account',
                    'mandiri': 'Mandiri Virtual Account',
                    'bri': 'BRI Virtual Account',
                    'bni': 'BNI Virtual Account'
                };
                methodText = bankNames[selectedBank];
                icon = '<i class="fas fa-university"></i>';
            } else if (selectedPaymentMethod === 'qris') {
                methodText = 'QRIS';
                icon = '<i class="fas fa-qrcode"></i>';
            }
            
            paymentSelected.innerHTML = `
                <div class="payment-selected-content">
                    <div class="payment-method-info">
                        ${icon}
                        <span>${methodText}</span>
                    </div>
                    <span class="payment-change" onclick="resetPaymentSelection()">Ubah</span>
                </div>
            `;
            paymentSelected.classList.add('show');
        } else {
            paymentSelected.classList.remove('show');
        }
    }

    // Fungsi untuk reset pilihan pembayaran
    function resetPaymentSelection() {
        selectedPaymentMethod = '';
        selectedBank = '';
        
        document.querySelectorAll('.method-card').forEach(el => {
            el.classList.remove('active', 'selected');
        });
        
        document.getElementById('bank-selection').style.display = 'none';
        document.getElementById('qris-display').style.display = 'none';
        document.getElementById('va-info').style.display = 'none';
        document.getElementById('payment-selected').classList.remove('show');
        document.getElementById('pay-button').disabled = true;
        
        // Reset input hidden
        document.getElementById('payment_method').value = '';
        document.getElementById('bank_code').value = '';
        
        // Hentikan timer QRIS
        clearInterval(qrisTimer);
    }

    // Fungsi untuk menampilkan notifikasi
    function showNotification(message, type) {
        // Buat elemen notifikasi jika belum ada
        let notification = document.querySelector('.notification-container');
        if (!notification) {
            notification = document.createElement('div');
            notification.className = 'notification-container';
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 10px;
            `;
            document.body.appendChild(notification);
        }
        
        const notificationEl = document.createElement('div');
        notificationEl.className = `notification notification-${type}`;
        notificationEl.style.cssText = `
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            transform: translateX(120%);
            transition: transform 0.3s ease;
            border-left: 4px solid ${type === 'success' ? '#4CAF50' : type === 'warning' ? '#FF9800' : '#f44336'};
        `;
        
        notificationEl.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}" 
               style="color: ${type === 'success' ? '#4CAF50' : type === 'warning' ? '#FF9800' : '#f44336'}; font-size: 20px;"></i>
            <span>${message}</span>
        `;
        
        notification.appendChild(notificationEl);
        
        // Animasi masuk
        setTimeout(() => notificationEl.style.transform = 'translateX(0)', 10);
        
        // Hapus setelah 3 detik
        setTimeout(() => {
            notificationEl.style.transform = 'translateX(120%)';
            setTimeout(() => notificationEl.remove(), 300);
        }, 3000);
    }

    // Validasi form sebelum submit
    document.getElementById('payment-form').addEventListener('submit', function(e) {
        if (!selectedPaymentMethod) {
            e.preventDefault();
            showNotification('Pilih metode pembayaran terlebih dahulu', 'warning');
            return;
        }
        
        if (selectedPaymentMethod === 'transfer' && !selectedBank) {
            e.preventDefault();
            showNotification('Pilih bank terlebih dahulu', 'warning');
            return;
        }
        
        // Tampilkan loading
        const button = document.getElementById('pay-button');
        button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';
        button.disabled = true;
    });

    // Timer countdown untuk pembayaran
    <?php if($remainingMinutes > 0): ?>
    let paymentExpiresAt = new Date("<?php echo e($expiresAt); ?>").getTime();
    
    let paymentCountdown = setInterval(function() {
        let now = new Date().getTime();
        let distance = paymentExpiresAt - now;
        
        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        document.getElementById("countdown-timer").innerHTML = 
            minutes + ":" + seconds.toString().padStart(2, '0');
        
        if (distance < 0) {
            clearInterval(paymentCountdown);
            document.getElementById("countdown-timer").innerHTML = "EXPIRED";
            showNotification('Waktu pembayaran telah habis', 'error');
            setTimeout(() => {
                window.location.href = "<?php echo e(route('pesan.riwayat-booking')); ?>";
            }, 2000);
        }
    }, 1000);
    <?php endif; ?>

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Set nilai default hidden input
        document.getElementById('payment_method').value = '';
        document.getElementById('bank_code').value = '';
    });
</script>
</body>
</html><?php /**PATH D:\CariArena\resources\views/user/pesan/bayar.blade.php ENDPATH**/ ?>