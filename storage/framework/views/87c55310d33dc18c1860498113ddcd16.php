<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'CariArena'); ?></title>
    
    <!-- Tambahkan meta untuk notifikasi -->
    <?php if(session('success')): ?>
        <meta name="success-message" content="<?php echo e(session('success')); ?>">
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <meta name="error-message" content="<?php echo e(session('error')); ?>">
    <?php endif; ?>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #63B3ED;
            --primary-hover: #90CDF4;
            --primary-light: #EBF8FF;
            --text-dark: #1A202C;
            --text-light: #718096;
            --bg-light: #EDF2F7;
            --card-bg: #FFFFFF;
            --success: #48BB78;
            --warning: #ECC94B;
            --danger: #F56565;
            --sidebar-bg: #FFFFFF;
            --btn-blue: #4299E1;
            --btn-green: #48BB78;
            --btn-yellow: #ED8936;
        }

        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-light);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* ==== LAYOUT UTAMA ==== */
        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* ==== SIDEBAR ==== */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: var(--text-light);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            padding: 25px 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            transform: translateX(0);
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .sidebar .logo i {
            font-size: 28px;
            margin-right: 10px;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .sidebar h2 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
            color: var(--text-dark);
            transition: all 0.3s ease;
        }

        .sidebar hr {
            border-color: rgba(113, 128, 150, 0.2);
            margin: 20px 0;
            transition: all 0.3s ease;
        }

        .sidebar .nav-title {
            font-size: 12px;
            letter-spacing: 1px;
            color: var(--text-light);
            margin-bottom: 15px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .sidebar .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex: 1;
        }

        .sidebar .nav-menu li {
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 8px;
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-menu a i {
            margin-right: 12px;
            font-size: 18px;
            width: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        /* WARNA IKON UNTUK SETIAP MENU */
        .sidebar .nav-menu a .fa-tachometer-alt {color: #4C8BF5;}
        .sidebar .nav-menu a .fa-users {color: #22C55E;}
        .sidebar .nav-menu a .fa-store {color: #F59E0B;}
        .sidebar .nav-menu a .fa-calendar-alt {color: #EC4899;}
        .sidebar .nav-menu a .fa-credit-card {color: #8B5CF6;}
        .sidebar .nav-menu a .fa-chart-bar {color: #F87171;}
        .sidebar .nav-menu a .fa-cog {color: #94A3B8;}
        .sidebar .nav-menu a .fa-home {color: #63B3ED;}
        .sidebar .nav-menu a .fa-ticket-alt {color: #B794F4;}
        .sidebar .nav-menu a .fa-star {color: #F6E05E;}
        .sidebar .nav-menu a .fa-chart-line {color: #63B3ED;}

        /* Animasi hover untuk menu items */
        .sidebar .nav-menu a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .sidebar .nav-menu a:hover::before {
            left: 100%;
        }

        .sidebar .nav-menu a:hover {
            background: var(--primary-light);
            color: var(--text-dark);
            transform: translateX(8px);
            box-shadow: 0 4px 12px rgba(99, 179, 237, 0.15);
        }

        .sidebar .nav-menu a.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(99, 179, 237, 0.3);
            transform: translateX(0);
            animation: menuPulse 2s infinite;
        }

        /* Animasi pulse untuk menu aktif */
        @keyframes menuPulse {
            0% {
                box-shadow: 0 6px 20px rgba(99, 179, 237, 0.3);
            }
            50% {
                box-shadow: 0 6px 25px rgba(99, 179, 237, 0.5);
            }
            100% {
                box-shadow: 0 6px 20px rgba(99, 179, 237, 0.3);
            }
        }

        /* Animasi shimmer untuk menu aktif */
        @keyframes shimmer {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(30deg);
            }
            100% {
                transform: translateX(100%) translateY(100%) rotate(30deg);
            }
        }

        /* Garis indikator untuk menu aktif */
        .sidebar .nav-menu a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: white;
            border-radius: 0 4px 4px 0;
            animation: lineGlow 2s infinite;
        }

        @keyframes lineGlow {
            0%, 100% {
                box-shadow: 0 0 5px rgba(255,255,255,0.5);
            }
            50% {
                box-shadow: 0 0 15px rgba(255,255,255,0.8);
            }
        }

        /* Efek kilat pada menu aktif */
        .sidebar .nav-menu a.active::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255,255,255,0) 0%,
                rgba(255,255,255,0.3) 50%,
                rgba(255,255,255,0) 100%
            );
            transform: rotate(30deg);
            animation: shimmer 3s infinite;
        }

        /* Saat menu aktif, ikon berubah menjadi putih */
        .sidebar .nav-menu a.active i {
            color: white !important;
            animation: iconBounce 0.6s ease;
        }

        @keyframes iconBounce {
            0%, 20%, 60%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-5px);
            }
            80% {
                transform: translateY(-2px);
            }
        }

        /* Menu logout dengan warna merah */
        .sidebar .nav-menu .logout-btn {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 8px;
            color: #F56565;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
            overflow: hidden;
        }

        .sidebar .nav-menu .logout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(245, 101, 101, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .sidebar .nav-menu .logout-btn:hover::before {
            left: 100%;
        }

        .sidebar .nav-menu .logout-btn:hover {
            background: #FED7D7;
            color: #C53030;
            transform: translateX(8px);
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.15);
        }

        .sidebar .nav-menu .logout-btn i {
            margin-right: 12px;
            font-size: 18px;
            width: 20px;
            text-align: center;
            color: #F56565;
            transition: all 0.3s ease;
        }

        .sidebar .nav-menu .logout-btn:hover i {
            color: #C53030;
            animation: iconShake 0.5s ease;
        }

        @keyframes iconShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-2px); }
            75% { transform: translateX(2px); }
        }

        /* ==== MAIN CONTENT AREA ==== */
        .main-content {
            flex: 1;
            margin-left: 260px;
            background-color: var(--bg-light);
            min-height: 100vh;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* ==== HEADER/TOPBAR ==== */
        .topbar {
            background: white;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 8px rgba(45, 55, 72, 0.1);
            margin: 30px 30px 1.5rem;
            border-left: 5px solid var(--primary-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .topbar:hover {
            box-shadow: 0 4px 15px rgba(45, 55, 72, 0.15);
            transform: translateY(-2px);
        }

        .topbar h1 {
            color: var(--primary-color);
            font-size: 28px;
            margin: 0;
            font-weight: 700;
        }

        /* User Info Button */
        .user-info-btn {
            display: flex;
            align-items: center;
            background: white;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--text-dark);
            text-decoration: none;
            font-size: 16px;
        }

        .user-info-btn:hover {
            background: var(--primary-light);
            box-shadow: 0 6px 20px rgba(99, 179, 237, 0.2);
            transform: translateY(-3px);
        }

        .user-info-btn:active {
            transform: translateY(-1px);
        }

        .user-info-btn img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .user-info-btn:hover img {
            border-color: var(--primary-hover);
            transform: scale(1.1) rotate(5deg);
        }

        .user-info-btn .user-name {
            font-weight: 500;
            margin-right: 8px;
            transition: all 0.3s ease;
        }

        .user-info-btn .arrow-icon {
            color: var(--primary-color);
            transition: all 0.3s ease;
            margin-left: 10px;
        }

        .user-info-btn:hover .arrow-icon {
            transform: translateX(5px);
            color: var(--primary-hover);
        }

        /* Profile Dropdown Menu */
        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            z-index: 1000;
            display: none;
            overflow: hidden;
            margin-top: 5px;
        }

        .profile-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-dropdown-item:last-child {
            border-bottom: none;
        }

        .profile-dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary-color);
            padding-left: 20px;
        }

        .profile-dropdown-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .profile-dropdown-header {
            padding: 15px;
            background: var(--primary-light);
            border-bottom: 1px solid #e0e0e0;
        }

        .profile-dropdown-header .user-name {
            font-weight: 600;
            color: var(--text-dark);
        }

        .profile-dropdown-header .user-email {
            font-size: 12px;
            color: var(--text-light);
        }

        /* ==== CONTENT WRAPPER ==== */
        .content-wrapper {
            flex: 1;
            padding: 0 30px 30px;
            transition: all 0.3s ease;
        }

        /* ==== MOBILE NAVIGATION ==== */
        .mobile-nav {
            display: none;
            background: white;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(45, 55, 72, 0.1);
            position: sticky;
            top: 0;
            z-index: 999;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .mobile-nav.scrolled {
            box-shadow: 0 4px 15px rgba(45, 55, 72, 0.15);
        }

        .mobile-nav .logo {
            display: flex;
            align-items: center;
        }

        .mobile-nav .logo i {
            font-size: 24px;
            margin-right: 10px;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .mobile-nav h2 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            color: var(--text-dark);
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-dark);
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            position: relative;
        }

        .mobile-menu-toggle:hover {
            background-color: var(--bg-light);
            transform: scale(1.1);
        }

        .mobile-menu-toggle:active {
            transform: scale(0.95);
        }

        /* Animasi hamburger icon */
        .mobile-menu-toggle i {
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle.active i {
            transform: rotate(90deg);
        }

        /* ==== OVERLAY UNTUK MOBILE ==== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* ==== DIVIDER UNTUK LOGOUT ==== */
        .sidebar .nav-divider {
            margin: 20px 0;
            border-color: rgba(113, 128, 150, 0.2);
            transition: all 0.3s ease;
        }

        /* ==== MODAL KONFIRMASI LOGOUT ==== */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1100;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .logout-modal {
            background: white;
            border-radius: 14px;
            padding: 25px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .modal-overlay.active .logout-modal {
            transform: translateY(0);
            opacity: 1;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .logout-modal .modal-icon {
            font-size: 3rem;
            color: #F56565;
            margin-bottom: 15px;
            animation: iconPulse 2s infinite;
        }

        @keyframes iconPulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        .logout-modal h4 {
            color: var(--text-dark);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .logout-modal p {
            color: var(--text-light);
            margin-bottom: 20px;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .modal-btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            flex: 1;
        }

        .modal-btn-cancel {
            background: #EDF2F7;
            color: var(--text-dark);
        }

        .modal-btn-cancel:hover {
            background: #E2E8F0;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .modal-btn-confirm {
            background: #F56565;
            color: white;
        }

        .modal-btn-confirm:hover {
            background: #E53E3E;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(245, 101, 101, 0.4);
        }

        /* Toast Notification */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            z-index: 9999;
            transform: translateX(150%);
            transition: transform 0.3s ease;
        }

        .toast-notification.show {
            transform: translateX(0);
        }

        .toast-icon {
            margin-right: 10px;
            font-size: 20px;
        }

        .toast-success {
            border-left: 4px solid var(--success);
        }

        .toast-success .toast-icon {
            color: var(--success);
        }

        .toast-error {
            border-left: 4px solid var(--danger);
        }

        .toast-error .toast-icon {
            color: var(--danger);
        }

        /* ========== RESPONSIVE DESIGN ========== */
        /* Tablet */
        @media (max-width: 1024px) {
            .sidebar {
                width: 240px;
                padding: 20px 15px;
            }
            
            .main-content {
                margin-left: 240px;
            }
            
            .topbar {
                margin: 25px 25px 1.5rem;
                padding: 0.9rem 1.2rem;
            }
            
            .topbar h1 {
                font-size: 24px;
            }
            
            .content-wrapper {
                padding: 0 25px 25px;
            }
            
            .user-info-btn {
                padding: 8px 12px;
                font-size: 15px;
            }
            
            .user-info-btn img {
                width: 36px;
                height: 36px;
            }
        }

        /* Mobile */
        @media (max-width: 768px) {
            .app-container {
                flex-direction: column;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            }
            
            .sidebar.active {
                transform: translateX(0);
                animation: slideInLeft 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            @keyframes slideInLeft {
                from {
                    transform: translateX(-100%);
                }
                to {
                    transform: translateX(0);
                }
            }
            
            .mobile-nav {
                display: flex;
            }
            
            .topbar {
                margin: 1rem 1rem 1rem;
                padding: 0.75rem 1rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
                border-radius: 8px;
            }
            
            .topbar h1 {
                font-size: 20px;
            }
            
            .content-wrapper {
                padding: 0 1rem 1rem;
            }
            
            .user-info-btn {
                width: 100%;
                justify-content: flex-start;
            }
            
            .sidebar-overlay.active {
                display: block;
                animation: fadeIn 0.3s ease;
            }
            
            @keyframes fadeIn {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }
            
            .profile-dropdown {
                right: 10px;
                left: 10px;
                min-width: auto;
            }
        }

        /* Small Mobile */
        @media (max-width: 480px) {
            .mobile-nav {
                padding: 0.75rem;
            }
            
            .topbar {
                margin: 0.75rem 0.75rem 0.75rem;
                padding: 0.5rem 0.75rem;
            }
            
            .topbar h1 {
                font-size: 18px;
            }
            
            .content-wrapper {
                padding: 0 0.75rem 0.75rem;
            }
            
            .modal-buttons {
                flex-direction: column;
            }
            
            .sidebar {
                width: 85%;
                max-width: 300px;
            }
        }

        /* Very Small Mobile */
        @media (max-width: 360px) {
            .mobile-nav {
                padding: 0.5rem;
            }
            
            .mobile-nav h2 {
                font-size: 16px;
            }
            
            .topbar {
                margin: 0.5rem 0.5rem 0.5rem;
                padding: 0.5rem;
            }
            
            .topbar h1 {
                font-size: 16px;
            }
            
            .content-wrapper {
                padding: 0 0.5rem 0.5rem;
            }
            
            .user-info-btn {
                font-size: 14px;
                padding: 8px 10px;
            }
            
            .user-info-btn img {
                width: 32px;
                height: 32px;
            }
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Performance optimizations */
        .sidebar * {
            will-change: transform, opacity;
        }

        /* Reduced motion for accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="app-container">
        <!-- Mobile Navigation -->
        <nav class="mobile-nav" id="mobileNav">
            <div class="logo">
                <i class="fas fa-volleyball-ball"></i>
                <h2>CariArena</h2>
            </div>
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </nav>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="logo">
                <i class="fas fa-volleyball-ball"></i>
                <h2>CariArena <?php echo $__env->yieldContent('role-text', ''); ?></h2>
            </div>
            <hr>
            <div class="nav-title">NAVIGATION</div>
            
            <!-- Menu akan diisi oleh child template -->
            <?php echo $__env->yieldContent('sidebar-menu'); ?>
            
            <!-- Divider untuk logout -->
            <hr class="nav-divider">
            
            <!-- Tombol Logout -->
            <ul class="nav-menu">
                <li>
                    <button class="logout-btn" id="logoutBtn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </li>
            </ul>
        </aside>

        <!-- Overlay untuk mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Content Area -->
        <div class="main-content" id="mainContent">
            <!-- Topbar -->
            <div class="topbar">
                <h1><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                
                <!-- User Profile Button dengan Data dari Database -->
                <div style="position: relative;">
                    <button class="user-info-btn" id="userInfoBtn">
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(Auth::user()->profile_picture): ?>
                                <img src="<?php echo e(asset('storage/' . Auth::user()->profile_picture)); ?>" 
                                     alt="<?php echo e(Auth::user()->name); ?>" 
                                     onerror="this.src='https://ui-avatars.com/api/?name=<?php echo e(urlencode(Auth::user()->name)); ?>&background=63B3ED&color=fff'">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(Auth::user()->name)); ?>&background=63B3ED&color=fff" 
                                     alt="<?php echo e(Auth::user()->name); ?>">
                            <?php endif; ?>
                            <span class="user-name"><?php echo e(Auth::user()->name); ?></span>
                            <i class="fas fa-chevron-down arrow-icon"></i>
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=Guest&background=718096&color=fff" alt="Guest">
                            <span class="user-name">Guest</span>
                            <i class="fas fa-chevron-down arrow-icon"></i>
                        <?php endif; ?>
                    </button>
                    
                    <!-- Dropdown Menu untuk Profil -->
                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="profile-dropdown-header">
                            <div class="user-name">
                                <?php if(auth()->guard()->check()): ?>
                                    <?php echo e(Auth::user()->name); ?>

                                <?php else: ?>
                                    Guest
                                <?php endif; ?>
                            </div>
                            <div class="user-email">
                                <?php if(auth()->guard()->check()): ?>
                                    <?php echo e(Auth::user()->email); ?>

                                <?php else: ?>
                                    guest@example.com
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if(auth()->guard()->check()): ?>
                            <!-- Link ke halaman profil -->
                            <?php if(Route::has('profile.show')): ?>
                                <a href="<?php echo e(route('profile.show')); ?>" class="profile-dropdown-item">
                                    <i class="fas fa-user-circle"></i>
                                    Profil Saya
                                </a>
                            <?php else: ?>
                                <a href="#" class="profile-dropdown-item" onclick="showToast('Halaman profil tidak tersedia', 'error')">
                                    <i class="fas fa-user-circle"></i>
                                    Profil Saya
                                </a>
                            <?php endif; ?>
                            
                            <!-- Link ke pengaturan -->
                            <?php if(Route::has('settings')): ?>
                                <a href="<?php echo e(route('settings')); ?>" class="profile-dropdown-item">
                                    <i class="fas fa-cog"></i>
                                    Pengaturan
                                </a>
                            <?php endif; ?>
                            
                            <!-- Role Specific Menu -->
                            <?php if(Auth::user()->role === 'admin'): ?>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="profile-dropdown-item">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Admin Dashboard
                                </a>
                            <?php elseif(Auth::user()->role === 'venue'): ?>
                                <a href="<?php echo e(route('venue.dashboard')); ?>" class="profile-dropdown-item">
                                    <i class="fas fa-store"></i>
                                    Venue Dashboard
                                </a>
                            <?php endif; ?>
                            
                            <div class="profile-dropdown-item" style="border-top: 2px solid #f0f0f0; margin-top: 5px;">
                                <i class="fas fa-info-circle"></i>
                                <small>Role: <?php echo e(ucfirst(Auth::user()->role)); ?></small>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="profile-dropdown-item">
                                <i class="fas fa-sign-in-alt"></i>
                                Login
                            </a>
                            <a href="<?php echo e(route('register')); ?>" class="profile-dropdown-item">
                                <i class="fas fa-user-plus"></i>
                                Register
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="content-wrapper">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast-notification" id="toastNotification">
        <div class="toast-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div class="toast-message" id="toastMessage"></div>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div class="modal-overlay" id="logoutModal">
        <div class="logout-modal">
            <div class="modal-icon">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <h4>Konfirmasi Logout</h4>
            <p>Apakah Anda yakin ingin keluar dari sistem?</p>
            <div class="modal-buttons">
                <button class="modal-btn modal-btn-cancel" id="cancelLogout">Batal</button>
                <form action="<?php echo e(route('logout')); ?>" method="POST" id="logoutForm" style="display: none;">
                    <?php echo csrf_field(); ?>
                </form>
                <button class="modal-btn modal-btn-confirm" id="confirmLogout">Logout</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            const mobileNav = document.getElementById('mobileNav');
            
            // Toggle sidebar ketika tombol menu diklik
            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
                mobileMenuToggle.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
                
                // Animasi hamburger icon
                if (sidebar.classList.contains('active')) {
                    mobileMenuToggle.innerHTML = '<i class="fas fa-times"></i>';
                } else {
                    mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });
            
            // Tutup sidebar ketika overlay diklik
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
                mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                document.body.style.overflow = '';
            });
            
            // Tutup sidebar ketika item menu diklik (di mobile)
            const navLinks = document.querySelectorAll('.sidebar .nav-menu a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                        mobileMenuToggle.classList.remove('active');
                        mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                        document.body.style.overflow = '';
                    }
                });
            });
            
            // Scroll effect untuk mobile nav
            window.addEventListener('scroll', function() {
                if (window.innerWidth <= 768) {
                    if (window.scrollY > 50) {
                        mobileNav.classList.add('scrolled');
                    } else {
                        mobileNav.classList.remove('scrolled');
                    }
                }
            });
            
            // Tutup sidebar ketika window di-resize ke ukuran desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                    mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                    document.body.style.overflow = '';
                }
            });

            // Profile Dropdown Functionality
            const userInfoBtn = document.getElementById('userInfoBtn');
            const profileDropdown = document.getElementById('profileDropdown');
            
            if (userInfoBtn && profileDropdown) {
                userInfoBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('show');
                    
                    // Close other dropdowns if open
                    closeAllDropdownsExcept(profileDropdown);
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!profileDropdown.contains(e.target) && !userInfoBtn.contains(e.target)) {
                        profileDropdown.classList.remove('show');
                    }
                });
                
                // Close dropdown on escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        profileDropdown.classList.remove('show');
                    }
                });
            }
            
            function closeAllDropdownsExcept(exceptDropdown) {
                const allDropdowns = document.querySelectorAll('.profile-dropdown');
                allDropdowns.forEach(dropdown => {
                    if (dropdown !== exceptDropdown) {
                        dropdown.classList.remove('show');
                    }
                });
            }

            // Logout functionality
            const logoutBtn = document.getElementById('logoutBtn');
            const logoutModal = document.getElementById('logoutModal');
            const cancelLogout = document.getElementById('cancelLogout');
            const confirmLogout = document.getElementById('confirmLogout');
            const logoutForm = document.getElementById('logoutForm');

            // Tampilkan modal konfirmasi logout
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function() {
                    logoutModal.classList.add('active');
                    
                    // Tutup sidebar di mobile saat logout diklik
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                        mobileMenuToggle.classList.remove('active');
                        mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                        document.body.style.overflow = '';
                    }
                    
                    // Tutup profile dropdown jika terbuka
                    if (profileDropdown) {
                        profileDropdown.classList.remove('show');
                    }
                });
            }

            // Tutup modal ketika batal diklik
            if (cancelLogout) {
                cancelLogout.addEventListener('click', function() {
                    logoutModal.classList.remove('active');
                });
            }

            // Proses logout ketika konfirmasi diklik
            if (confirmLogout) {
                confirmLogout.addEventListener('click', function() {
                    // Tambah efek loading
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging out...';
                    this.disabled = true;
                    
                    // Submit form logout setelah delay kecil
                    setTimeout(() => {
                        if (logoutForm) {
                            logoutForm.submit();
                        }
                    }, 1000);
                });
            }

            // Tutup modal ketika klik di luar modal
            if (logoutModal) {
                logoutModal.addEventListener('click', function(e) {
                    if (e.target === logoutModal) {
                        logoutModal.classList.remove('active');
                    }
                });
            }

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Tutup sidebar jika terbuka
                    if (sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                        mobileMenuToggle.classList.remove('active');
                        mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                        document.body.style.overflow = '';
                    }
                    
                    // Tutup modal logout jika terbuka
                    if (logoutModal.classList.contains('active')) {
                        logoutModal.classList.remove('active');
                    }
                    
                    // Tutup profile dropdown jika terbuka
                    if (profileDropdown && profileDropdown.classList.contains('show')) {
                        profileDropdown.classList.remove('show');
                    }
                }
            });

            // Add hover effects to menu items on desktop
            if (window.innerWidth > 768) {
                const menuItems = document.querySelectorAll('.sidebar .nav-menu a');
                menuItems.forEach(item => {
                    item.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateX(8px)';
                    });
                    
                    item.addEventListener('mouseleave', function() {
                        if (!this.classList.contains('active')) {
                            this.style.transform = 'translateX(0)';
                        }
                    });
                });
            }

            // Show session notifications
            const successMessage = document.querySelector('meta[name="success-message"]');
            const errorMessage = document.querySelector('meta[name="error-message"]');
            
            if (successMessage && successMessage.content) {
                showToast(successMessage.content, 'success');
            }
            
            if (errorMessage && errorMessage.content) {
                showToast(errorMessage.content, 'error');
            }
        });

        // Function to show toast notification
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toastNotification');
            const toastMessage = document.getElementById('toastMessage');
            
            if (!toast || !toastMessage) return;
            
            // Set message and type
            toastMessage.textContent = message;
            toast.className = 'toast-notification';
            
            if (type === 'success') {
                toast.classList.add('toast-success');
                toast.querySelector('.toast-icon i').className = 'fas fa-check-circle';
            } else if (type === 'error') {
                toast.classList.add('toast-error');
                toast.querySelector('.toast-icon i').className = 'fas fa-exclamation-circle';
            } else {
                toast.querySelector('.toast-icon i').className = 'fas fa-info-circle';
            }
            
            // Show toast
            toast.classList.add('show');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                toast.classList.remove('show');
            }, 5000);
            
            // Close on click
            toast.addEventListener('click', function() {
                this.classList.remove('show');
            });
        }

        // Function to handle user profile redirect
        function goToProfile() {
            <?php if(auth()->guard()->check()): ?>
                <?php if(View::hasSection('user-profile-link')): ?>
                    window.location.href = "<?php echo $__env->yieldContent('user-profile-link'); ?>";
                <?php else: ?>
                    // Default profile routes based on user role
                    const userRole = "<?php echo e(Auth::user()->role ?? ''); ?>";
                    
                    switch(userRole) {
                        case 'admin':
                            if (typeof route !== 'undefined' && route('admin.profile')) {
                                window.location.href = route('admin.profile');
                            } else {
                                showToast('Halaman profil admin tidak ditemukan', 'error');
                            }
                            break;
                        case 'venue':
                            if (typeof route !== 'undefined' && route('venue.profile')) {
                                window.location.href = route('venue.profile');
                            } else {
                                showToast('Halaman profil venue tidak ditemukan', 'error');
                            }
                            break;
                        case 'user':
                            if (typeof route !== 'undefined' && route('user.profile')) {
                                window.location.href = route('user.profile');
                            } else {
                                showToast('Halaman profil user tidak ditemukan', 'error');
                            }
                            break;
                        default:
                            showToast('Role pengguna tidak dikenali', 'error');
                    }
                <?php endif; ?>
            <?php else: ?>
                window.location.href = "<?php echo e(route('login')); ?>";
            <?php endif; ?>
        }
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH D:\CariArena\resources\views/layouts/app.blade.php ENDPATH**/ ?>