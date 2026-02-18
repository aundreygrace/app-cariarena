

<?php $__env->startSection('title', 'Detail Venue'); ?>

<?php $__env->startSection('page-title', 'Detail Venue'); ?>

<?php $__env->startSection('content'); ?>
<!-- Back Button -->
<a href="<?php echo e(route('venue.venue-saya')); ?>" class="back-button">
    <i class="fas fa-arrow-left"></i> Kembali ke Venue Saya
</a>

<!-- Venue Header dengan data dinamis -->
<div class="venue-header">
    <h1 class="venue-title"><?php echo e($venue->name); ?></h1>
    <div class="venue-rating">
        <?php
            $rating = $venue->rating ?? 0;
            $fullStars = floor($rating);
            $hasHalfStar = ($rating - $fullStars) > 0;
        ?>
        
        <?php for($i = 1; $i <= 5; $i++): ?>
            <?php if($i <= $fullStars): ?>
                <i class="fas fa-star"></i>
            <?php elseif($i == $fullStars + 1 && $hasHalfStar): ?>
                <i class="fas fa-star-half-alt"></i>
            <?php else: ?>
                <i class="far fa-star"></i>
            <?php endif; ?>
        <?php endfor; ?>
        <span class="venue-rating-text"><?php echo e(number_format($rating, 1)); ?> (<?php echo e($venue->reviews_count); ?> ulasan)</span>
    </div>
</div>

<!-- Venue Content -->
<div class="venue-content">
    <!-- Calendar Section -->
    <div class="calendar-section">
        <h2 class="section-title">Kalender Ketersediaan</h2>
        <div class="calendar">
            <div class="calendar-header">
                <div class="calendar-month" id="calendar-month">September 2024</div>
                <div class="calendar-nav">
                    <button id="prev-month"><i class="fas fa-chevron-left"></i></button>
                    <button id="next-month"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            
            <div class="calendar-weekdays">
                <div class="calendar-weekday">Min</div>
                <div class="calendar-weekday">Sen</div>
                <div class="calendar-weekday">Sel</div>
                <div class="calendar-weekday">Rab</div>
                <div class="calendar-weekday">Kam</div>
                <div class="calendar-weekday">Jum</div>
                <div class="calendar-weekday">Sab</div>
            </div>
            
            <div class="calendar-days" id="calendar-days">
                <!-- Days will be populated by JavaScript -->
            </div>
            
            <div class="calendar-legend">
                <div class="legend-item">
                    <div class="legend-color legend-booked"></div>
                    <span>Penuh</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-partial"></div>
                    <span>Belum penuh</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-available"></div>
                    <span>Kosong</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section dengan data dinamis -->
    <div class="info-section">
        <h2 class="section-title">Detail Venue</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nama Venue</div>
                <div class="info-value"><?php echo e($venue->name); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Kategori</div>
                <div class="info-value"><?php echo e($venue->category); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Harga</div>
                <div class="info-value">Rp <?php echo e(number_format($venue->price_per_hour, 0, ',', '.')); ?>/jam</div>
            </div>
            <div class="info-item">
    <div class="info-label">Status</div>
    <div class="info-value">
        <span class="badge 
            <?php if($venue->status === 'Aktif'): ?> badge-aktif
            <?php elseif($venue->status === 'Maintenance'): ?> badge-maintenance
            <?php elseif($venue->status === 'Tidak Aktif'): ?> badge-nonaktif
            <?php else: ?> badge-secondary
            <?php endif; ?>">
            <?php echo e($venue->status ?? 'Tidak Diketahui'); ?>

        </span>
    </div>
</div>
            <div class="info-item">
                <div class="info-label">Lokasi</div>
                <div class="info-value"><?php echo e($venue->address); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Total Ulasan</div>
                <div class="info-value"><?php echo e($venue->reviews_count); ?> ulasan</div>
            </div>
        </div>
        
        <div class="facilities-section">
            <h3 class="section-title">Fasilitas</h3>
            <div class="facilities-grid">
                <?php if(!empty($venue->facilities) && is_array($venue->facilities)): ?>
                    <?php $__currentLoopData = $venue->facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="facility-item">
                            <i class="fas fa-check"></i>
                            <span><?php echo e($facility); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="facility-item">
                        <i class="fas fa-times"></i>
                        <span>Tidak ada fasilitas</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Reviews Section -->
