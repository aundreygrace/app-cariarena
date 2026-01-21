@extends('layouts.venue')

@section('title', 'Atur Jadwal')

@section('page-title', 'Atur Jadwal')

@section('content')
<div class="content-main ">
    <!-- Tombol Kembali -->
    <a href="{{ route('venue.jadwal.index') }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Kembali ke Jadwal
    </a>
    
    <p class="page-subtitle">Kelola slot waktu untuk venue Anda</p>

    <div class="content">
        <!-- Calendar Section -->
        <section class="card calendar-container">
            <div class="calendar-header">
                <h3><i class="fas fa-calendar-alt me-2"></i>Jadwal Bulanan</h3>
                <div class="calendar-nav">
                    <button id="prev-month">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="current-month" id="current-month">Oktober 2025</div>
                    <button id="next-month">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="calendar-wrap">
                <div class="weekday">
                    <div>Min</div>
                    <div>Sen</div>
                    <div>Sel</div>
                    <div>Rab</div>
                    <div>Kam</div>
                    <div>Jum</div>
                    <div>Sab</div>
                </div>

                <div class="calendar-grid" id="calendar-days">
                    <!-- Calendar days will be populated by JavaScript -->
                </div>

                <div class="legend">
                    <div class="item"><span class="dot full"></span>Penuh</div>
                    <div class="item"><span class="dot partial"></span>Belum penuh</div>
                    <div class="item"><span class="dot empty"></span>Kosong</div>
                </div>
            </div>
        </section>

        <!-- Form Section -->
        <aside class="card form-container">
            <h3><i class="fas fa-cog me-2"></i>Pengaturan Slot</h3>
            
            {{-- Tampilkan pesan error/success --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <form action="{{ route('venue.jadwal.store') }}" method="POST">
                @csrf
                
                {{-- Ganti select venue dengan input hidden karena user hanya punya 1 venue --}}
                <div class="form-row">
                    <label>Venue:</label>
                    @if(isset($venue) && $venue)
                        <input type="text" class="form-control" value="{{ $venue->name }}" readonly>
                        <input type="hidden" name="venue_id" value="{{ $venue->id }}">
                        <small class="text-muted">Venue yang akan diatur jadwalnya</small>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Anda belum memiliki venue. Silakan buat venue terlebih dahulu.
                        </div>
                    @endif
                </div>
                
                <div class="form-row">
                    <label>Tanggal:</label>
                    <input type="date" name="tanggal" class="form-control" id="tanggal-input" min="{{ date('Y-m-d') }}" required>
                    <small class="text-muted">Tidak bisa memilih tanggal kemarin/hari sebelumnya</small>
                </div>
                
                <div class="form-row">
                    <label>Waktu Mulai:</label>
                    <div class="time-select-container">
                        <select name="waktu_mulai" class="form-control time-select" id="waktu-mulai-select" required>
                            <option value="">Pilih Waktu Mulai</option>
                            @php
                                // Buat array waktu dari 00:00 hingga 23:00
                                $times = [];
                                for($i = 0; $i < 24; $i++) {
                                    $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
                                    $ampm = $i < 12 ? 'AM' : 'PM';
                                    $displayHour = $i % 12;
                                    $displayHour = $displayHour == 0 ? 12 : $displayHour;
                                    $displayHour = str_pad($displayHour, 2, '0', STR_PAD_LEFT);
                                    $times[] = [
                                        'value' => $hour . ':00',
                                        'label' => $displayHour . ':00 ' . $ampm
                                    ];
                                }
                            @endphp
                            @foreach($times as $time)
                                <option value="{{ $time['value'] }}">{{ $time['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <small class="text-muted">Pilih waktu mulai (hanya jam, tanpa menit)</small>
                </div>
                
                <div class="form-row">
                    <label>Catatan (opsional):</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan"></textarea>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn" {{ !isset($venue) || !$venue ? 'disabled' : '' }}>
                        <i class="fas fa-save me-2"></i>Simpan Jadwal
                    </button>
                </div>
            </form>

            {{-- Tambahkan informasi venue --}}
            @if(isset($venue) && $venue)
                <div class="venue-info mt-4 p-3" style="background: #f8f9fa; border-radius: 8px; border-left: 4px solid #1E40AF;">
                    <h6 style="margin-bottom: 10px; color: #1E40AF;">
                        <i class="fas fa-info-circle me-2"></i>Informasi Venue
                    </h6>
                    <div style="font-size: 0.9rem;">
                        <p style="margin-bottom: 5px;"><strong>Nama:</strong> {{ $venue->name }}</p>
                        <p style="margin-bottom: 5px;"><strong>Kategori:</strong> {{ $venue->category ?? 'Tidak ada' }}</p>
                        <p style="margin-bottom: 5px;"><strong>Harga per Jam:</strong> Rp {{ number_format($venue->price_per_hour ?? 0, 0, ',', '.') }}</p>
                        <p style="margin-bottom: 0;"><strong>Alamat:</strong> {{ $venue->address ?? 'Tidak ada' }}</p>
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>

<style>
    
    /* ==== BACK BUTTON ==== */
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        color: var(--primary-hover);
        transform: translateX(-3px);
    }

    .page-subtitle {
        color: var(--text-light);
        margin-bottom: 20px;
    }

    /* STYLE BUTTON YANG DIUBAH */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: var(--primary-light);
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(99, 179, 237, 0.2);
        text-decoration: none;
        width: 100%;
    }

    .btn:hover:not(:disabled) {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(99, 179, 237, 0.3);
    }

    .btn:disabled {
        background: #6c757d;
        color: white;
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Alert Styles */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid transparent;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .alert-success {
        background-color: #d1edff;
        border-color: #b3d7ff;
        color: #155724;
    }

    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeaa7;
        color: #856404;
    }

    /* Content Layout */
    .content {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        position: relative;
    }

    /* Card Styles */
    .card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        border: none;
        margin-bottom: 20px;
    }

    .card h3 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--primary-color);
    }

    /* Calendar Styles - TAMBAHKAN POSITION STICKY DI SINI */
    .calendar-container {
        flex: 1;
        min-width: 300px;
        position: sticky;
        top: 20px;
        align-self: flex-start;
        height: fit-content;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
    }

    /* Tambahkan scroll untuk kalender jika kontennya terlalu panjang */
    .calendar-container::-webkit-scrollbar {
        width: 6px;
    }

    .calendar-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .calendar-container::-webkit-scrollbar-thumb {
        background: var(--primary-light);
        border-radius: 10px;
    }

    .calendar-container::-webkit-scrollbar-thumb:hover {
        background: var(--primary-color);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .calendar-nav {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-top: 5px;
    }

    .calendar-nav button {
        background: var(--primary-light);
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        color: var(--primary-color);
    }

    .calendar-nav button:hover {
        background: var(--primary-color);
        color: white;
    }

    .current-month {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-dark);
        padding: 8px 12px;
        min-width: 180px;
        text-align: center;
    }

    .calendar-wrap {
        background: var(--card-bg);
        padding: 20px;
        border-radius: 10px;
        border: 1px solid rgba(0,0,0,0.04);
        overflow-x: auto;
    }

    .weekday {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        margin-bottom: 12px;
        padding: 8px 6px;
        color: var(--text-dark);
        font-weight: 600;
        text-align: center;
        min-width: 350px;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
        min-width: 350px;
    }

    .cell {
        height: 50px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        transition: all 0.2s ease;
        cursor: pointer;
        min-width: 40px;
    }

    .cell.selected {
        outline: 3px solid #1E40AF;
        outline-offset: 0;
        box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.2);
        position: relative;
        z-index: 1;
    }

    .cell.empty {
        background: var(--bg-light);
        color: transparent;
        cursor: default;
    }

    /* WARNA BIRU SEMUA DENGAN VARIASI YANG BERBEDA */
    .cell.full {
        background: #3B82F6; /* Biru gelap untuk Penuh */
        color: white;
        border: 2px solid #3B82F6;
    }

    .cell.partial {
        background: #93C5FD; /* Biru medium untuk Belum penuh */
        color: white;
        border: 2px solid #93C5FD;
    }

    .cell.light {
        background: #cacacaff; /* Biru muda untuk Kosong */
        color: #000000ff; /* Teks berwarna biru gelap agar terbaca */
        border: 2px solid #cacacaff;
    }

    .cell.other-month {
        color: #a0aec0;
        background: #f7fafc;
    }

    .cell.disabled {
        background: #f8f9fa;
        color: #6c757d;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .cell:hover:not(.empty):not(.other-month):not(.disabled) {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .legend {
        display: flex;
        gap: 18px;
        align-items: center;
        margin-top: 18px;
        flex-wrap: wrap;
    }

    .legend .item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: var(--text-dark);
        font-weight: 500;
    }

    .dot {
        width: 16px;
        height: 16px;
        border-radius: 999px;
        border: 1px solid rgba(0,0,0,0.1);
    }

    /* WARNA BIRU UNTUK LEGEND */
    .dot.full {
        background: #3B82F6; /* Biru gelap untuk Penuh */
        border-color: #3B82F6;
    }

    .dot.partial {
        background: #93C5FD; /* Biru medium untuk Belum penuh */
        border-color: #93C5FD;
    }

    .dot.empty {
        background: #cacacaff; /* Biru muda untuk Kosong */
        border-color: #cacacaff;
    }

    /* Form Styles */
    .form-container {
        width: 400px;
        min-width: 300px;
        flex: 1;
    }

    .form-row {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 15px;
    }

    label {
        font-weight: 600;
        color: var(--text-dark);
    }

    .form-control {
        padding: 12px 14px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background: #f7fbfb;
        transition: all 0.2s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px var(--primary-light);
        outline: none;
    }

    /* STYLE KHUSUS UNTUK DROPDOWN WAKTU */
    .time-select-container {
        position: relative;
        width: 100%;
    }

    .time-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%231E40AF' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 40px;
        cursor: pointer;
    }

    /* Limit tinggi dropdown agar tidak terlalu besar */
    .time-select option {
        padding: 8px 12px;
        font-size: 14px;
    }

    /* Untuk browser tertentu yang mendukung */
    .time-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(30, 64, 175, 0.2);
    }

    /* Responsive styling untuk dropdown */
    @media (max-width: 768px) {
        .time-select {
            max-height: 200px; /* Batasi tinggi dropdown di mobile */
        }
    }

    .text-muted {
        color: #6c757d;
        font-size: 0.875em;
    }

    /* Responsiveness */
    @media (max-width: 768px) {
        .content {
            flex-direction: column;
        }
        
        .calendar-container {
            position: static;
            max-height: none;
            overflow-y: visible;
            width: 100%;
        }
        
        .form-container {
            width: 100%;
        }
        
        .calendar-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .calendar-nav {
            align-self: center;
        }
    }

    @media (max-width: 480px) {
        .card {
            padding: 15px;
        }
        
        .calendar-wrap {
            padding: 15px 10px;
        }
        
        .cell {
            height: 40px;
            font-size: 14px;
        }
        
        .weekday div {
            font-size: 14px;
        }
        
        .current-month {
            font-size: 16px;
            min-width: 150px;
        }
        
        .legend {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .calendar-header h3 {
            font-size: 18px;
        }
        
        .form-container h3 {
            font-size: 18px;
        }
        
        .time-select {
            padding: 10px 12px;
            font-size: 14px;
        }
    }

    @media (max-width: 360px) {
        .cell {
            height: 35px;
            font-size: 12px;
        }
        
        .weekday div {
            font-size: 12px;
        }
        
        .current-month {
            font-size: 14px;
            min-width: 130px;
        }
        
        .calendar-nav button {
            width: 32px;
            height: 32px;
        }
        
        .time-select {
            font-size: 13px;
            padding: 8px 10px;
        }
    }
</style>

<script>
    let currentDate = new Date();

    // Format tanggal ke YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Cek apakah tanggal adalah hari kemarin/sebelumnya
    function isPastDate(date) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return date < today;
    }

    // Render kalender berdasarkan bulan dan tahun
    function renderCalendar() {
        const calendarDays = document.getElementById('calendar-days');
        const currentMonthElement = document.getElementById('current-month');
        
        // Kosongkan kalender
        calendarDays.innerHTML = '';
        
        // Set teks bulan dan tahun
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        currentMonthElement.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
        
        // Dapatkan hari pertama bulan ini dan hari terakhir bulan sebelumnya
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDay = firstDay.getDay();
        
        // Tambahkan hari dari bulan sebelumnya
        const prevMonthLastDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0).getDate();
        for (let i = startingDay - 1; i >= 0; i--) {
            const dayElement = document.createElement('div');
            dayElement.className = 'cell empty';
            dayElement.textContent = prevMonthLastDay - i;
            calendarDays.appendChild(dayElement);
        }
        
        // Tambahkan hari bulan ini
        for (let i = 1; i <= daysInMonth; i++) {
            const dayElement = document.createElement('div');
            const checkDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), i);
            const formattedDate = formatDate(checkDate);
            
            // Default class
            dayElement.className = 'cell';
            dayElement.textContent = i;
            dayElement.dataset.date = formattedDate;
            
            // Tandai hari yang sudah lewat (kemarin/sebelumnya) sebagai disabled
            if (isPastDate(checkDate)) {
                dayElement.classList.add('disabled');
            } else {
                // Default status light (kosong) - akan diupdate via AJAX
                dayElement.classList.add('light');
            }
            
            // Tandai hari ini
            const today = new Date();
            if (checkDate.getDate() === today.getDate() && 
                checkDate.getMonth() === today.getMonth() && 
                checkDate.getFullYear() === today.getFullYear()) {
                dayElement.classList.add('today');
            }
            
            calendarDays.appendChild(dayElement);
        }
        
        // Tambahkan hari dari bulan berikutnya
        const totalCells = 42;
        const remainingCells = totalCells - (startingDay + daysInMonth);
        for (let i = 1; i <= remainingCells; i++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'cell other-month';
            dayElement.textContent = i;
            calendarDays.appendChild(dayElement);
        }
        
        // Load data status bulanan setelah render kalender
        loadMonthlyStatus();
        
        // Tambahkan event listener ke sel kalender yang bisa diklik
        addDayClickListeners();
    }

    // Tambahkan event listener untuk klik pada hari kalender
    function addDayClickListeners() {
        const dayCells = document.querySelectorAll('.cell:not(.empty):not(.other-month):not(.disabled)');
        
        dayCells.forEach(cell => {
            // Hapus event listener yang lama jika ada
            cell.removeEventListener('click', handleDayClick);
            
            // Tambahkan event listener baru
            cell.addEventListener('click', handleDayClick);
        });
    }

    // Fungsi untuk menangani klik pada hari kalender
    function handleDayClick(event) {
        const cell = event.currentTarget;
        const selectedDate = cell.dataset.date;
        
        if (selectedDate) {
            // Isi field tanggal di form
            document.getElementById('tanggal-input').value = selectedDate;
            console.log('Tanggal dipilih:', selectedDate);
            
            // Berikan feedback visual
            cell.style.transform = 'scale(0.95)';
            cell.style.boxShadow = '0 4px 12px rgba(0,0,0,0.2)';
            
            setTimeout(() => {
                cell.style.transform = '';
                cell.style.boxShadow = '';
            }, 200);
            
            // Hapus seleksi sebelumnya (jika ada)
            const previouslySelected = document.querySelector('.cell.selected');
            if (previouslySelected && previouslySelected !== cell) {
                previouslySelected.classList.remove('selected');
            }
            
            // Tambahkan kelas selected
            cell.classList.add('selected');
        }
    }

    // Load status bulanan dari server
    function loadMonthlyStatus() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1;
        
        fetch(`/venue/jadwal/monthly-status?year=${year}&month=${month}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                updateCalendarWithRealData(data);
            })
            .catch(error => {
                console.error('Error loading monthly status:', error);
            });
    }

    // Update kalender dengan data real
    function updateCalendarWithRealData(monthlyData) {
        const calendarDays = document.getElementById('calendar-days');
        const days = calendarDays.querySelectorAll('.cell:not(.empty):not(.other-month):not(.disabled)');
        
        days.forEach(dayElement => {
            const day = parseInt(dayElement.textContent);
            const checkDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
            const formattedDate = formatDate(checkDate);
            
            // Simpan tanggal di data attribute
            dayElement.dataset.date = formattedDate;
            
            // Hapus kelas status sebelumnya
            dayElement.classList.remove('full', 'partial', 'light');
            
            // Set status berdasarkan data real
            if (monthlyData[formattedDate]) {
                dayElement.classList.add(monthlyData[formattedDate]);
            } else {
                dayElement.classList.add('light'); // Kosong
            }
        });
        
        // Setelah update data, tambahkan kembali event listener
        addDayClickListeners();
    }

    // Event listener untuk navigasi bulan
    document.getElementById('prev-month').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    document.getElementById('next-month').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

        document.querySelector('form').addEventListener('submit', function(e) {
        const tanggalInput = document.getElementById('tanggal-input').value;
        const waktuMulai = document.getElementById('waktu-mulai-select').value;
        const durasi = document.getElementById('durasi-select').value;
        const waktuSelesai = document.getElementById('waktu-selesai-hidden').value;

        const selectedDate = new Date(tanggalInput);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (selectedDate < today) {
            e.preventDefault();
            alert('Tidak bisa menambah jadwal untuk hari sebelumnya.');
            return false;
        }

        if (!waktuMulai || !durasi || !waktuSelesai) {
            e.preventDefault();
            alert('Harap pilih waktu mulai dan durasi.');
            return false;
        }
    });


        function calculateEndTime() {
        const waktuMulai = document.getElementById('waktu-mulai-select').value;
        const durasi = document.getElementById('durasi-select')?.value;

        if (!waktuMulai || !durasi) return;

        const [hour, minute] = waktuMulai.split(':').map(Number);

        const start = new Date(1970, 0, 1, hour, minute);
        start.setHours(start.getHours() + parseInt(durasi));

        const endHour = String(start.getHours()).padStart(2, '0');
        const endMinute = String(start.getMinutes()).padStart(2, '0');

        const endTime = `${endHour}:${endMinute}`;

        // tampilkan ke user
        document.getElementById('waktu-selesai-display').value = endTime;

        // kirim ke backend
        document.getElementById('waktu-selesai-hidden').value = endTime;
    }

    // Trigger hitung waktu selesai
    document.getElementById('waktu-mulai-select')
        .addEventListener('change', calculateEndTime);

    document.getElementById('durasi-select')
        .addEventListener('change', calculateEndTime);


    document.addEventListener('DOMContentLoaded', () => {
    renderCalendar();

    const today = new Date();
    document.getElementById('tanggal-input').value = formatDate(today);
    document.getElementById('tanggal-input').min = formatDate(today);

    document.getElementById('waktu-mulai-select').value = '08:00';

    calculateEndTime();
});

</script>
@endsection