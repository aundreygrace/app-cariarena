

<?php $__env->startSection('title', 'Manajemen Booking - CariArena'); ?>
<?php $__env->startSection('page-title', 'Manajemen Booking'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* === VARIABLES BERDASARKAN STYLE VENUE === */
    :root {
        --primary-color: #63b3ed;
        --primary-hover: #4299e1;
        --card-bg: #ffffff;
        --text-dark: #2d3748;
        --text-light: #718096;
        --success: #48bb78;
        --warning: #ed8936;
        --danger: #f56565;
        --info: #4299e1;
    }

    /* === STATISTIC CARDS - STYLE KONSISTEN SEPERTI MANAJEMEN PENGGUNA === */
    .cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 25px;
        max-width: 100%;
    }

    .card {
        background: var(--card-bg);
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        min-height: 120px;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Border left warna-warni untuk setiap card */
    .card:nth-child(1) {
        border-left: 4px solid #4299E1;
    }
    
    .card:nth-child(2) {
        border-left: 4px solid #48BB78;
    }
    
    .card:nth-child(3) {
        border-left: 4px solid #ED8936;
    }
    
    .card:nth-child(4) {
        border-left: 4px solid #9F7AEA;
    }

    /* Warna teks untuk setiap card */
    .card:nth-child(1) h3 {
        color: #4299E1;
    }
    
    .card:nth-child(2) h3 {
        color: #48BB78;
    }
    
    .card:nth-child(3) h3 {
        color: #ED8936;
    }
    
    .card:nth-child(4) h3 {
        color: #9F7AEA;
    }

    .card small {
        color: var(--text-light);
        font-size: 13px;
        display: block;
        font-weight: 500;
    }

    .card h3 {
        margin: 10px 0;
        font-size: 26px;
        font-weight: 700;
        line-height: 1.2;
    }

    .card small:last-of-type {
        font-size: 12px;
        color: var(--text-light);
        opacity: 0.8;
        margin-top: 5px;
    }

    /* === FILTER CARD - STYLE KONSISTEN === */
    .filter-card {
        background: var(--card-bg);
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        margin-bottom: 24px;
        border: 1px solid #e2e8f0;
        max-width: 100%;
        overflow: hidden;
    }

    .filter-card .section-header {
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .filter-card .section-header h5 {
        font-size: 16px;
        margin: 0;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-form .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
        font-size: 0.9rem;
        display: block;
    }

    .filter-form .form-control,
    .filter-form .form-select {
        border: 1.5px solid #E5E7EB;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .filter-form .form-control:focus,
    .filter-form .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(99, 179, 237, 0.1);
        outline: none;
    }

    /* === BUTTONS - STYLE KONSISTEN === */
    .btn-tambah {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
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
        width: 100%;
        min-width: 120px;
    }

    .btn-tambah:hover {
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
        padding: 10px 20px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        height: 42px;
        width: 100%;
        min-width: 120px;
    }

    .btn-outline-secondary:hover {
        background: #F9FAFB;
        border-color: #D1D5DB;
        color: #374151;
        transform: translateY(-2px);
        text-decoration: none;
    }

    /* === PERBAIKAN GRID UNTUK TOMBOL SEBARIS === */
    .filter-buttons {
        display: flex;
        gap: 10px;
        height: 100%;
        align-items: flex-end;
    }

    .filter-buttons .btn-tambah,
    .filter-buttons .btn-outline-secondary {
        height: 42px;
        flex: 1;
        min-width: auto;
    }

    /* === PAGINATION STYLES - BARU DITAMBAHKAN === */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 15px;
    }

    .pagination-info {
        font-size: 0.85rem;
        color: var(--text-light);
        white-space: nowrap;
    }

    .pagination {
        display: flex;
        gap: 6px;
        margin: 0;
        padding: 0;
        list-style: none;
        flex-wrap: wrap;
    }

    .page-item {
        display: inline-block;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--text-dark);
        background-color: white;
        border: 1.5px solid #E5E7EB;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .page-link:hover {
        background-color: #F9FAFB;
        border-color: #D1D5DB;
        color: var(--primary-color);
        transform: translateY(-1px);
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 2px 4px rgba(99, 179, 237, 0.2);
    }

    .page-item.disabled .page-link {
        color: #9CA3AF;
        background-color: #F3F4F6;
        border-color: #E5E7EB;
        cursor: not-allowed;
        transform: none;
    }

    .page-item.disabled .page-link:hover {
        background-color: #F3F4F6;
        border-color: #E5E7EB;
        color: #9CA3AF;
        transform: none;
    }

    .page-link-dots {
        min-width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-light);
        font-weight: 500;
    }

    /* Tombol next/previous lebih besar */
    .page-item:first-child .page-link,
    .page-item:last-child .page-link {
        min-width: 42px;
        font-weight: 600;
    }

    /* === ITEMS PER PAGE SELECTOR === */
    .items-per-page {
        display: flex;
        align-items: center;
        gap: 10px;
        white-space: nowrap;
    }

    .items-per-page-label {
        font-size: 0.85rem;
        color: var(--text-light);
    }

    .pagination-select {
        border: 1.5px solid #E5E7EB;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.85rem;
        color: var(--text-dark);
        background-color: white;
        transition: all 0.3s ease;
        cursor: pointer;
        min-width: 80px;
    }

    .pagination-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(99, 179, 237, 0.1);
        outline: none;
    }

    .pagination-select:hover {
        border-color: #D1D5DB;
    }

    /* === TABLE STYLES - STYLE KONSISTEN === */
    .dashboard-section {
        background: var(--card-bg);
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        max-width: 100%;
        overflow: hidden;
    }

    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
        max-width: 100%;
    }

    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        font-size: 0.875rem;
    }

    .table th {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 12px 8px;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .table td {
        padding: 12px 8px;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* === BADGES - STYLE KONSISTEN === */
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
        border-radius: 6px;
    }

    .badge-confirmed {
        background-color: #E8F5E8;
        color: var(--success);
    }

    .badge-pending {
        background-color: #FFF3E0;
        color: var(--warning);
    }

    .badge-cancelled {
        background-color: #FFEBEE;
        color: var(--danger);
    }

    .badge-completed {
        background-color: #E3F2FD;
        color: #1976D2;
    }

    /* === EMPTY STATE === */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--text-light);
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-light);
        margin: 0;
    }

    /* === ACTION BUTTONS - STYLE KONSISTEN === */
    .btn-group-sm .btn {
        padding: 0.4rem 0.6rem;
        font-size: 0.75rem;
        margin: 1px;
        border: none;
        background: transparent !important;
    }

    .btn-info {
        color: var(--info) !important;
        background: transparent !important;
        border: none !important;
        padding: 0.4rem 0.6rem;
    }

    .btn-info:hover {
        color: var(--primary-hover) !important;
        background: transparent !important;
        transform: translateY(-1px);
    }

    .btn-info .fas {
        color: inherit !important;
    }

    /* === MODAL STYLES - STYLE KONSISTEN === */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        border-bottom: none;
        padding: 15px 20px;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 15px 20px;
    }

    .booking-detail-item {
        display: flex;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
    }

    .booking-detail-label {
        font-weight: 600;
        color: var(--text-dark);
        min-width: 120px;
    }

    .booking-detail-value {
        color: var(--text-light);
    }

    .booking-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        margin-right: 15px;
    }

    .booking-profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .booking-profile-info h4 {
        margin: 0 0 5px 0;
        color: var(--text-dark);
        font-size: 18px;
    }

    .booking-profile-info p {
        margin: 0;
        color: var(--text-light);
        font-size: 14px;
    }

    /* === ALERT STYLES === */
    .alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .alert-success {
        background-color: #f0fff4;
        color: #22543d;
        border-left: 4px solid var(--success);
    }

    .alert-danger {
        background-color: #fff5f5;
        color: #742a2a;
        border-left: 4px solid var(--danger);
    }

    /* Panel header styling */
    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .panel-header h5 {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        color: var(--text-dark);
    }

    /* Tombol Primary Custom */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(99, 179, 237, 0.3);
        color: white;
    }

    /* ========== RESPONSIVE DESIGN ========== */
    /* Untuk layar yang lebih kecil dari desktop */
    @media (max-width: 1200px) {
        .cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .filter-buttons {
            flex-direction: column;
        }
    }

    @media (max-width: 768px) {
        .cards {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .card {
            padding: 15px;
            text-align: left;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            min-width: 800px;
        }
        
        .filter-form .row {
            flex-direction: column;
            gap: 12px;
        }
        
        .filter-form .col-md-4,
        .filter-form .col-md-2,
        .filter-form .col-md-4 {
            width: 100%;
        }
        
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .filter-buttons {
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }
        
        .filter-buttons .btn-tambah,
        .filter-buttons .btn-outline-secondary {
            width: 100%;
        }
        
        .pagination-container {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }
        
        .pagination-info {
            text-align: center;
            order: 1;
        }
        
        .items-per-page {
            justify-content: center;
            order: 2;
        }
        
        .pagination {
            justify-content: center;
            order: 3;
        }
        
        .page-link {
            min-width: 32px;
            height: 32px;
            padding: 0 10px;
            font-size: 0.8rem;
        }
        
        .page-link-dots {
            min-width: 32px;
            height: 32px;
        }
        
        .page-item:first-child .page-link,
        .page-item:last-child .page-link {
            min-width: 38px;
        }
    }

    @media (max-width: 480px) {
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .card {
            padding: 15px;
        }
        
        .filter-card {
            padding: 15px;
        }
        
        .dashboard-section {
            padding: 15px;
        }
        
        .modal-content {
            margin: 10px;
            max-width: calc(100% - 20px);
        }
        
        .btn-tambah,
        .btn-outline-secondary {
            padding: 10px 14px;
            font-size: 13px;
        }
        
        .pagination {
            gap: 4px;
        }
        
        .page-link {
            min-width: 28px;
            height: 28px;
            padding: 0 8px;
            font-size: 0.75rem;
        }
        
        .page-link-dots {
            min-width: 28px;
            height: 28px;
        }
        
        .page-item:first-child .page-link,
        .page-item:last-child .page-link {
            min-width: 32px;
        }
        
        .items-per-page-label {
            font-size: 0.8rem;
        }
        
        .pagination-select {
            padding: 5px 10px;
            font-size: 0.8rem;
            min-width: 70px;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistik Pemesanan -->
    <div class="cards">
        <div class="card">
            <small>Total Booking</small>
            <h3><?php echo e(number_format($totalPemesanans ?? 0)); ?></h3>
            <small>Semua booking</small>
        </div>

        <div class="card">
            <small>Booking Aktif</small>
            <h3><?php echo e(number_format($activePemesanans ?? 0)); ?></h3>
            <small>Booking aktif hari ini</small>
        </div>

        <div class="card">
            <small>Menunggu Konfirmasi</small>
            <h3><?php echo e(number_format($pendingPemesanans ?? 0)); ?></h3>
            <small>Booking pending</small>
        </div>

        <div class="card">
            <small>Tingkat Keterlibatan</small>
            <h3><?php echo e($occupancyRate ?? 0); ?>%</h3>
            <small>Rata-rata okupansi venue</small>
        </div>
    </div>

    <!-- Search dan Filter - PERBAIKAN TOMBOL SEBARIS -->
    <div class="filter-card">
        <div class="section-header">
            <h5>🔍 Filter Booking</h5>
        </div>
        <div class="section-body">
            <form method="GET" action="<?php echo e(route('admin.pemesanan.index')); ?>" id="filterForm" class="filter-form">
                <input type="hidden" name="per_page" id="perPageHidden" value="<?php echo e(request('per_page', 10)); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Cari Booking</label>
                        <input type="text" class="form-control" name="search" id="searchInput" 
                               placeholder="Cari nama customer atau kode booking..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" <?php echo e(request('status') == 'Menunggu' ? 'selected' : ''); ?>>Menunggu</option>
                            <option value="Terkonfirmasi" <?php echo e(request('status') == 'Terkonfirmasi' ? 'selected' : ''); ?>>Terkonfirmasi</option>
                            <option value="Selesai" <?php echo e(request('status') == 'Selesai' ? 'selected' : ''); ?>>Selesai</option>
                            <option value="Dibatalkan" <?php echo e(request('status') == 'Dibatalkan' ? 'selected' : ''); ?>>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Venue</label>
                        <select class="form-select" name="venue_id" id="venueFilter">
                            <option value="">Semua Venue</option>
                            <?php $__currentLoopData = $venues ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($venue->id); ?>" <?php echo e(request('venue_id') == $venue->id ? 'selected' : ''); ?>>
                                    <?php echo e($venue->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="filter-buttons">
                            <button type="submit" class="btn-tambah">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                            <a href="<?php echo e(route('admin.pemesanan.index')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Pemesanan -->
    <div class="dashboard-section">
        <div class="panel-header">
            <h5>📋 Daftar Booking</h5>
        </div>

        <div class="section-body">
            <div class="table-responsive">
                <table class="table table-hover" id="bookingsTable">
                    <thead>
                        <tr>
                            <th width="12%">Kode Booking</th>
                            <th width="15%">Nama Customer</th>
                            <th width="12%">Telepon</th>
                            <th width="15%">Venue</th>
                            <th width="18%">Tanggal & Waktu</th>
                            <th width="8%">Durasi</th>
                            <th width="12%">Total Biaya</th>
                            <th width="10%">Status</th>
                            <th width="8%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsTableBody">
                        <?php $__empty_1 = true; $__currentLoopData = $pemesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pemesanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-bold"><?php echo e($pemesanan->booking_code ?? 'B' . str_pad($pemesanan->id, 4, '0', STR_PAD_LEFT)); ?></td>
                            <td><?php echo e($pemesanan->nama_customer); ?></td>
                            <td><?php echo e($pemesanan->customer_phone ?? '-'); ?></td>
                            <td>
                                <?php if($pemesanan->venue): ?>
                                    <span class="venue-name" title="<?php echo e($pemesanan->venue->name); ?>">
                                        <?php echo e(Str::limit($pemesanan->venue->name, 20)); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="small"><?php echo e(\Carbon\Carbon::parse($pemesanan->tanggal_booking)->translatedFormat('d M Y')); ?></div>
                                <small class="text-muted">
                                    <?php echo e(\Carbon\Carbon::parse($pemesanan->waktu_booking)->format('H:i')); ?> - 
                                    <?php echo e(\Carbon\Carbon::parse($pemesanan->end_time)->format('H:i')); ?>

                                </small>
                            </td>
                            <td><?php echo e($pemesanan->durasi ?? '1'); ?> jam</td>
                            <td class="fw-bold text-success">Rp <?php echo e(number_format($pemesanan->total_biaya, 0, ',', '.')); ?></td>
                            <td>
                                <?php if($pemesanan->status == 'Terkonfirmasi'): ?>
                                    <span class="badge badge-confirmed">Terkonfirmasi</span>
                                <?php elseif($pemesanan->status == 'Menunggu'): ?>
                                    <span class="badge badge-pending">Menunggu</span>
                                <?php elseif($pemesanan->status == 'Selesai'): ?>
                                    <span class="badge badge-completed">Selesai</span>
                                <?php elseif($pemesanan->status == 'Dibatalkan'): ?>
                                    <span class="badge badge-cancelled">Dibatalkan</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?php echo e($pemesanan->status); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" title="Lihat Detail" data-bs-toggle="modal" 
                                            data-bs-target="#detailPemesananModal" 
                                            data-booking-id="<?php echo e($pemesanan->id); ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak ada data booking</h5>
                                    <p class="text-muted">Belum ada booking yang tercatat.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($pemesanans->hasPages()): ?>
            <div class="pagination-container">
                <div class="pagination-info">
                    <?php
                        $currentPage = $pemesanans->currentPage();
                        $perPage = request('per_page', 10);
                        $totalItems = $pemesanans->total();
                        
                        if ($perPage === 'all') {
                            $start = 1;
                            $end = $totalItems;
                        } else {
                            $start = ($currentPage - 1) * $perPage + 1;
                            $end = min($currentPage * $perPage, $totalItems);
                        }
                    ?>
                    Menampilkan <?php echo e($start); ?> - <?php echo e($end); ?> dari <?php echo e($totalItems); ?> pemesanan
                </div>
                
                <div class="pagination-controls">
                    <ul class="pagination">
                        
                        <?php if($pemesanans->onFirstPage()): ?>
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </li>
                        <?php else: ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($pemesanans->previousPageUrl()); ?>" rel="prev">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        
                        <?php
                            $currentPage = $pemesanans->currentPage();
                            $lastPage = $pemesanans->lastPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                            
                            if ($currentPage <= 3) {
                                $endPage = min(5, $lastPage);
                            }
                            
                            if ($currentPage >= $lastPage - 2) {
                                $startPage = max(1, $lastPage - 4);
                            }
                            
                            $showStartDots = $startPage > 1;
                            $showEndDots = $endPage < $lastPage;
                        ?>

                        
                        <?php if($showStartDots): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($pemesanans->url(1)); ?>">1</a>
                            </li>
                            <?php if($startPage > 2): ?>
                                <li class="page-item disabled">
                                    <span class="page-link-dots">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>

                        
                        <?php for($page = $startPage; $page <= $endPage; $page++): ?>
                            <?php if($page == $currentPage): ?>
                                <li class="page-item active">
                                    <span class="page-link"><?php echo e($page); ?></span>
                                </li>
                            <?php else: ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo e($pemesanans->url($page)); ?>"><?php echo e($page); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>

                        
                        <?php if($showEndDots): ?>
                            <?php if($endPage < $lastPage - 1): ?>
                                <li class="page-item disabled">
                                    <span class="page-link-dots">...</span>
                                </li>
                            <?php endif; ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($pemesanans->url($lastPage)); ?>"><?php echo e($lastPage); ?></a>
                            </li>
                        <?php endif; ?>

                        
                        <?php if($pemesanans->hasMorePages()): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($pemesanans->nextPageUrl()); ?>" rel="next">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="items-per-page">
                    <span class="items-per-page-label">Tampilkan:</span>
                    <select class="pagination-select" id="perPageSelectBottom">
                        <option value="10" <?php echo e(request('per_page', 10) == 10 ? 'selected' : ''); ?>>10</option>
                        <option value="25" <?php echo e(request('per_page', 10) == 25 ? 'selected' : ''); ?>>25</option>
                        <option value="50" <?php echo e(request('per_page', 10) == 50 ? 'selected' : ''); ?>>50</option>
                        <option value="100" <?php echo e(request('per_page', 10) == 100 ? 'selected' : ''); ?>>100</option>
                        <option value="all" <?php echo e(request('per_page') == 'all' ? 'selected' : ''); ?>>Semua</option>
                    </select>
                </div>
            </div>
            <?php else: ?>
            <!-- Tetap tampilkan info jika hanya 1 halaman -->
            <div class="pagination-container">
                <?php
                    $totalItems = $pemesanans->total();
                    $perPage = request('per_page', 10);
                    
                    if ($perPage === 'all' || $perPage >= $totalItems) {
                        $start = 1;
                        $end = $totalItems;
                        $displayText = "Semua";
                    } else {
                        $start = 1;
                        $end = min($perPage, $totalItems);
                        $displayText = "$start - $end";
                    }
                ?>
                <div class="pagination-info">
                    <?php if($perPage === 'all' || $perPage >= $totalItems): ?>
                        Menampilkan semua <?php echo e($totalItems); ?> pemesanan
                    <?php else: ?>
                        Menampilkan <?php echo e($displayText); ?> dari <?php echo e($totalItems); ?> pemesanan
                    <?php endif; ?>
                </div>
                <div class="items-per-page">
                    <span class="items-per-page-label">Tampilkan:</span>
                    <select class="pagination-select" id="perPageSelectBottom">
                        <option value="10" <?php echo e(request('per_page', 10) == 10 ? 'selected' : ''); ?>>10</option>
                        <option value="25" <?php echo e(request('per_page', 10) == 25 ? 'selected' : ''); ?>>25</option>
                        <option value="50" <?php echo e(request('per_page', 10) == 50 ? 'selected' : ''); ?>>50</option>
                        <option value="100" <?php echo e(request('per_page', 10) == 100 ? 'selected' : ''); ?>>100</option>
                        <option value="all" <?php echo e(request('per_page') == 'all' ? 'selected' : ''); ?>>Semua</option>
                    </select>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Detail Pemesanan -->
    <div class="modal fade" id="detailPemesananModal" tabindex="-1" aria-labelledby="detailPemesananModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailPemesananModalLabel">📋 Detail Booking</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailModalBody">
                    <!-- Content will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pemesanan -->
    <div class="modal fade" id="tambahPemesananModal" tabindex="-1" aria-labelledby="tambahPemesananModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPemesananModalLabel">➕ Tambah Booking Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?php echo e(route('admin.pemesanan.store')); ?>" id="tambahPemesananForm">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Customer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_customer" required 
                                       placeholder="Masukkan nama customer" value="<?php echo e(old('nama_customer')); ?>">
                                <?php $__errorArgs = ['nama_customer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telepon</label>
                                <input type="text" class="form-control" name="customer_phone" 
                                       placeholder="Masukkan nomor telepon" value="<?php echo e(old('customer_phone')); ?>">
                                <?php $__errorArgs = ['customer_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Venue <span class="text-danger">*</span></label>
                                <select class="form-select" name="venue_id" required>
                                    <option value="">Pilih Venue</option>
                                    <?php $__currentLoopData = $venues ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($venue->id); ?>" <?php echo e(old('venue_id') == $venue->id ? 'selected' : ''); ?>>
                                            <?php echo e($venue->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['venue_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_booking" required 
                                       min="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e(old('tanggal_booking')); ?>">
                                <?php $__errorArgs = ['tanggal_booking'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="waktu_booking" required 
                                       value="<?php echo e(old('waktu_booking')); ?>">
                                <?php $__errorArgs = ['waktu_booking'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="end_time" required 
                                       value="<?php echo e(old('end_time')); ?>">
                                <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Durasi (jam) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="durasi" min="1" required 
                                       value="<?php echo e(old('durasi', 1)); ?>">
                                <?php $__errorArgs = ['durasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Total Biaya <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="total_biaya" required 
                                       placeholder="Masukkan total biaya" value="<?php echo e(old('total_biaya')); ?>">
                                <?php $__errorArgs = ['total_biaya'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="Menunggu" <?php echo e(old('status') == 'Menunggu' ? 'selected' : ''); ?>>Menunggu</option>
                                    <option value="Terkonfirmasi" <?php echo e(old('status') == 'Terkonfirmasi' ? 'selected' : ''); ?>>Terkonfirmasi</option>
                                    <option value="Selesai" <?php echo e(old('status') == 'Selesai' ? 'selected' : ''); ?>>Selesai</option>
                                    <option value="Dibatalkan" <?php echo e(old('status') == 'Dibatalkan' ? 'selected' : ''); ?>>Dibatalkan</option>
                                </select>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="catatan" rows="3" 
                                      placeholder="Masukkan catatan tambahan"><?php echo e(old('catatan')); ?></textarea>
                            <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-custom">Tambah Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load detail pemesanan via AJAX
        const detailModal = document.getElementById('detailPemesananModal');
        if (detailModal) {
            detailModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const bookingId = button.getAttribute('data-booking-id');
                
                // Show loading state
                document.getElementById('detailModalBody').innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data pemesanan...</p>
                    </div>
                `;
                
                fetch(`/admin/pemesanan/${bookingId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const pemesanan = data.data;
                            document.getElementById('detailModalBody').innerHTML = `
                                <div class="booking-detail-item">
                                    <div class="booking-detail-label">Kode Booking</div>
                                    <div class="booking-detail-value fw-bold">${pemesanan.booking_code || 'B' + String(pemesanan.id).padStart(4, '0')}</div>
                                </div>
                                <div class="booking-detail-item">
                                    <div class="booking-detail-label">Nama Customer</div>
                                    <div class="booking-detail-value">${pemesanan.nama_customer}</div>
                                </div>
                                <div class="booking-detail-item">
                                    <div class="booking-detail-label">Telepon</div>
                                    <div class="booking-detail-value">${pemesanan.customer_phone || '-'}</div>
                                </div>
                                <div class="booking-detail-item">
                                    <div class="booking-detail-label">Venue</div>
                                    <div class="booking-detail-value">${pemesanan.venue?.name || 'Venue tidak ditemukan'}</div>
                                </div>
                                <div class="booking-detail-item">
                                    <div class="booking-detail-label">Tanggal & Waktu</div>
                                    <div class="booking-detail-value">${new Date(pemesanan.tanggal_booking).toLocaleDateString('id-ID')} ${pemesanan.waktu_booking} - ${pemesanan.end_time}</div>
                                </div>
                                <div class="booking-detail-item">
                                    <div class="booking-detail-label">Durasi</div>
                                    <div class="booking-detail-value">${pemesanan.durasi || '1'} jam</div>
                                </div>
                                <div class="booking-detail-item">
                                    <div class="booking-detail-label">Total Biaya</div>
                                    <div class="booking-detail-value fw-bold text-success">Rp ${new Intl.NumberFormat('id-ID').format(pemesanan.total_biaya)}</div>
                                </div>
                                <div class="booking-detail-item">
                                    <div class="booking-detail-label">Status</div>
                                    <div class="booking-detail-value">
                                        ${pemesanan.status === 'Terkonfirmasi' ? '<span class="badge badge-confirmed">Terkonfirmasi</span>' : 
                                        pemesanan.status === 'Menunggu' ? '<span class="badge badge-pending">Menunggu</span>' :
                                        pemesanan.status === 'Selesai' ? '<span class="badge badge-completed">Selesai</span>' :
                                        pemesanan.status === 'Dibatalkan' ? '<span class="badge badge-cancelled">Dibatalkan</span>' :
                                        '<span class="badge badge-pending">Menunggu</span>'}
                                    </div>
                                </div>
                                <div class="booking-detail-item">
                                    <div class="booking-detail-label">Catatan</div>
                                    <div class="booking-detail-value">${pemesanan.catatan || '-'}</div>
                                </div>
                            `;
                        } else {
                            throw new Error(data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('detailModalBody').innerHTML = `
                            <div class="text-center text-danger py-4">
                                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                                <p>Terjadi kesalahan saat memuat data pemesanan</p>
                                <small>${error.message}</small>
                            </div>
                        `;
                    });
            });
        }

        // Real-time search
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#bookingsTableBody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                }, 300);
            });
        }

        // Auto-submit filter ketika dropdown berubah
        document.getElementById('statusFilter').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('venueFilter').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        // Function untuk mengubah jumlah item per halaman
        function changePerPage(value) {
            // Update hidden input di form filter
            document.getElementById('perPageHidden').value = value;
            
            // Submit form dengan per_page yang baru
            document.getElementById('filterForm').submit();
        }

        // Event listener untuk dropdown items per page
        const perPageSelectTop = document.getElementById('perPageSelect');
        const perPageSelectBottom = document.getElementById('perPageSelectBottom');
        
        if (perPageSelectTop) {
            perPageSelectTop.addEventListener('change', function() {
                changePerPage(this.value);
            });
        }
        
        if (perPageSelectBottom) {
            perPageSelectBottom.addEventListener('change', function() {
                changePerPage(this.value);
            });
        }

        // Sync nilai dropdown atas dan bawah
        if (perPageSelectTop && perPageSelectBottom) {
            perPageSelectTop.addEventListener('change', function() {
                perPageSelectBottom.value = this.value;
            });
            
            perPageSelectBottom.addEventListener('change', function() {
                perPageSelectTop.value = this.value;
            });
        }

        // Hitung durasi otomatis berdasarkan waktu mulai dan selesai
        function calculateDuration() {
            const startTime = document.querySelector('input[name="waktu_booking"]');
            const endTime = document.querySelector('input[name="end_time"]');
            const durationInput = document.querySelector('input[name="durasi"]');
            
            if (startTime && endTime && durationInput) {
                startTime.addEventListener('change', updateDuration);
                endTime.addEventListener('change', updateDuration);
            }
            
            function updateDuration() {
                if (startTime.value && endTime.value) {
                    const start = new Date(`2000-01-01T${startTime.value}`);
                    const end = new Date(`2000-01-01T${endTime.value}`);
                    const diff = (end - start) / (1000 * 60 * 60); // Convert to hours
                    
                    if (diff > 0) {
                        durationInput.value = Math.round(diff * 2) / 2; // Round to nearest 0.5
                    }
                }
            }
        }
        
        calculateDuration();

        // Auto close alert setelah 5 detik
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\CariArena\resources\views/admin/pemesanan.blade.php ENDPATH**/ ?>