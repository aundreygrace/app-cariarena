<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CariArena</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4A90E2;
            --primary-hover: #6AA7EC;
            --primary-light: #E3F2FD;
            --text-dark: #1A202C;
            --text-light: #718096;
            --bg-light: #EDF2F7;
            --card-bg: #FFFFFF;
            --success: #48BB78;
            --warning: #ECC94B;
            --danger: #F56565;
            --gradient-start: #f5f7fa;
            --gradient-end: #c3cfe2;
            --border-radius: 12px;
        }

        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            overflow-x: hidden;
        }

        .auth-container {
            min-height: 100vh;
            background: linear-gradient(120deg, #dff3ff 0%, #e8f4ff 40%, #b7dbff 80%, #a0c4ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Efek glow lembut */
        .auth-container::before {
            content: '';
            position: absolute;
            top: -10%;
            left: -20%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle at top right, rgba(102, 204, 255, 0.6), transparent 70%);
            filter: blur(100px);
            z-index: 0;
        }

        .auth-container::after {
            content: '';
            position: absolute;
            bottom: -10%;
            right: -10%;
            width: 70%;
            height: 70%;
            background: radial-gradient(circle at bottom left, rgba(0, 102, 255, 0.5), transparent 70%);
            filter: blur(120px);
            z-index: 0;
        }

        /* ANIMASI IKON BOLA VOLI DENGAN BERBAGAI UKURAN */
        .volleyball-icon {
            position: absolute;
            color: white;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
            animation: bounce 20s infinite linear;
            z-index: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Ikon dengan ukuran berbeda */
        .icon-1 {
            font-size: 5rem;
            animation-name: bounce-1;
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }

        .icon-2 {
            font-size: 3.5rem;
            animation-name: bounce-2;
            bottom: 15%;
            right: 8%;
            animation-delay: -5s;
        }

        .icon-3 {
            font-size: 2.5rem;
            animation-name: bounce-3;
            top: 60%;
            left: 8%;
            animation-delay: -10s;
        }

        .icon-4 {
            font-size: 4rem;
            animation-name: bounce-4;
            bottom: 10%;
            left: 15%;
            animation-delay: -15s;
        }

        .icon-5 {
            font-size: 3rem;
            animation-name: bounce-5;
            top: 15%;
            right: 10%;
            animation-delay: -7s;
        }

        /* Animasi memantul untuk setiap ikon */
        @keyframes bounce-1 {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            10% {
                transform: translate(30px, -80px) rotate(36deg);
            }
            20% {
                transform: translate(60px, 20px) rotate(72deg);
            }
            30% {
                transform: translate(90px, -60px) rotate(108deg);
            }
            40% {
                transform: translate(120px, 40px) rotate(144deg);
            }
            50% {
                transform: translate(150px, -40px) rotate(180deg);
            }
            60% {
                transform: translate(180px, 60px) rotate(216deg);
            }
            70% {
                transform: translate(210px, -20px) rotate(252deg);
            }
            80% {
                transform: translate(240px, 80px) rotate(288deg);
            }
            90% {
                transform: translate(270px, 0px) rotate(324deg);
            }
        }

        @keyframes bounce-2 {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            12.5% {
                transform: translate(-40px, -70px) rotate(45deg);
            }
            25% {
                transform: translate(-80px, 30px) rotate(90deg);
            }
            37.5% {
                transform: translate(-120px, -50px) rotate(135deg);
            }
            50% {
                transform: translate(-160px, 50px) rotate(180deg);
            }
            62.5% {
                transform: translate(-200px, -30px) rotate(225deg);
            }
            75% {
                transform: translate(-240px, 70px) rotate(270deg);
            }
            87.5% {
                transform: translate(-280px, 10px) rotate(315deg);
            }
        }

        @keyframes bounce-3 {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            20% {
                transform: translate(50px, -60px) rotate(72deg);
            }
            40% {
                transform: translate(100px, 40px) rotate(144deg);
            }
            60% {
                transform: translate(150px, -30px) rotate(216deg);
            }
            80% {
                transform: translate(200px, 50px) rotate(288deg);
            }
        }

        @keyframes bounce-4 {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(-60px, -40px) rotate(90deg);
            }
            50% {
                transform: translate(-120px, 60px) rotate(180deg);
            }
            75% {
                transform: translate(-180px, -20px) rotate(270deg);
            }
        }

        @keyframes bounce-5 {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            33% {
                transform: translate(70px, -50px) rotate(120deg);
            }
            66% {
                transform: translate(140px, 70px) rotate(240deg);
            }
        }

        .auth-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
            border: none;
            position: relative;
            z-index: 1;
            transition: transform 0.5s ease, box-shadow 0.5s ease;
            backdrop-filter: blur(10px);
            animation: cardAppear 1s ease-out;
        }

        @keyframes cardAppear {
            0% {
                opacity: 0;
                transform: scale(0.8) translateY(50px);
            }
            70% {
                transform: scale(1.02) translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .auth-card:hover {
            transform: translateY(-10px) scale(1.01);
            box-shadow: 0 30px 80px rgba(0,0,0,0.15);
        }

        .auth-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, 
                rgba(255,255,255,0) 0%, 
                rgba(255,255,255,0.8) 50%, 
                rgba(255,255,255,0) 100%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }

        .auth-header i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
            animation: iconPulse 2s infinite ease-in-out;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        @keyframes iconPulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        /* PERUBAHAN UTAMA: Style font header seperti gambar */
        .auth-header h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            margin: 0;
            font-size: 2.2rem;
            letter-spacing: -0.5px;
            line-height: 1.2;
            text-transform: none;
            /* EFEK GLOW PADA TULISAN CARI ARENA - LEBIH SOFT */
            text-shadow: 
                0 0 8px rgba(255, 255, 255, 0.4),
                0 0 16px rgba(255, 255, 255, 0.2),
                0 0 24px rgba(255, 255, 255, 0.1);
            animation: textGlowSoft 4s infinite alternate ease-in-out;
        }

        /* ANIMASI GLOW UNTUK TEKS CARI ARENA - LEBIH SOFT */
        @keyframes textGlowSoft {
            0% {
                text-shadow: 
                    0 0 4px rgba(255, 255, 255, 0.3),
                    0 0 8px rgba(255, 255, 255, 0.15),
                    0 0 12px rgba(255, 255, 255, 0.05);
            }
            100% {
                text-shadow: 
                    0 0 12px rgba(255, 255, 255, 0.5),
                    0 0 20px rgba(255, 255, 255, 0.25),
                    0 0 28px rgba(255, 255, 255, 0.15),
                    0 0 36px rgba(255, 255, 255, 0.05);
            }
        }

        .auth-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 400;
        }

        .auth-body {
            padding: 30px;
        }

        .form-control {
            border: 2px solid #E2E8F0;
            border-radius: var(--border-radius);
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.4s ease;
            background-color: #fff;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            font-size: 0.9rem;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
        }

        /* PERBAIKAN UTAMA: Input Group dan Password Toggle */
        .input-group {
            position: relative;
            display: flex;
            align-items: stretch;
            width: 100%;
        }

        .input-group .form-control {
            padding-right: 50px;
            border-radius: var(--border-radius);
            width: 100%;
            flex: 1 1 auto;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            z-index: 5;
            transition: all 0.3s ease;
            padding: 5px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            outline: none;
        }

        .password-toggle:hover {
            color: var(--primary-color);
            background: rgba(74, 144, 226, 0.1);
        }

        /* Override Bootstrap yang mungkin mengganggu */
        .input-group > .form-control {
            border-top-right-radius: var(--border-radius) !important;
            border-bottom-right-radius: var(--border-radius) !important;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.4s ease;
            color: white;
            width: 100%;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(74, 144, 226, 0.3);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.7s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(74, 144, 226, 0.4);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            animation: checkmark 0.3s ease;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0.8);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .form-check-label {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 12px 16px;
            font-size: 0.9rem;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background-color: #FED7D7;
            color: #C53030;
            border-left: 4px solid #F56565;
        }

        .alert-success {
            background-color: #C6F6D5;
            color: #276749;
            border-left: 4px solid #48BB78;
        }

        .alert-info {
            background-color: #BEE3F8;
            color: #2C5AA0;
            border-left: 4px solid #4299E1;
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #E2E8F0;
        }

        .auth-footer p {
            color: var(--text-light);
            font-size: 0.85rem;
            margin: 0;
        }

        .input-animation {
            position: relative;
        }

        @media (max-width: 480px) {
            .auth-card {
                margin: 10px;
                max-width: 100%;
            }
            
            .auth-header {
                padding: 25px 20px;
            }
            
            .auth-body {
                padding: 25px 20px;
            }
            
            .auth-header i {
                font-size: 2.5rem;
            }
            
            .auth-header h1 {
                font-size: 1.8rem;
            }
            
            .volleyball-icon {
                display: none;
            }
        }

        .btn-loading {
            position: relative;
            color: transparent;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-right-color: transparent;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .nav-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .nav-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease, transform 0.2s ease;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .form-group {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
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

        .form-group:nth-child(1) {
            animation-delay: 0.1s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.2s;
        }

        .nav-links {
            animation-delay: 0.3s;
        }

        .btn-login {
            animation-delay: 0.4s;
        }

        .text-center {
            animation-delay: 0.5s;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 0.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:hover {
            transform: scale(1.1);
        }

        .form-check-label {
            cursor: pointer;
            padding-left: 5px;
            transition: color 0.3s ease;
        }

        .form-check-label:hover {
            color: var(--primary-color);
        }

        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            animation: particleFloat 15s infinite linear;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .alert {
            animation: bounceIn 0.6s ease;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(74, 144, 226, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(74, 144, 226, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(74, 144, 226, 0);
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        /* PERBAIKAN: Validasi real-time */
        .is-valid {
            border-color: var(--success) !important;
            box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.2) !important;
        }

        .is-invalid {
            border-color: var(--danger) !important;
            box-shadow: 0 0 0 3px rgba(245, 101, 101, 0.2) !important;
        }

        .validation-feedback {
            display: none;
            font-size: 0.8rem;
            margin-top: 5px;
            padding: 5px 10px;
            border-radius: 5px;
            animation: fadeIn 0.3s ease;
        }

        .validation-feedback.valid {
            display: block;
            background-color: rgba(72, 187, 120, 0.1);
            color: var(--success);
            border-left: 3px solid var(--success);
        }

        .validation-feedback.invalid {
            display: block;
            background-color: rgba(245, 101, 101, 0.1);
            color: var(--danger);
            border-left: 3px solid var(--danger);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* PERBAIKAN: Aksesibilitas */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* PERBAIKAN: Focus styles untuk aksesibilitas */
        .password-toggle:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* PERBAIKAN: Responsivitas tambahan */
        @media (max-width: 350px) {
            .nav-links {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        /* PERBAIKAN: Tanda seru untuk error */
        .error-icon {
            position: absolute;
            right: 45px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--danger);
            display: none;
            z-index: 10;
        }

        /* PERBAIKAN: Posisi mata password */
        .password-toggle {
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 15;
        }

        .input-group .form-control.is-invalid {
            padding-right: 75px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Ikon bola voli dengan berbagai ukuran -->
        <i class="fas fa-volleyball-ball volleyball-icon icon-1" aria-hidden="true"></i>
        <i class="fas fa-volleyball-ball volleyball-icon icon-2" aria-hidden="true"></i>
        <i class="fas fa-volleyball-ball volleyball-icon icon-3" aria-hidden="true"></i>
        <i class="fas fa-volleyball-ball volleyball-icon icon-4" aria-hidden="true"></i>
        <i class="fas fa-volleyball-ball volleyball-icon icon-5" aria-hidden="true"></i>
        
        <!-- Particle effect -->
        <div class="particles" id="particles" aria-hidden="true"></div>
        
        <div class="auth-card">
            <div class="auth-header">
                <i class="fas fa-volleyball-ball" aria-hidden="true"></i>
                <h1>CariArena</h1>
                <p>Masuk ke akun Anda</p>
            </div>

            <div class="auth-body">
                <!-- Alert messages -->
                <?php if(session('error')): ?>
                    <div class="alert alert-danger mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2" aria-hidden="true"></i>
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('success')): ?>
                    <div class="alert alert-success mb-4" role="alert">
                        <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2" aria-hidden="true"></i>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($error); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('login')); ?>" id="loginForm" novalidate>
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-4 form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2" aria-hidden="true"></i>Alamat Email
                        </label>
                        <div class="input-animation">
                            <div class="input-group">
                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="email" name="email" value="<?php echo e(old('email')); ?>" 
                                       placeholder="masukkan email anda" required autocomplete="email" autofocus
                                       aria-describedby="emailFeedback">
                                <i class="fas fa-exclamation-circle error-icon" id="emailErrorIcon" aria-hidden="true"></i>
                                <div id="emailFeedback" class="validation-feedback"></div>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2" aria-hidden="true"></i>Password
                        </label>
                        <div class="input-animation">
                            <div class="input-group">
                                <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="password" name="password" placeholder="masukkan password anda" 
                                       required autocomplete="current-password"
                                       aria-describedby="passwordFeedback">
                                <i class="fas fa-exclamation-circle error-icon" id="passwordErrorIcon" aria-hidden="true"></i>
                                <button type="button" class="password-toggle" id="passwordToggle" 
                                        aria-label="Tampilkan password" aria-pressed="false">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                                <div id="passwordFeedback" class="validation-feedback"></div>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <div class="nav-links form-group">
                        <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <a href="<?php echo e(route('password.request')); ?>" class="text-decoration-none">
                            Lupa password?
                        </a>
                    </div>

                    <button type="submit" class="btn btn-login mb-3 form-group" id="loginButton">
                        <span id="loginText">Masuk</span>
                    </button>

                    <div class="text-center form-group">
                        <p class="text-muted mb-0">Belum punya akun? 
                            <a href="<?php echo e(route('register')); ?>" class="text-decoration-none">
                                Daftar di sini
                            </a>
                        </p>
                    </div>
                </form>

                <div class="auth-footer">
                    <p>&copy; 2025 CariArena. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        function togglePasswordVisibility(inputId, toggleButton) {
            const passwordInput = document.getElementById(inputId);
            const icon = toggleButton.querySelector('i');
            const isVisible = passwordInput.type === 'text';
            
            passwordInput.type = isVisible ? 'password' : 'text';
            icon.classList.toggle('fa-eye', isVisible);
            icon.classList.toggle('fa-eye-slash', !isVisible);
            toggleButton.setAttribute('aria-pressed', !isVisible);
        }

        document.getElementById('passwordToggle').addEventListener('click', function() {
            togglePasswordVisibility('password', this);
        });

        // Validation functions - hanya menunjukkan error
        function validateEmail() {
            const emailInput = document.getElementById('email');
            const errorIcon = document.getElementById('emailErrorIcon');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailInput.value.trim()) {
                emailInput.classList.remove('is-valid');
                emailInput.classList.add('is-invalid');
                errorIcon.style.display = 'block';
                return false;
            } else if (!emailRegex.test(emailInput.value.trim())) {
                emailInput.classList.remove('is-valid');
                emailInput.classList.add('is-invalid');
                errorIcon.style.display = 'block';
                return false;
            } else {
                emailInput.classList.remove('is-invalid');
                emailInput.classList.remove('is-valid');
                errorIcon.style.display = 'none';
                return true;
            }
        }

        function validatePassword() {
            const passwordInput = document.getElementById('password');
            const errorIcon = document.getElementById('passwordErrorIcon');
            
            if (!passwordInput.value) {
                passwordInput.classList.remove('is-valid');
                passwordInput.classList.add('is-invalid');
                errorIcon.style.display = 'block';
                return false;
            } else {
                passwordInput.classList.remove('is-invalid');
                passwordInput.classList.remove('is-valid');
                errorIcon.style.display = 'none';
                return true;
            }
        }

        // Form submission loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const loginButton = document.getElementById('loginButton');
            const loginText = document.getElementById('loginText');
            
            // Validate all fields
            const isEmailValid = validateEmail();
            const isPasswordValid = validatePassword();
            
            // Check if all validations passed
            if (!isEmailValid || !isPasswordValid) {
                e.preventDefault();
                return;
            }
            
            // Show loading state
            loginButton.classList.add('btn-loading');
            loginText.textContent = 'Memproses...';
            loginButton.disabled = true;
        });

        // Auto-focus on email field if empty
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            if (!emailInput.value) {
                emailInput.focus();
            }
            
            // Add animation classes to form groups
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach(group => {
                group.style.animation = 'fadeInUp 0.6s ease-out both';
            });
            
            // Create particle effect
            createParticles();
            
            // Add real-time validation listeners
            document.getElementById('email').addEventListener('blur', validateEmail);
            document.getElementById('password').addEventListener('blur', validatePassword);
        });

        // Particle effect function
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 20;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random properties
                const size = Math.random() * 5 + 2;
                const posX = Math.random() * 100;
                const delay = Math.random() * 15;
                const duration = Math.random() * 10 + 10;
                
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${posX}%`;
                particle.style.animationDelay = `${delay}s`;
                particle.style.animationDuration = `${duration}s`;
                
                particlesContainer.appendChild(particle);
            }
        }

        // Keyboard navigation for accessibility
        document.addEventListener('keydown', function(e) {
            // Enter key to submit form
            if (e.key === 'Enter' && e.target.type !== 'textarea' && e.target.type !== 'submit') {
                e.preventDefault();
                document.getElementById('loginButton').click();
            }
        });
    </script>
</body>
</html><?php /**PATH D:\CariArena\resources\views/auth/login.blade.php ENDPATH**/ ?>