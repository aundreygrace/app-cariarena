@extends('layouts.user')
@section('title', 'Beranda')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    :root {
        --primary-color: #6293c4;
        --primary-hover: #4a7cb0;
        --primary-light: #E8F4FD;
        --text-dark: #1A202C;
        --text-light: #64748b;
        --bg-light: #f8fafc;
        --card-bg: #FFFFFF;
        --success: #1AC42E;
        --warning: #F59E0B;
        --danger: #FE2222;
        --info: #6293c4;
        --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        --card-radius: 16px;
    }

    .beranda-page {
        height: 100%;
        background: #f8fafc;
        min-height: 100vh;
        padding: 0 0;
        margin-top: -40px;
    }

    .page-content {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 0 40px;
        padding-top: 20px;
    }

    @media (max-width: 1440px) {
        .page-content {
            max-width: 1200px;
            padding: 0 30px;
            padding-top: 20px;
        }
    }

    @media (max-width: 768px) {
        .page-content {
            padding: 0 20px;
            padding-top: 15px;
        }
    }

    @media (max-width: 480px) {
        .page-content {
            padding: 0 16px;
            padding-top: 10px;
        }
    }

    .welcome-message {
        background: linear-gradient(135deg, #E8F4FD 0%, #D4E7FA 100%);
        color: #2D3748;
        padding: 32px;
        border-radius: 16px;
        margin-bottom: 25px;
        margin-top: 20px;
        box-shadow: 0 4px 15px rgba(98, 147, 196, 0.15);
        border: 1px solid #BBDEFB;
        position: relative;
        overflow: hidden;
        width: 100%;
    }

    .welcome-message h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #1A365D;
        position: relative;
        z-index: 1;
    }

    .welcome-message p {
        opacity: 0.9;
        margin-bottom: 0;
        font-size: 17px;
        line-height: 1.5;
        color: #4A5568;
        position: relative;
        z-index: 1;
        font-weight: 500;
    }

    .section-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
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

    .lihat-semua-btn i {
        margin-left: 5px;
        font-size: 0.8rem;
        transition: transform 0.3s ease;
    }

    .lihat-semua-btn:hover i {
        transform: translateX(3px);
    }

    .category-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding: 12px 4px 16px 4px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        scroll-padding: 0 16px;
    }

    .category-buttons::-webkit-scrollbar {
        display: none;
    }

    .category-btn {
        padding: 12px 20px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        border: 2px solid #e2e8f0;
        background: white;
        color: var(--text-light);
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        flex-shrink: 0;
        min-width: max-content;
    }

    .category-btn:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-2px);
    }

    .category-btn.active {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(98, 147, 196, 0.3);
    }

    .venue-section {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: var(--card-shadow);
        border: 1px solid #e2e8f0;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 10px;
    }

    .section-header-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .venue-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        width: 100%;
    }

    .venue-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        overflow: hidden;
        min-height: 380px;
        display: flex;
        flex-direction: column;
    }

    .venue-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }

    .venue-image {
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .venue-image.no-image {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
    }

    .venue-icon {
        font-size: 2.5rem;
        color: white;
        opacity: 0.9;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .venue-badges {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .category-badge {
        background: rgba(255, 255, 255, 0.95);
        color: var(--primary-color);
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 700;
        border: 1px solid var(--primary-color);
    }

    .popular-badge {
        background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
        color: white;
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 700;
    }

    .venue-content {
        padding: 15px;
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    .venue-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 8px;
        line-height: 1.3;
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        flex-shrink: 0;
    }

    .venue-location {
        display: flex;
        align-items: flex-start;
        gap: 6px;
        margin-bottom: 12px;
        color: var(--text-light);
        font-size: 13px;
        min-height: 40px;
        flex-shrink: 0;
    }

    .venue-location i {
        color: var(--primary-color);
        margin-top: 2px;
        font-size: 13px;
        flex-shrink: 0;
        min-width: 12px;
    }

    .venue-location span {
        display: block;
        line-height: 1.4;
        overflow: visible;
        white-space: normal;
        word-wrap: break-word;
        flex: 1;
    }

    .venue-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding: 10px 0;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        flex-shrink: 0;
        min-height: 50px;
    }

    .venue-rating {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex: 1;
        min-width: 0;
    }

    .rating-stars {
        color: var(--warning);
        display: flex;
        gap: 2px;
        flex-shrink: 0;
    }

    .rating-stars i {
        font-size: 14px;
    }

    .rating-info {
        display: flex;
        align-items: center;
        gap: 4px;
        min-width: 0;
    }

    .rating-text {
        font-size: 12px;
        color: var(--text-light);
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex-shrink: 0;
    }

    .venue-price {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        flex-shrink: 0;
        min-width: 90px;
    }

    .price-text {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-color);
        white-space: nowrap;
        text-align: right;
    }

    .price-label {
        font-size: 11px;
        color: var(--text-light);
        white-space: nowrap;
    }

    .venue-action {
        margin-top: auto;
        flex-shrink: 0;
        min-height: 38px;
        display: flex;
        align-items: flex-end;
    }

    .booking-btn {
        width: 100%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 12px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 38px;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
        flex-shrink: 0;
    }

    .booking-btn:hover {
        background: linear-gradient(135deg, var(--primary-hover) 0%, #3a6ea5 100%);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(98, 147, 196, 0.3);
    }

    .booking-btn i {
        font-size: 12px;
        flex-shrink: 0;
    }

    .booking-btn span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .notification-section {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: var(--card-shadow);
        border: 1px solid #e2e8f0;
    }

    .notification-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .notification-item {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 15px;
        border-radius: 10px;
        border-left: 3px solid var(--primary-color);
        transition: all 0.3s ease;
    }

    .notification-item:hover {
        transform: translateX(3px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        flex-wrap: wrap;
        gap: 5px;
    }

    .notification-title {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 14px;
    }

    .notification-time {
        color: var(--text-light);
        font-size: 12px;
        font-weight: 500;
    }

    .notification-content {
        color: var(--text-light);
        font-size: 13px;
        line-height: 1.4;
    }

    .facilities-section {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        border: 1px solid #e2e8f0;
    }

    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 15px;
        margin-top: 15px;
    }

    .facility-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 20px 12px;
        background: white;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .facility-item:hover {
        transform: translateY(-3px);
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(98, 147, 196, 0.1);
    }

    .facility-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
    }

    .facility-icon i {
        color: white;
        font-size: 16px;
    }

    .facility-name {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 13px;
        line-height: 1.2;
    }

    .section-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, #e2e8f0 50%, transparent 100%);
        margin: 25px 0;
        border: none;
    }

    .fade-in-up {
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

    .empty-venues {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-light);
        grid-column: 1 / -1;
    }

    .empty-icon {
        font-size: 48px;
        color: #cbd5e0;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .empty-description {
        font-size: 14px;
        color: var(--text-light);
        line-height: 1.5;
        max-width: 400px;
        margin: 0 auto;
    }

    @media (max-width: 1199px) {
        .venue-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .facilities-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 991px) {
        .venue-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .facilities-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .welcome-message {
            padding: 28px;
        }
        
        .welcome-message h2 {
            font-size: 26px;
        }
        
        .welcome-message p {
            font-size: 16px;
        }
    }

    @media (max-width: 767px) {
        .page-content {
            padding: 0 20px;
            padding-top: 15px;
        }
        
        .welcome-message {
            padding: 20px;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        
        .welcome-message h2 {
            font-size: 22px;
        }
        
        .welcome-message p {
            font-size: 15px;
        }
        
        .category-section,
        .venue-section,
        .notification-section,
        .facilities-section {
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .section-title,
        .section-header-title {
            font-size: 20px;
        }
        
        .category-buttons {
            gap: 8px;
            padding: 10px 2px 14px 2px;
            margin: 0 -4px;
        }
        
        .category-btn {
            padding: 10px 16px;
            font-size: 13px;
            flex-shrink: 0;
            min-width: max-content;
        }
        
        .venue-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        
        .venue-card {
            min-height: auto;
        }
        
        .venue-image {
            height: 100px;
        }
        
        .venue-content {
            padding: 12px;
        }
        
        .venue-name {
            font-size: 16px;
            height: auto;
            min-height: 40px;
        }
        
        .venue-location {
            font-size: 12px;
            min-height: 35px;
        }
        
        .venue-details {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            min-height: auto;
        }
        
        .venue-price {
            align-items: flex-start;
        }
        
        .price-text {
            font-size: 13px;
        }
        
        .facilities-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .facility-item {
            padding: 15px 8px;
        }
        
        .facility-icon {
            width: 36px;
            height: 36px;
            margin-bottom: 6px;
        }
        
        .facility-icon i {
            font-size: 14px;
        }
        
        .facility-name {
            font-size: 12px;
        }
        
        .notification-item {
            padding: 12px;
        }
        
        .notification-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }
        
        .notification-title {
            font-size: 13px;
        }
        
        .notification-time {
            font-size: 11px;
        }
        
        .notification-content {
            font-size: 12px;
        }
        
        .section-divider {
            margin: 20px 0;
        }
        
        .booking-btn {
            padding: 10px;
            font-size: 12px;
        }
        
        .lihat-semua-btn {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        .rating-stars i {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .page-content {
            padding: 0 16px;
            padding-top: 10px;
        }
        
        .welcome-message {
            padding: 16px;
        }
        
        .welcome-message h2 {
            font-size: 20px;
        }
        
        .welcome-message p {
            font-size: 14px;
        }
        
        .category-section,
        .venue-section,
        .notification-section,
        .facilities-section {
            padding: 16px;
        }
        
        .section-title,
        .section-header-title {
            font-size: 18px;
        }
        
        .category-buttons {
            gap: 6px;
            padding: 8px 1px 12px 1px;
        }
        
        .category-btn {
            padding: 8px 14px;
            font-size: 12px;
        }
        
        .venue-grid {
            gap: 12px;
        }
        
        .venue-image {
            height: 90px;
        }
        
        .venue-content {
            padding: 10px;
        }
        
        .venue-name {
            font-size: 15px;
        }
        
        .venue-location {
            font-size: 11px;
        }
        
        .facilities-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .facility-item {
            padding: 12px 6px;
        }
        
        .facility-icon {
            width: 32px;
            height: 32px;
        }
        
        .facility-icon i {
            font-size: 13px;
        }
        
        .facility-name {
            font-size: 11px;
        }
        
        .notification-item {
            padding: 10px;
        }
        
        .lihat-semua-btn {
            padding: 5px 10px;
            font-size: 0.75rem;
        }

        .rating-stars i {
            font-size: 11px;
        }
    }

    @media (max-width: 360px) {
        .page-content {
            padding: 0 12px;
            padding-top: 8px;
        }
        
        .welcome-message {
            padding: 14px;
        }
        
        .category-section,
        .venue-section,
        .notification-section,
        .facilities-section {
            padding: 14px;
        }
        
        .category-buttons {
            gap: 4px;
            padding: 6px 0 10px 0;
        }
        
        .category-btn {
            padding: 6px 12px;
            font-size: 11px;
        }
        
        .facilities-grid {
            grid-template-columns: 1fr;
        }
        
        .venue-details {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }
        
        .venue-price {
            align-items: flex-start;
        }
        
        .lihat-semua-btn {
            padding: 4px 8px;
            font-size: 0.7rem;
        }

        .rating-stars i {
            font-size: 10px;
        }
    }

    @media (min-width: 768px) {
        .welcome-message {
            margin-top: 40px;
        }
    }

    @media (min-width: 992px) {
        .welcome-message {
            margin-top: 45px;
        }
    }

    @media (min-width: 1200px) {
        .welcome-message {
            margin-top: 50px;
        }
    }

    @media (min-width: 1600px) {
        .welcome-message {
            margin-top: 60px;
        }
    }
</style>

<div class="beranda-page">
    <div class="page-content">
        
        <div class="welcome-message fade-in-up">
            <h2>Selamat Datang, {{ Auth::user()->name ?? 'User' }}!</h2>
            <p>Siap berolahraga hari ini? Temukan venue terbaik untuk aktivitas olahraga Anda.</p>
        </div>

        <div class="category-section fade-in-up">
            <h2 class="section-title">Kategori Olahraga</h2>
            <div class="category-buttons">
                <button class="category-btn active" data-category="all">Semua</button>
                @php
                    $categories = \App\Models\Venue::where('status', 'Aktif')->distinct()->pluck('category')->filter();
                @endphp
                @foreach($categories as $category)
                    @if($category)
                    <button class="category-btn" data-category="{{ $category }}">{{ $category }}</button>
                    @endif
                @endforeach
            </div>
        </div>

        <hr class="section-divider">

        <div class="venue-section fade-in-up">
            <div class="section-header">
                <h2 class="section-header-title">Venue Populer</h2>
                <a href="{{ route('pesan.index') }}" class="lihat-semua-btn">
                    Lihat Semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="venue-grid" id="venue-container">
                @php
                    $popularVenues = \App\Models\Venue::where('status', 'Aktif')
                        ->where('rating', '>=', 4.0)
                        ->orderBy('rating', 'desc')
                        ->orderBy('reviews_count', 'desc')
                        ->limit(4)
                        ->get();
                @endphp

                @foreach($popularVenues as $venue)
                <div class="venue-card venue-item" data-category="{{ $venue->category }}">
                    <div class="venue-image @if(empty($venue->photo)) no-image @endif" 
                         style="@if($venue->photo) background-image: url('{{ asset('storage/' . $venue->photo) }}'); @endif">
                        
                        @if(empty($venue->photo))
                        <div class="venue-icon">
                            @if($venue->category == 'Futsal')
                                ⚽
                            @elseif($venue->category == 'Badminton')
                                🏸
                            @elseif($venue->category == 'Basket')
                                🏀
                            @elseif($venue->category == 'Soccer')
                                ⚽
                            @else
                                🏟
                            @endif
                        </div>
                        @endif
                        
                        <div class="venue-badges">
                            <span class="category-badge">{{ strtoupper($venue->category) }}</span>
                            @if($venue->rating >= 4.5)
                            <span class="popular-badge">⭐ POPULER</span>
                            @endif
                        </div>
                    </div>
                    <div class="venue-content">
                        <h3 class="venue-name">{{ $venue->name }}</h3>
                        <div class="venue-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $venue->address }}</span>
                        </div>
                        <div class="venue-details">
                            <div class="venue-rating">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($venue->rating))
                                            <i class="fas fa-star"></i>
                                        @elseif($i == ceil($venue->rating) && fmod($venue->rating, 1) >= 0.5)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="rating-info">
                                    <span class="rating-text">{{ number_format($venue->rating, 1) }}/5.0 ({{ $venue->reviews_count }})</span>
                                </div>
                            </div>
                            <div class="venue-price">
                                @if($venue->price_per_hour)
                                <span class="price-text">Rp {{ number_format($venue->price_per_hour, 0, ',', '.') }}</span>
                                <span class="price-label">per jam</span>
                                @else
                                <span class="price-text">-</span>
                                <span class="price-label">hubungi venue</span>
                                @endif
                            </div>
                        </div>
                        <div class="venue-action">
                            <a href="{{ route('pesan.pesan-sekarang', ['id' => $venue->id]) }}" class="booking-btn">
                                <i class="far fa-calendar"></i>
                                <span>Pesan Sekarang</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($popularVenues->count() == 0)
                <div class="empty-venues">
                    <div class="empty-icon">
                        <i class="fas fa-search-location"></i>
                    </div>
                    <h3 class="empty-title">Tidak ada venue populer</h3>
                    <p class="empty-description">
                        Saat ini belum ada venue yang tersedia. Silakan coba lagi nanti.
                    </p>
                </div>
                @endif
            </div>
        </div>

        <div class="notification-section fade-in-up">
            <div class="section-header">
                <h2 class="section-header-title">Notifikasi Terbaru</h2>
                <a href="{{ route('notifikasi.index') }}" class="lihat-semua-btn">
                    Lihat Semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="notification-list">
                @php
                    try {
                        $recentNotifications = \App\Models\Notifikasi::where('user_id', Auth::id())
                            ->where('is_read', false)
                            ->orderBy('created_at', 'desc')
                            ->limit(3)
                            ->get();
                    } catch (Exception $e) {
                        $recentNotifications = collect();
                    }
                @endphp

                @if($recentNotifications && $recentNotifications->count() > 0)
                    @foreach($recentNotifications as $notification)
                    <div class="notification-item">
                        <div class="notification-header">
                            <span class="notification-title">{{ $notification->title }}</span>
                            <span class="notification-time">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                        </div>
                        <div class="notification-content">
                            {{ $notification->message }}
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="notification-item">
                    <div class="notification-header">
                        <span class="notification-title">Selamat Datang di CariArena!</span>
                        <span class="notification-time">Baru saja</span>
                    </div>
                    <div class="notification-content">
                        Tidak ada notifikasi baru. Mulai jelajahi venue olahraga terbaik di sekitar Anda.
                    </div>
                </div>
                @endif
            </div>
        </div>

        <hr class="section-divider">

        <div class="facilities-section fade-in-up">
            <h2 class="section-title">Fasilitas Tersedia</h2>
            <div class="facilities-grid">
                @php
                    $commonFacilities = ['Parkir', 'Kamar Mandi', 'Ruang Ganti', 'Musholla', 'Ruang Tunggu', 'Kantin'];
                @endphp
                
                @foreach($commonFacilities as $facility)
                <div class="facility-item">
                    <div class="facility-icon">
                        @if($facility == 'Parkir')
                            <i class="fas fa-parking"></i>
                        @elseif($facility == 'Kamar Mandi')
                            <i class="fas fa-toilet"></i>
                        @elseif($facility == 'Ruang Ganti')
                            <i class="fas fa-tshirt"></i>
                        @elseif($facility == 'Musholla')
                            <i class="fas fa-mosque"></i>
                        @elseif($facility == 'Ruang Tunggu')
                            <i class="fas fa-couch"></i>
                        @elseif($facility == 'Kantin')
                            <i class="fas fa-utensils"></i>
                        @endif
                    </div>
                    <span class="facility-name">{{ $facility }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const categoryButtons = document.querySelectorAll('.category-btn');
    const venueItems = document.querySelectorAll('.venue-item');
    
    function filterVenues() {
        const activeCategory = document.querySelector('.category-btn.active').getAttribute('data-category');
        
        venueItems.forEach((item) => {
            const category = item.getAttribute('data-category');
            
            if (activeCategory === 'all' || category === activeCategory) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            categoryButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            
            this.classList.add('active');
            filterVenues();
        });
    });
    
    filterVenues();

    const venueCards = document.querySelectorAll('.venue-card');
    venueCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'var(--card-shadow)';
        });
    });

    const lihatSemuaButtons = document.querySelectorAll('.lihat-semua-btn');
    lihatSemuaButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.category-section, .venue-section, .notification-section, .facilities-section').forEach(section => {
        observer.observe(section);
    });
});
</script>
@endsection