

<?php $__env->startSection('title', 'Manajemen Transaksi - CariArena'); ?>
<?php $__env->startSection('page-title', 'Manajemen Transaksi'); ?>

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

    /* === STATISTIC CARDS - STYLE KONSISTEN DENGAN PENGGUNA === */
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

    /* === FILTER CARD - STYLE KONSISTEN DENGAN PENGGUNA === */
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

    /* === BUTTONS - STYLE KONSISTEN DENGAN PENGGUNA === */
    .btn-tambah {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
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
        width: 100%;
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
        width: 100%;
    }

    .btn-outline-secondary:hover {
        background: #F9FAFB;
        border-color: #D1D5DB;
        color: #374151;
        transform: translateY(-2px);
        text-decoration: none;
    }

    /* === TABLE STYLES - PERBAIKAN AGAR TIDAK BERJARAK LEBAR === */
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
        table-layout: fixed;
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
        overflow: hidden;
        text-overflow: ellipsis;
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

    /* PERBAIKAN: Atur lebar kolom lebih kompak */
    .table th:nth-child(1),
    .table td:nth-child(1) {
        width: 12%; /* Kode Transaksi */
        min-width: 100px;
    }

    .table th:nth-child(2),
    .table td:nth-child(2) {
        width: 18%; /* Pengguna */
        min-width: 150px;
    }

    .table th:nth-child(3),
    .table td:nth-child(3) {
        width: 18%; /* Venue */
        min-width: 150px;
    }

    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 14%; /* Metode Pembayaran */
        min-width: 120px;
    }

    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 12%; /* Jumlah */
        min-width: 100px;
    }

    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 10%; /* Status */
        min-width: 90px;
    }

    .table td:nth-child(7) {
        width: 12%; /* Tanggal */
        min-width: 110px;
    }

    .table th:nth-child(8),
    .table td:nth-child(8) {
        width: 8%; /* Aksi - DIPERKECIL */
        min-width: 80px;
        text-align: center;
    }

    /* === BADGES - STYLE KONSISTEN DENGAN PENGGUNA === */
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
        border-radius: 6px;
        display: inline-block;
        text-align: center;
        white-space: nowrap;
    }

    .badge-success {
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

    /* === PAYMENT METHOD BADGE === */
    .payment-method {
        font-size: 0.75rem;
        background: #EBF8FF;
        color: var(--primary-color);
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
        font-weight: 500;
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

    /* === ACTION BUTTONS - PERBAIKAN: LEBIH KOMPAK SEPERTI DI GAMBAR === */
    .btn-group-sm {
        display: flex;
        gap: 2px; /* DIPERKECIL GAP NYA */
        justify-content: center;
        align-items: center;
        flex-wrap: nowrap;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.4rem; /* DIPERKECIL PADDING */
        font-size: 0.7rem; /* DIPERKECIL FONT */
        margin: 0;
        border: none;
        background: transparent !important;
        width: 24px; /* DIPERKECIL UKURAN */
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

    .btn-check {
        color: var(--success) !important;
    }

    .btn-check:hover {
        color: #38a169 !important;
        background: rgba(72, 187, 120, 0.1) !important;
        transform: translateY(-1px);
    }

    .btn-times {
        color: var(--danger) !important;
    }

    .btn-times:hover {
        color: #e53e3e !important;
        background: rgba(245, 101, 101, 0.1) !important;
        transform: translateY(-1px);
    }

    .btn-group-sm .btn .fas {
        color: inherit !important;
        font-size: 0.65rem; /* DIPERKECIL ICON */
    }

    /* Form action inline */
    .action-form {
        display: inline-block;
        margin: 0;
        padding: 0;
    }

    /* === MODAL STYLES - STYLE KONSISTEN DENGAN PENGGUNA === */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        max-width: 500px;
        margin: 0 auto;
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

    .transaction-detail-item {
        display: flex;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
    }

    .transaction-detail-label {
        font-weight: 600;
        color: var(--text-dark);
        min-width: 140px;
        flex-shrink: 0;
    }

    .transaction-detail-value {
        color: var(--text-light);
        word-break: break-word;
    }

    .transaction-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .transaction-icon {
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
        flex-shrink: 0;
    }

    .transaction-info {
        min-width: 0;
        flex: 1;
    }

    .transaction-info h4 {
        margin: 0 0 5px 0;
        color: var(--text-dark);
        font-size: 18px;
        word-break: break-word;
    }

    .transaction-info p {
        margin: 0;
        color: var(--text-light);
        font-size: 14px;
        word-break: break-word;
    }

    /* === ALERT STYLES === */
    .alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        max-width: 100%;
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

    /* Layout improvements */
    .container-fluid {
        max-width: 100%;
        padding-left: 20px;
        padding-right: 20px;
    }

    .row {
        margin-left: -8px;
        margin-right: -8px;
    }

    .row > [class*="col-"] {
        padding-left: 8px;
        padding-right: 8px;
    }

    /* === PAGINATION STYLES - SAMA SEPERTI PENGGUNA === */
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

    /* === ITEMS PER PAGE SELECTOR - SAMA SEPERTI PENGGUNA === */
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

    /* PERBAIKAN: Date range picker styling */
    .date-range-container {
        position: relative;
    }

    .date-range-input {
        background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z'/%3E%3C/svg%3E") no-repeat right 12px center;
        background-size: 16px;
        cursor: pointer;
    }

    /* Responsive pagination */
    @media (max-width: 768px) {
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

    /* Responsive design untuk transaksi */
    @media (max-width: 1200px) {
        .cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .table th:nth-child(2),
        .table td:nth-child(2),
        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 16%; /* Lebar kolom disesuaikan */
        }
    }

    @media (max-width: 992px) {
        .table {
            font-size: 0.8rem;
        }
        
        .table th,
        .table td {
            padding: 10px 6px;
        }
        
        .btn-group-sm .btn {
            width: 22px;
            height: 22px;
            padding: 0.2rem 0.3rem;
        }
        
        .btn-group-sm .btn .fas {
            font-size: 0.6rem;
        }
    }

    @media (max-width: 768px) {
        .cards {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            min-width: 700px; /* Lebih kecil dari sebelumnya */
        }
        
        .filter-form .row {
            flex-direction: column;
            gap: 12px;
        }
        
        .filter-form .col-md {
            width: 100%;
        }
        
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .btn-group-sm {
            justify-content: flex-start;
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
        
        .table {
            min-width: 650px;
        }
        
        .btn-group-sm .btn {
            width: 20px;
            height: 20px;
        }
        
        .btn-group-sm .btn .fas {
            font-size: 0.55rem;
        }
        
        .table th:nth-child(8),
        .table td:nth-child(8) {
            width: 7%;
            min-width: 70px;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistik Transaksi - STYLE KONSISTEN DENGAN PENGGUNA -->
    <div class="cards">
        <div class="card">
            <small>Total Transaksi</small>
            <h3><?php echo e(number_format($statistik['total_hari_ini'] ?? 0)); ?></h3>
            <small>Transaksi hari ini</small>
        </div>

        <div class="card">
            <small>Pendapatan Hari Ini</small>
            <h3>Rp <?php echo e(number_format($statistik['pendapatan_hari_ini'] ?? 0, 0, ',', '.')); ?></h3>
            <small>Total pendapatan</small>
        </div>

        <div class="card">
            <small>Menunggu Konfirmasi</small>
            <h3><?php echo e(number_format($statistik['pending_count'] ?? 0)); ?></h3>
            <small>Transaksi pending</small>
        </div>

        <div class="card">
            <small>Transaksi Berhasil</small>
            <h3><?php echo e(number_format($statistik['success_count'] ?? 0)); ?></h3>
            <small>Transaksi completed</small>
        </div>
    </div>

    <!-- PERBAIKAN: Filter Card dengan layout lebih kompak dan 1 input tanggal -->
    <div class="filter-card">
        <div class="section-header">
            <h5>🔍 Filter Transaksi</h5>
        </div>
        <div class="section-body">
            <form method="GET" action="<?php echo e(route('admin.transaksi.index')); ?>" id="filterForm" class="filter-form">
                <input type="hidden" name="per_page" id="perPageHidden" value="<?php echo e(request('per_page', 10)); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Cari Transaksi</label>
                        <input type="text" class="form-control" id="searchInput" name="search" 
                               placeholder="Kode transaksi atau nama..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Menunggu</option>
                            <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Berhasil</option>
                            <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-select" name="metode_pembayaran" id="paymentMethodFilter">
                            <option value="">Semua Metode</option>
                            <option value="transfer bank" <?php echo e(request('metode_pembayaran') == 'transfer bank' ? 'selected' : ''); ?>>Transfer Bank</option>
                            <option value="tunai" <?php echo e(request('metode_pembayaran') == 'tunai' ? 'selected' : ''); ?>>Tunai</option>
                            <option value="qris" <?php echo e(request('metode_pembayaran') == 'qris' ? 'selected' : ''); ?>>QRIS</option>
                            <option value="e-wallet" <?php echo e(request('metode_pembayaran') == 'e-wallet' ? 'selected' : ''); ?>>E-Wallet</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control date-range-input" name="tanggal" 
                               value="<?php echo e(request('tanggal')); ?>">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn-tambah">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="<?php echo e(route('admin.transaksi.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Transaksi -->
    <div class="dashboard-section">
        <div class="panel-header">
            <h5>📋 Daftar Transaksi</h5>
            <!-- Items per page selector - SAMA SEPERTI PENGGUNA -->
            <div class="items-per-page">
                <span class="items-per-page-label">Tampilkan:</span>
                <select class="pagination-select" id="perPageSelect">
                    <option value="10" <?php echo e(request('per_page', 10) == 10 ? 'selected' : ''); ?>>10</option>
                    <option value="25" <?php echo e(request('per_page', 10) == 25 ? 'selected' : ''); ?>>25</option>
                    <option value="50" <?php echo e(request('per_page', 10) == 50 ? 'selected' : ''); ?>>50</option>
                    <option value="100" <?php echo e(request('per_page', 10) == 100 ? 'selected' : ''); ?>>100</option>
                    <option value="all" <?php echo e(request('per_page') == 'all' ? 'selected' : ''); ?>>Semua</option>
                </select>
            </div>
        </div>
        <div class="section-body">
            <div class="table-responsive">
                <table class="table table-hover" id="transactionsTable">
                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Pengguna</th>
                            <th>Venue</th>
                            <th>Metode Pembayaran</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="transactionsTableBody">
                        <?php
                            $perPage = request('per_page', 10);
                            $currentPage = $transaksis->currentPage();
                            $startNumber = ($currentPage - 1) * $perPage + 1;
                        ?>
                        
                        <?php $__empty_1 = true; $__currentLoopData = $transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-bold"><?php echo e($transaksi->transaction_number); ?></td>
                            <td title="<?php echo e($transaksi->pengguna); ?>">
                                <?php echo e(\Illuminate\Support\Str::limit($transaksi->pengguna, 20)); ?>

                            </td>
                            <td title="<?php echo e($transaksi->nama_venue); ?>">
                                <?php echo e(\Illuminate\Support\Str::limit($transaksi->nama_venue, 20)); ?>

                            </td>
                            <td>
                                <span class="payment-method">
                                    <i class="fas fa-wallet me-1"></i>
                                    <?php echo e(ucfirst($transaksi->metode_pembayaran)); ?>

                                </span>
                            </td>
                            <td class="fw-bold text-success">Rp <?php echo e(number_format($transaksi->amount, 0, ',', '.')); ?></td>
                            <td>
                                <?php if($transaksi->status == 'pending'): ?>
                                    <span class="badge badge-pending">Menunggu</span>
                                <?php elseif($transaksi->status == 'completed'): ?>
                                    <span class="badge badge-success">Berhasil</span>
                                <?php elseif($transaksi->status == 'cancelled'): ?>
                                    <span class="badge badge-cancelled">Dibatalkan</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?php echo e($transaksi->status); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e(\Carbon\Carbon::parse($transaksi->transaction_date)->format('d/m/Y H:i')); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <!-- Tombol Detail - STYLE LEBIH KOMPAK -->
                                    <button class="btn btn-eye" title="Lihat Detail" data-bs-toggle="modal" 
                                            data-bs-target="#detailTransaksiModal" 
                                            data-transaksi-id="<?php echo e($transaksi->id); ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <?php if($transaksi->status == 'pending'): ?>
                                    <!-- Tombol Konfirmasi -->
                                    <form action="<?php echo e(route('admin.transaksi.confirm', $transaksi)); ?>" method="POST" class="action-form">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-check" title="Konfirmasi" 
                                                onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi pembayaran ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    
                                    <!-- Tombol Tolak -->
                                    <form action="<?php echo e(route('admin.transaksi.reject', $transaksi)); ?>" method="POST" class="action-form">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-times" title="Tolak" 
                                                onclick="return confirm('Apakah Anda yakin ingin menolak pembayaran ini?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak ada data transaksi</h5>
                                    <p class="text-muted">Belum ada transaksi yang tercatat.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination - SAMA SEPERTI PENGGUNA -->
            <?php if($transaksis->hasPages()): ?>
            <div class="pagination-container">
                <div class="pagination-info">
                    <?php
                        $currentPage = $transaksis->currentPage();
                        $perPage = request('per_page', 10);
                        $totalItems = $transaksis->total();
                        
                        if ($perPage === 'all') {
                            $start = 1;
                            $end = $totalItems;
                        } else {
                            $start = ($currentPage - 1) * $perPage + 1;
                            $end = min($currentPage * $perPage, $totalItems);
                        }
                    ?>
                    Menampilkan <?php echo e($start); ?> - <?php echo e($end); ?> dari <?php echo e($totalItems); ?> transaksi
                </div>
                
                <div class="pagination-controls">
                    <ul class="pagination">
                        
                        <?php if($transaksis->onFirstPage()): ?>
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </li>
                        <?php else: ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($transaksis->previousPageUrl()); ?>" rel="prev">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        
                        <?php
                            $currentPage = $transaksis->currentPage();
                            $lastPage = $transaksis->lastPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                            
                            // Adjust jika dekat dengan awal
                            if ($currentPage <= 3) {
                                $endPage = min(5, $lastPage);
                            }
                            
                            // Adjust jika dekat dengan akhir
                            if ($currentPage >= $lastPage - 2) {
                                $startPage = max(1, $lastPage - 4);
                            }
                            
                            $showStartDots = $startPage > 1;
                            $showEndDots = $endPage < $lastPage;
                        ?>

                        
                        <?php if($showStartDots): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($transaksis->url(1)); ?>">1</a>
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
                                    <a class="page-link" href="<?php echo e($transaksis->url($page)); ?>"><?php echo e($page); ?></a>
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
                                <a class="page-link" href="<?php echo e($transaksis->url($lastPage)); ?>"><?php echo e($lastPage); ?></a>
                            </li>
                        <?php endif; ?>

                        
                        <?php if($transaksis->hasMorePages()): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($transaksis->nextPageUrl()); ?>" rel="next">
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
                    $totalItems = $transaksis->total();
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
                        Menampilkan semua <?php echo e($totalItems); ?> transaksi
                    <?php else: ?>
                        Menampilkan <?php echo e($displayText); ?> dari <?php echo e($totalItems); ?> transaksi
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

    <!-- Modal Detail Transaksi -->
    <div class="modal fade" id="detailTransaksiModal" tabindex="-1" aria-labelledby="detailTransaksiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">📋 Detail Transaksi</h5>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Real-time search untuk transaksi
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#transactionsTableBody tr');
                    
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

        document.getElementById('paymentMethodFilter').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        // Auto-submit ketika tanggal dipilih
        document.querySelector('input[name="tanggal"]').addEventListener('change', function() {
            if (this.value) {
                document.getElementById('filterForm').submit();
            }
        });

        // Load detail transaksi via AJAX
        const detailModal = document.getElementById('detailTransaksiModal');
        if (detailModal) {
            detailModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const transaksiId = button.getAttribute('data-transaksi-id');
                
                // Show loading state
                document.getElementById('detailModalBody').innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data transaksi...</p>
                    </div>
                `;
                
                fetch(`/admin/transaksi/${transaksiId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const transaksi = data.data;
                        const transactionDate = new Date(transaksi.transaction_date);
                        const formattedDate = transactionDate.toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        
                        document.getElementById('detailModalBody').innerHTML = `
                            <div class="transaction-header">
                                <div class="transaction-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="transaction-info">
                                    <h4>${transaksi.transaction_number}</h4>
                                    <p>${formattedDate}</p>
                                    <span class="badge ${transaksi.status === 'pending' ? 'badge-pending' : transaksi.status === 'completed' ? 'badge-success' : 'badge-cancelled'}">
                                        ${transaksi.status === 'pending' ? 'Menunggu' : transaksi.status === 'completed' ? 'Berhasil' : 'Dibatalkan'}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="transaction-detail-item">
                                <div class="transaction-detail-label">Pengguna</div>
                                <div class="transaction-detail-value">${transaksi.pengguna}</div>
                            </div>
                            <div class="transaction-detail-item">
                                <div class="transaction-detail-label">Venue</div>
                                <div class="transaction-detail-value">${transaksi.nama_venue}</div>
                            </div>
                            <div class="transaction-detail-item">
                                <div class="transaction-detail-label">Metode Pembayaran</div>
                                <div class="transaction-detail-value">
                                    <span class="payment-method">
                                        <i class="fas fa-wallet me-1"></i>
                                        ${transaksi.metode_pembayaran.charAt(0).toUpperCase() + transaksi.metode_pembayaran.slice(1)}
                                    </span>
                                </div>
                            </div>
                            <div class="transaction-detail-item">
                                <div class="transaction-detail-label">Jumlah</div>
                                <div class="transaction-detail-value fw-bold text-success">
                                    Rp ${new Intl.NumberFormat('id-ID').format(transaksi.amount)}
                                </div>
                            </div>
                            <div class="transaction-detail-item">
                                <div class="transaction-detail-label">Status</div>
                                <div class="transaction-detail-value">
                                    ${transaksi.status === 'pending' ? '<span class="badge badge-pending">Menunggu</span>' : 
                                    transaksi.status === 'completed' ? '<span class="badge badge-success">Berhasil</span>' :
                                    '<span class="badge badge-cancelled">Dibatalkan</span>'}
                                </div>
                            </div>
                            <div class="transaction-detail-item">
                                <div class="transaction-detail-label">Tanggal Transaksi</div>
                                <div class="transaction-detail-value">${formattedDate}</div>
                            </div>
                            <div class="transaction-detail-item">
                                <div class="transaction-detail-label">Dibuat Pada</div>
                                <div class="transaction-detail-value">${new Date(transaksi.created_at).toLocaleDateString('id-ID')}</div>
                            </div>
                            ${transaksi.updated_at ? `
                            <div class="transaction-detail-item">
                                <div class="transaction-detail-label">Diupdate Pada</div>
                                <div class="transaction-detail-value">${new Date(transaksi.updated_at).toLocaleDateString('id-ID')}</div>
                            </div>
                            ` : ''}
                        `;
                    } else {
                        throw new Error(data.error || 'Gagal memuat data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('detailModalBody').innerHTML = `
                        <div class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <p>Terjadi kesalahan saat memuat data transaksi</p>
                            <small>${error.message}</small>
                        </div>
                    `;
                });
            });
        }

        // Function untuk mengubah jumlah item per halaman (AJAX)
        function changePerPage(value) {
            // Ambil semua parameter URL saat ini
            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search);
            
            // Update parameter per_page
            params.set('per_page', value);
            
            // Reset ke halaman 1
            params.delete('page');
            
            // Submit form filter dengan parameter baru
            document.getElementById('perPageHidden').value = value;
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\CariArena\resources\views/admin/transaksi.blade.php ENDPATH**/ ?>