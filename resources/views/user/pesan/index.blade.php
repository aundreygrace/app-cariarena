@extends('layouts.user')
@section('title', 'Booking Venue')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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

    /* RESET BODY */
    body {
        margin: 0;
        padding: 0;
        background-color: #ffffff;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        min-height: 100vh;
    }

    /* ==== MAIN CONTAINER ==== */
    .main-container {
        width: 100%;
        min-height: 100vh;
        background-color: #ffffff;
    }

    /* ==== CONTENT WRAPPER ==== */
    .content-wrapper {
        max-width: 1600px;
        margin: 0 auto;
        padding: 40px 40px 80px 40px;
    }

    /* ==== HEADER SECTION ==== */
    .page-header {
        text-align: center;
        margin-bottom: 50px;
        padding-top: 20px;
    }

    .page-title {
        font-size: 42px;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 20px;
        letter-spacing: 0.5px;
    }

    .page-subtitle {
        font-size: 20px;
        color: var(--text-light);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
        font-weight: 400;
    }

    /* ==== SPORT ICONS SECTION ==== */
    .sports-icons {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin: 50px 0 60px 0;
        flex-wrap: wrap;
    }

    .sport-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 20px 30px;
        border-radius: 20px;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        min-width: 140px;
    }

    .sport-item:hover {
        transform: translateY(-10px);
        background: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }

    .sport-item.active {
        background: white;
        border-color: var(--primary-color);
        box-shadow: 0 15px 40px rgba(98, 147, 196, 0.2);
    }

    .sport-icon {
        width: 60px;
        height: 60px;
        background: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        box-shadow: 0 6px 15px rgba(98, 147, 196, 0.2);
    }

    .sport-name {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-dark);
        text-align: center;
    }

    /* ==== FILTER TABS ==== */
    .filter-tabs {
        display: flex;
        gap: 15px;
        margin: 0 0 50px 0;
        flex-wrap: wrap;
        padding: 0;
    }

    .filter-tab {
        padding: 14px 28px;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
        background: white;
        color: var(--text-dark);
        font-size: 16px;
    }

    .filter-tab:hover {
        background: #f8fafc;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .filter-tab.active {
        background: var(--primary-color);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(98, 147, 196, 0.3);
        border-color: var(--primary-color);
    }

    /* ==== VENUE GRID ==== */
    .venue-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        margin-bottom: 60px;
    }

    .venue-card {
        background: white;
        border-radius: 20px;
        padding: 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.4s ease;
        animation: fadeInUp 0.6s ease-out;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: fit-content;
        position: relative;
    }

    .venue-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        border-color: var(--primary-color);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ==== VENUE HEADER ==== */
    .venue-header {
        position: relative;
    }

    .venue-image {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .venue-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .venue-card:hover .venue-image img {
        transform: scale(1.1);
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

    /* ==== PERBAIKAN: HAPUS BACKGROUND PUTIH PADA BADGE ==== */
    .badge {
        padding: 10px 18px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        /* HAPUS border: 2px solid white; */
        border: none;
    }

    .badge-category {
        background: var(--primary-color);
        color: white;
    }

    .badge-status {
        color: white;
    }

    .badge-available {
        background: var(--success);
    }

    .badge-maintenance {
        background: var(--warning);
    }

    .badge-inactive {
        background: var(--danger);
    }

    /* ==== VENUE CONTENT ==== */
    .venue-content {
        padding: 25px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .venue-info {
        margin-bottom: 20px;
    }

    .venue-name {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 15px 0;
        line-height: 1.4;
        height: 56px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .venue-location {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        color: var(--text-light);
        margin-bottom: 15px;
    }

    .venue-location i {
        width: 18px;
        color: var(--primary-color);
        font-size: 16px;
    }

    /* ==== VENUE DETAILS ==== */
    .venue-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .venue-rating {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .rating-stars {
        display: flex;
        gap: 3px;
        color: #fbbf24;
    }

    .rating-stars .fa-star {
        font-size: 16px;
    }

    .rating-text {
        font-size: 14px;
        color: var(--text-light);
        font-weight: 600;
    }

    .venue-price {
        text-align: right;
    }

    .price-text {
        font-weight: 800;
        color: var(--primary-color);
        font-size: 18px;
    }

    /* ==== ACTION BUTTONS ==== */
    .venue-action {
        margin-top: auto;
        display: flex;
        gap: 15px;
    }

    .action-btn {
        flex: 1;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 15px;
        border: 2px solid transparent;
        text-decoration: none;
        text-align: center;
    }

    .btn-booking {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .btn-booking:hover {
        background: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(98, 147, 196, 0.4);
    }

    /* ==== NO DATA STATE ==== */
    .no-data {
        text-align: center;
        padding: 80px 40px;
        color: var(--text-light);
        grid-column: 1 / -1;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        margin: 20px 0;
        border: 1px solid #e2e8f0;
    }

    .no-data i {
        font-size: 60px;
        margin-bottom: 25px;
        color: #cbd5e0;
    }

    .no-data h3 {
        font-size: 28px;
        margin-bottom: 15px;
        color: var(--text-dark);
    }

    .no-data p {
        font-size: 18px;
        max-width: 500px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ==== FOOTER NOTE ==== */
    .footer-note {
        text-align: center;
        color: var(--text-light);
        font-size: 16px;
        margin-top: 40px;
        padding: 20px;
        border-top: 1px solid #e2e8f0;
    }

    /* ==== RESPONSIVE DESIGN ==== */
    @media (max-width: 1440px) {
        .content-wrapper {
            max-width: 1200px;
            padding: 30px 30px 60px 30px;
        }
        
        .venue-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }
        
        .page-title {
            font-size: 38px;
        }
        
        .sports-icons {
            gap: 30px;
        }
    }

    @media (max-width: 1200px) {
        .sports-icons {
            gap: 25px;
        }
        
        .sport-item {
            min-width: 120px;
            padding: 15px 20px;
        }
    }

    @media (max-width: 1024px) {
        .venue-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .filter-tabs {
            gap: 12px;
        }
        
        .filter-tab {
            padding: 12px 24px;
        }
        
        .page-title {
            font-size: 34px;
        }
        
        .page-subtitle {
            font-size: 18px;
        }
        
        .sports-icons {
            gap: 20px;
            margin: 40px 0 50px 0;
        }
        
        .sport-item {
            min-width: 110px;
            padding: 12px 18px;
        }
        
        .sport-icon {
            width: 50px;
            height: 50px;
            font-size: 24px;
        }
    }

    @media (max-width: 768px) {
        .content-wrapper {
            padding: 20px 20px 50px 20px;
        }

        .page-title {
            font-size: 30px;
            margin-bottom: 15px;
        }

        .page-subtitle {
            font-size: 16px;
            padding: 0 10px;
        }

        .sports-icons {
            gap: 15px;
            margin: 30px 0 40px 0;
        }

        .sport-item {
            min-width: 100px;
            padding: 10px 15px;
        }

        .sport-icon {
            width: 45px;
            height: 45px;
            font-size: 22px;
        }

        .sport-name {
            font-size: 16px;
        }

        .filter-tabs {
            gap: 10px;
            margin: 0 0 40px 0;
        }

        .filter-tab {
            padding: 10px 20px;
            font-size: 15px;
        }

        .venue-grid {
            grid-template-columns: 1fr;
            gap: 25px;
            margin-bottom: 40px;
        }

        .venue-content {
            padding: 20px;
        }

        .venue-image {
            height: 200px;
        }

        .venue-action {
            flex-direction: column;
            gap: 12px;
        }

        .action-btn {
            width: 100%;
            padding: 12px;
        }
    }

    @media (max-width: 480px) {
        .content-wrapper {
            padding: 15px 15px 40px 15px;
        }

        .page-title {
            font-size: 26px;
            margin-bottom: 10px;
        }

        .page-subtitle {
            font-size: 15px;
        }

        .sports-icons {
            gap: 10px;
            margin: 25px 0 35px 0;
        }

        .sport-item {
            min-width: 85px;
            padding: 8px 12px;
        }

        .sport-icon {
            width: 40px;
            height: 40px;
            font-size: 20px;
        }

        .sport-name {
            font-size: 14px;
        }

        .filter-tabs {
            gap: 8px;
            margin: 0 0 35px 0;
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 10px;
            flex-wrap: nowrap;
        }

        .filter-tab {
            padding: 8px 16px;
            font-size: 14px;
            white-space: nowrap;
        }

        .venue-name {
            font-size: 18px;
            height: 50px;
        }

        .price-text {
            font-size: 16px;
        }

        .venue-image {
            height: 180px;
        }

        .venue-action {
            flex-direction: column;
        }

        .action-btn {
            padding: 10px;
            font-size: 14px;
        }

        .no-data {
            padding: 60px 20px;
        }

        .no-data i {
            font-size: 48px;
        }

        .no-data h3 {
            font-size: 22px;
        }
    }

    @media (max-width: 360px) {
        .venue-action {
            flex-direction: column;
        }
        
        .action-btn {
            width: 100%;
        }
        
        .content-wrapper {
            padding: 12px 12px 30px 12px;
        }
        
        .page-title {
            font-size: 24px;
        }
        
        .sports-icons {
            justify-content: space-around;
        }
        
        .sport-item {
            min-width: 75px;
            padding: 6px 8px;
        }
    }
</style>
@endsection

@section('content')
<div class="desktop-container">
    <div class="content-wrapper">

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab active" data-category="all">Semua Venue</button>
            <button class="filter-tab" data-category="Futsal">Futsal</button>
            <button class="filter-tab" data-category="Basket">Basket</button>
            <button class="filter-tab" data-category="Soccer">Soccer</button>
            <button class="filter-tab" data-category="Badminton">Badminton</button>
        </div>

        <!-- Venue Grid -->
        <div class="venue-grid" id="venue-grid">
            @if($venues->count() > 0)
                @foreach($venues as $venue)
                <div class="venue-card" data-category="{{ $venue->category }}">
                    <div class="venue-header">
                        <div class="venue-image">
                            <img src="{{ $venue->photo_url }}" 
                                 alt="{{ $venue->name }}" 
                                 onerror="this.src='{{ $venue->getDefaultPhotoUrl() }}'"
                                 loading="lazy">
                            <div class="venue-overlay">
                                <span class="badge badge-category">{{ $venue->category }}</span>
                                <span class="badge badge-status 
                                    @if($venue->status == 'Aktif') badge-available
                                    @elseif($venue->status == 'Maintenance') badge-maintenance
                                    @else badge-inactive @endif">
                                    {{ $venue->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="venue-content">
                        <div class="venue-info">
                            <h3 class="venue-name">{{ $venue->name }}</h3>
                            <div class="venue-location">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>{{ Str::limit($venue->address, 30) }}</span>
                            </div>
                        </div>

                        <div class="venue-details">
                            <div class="venue-rating">
                                <div class="rating-stars">
                                    @php
                                        $rating = $venue->rating ?? 0;
                                        $fullStars = floor($rating);
                                        $halfStar = ($rating - $fullStars) >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                    @endphp
                                    
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <i class="fa-solid fa-star"></i>
                                    @endfor
                                    
                                    @if($halfStar)
                                        <i class="fa-solid fa-star-half-alt"></i>
                                    @endif
                                    
                                    @for($i = 0; $i < $emptyStars; $i++)
                                        <i class="fa-regular fa-star"></i>
                                    @endfor
                                </div>
                                <span class="rating-text">{{ number_format($venue->rating, 1) }}/5.0</span>
                            </div>
                            
                            <div class="venue-price">
                                <span class="price-text">Rp {{ number_format($venue->price_per_hour, 0, ',', '.') }}/jam</span>
                            </div>
                        </div>

                        <div class="venue-action">
                            @if($venue->status == 'Aktif')
                                <a href="{{ route('pesan.pesan-sekarang', ['id' => $venue->id]) }}" class="action-btn btn-booking">
                                    <i class="fa-regular fa-calendar"></i>
                                    Pesan Sekarang
                                </a>
                            @else
                                <button class="action-btn btn-booking" disabled style="background: #9ca3af; border-color: #9ca3af; cursor: not-allowed;">
                                    <i class="fa-solid fa-clock"></i>
                                    {{ $venue->status == 'Maintenance' ? 'Maintenance' : 'Tidak Aktif' }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="no-data">
                    <i class="fa-solid fa-map-marker-alt"></i>
                    <h3>Belum ada venue tersedia</h3>
                    <p>Silakan coba lagi nanti atau hubungi administrator.</p>
                </div>
            @endif
        </div>

        <!-- Footer Note -->
        <div class="footer-note">
            <p>© 2024 Sports Venue Booking System. All rights reserved.</p>
        </div>
    </div>
</div>

<script>
// Filter functionality untuk kategori
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const sportItems = document.querySelectorAll('.sport-item');
    const venueCards = document.querySelectorAll('.venue-card');
    const venueGrid = document.getElementById('venue-grid');
    
    // Filter venues
    function filterVenues(category) {
        let visibleCount = 0;
        
        venueCards.forEach(card => {
            if (category === 'all' || card.getAttribute('data-category') === category) {
                card.style.display = 'flex';
                card.style.animation = 'fadeInUp 0.5s ease-out';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Update active tab
        filterTabs.forEach(tab => {
            tab.classList.toggle('active', tab.getAttribute('data-category') === category);
        });
        
        // Update active sport icon
        sportItems.forEach(item => {
            item.classList.toggle('active', item.getAttribute('data-category') === category);
        });

        // Handle no data message
        const existingNoData = venueGrid.querySelector('.no-data');
        if (visibleCount === 0) {
            if (!existingNoData) {
                const noDataElement = document.createElement('div');
                noDataElement.className = 'no-data';
                noDataElement.innerHTML = `
                    <i class="fa-solid fa-map-marker-alt"></i>
                    <h3>Tidak ada venue dalam kategori ini</h3>
                    <p>Silakan pilih kategori lain atau coba lagi nanti.</p>
                `;
                venueGrid.appendChild(noDataElement);
            }
        } else if (existingNoData && existingNoData.parentNode === venueGrid) {
            existingNoData.remove();
        }
    }
    
    // Add event listeners to filter tabs
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.getAttribute('data-category');
            filterVenues(filter);
        });
    });
    
    // Add event listeners to sport icons
    sportItems.forEach(item => {
        item.addEventListener('click', function() {
            const filter = this.getAttribute('data-category');
            filterVenues(filter);
        });
    });
    
    // Initialize with all venues
    filterVenues('all');
    
    // Add scroll animation for cards when they come into view
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeInUp 0.6s ease-out';
            }
        });
    }, observerOptions);
    
    venueCards.forEach(card => {
        observer.observe(card);
    });
});
</script>
@endsection