<div class="reviews-section">
    <h2 class="section-title">Ulasan Pelanggan</h2>
    
    <!-- Data ulasan statis (bisa diganti dengan data dinamis dari controller) -->
    <div class="review-card">
        <div class="review-header">
            <div class="reviewer-name">Alisha Widya</div>
            <div class="review-date">30 September 2024</div>
        </div>
        <div class="review-rating">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
        </div>
        <div class="review-text">
            Lapangan sangat nyaman dan bersih. Fasilitasnya lengkap dan terawat dengan baik. Staff ramah dan responsif, sangat puas bermain di sini!
        </div>
    </div>
    
    <div class="review-card">
        <div class="review-header">
            <div class="reviewer-name">Budi Santoso</div>
            <div class="review-date">30 September 2024</div>
        </div>
        <div class="review-rating">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
        </div>
        <div class="review-text">
            Venue favorit saya untuk olahraga rutin. Panitianya ramah, jadwal selalu tepat waktu. Recommended banget!
        </div>
    </div>
</div>

<!-- Modal untuk detail jadwal -->
<div class="modal fade" id="jadwalModal" tabindex="-1" aria-labelledby="jadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jadwalModalTitle">Detail Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="jadwalModalBody">
                <!-- Konten modal akan diisi oleh JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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

    /* ==== DETAIL VENUE STYLES ==== */
    .venue-header {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .venue-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .venue-rating {
        color: #FFC107;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .venue-rating-text {
        color: var(--text-light);
        font-size: 1rem;
        margin-left: 0.5rem;
    }

    .venue-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .calendar-section, .info-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    /* Calendar Styles */
    .calendar {
        width: 100%;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .calendar-month {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    .calendar-nav {
        display: flex;
        gap: 0.5rem;
    }

    .calendar-nav button {
        background: var(--primary-light);
        border: none;
        border-radius: 6px;
        padding: 6px 10px;
        color: var(--primary-color);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .calendar-nav button:hover {
        background: var(--primary-color);
        color: white;
    }

    .calendar-weekdays, .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        margin-bottom: 0.5rem;
    }

    .calendar-weekday {
        text-align: center;
        font-weight: 600;
        color: var(--text-dark);
        padding: 8px 0;
        font-size: 0.9rem;
    }

    .calendar-day {
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .calendar-day.empty {
        background: transparent;
        cursor: default;
    }

    .calendar-day.available {
        background: #f0fff4;
        color: var(--success);
        border: 1px solid #c6f6d5;
    }

    .calendar-day.partial {
        background: #fffaf0;
        color: var(--warning);
        border: 1px solid #fefcbf;
    }

    .calendar-day.booked {
        background: #fed7d7;
        color: var(--danger);
        cursor: not-allowed;
        border: 1px solid #feb2b2;
    }

    .calendar-day:hover:not(.empty):not(.booked) {
        transform: scale(1.05);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .calendar-legend {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    .legend-available {
        background: #f0fff4;
        border: 1px solid var(--success);
    }

    .legend-partial {
        background: #fffaf0;
        border: 1px solid var(--warning);
    }

    .legend-booked {
        background: #fed7d7;
        border: 1px solid var(--danger);
    }

    /* Info Section Styles */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .info-item {
        margin-bottom: 1rem;
    }

    .info-label {
        font-size: 0.9rem;
        color: var(--text-light);
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: var(--text-dark);
    }

    .facilities-section {
        margin-top: 1.5rem;
    }

    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
    }

    .facility-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 8px 12px;
        background: var(--primary-light);
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .facility-item:hover {
        background: var(--primary-color);
        color: white;
    }

    .facility-item:hover i {
        color: white;
    }

    .facility-item i {
        color: var(--primary-color);
        transition: all 0.2s ease;
    }

    /* Reviews Section */
    .reviews-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .review-card {
        border-bottom: 1px solid #e2e8f0;
        padding: 1.5rem 0;
    }

    .review-card:last-child {
        border-bottom: none;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .reviewer-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .review-date {
        font-size: 0.9rem;
        color: var(--text-light);
    }

    .review-rating {
        color: #FFC107;
        margin-bottom: 0.5rem;
    }

    .review-text {
        color: var(--text-dark);
        line-height: 1.5;
    }

    /* Badge Status Styles untuk Detail Page */
    .badge-aktif {
        background: var(--success);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-maintenance {
        background: var(--warning);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-nonaktif {
        background: var(--danger);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-secondary {
        background: #6c757d;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* ==== RESPONSIVE STYLES ==== */

    /* Tablet Landscape (992px - 1200px) */
    @media (max-width: 1200px) {
        .facilities-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Tablet Portrait (768px - 992px) */
    @media (max-width: 992px) {
        .venue-content {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .facilities-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .venue-title {
            font-size: 1.75rem;
        }
        
        .calendar-section, 
        .info-section {
            padding: 1.25rem;
        }
    }

    /* Mobile Landscape (576px - 768px) */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .facilities-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .calendar-weekdays {
            gap: 2px;
        }
        
        .calendar-day {
            height: 35px;
            font-size: 0.85rem;
        }
        
        .calendar-month {
            font-size: 1.1rem;
        }
        
        .review-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .venue-header {
            padding: 1.5rem;
        }
        
        .venue-title {
            font-size: 1.5rem;
        }
        
        .section-title {
            font-size: 1.1rem;
        }
    }

    /* Mobile Portrait (max-width: 576px) */
    @media (max-width: 576px) {
        .venue-content {
            gap: 1rem;
        }
        
        .facilities-grid {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        
        .calendar-days, 
        .calendar-weekdays {
            gap: 1px;
        }
        
        .calendar-day {
            height: 32px;
            font-size: 0.8rem;
            border-radius: 4px;
        }
        
        .calendar-header {
            flex-direction: column;
            gap: 0.75rem;
            align-items: flex-start;
        }
        
        .calendar-nav {
            align-self: flex-end;
        }
        
        .calendar-legend {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .legend-item {
            font-size: 0.8rem;
        }
        
        .venue-header {
            padding: 1.25rem;
        }
        
        .venue-title {
            font-size: 1.35rem;
        }
        
        .venue-rating {
            font-size: 1rem;
        }
        
        .venue-rating-text {
            font-size: 0.9rem;
        }
        
        .calendar-section, 
        .info-section, 
        .reviews-section {
            padding: 1rem;
            border-radius: 8px;
        }
        
        .back-button {
            font-size: 0.9rem;
        }
        
        .info-label {
            font-size: 0.85rem;
        }
        
        .info-value {
            font-size: 0.9rem;
        }
        
        .review-card {
            padding: 1rem 0;
        }
        
        .reviewer-name {
            font-size: 0.95rem;
        }
        
        .review-date {
            font-size: 0.85rem;
        }
        
        .review-text {
            font-size: 0.9rem;
        }
    }

    /* Very Small Mobile (max-width: 375px) */
    @media (max-width: 375px) {
        .calendar-day {
            height: 28px;
            font-size: 0.75rem;
        }
        
        .calendar-weekday {
            font-size: 0.8rem;
            padding: 6px 0;
        }
        
        .venue-header {
            padding: 1rem;
        }
        
        .calendar-section, 
        .info-section, 
        .reviews-section {
            padding: 0.75rem;
        }
        
        .facility-item {
            padding: 6px 10px;
            font-size: 0.85rem;
        }
    }

    /* Touch Device Optimizations */
    .touch-device .calendar-day:hover:not(.empty):not(.booked) {
        transform: none;
        box-shadow: none;
    }

    .touch-device .calendar-day:active:not(.empty):not(.booked) {
        transform: scale(0.95);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .touch-device .facility-item:active {
        transform: scale(0.98);
    }

    /* Improvemen aksesibilitas untuk mobile */
    @media (max-width: 768px) {
        .calendar-nav button {
            min-width: 44px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .back-button {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
        }
        
        .calendar-day {
            min-height: 44px;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Data jadwal dari controller
    const jadwalData = <?php echo json_encode($jadwalData ?? [], 15, 512) ?>;
    
    // Dapatkan CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Fungsi untuk menandai tanggal dengan status jadwal berdasarkan data real
    function markJadwalDays(days, currentMonth, currentYear) {
        // Format bulan dan tahun untuk matching
        const currentMonthFormatted = String(currentMonth + 1).padStart(2, '0');
        const currentYearFormatted = String(currentYear);
        
        days.forEach(day => {
            if (day.classList.contains('empty')) return;
            
            const dayNumber = parseInt(day.textContent);
            const dayFormatted = String(dayNumber).padStart(2, '0');
            const fullDate = `${currentYearFormatted}-${currentMonthFormatted}-${dayFormatted}`;
            
            // Reset classes
            day.classList.remove('available', 'partial', 'booked');
            
            // Cari jadwal untuk tanggal ini
            const dayJadwals = jadwalData.filter(jadwal => {
                try {
                    const jadwalDate = new Date(jadwal.date);
                    const calendarDate = new Date(currentYear, currentMonth, dayNumber);
                    return jadwalDate.toDateString() === calendarDate.toDateString();
                } catch (error) {
                    console.error('Error processing jadwal:', jadwal, error);
                    return false;
                }
            });
            
            if (dayJadwals.length > 0) {
                // Hitung jumlah slot yang sudah booked
                const bookedSlots = dayJadwals.filter(jadwal => jadwal.status === 'Booked').length;
                const totalSlots = dayJadwals.length;
                
                if (bookedSlots === totalSlots) {
                    // Semua slot sudah dibooking -> Penuh
                    day.classList.add('booked');
                } else if (bookedSlots >= totalSlots * 0.5) {
                    // Lebih dari 50% slot sudah dibooking -> Sebagian terisi
                    day.classList.add('partial');
                } else {
                    // Kurang dari 50% slot sudah dibooking -> Masih banyak tersedia
                    day.classList.add('available');
                }
            } else {
                // Tidak ada jadwal -> Tersedia (atau tidak ada jadwal yang dibuat)
                day.classList.add('available');
            }
            
            // Tambah tooltip dengan info detail
            addJadwalTooltip(day, dayJadwals, fullDate);
        });
    }
    
    // Fungsi untuk menambahkan tooltip dengan info jadwal
    function addJadwalTooltip(dayElement, dayJadwals, fullDate) {
        let tooltipContent = `<strong>${fullDate}</strong><br>`;
        
        if (dayJadwals.length === 0) {
            tooltipContent += 'Tidak ada jadwal tersedia';
        } else {
            const bookedCount = dayJadwals.filter(j => j.status === 'Booked').length;
            const availableCount = dayJadwals.filter(j => j.status === 'Available').length;
            
            tooltipContent += `Booked: ${bookedCount} slot<br>`;
            tooltipContent += `Available: ${availableCount} slot<br><br>`;
            
            // Tampilkan detail waktu
            dayJadwals.forEach(jadwal => {
                const statusIcon = jadwal.status === 'Booked' ? 
                    '<span style="color: var(--danger)">⛔</span>' : 
                    '<span style="color: var(--success)">✅</span>';
                
                tooltipContent += `${statusIcon} ${jadwal.start_time} - ${jadwal.end_time}<br>`;
            });
        }
        
        dayElement.setAttribute('title', tooltipContent);
        dayElement.setAttribute('data-bs-toggle', 'tooltip');
        dayElement.setAttribute('data-bs-html', 'true');
    }
    
    // Fungsi untuk membuat kalender
    function generateCalendar(month, year) {
        const calendarDays = document.getElementById('calendar-days');
        const calendarMonth = document.getElementById('calendar-month');
        
        // Set nama bulan dalam bahasa Indonesia
        const monthNames = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        
        calendarMonth.textContent = `${monthNames[month]} ${year}`;
        
        // Kosongkan kalender
        calendarDays.innerHTML = '';
        
        // Dapatkan hari pertama dan jumlah hari dalam bulan
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        // Tambahkan sel kosong untuk hari-hari sebelum bulan dimulai
        for (let i = 0; i < firstDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.classList.add('calendar-day', 'empty');
            calendarDays.appendChild(emptyDay);
        }
        
        // Tambahkan hari-hari dalam bulan
        for (let i = 1; i <= daysInMonth; i++) {
            const day = document.createElement('div');
            day.classList.add('calendar-day');
            day.textContent = i;
            calendarDays.appendChild(day);
        }
        
        // Tandai hari dengan status jadwal berdasarkan data real
        const days = document.querySelectorAll('.calendar-day:not(.empty)');
        markJadwalDays(days, month, year);
        
        // Inisialisasi tooltip Bootstrap
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Fungsi untuk menyesuaikan kalender berdasarkan ukuran layar
    function adjustCalendarForScreenSize() {
        const calendarDays = document.querySelectorAll('.calendar-day');
        const calendarWeekdays = document.querySelectorAll('.calendar-weekday');
        
        if (window.innerWidth <= 576) {
            // Mobile kecil - sesuaikan ukuran elemen kalender
            calendarDays.forEach(day => {
                if (!day.classList.contains('empty')) {
                    day.style.fontSize = '0.8rem';
                    day.style.height = '32px';
                }
            });
            
            calendarWeekdays.forEach(weekday => {
                weekday.style.fontSize = '0.8rem';
                weekday.style.padding = '6px 0';
            });
        } else if (window.innerWidth <= 768) {
            // Mobile landscape - ukuran sedang
            calendarDays.forEach(day => {
                if (!day.classList.contains('empty')) {
                    day.style.fontSize = '0.85rem';
                    day.style.height = '35px';
                }
            });
        } else {
            // Desktop - reset ke ukuran normal
            calendarDays.forEach(day => {
                if (!day.classList.contains('empty')) {
                    day.style.fontSize = '';
                    day.style.height = '40px';
                }
            });
            
            calendarWeekdays.forEach(weekday => {
                weekday.style.fontSize = '';
                weekday.style.padding = '8px 0';
            });
        }
    }
    
    // Fungsi untuk menyesuaikan tata letak fasilitas berdasarkan ukuran layar
    function adjustFacilitiesLayout() {
        const facilitiesGrid = document.querySelector('.facilities-grid');
        
        if (window.innerWidth <= 576) {
            facilitiesGrid.style.gridTemplateColumns = '1fr';
        } else if (window.innerWidth <= 768) {
            facilitiesGrid.style.gridTemplateColumns = 'repeat(2, 1fr)';
        } else if (window.innerWidth <= 992) {
            facilitiesGrid.style.gridTemplateColumns = 'repeat(3, 1fr)';
        } else {
            facilitiesGrid.style.gridTemplateColumns = 'repeat(3, 1fr)';
        }
    }
    
    // Event listener untuk resize window
    window.addEventListener('resize', function() {
        adjustCalendarForScreenSize();
        adjustFacilitiesLayout();
    });
    
    // Inisialisasi kalender saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        
        generateCalendar(currentMonth, currentYear);
        
        // Panggil fungsi responsive saat halaman dimuat
        adjustCalendarForScreenSize();
        adjustFacilitiesLayout();
        
        // Tambahkan class untuk touch devices
        if ('ontouchstart' in window || navigator.maxTouchPoints) {
            document.body.classList.add('touch-device');
        }
        
        // Tambahkan event listener untuk tombol navigasi bulan
        document.getElementById('prev-month').addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            generateCalendar(currentMonth, currentYear);
            adjustCalendarForScreenSize();
        });
        
        document.getElementById('next-month').addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            generateCalendar(currentMonth, currentYear);
            adjustCalendarForScreenSize();
        });

        // Tambahkan event listener untuk klik pada hari di kalender
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('calendar-day') && 
                !e.target.classList.contains('empty') && 
                !e.target.classList.contains('booked')) {
                
                const dayNumber = e.target.textContent;
                const monthNames = [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];
                
                const selectedDate = `${dayNumber} ${monthNames[currentMonth]} ${currentYear}`;
                
                // Tampilkan modal detail jadwal
                showJadwalModal(selectedDate, dayNumber, currentMonth, currentYear);
            }
        });
    });
    
    // Fungsi untuk menampilkan modal jadwal detail
    function showJadwalModal(dateString, day, month, year) {
        const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        // Cari jadwal untuk tanggal ini
        const dayJadwals = jadwalData.filter(jadwal => {
            try {
                const jadwalDate = new Date(jadwal.date);
                const selectedDate = new Date(year, month, day);
                return jadwalDate.toDateString() === selectedDate.toDateString();
            } catch (error) {
                console.error('Error filtering jadwals:', error);
                return false;
            }
        });
        
        let modalContent = `<h5>Jadwal ${dateString}</h5>`;
        
        if (dayJadwals.length === 0) {
            modalContent += `<p class="text-muted">📅 Belum ada jadwal untuk tanggal ini</p>`;
        } else {
            const availableSlots = dayJadwals.filter(j => j.status === 'Available');
            const bookedSlots = dayJadwals.filter(j => j.status === 'Booked');
            
            modalContent += `
                <div class="mb-3">
                    <span class="badge bg-success">Tersedia: ${availableSlots.length} slot</span>
                    <span class="badge bg-danger ms-2">Booked: ${bookedSlots.length} slot</span>
                </div>
            `;
            
            modalContent += `<div class="jadwal-list" style="max-height: 300px; overflow-y: auto;">`;
            dayJadwals.forEach(jadwal => {
                const statusClass = jadwal.status === 'Booked' ? 'text-danger' : 'text-success';
                const statusIcon = jadwal.status === 'Booked' ? '⛔' : '✅';
                
                modalContent += `
                    <div class="jadwal-item ${statusClass}" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 8px; background: #f8f9fa;">
                        ${statusIcon} ${jadwal.start_time} - ${jadwal.end_time}
                        <small class="ms-2">(${jadwal.status})</small>
                    </div>
                `;
            });
            modalContent += `</div>`;
        }
        
        // Gunakan modal Bootstrap
        const modal = new bootstrap.Modal(document.getElementById('jadwalModal'));
        document.getElementById('jadwalModalBody').innerHTML = modalContent;
        document.getElementById('jadwalModalTitle').textContent = `Jadwal ${dateString}`;
        modal.show();
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.venue', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\CariArena\resources\views/venue/venue-saya/lihat-detail-venue.blade.php ENDPATH**/ ?>