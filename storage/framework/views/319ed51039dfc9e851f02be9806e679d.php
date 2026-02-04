<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - CariArena</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4A90E2;
            --primary-hover: #6AA7EC;
            --card-bg: #FFFFFF;
            --border-radius: 12px;
            --text-light: #718096;
            --success: #48BB78;
            --info: #2C5AA0;
        }

        * { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        body {
            margin: 0;
            overflow-x: hidden;
        }

        .auth-container {
            min-height: 100vh;
            background: linear-gradient(120deg, #dff3ff 0%, #e8f4ff 40%, #b7dbff 80%, #a0c4ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 20px;
            overflow: hidden;
        }

        /* Ikon bola voli animasi */
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

        .icon-1 { font-size: 5rem; top: 10%; left: 5%; animation-name: bounce-1; }
        .icon-2 { font-size: 3.5rem; bottom: 15%; right: 8%; animation-name: bounce-2; }
        .icon-3 { font-size: 2.5rem; top: 60%; left: 8%; animation-name: bounce-3; }
        .icon-4 { font-size: 4rem; bottom: 10%; left: 15%; animation-name: bounce-4; }
        .icon-5 { font-size: 3rem; top: 15%; right: 10%; animation-name: bounce-5; }

        @keyframes bounce-1 { 0%,100%{transform:translate(0,0)}50%{transform:translate(150px,-40px)} }
        @keyframes bounce-2 { 0%,100%{transform:translate(0,0)}50%{transform:translate(-160px,50px)} }
        @keyframes bounce-3 { 0%,100%{transform:translate(0,0)}50%{transform:translate(150px,-30px)} }
        @keyframes bounce-4 { 0%,100%{transform:translate(0,0)}50%{transform:translate(-120px,60px)} }
        @keyframes bounce-5 { 0%,100%{transform:translate(0,0)}50%{transform:translate(140px,70px)} }

        /* Particle effect */
        .particles { position: absolute; top:0; left:0; width:100%; height:100%; z-index:0; overflow:hidden; }

        .particle {
            position: absolute;
            background: rgba(255,255,255,0.7);
            border-radius: 50%;
            animation: particleFloat 15s infinite linear;
        }

        @keyframes particleFloat {
            0% {transform:translateY(100vh) rotate(0deg); opacity:0;}
            10% {opacity:1;}
            90% {opacity:1;}
            100% {transform:translateY(-100px) rotate(360deg); opacity:0;}
        }

        .auth-card {
            background: var(--card-bg);
            border-radius: 20px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            position: relative;
            z-index:1;
            overflow:hidden;
            animation: cardAppear 1s ease-out;
        }

        @keyframes cardAppear { 0%{opacity:0; transform:scale(0.8) translateY(50px);} 70%{transform:scale(1.02) translateY(-10px);} 100%{opacity:1; transform:scale(1) translateY(0);} }

        .auth-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-header i { font-size: 3rem; margin-bottom: 15px; display: block; animation: iconPulse 2s infinite ease-in-out; }
        @keyframes iconPulse { 0%,100%{transform:scale(1);}50%{transform:scale(1.1);} }

        .auth-header h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            margin:0;
            font-size:2.2rem;
            line-height:1.2;
        }

        .auth-header p { margin:10px 0 0 0; opacity:0.9; font-size:1rem ; font-weight: 600;}

        .auth-body { padding:30px; text-align:center; }

        button {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            border:none;
            border-radius: var(--border-radius);
            padding:14px;
            font-weight:600;
            font-size:1rem;
            color:white;
            cursor:pointer;
            width:100%;
            transition: all 0.4s ease;
        }

        button:hover { opacity:0.9; }

        .alert {
            border-radius: var(--border-radius);
            padding:12px 16px;
            font-size:0.95rem;
            margin-bottom:20px;
        }

        .alert-info { background-color:#BEE3F8; color:var(--info); border-left:4px solid #4299E1; }

        .small-text { margin-top:20px; font-size:0.85rem; color:var(--text-light); }

        @media(max-width:480px){
            .auth-card{margin:10px;}
            .auth-header{padding:25px 20px;}
            .auth-body{padding:25px 20px;}
            .auth-header i{font-size:2.5rem;}
            .auth-header h1{font-size:1.8rem;}
            .volleyball-icon{display:none;}
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Ikon bola voli animasi -->
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
                <p>Verifikasi Email Anda</p>
            </div>

            <div class="auth-body">
                <?php if(session('message')): ?>
                    <div class="alert alert-info">
                        <?php echo e(session('message')); ?>

                    </div>
                <?php endif; ?>

                <p>Kami telah mengirimkan email verifikasi ke alamat email Anda.<br>
                Silakan buka email tersebut dan klik link verifikasi untuk melanjutkan.</p>

                <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit">Kirim Ulang Email Verifikasi</button>
                </form>

                <p class="small-text">
                    Tidak menerima email? Coba cek folder spam atau tekan tombol di atas untuk mengirim ulang.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Particle effect
        function createParticles() {
            const container = document.getElementById('particles');
            const count = 20;
            for(let i=0;i<count;i++){
                const p = document.createElement('div');
                p.className = 'particle';
                const size = Math.random()*5+2;
                const posX = Math.random()*100;
                const delay = Math.random()*15;
                const duration = Math.random()*10+10;
                p.style.width = `${size}px`;
                p.style.height = `${size}px`;
                p.style.left = `${posX}%`;
                p.style.animationDelay = `${delay}s`;
                p.style.animationDuration = `${duration}s`;
                container.appendChild(p);
            }
        }
        document.addEventListener('DOMContentLoaded', createParticles);
    </script>
</body>
</html>
<?php /**PATH D:\CariArena\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>