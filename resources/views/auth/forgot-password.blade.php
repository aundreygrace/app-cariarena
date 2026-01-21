<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - CariArena</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- COPY SEMUA CSS DARI forgot-password.blade -->
    <style>
        /* --- ⛔ COPY-PASTE TANPA DIUBAH (PERSIS) --- */
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
            --admin-color: #667eea;
            --venue-color: #10b981;
        }
        *{font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;}
        body{overflow-x:hidden;}
        .login-container{
            min-height:100vh;
            background:linear-gradient(120deg,#dff3ff 0%,#e8f4ff 40%,#b7dbff 80%,#a0c4ff 100%);
            display:flex;align-items:center;justify-content:center;
            padding:20px;position:relative;overflow:hidden;
        }
        .login-container::before{
            content:'';position:absolute;top:-10%;left:-20%;
            width:80%;height:80%;
            background:radial-gradient(circle at top right,rgba(102,204,255,.6),transparent 70%);
            filter:blur(100px);z-index:0;
        }
        .login-container::after{
            content:'';position:absolute;bottom:-10%;right:-10%;
            width:70%;height:70%;
            background:radial-gradient(circle at bottom left,rgba(0,102,255,.5),transparent 70%);
            filter:blur(120px);z-index:0;
        }
        .volleyball-icon{position:absolute;color:white;text-shadow:0 2px 10px rgba(0,0,0,.2);animation:bounce 20s infinite linear;z-index:0;display:flex;align-items:center;justify-content:center;}
        .icon-1{font-size:5rem;animation-name:bounce-1;top:10%;left:5%;}
        .icon-2{font-size:3.5rem;animation-name:bounce-2;bottom:15%;right:8%;}
        .icon-3{font-size:2.5rem;animation-name:bounce-3;top:60%;left:8%;}
        .icon-4{font-size:4rem;animation-name:bounce-4;bottom:10%;left:15%;}
        .icon-5{font-size:3rem;animation-name:bounce-5;top:15%;right:10%;}
        /* (semua keyframes tetap sama) */
        @keyframes bounce-1{0%,100%{transform:translate(0,0) rotate(0deg);}10%{transform:translate(30px,-80px) rotate(36deg);}20%{transform:translate(60px,20px) rotate(72deg);}30%{transform:translate(90px,-60px) rotate(108deg);}40%{transform:translate(120px,40px) rotate(144deg);}50%{transform:translate(150px,-40px) rotate(180deg);}60%{transform:translate(180px,60px) rotate(216deg);}70%{transform:translate(210px,-20px) rotate(252deg);}80%{transform:translate(240px,80px) rotate(288deg);}90%{transform:translate(270px,0px) rotate(324deg);}}
        @keyframes bounce-2{0%,100%{transform:translate(0,0) rotate(0);}12.5%{transform:translate(-40px,-70px) rotate(45deg);}25%{transform:translate(-80px,30px) rotate(90deg);}37.5%{transform:translate(-120px,-50px) rotate(135deg);}50%{transform:translate(-160px,50px) rotate(180deg);}62.5%{transform:translate(-200px,-30px) rotate(225deg);}75%{transform:translate(-240px,70px) rotate(270deg);}87.5%{transform:translate(-280px,10px) rotate(315deg);}}
        @keyframes bounce-3{0%,100%{transform:translate(0,0);}}
        @keyframes bounce-4{0%,100%{transform:translate(0,0);}}
        @keyframes bounce-5{0%,100%{transform:translate(0,0);}}

        .toggle-password {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    cursor: pointer;
    color: #718096;
    font-size: 1.1rem;
    transition: color .3s ease;
}

.toggle-password:hover {
    color: var(--primary-color);
}

        .login-card{
            background:var(--card-bg);
            border-radius:20px;
            box-shadow:0 20px 60px rgba(0,0,0,.1);
            width:100%;max-width:450px;overflow:hidden;
            position:relative;z-index:1;
            backdrop-filter:blur(10px);
            animation:cardAppear 1s ease-out;
        }

        @keyframes cardAppear{
            0%{opacity:0;transform:scale(.8) translateY(50px);}
            100%{opacity:1;transform:scale(1);}
        }

        .login-header{
            background:linear-gradient(135deg,var(--primary-color) 0%,var(--primary-hover) 100%);
            color:white;padding:30px;text-align:center;
        }
        .login-header i{font-size:3rem;margin-bottom:15px;}

        .login-body{padding:30px;}
        .form-control{border:2px solid #E2E8F0;border-radius:12px;padding:12px 16px;}
        .form-control:focus{border-color:var(--primary-color);box-shadow:0 0 0 3px rgba(74,144,226,.2);}

        .btn-login{
            background:linear-gradient(135deg,var(--primary-color),var(--primary-hover));
            border:none;border-radius:12px;padding:14px;
            font-weight:600;color:white;width:100%;
            box-shadow:0 5px 15px rgba(74,144,226,.3);
        }

        .login-footer{text-align:center;margin-top:20px;padding-top:20px;border-top:1px solid #E2E8F0;color:#999;font-size:.85rem;}
    </style>
</head>

<body>
<div class="login-container">

    <!-- Animated Icons -->
    <i class="fas fa-volleyball-ball volleyball-icon icon-1"></i>
    <i class="fas fa-volleyball-ball volleyball-icon icon-2"></i>
    <i class="fas fa-volleyball-ball volleyball-icon icon-3"></i>
    <i class="fas fa-volleyball-ball volleyball-icon icon-4"></i>
    <i class="fas fa-volleyball-ball volleyball-icon icon-5"></i>

    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-unlock-alt"></i>
            <h3>Buat Password Baru</h3>
            <p>Sistem Manajemen Venue Booking</p>
        </div>

        <div class="login-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-lock me-2"></i>Password Baru
                    </label>
                        <div class="position-relative">
                            <input type="password" name="password" id="password" class="form-control" 
                                required minlength="6" placeholder="Masukkan password baru">
                        
                        <!-- Eye -->
                            <span class="toggle-password" onclick="togglePassword('password', this)">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-lock me-2"></i>Konfirmasi Password
                    </label>
                        <div class="position-relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                    class="form-control" required minlength="6" placeholder="Ulangi password baru">
                            <!-- Eye -->
                            <span class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                </div>

                <button class="btn btn-login">Reset Password</button>
            </form>

            <div class="nav-links mt-3 text-center">
                <a href="{{ route('login') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Login
                </a>
            </div>

            <div class="login-footer">
                <p>&copy; 2025 CariArena. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>
<script>
function togglePassword(id, el) {
    const input = document.getElementById(id);
    const icon = el.querySelector("i");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>
</body>
</html>