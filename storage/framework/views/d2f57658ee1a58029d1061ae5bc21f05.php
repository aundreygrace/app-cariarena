

<?php $__env->startSection('header'); ?>
<header class="main-header p-4 pt-4 pb-12 rounded-b-3xl">
    <!-- Ikon bola voli animasi menggelinding (hanya desktop) -->
    <i class="fas fa-volleyball-ball volleyball-icon icon-1" aria-hidden="true"></i>
    <i class="fas fa-volleyball-ball volleyball-icon icon-2" aria-hidden="true"></i>
    <i class="fas fa-volleyball-ball volleyball-icon icon-3" aria-hidden="true"></i>
    <i class="fas fa-volleyball-ball volleyball-icon icon-4" aria-hidden="true"></i>
    <i class="fas fa-volleyball-ball volleyball-icon icon-5" aria-hidden="true"></i>

    <div class="soft-shimmer"></div>

    <div class="max-w-7xl mx-auto hero-content">
        
        <!-- Top Bar (Tanpa tombol kembali) -->
        <div class="flex justify-end items-center mb-6 fade-in-up top-bar">
            <!-- Tombol Kembali dihapus sesuai permintaan -->
        </div>

        <!-- Konten Header Dinamis Berdasarkan Halaman -->
        <?php if(request()->routeIs('beranda')): ?>
        <!-- Header untuk Beranda -->
        <div class="center-header fade-in-up" style="animation-delay: 0.2s;">
            <!-- WRAPPER UNTUK BOLA VOLI -->
            <div class="volley-icon-wrapper" style="width: 100%; text-align: center; margin-bottom: 1rem;">
                <i class="fas fa-volleyball-ball volley-icon-main" aria-hidden="true" style="display: inline-block;"></i>
            </div>
            <h1 class="text-3xl font-semibold text-white mt-4 mb-4 text-center">Selamat Datang di CariArena!</h1>
            <p class="text-gray-100 text-sm mb-3 text-center">Temukan venue olahraga terbaik di sekitar kamu</p>
        </div>

        <!-- Search Bar Hanya di Beranda -->
        <div class="max-w-md mx-auto relative z-60 fade-in-up search-section" style="animation-delay: 0.3s;">
            <div class="search-bar mb-3 relative z-60">
                <input
                    type="text"
                    class="form-input placeholder-gray-500"
                    placeholder="Cari venue, olahraga, atau lokasi..."
                >
                <svg class="w-5 h-5 text-gray-500 relative z-60 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        <?php else: ?>
        <!-- Header untuk Halaman Lain - DENGAN WRAPPER YANG SAMA -->
        <div class="center-header fade-in-up" style="animation-delay: 0.2s;">
            <!-- WRAPPER UNTUK BOLA VOLI -->
            <div class="volley-icon-wrapper" style="width: 100%; text-align: center; margin-bottom: 1rem;">
                <i class="fas fa-volleyball-ball volley-icon-main" aria-hidden="true" style="display: inline-block;"></i>
            </div>
            
            <?php if(request()->routeIs('akun')): ?>
            <h1 class="text-3xl font-semibold text-white mt-4 mb-4 text-center">Profil Saya</h1>
            <p class="text-gray-100 text-sm mb-6 text-center">Kelola akun & preferensi anda</p>
            <?php elseif(request()->routeIs('pesan.index')): ?>
            <h1 class="text-3xl font-semibold text-white mt-4 mb-4 text-center">Pesan</h1>
            <p class="text-gray-100 text-sm mb-6 text-center">Kelola pemesanan venue olahraga</p>
            <?php elseif(request()->routeIs('riwayat')): ?>
            <h1 class="text-3xl font-semibold text-white mt-4 mb-4 text-center">Riwayat Pesanan</h1>
            <p class="text-gray-100 text-sm mb-6 text-center">Lihat dan kelola semua pesanan yang telah Anda buat</p>
            <?php elseif(request()->routeIs('pesan.riwayat-booking')): ?>
            <!-- TAMBAHKAN STYLE INLINE KHUSUS UNTUK HALAMAN DETAIL BOOKING -->
            <style>
                /* Fix tambahan untuk halaman detail booking */
                .main-header .center-header .volley-icon-wrapper {
                    position: relative !important;
                    left: 0 !important;
                    right: 0 !important;
                    margin-left: auto !important;
                    margin-right: auto !important;
                }
                
                .main-header .center-header .volley-icon-main {
                    position: relative !important;
                    left: 0 !important;
                    right: 0 !important;
                }
            </style>
            <h1 class="text-3xl font-semibold text-white mt-4 mb-4 text-center">Detail Booking</h1>
            <p class="text-gray-100 text-sm mb-6 text-center">Detail Lengkap Booking Venue Anda</p>
            <?php else: ?>
            <h1 class="text-3xl font-semibold text-white mt-4 mb-4 text-center"><?php echo $__env->yieldContent('title', 'CariArena'); ?></h1>
            <p class="text-gray-100 text-sm mb-6 text-center"><?php echo $__env->yieldContent('subtitle', ''); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
    </div>
</header>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<nav class="footer-floating">
    <div class="footer-nav">
        
        <!-- Tombol Beranda -->
        <!-- PHP logic dihapus karena sekarang dikontrol oleh JavaScript -->
        <a href="<?php echo e(route('beranda')); ?>" class="footer-menu" id="menu-beranda">
            <svg xmlns="http://www.w3.org/2000/svg" class="footer-icon" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span class="footer-label">Beranda</span>
        </a>

        <!-- Tombol Pesan -->
        <a href="<?php echo e(route('pesan.index')); ?>" class="footer-menu" id="menu-pesan">
            <svg xmlns="http://www.w3.org/2000/svg" class="footer-icon" viewBox="0 0 24 24">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <span class="footer-label">Pesan</span>
        </a>

        <!-- Tombol Riwayat -->
        <a href="<?php echo e(route('riwayat')); ?>" class="footer-menu" id="menu-riwayat">
            <svg xmlns="http://www.w3.org/2000/svg" class="footer-icon" viewBox="0 0 24 24">
                <path d="M21.5 2v6h-6"></path>
                <path d="M2.5 22v-6h6"></path>
                <path d="M20.42 12.59a9 9 0 1 1-9.9-9.9"></path>
            </svg>
            <span class="footer-label">Riwayat</span>
        </a>

        <!-- Tombol Akun -->
        <a href="<?php echo e(route('akun')); ?>" class="footer-menu" id="menu-akun">
            <svg xmlns="http://www.w3.org/2000/svg" class="footer-icon" viewBox="0 0 24 24">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span class="footer-label">Akun</span>
        </a>

    </div>
</nav>

<script>
    // Script tambahan untuk memastikan menu aktif sesuai halaman
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk menentukan halaman aktif
        function getActivePage() {
            const path = window.location.pathname;
            
            // Mapping path ke halaman
            if (path === '/' || path.includes('beranda')) return 'beranda';
            if (path.includes('pesan')) return 'pesan';
            if (path.includes('riwayat')) return 'riwayat';
            if (path.includes('akun')) return 'akun';
            
            return 'beranda';
        }
        
        // Set menu aktif berdasarkan halaman
        const activePage = getActivePage();
        const activeMenu = document.getElementById(`menu-${activePage}`);
        
        if (activeMenu) {
            activeMenu.classList.add('active');
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('user.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\CariArena\resources\views/layouts/user.blade.php ENDPATH**/ ?>