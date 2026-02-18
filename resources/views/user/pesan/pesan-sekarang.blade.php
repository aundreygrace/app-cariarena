<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $venue->name }}</title>
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

        /* ==== VENUE DETAIL CARD ==== */
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

        /* ==== DESCRIPTION SECTION ==== */
        .description-section {
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

        .description-text {
            line-height: 1.7;
            color: var(--text-light);
            font-size: 14px;
        }

        /* ==== FACILITIES SECTION ==== */
        .facilities-section {
            margin-bottom: 30px;
        }

        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .facility-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .facility-item:hover {
            background: #f1f5f9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .facility-item i {
            color: var(--primary-color);
            font-size: 14px;
            width: 16px;
        }

        /* ==== REVIEWS SECTION ==== */
        .reviews-section {
            margin-bottom: 30px;
        }

        .reviews-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .reviews-section-title {
            font-size: 18px;
            color: var(--text-dark);
            font-weight: 600;
        }

        .see-all-btn {
            background: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 6px 16px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .see-all-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .review-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px;
            transition: all 0.3s ease;
        }

        .review-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .review-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .profile-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
        }

        .reviewer-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 14px;
        }

        .review-stars {
            color: #fbbf24;
            font-size: 14px;
            margin-bottom: 5px;
            margin-left: 42px;
        }

        .review-content {
            color: var(--text-light);
            line-height: 1.5;
            font-size: 13px;
        }

        /* ==== BOOKING SECTION ==== */
        .booking-section {
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

        .btn-booking {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .btn-booking:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(98, 147, 196, 0.3);
        }

        /* ==== RESPONSIVE DESIGN ==== */
        @media (max-width: 768px) {
            .booking-container {
                padding: 20px;
            }

            .page-header {
                text-align: center;
                padding: 0 10px;
            }

            .back-button {
                position: relative;
                margin-bottom: 20px;
                transform: none;
                display: inline-flex;
                left: auto;
                top: auto;
                width: auto;
            }

            .page-title {
                padding: 0;
                font-size: 28px;
            }

            .page-subtitle {
                padding: 0;
                font-size: 14px;
            }

            /* PERBAIKAN UTAMA: Nama venue dan jam operasional sejajar dalam satu baris */
            .venue-info {
                grid-template-columns: 1fr;
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
            }
            
            .venue-info .info-item {
                flex: 1;
                min-width: calc(50% - 15px);
                margin-bottom: 0;
            }

            .venue-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .venue-price {
                text-align: left;
            }

            .reviews-grid {
                grid-template-columns: 1fr;
            }

            .booking-section {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .booking-actions {
                width: 100%;
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .booking-container {
                padding: 16px;
            }

            .page-title {
                font-size: 24px;
            }

            .back-button {
                padding: 10px 16px;
                font-size: 13px;
            }

            .venue-content {
                padding: 20px;
            }

            .booking-section {
                padding: 20px;
            }

            .action-btn {
                padding: 12px 20px;
                font-size: 13px;
            }
            
            /* PERBAIKAN: Tampilan lebih kompak untuk layar kecil */
            .venue-info .info-item {
                min-width: 100%;
                flex: 0 0 100%;
            }
            
            /* Memastikan alamat dan jam sejajar di layar kecil */
            .venue-info {
                display: grid;
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .venue-info .info-item:nth-child(1),
            .venue-info .info-item:nth-child(2) {
                display: inline-flex;
                width: 100%;
            }
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
    </style>
</head>
<body>

    <div class="desktop">

        <!-- Venue Detail Card -->
        <div class="venue-detail-card">
            <!-- Venue Image -->
            <div class="venue-image-section">
                <img src="{{ $venue->photo_url }}" 
                        alt="{{ $venue->name }}"
                            onerror="this.src='{{ $venue->getDefaultPhotoUrl() }}'">
                <div class="venue-overlay">
                    <span class="badge badge-category">{{ strtoupper($venue->category) }}</span>
                    <span class="badge badge-rating">
                        <i class="fas fa-star"></i>
                        {{ number_format($averageRating, 1) }}/5
                    </span>
                </div>
            </div>
            
            <!-- Venue Content -->
            <div class="venue-content">
                <!-- Header dengan nama dan harga -->
                <div class="venue-header">
                    <h2 class="venue-name">{{ $venue->name }}</h2>
                    <div class="venue-price">
                        <div class="price-label">Mulai dari</div>
                        <div class="price-tag">Rp {{ number_format($venue->price_per_hour, 0, ',', '.') }}/jam</div>
                    </div>
                </div>

                <!-- Informasi venue - PERBAIKAN STRUKTUR -->
                <div class="venue-info">
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $venue->name }}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <span>Buka: 06.00–23.00</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-basketball"></i>
                        <span>Kategori: {{ $venue->category }}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-star"></i>
                        <span>Rating: {{ number_format($averageRating, 1) }}/5.0 ({{ $reviewsCount }} ulasan)</span>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="description-section">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Deskripsi Venue
                    </h3>
                    <p class="description-text">
                        {{ $venue->description ?? 'Venue olahraga berkualitas dengan fasilitas lengkap dan pelayanan terbaik. Cocok untuk berbagai kegiatan olahraga dan rekreasi.' }}
                    </p>
                </div>

                <!-- Fasilitas -->
                @if(!empty($facilities))
                <div class="facilities-section">
                    <h3 class="section-title">
                        <i class="fas fa-list"></i>
                        Fasilitas Venue
                    </h3>
                    <div class="facilities-grid">
                        @foreach($facilities as $facility)
                        <div class="facility-item">
                            <i class="{{ $facility['icon'] }}"></i>
                            <span>{{ $facility['name'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Ulasan -->
                @if($reviews->count() > 0)
                <div class="reviews-section">
                    <div class="reviews-header">
                        <h3 class="reviews-section-title">Ulasan ({{ $reviewsCount }})</h3>
                        <a href="{{ route('pesan.ulasan', ['id' => $venue->id]) }}" class="see-all-btn">
                            Lihat Semua
                        </a>
                    </div>
                    
                    <div class="reviews-grid">
                        @foreach($reviews as $review)
                        <div class="review-box">
                            <div class="review-header">
                                <div class="profile-icon">{{ $review->initials }}</div>
                                <div class="reviewer-name">{{ $review->customer_name }}</div>
                            </div>
                            <div class="review-stars">
                                @php
                                    $rating = $review->rating ?? 5;
                                    $fullStars = floor($rating);
                                    $emptyStars = 5 - $fullStars;
                                @endphp
                                @for($i = 0; $i < $fullStars; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @for($i = 0; $i < $emptyStars; $i++)
                                    <i class="far fa-star"></i>
                                @endfor
                            </div>
                            <div class="review-content">
                                {{ $review->comment }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="reviews-section">
                    <h3 class="section-title">
                        <i class="fas fa-comments"></i>
                        Ulasan
                    </h3>
                    <p class="description-text">Belum ada ulasan untuk venue ini.</p>
                </div>
                @endif

                <!-- Booking Section -->
                <div class="booking-section">
                    <div class="booking-info">
                        <div class="booking-label">Harga per jam</div>
                        <div class="booking-price">Rp {{ number_format($venue->price_per_hour, 0, ',', '.') }}</div>
                    </div>
                    <div class="booking-actions">
                        <a href="{{ route('pesan.index') }}" class="action-btn btn-back">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                        <a href="{{ route('pesan.booking', ['id' => $venue->id]) }}" class="action-btn btn-booking">
                            <i class="fas fa-calendar-check"></i>
                            Booking Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>