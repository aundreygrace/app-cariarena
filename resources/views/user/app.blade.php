<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CariArena')</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Variabel warna */
        :root {
            --primary-color: #6293c4ff;
            --primary-hover: #4a7cb0;
            --primary-light: #EBF8FF;
            --text-dark: #1A202C;
            --text-light: #718096;
            --bg-light: #EDF2F7;
            --card-bg: #FFFFFF;
            --success: #1AC42E;
            --danger: #FE2222;
            --white: #FFFFFF;
            --gray-100: #F7FAFC;
            --gray-200: #EDF2F7;
            --gray-500: #718096;
            --gray-700: #4A5568;
        }

        /* ========== FIX GLOBAL UNTUK BOLA VOLI ========== */
        .center-header {
            position: relative !important;
            width: 100% !important;
            text-align: center !important;
            display: block !important;
        }

        .volley-icon-main {
            float: none !important;
            position: relative !important;
            display: block !important;
            margin: 0 auto 1rem auto !important;
            text-align: center !important;
            left: 0 !important;
            right: 0 !important;
            font-size: 4rem !important;
            color: white !important;
            animation: float 3s ease-in-out infinite !important;
            text-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
        }

        .main-header .center-header .volley-icon-main,
        .hero-content .center-header .volley-icon-main,
        body .volley-icon-main {
            margin-left: auto !important;
            margin-right: auto !important;
            display: block !important;
        }

        .main-header * {
            float: none !important;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        /* ========== END FIX GLOBAL ========== */

        /* Reset dan base styles */
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--white);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: 'Segoe UI', sans-serif;
            /* Tambahkan padding bottom untuk memberi ruang footer */
            padding-bottom: 80px;
        }

        html {
            scroll-behavior: smooth;
        }

        /* Container utility */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .container-sm {
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Layout utilities */
        .flex {
            display: flex;
        }

        .flex-col {
            flex-direction: column;
        }

        .items-center {
            align-items: center;
        }

        .justify-center {
            justify-content: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .justify-around {
            justify-content: space-around;
        }

        .justify-end {
            justify-content: flex-end;
        }

        .flex-1 {
            flex: 1;
        }

        .text-center {
            text-align: center;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        /* Spacing utilities */
        .p-4 { padding: 1rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .pt-4 { padding-top: 1rem; }
        .pb-4 { padding-bottom: 1rem; }
        .pb-12 { padding-bottom: 3rem; }
        .pb-24 { padding-bottom: 6rem; }
        
        .m-0 { margin: 0; }
        .mb-3 { margin-bottom: 0.75rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mt-4 { margin-top: 1rem; }

        /* Typography utilities */
        .text-sm { font-size: 0.875rem; }
        .text-base { font-size: 1rem; }
        .text-lg { font-size: 1.125rem; }
        .text-xl { font-size: 1.25rem; }
        .text-2xl { font-size: 1.5rem; }
        .text-3xl { font-size: 1.875rem; }
        .text-4xl { font-size: 2.25rem; }

        .font-normal { font-weight: 400; }
        .font-medium { font-weight: 500; }
        .font-semibold { font-weight: 600; }
        .font-bold { font-weight: 700; }

        .text-white { color: var(--white); }
        .text-gray-500 { color: var(--gray-500); }
        .text-gray-700 { color: var(--gray-700); }
        .text-gray-900 { color: var(--text-dark); }
        .text-gray-100 { color: rgba(255, 255, 255, 0.9); }

        /* Background utilities */
        .bg-white { background-color: var(--white); }
        .bg-gray-100 { background-color: var(--gray-100); }
        .bg-gray-200 { background-color: var(--gray-200); }
        .bg-header { background: var(--bg-light); }

        /* Border utilities */
        .rounded-full { border-radius: 9999px; }
        .rounded-lg { border-radius: 0.5rem; }
        .rounded-xl { border-radius: 0.75rem; }
        .rounded-2xl { border-radius: 1rem; }
        .rounded-3xl { border-radius: 1.5rem; }
        .rounded-b-3xl { border-radius: 0 0 1.5rem 1.5rem; }

        /* Shadow utilities */
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
        .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }

        /* Width & Height utilities */
        .w-5 { width: 1.25rem; }
        .w-6 { width: 1.5rem; }
        .w-24 { width: 6rem; }
        .w-full { width: 100%; }
        .max-w-xl { max-width: 36rem; }
        .max-w-md { max-width: 28rem; }
        .max-w-7xl { max-width: 80rem; }
        
        .h-5 { height: 1.25rem; }
        .h-6 { height: 1.5rem; }
        .h-24 { height: 6rem; }
        .min-h-screen { min-height: 100vh; }

        /* Position utilities */
        .relative { position: relative; }
        .absolute { position: absolute; }
        .fixed { position: fixed; }
        .sticky { position: sticky; }
        
        .top-0 { top: 0; }
        .bottom-0 { bottom: 0; }
        .left-0 { left: 0; }
        .right-0 { right: 0; }
        
        .z-10 { z-index: 10; }
        .z-20 { z-index: 20; }
        .z-30 { z-index: 30; }
        .z-40 { z-index: 40; }
        .z-50 { z-index: 50; }
        .z-60 { z-index: 60; }

        /* Overflow utilities */
        .overflow-hidden { overflow: hidden; }
        .overflow-x-hidden { overflow-x: hidden; }

        /* Display utilities */
        .block { display: block; }
        .inline-block { display: inline-block; }
        .hidden { display: none; }

        /* Placeholder */
        .placeholder-gray-500::placeholder {
            color: var(--gray-500);
        }

        /* Focus utilities */
        .focus\:outline-none:focus {
            outline: none;
        }

        /* Animasi bola voli */
        .volleyball-icon {
            position: absolute;
            color: rgba(255, 255, 255, 0.7);
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
            animation: rollingBall 25s infinite linear;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .icon-1 { 
            font-size: 2.5rem; 
            top: 15%; 
            left: 8%; 
            animation-delay: 0s; 
            animation-duration: 25s;
        }
        
        .icon-2 { 
            font-size: 2rem; 
            bottom: 20%; 
            right: 10%; 
            animation-delay: -5s; 
            animation-duration: 22s;
        }
        
        .icon-3 { 
            font-size: 1.8rem; 
            top: 65%; 
            left: 12%; 
            animation-delay: -10s; 
            animation-duration: 28s;
        }
        
        .icon-4 { 
            font-size: 2.2rem; 
            bottom: 30%; 
            left: 10%; 
            animation-delay: -15s; 
            animation-duration: 24s;
        }
        
        .icon-5 { 
            font-size: 1.5rem; 
            top: 35%; 
            right: 15%; 
            animation-delay: -7s; 
            animation-duration: 26s;
        }

        @keyframes rollingBall {
            0% {
                transform: translate(0, 0) rotate(0deg);
                opacity: 0.7;
            }
            25% {
                transform: translate(80px, 60px) rotate(90deg);
                opacity: 0.9;
            }
            50% {
                transform: translate(160px, 0) rotate(180deg);
                opacity: 0.7;
            }
            75% {
                transform: translate(80px, -60px) rotate(270deg);
                opacity: 0.9;
            }
            100% {
                transform: translate(0, 0) rotate(360deg);
                opacity: 0.7;
            }
        }

        /* ==== STYLE UNTUK HEADER UTAMA ==== */
        .main-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            position: relative;
            z-index: 50;
        }

        .top-bar {
            position: relative;
            z-index: 60;
        }

        .search-section {
            position: relative;
            z-index: 55;
        }

        .center-header {
            margin-bottom: 2rem;
        }

        /* Animasi shimmer */
        .soft-shimmer {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                90deg,
                transparent 0%,
                rgba(255, 255, 255, 0.1) 20%,
                rgba(255, 255, 255, 0.3) 50%,
                rgba(255, 255, 255, 0.1) 80%,
                transparent 100%
            );
            transform: rotate(30deg);
            animation: shimmer 2.5s infinite linear;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(30deg);
            }
            100% {
                transform: translateX(100%) translateY(100%) rotate(30deg);
            }
        }

        /* Background utama */
        .bg-sky-light {
            background: linear-gradient(
                135deg,
                #e0f2ff 0%,
                #ebf8ff 25%,
                #f0f9ff 50%,
                #e6f2ff 75%,
                #e0f2ff 100%
            );
            background-size: 400% 400%;
            animation: backgroundFlow 15s ease-in-out infinite;
            min-height: 100vh;
            padding-bottom: 90px;
        }

        @keyframes backgroundFlow {
            0%, 100% {
                background-position: 0% 0%;
            }
            50% {
                background-position: 100% 100%;
            }
        }

        /* Fade in animation */
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

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        /* ========== STYLE FOOTER YANG DIPERBAIKI ========== */
        /* Animasi shimmer untuk menu aktif */
        @keyframes activeShimmer {
            0% {
                background-position: -200% center;
            }
            100% {
                background-position: 200% center;
            }
        }

        /* Animasi untuk garis indikator */
        @keyframes linePulse {
            0%, 100% {
                width: 20px;
                opacity: 1;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            }
            50% {
                width: 24px;
                opacity: 0.9;
                box-shadow: 0 2px 4px rgba(255, 255, 255, 0.4);
            }
        }

        /* Animasi ikon mengambang */
        @keyframes iconFloat {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-1px);
            }
        }

        @keyframes labelGlow {
            0%, 100% {
                text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            }
            50% {
                text-shadow: 0 1px 2px rgba(255, 255, 255, 0.5), 
                            0 0 6px rgba(255, 255, 255, 0.3);
            }
        }

        /* Navigasi footer - DIKECILKAN */
        .footer-floating {
            background: #FFFFFF;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 -8px 25px rgba(98, 147, 196, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.5),
                inset 0 0 20px rgba(98, 147, 196, 0.08);
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
            padding: 12px 10px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 50;
            height: 68px; /* Diperkecil dari sebelumnya */
        }

        .footer-menu {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px 6px; /* Diperkecil */
            border-radius: 12px; /* Diperkecil */
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            margin: 0 4px; /* Diperkecil */
            min-width: 56px; /* Diperkecil */
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: var(--text-light);
            cursor: pointer;
            text-decoration: none;
        }

        .footer-menu::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(98, 147, 196, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .footer-menu:hover {
            background: rgba(98, 147, 196, 0.1);
            border-color: rgba(98, 147, 196, 0.2);
            color: var(--primary-color);
            transform: translateY(-2px); /* Diperkecil */
            box-shadow: 
                0 4px 12px rgba(98, 147, 196, 0.1),
                inset 0 0 0 1px rgba(98, 147, 196, 0.1);
        }

        .footer-menu:hover::before {
            left: 100%;
        }

        /* Background gradient untuk menu aktif */
        .footer-menu.active {
            background: linear-gradient(
                135deg,
                var(--primary-color) 0%,
                var(--primary-hover) 25%,
                #5b8cc9 50%,
                var(--primary-hover) 75%,
                var(--primary-color) 100%
            );
            background-size: 200% 200%;
            color: white !important;
            box-shadow: 
                0 4px 15px rgba(98, 147, 196, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            animation: gentlePulse 3s infinite ease-in-out, 
                       gradientShift 3s infinite ease-in-out;
        }

        @keyframes gentlePulse {
            0%, 100% {
                box-shadow: 
                    0 4px 15px rgba(98, 147, 196, 0.4),
                    0 0 0 1px rgba(255, 255, 255, 0.15),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }
            50% {
                box-shadow: 
                    0 4px 18px rgba(98, 147, 196, 0.5),
                    0 0 0 1px rgba(255, 255, 255, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.25),
                    0 0 15px rgba(98, 147, 196, 0.15);
            }
        }

        @keyframes gradientShift {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        .footer-menu.active {
            position: relative;
            overflow: hidden;
        }

        /* Overlay shimmer untuk menu aktif */
        .footer-menu.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 200%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent 0%,
                rgba(255, 255, 255, 0.15) 25%,
                rgba(255, 255, 255, 0.3) 50%,
                rgba(255, 255, 255, 0.15) 75%,
                transparent 100%
            );
            animation: activeShimmer 3s infinite linear;
            z-index: 1;
            border-radius: inherit;
        }

        /* Garis indikator untuk menu aktif - DIKECILKAN */
        .footer-menu.active::after {
            content: '';
            position: absolute;
            bottom: 4px; /* Diperkecil */
            left: 50%;
            transform: translateX(-50%);
            width: 20px; /* Diperkecil */
            height: 2px; /* Diperkecil */
            background: linear-gradient(90deg, #ffffff 0%, rgba(255, 255, 255, 0.8) 100%);
            border-radius: 2px; /* Diperkecil */
            z-index: 2;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            animation: linePulse 2s infinite ease-in-out;
        }

        .footer-icon {
            width: 20px; /* Diperkecil */
            height: 20px; /* Diperkecil */
            margin-bottom: 4px; /* Diperkecil */
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .footer-label {
            font-size: 10px; /* Diperkecil */
            font-weight: 500;
            line-height: 1;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        /* Ikon untuk menu aktif */
        .footer-menu.active .footer-icon {
            color: white;
            animation: iconFloat 2s infinite ease-in-out;
            filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.2));
        }

        /* Label untuk menu aktif */
        .footer-menu.active .footer-label {
            font-weight: 600;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            animation: labelGlow 2s infinite ease-in-out;
        }

        /* Efek ripple untuk menu non-aktif */
        .footer-menu:not(.active):active {
            transform: scale(0.95);
            transition: transform 0.15s ease;
        }

        @keyframes quickFlash {
            0%, 100% {
                opacity: 0;
                transform: scale(0.95);
            }
            50% {
                opacity: 0.5;
                transform: scale(1.05);
            }
        }

        .footer-menu:not(.active):hover::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(
                circle at center,
                rgba(98, 147, 196, 0.3) 0%,
                transparent 70%
            );
            animation: quickFlash 0.6s ease-out;
            z-index: 1;
            border-radius: inherit;
            pointer-events: none;
        }

        /* Navigation container */
        .footer-nav {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 36rem;
            margin: 0 auto;
            height: 100%;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .volleyball-icon {
                display: none !important;
            }
            
            .footer-floating {
                padding: 10px 8px;
                border-top-left-radius: 16px;
                border-top-right-radius: 16px;
                height: 62px;
            }
            
            .footer-menu {
                padding: 7px 5px;
                margin: 0 3px;
                min-width: 52px;
                border-radius: 10px;
            }
            
            .footer-icon {
                width: 18px;
                height: 18px;
                margin-bottom: 3px;
            }
            
            .footer-label {
                font-size: 9px;
            }
            
            .footer-menu.active::after {
                width: 18px;
                height: 2px;
                bottom: 3px;
            }
            
            @keyframes linePulse {
                0%, 100% {
                    width: 18px;
                }
                50% {
                    width: 20px;
                }
            }
            
            .text-3xl {
                font-size: 1.75rem;
            }
            
            .volley-icon-main {
                font-size: 3rem !important;
            }
        }

        @media (max-width: 480px) {
            .footer-floating {
                padding: 8px 6px;
                height: 58px;
                border-top-left-radius: 14px;
                border-top-right-radius: 14px;
            }
            
            .footer-menu {
                padding: 6px 4px;
                margin: 0 2px;
                min-width: 48px;
                border-radius: 8px;
            }
            
            .footer-icon {
                width: 17px;
                height: 17px;
                margin-bottom: 2px;
            }
            
            .footer-label {
                font-size: 8.5px;
            }
            
            .footer-menu.active::after {
                width: 16px;
                height: 2px;
                bottom: 2px;
            }
            
            @keyframes linePulse {
                0%, 100% {
                    width: 16px;
                }
                50% {
                    width: 18px;
                }
            }
            
            .text-3xl {
                font-size: 1.5rem;
            }
            
            .volley-icon-main {
                font-size: 2.5rem !important;
            }
        }

        @media (max-width: 360px) {
            .footer-floating {
                padding: 7px 5px;
                height: 56px;
            }
            
            .footer-menu {
                padding: 5px 3px;
                margin: 0 1px;
                min-width: 46px;
            }
            
            .footer-icon {
                width: 16px;
                height: 16px;
            }
            
            .footer-label {
                font-size: 8px;
            }
            
            .footer-menu.active::after {
                width: 14px;
                height: 1.5px;
                bottom: 2px;
            }
            
            @keyframes linePulse {
                0%, 100% {
                    width: 14px;
                }
                50% {
                    width: 16px;
                }
            }
            
            .volley-icon-main {
                font-size: 2rem !important;
            }
        }

        /* Form input styles */
        .form-input {
            width: 100%;
            background: transparent;
            border: none;
            font-size: 0.875rem;
            color: var(--gray-700);
        }

        .form-input:focus {
            outline: none;
        }

        /* Search bar styles */
        .search-bar {
            display: flex;
            align-items: center;
            background-color: var(--white);
            border-radius: 9999px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 1rem;
            margin-bottom: 0.75rem;
        }

        /* Untuk prefers-reduced-motion */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body class="bg-header">
    @yield('header')
    
    <main class="min-h-screen" style="padding-bottom: 90px;"> <!-- Padding bottom untuk memberi ruang footer -->
        @yield('content')
    </main>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    @yield('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // logout function
        function logout() {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                document.getElementById('logout-form').submit();
            }
        }

        // ========== FIX JAVASCRIPT UNTUK BOLA VOLI ==========
        function fixVolleyIconPosition() {
            const volleyIcon = document.querySelector('.volley-icon-main');
            const centerHeader = document.querySelector('.center-header');
            
            if (volleyIcon && centerHeader) {
                // Force center positioning
                volleyIcon.style.cssText += 'margin-left: auto !important; margin-right: auto !important; display: block !important; float: none !important; text-align: center !important;';
                centerHeader.style.cssText += 'text-align: center !important; display: block !important; width: 100% !important;';
                
                // Tambahkan wrapper untuk extra safety
                if (!volleyIcon.parentElement.classList.contains('volley-icon-wrapper')) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'volley-icon-wrapper';
                    wrapper.style.cssText = 'width: 100%; text-align: center; margin-bottom: 1rem;';
                    volleyIcon.parentNode.insertBefore(wrapper, volleyIcon);
                    wrapper.appendChild(volleyIcon);
                }
            }
        }

        // Fungsi untuk mendeteksi halaman aktif berdasarkan URL
        function getCurrentPage() {
            const path = window.location.pathname;
            
            // Mapping path ke nama halaman
            if (path === '/' || path === '/beranda' || path.includes('beranda')) return 'beranda';
            if (path.includes('/pesan')) return 'pesan';
            if (path.includes('/riwayat')) return 'riwayat';
            if (path.includes('/akun')) return 'akun';
            
            return 'beranda'; // default
        }

        // Fungsi untuk mengatur menu aktif
        function setActiveMenu() {
            const currentPage = getCurrentPage();
            const footerMenus = document.querySelectorAll('.footer-menu');
            
            footerMenus.forEach(menu => {
                const href = menu.getAttribute('href');
                const menuPage = href.split('/').pop() || 'beranda';
                
                // Hapus semua kelas aktif
                menu.classList.remove('active');
                
                // Tambahkan kelas aktif jika cocok
                if ((currentPage === 'beranda' && menuPage === 'beranda') ||
                    (currentPage === 'pesan' && href.includes('/pesan')) ||
                    (currentPage === 'riwayat' && href.includes('/riwayat')) ||
                    (currentPage === 'akun' && href.includes('/akun'))) {
                    menu.classList.add('active');
                }
            });
        }

        // Fungsi untuk menyesuaikan padding body berdasarkan tinggi footer
        function adjustBodyPadding() {
            const footer = document.querySelector('.footer-floating');
            if (footer) {
                const footerHeight = footer.offsetHeight;
                // Set padding bottom body sama dengan tinggi footer
                document.body.style.paddingBottom = footerHeight + 'px';
                
                // Juga set padding bottom untuk main element
                const main = document.querySelector('main');
                if (main) {
                    main.style.paddingBottom = (footerHeight + 20) + 'px';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const footerMenus = document.querySelectorAll('.footer-menu');
            
            // Set menu aktif berdasarkan URL saat halaman dimuat
            setActiveMenu();
            
            // Fix bola voli position
            fixVolleyIconPosition();
            
            // Sesuaikan padding body
            adjustBodyPadding();
            
            // Tambahkan event listener untuk setiap menu
            if (footerMenus) {
                footerMenus.forEach(menu => {
                    menu.addEventListener('click', function(e) {
                        // Jika menu ini sudah aktif, jangan lakukan apa-apa
                        if (this.classList.contains('active')) {
                            e.preventDefault();
                            return;
                        }
                        
                        // Cegah navigasi default dulu
                        e.preventDefault();
                        
                        // Simpan href untuk navigasi nanti
                        const href = this.getAttribute('href');
                        
                        // Hapus kelas aktif dari semua menu
                        footerMenus.forEach(m => {
                            m.classList.remove('active');
                        });
                        
                        // Tambahkan kelas aktif ke menu yang diklik
                        this.classList.add('active');
                        
                        // Berikan efek klik
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = '';
                            
                            // Tunggu sebentar untuk animasi, lalu navigasi
                            setTimeout(() => {
                                window.location.href = href;
                            }, 200);
                        }, 150);
                    });
                });
            }
            
            // Update menu aktif saat navigasi (untuk browser history)
            window.addEventListener('popstate', function() {
                setActiveMenu();
                fixVolleyIconPosition();
            });
        });

        // Fungsi untuk update menu aktif saat halaman selesai dimuat
        window.addEventListener('load', function() {
            setActiveMenu();
            fixVolleyIconPosition();
            adjustBodyPadding();
        });

        // Juga sesuaikan saat ukuran window berubah
        window.addEventListener('resize', adjustBodyPadding);
    </script>
    
    @yield('scripts')
</body>
</html>