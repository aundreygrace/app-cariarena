

<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('role-text', 'Admin'); ?>
<?php $__env->startSection('page-title', 'Dashboard Admin'); ?>
<?php $__env->startSection('logout-route', route('logout')); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<ul class="nav-menu">
    <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    
    <li><a href="<?php echo e(route('admin.pengguna.index')); ?>" class="<?php echo e(request()->routeIs('admin.pengguna.*') ? 'active' : ''); ?>"><i class="fas fa-users"></i> Manajemen Pengguna</a></li>
    <li><a href="<?php echo e(route('admin.venue.index')); ?>" class="<?php echo e(request()->routeIs('admin.venue.*') ? 'active' : ''); ?>"><i class="fas fa-store"></i> Manajemen Venue</a></li>
    <li><a href="<?php echo e(route('admin.pemesanan.index')); ?>" class="<?php echo e(request()->routeIs('admin.pemesanan.*') ? 'active' : ''); ?>"><i class="fas fa-calendar-alt"></i> Manajemen Booking</a></li>
    <li><a href="<?php echo e(route('admin.transaksi.index')); ?>" class="<?php echo e(request()->routeIs('admin.transaksi.*') ? 'active' : ''); ?>"><i class="fas fa-credit-card"></i> Transaksi</a></li>
    <li><a href="<?php echo e(route('admin.laporan.index')); ?>" class="<?php echo e(request()->routeIs('admin.laporan.*') ? 'active' : ''); ?>"><i class="fas fa-chart-bar"></i> Laporan</a></li>
    <li><a href="<?php echo e(route('admin.pengaturan.index')); ?>" class="<?php echo e(request()->routeIs('admin.pengaturan.*') ? 'active' : ''); ?>"><i class="fas fa-cog"></i> Pengaturan</a></li>
</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('user-profile-link', "window.location.href='".route('admin.pengaturan.index')."'"); ?>

<?php $__env->startSection('user-profile'); ?>
<?php
    $userName = Auth::user()->name ?? 'Admin';
    $profileUrl = "https://ui-avatars.com/api/?name=" . urlencode($userName) . "&background=63B3ED&color=fff";
?>
<img src="<?php echo e($profileUrl); ?>" alt="Profile">
<span><?php echo e($userName); ?></span>
<i class="fas fa-chevron-right arrow-icon"></i>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\CariArena\resources\views/layouts/admin.blade.php ENDPATH**/ ?>