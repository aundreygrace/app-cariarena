@extends('layouts.venue')

@section('title', 'Jadwal Booking')

@section('page-title', 'Jadwal Booking')

@section('content')
<p>Kelola jadwal booking venue Anda</p>

<div class="schedule-container">
    <!-- Kiri -->
    <div class="left-panel">
        <h3>Pilih Tanggal</h3>
        <div class="calendar">
            <div class="calendar-header">
                <button id="prev-month">&lt;</button>
                <span id="current-month-year">Oktober 2025</span>
                <button id="next-month">&gt;</button>
            </div>
            
            <div class="weekdays">
                <div>Su</div>
                <div>Mo</div>
                <div>Tu</div>
                <div>We</div>
                <div>Th</div>
                <div>Fr</div>
                <div>Sa</div>
            </div>
            
            <div class="calendar-grid" id="calendar-days">
                <!-- Days will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Kanan -->
    <div class="right-panel">
        <div class="right-panel-header">
            <div class="day-title">
                <span id="selected-date-title">Selasa, 14 Oktober 2025</span>
                {{-- PERBAIKAN: Perbaiki route name --}}
                <button class="btn-atur-jadwal" id="btnAturJadwal" onclick="window.location.href='{{ route('venue.jadwal.atur') }}'">
                    <i class="fas fa-calendar-alt"></i>
                    Atur Jadwal
                </button>
            </div>
        </div>
        <div class="right-panel-content" id="booking-slots">
            <!-- PERBAIKAN: Tambahkan loading indicator -->
            <div id="loading-slots" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat jadwal...</p>
            </div>
        </div>
    </div>
</div>

