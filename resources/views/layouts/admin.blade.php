@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('role-text', 'Admin')
@section('page-title', 'Dashboard Admin')
@section('logout-route', route('logout'))

@section('sidebar-menu')
<ul class="nav-menu">
    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    
    <li><a href="{{ route('admin.pengguna.index') }}" class="{{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}"><i class="fas fa-users"></i> Manajemen Pengguna</a></li>
    <li><a href="{{ route('admin.venue.index') }}" class="{{ request()->routeIs('admin.venue.*') ? 'active' : '' }}"><i class="fas fa-store"></i> Manajemen Venue</a></li>
    <li><a href="{{ route('admin.pemesanan.index') }}" class="{{ request()->routeIs('admin.pemesanan.*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> Manajemen Booking</a></li>
    <li><a href="{{ route('admin.transaksi.index') }}" class="{{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}"><i class="fas fa-credit-card"></i> Transaksi</a></li>
    <li><a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
    <li><a href="{{ route('admin.pengaturan.index') }}" class="{{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}"><i class="fas fa-cog"></i> Pengaturan</a></li>
</ul>
@endsection

@section('user-profile-link', "window.location.href='".route('admin.pengaturan.index')."'")

@section('user-profile')
@php
    $userName = Auth::user()->name ?? 'Admin';
    $profileUrl = "https://ui-avatars.com/api/?name=" . urlencode($userName) . "&background=63B3ED&color=fff";
@endphp
<img src="{{ $profileUrl }}" alt="Profile">
<span>{{ $userName }}</span>
<i class="fas fa-chevron-right arrow-icon"></i>
@endsection