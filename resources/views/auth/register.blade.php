<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - CariArena</title>
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

        .auth-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 480px;
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

        .btn-register {
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

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.7s;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(74, 144, 226, 0.4);
        }

        .btn-register:hover::before {
            left: 100%;
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

        .form-group:nth-child(3) {
            animation-delay: 0.3s;
        }

        .nav-links {
            animation-delay: 0.4s;
        }

        .btn-register {
            animation-delay: 0.5s;
        }

        .text-center {
            animation-delay: 0.6s;
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

        /* Progress bar untuk form */
        .progress-container {
            margin-bottom: 25px;
        }

        .progress {
            height: 6px;
            border-radius: 3px;
            background-color: #E2E8F0;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--primary-color), var(--primary-hover));
            transition: width 0.5s ease;
            border-radius: 3px;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            font-size: 0.75rem;
            color: var(--text-light);
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* TIDAK ADA LAGI GARIS YANG MELINTASI TENGAH TULISAN */
        /* Bagian ini yang telah dihapus:
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 0;
            width: 100%;
            height: 1px;
            background-color: #E2E8F0;
            z-index: -1;
        }
        */
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <i class="fas fa-user-plus"></i>
                <h3>Daftar Akun Baru</h3>
                <p>Buat akun baru untuk bergabung dengan CariArena</p>
            </div>

            <div class="auth-body">
                <!-- Alert messages -->
                <div id="alertContainer"></div>

                <!-- Progress Bar - Sekarang hanya 3 step -->
                <div class="progress-container">
                    <div class="progress">
                        <div class="progress-bar" id="formProgress" style="width: 33%"></div>
                    </div>
                    <div class="progress-steps">
                        <div class="step active" id="step1">Data Diri</div>
                        <div class="step" id="step2">Data Akun</div>
                        <div class="step" id="step3">Konfirmasi</div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                
                <form method="POST" action="{{ route('register.submit') }}" id="registerForm">
                @csrf
                    <!-- Input hidden untuk role tetap ada (default: user) -->
                    <input type="hidden" name="role" id="selectedRole" value="user">

                    <!-- Data Diri Section - Langsung ditampilkan -->
                    <div class="form-section" id="section1">
                        <div class="mb-4 form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-2"></i>Nama Lengkap
                            </label>
                            <div class="input-animation">
                                <input type="text" class="form-control" 
                                       id="name" name="name" 
                                       placeholder="masukkan nama lengkap" required>
                            </div>
                        </div>

                        <div class="mb-4 form-group">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-2"></i>Nomor Telepon
                            </label>
                            <div class="input-animation">
                                <input type="tel" class="form-control" 
                                       id="phone" name="phone" 
                                       placeholder="masukkan nomor telepon" required>
                            </div>
                        </div>

                        <div class="mb-4 form-group" id="addressField">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-2"></i>Alamat
                            </label>
                            <div class="input-animation">
                                <textarea class="form-control" 
                                          id="address" name="address" rows="3" 
                                          placeholder="masukkan alamat lengkap" required></textarea>
                            </div>
                        </div>

                        <button type="button" class="btn btn-register mb-3 form-group" id="nextToSection2">
                            <span>Lanjut ke Data Akun</span>
                        </button>
                    </div>

                    <!-- Data Akun Section -->
                    <div class="form-section" id="section2" style="display: none;">
                        <div class="mb-4 form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Alamat Email
                            </label>
                            <div class="input-animation">
                                <input type="email" class="form-control" 
                                       id="email" name="email" 
                                       placeholder="masukkan email anda" required>
                            </div>
                        </div>

                        <div class="mb-4 form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <div class="input-animation">
                                <div class="input-group">
                                    <input type="password" class="form-control" 
                                           id="password" name="password" placeholder="masukkan password (min. 8 karakter)" required>
                                    <button type="button" class="password-toggle" id="passwordToggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 form-group">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock me-2"></i>Konfirmasi Password
                            </label>
                            <div class="input-animation">
                                <div class="input-group">
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="ulangi password anda" required>
                                    <button type="button" class="password-toggle" id="confirmPasswordToggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="nav-links form-group">
                            <button type="button" class="btn btn-outline-secondary" id="backToSection1">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </button>
                            <button type="button" class="btn btn-register" id="nextToSection3">
                                <span>Lanjut ke Konfirmasi</span>
                            </button>
                        </div>
                    </div>

                    <!-- Konfirmasi Section -->
                    <div class="form-section" id="section3" style="display: none;">
                        <div class="mb-4 form-group">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Konfirmasi Data Pendaftaran</strong>
                                <p class="mb-0 mt-2">Pastikan data yang Anda masukkan sudah benar sebelum mengirimkan pendaftaran.</p>
                            </div>
                        </div>

                        <div class="mb-3 form-group">
                            <h6 class="text-muted mb-3">Data Diri:</h6>
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <p class="mb-2"><strong>Nama:</strong> <span id="confirmName"></span></p>
                                    <p class="mb-2"><strong>Telepon:</strong> <span id="confirmPhone"></span></p>
                                    <p class="mb-0"><strong>Alamat:</strong> <span id="confirmAddress"></span></p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 form-group">
                            <h6 class="text-muted mb-3">Data Akun:</h6>
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <p class="mb-2"><strong>Email:</strong> <span id="confirmEmail"></span></p>
                                    <p class="mb-0"><strong>Password:</strong> ********</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" 
                                       id="terms" name="terms" value="1" required>
                                <label class="form-check-label" for="terms">
                                    Saya menyetujui 
                                    <a href="#" class="text-decoration-none">Syarat & Ketentuan</a> 
                                    dan 
                                    <a href="#" class="text-decoration-none">Kebijakan Privasi</a>
                                </label>
                            </div>
                        </div>

                        <div class="nav-links form-group">
                            <button type="button" class="btn btn-outline-secondary" id="backToSection2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </button>
                            <button type="submit" class="btn btn-register" id="registerButton">
                                <span id="registerText">Daftar Sekarang</span>
                            </button>
                        </div>
                    </div>

                    <div class="text-center form-group">
                        <p class="text-muted mb-0">Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                Masuk di sini
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
document.addEventListener('DOMContentLoaded', function () {

    // Debug mode
    const DEBUG = true;

    // =====================
    // TOGGLE PASSWORD
    // =====================
    document.getElementById('passwordToggle').addEventListener('click', function() {
        const input = document.getElementById('password');
        const icon = this.querySelector('i');

        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });

    document.getElementById('confirmPasswordToggle').addEventListener('click', function() {
        const input = document.getElementById('password_confirmation');
        const icon = this.querySelector('i');

        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });

    // =====================
    // MULTI STEP FORM
    // =====================
    let currentSection = 1;
    const totalSections = 3;

    function updateProgress() {
        const progress = (currentSection / totalSections) * 100;
        document.getElementById('formProgress').style.width = progress + '%';

        for (let i = 1; i <= totalSections; i++) {
            document.getElementById('step' + i)
                .classList.toggle('active', i <= currentSection);
        }
    }

    function showSection(num) {
        document.querySelectorAll('.form-section')
            .forEach(s => s.style.display = 'none');

        document.getElementById('section' + num).style.display = 'block';
        currentSection = num;
        updateProgress();
    }

    // =====================
    // NAVIGATION
    // =====================
    document.getElementById('nextToSection2').addEventListener('click', function () {
    const nameValue = document.getElementById('name').value.trim();
    const phoneValue = document.getElementById('phone').value.trim();
    const addressValue = document.getElementById('address').value.trim();

    if (!nameValue || !phoneValue || !addressValue) {
        showAlert('Harap lengkapi semua data diri terlebih dahulu.', 'danger');
        return;
    }

    showSection(2);
});


document.getElementById('nextToSection3').addEventListener('click', function () {

const nameInput = document.getElementById('name').value.trim();
const phoneInput = document.getElementById('phone').value.trim();
const addressInput = document.getElementById('address').value.trim();
const emailInput = document.getElementById('email').value.trim();
const passwordInput = document.getElementById('password').value;
const confirmPasswordInput = document.getElementById('password_confirmation').value;

if (!emailInput || !passwordInput || !confirmPasswordInput) {
    showAlert('Harap lengkapi semua data akun terlebih dahulu.', 'danger');
    return;
}

if (passwordInput !== confirmPasswordInput) {
    showAlert('Konfirmasi password tidak sesuai.', 'danger');
    return;
}

if (passwordInput.length < 8) {
    showAlert('Password minimal 8 karakter.', 'danger');
    return;
}

// ✅ UPDATE KONFIRMASI DI SINI (SAAT DIKLIK)
document.getElementById('confirmName').textContent = nameInput;
document.getElementById('confirmPhone').textContent = phoneInput;
document.getElementById('confirmAddress').textContent = addressInput;
document.getElementById('confirmEmail').textContent = emailInput;

showSection(3);
});


    document.getElementById('backToSection1').onclick = () => showSection(1);
    document.getElementById('backToSection2').onclick = () => showSection(2);

    // =====================
    // ALERT
    // =====================
    function showAlert(msg, type) {
        alertContainer.innerHTML = `
            <div class="alert alert-${type}">
                ${msg}
            </div>`;
        setTimeout(() => alertContainer.innerHTML = '', 4000);
    }

    updateProgress();
});
</script>

</body>
</html>