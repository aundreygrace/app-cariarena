

<?php $__env->startSection('title', 'Laporan - CariArena Venue'); ?>

<?php $__env->startSection('page-title', 'Laporan'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* === VARIABLES BERDASARKAN STYLE PENGGUNA === */
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

    /* === STATISTIC CARDS - UKURAN LEBIH KOMPAK === */
    .cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 20px;
        max-width: 100%;
    }

    .card-stat {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        min-height: 100px;
        border-left: 4px solid;
    }

    .card-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Border left warna-warni untuk setiap card */
    .card-stat:nth-child(1) {
        border-left-color: #4299E1;
    }
    
    .card-stat:nth-child(2) {
        border-left-color: #48BB78;
    }
    
    .card-stat:nth-child(3) {
        border-left-color: #ED8936;
    }
    
    .card-stat:nth-child(4) {
        border-left-color: #9F7AEA;
    }

    /* Warna teks untuk setiap card */
    .card-stat:nth-child(1) h3 {
        color: #4299E1;
    }
    
    .card-stat:nth-child(2) h3 {
        color: #48BB78;
    }
    
    .card-stat:nth-child(3) h3 {
        color: #ED8936;
    }
    
    .card-stat:nth-child(4) h3 {
        color: #9F7AEA;
    }

    .card-stat small {
        color: var(--text-light);
        font-size: 12px;
        display: block;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .card-stat h3 {
        margin: 5px 0;
        font-size: 22px;
        font-weight: 700;
        line-height: 1.2;
    }

    .card-stat small:last-of-type {
        font-size: 11px;
        color: var(--text-light);
        opacity: 0.8;
        margin-top: 3px;
    }

    /* === FILTER CARD - UKURAN LEBIH KOMPAK === */
    .filter-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
        max-width: 100%;
        overflow: hidden;
    }

    .filter-card .section-header {
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .filter-card .section-header h5 {
        font-size: 14px;
        margin: 0;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-form .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 6px;
        font-size: 0.85rem;
        display: block;
    }

    .filter-form .form-control,
    .filter-form .form-select {
        border: 1.5px solid #E5E7EB;
        border-radius: 6px;
        padding: 8px 10px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        width: 100%;
        box-sizing: border-box;
        height: 38px;
    }

    .filter-form .form-control:focus,
    .filter-form .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(99, 179, 237, 0.1);
        outline: none;
    }

    /* === BUTTONS - UKURAN LEBIH KOMPAK === */
    .btn-export {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border: none;
        border-radius: 6px;
        padding: 8px 12px;
        color: white;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        height: auto;
    }

    .btn-export:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 179, 237, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-outline-secondary {
        border: 1.5px solid #E5E7EB;
        color: #6B7280;
        background: white;
        border-radius: 6px;
        padding: 8px 12px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        height: auto;
    }

    .btn-outline-secondary:hover {
        background: #F9FAFB;
        border-color: #D1D5DB;
        color: #374151;
        transform: translateY(-1px);
        text-decoration: none;
    }

    /* PERBAIKAN: Button untuk toggle chart period */
    .btn-chart-period {
        border: 1.5px solid var(--primary-color);
        color: var(--primary-color);
        background: white;
        border-radius: 6px;
        padding: 6px 12px;
        font-weight: 600;
        font-size: 0.75rem;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        height: 32px;
        min-width: 80px;
    }

    .btn-chart-period:hover {
        background: #f0f9ff;
        border-color: var(--primary-hover);
        color: var(--primary-hover);
        transform: translateY(-1px);
    }

    .btn-chart-period.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .btn-chart-period.active:hover {
        background: var(--primary-hover);
        border-color: var(--primary-hover);
        color: white;
    }

    /* === TABLE STYLES - UKURAN LEBIH KOMPAK === */
    .dashboard-section {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        max-width: 100%;
        overflow: hidden;
    }

    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        max-width: 100%;
    }

    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        font-size: 0.8rem;
        table-layout: fixed;
    }

    .table th {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 10px 6px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .table td {
        padding: 10px 6px;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 0.8rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* PERBAIKAN: Atur lebar kolom lebih kompak */
    .table th:nth-child(1),
    .table td:nth-child(1) {
        width: 15%; /* Kode Pemesanan */
        min-width: 120px;
    }

    .table th:nth-child(2),
    .table td:nth-child(2) {
        width: 20%; /* Venue */
        min-width: 150px;
    }

    .table th:nth-child(3),
    .table td:nth-child(3) {
        width: 20%; /* Customer */
        min-width: 150px;
    }

    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 15%; /* Tanggal */
        min-width: 100px;
    }

    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 15%; /* Jumlah */
        min-width: 100px;
    }

    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 10%; /* Status */
        min-width: 80px;
    }

    .table th:nth-child(7),
    .table td:nth-child(7) {
        width: 5%; /* Aksi */
        min-width: 50px;
        text-align: center;
    }

    /* === BADGES - UKURAN LEBIH KOMPAK === */
    .badge {
        font-size: 0.7rem;
        padding: 0.25em 0.5em;
        font-weight: 500;
        border-radius: 4px;
        display: inline-block;
        text-align: center;
        white-space: nowrap;
    }

    .badge-success {
        background-color: #E8F5E8;
        color: var(--success);
    }

    .badge-warning {
        background-color: #FFF3E0;
        color: var(--warning);
    }

    .badge-danger {
        background-color: #FFEBEE;
        color: var(--danger);
    }

    .badge-info {
        background-color: #E8F4FD;
        color: var(--info);
    }

    .badge-secondary {
        background-color: #F3F4F6;
        color: #6B7280;
    }

    /* === ACTION BUTTONS === */
    .btn-group-sm {
        display: flex;
        gap: 0;
        justify-content: center;
        align-items: center;
        flex-wrap: nowrap;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.4rem;
        font-size: 0.7rem;
        margin: 0;
        border: none;
        background: transparent !important;
        width: 24px;
        height: 24px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-eye {
        color: var(--info) !important;
    }

    .btn-eye:hover {
        color: var(--primary-hover) !important;
        background: rgba(66, 153, 225, 0.1) !important;
        transform: translateY(-1px);
    }

    .btn-group-sm .btn .fas {
        color: inherit !important;
        font-size: 0.65rem;
    }

    /* === MODAL STYLES === */
    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        border-bottom: none;
        padding: 12px 15px;
    }

    .modal-body {
        padding: 15px;
    }

    .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 12px 15px;
    }

    /* Section header styling */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .section-header h5 {
        font-size: 14px;
        font-weight: 600;
        margin: 0;
        color: var(--text-dark);
    }

    /* Chart Container */
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
    }

    /* Export buttons */
    .export-buttons {
        display: flex;
        gap: 8px;
    }

    /* Laporan detail styling */
    .laporan-detail-item {
        display: flex;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
    }

    .laporan-detail-label {
        font-weight: 600;
        color: var(--text-dark);
        min-width: 120px;
        flex-shrink: 0;
    }

    .laporan-detail-value {
        color: var(--text-light);
        flex: 1;
        word-break: break-word;
    }

    /* Custom date inputs */
    .custom-date-inputs {
        display: none;
    }

    /* Debug info (untuk development saja) */
    .debug-info {
        background: #FFF3CD;
        border: 1px solid #FFEAA7;
        border-radius: 4px;
        padding: 5px 8px;
        font-size: 0.7rem;
        margin: 2px 0;
        color: #856404;
        display: none; /* Sembunyikan di production */
    }

    /* ========== RESPONSIVE DESIGN ========== */
    @media (max-width: 1200px) {
        .cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
    }

    @media (max-width: 992px) {
        .table {
            font-size: 0.75rem;
        }
        
        .table th,
        .table td {
            padding: 8px 5px;
        }
        
        .btn-group-sm .btn {
            width: 22px;
            height: 22px;
            padding: 0.2rem 0.3rem;
        }
        
        .btn-group-sm .btn .fas {
            font-size: 0.6rem;
        }
        
        /* Sembunyikan kolom tertentu di tablet */
        .table th:nth-child(2),
        .table td:nth-child(2) {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .cards {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .card-stat {
            padding: 12px;
            min-height: 90px;
        }
        
        .card-stat h3 {
            font-size: 20px;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            min-width: 650px;
        }
        
        .filter-form .row {
            flex-direction: column;
            gap: 10px;
        }
        
        .filter-form .col-md {
            width: 100%;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .btn-export,
        .btn-outline-secondary {
            width: 100%;
            justify-content: center;
        }
        
        .export-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .chart-container {
            height: 200px;
        }
        
        /* PERBAIKAN: Button period di mobile */
        .btn-chart-period {
            padding: 5px 10px;
            font-size: 0.7rem;
            min-width: 70px;
        }
    }

    @media (max-width: 480px) {
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .card-stat {
            padding: 10px;
            min-height: 80px;
        }
        
        .card-stat h3 {
            font-size: 18px;
        }
        
        .card-stat small {
            font-size: 11px;
        }
        
        .filter-card {
            padding: 12px;
        }
        
        .dashboard-section {
            padding: 12px;
        }
        
        .modal-content {
            margin: 5px;
            max-width: calc(100% - 10px);
        }
        
        .table {
            min-width: 600px;
        }
        
        .badge {
            font-size: 0.65rem;
            padding: 0.2em 0.4em;
        }
        
        .chart-container {
            height: 180px;
        }
        
        .btn-group-sm .btn {
            width: 20px;
            height: 20px;
        }
        
        .btn-group-sm .btn .fas {
            font-size: 0.55rem;
        }
        
        /* PERBAIKAN: Button period di mobile kecil */
        .btn-chart-period {
            padding: 4px 8px;
            font-size: 0.65rem;
            min-width: 65px;
            height: 28px;
        }
    }

    /* Styling untuk empty state */
    .empty-state {
        text-align: center;
        padding: 20px 0;
    }

    .empty-state i {
        font-size: 2rem !important;
        color: var(--text-light);
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        font-size: 14px;
        margin: 10px 0 5px 0;
        color: var(--text-dark);
    }

    .empty-state p {
        font-size: 12px;
        margin: 0;
        color: var(--text-light);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Statistik Laporan - UKURAN LEBIH KOMPAK -->
    <div class="cards">
        <div class="card-stat">
            <small>Total Booking</small>
            <h3><?php echo e(number_format($totalPemesanan)); ?></h3>
            <small>Booking terdaftar</small>
        </div>

        <div class="card-stat">
            <small>Total Pendapatan</small>
            <h3>Rp <?php echo e(number_format($totalPendapatan)); ?></h3>
            <small class="text-success">+12% dari bulan lalu</small>
        </div>

        <div class="card-stat">
            <small>Rata-rata Booking/Hari</small>
            <h3><?php echo e($rataPemesananPerHari); ?></h3>
            <small class="text-success">+5% dari minggu lalu</small>
        </div>

        <div class="card-stat">
            <small>Venue Terpopuler</small>
            <h3><?php echo e($venueTerpopuler ?? '-'); ?></h3>
            <small><?php echo e($jumlahPemesananVenue ?? 0); ?>x dipesan bulan ini</small>
        </div>
    </div>

    <!-- Search dan Filter - UKURAN LEBIH KOMPAK -->
    <div class="filter-card">
        <div class="section-header">
            <h5>🔍 Filter Laporan</h5>
        </div>
        <div class="section-body">
            <form method="GET" action="<?php echo e(route('venue.reports')); ?>" id="filterForm" class="filter-form">
                <div class="row g-2 align-items-end">
                    <div class="col-md">
                        <label class="form-label">Periode</label>
                        <select class="form-select" id="periode" name="periode">
                            <option value="hari-ini">Hari Ini</option>
                            <option value="minggu-ini" <?php echo e(request('periode', 'minggu-ini') == 'minggu-ini' ? 'selected' : ''); ?>>Minggu Ini</option>
                            <option value="bulan-ini" <?php echo e(request('periode') == 'bulan-ini' ? 'selected' : ''); ?>>Bulan Ini</option>
                            <option value="tahun-ini" <?php echo e(request('periode') == 'tahun-ini' ? 'selected' : ''); ?>>Tahun Ini</option>
                            <option value="custom" <?php echo e(request('periode') == 'custom' ? 'selected' : ''); ?>>Kustom</option>
                        </select>
                    </div>
                    
                    <div class="col-md custom-date-inputs">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" name="start_date" id="startDate" value="<?php echo e(request('start_date')); ?>">
                    </div>
                    
                    <div class="col-md custom-date-inputs">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" name="end_date" id="endDate" value="<?php echo e(request('end_date')); ?>">
                    </div>
                    
                    <div class="col-md">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="statusFilter" name="status">
                            <option value="" <?php echo e(request('status') == '' ? 'selected' : ''); ?>>Semua Status</option>
                            <option value="selesai" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="dibatalkan" <?php echo e(request('status') == 'dibatalkan' ? 'selected' : ''); ?>>Dibatalkan</option>
                            <option value="dikembalikan" <?php echo e(request('status') == 'dikembalikan' ? 'selected' : ''); ?>>Dikembalikan</option>
                        </select>
                    </div>
                    
                    <div class="col-md-auto">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-export">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="resetFilter">
                                <i class="fas fa-undo me-1"></i>Reset
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Charts Section - UKURAN LEBIH KOMPAK -->
    <div class="row mb-3">
        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7 col-md-12 mb-3">
            <div class="dashboard-section">
                <!-- PERBAIKAN: Section header dengan button yang terlihat jelas -->
                <div class="section-header d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="mb-2 mb-sm-0">📈 Pendapatan Bulanan</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn-chart-period active" data-period="bulanan">
                            Bulanan
                        </button>
                        <button type="button" class="btn-chart-period" data-period="mingguan">
                            Mingguan
                        </button>
                    </div>
                </div>
                <div class="section-body">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Venue Distribution Chart -->
        <div class="col-xl-4 col-lg-5 col-md-12 mb-3">
            <div class="dashboard-section">
                <div class="section-header">
                    <h5>🥧 Distribusi Booking per Venue</h5>
                </div>
                <div class="section-body">
                    <div class="chart-container">
                        <canvas id="venueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Laporan - UKURAN LEBIH KOMPAK -->
    <div class="dashboard-section">
        <div class="section-header d-flex justify-content-between align-items-center flex-column flex-md-row">
            <h5 class="mb-2 mb-md-0">📋 Detail Laporan Booking</h5>
            <div class="export-buttons">
                <button type="button" class="btn-export" id="exportPdf">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </button>
                <button type="button" class="btn-export" id="exportExcel">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </button>
            </div>
        </div>
        <div class="section-body">
            <div class="table-responsive">
                <table class="table table-hover" id="laporanTable">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Venue</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="laporanTableBody">
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // ==============================
                        // PERBAIKAN UTAMA: Mapping Status
                        // ==============================
                        
                        // Ambil status dari database
                        $rawStatus = $transaction->status ?? '';
                        
                        // Normalisasi: lowercase, trim, hilangkan spasi berlebih
                        $normalizedStatus = strtolower(trim(preg_replace('/\s+/', ' ', $rawStatus)));
                        
                        // Debug info (opsional, bisa dihapus di production)
                        // <div class="debug-info">DB Status: "{{ $rawStatus }}" → Normalized: "{{ $normalizedStatus }}"</div>
                        
                        // Mapping lengkap semua kemungkinan status
                        $statusMapping = [
                            // Status Selesai
                            'selesai' => [
                                'display' => 'Selesai',
                                'class' => 'badge-success',
                                'variations' => ['selesai', 'completed', 'success', 'done', 'selesat', 'sukses', 'berhasil', 'finished']
                            ],
                            
                            // Status Pending
                            'pending' => [
                                'display' => 'Pending',
                                'class' => 'badge-warning',
                                'variations' => ['pending', 'menunggu', 'waiting', 'process', 'processing', 'diproses']
                            ],
                            
                            // Status Dibatalkan
                            'dibatalkan' => [
                                'display' => 'Dibatalkan',
                                'class' => 'badge-danger',
                                'variations' => ['dibatalkan', 'cancelled', 'canceled', 'batal', 'cancel', 'failed', 'gagal']
                            ],
                            
                            // Status Dikembalikan
                            'dikembalikan' => [
                                'display' => 'Dikembalikan',
                                'class' => 'badge-info',
                                'variations' => ['dikembalikan', 'refunded', 'refund', 'kembali', 'returned']
                            ]
                        ];
                        
                        // Cari status yang sesuai
                        $displayStatus = 'Tidak Diketahui';
                        $badgeClass = 'badge-secondary';
                        
                        foreach ($statusMapping as $statusGroup) {
                            foreach ($statusGroup['variations'] as $variation) {
                                if ($normalizedStatus === $variation) {
                                    $displayStatus = $statusGroup['display'];
                                    $badgeClass = $statusGroup['class'];
                                    break 2;
                                }
                            }
                        }
                        
                        // Jika tidak ditemukan, tampilkan status asli
                        if ($displayStatus === 'Tidak Diketahui' && !empty($normalizedStatus)) {
                            $displayStatus = ucfirst($normalizedStatus);
                        }
                    ?>
                    
                    <tr>
                        <td class="fw-bold"><?php echo e($transaction->transaction_number); ?></td>
                        <td><?php echo e($transaction->nama_venue); ?></td>
                        <td><?php echo e($transaction->pengguna ?? $transaction->customer_name ?? 'N/A'); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y')); ?></td>
                        <td>Rp <?php echo e(number_format($transaction->amount)); ?></td>
                        <td>
                            <span class="badge <?php echo e($badgeClass); ?>" title="Status: <?php echo e($rawStatus); ?>">
                                <?php echo e($displayStatus); ?>

                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-eye" title="Lihat Detail" data-bs-toggle="modal" data-bs-target="#detailLaporanModal" data-pemesanan-id="<?php echo e($transaction->id); ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-3">
                            <div class="empty-state">
                                <i class="fas fa-chart-bar fa-2x text-muted mb-2"></i>
                                <h5 class="text-muted">Tidak ada data laporan</h5>
                                <p class="text-muted">Belum ada laporan yang tersedia.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if($transactions->hasPages()): ?>
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination justify-content-center pagination-sm">
                    <?php if($transactions->onFirstPage()): ?>
                    <li class="page-item disabled">
                        <span class="page-link">Previous</span>
                    </li>
                    <?php else: ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo e($transactions->previousPageUrl()); ?>">Previous</a>
                    </li>
                    <?php endif; ?>
                    
                    <?php $__currentLoopData = range(1, $transactions->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($i == $transactions->currentPage()): ?>
                    <li class="page-item active"><span class="page-link"><?php echo e($i); ?></span></li>
                    <?php else: ?>
                    <li class="page-item"><a class="page-link" href="<?php echo e($transactions->url($i)); ?>"><?php echo e($i); ?></a></li>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <?php if($transactions->hasMorePages()): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo e($transactions->nextPageUrl()); ?>">Next</a>
                    </li>
                    <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link">Next</span>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Detail Laporan - SAMA SEPERTI ROLE ADMIN -->
    <div class="modal fade" id="detailLaporanModal" tabindex="-1" aria-labelledby="detailLaporanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailLaporanModalLabel">📊 Detail Laporan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="laporan-detail-item">
                                <div class="laporan-detail-label">Kode Booking</div>
                                <div class="laporan-detail-value" id="detailKodePemesanan">-</div>
                            </div>
                            <div class="laporan-detail-item">
                                <div class="laporan-detail-label">Nama Customer</div>
                                <div class="laporan-detail-value" id="detailNamaCustomer">-</div>
                            </div>
                            <div class="laporan-detail-item">
                                <div class="laporan-detail-label">Venue</div>
                                <div class="laporan-detail-value" id="detailVenue">-</div>
                            </div>
                            <div class="laporan-detail-item">
                                <div class="laporan-detail-label">Tanggal Booking</div>
                                <div class="laporan-detail-value" id="detailTanggalPemesanan">-</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="laporan-detail-item">
                                <div class="laporan-detail-label">Durasi</div>
                                <div class="laporan-detail-value" id="detailDurasi">-</div>
                            </div>
                            <div class="laporan-detail-item">
                                <div class="laporan-detail-label">Total</div>
                                <div class="laporan-detail-value" id="detailTotal">-</div>
                            </div>
                            <div class="laporan-detail-item">
                                <div class="laporan-detail-label">Status</div>
                                <div class="laporan-detail-value" id="detailStatus">-</div>
                            </div>
                            <div class="laporan-detail-item">
                                <div class="laporan-detail-label">Metode Pembayaran</div>
                                <div class="laporan-detail-value" id="detailMetodeBayar">-</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <h6>Rincian Biaya</h6>
                        <table class="table table-sm">
                            <tbody id="rincianBiaya">
                                <!-- Rincian biaya akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Data dari PHP untuk grafik
        const revenueData = <?php echo json_encode($monthlyRevenue, 15, 512) ?>;
        const venueDistribution = <?php echo json_encode($venueDistribution, 15, 512) ?>;
        const venueData = venueDistribution.data || [];
        const venueLabels = venueDistribution.labels || [];

        // Initialize Charts dengan data dari database
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const venueCtx = document.getElementById('venueChart').getContext('2d');

        const revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendapatan (Juta Rp)',
                    data: revenueData,
                    backgroundColor: 'rgba(99, 179, 237, 0.6)',
                    borderColor: 'rgba(99, 179, 237, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value + ' jt';
                            }
                        }
                    }
                }
            }
        });

        const venueChart = new Chart(venueCtx, {
            type: 'doughnut',
            data: {
                labels: venueLabels,
                datasets: [{
                    data: venueData,
                    backgroundColor: [
                        'rgba(99, 179, 237, 0.6)',
                        'rgba(34, 197, 94, 0.6)',
                        'rgba(59, 130, 246, 0.6)',
                        'rgba(245, 158, 11, 0.6)',
                        'rgba(139, 92, 246, 0.6)',
                        'rgba(236, 72, 153, 0.6)',
                        'rgba(16, 185, 129, 0.6)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });

        // PERBAIKAN: Chart period toggle dengan button yang lebih jelas
        const periodButtons = document.querySelectorAll('.btn-chart-period');
        periodButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Hapus class active dari semua button
                periodButtons.forEach(btn => btn.classList.remove('active'));
                
                // Tambah class active ke button yang diklik
                this.classList.add('active');
                
                const period = this.dataset.period;
                updateChartData(period);
            });
        });

        // Function untuk update chart data
        function updateChartData(period) {
            fetch(`/venue/reports/chart-data?period=${period}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update judul berdasarkan periode
                    const titleElement = document.querySelector('.dashboard-section .section-header h5');
                    if (period === 'mingguan') {
                        titleElement.textContent = '📈 Pendapatan Mingguan';
                    } else {
                        titleElement.textContent = '📈 Pendapatan Bulanan';
                    }
                    
                    revenueChart.data.datasets[0].data = data.revenueData;
                    revenueChart.update();
                    
                    venueChart.data.labels = data.venueLabels;
                    venueChart.data.datasets[0].data = data.venueData;
                    venueChart.update();
                }
            })
            .catch(error => console.error('Error updating chart:', error));
        }

        // Tampilkan/sembunyikan input tanggal kustom
        const periodeSelect = document.getElementById('periode');
        const customDateInputs = document.querySelectorAll('.custom-date-inputs');
        
        periodeSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateInputs.forEach(input => {
                    input.style.display = 'block';
                });
            } else {
                customDateInputs.forEach(input => {
                    input.style.display = 'none';
                });
            }
        });

        // Inisialisasi tampilan tanggal kustom
        if (periodeSelect.value === 'custom') {
            customDateInputs.forEach(input => {
                input.style.display = 'block';
            });
        }

        // Inisialisasi tanggal default
        const today = new Date();
        const oneWeekAgo = new Date();
        oneWeekAgo.setDate(today.getDate() - 7);
        
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        
        if (startDateInput && !startDateInput.value) {
            startDateInput.value = oneWeekAgo.toISOString().split('T')[0];
        }
        if (endDateInput && !endDateInput.value) {
            endDateInput.value = today.toISOString().split('T')[0];
        }

        // Reset Filter
        document.getElementById('resetFilter').addEventListener('click', function() {
            document.getElementById('periode').value = 'minggu-ini';
            document.getElementById('statusFilter').value = '';
            
            if (startDateInput) startDateInput.value = '';
            if (endDateInput) endDateInput.value = '';
            
            customDateInputs.forEach(input => {
                input.style.display = 'none';
            });
            
            document.getElementById('filterForm').submit();
        });

        // Export buttons
        document.getElementById('exportPdf').addEventListener('click', function() {
            const periode = document.getElementById('periode').value;
            const status = document.getElementById('statusFilter').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            let url = '/venue/reports/export-pdf';
            let params = new URLSearchParams({
                periode: periode,
                status: status
            });

            if (periode === 'custom') {
                params.append('start_date', startDate);
                params.append('end_date', endDate);
            }

            window.open(url + '?' + params.toString(), '_blank');
        });

        document.getElementById('exportExcel').addEventListener('click', function () {
            const periode   = document.getElementById('periode').value;
            const status    = document.getElementById('statusFilter').value;
            const startDate = document.getElementById('startDate').value;
            const endDate   = document.getElementById('endDate').value;

            let params = new URLSearchParams({
                periode: periode,
                status: status
            });

            if (periode === 'custom') {
                params.append('start_date', startDate);
                params.append('end_date', endDate);
            }

            window.location.href = '/venue/reports/export-excel?' + params.toString();
        });

        // PERBAIKAN: Handle modal detail laporan dengan mapping status yang sama
        const detailModal = document.getElementById('detailLaporanModal');
        if (detailModal) {
            detailModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const pemesananId = button.getAttribute('data-pemesanan-id');
                
                // Set loading state
                document.getElementById('detailKodePemesanan').textContent = 'Memuat...';
                document.getElementById('detailNamaCustomer').textContent = 'Memuat...';
                document.getElementById('detailVenue').textContent = 'Memuat...';
                document.getElementById('detailTanggalPemesanan').textContent = 'Memuat...';
                document.getElementById('detailDurasi').textContent = 'Memuat...';
                document.getElementById('detailTotal').textContent = 'Memuat...';
                document.getElementById('detailStatus').innerHTML = '<span class="badge badge-warning">Memuat...</span>';
                document.getElementById('detailMetodeBayar').textContent = 'Memuat...';
                document.getElementById('rincianBiaya').innerHTML = '<tr><td colspan="2">Memuat data...</td></tr>';
                
                // Fetch detail data
                fetch(`/venue/reports/${pemesananId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.data) {
                        const transaction = data.data;
                        
                        // Isi data ke modal
                        document.getElementById('detailKodePemesanan').textContent = transaction.transaction_number || '-';
                        document.getElementById('detailNamaCustomer').textContent = transaction.pengguna || transaction.customer_name || '-';
                        document.getElementById('detailVenue').textContent = transaction.nama_venue || '-';
                        
                        // Format tanggal
                        if (transaction.transaction_date) {
                            const date = new Date(transaction.transaction_date);
                            document.getElementById('detailTanggalPemesanan').textContent = 
                                date.toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: 'long',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                        } else {
                            document.getElementById('detailTanggalPemesanan').textContent = '-';
                        }
                        
                        document.getElementById('detailDurasi').textContent = (transaction.durasi || '2') + ' jam';
                        document.getElementById('detailTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(transaction.amount || 0);
                        
                        // Mapping status untuk modal (sama dengan di tabel)
                        const rawStatus = transaction.status || '';
                        const normalizedStatus = rawStatus.toLowerCase().trim().replace(/\s+/g, ' ');
                        
                        // Function untuk mapping status
                        function mapStatus(status) {
                            const statusMap = {
                                // Selesai
                                'selesai': {display: 'Selesai', class: 'badge-success'},
                                'completed': {display: 'Selesai', class: 'badge-success'},
                                'success': {display: 'Selesai', class: 'badge-success'},
                                'done': {display: 'Selesai', class: 'badge-success'},
                                'selesat': {display: 'Selesai', class: 'badge-success'},
                                'sukses': {display: 'Selesai', class: 'badge-success'},
                                'berhasil': {display: 'Selesai', class: 'badge-success'},
                                'finished': {display: 'Selesai', class: 'badge-success'},
                                
                                // Pending
                                'pending': {display: 'Pending', class: 'badge-warning'},
                                'menunggu': {display: 'Pending', class: 'badge-warning'},
                                'waiting': {display: 'Pending', class: 'badge-warning'},
                                'process': {display: 'Pending', class: 'badge-warning'},
                                'processing': {display: 'Pending', class: 'badge-warning'},
                                'diproses': {display: 'Pending', class: 'badge-warning'},
                                
                                // Dibatalkan
                                'dibatalkan': {display: 'Dibatalkan', class: 'badge-danger'},
                                'cancelled': {display: 'Dibatalkan', class: 'badge-danger'},
                                'canceled': {display: 'Dibatalkan', class: 'badge-danger'},
                                'batal': {display: 'Dibatalkan', class: 'badge-danger'},
                                'cancel': {display: 'Dibatalkan', class: 'badge-danger'},
                                'failed': {display: 'Dibatalkan', class: 'badge-danger'},
                                'gagal': {display: 'Dibatalkan', class: 'badge-danger'},
                                
                                // Dikembalikan
                                'dikembalikan': {display: 'Dikembalikan', class: 'badge-info'},
                                'refunded': {display: 'Dikembalikan', class: 'badge-info'},
                                'refund': {display: 'Dikembalikan', class: 'badge-info'},
                                'kembali': {display: 'Dikembalikan', class: 'badge-info'},
                                'returned': {display: 'Dikembalikan', class: 'badge-info'}
                            };
                            
                            return statusMap[status] || {display: ucFirst(status), class: 'badge-secondary'};
                        }
                        
                        // Helper function untuk kapitalisasi pertama
                        function ucFirst(str) {
                            return str.charAt(0).toUpperCase() + str.slice(1);
                        }
                        
                        const statusInfo = mapStatus(normalizedStatus);
                        document.getElementById('detailStatus').innerHTML = 
                            `<span class="badge ${statusInfo.class}">${statusInfo.display}</span>`;
                        
                        document.getElementById('detailMetodeBayar').textContent = transaction.metode_pembayaran || 'Transfer Bank';
                        
                        // Isi rincian biaya
                        const rincianBiaya = document.getElementById('rincianBiaya');
                        const hargaPerJam = transaction.harga_per_jam || Math.round((transaction.amount || 0) / (transaction.durasi || 2));
                        const durasi = transaction.durasi || 2;
                        const biayaSewa = hargaPerJam * durasi;
                        const biayaLayanan = biayaSewa * 0.1;
                        const pajak = biayaSewa * 0.05;
                        const total = biayaSewa + biayaLayanan + pajak;
                        
                        rincianBiaya.innerHTML = `
                            <tr>
                                <td>Biaya Sewa Venue (${durasi} jam @ Rp ${new Intl.NumberFormat("id-ID").format(hargaPerJam)}/jam)</td>
                                <td class="text-end">Rp ${new Intl.NumberFormat("id-ID").format(biayaSewa)}</td>
                            </tr>
                            <tr>
                                <td>Biaya Layanan (10%)</td>
                                <td class="text-end">Rp ${new Intl.NumberFormat("id-ID").format(biayaLayanan)}</td>
                            </tr>
                            <tr>
                                <td>Pajak (5%)</td>
                                <td class="text-end">Rp ${new Intl.NumberFormat("id-ID").format(pajak)}</td>
                            </tr>
                            <tr class="table-active">
                                <td><strong>Total</strong></td>
                                <td class="text-end"><strong>Rp ${new Intl.NumberFormat("id-ID").format(total)}</strong></td>
                            </tr>
                        `;
                    } else {
                        throw new Error(data.message || 'Data tidak valid dari server');
                    }
                })
                .catch(error => {
                    console.error('Error loading detail:', error);
                    
                    // Set error state
                    document.getElementById('detailKodePemesanan').textContent = 'Error';
                    document.getElementById('detailNamaCustomer').textContent = 'Error';
                    document.getElementById('detailVenue').textContent = 'Error';
                    document.getElementById('detailTanggalPemesanan').textContent = 'Error';
                    document.getElementById('detailDurasi').textContent = 'Error';
                    document.getElementById('detailTotal').textContent = 'Error';
                    document.getElementById('detailStatus').innerHTML = '<span class="badge badge-danger">Error</span>';
                    document.getElementById('detailMetodeBayar').textContent = 'Error';
                    document.getElementById('rincianBiaya').innerHTML = '<tr><td colspan="2" class="text-danger">Gagal memuat data</td></tr>';
                });
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.venue', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\CariArena\resources\views/venue/report.blade.php ENDPATH**/ ?>