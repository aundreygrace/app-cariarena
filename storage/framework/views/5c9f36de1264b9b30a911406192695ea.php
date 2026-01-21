

<?php $__env->startSection('title', 'Venue Dashboard'); ?>
<?php $__env->startSection('role-text', 'Venue'); ?>
<?php $__env->startSection('page-title', 'Dashboard Venue'); ?>
<?php $__env->startSection('logout-route', route('logout')); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<ul class="nav-menu">
    <li><a href="<?php echo e(route('venue.dashboard')); ?>" class="<?php echo e(request()->routeIs('venue.dashboard') ? 'active' : ''); ?>"><i class="fas fa-home"></i> Dashboard</a></li>
    <li><a href="<?php echo e(route('venue.venue-saya')); ?>" class="<?php echo e(request()->routeIs('venue.venue-saya') ? 'active' : ''); ?>"><i class="fas fa-store"></i> Venue Saya</a></li>
    <li><a href="<?php echo e(route('venue.jadwal.index')); ?>" class="<?php echo e(request()->routeIs('venue.jadwal.*') ? 'active' : ''); ?>"><i class="fas fa-calendar-alt"></i> Jadwal</a></li>
    <li><a href="<?php echo e(route('venue.booking')); ?>" class="<?php echo e(request()->routeIs('venue.booking.*') ? 'active' : ''); ?>"><i class="fas fa-ticket-alt"></i> Booking Masuk</a></li>
    <li><a href="<?php echo e(route('venue.ulasan.index')); ?>" class="<?php echo e(request()->routeIs('venue.ulasan.*') ? 'active' : ''); ?>"><i class="fas fa-star"></i> Ulasan</a></li>
    <li><a href="<?php echo e(route('venue.reports')); ?>" class="<?php echo e(request()->routeIs('venue.reports') ? 'active' : ''); ?>"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
    <li><a href="<?php echo e(route('venue.pengaturan')); ?>" class="<?php echo e(request()->routeIs('venue.pengaturan') ? 'active' : ''); ?>"><i class="fas fa-cog"></i> Pengaturan</a></li>
</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('user-profile-link', "window.location.href='".route('venue.pengaturan')."'"); ?>

<?php $__env->startSection('user-profile'); ?>
<?php
    $userName = Auth::user()->venue_name ?? Auth::user()->name ?? 'Venue Owner';
    $profileUrl = "https://ui-avatars.com/api/?name=" . urlencode($userName) . "&background=63B3ED&color=fff";
?>
<img src="<?php echo e($profileUrl); ?>" alt="Profile">
<span class="user-name"><?php echo e($userName); ?></span>
<i class="fas fa-chevron-right arrow-icon"></i>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Konten halaman venue akan diisi di sini -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <p>Konten dashboard venue akan ditampilkan di sini.</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\CariArena\resources\views/layouts/venue.blade.php ENDPATH**/ ?>