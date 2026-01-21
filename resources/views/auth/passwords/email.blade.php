<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - CariArena</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            max-width: 420px;
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

        .auth-header h3 {
            font-weight: 700;
            margin: 0;
            font-size: 1.8rem;
            animation: textGlow 3s infinite alternate;
        }

        @keyframes textGlow {
            0% {
                text-shadow: 0 0 5px rgba(255,255,255,0.5);
            }
            100% {
                text-shadow: 0 0 15px rgba(255,255,255,0.8);
            }
        }

        .auth-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
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

        .btn-reset {
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

        .btn-reset::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.7s;
        }

        .btn-reset:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(74, 144, 226, 0.4);
        }

        .btn-reset:hover::before {
            left: 100%;
        }

        .btn-reset:active {
            transform: translateY(-1px);
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
            
            .auth-header h3 {
                font-size: 1.5rem;
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
            justify-content: center;
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

        .btn-reset {
            animation-delay: 0.4s;
        }

        .text-center {
            animation-delay: 0.5s;
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

        /* Success state styles */
        .success-state {
            text-align: center;
            padding: 20px 0;
        }

        .success-icon {
            font-size: 4rem;
            color: var(--success);
            margin-bottom: 20px;
            animation: successScale 0.6s ease-out;
        }

        @keyframes successScale {
            0% {
                transform: scale(0);
            }
            70% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .success-state h4 {
            color: var(--success);
            margin-bottom: 15px;
            font-weight: 600;
        }

        .success-state p {
            color: var(--text-light);
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .countdown {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Ikon bola voli dengan berbagai ukuran -->
        <i class="fas fa-volleyball-ball volleyball-icon icon-1"></i>
        <i class="fas fa-volleyball-ball volleyball-icon icon-2"></i>
        <i class="fas fa-volleyball-ball volleyball-icon icon-3"></i>
        <i class="fas fa-volleyball-ball volleyball-icon icon-4"></i>
        <i class="fas fa-volleyball-ball volleyball-icon icon-5"></i>
        
        <!-- Particle effect -->
        <div class="particles" id="particles"></div>
        
        <div class="auth-card">
            <div class="auth-header">
                <i class="fas fa-key"></i>
                <h3>Reset Password</h3>
                <p>Masukkan email untuk reset password</p>
            </div>

            <div class="auth-body">
            <input type="hidden" id="hiddenEmail" value="{{ $email ?? '' }}">
                <!-- Alert messages -->
                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('status'))
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <!-- Reset Password Form -->
                <div id="resetForm">
                    <div class="mb-4 form-group">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Instruksi Reset Password</strong>
                            <p class="mb-0 mt-2">Masukkan alamat email yang terdaftar. Kami akan mengirimkan link untuk reset password.</p>
                        </div>
                    </div>

                    <!-- PERBAIKAN: Form action menggunakan route yang benar -->
                    <form method="POST" action="{{ route('password.email') }}" id="passwordResetForm">
                        @csrf
                        
                        <div class="mb-4 form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Alamat Email
                            </label>
                            <div class="input-animation">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="masukkan email terdaftar" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-reset mb-3 form-group" id="resetButton">
                            <span id="resetText">Kirim Link Reset</span>
                        </button>
                    </form>

                    <div class="nav-links form-group">
                        <!-- PERBAIKAN: Link kembali ke login yang benar -->
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Login
                        </a>
                    </div>
                </div>

                <!-- Success State (akan ditampilkan via JavaScript) -->
                <div id="successState" class="success-state" style="display: none;">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h4>Link Reset Terkirim!</h4>
                    <p>Kami telah mengirimkan link reset password ke email Anda. Silakan periksa inbox email dan ikuti instruksi yang diberikan.</p>
                    <div class="alert alert-info">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Perhatian:</strong> Link reset password akan kadaluarsa dalam 
                        <span class="countdown" id="countdown">05:00</span>
                    </div>
                    <button type="button" class="btn btn-outline-primary mt-3" id="resendButton">
                        <i class="fas fa-redo me-2"></i>Kirim Ulang Link
                    </button>
                </div>

                <div class="auth-footer">
                    <p>&copy; 2025 CariArena. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const emailValue = document.getElementById('hiddenEmail').value;
        // Form submission loading state
        document.getElementById('passwordResetForm').addEventListener('submit', function(e) {
            const resetButton = document.getElementById('resetButton');
            const resetText = document.getElementById('resetText');
            
            // Show loading state
            resetButton.classList.add('btn-loading');
            resetText.textContent = 'Mengirim...';
            resetButton.disabled = true;
        });

        // Show success state when form is successfully submitted
        function showSuccessState() {
            document.getElementById('resetForm').style.display = 'none';
            document.getElementById('successState').style.display = 'block';
            
            // Start countdown timer
            startCountdown(5 * 60); // 5 minutes
        }

        // Countdown timer
        function startCountdown(duration) {
            const countdownElement = document.getElementById('countdown');
            let timer = duration, minutes, seconds;
            
            const interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);
                
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                
                countdownElement.textContent = minutes + ":" + seconds;
                
                if (--timer < 0) {
                    clearInterval(interval);
                    countdownElement.textContent = "00:00";
                    countdownElement.style.color = "var(--danger)";
                }
            }, 1000);
        }

document.getElementById('resendButton')?.addEventListener('click', function () {
    const resendButton = this;
    resendButton.disabled = true;
    resendButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';

    // Kirim ulang request reset password
    const emailValue = "{{ $email ?? '' }}"; // ambil dari server

    fetch("{{ route('password.email') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ email: emailValue })
    })
    .then(res => res.json())
    .then(() => {
        resendButton.disabled = false;
        resendButton.innerHTML = '<i class="fas fa-redo me-2"></i>Kirim Ulang Link';

        // Tampilkan pesan sukses
        alert('Link reset password telah dikirim ulang!');
        startCountdown(5 * 60);
    })
    .catch(() => {
        resendButton.disabled = false;
        resendButton.innerHTML = '<i class="fas fa-redo me-2"></i>Kirim Ulang Link';

        alert('Gagal mengirim ulang. Coba lagi.');
    });
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
            
            // Check if there's a success message and show success state
            if (document.querySelector('.alert-success')) {
                setTimeout(showSuccessState, 1000);
            }
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

        // Email validation
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });

        // Real-time form validation
        document.getElementById('email').addEventListener('input', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>