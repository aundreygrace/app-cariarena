@extends('layouts.app')

@section('title', 'Venue Dashboard')
@section('role-text', 'Venue')
@section('page-title', 'Dashboard Venue')
@section('logout-route', route('logout'))

@section('sidebar-menu')
<ul class="nav-menu">
    <li><a href="{{ route('venue.dashboard') }}" class="{{ request()->routeIs('venue.dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a></li>
    <li><a href="{{ route('venue.venue-saya') }}" class="{{ request()->routeIs('venue.venue-saya') ? 'active' : '' }}"><i class="fas fa-store"></i> Venue Saya</a></li>
    <li><a href="{{ route('venue.jadwal.index') }}" class="{{ request()->routeIs('venue.jadwal.*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> Jadwal</a></li>
    <li><a href="{{ route('venue.booking') }}" class="{{ request()->routeIs('venue.booking.*') ? 'active' : '' }}"><i class="fas fa-ticket-alt"></i> Booking Masuk</a></li>
    <li><a href="{{ route('venue.ulasan.index') }}" class="{{ request()->routeIs('venue.ulasan.*') ? 'active' : '' }}"><i class="fas fa-star"></i> Ulasan</a></li>
    <li><a href="{{ route('venue.reports') }}" class="{{ request()->routeIs('venue.reports') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
    <li><a href="{{ route('venue.pengaturan') }}" class="{{ request()->routeIs('venue.pengaturan') ? 'active' : '' }}"><i class="fas fa-cog"></i> Pengaturan</a></li>
</ul>
@endsection

@section('user-profile-link', "window.location.href='".route('venue.pengaturan')."'")

@section('user-profile')
@php
    $userName = Auth::user()->venue_name ?? Auth::user()->name ?? 'Venue Owner';
    $profileUrl = "https://ui-avatars.com/api/?name=" . urlencode($userName) . "&background=63B3ED&color=fff";
@endphp
<img src="{{ $profileUrl }}" alt="Profile">
<span class="user-name">{{ $userName }}</span>
<i class="fas fa-chevron-right arrow-icon"></i>
@endsection

@section('content')
<!-- Konten halaman venue akan diisi di sini -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <p>Konten dashboard venue akan ditampilkan di sini.</p>
        </div>
    </div>
</div>
@endsection