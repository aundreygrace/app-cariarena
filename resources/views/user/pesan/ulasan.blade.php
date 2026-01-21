@extends('layouts.user')
@section('title', 'Ulasan')
@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan {{ $venue->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #F6F9FB;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            padding-bottom: 80px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 3px 20px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            z-index: 10;
            transition: transform 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }
        
        .back-button:hover {
            transform: translateX(-5px);
        }
        
        /* Header baru sesuai permintaan */
        .header-booking {
            background-color: #2C8BC1;
            color: white;
            padding: 40px 20px;
            border-bottom-left-radius: 3rem;
            border-bottom-right-radius: 3rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            margin-bottom: 30px;
        }
        
        .header-content {
            max-width: 64rem;
            margin: 0 auto;
            text-align: center;
        }
        
        .header-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .header-subtitle {
            font-size: 0.885rem;
            opacity: 0.9;
        }
        
        .venue-info {
            text-align: center;
            padding: 0 20px 20px;
        }
        
        .venue-name {
            font-size: 1.50rem;
            color: #467597;
            font-weight: 600;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .rating-summary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .average-rating {
            font-size: 1.5rem;
            font-weight: bold;
            color: #F59E0B;
        }
        
        .total-reviews {
            color: #6B7280;
        }
        
        .reviews-container {
            padding: 20px 20px 20px 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .review {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: white;
        }
        
        .review:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        
        .review-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .profile-icon {
            background-color: #2E86AB;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 600;
            margin-right: 12px;
            flex-shrink: 0;
        }
        
        .reviewer-info {
            flex-grow: 1;
        }
        
        .reviewer-name {
            font-weight: bold;
            color: #467597;
            margin-bottom: 3px;
        }
        
        .rating {
            color: #FFD700;
        }
        
        .rating .star {
            margin-right: 1px;
            font-size: 14px;
        }
        
        .review-text {
            color: #333;
            font-size: 15px;
            line-height: 1.5;
        }
        
        .review-date {
            color: #6B7280;
            font-size: 12px;
            margin-top: 8px;
        }
        
        @media (max-width: 992px) {
            .reviews-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 600px) {
            .container {
                border-radius: 0;
            }
            
            .reviews-container {
                grid-template-columns: 1fr;
                padding: 0 15px 15px 15px;
            }
            
            .review-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-icon {
                margin-right: 0;
                margin-bottom: 8px;
            }
            
            .header-booking {
                padding: 30px 15px;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="desktop-layout">
        <a href="{{ route('pesan.pesan-sekarang', ['id' => $venue->id]) }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        
    <!-- Container ulasan -->
        <div class="reviews-container">
            @foreach($reviews as $review)
            <div class="review">
                <div class="review-header">
                    <div class="profile-icon">
                        {{ $review->initials }}
                    </div>
                    <div class="reviewer-info">
                        <div class="reviewer-name">{{ $review->customer_name }}</div>
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <span class="star"><i class="fas fa-star"></i></span>
                                @else
                                    <span class="star"><i class="far fa-star"></i></span>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="review-text">
                    {{ $review->comment }}
                </div>
                <div class="review-date">
                    {{ $review->created_at->format('d M Y') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <script>
    </script>
</body>
</html>
@endsection