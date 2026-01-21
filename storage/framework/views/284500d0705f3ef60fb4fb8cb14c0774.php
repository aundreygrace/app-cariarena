

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<section class="cards">
    <div class="card">
        <small>Total Booking Hari Ini</small>
        <h3><?php echo e($todayBookingsCount); ?></h3>
        <small><?php echo e($todayBookingsCount > 0 ? '+12% dari kemarin' : 'Belum ada booking hari ini'); ?></small>
    </div>
    <div class="card">
        <small>Pendapatan Minggu Ini</small>
        <h3>Rp <?php echo e(number_format($weeklyRevenue, 0, ',', '.')); ?></h3>
        <small><?php echo e($weeklyRevenue > 0 ? '+8% dari minggu lalu' : 'Belum ada pendapatan'); ?></small>
    </div>
    <div class="card">
        <small>Rating Rata-rata</small>
        <h3><?php echo e($averageRating); ?></h3>
        <small>dari <?php echo e($totalReviews); ?> review</small>
    </div>
    <div class="card">
        <small>Tingkat Okupansi</small>
        <h3><?php echo e($occupancyRate); ?>%</h3>
        <small>Bulan <?php echo e(\Carbon\Carbon::now()->translatedFormat('F')); ?></small>
    </div>
</section>

<section class="content">
    <div class="booking">
        <div class="panel-header">
            <h4>📅 Booking Terbaru</h4>
            <a href="/venue/booking-masuk" class="lihat-semua">Lihat Semua <i class="fas fa-chevron-right"></i></a>
        </div>
        <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="booking-item">
            <div class="booking-info">
                <strong><?php echo e($booking->nama_customer); ?></strong>
                <p>
                    <?php if($booking->venue): ?>
                        <?php echo e($booking->venue->name); ?> — 
                    <?php else: ?>
                        Venue — 
                    <?php endif; ?>
                    <?php echo e(\Carbon\Carbon::parse($booking->waktu_booking)->format('H:i')); ?> - 
                    <?php echo e(\Carbon\Carbon::parse($booking->end_time)->format('H:i')); ?> 
                    (<?php echo e($booking->durasi); ?> jam)
                </p>
                <small><?php echo e(\Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d F Y')); ?></small>
            </div>
            <div>
                <strong>Rp<?php echo e(number_format($booking->total_biaya, 0, ',', '.')); ?></strong>
                <div class="status <?php echo e($booking->status == 'Terkonfirmasi' || $booking->status == 'Selesai' ? 'confirmed' : 'pending'); ?>">
                    <?php echo e($booking->status); ?>

                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="booking-item">
            <div class="booking-info">
                <strong>Belum ada booking</strong>
                <p>Booking akan muncul di sini ketika ada customer yang membooking venue Anda</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="notif">
        <div class="panel-header">
            <h4>🔔 Notifikasi</h4>
            <a href="/venue/notifikasi" class="lihat-semua">Lihat Semua <i class="fas fa-chevron-right"></i></a>
        </div>
        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="notif-item">
            <div>
                <strong><?php echo e($notification->title); ?></strong>
                <p><?php echo e($notification->message); ?></p>
            </div>
            <small><?php echo e(\Carbon\Carbon::parse($notification->created_at)->diffForHumans()); ?></small>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="notif-item">
            <div>
                <strong>Tidak ada notifikasi</strong>
                <p>Notifikasi akan muncul di sini</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="aksi">
    <button class="btn-blue" data-bs-toggle="modal" data-bs-target="#tambahVenueModal">
        <i class="fas fa-plus"></i>
        <span>Tambah Venue</span>
    </button>
    <button class="btn-green" onclick="window.location.href='/venue/jadwal'">
        <i class="fas fa-calendar-alt"></i>
        <span>Atur Jadwal</span>
    </button>
    <button class="btn-yellow" onclick="window.location.href='/venue/reviews'">
        <i class="fas fa-star"></i>
        <span>Lihat Review</span>
    </button>
</section>

<!-- Modal Tambah Venue -->
<div class="modal fade" id="tambahVenueModal" tabindex="-1" aria-labelledby="tambahVenueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="tambahVenueModalLabel">Tambah Venue Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/venue/tambah" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p class="text-muted">Isi informasi venue baru Anda</p>
                    
                    <div class="row">
                        <!-- Kolom Kiri: Informasi Dasar -->
                        <div class="col-md-6">
                            <div class="form-section">
                                <h6 class="form-section-title">Informasi Dasar</h6>
                                
                                <div class="mb-3">
                                    <label for="namaVenue" class="form-label required">Nama Venue</label>
                                    <input type="text" class="form-control" id="namaVenue" name="name" placeholder="Masukkan nama venue" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="kategori" class="form-label required">Kategori</label>
                                    <select class="form-select" id="kategori" name="category" required>
                                        <option value="" selected disabled>Pilih kategori</option>
                                        <option value="futsal">Futsal</option>
                                        <option value="basket">Basket</option>
                                        <option value="badminton">Badminton</option>
                                        <option value="tenis">Tenis</option>
                                        <option value="voli">Voli</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="lokasi" class="form-label required">Lokasi</label>
                                    <input type="text" class="form-control" id="lokasi" name="address" placeholder="Masukkan lokasi venue" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="harga" class="form-label required">Harga per Jam</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="harga" name="price_per_hour" placeholder="150000" value="150000" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Fasilitas</label>
                                    <div class="facilities-grid">
                                        <?php $__currentLoopData = ['parking', 'toilet', 'canteen', 'ac', 'prayer_room', 'locker_room', 'waiting_room', 'sound_system']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="<?php echo e($facility); ?>" id="check<?php echo e(ucfirst(str_replace('_', '', $facility))); ?>" name="facilities[]">
                                            <label class="form-check-label" for="check<?php echo e(ucfirst(str_replace('_', '', $facility))); ?>">
                                                <?php
                                                    $facilityNames = [
                                                        'parking' => 'Tempat Parkir',
                                                        'toilet' => 'Toilet',
                                                        'canteen' => 'Kantin',
                                                        'ac' => 'AC',
                                                        'prayer_room' => 'Musholla',
                                                        'locker_room' => 'Ruang Ganti',
                                                        'waiting_room' => 'Ruang Tunggu',
                                                        'sound_system' => 'Sound System'
                                                    ];
                                                ?>
                                                <?php echo e($facilityNames[$facility] ?? ucwords(str_replace('_', ' ', $facility))); ?>

                                            </label>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kolom Kanan: Status & Gambar -->
                        <div class="col-md-6">
                            <div class="form-section">
                                <h6 class="form-section-title">Status & Gambar</h6>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label required">Status Venue</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="" selected disabled>Pilih status</option>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Tidak Aktif</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Gambar Venue</label>
                                    <div class="file-upload-container">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>Unggah gambar venue (opsional)</p>
                                        <div class="file-input-wrapper">
                                            <button type="button" class="file-input-label">
                                                <i class="fas fa-upload me-2"></i> Pilih File
                                            </button>
                                            <input type="file" id="gambarVenue" name="photo" accept="image/*">
                                        </div>
                                        <small class="text-muted" id="file-name">No file chosen</small>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="deskripsi" name="description" rows="3" placeholder="Deskripsi venue"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-simpan">Simpan Venue</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ==== CARD STATS ==== */
    .cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }

    .card {
        background: var(--card-bg);
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }

    /* Border left warna-warni untuk setiap card */
    .card:nth-child(1) {
        border-left: 4px solid #4299E1; /* Biru untuk Total Booking */
    }
    
    .card:nth-child(2) {
        border-left: 4px solid #48BB78; /* Hijau untuk Pendapatan */
    }
    
    .card:nth-child(3) {
        border-left: 4px solid #ED8936; /* Oranye untuk Rating */
    }
    
    .card:nth-child(4) {
        border-left: 4px solid #9F7AEA; /* Ungu untuk Tingkat Okupansi */
    }

    /* Warna teks untuk setiap card */
    .card:nth-child(1) h3 {
        color: #4299E1; /* Biru untuk Total Booking */
    }
    
    .card:nth-child(2) h3 {
        color: #48BB78; /* Hijau untuk Pendapatan */
    }
    
    .card:nth-child(3) h3 {
        color: #ED8936; /* Oranye untuk Rating */
    }
    
    .card:nth-child(4) h3 {
        color: #9F7AEA; /* Ungu untuk Tingkat Okupansi */
    }

    .card small {
        color: var(--text-light);
        font-size: 13px;
        display: block;
    }

    .card h3 {
        margin: 10px 0;
        font-size: 26px;
        font-weight: 700;
    }

    /* ==== CONTENT ==== */
    .content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
    }

    .booking, .notif {
        background: var(--card-bg);
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .booking h4, .notif h4 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 14px;
        color: var(--primary-color);
    }

    .booking-item, .notif-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .booking-item:last-child, .notif-item:last-child {
        border-bottom: none;
    }

    .booking-info strong {
        font-size: 14px;
        display: block;
        margin-bottom: 4px;
    }

    .booking-info p {
        font-size: 13px;
        color: var(--text-light);
        margin: 0;
    }

    .status {
        font-size: 12px;
        border-radius: 6px;
        padding: 3px 8px;
        font-weight: 500;
        display: inline-block;
        margin-top: 5px;
    }

    .confirmed {
        background: #C6F6D5;
        color: var(--success);
    }

    .pending {
        background: #FEFCBF;
        color: var(--warning);
    }

    /* ==== NOTIFIKASI ==== */
    .notif-item div {
        flex: 1;
    }

    .notif-item strong {
        font-size: 14px;
        display: block;
        margin-bottom: 4px;
    }

    .notif-item p {
        font-size: 13px;
        color: var(--text-light);
        margin: 0;
    }

    .notif-item small {
        color: var(--text-light);
        font-size: 12px;
        white-space: nowrap;
        margin-left: 10px;
    }

    /* ==== AKSI CEPAT ==== */
    .aksi {
        background: var(--card-bg);
        border-radius: 14px;
        padding: 20px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        text-align: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .aksi button {
        border: none;
        border-radius: 14px;
        padding: 16px;
        color: #fff;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .aksi button i {
        font-size: 24px;
        margin-bottom: 8px;
    }

    .aksi button:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .btn-blue {
        background: var(--btn-blue);
    }

    .btn-green {
        background: var(--btn-green);
    }

    .btn-yellow {
        background: var(--btn-yellow);
    }

    /* ==== STYLE UNTUK TOMBOL LIHAT SEMUA ==== */
    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .lihat-semua {
        color: var(--primary-color);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .lihat-semua:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    .lihat-semua i {
        margin-left: 5px;
        font-size: 12px;
    }

    /* ==== MODAL TAMBAH VENUE ==== */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px 12px 0 0;
        padding: 1.2rem 1.5rem;
    }

    .modal-title {
        color: white;
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

    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }

    .form-check {
        margin-bottom: 0.5rem;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .file-upload-container {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        background-color: #f8f9fa;
        margin-bottom: 1rem;
    }

    .file-upload-container i {
        font-size: 2rem;
        color: var(--text-light);
        margin-bottom: 0.5rem;
    }

    .file-upload-container p {
        margin-bottom: 0.75rem;
        color: var(--text-light);
        font-size: 0.9rem;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-input-wrapper input[type="file"] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-input-label {
        display: inline-block;
        padding: 0.5rem 1rem;
        background-color: white;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        color: var(--text-dark);
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .btn-simpan {
        background: linear-gradient(135deg, var(--primary-color) 70%, var(--primary-hover) 100%);
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        height: 42px;
        width: 20%;
    }

    .btn-simpan :hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 179, 237, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-outline-secondary {
        border: 1.5px solid #E5E7EB;
        color: #6B7280;
        background: white;
        border-radius: 8px;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        height: 42px;
        width: 10%;
    }

    .btn-outline-secondary:hover {
        background: #F9FAFB;
        border-color: #D1D5DB;
        color: #374151;
        transform: translateY(-2px);
        text-decoration: none;
    }

    .btn-close {
        color : #ffff ;
        background-color: #ffff;
    }


    .file-input-label:hover {
        background-color: #f8f9fa;
    }

    /* ========== RESPONSIVE DESIGN ========== */
    /* Tablet */
    @media (max-width: 1024px) {
        .cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .content {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .aksi {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .aksi button {
            flex-direction: row;
            padding: 12px 15px;
        }
        
        .aksi button i {
            margin-bottom: 0;
            margin-right: 8px;
            font-size: 20px;
        }
        
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .lihat-semua {
            align-self: flex-end;
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .cards {
            grid-template-columns: 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .card {
            padding: 15px;
        }
        
        .card h3 {
            font-size: 22px;
        }
        
        .booking, .notif {
            padding: 15px;
        }
        
        .booking-item, .notif-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 10px 0;
        }
        
        .booking-item > div:last-child {
            margin-top: 8px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .notif-item {
            flex-direction: row;
        }
        
        .notif-item small {
            margin-left: 0;
            margin-top: 5px;
        }
        
        .aksi {
            grid-template-columns: 1fr;
            gap: 12px;
            padding: 15px;
        }
        
        .aksi button {
            padding: 14px;
            flex-direction: row;
            justify-content: flex-start;
        }
        
        .aksi button i {
            margin-bottom: 0;
            margin-right: 10px;
            font-size: 20px;
        }
        
        .facilities-grid {
            grid-template-columns: 1fr;
        }
        
        .modal-dialog.modal-lg {
            margin: 10px;
            max-width: calc(100% - 20px);
        }
        
        .modal-body {
            padding: 1rem;
        }
        
        .modal-body .row {
            flex-direction: column;
        }
        
        .modal-body .col-md-6 {
            width: 100%;
        }
    }

    /* Small Mobile */
    @media (max-width: 480px) {
        .card {
            padding: 12px;
        }
        
        .card h3 {
            font-size: 20px;
        }
        
        .booking, .notif {
            padding: 12px;
        }
        
        .booking h4, .notif h4 {
            font-size: 15px;
        }
        
        .aksi {
            padding: 12px;
        }
        
        .aksi button {
            font-size: 14px;
            padding: 12px;
        }
        
        .aksi button i {
            font-size: 18px;
        }
        
        .panel-header {
            margin-bottom: 12px;
        }
        
        .panel-header h4 {
            font-size: 15px;
        }
        
        .lihat-semua {
            font-size: 13px;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard loaded successfully');
        
        // File upload display
        document.getElementById('gambarVenue').addEventListener('change', function(e) {
            const fileName = e.target.files.length > 0 ? e.target.files[0].name : 'No file chosen';
            document.getElementById('file-name').textContent = fileName;
        });
        
        // Aksi cepat buttons
        document.querySelector('.btn-green').addEventListener('click', function() {
            window.location.href = '/venue/jadwal';
        });
        
        document.querySelector('.btn-yellow').addEventListener('click', function() {
            window.location.href = '/venue/reviews';
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.venue', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\CariArena\resources\views/venue/dashboard/index.blade.php ENDPATH**/ ?>