<!-- PERBAIKAN: Summary Cards dengan Data Real -->
<div class="summary-cards">
    <div class="summary-card">
        <div class="stat-number">{{ $totalBookings ?? 0 }}</div>
        <div class="stat-label">Total Booking</div>
    </div>
    <div class="summary-card">
        <div class="stat-number">{{ $confirmedBookings ?? 0 }}</div>
        <div class="stat-label">Dikonfirmasi</div>
    </div>
    <div class="summary-card">
        <div class="stat-number">{{ $pendingBookings ?? 0 }}</div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="summary-card">
        <div class="stat-number">Rp {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</div>
        <div class="stat-label">Pendapatan Hari Ini</div>
    </div>
    <!-- PERBAIKAN: Tambahkan card untuk total pendapatan -->
    <div class="summary-card">
        <div class="stat-number">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
        <div class="stat-label">Total Pendapatan</div>
    </div>
</div>

<!-- Modal Detail Booking --> 
<div class="modal fade" id="detailBookingModal" tabindex="-1" aria-labelledby="detailBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="detailBookingModalLabel">Detail Jadwal Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="detail-booking-item">
                    <span class="detail-label">Nama</span>
                    <span class="detail-value" id="detail-nama">-</span>
                </div>
                <div class="detail-booking-item">
                    <span class="detail-label">Tempat</span>
                    <span class="detail-value" id="detail-tempat">-</span>
                </div>
                <div class="detail-booking-item">
                    <span class="detail-label">Tanggal Booking</span>
                    <span class="detail-value" id="detail-tanggal">-</span>
                </div>
                <div class="detail-booking-item">
                    <span class="detail-label">Durasi</span>
                    <span class="detail-value" id="detail-durasi">-</span>
                </div>
                <div class="detail-booking-item">
                    <span class="detail-label">Biaya Booking</span>
                    <span class="detail-value" id="detail-biaya">-</span>
                </div>
                {{-- PERBAIKAN: Tambahkan status --}}
                <div class="detail-booking-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" id="detail-status">-</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --navy: #1E40AF;
        --blusky: #60A5FA;
    }

    /* Judul halaman */
    .main h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.25rem;
    }

    .main p {
        color: var(--text-light);
        margin-bottom: 1.5rem;
    }

    /* Container besar */
    .schedule-container {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        width: 100%;
        min-width: 0;
        max-width: 100%;
        align-items: stretch;
    }

    /* Panel kiri (kalender)*/
    .left-panel {
        flex: 0 0 320px;
        background: var(--card-bg);
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        color: var(--text-dark);
    }

    .calendar-header button {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: var(--text-light);
        padding: 5px 10px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .calendar-header button:hover {
        background: var(--primary-light);
        color: var(--primary-color);
    }

    .weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        text-align: center;
        font-size: 0.85rem;
        color: var(--text-light);
        margin-bottom: 0.5rem;
    }

    .weekdays div {
        padding: 5px 0;
        font-weight: 600;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        text-align: center;
        font-size: 0.85rem;
        color: var(--text-dark);
    }

    .day {
        padding: 8px 0;
        background: #f7fafc;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .day:hover {
        background: var(--primary-light);
    }

    .day.selected {
        background: var(--primary-color);
        color: white;
        font-weight: bold;
    }

    .day.other-month {
        color: #a0aec0;
    }

    .day.has-booking {
        position: relative;
    }

    .day.has-booking::after {
        content: '';
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 4px;
        background-color: var(--success);
        border-radius: 50%;
    }

    .day.today {
        border: 1px solid var(--primary-color);
    }

    /* Panel kanan (jadwal booking) */
    .right-panel {
        flex: 1;
        min-width: 400px;
        background: var(--card-bg);
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .right-panel-header {
        padding: 1.5rem 1.5rem 0;
    }

    .right-panel-content {
        flex: 1;
        overflow-y: auto;
        padding: 0 1.5rem 1.5rem;
        max-height: calc(100vh - 350px); /* Menyesuaikan tinggi maksimum */
    }

    .day-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--text-dark);
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 0.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Button Atur Jadwal dengan gradasi navy dan blusky */
    .btn-atur-jadwal {
        background: #60A5FA;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(30, 64, 175, 0.2);
    }

    .btn-atur-jadwal:hover {
        background: #60A5FA;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
    }

    /* Slot waktu */
    .time-slot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .time-slot:hover {
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .time-slot.booked {
        background: #f0fff4;
        border-color: #c6f6d5;
    }

    .time-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .time {
        font-weight: 600;
        width: 60px;
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    .slot {
        flex: 1;
    }

    .status {
        font-size: 0.85rem;
        color: var(--success);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .info {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .info strong {
        font-weight: 600;
        display: block;
    }

    .price {
        color: var(--primary-color);
        font-weight: 600;
    }

    .slot-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }

    .detail-btn {
        background: var(--primary-light);
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        padding: 6px 12px;
        font-size: 0.85rem;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.2s ease;
        white-space: nowrap;
    }

    .detail-btn:hover {
        background: var(--primary-color);
        color: white;
    }

    .available-text {
        color: var(--text-light);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .booked-info {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 0.5rem;
    }

    .booked-info .fa-check {
        color: var(--success);
    }

    /* Ringkasan di bawah */
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }

    .summary-card {
        background: var(--card-bg);
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-left: 4px solid;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--text-light);
        font-weight: 500;
    }

    /* Warna untuk setiap card */
    .summary-card:nth-child(1) {
        border-left-color: #4299E1;
        color: #4299E1;
    }
    
    .summary-card:nth-child(2) {
        border-left-color: var(--success);
        color: var(--success);
    }
    
    .summary-card:nth-child(3) {
        border-left-color: var(--warning);
        color: var(--warning);
    }
    
    .summary-card:nth-child(4) {
        border-left-color: #9F7AEA;
        color: #9F7AEA
    }
    
    .summary-card:nth-child(5) {
        border-left-color: var(--navy);
        color: var(--navy);
    }

    /* Modal styling - konsisten dengan dashboard */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background-color: var(--primary-light);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px 12px 0 0;
        padding: 1.2rem 1.5rem;
    }

    .modal-title {
        color: var(--text-dark);
        font-weight: 700;
        font-size: 1.25rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-body p.text-muted {
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }

    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem 1.5rem;
    }

    .form-section {
        margin-bottom: 1.5rem;
    }

    .form-section-title {
        font-weight: 600;
        margin-bottom: 0.75rem;
        font-size: 1rem;
        color: var(--text-dark);
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }

    .required::after {
        content: " *";
        color: var(--danger);
    }

    /* Styling khusus untuk modal detail booking */
    .detail-booking-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-label {
        font-weight: 600;
        color: var(--text-dark);
    }

    .detail-value {
        color: var(--text-light);
    }

    /* ========== RESPONSIVE DESIGN UPDATE ========== */
    
    /* Tablet Landscape (1024px dan di bawahnya) */
    @media (max-width: 1024px) {
        .schedule-container {
            flex-direction: column;
            gap: 1rem;
        }
        
        .left-panel {
            flex: 1;
            width: 100%;
            max-width: 100%;
        }
        
        .right-panel {
            width: 100%;
            min-width: 100%;
        }
        
        .summary-cards {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.75rem;
        }
        
        .stat-number {
            font-size: 1.6rem;
        }
        
        .right-panel-content {
            max-height: 400px;
        }
    }

    /* Tablet Portrait (768px dan di bawahnya) */
    @media (max-width: 768px) {
        .main h1 {
            font-size: 1.6rem;
        }
        
        .schedule-container {
            flex-direction: column;
        }
        
        .left-panel, .right-panel {
            width: 100%;
            min-width: auto;
        }
        
        .left-panel {
            padding: 1rem;
        }
        
        .calendar-header {
            font-size: 1rem;
        }
        
        .day {
            padding: 6px 0;
        }
        
        .right-panel-header {
            padding: 1rem 1rem 0;
        }
        
        .right-panel-content {
            padding: 0 1rem 1rem;
            max-height: 350px;
        }
        
        .day-title {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            font-size: 1.1rem;
        }
        
        .btn-atur-jadwal {
            align-self: flex-start;
            padding: 8px 14px;
            font-size: 0.85rem;
        }
        
        .time-slot {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding: 0.8rem;
        }
        
        .time-info {
            width: 100%;
        }
        
        .slot-actions {
            width: 100%;
            align-items: flex-start;
            flex-direction: row;
        }
        
        .summary-cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
        
        .summary-card {
            padding: 1.25rem 1rem;
        }
        
        .stat-number {
            font-size: 1.5rem;
        }
        
        .stat-label {
            font-size: 0.85rem;
        }
    }

    /* Mobile (480px dan di bawahnya) */
    @media (max-width: 480px) {
        .main h1 {
            font-size: 1.4rem;
        }
        
        .main p {
            font-size: 0.9rem;
        }
        
        .left-panel {
            padding: 0.8rem;
        }
        
        .left-panel h3 {
            font-size: 1.1rem;
        }
        
        .calendar-header {
            font-size: 0.95rem;
            margin-bottom: 0.8rem;
        }
        
        .calendar-header button {
            padding: 4px 8px;
        }
        
        .weekdays {
            font-size: 0.8rem;
        }
        
        .calendar-grid {
            font-size: 0.8rem;
        }
        
        .day {
            padding: 5px 0;
        }
        
        .right-panel-header {
            padding: 0.8rem 0.8rem 0;
        }
        
        .right-panel-content {
            padding: 0 0.8rem 0.8rem;
            max-height: 300px;
        }
        
        .day-title {
            font-size: 1rem;
        }
        
        .btn-atur-jadwal {
            padding: 7px 12px;
            font-size: 0.8rem;
            gap: 5px;
        }
        
        .time-slot {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            padding: 0.7rem;
            margin-bottom: 0.8rem;
        }
        
        .time-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
            width: 100%;
        }
        
        .time {
            width: auto;
            font-size: 0.9rem;
        }
        
        .slot {
            width: 100%;
        }
        
        .slot-actions {
            width: 100%;
            align-items: flex-start;
        }
        
        .detail-btn {
            width: 100%;
            text-align: center;
            padding: 8px;
        }
        
        .summary-cards {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        
        .summary-card {
            padding: 1rem 0.8rem;
        }
        
        .stat-number {
            font-size: 1.4rem;
        }
        
        .stat-label {
            font-size: 0.8rem;
        }
        
        /* Modal adjustments for mobile */
        .modal-content {
            margin: 10px;
        }
        
        .modal-header {
            padding: 1rem;
        }
        
        .modal-title {
            font-size: 1.1rem;
        }
        
        .modal-body {
            padding: 1rem;
        }
        
        .detail-booking-item {
            flex-direction: column;
            gap: 5px;
        }
        
        .detail-label, .detail-value {
            width: 100%;
        }
    }

    /* Small Mobile (360px dan di bawahnya) */
    @media (max-width: 360px) {
        .calendar-header {
            font-size: 0.9rem;
        }
        
        .weekdays {
            font-size: 0.75rem;
        }
        
        .calendar-grid {
            font-size: 0.75rem;
        }
        
        .day {
            padding: 4px 0;
        }
        
        .day-title {
            font-size: 0.95rem;
        }
        
        .btn-atur-jadwal {
            padding: 6px 10px;
            font-size: 0.75rem;
        }
        
        .time {
            font-size: 0.85rem;
        }
        
        .available-text, .info, .price {
            font-size: 0.85rem;
        }
        
        .detail-btn {
            font-size: 0.8rem;
        }
    }

    /* Responsive design untuk summary cards */
    @media (max-width: 1200px) {
        .summary-cards {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }
        
        .stat-number {
            font-size: 1.6rem;
        }
    }

    /* Responsiveness untuk layar besar - existing code */
    @media (max-width: 1400px) {
        .main {
            padding: 20px;
        }
        
        .schedule-container {
            gap: 1rem;
        }
        
        .left-panel {
            flex: 0 0 300px;
        }
    }

    @media (max-width: 1200px) {
        .left-panel, .right-panel {
            min-width: 100%;
            max-width: 100%;
        }
        
        .right-panel {
            max-width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // PERBAIKAN: Data booking dari controller (real data dari database)
    const bookingsData = @json($bookingsData);
    
    const statsData = {
        totalBookings: {{ $totalBookings ?? 0 }},
        confirmedBookings: {{ $confirmedBookings ?? 0 }},
        pendingBookings: {{ $pendingBookings ?? 0 }},
        todayRevenue: {{ $todayRevenue ?? 0 }},
        totalRevenue: {{ $totalRevenue ?? 0 }}
    };

    // PERBAIKAN: Inisialisasi variabel dengan tanggal hari ini
    let currentDate = new Date();
    let selectedDate = new Date();

    // Format tanggal ke format YYYY-MM-DD (sesuai format database)
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Format tanggal untuk tampilan
    function formatDisplayDate(date) {
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    }

    // PERBAIKAN: Fungsi untuk mendapatkan nama bulan dalam Bahasa Indonesia
    function getMonthName(month) {
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return monthNames[month];
    }

    // PERBAIKAN: Render kalender dengan optimasi performa
    function renderCalendar() {
        const calendarDays = document.getElementById('calendar-days');
        const currentMonthYear = document.getElementById('current-month-year');
        
        // Tampilkan loading sementara
        calendarDays.innerHTML = '<div class="text-center py-2">Memuat kalender...</div>';
        
        setTimeout(() => {
            calendarDays.innerHTML = '';
            
            // Set teks bulan dan tahun
            currentMonthYear.textContent = `${getMonthName(currentDate.getMonth())} ${currentDate.getFullYear()}`;
            
            // Dapatkan hari pertama bulan ini dan hari terakhir bulan sebelumnya
            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDay = firstDay.getDay();
            
            // Tambahkan hari dari bulan sebelumnya
            const prevMonthLastDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0).getDate();
            for (let i = startingDay - 1; i >= 0; i--) {
                const dayElement = document.createElement('div');
                dayElement.className = 'day other-month';
                dayElement.textContent = prevMonthLastDay - i;
                calendarDays.appendChild(dayElement);
            }
            
            // Tambahkan hari bulan ini
            for (let i = 1; i <= daysInMonth; i++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'day';
                dayElement.textContent = i;
                
                // Periksa apakah hari ini memiliki booking dari data database
                const checkDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), i);
                const formattedDate = formatDate(checkDate);
                
                // PERBAIKAN: Cek apakah ada booking pada tanggal ini dengan validasi data
                if (bookingsData && bookingsData[formattedDate] && bookingsData[formattedDate].length > 0) {
                    dayElement.classList.add('has-booking');
                    
                    // PERBAIKAN: Tambahkan tooltip untuk informasi booking
                    dayElement.title = `${bookingsData[formattedDate].length} booking pada tanggal ini`;
                }
                
                // Tandai hari yang dipilih
                if (checkDate.getDate() === selectedDate.getDate() && 
                    checkDate.getMonth() === selectedDate.getMonth() && 
                    checkDate.getFullYear() === selectedDate.getFullYear()) {
                    dayElement.classList.add('selected');
                }
                
                // Tandai hari ini
                const today = new Date();
                if (checkDate.getDate() === today.getDate() && 
                    checkDate.getMonth() === today.getMonth() && 
                    checkDate.getFullYear() === today.getFullYear()) {
                    dayElement.classList.add('today');
                }
                
                // Tambahkan event listener untuk memilih tanggal
                dayElement.addEventListener('click', () => {
                    selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), i);
                    renderCalendar();
                    renderBookingSlots();
                });
                
                calendarDays.appendChild(dayElement);
            }
            
            // Tambahkan hari dari bulan berikutnya
            const totalCells = 42;
            const remainingCells = totalCells - (startingDay + daysInMonth);
            for (let i = 1; i <= remainingCells; i++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'day other-month';
                dayElement.textContent = i;
                calendarDays.appendChild(dayElement);
            }
        }, 100);
    }

    // PERBAIKAN: Render slot booking dengan handling error
    function renderBookingSlots() {
        const selectedDateTitle = document.getElementById('selected-date-title');
        const bookingSlots = document.getElementById('booking-slots');
        const loadingSlots = document.getElementById('loading-slots');
        const formattedDate = formatDate(selectedDate);
        
        // Update judul tanggal
        selectedDateTitle.textContent = formatDisplayDate(selectedDate);
        
        // Tampilkan loading
        bookingSlots.innerHTML = '';
        loadingSlots.style.display = 'block';
        
        setTimeout(() => {
            loadingSlots.style.display = 'none';
            
            // PERBAIKAN: Validasi data bookingsData
            if (!bookingsData) {
                bookingSlots.innerHTML = `
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Data jadwal tidak tersedia</p>
                    </div>
                `;
                return;
            }
            
            // Dapatkan data booking untuk tanggal yang dipilih dari database
            const bookings = bookingsData[formattedDate] || [];
            
            // Jika ada data booking, tampilkan
            if (bookings.length > 0) {
                bookings.forEach((booking, index) => {
                    const timeSlot = document.createElement('div');
                    timeSlot.className = 'time-slot';
                    timeSlot.id = `time-slot-${index}`;
                    
                    if (!booking.available) {
                        timeSlot.classList.add('booked');
                    }
                    
                    // PERBAIKAN: Tambahkan informasi status yang lebih detail
                    const statusInfo = booking.detail && booking.detail.status ? 
                        `<span class="status-badge status-${booking.detail.status.toLowerCase()}">${booking.detail.status}</span>` : '';
                    
                    timeSlot.innerHTML = `
                        <div class="time-info">
                            <div class="time">${booking.time || '--:--'}</div>
                            <div class="slot">
                                ${booking.available 
                                  ? `<div class="available-text">Slot Tersedia</div>
                                     <div class="price">${booking.price || 'Rp 0'}</div>`
                                  : `<div class="booked-info">
                                         <i class="fas fa-check"></i>
                                         <span>${booking.name || 'Tidak ada nama'}</span>
                                         ${statusInfo}
                                     </div>
                                     <div class="price">${booking.price || 'Rp 0'}</div>`
                                }
                            </div>
                        </div>
                        ${!booking.available ? `
                            <div class="slot-actions">
                                <button class="detail-btn" data-booking='${JSON.stringify(booking.detail || {})}'>
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </button>
                            </div>
                        ` : ''}
                    `;
                    
                    bookingSlots.appendChild(timeSlot);
                });

                // Tambahkan event listener untuk tombol "Lihat Detail"
                document.querySelectorAll('.detail-btn').forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const bookingDetail = JSON.parse(this.dataset.booking);
                        openDetailBookingModal(bookingDetail);
                    });
                });


            } else {
                // Jika tidak ada data booking, tampilkan pesan
                bookingSlots.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada jadwal booking untuk tanggal ini</p>
                        
                    </div>
                `;
            }
        }, 500);
    }

    // PERBAIKAN: Fungsi untuk membuka modal detail booking dengan validasi data
    function openDetailBookingModal(bookingDetail) {
        if (bookingDetail && Object.keys(bookingDetail).length > 0) {
            // Isi data detail ke modal dengan fallback value
            document.getElementById('detail-nama').textContent = bookingDetail.nama || 'Tidak tersedia';
            document.getElementById('detail-tempat').textContent = bookingDetail.tempat || 'Tidak tersedia';
            document.getElementById('detail-tanggal').textContent = bookingDetail.tanggal || 'Tidak tersedia';
            document.getElementById('detail-durasi').textContent = bookingDetail.durasi || 'Tidak tersedia';
            document.getElementById('detail-biaya').textContent = bookingDetail.biaya ? `Rp ${bookingDetail.biaya}` : 'Tidak tersedia';
            document.getElementById('detail-status').textContent = bookingDetail.status + (bookingDetail.sumber ? ` (${bookingDetail.sumber})` : '');

            
            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('detailBookingModal'));
            modal.show();
        } else {
            // Tampilkan pesan error jika data tidak valid
            alert('Data booking tidak tersedia atau tidak valid');
        }
    }

    // PERBAIKAN: Event listener untuk navigasi bulan
    document.getElementById('prev-month').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    document.getElementById('next-month').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    // PERBAIKAN: Inisialisasi dengan error handling
    document.addEventListener('DOMContentLoaded', () => {
        try {
            renderCalendar();
            renderBookingSlots();

            // Log data untuk debugging (hanya di development)
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                console.log('Bookings Data:', bookingsData);
                console.log('Stats Data:', statsData);
            }
        } catch (error) {
            console.error('Error initializing calendar:', error);
            // Tampilkan pesan error ke user
            const bookingSlots = document.getElementById('booking-slots');
            bookingSlots.innerHTML = `
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Terjadi kesalahan saat memuat jadwal. Silakan refresh halaman.</p>
                    <button class="btn btn-warning mt-2" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt"></i> Refresh Halaman
                    </button>
                </div>
            `;
        }
    });

    // PERBAIKAN: Handle visibility change untuk refresh data ketika tab aktif kembali
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Tab menjadi aktif, refresh data jika diperlukan
            const currentFormattedDate = formatDate(new Date());
            if (formatDate(selectedDate) === currentFormattedDate) {
                renderBookingSlots();
            }
        }
    });
</script>
@endpush