@extends('layouts.venue')

@section('title', 'Ulasan')
@section('page-title', 'Ulasan')

@push('styles')
<style>
    /* ==== STYLE UNTUK KONTEN ULASAN ==== */
    .content h2 {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 24px;
    }

    /* ==== CARD STATS ==== */
    .cards {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-bottom: 25px;
    }

    .card {
      background: var(--card-bg);
      border-radius: 14px;
      padding: 18px 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      position: relative;
      overflow: hidden;
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
    }

    .card h3 {
      margin: 10px 0;
      font-size: 26px;
      font-weight: 700;
    }

    /* FILTER CONTAINER */
    .filter-container {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 15px;
      margin-bottom: 30px;
    }

    .filter-label {
      font-weight: 600;
      color: var(--text-dark);
      font-size: 14px;
      margin: 0;
    }

    .filter-select {
      padding: 10px 15px;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
      background: var(--card-bg);
      font-size: 14px;
      outline: none;
      min-width: 180px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .filter-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(99, 179, 237, 0.2);
    }

    /* REVIEWS GRID */
    .review-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
    }

    .review-card {
      background: var(--card-bg);
      border-radius: 12px;
      padding: 16px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .review-card:hover {
      transform: scale(1.03);
      box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    .review-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 6px;
    }

    .review-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      color: white;
      font-weight: bold;
    }

    .avatar-red { background-color: #EF4444; }
    .avatar-blue { background-color: #3B82F6; }
    .avatar-green { background-color: #10B981; }
    .avatar-yellow { background-color: #F59E0B; }
    .avatar-purple { background-color: #8B5CF6; }
    .avatar-pink { background-color: #EC4899; }
    .avatar-indigo { background-color: #6366F1; }
    .avatar-teal { background-color: #14B8A6; }
    .avatar-orange { background-color: #F97316; }
    .avatar-cyan { background-color: #06B6D4; }

    .review-name {
      font-weight: 600;
      font-size: 14px;
    }

    .review-time {
      font-size: 12px;
      color: var(--text-light);
    }

    .review-stars {
      color: #fbbf24;
      margin-bottom: 8px;
    }

    .review-text {
      font-size: 14px;
      color: var(--text-dark);
      margin-bottom: 10px;
    }

    .review-footer {
      font-size: 13px;
      color: #3b82f6;
      background-color: #f3f4f6;
      display: inline-block;
      padding: 4px 10px;
      border-radius: 6px;
    }

    /* ==== RESPONSIVE STYLES ==== */

    /* Tablet Landscape (992px - 1200px) */
    @media (max-width: 1200px) {
        .cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .review-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 15px;
        }
    }

    /* Tablet Portrait (768px - 992px) */
    @media (max-width: 992px) {
        .content h2 {
            font-size: 22px;
            margin-bottom: 20px;
        }
        
        .cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .card {
            padding: 15px;
            border-radius: 12px;
        }
        
        .card h3 {
            font-size: 22px;
            margin: 8px 0;
        }
        
        .card small {
            font-size: 12px;
        }
        
        .filter-container {
            margin-bottom: 25px;
            gap: 12px;
        }
        
        .filter-select {
            min-width: 160px;
            padding: 9px 12px;
            font-size: 13px;
        }
        
        .review-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .review-card {
            padding: 14px;
        }
    }

    /* Mobile Landscape (576px - 768px) */
    @media (max-width: 768px) {
        .content {
            padding: 10px;
        }
        
        .content h2 {
            font-size: 20px;
            margin-bottom: 18px;
        }
        
        .cards {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 18px;
        }
        
        .card {
            padding: 12px;
            border-radius: 10px;
        }
        
        .card h3 {
            font-size: 20px;
            margin: 6px 0;
        }
        
        .card small {
            font-size: 11px;
        }
        
        .filter-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .filter-label {
            font-size: 13px;
        }
        
        .filter-select {
            width: 100%;
            min-width: auto;
            padding: 10px 12px;
            font-size: 14px;
        }
        
        .review-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .review-card {
            padding: 12px;
        }
        
        .review-header {
            gap: 8px;
            margin-bottom: 5px;
        }
        
        .review-avatar {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }
        
        .review-name {
            font-size: 13px;
        }
        
        .review-time {
            font-size: 11px;
        }
        
        .review-stars {
            font-size: 14px;
            margin-bottom: 6px;
        }
        
        .review-text {
            font-size: 13px;
            margin-bottom: 8px;
        }
        
        .review-footer {
            font-size: 12px;
            padding: 3px 8px;
        }
    }

    /* Mobile Portrait (max-width: 576px) */
    @media (max-width: 576px) {
        .content h2 {
            font-size: 18px;
            margin-bottom: 15px;
        }
        
        .cards {
            grid-template-columns: 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .card {
            padding: 12px 15px;
            border-radius: 8px;
        }
        
        .card h3 {
            font-size: 18px;
            margin: 5px 0;
        }
        
        .filter-container {
            gap: 8px;
            margin-bottom: 18px;
        }
        
        .filter-label {
            font-size: 12px;
        }
        
        .filter-select {
            padding: 8px 10px;
            font-size: 13px;
            border-radius: 6px;
        }
        
        .review-grid {
            gap: 10px;
        }
        
        .review-card {
            padding: 10px;
            border-radius: 8px;
        }
        
        .review-card:hover {
            transform: none; /* Disable hover effect on mobile */
        }
        
        .review-header {
            gap: 6px;
        }
        
        .review-avatar {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }
        
        .review-name {
            font-size: 12px;
        }
        
        .review-time {
            font-size: 10px;
        }
        
        .review-stars {
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .review-text {
            font-size: 12px;
            margin-bottom: 6px;
            line-height: 1.4;
        }
        
        .review-footer {
            font-size: 11px;
            padding: 2px 6px;
        }
    }

    /* Very Small Mobile (max-width: 375px) */
    @media (max-width: 375px) {
        .content {
            padding: 8px;
        }
        
        .content h2 {
            font-size: 16px;
            margin-bottom: 12px;
        }
        
        .cards {
            gap: 8px;
            margin-bottom: 12px;
        }
        
        .card {
            padding: 10px 12px;
        }
        
        .card h3 {
            font-size: 16px;
        }
        
        .card small {
            font-size: 10px;
        }
        
        .filter-container {
            margin-bottom: 15px;
        }
        
        .filter-select {
            padding: 6px 8px;
            font-size: 12px;
        }
        
        .review-grid {
            gap: 8px;
        }
        
        .review-card {
            padding: 8px;
        }
        
        .review-avatar {
            width: 24px;
            height: 24px;
            font-size: 11px;
        }
        
        .review-name {
            font-size: 11px;
        }
        
        .review-time {
            font-size: 9px;
        }
        
        .review-stars {
            font-size: 11px;
        }
        
        .review-text {
            font-size: 11px;
        }
        
        .review-footer {
            font-size: 10px;
        }
    }

    /* Touch Device Optimizations */
    @media (max-width: 768px) {
        .filter-select {
            min-height: 44px; /* Better touch target */
        }
        
        .review-card {
            min-height: 44px; /* Better touch target for cards */
        }
    }

    /* Improve readability on small screens */
    @media (max-width: 576px) {
        .review-text {
            line-height: 1.5;
        }
        
        .card small {
            line-height: 1.3;
        }
    }
</style>
@endpush

@section('content')
<section class="content">
    <!-- Tambahkan alert untuk error -->
    @if(isset($error))
    <div class="alert alert-warning">
        <strong>Peringatan:</strong> {{ $error }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- STAT CARDS -->
    <section class="cards">
        <div class="card">
            <small>Rating Rata-rata</small>
            <h3 id="average-rating">{{ number_format($statistics['average_rating'], 1) }}</h3>
            <small>Dari <span id="total-reviews">{{ $statistics['total_reviews'] }}</span> ulasan</small>
        </div>
        <div class="card">
            <small>Total Ulasan</small>
            <h3 id="total-reviews-count">{{ $statistics['total_reviews'] }}</h3>
            <small>Total ulasan bulan ini</small>
        </div>
        <div class="card">
            <small>Ulasan 5 Bintang</small>
            <h3 id="five-star-count">{{ $statistics['five_star_count'] }}</h3>
            <small><span id="five-star-percent">{{ $statistics['five_star_percent'] }}</span>% dari total</small>
        </div>
        <div class="card">
            <small>Respons</small>
            <h3 id="response-rate">{{ $statistics['response_rate'] }}%</h3>
            <small>Tingkat respons</small>
        </div>
    </section>

    <!-- FILTERS -->
    <div class="filter-container">
        <span class="filter-label">Filter Ulasan:</span>
        <select id="venue-filter" class="filter-select">
            <option value="all">Semua Venue</option>
            @foreach($venues as $venue)
                <option value="{{ $venue->id }}">{{ $venue->name }}</option>
            @endforeach
        </select>
        
        <select id="rating-filter" class="filter-select">
            <option value="all">Semua Rating</option>
            <option value="5">5 Bintang</option>
            <option value="4">4 Bintang</option>
            <option value="3">3 Bintang</option>
            <option value="2">2 Bintang</option>
            <option value="1">1 Bintang</option>
        </select>
    </div>

    <!-- REVIEW GRID -->
    <div class="review-grid" id="review-container">
        @foreach($reviews as $review)
        <div class="review-card" data-venue="{{ $review->venue_id }}" data-rating="{{ $review->rating }}">
            <div class="review-header">
                <div class="review-avatar {{ $review->avatarClass }}">{{ $review->initials }}</div>
                <div>
                    <div class="review-name">{{ $review->customer_name }}</div>
                    <div class="review-time">{{ $review->created_at->diffForHumans() }}</div>
                </div>
            </div>
            <div class="review-stars">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->rating)
                        ★
                    @else
                        ☆
                    @endif
                @endfor
            </div>
            <div class="review-text">{{ $review->comment }}</div>
            
            @if($review->isReplied)
            <div class="reply-section" style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e5e7eb;">
                <div style="font-weight: 600; color: #3b82f6; margin-bottom: 5px;">Balasan:</div>
                <div style="font-size: 13px; color: #4b5563;">{{ $review->reply_message }}</div>
                <div style="font-size: 11px; color: #6b7280; margin-top: 5px;">
                    {{ $review->replied_at->diffForHumans() }}
                </div>
            </div>
            @endif
            
            <div class="review-footer">{{ $review->venue->name }}</div>
        </div>
        @endforeach
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Fungsi untuk mengenerate bintang
    function generateStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '★';
            } else {
                stars += '☆';
            }
        }
        return stars;
    }

    // Fungsi untuk filter ulasan
    function filterReviews() {
        const venueFilter = document.getElementById('venue-filter').value;
        const ratingFilter = document.getElementById('rating-filter').value;
        
        // Kirim request AJAX
        fetch('{{ route("venue.ulasan.filter") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                venue_id: venueFilter,
                rating: ratingFilter
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update review container
                const reviewContainer = document.getElementById('review-container');
                reviewContainer.innerHTML = '';
                
                data.reviews.forEach(review => {
                    const reviewCard = document.createElement('div');
                    reviewCard.className = 'review-card';
                    reviewCard.setAttribute('data-venue', review.venue_id);
                    reviewCard.setAttribute('data-rating', review.rating);

                    reviewCard.innerHTML = `
                        <div class="review-header">
                            <div class="review-avatar ${review.avatarClass}">${review.initials}</div>
                            <div>
                                <div class="review-name">${review.customer_name}</div>
                                <div class="review-time">${review.created_at}</div>
                            </div>
                        </div>
                        <div class="review-stars">${generateStars(review.rating)}</div>
                        <div class="review-text">${review.comment}</div>
                        ${review.reply_message ? `
                        <div class="reply-section" style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e5e7eb;">
                            <div style="font-weight: 600; color: #3b82f6; margin-bottom: 5px;">Balasan:</div>
                            <div style="font-size: 13px; color: #4b5563;">${review.reply_message}</div>
                            <div style="font-size: 11px; color: #6b7280; margin-top: 5px;">
                                ${review.replied_at}
                            </div>
                        </div>
                        ` : ''}
                        <div class="review-footer">${review.venue.name}</div>
                    `;

                    reviewContainer.appendChild(reviewCard);
                });

                // Update statistics
                document.getElementById('average-rating').textContent = data.statistics.average_rating;
                document.getElementById('total-reviews').textContent = data.statistics.total_reviews;
                document.getElementById('total-reviews-count').textContent = data.statistics.total_reviews;
                document.getElementById('five-star-count').textContent = data.statistics.five_star_count;
                document.getElementById('five-star-percent').textContent = data.statistics.five_star_percent;
                document.getElementById('response-rate').textContent = data.statistics.response_rate + '%';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Tambahkan event listener untuk filter
        document.getElementById('venue-filter').addEventListener('change', filterReviews);
        document.getElementById('rating-filter').addEventListener('change', filterReviews);
    });
</script>
@endpush

@push('scripts')
<script>
    // Fungsi untuk mengenerate bintang
    function generateStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '★';
            } else {
                stars += '☆';
            }
        }
        return stars;
    }

    // Fungsi untuk filter ulasan
    function filterReviews() {
        const venueFilter = document.getElementById('venue-filter').value;
        const ratingFilter = document.getElementById('rating-filter').value;
        
        const reviewCards = document.querySelectorAll('.review-card');
        
        reviewCards.forEach(card => {
            const venueId = card.getAttribute('data-venue');
            const rating = card.getAttribute('data-rating');
            
            let showCard = true;
            
            // Filter berdasarkan venue
            if (venueFilter !== 'all' && venueId !== venueFilter) {
                showCard = false;
            }
            
            // Filter berdasarkan rating
            if (ratingFilter !== 'all' && rating !== ratingFilter) {
                showCard = false;
            }
            
            // Tampilkan atau sembunyikan card
            card.style.display = showCard ? 'block' : 'none';
        });
    }

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Tambahkan event listener untuk filter
        document.getElementById('venue-filter').addEventListener('change', filterReviews);
        document.getElementById('rating-filter').addEventListener('change', filterReviews);
    });
</script>
@endpush