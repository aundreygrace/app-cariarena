

<?php $__env->startSection('title', 'Manajemen Pengguna - CariArena'); ?>
<?php $__env->startSection('page-title', 'Manajemen Pengguna'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* === VARIABLES BERDASARKAN STYLE VENUE & PEMESANAN === */
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

    /* === STATISTIC CARDS - STYLE KONSISTEN === */
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

    /* Atur lebar kolom agar sesuai dengan layar desktop */
    .table th:nth-child(1),
    .table td:nth-child(1) {
        width: 60px; /* ID */
    }

    .table th:nth-child(2),
    .table td:nth-child(2) {
        width: 200px; /* Nama */
        min-width: 150px;
    }

    .table th:nth-child(3),
    .table td:nth-child(3) {
        width: 200px; /* Email */
        min-width: 150px;
    }

    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 120px; /* Telepon */
        min-width: 100px;
    }

    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 100px; /* Tipe */
        min-width: 80px;
    }

    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 150px; /* Nama Venue */
        min-width: 120px;
    }

    .table th:nth-child(7),
    .table td:nth-child(7) {
        width: 100px; /* Bergabung */
        min-width: 80px;
    }

    .table th:nth-child(8),
    .table td:nth-child(8) {
        width: 70px; /* Aksi */
        min-width: 60px;
    }

    /* === BADGES - STYLE KONSISTEN === */
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
        border-radius: 6px;
        display: inline-block;
        text-align: center;
        white-space: nowrap;
    }

    .badge-admin {
        background-color: #FFEBEE;
        color: var(--danger);
    }

    .badge-venue {
        background-color: #E8F5E8;
        color: var(--success);
    }

    .badge-user {
        background-color: #E3F2FD;
        color: var(--info);
    }

    /* === USER AVATAR === */
    .rounded-circle {
        object-fit: cover;
        border: 2px solid #e2e8f0;
        flex-shrink: 0;
    }

    .bg-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%) !important;
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
        width: auto;
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

    .user-detail-item {
        display: flex;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
    }

    .user-detail-label {
        font-weight: 600;
        color: var(--text-dark);
        min-width: 140px;
        flex-shrink: 0;
    }

    .user-detail-value {
        color: var(--text-light);
        word-break: break-word;
    }

    .user-avatar {
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

    .user-profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .user-profile-info {
        min-width: 0;
        flex: 1;
    }

    .user-profile-info h4 {
        margin: 0 0 5px 0;
        color: var(--text-dark);
        font-size: 18px;
        word-break: break-word;
    }

    .user-profile-info p {
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

    /* === STYLE UNTUK PANEL HEADER DENGAN BUTTON KANAN === */
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
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Icon emoji untuk judul */
    .panel-header h5::before {
        content: "📋";
        font-size: 18px;
    }

    /* Style khusus untuk button Tambah Pengguna di panel header */
    .panel-header .btn-tambah {
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
        min-width: 160px;
        width: auto;
        white-space: nowrap;
    }

    .panel-header .btn-tambah:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 179, 237, 0.3);
        color: white;
    }

    .panel-header .btn-tambah i {
        font-size: 14px;
    }

    /* Pastikan button berada di kanan */
    .panel-header > .btn-tambah {
        margin-left: auto;
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

    /* === STYLE UNTUK FORM MODAL TAMBAH PENGGUNA === */
    .modal-body .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 6px;
        font-size: 0.9rem;
    }

    .modal-body .form-control,
    .modal-body .form-select {
        border: 1.5px solid #E5E7EB;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        width: 100%;
    }

    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(99, 179, 237, 0.1);
        outline: none;
    }

    .modal-body .text-danger {
        color: #f56565;
        font-weight: 600;
    }

    .modal-body .text-muted {
        font-size: 0.8rem;
        color: #718096;
    }

    .modal-footer .btn-tambah {
        width: auto;
        min-width: 150px;
    }

    /* Responsive modal */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 10px;
        }
        
        .modal-body .row {
            flex-direction: column;
        }
        
        .modal-body .col-md-6 {
            width: 100%;
        }
    }

    /* ========== RESPONSIVE DESIGN ========== */
    /* Untuk layar yang lebih kecil dari desktop */
    @media (max-width: 1200px) {
        .cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 180px; /* Email lebih kecil */
        }
        
        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 180px; /* Nama lebih kecil */
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
            min-width: 800px;
        }
        
        .filter-form .row {
            flex-direction: column;
            gap: 12px;
        }
        
        .filter-form .col-md-4,
        .filter-form .col-md-2 {
            width: 100%;
        }
        
        /* Responsive untuk panel header */
        .panel-header {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }
        
        .panel-header h5 {
            text-align: center;
            justify-content: center;
        }
        
        .panel-header > .btn-tambah {
            margin-left: 0;
            width: 100%;
            min-width: unset;
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
        
        /* Responsive untuk panel header pada mobile */
        .panel-header {
            gap: 10px;
        }
        
        .panel-header h5 {
            font-size: 15px;
        }
        
        .panel-header .btn-tambah {
            padding: 8px 14px;
            font-size: 0.8rem;
            height: 38px;
            min-width: 140px;
        }
    }

    /* Untuk layar sangat kecil */
    @media (max-width: 360px) {
        .panel-header h5 {
            font-size: 14px;
        }
        
        .panel-header .btn-tambah {
            padding: 7px 12px;
            font-size: 0.75rem;
            height: 36px;
            min-width: 130px;
        }
        
        .panel-header .btn-tambah i {
            font-size: 12px;
        }
    }

    /* === PAGINATION STYLES === */
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

    <!-- Statistik Pengguna -->
    <div class="cards">
        <div class="card">
            <small>Total Pengguna</small>
            <h3><?php echo e(number_format($totalPengguna ?? 0)); ?></h3>
            <small>Semua pengguna terdaftar</small>
        </div>

        <div class="card">
            <small>Pemilik Venue</small>
            <h3><?php echo e(number_format($pemilikVenue ?? 0)); ?></h3>
            <small>Total venue terdaftar</small>
        </div>

        <div class="card">
            <small>Pengguna Baru</small>
            <h3><?php echo e(number_format($penggunaBaru ?? 0)); ?></h3>
            <small>Bulan ini</small>
        </div>

        <div class="card">
            <small>Admin</small>
            <h3><?php echo e(number_format($totalAdmin ?? 0)); ?></h3>
            <small>Total administrator</small>
        </div>
    </div>

    <!-- Search dan Filter -->
    <div class="filter-card">
        <div class="section-header">
            <h5>🔍 Filter Pengguna</h5>
        </div>
        <div class="section-body">
            <form method="GET" action="<?php echo e(route('admin.pengguna.index')); ?>" id="filterForm" class="filter-form">
                <input type="hidden" name="per_page" id="perPageHidden" value="<?php echo e(request('per_page', 10)); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Cari pengguna</label>
                        <input type="text" class="form-control" id="searchInput" name="search" 
                               placeholder="Cari nama, email, atau venue..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipe Pengguna</label>
                        <select class="form-select" id="typeFilter" name="type">
                            <option value="">Semua Tipe</option>
                            <option value="admin" <?php echo e(request('type') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                            <option value="venue" <?php echo e(request('type') == 'venue' ? 'selected' : ''); ?>>Pemilik Venue</option>
                            <option value="user" <?php echo e(request('type') == 'user' ? 'selected' : ''); ?>>Pengguna Biasa</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="statusFilter" name="status">
                            <option value="">Semua Status</option>
                            <option value="Aktif" <?php echo e(request('status') == 'Aktif' ? 'selected' : ''); ?>>Aktif</option>
                            <option value="Nonaktif" <?php echo e(request('status') == 'Nonaktif' ? 'selected' : ''); ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn-tambah">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo e(route('admin.pengguna.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-refresh me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

        <!-- Daftar Pengguna -->
        <div class="dashboard-section">
            <div class="panel-header">
                <h5>Daftar Pengguna</h5>
                <button type="button" class="btn-tambah" data-bs-toggle="modal" data-bs-target="#tambahPenggunaModal">
                    <i class="fas fa-plus me-2"></i>Tambah Pengguna
                </button>
            </div>
            <div class="section-body">
            <div class="table-responsive">
                <table class="table table-hover" id="usersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Tipe</th>
                            <th>Nama Venue</th>
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <?php
                            $perPage = request('per_page', 10);
                            $currentPage = $users->currentPage();
                            $startNumber = ($currentPage - 1) * $perPage + 1;
                        ?>
                        
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-bold"><?php echo e($user->id); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if($user->profile_photo && $user->profile_photo != '[null]'): ?>
                                        <img src="<?php echo e(Storage::url($user->profile_photo)); ?>" alt="<?php echo e($user->name); ?>" class="rounded-circle me-2" width="32" height="32">
                                    <?php else: ?>
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div style="min-width: 0;">
                                        <div class="fw-medium text-truncate"><?php echo e($user->name); ?></div>
                                        <?php if(strpos($user->email, 'admin') !== false): ?>
                                            <small class="text-muted">Administrator</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="text-truncate"><?php echo e($user->email); ?></td>
                            <td>
                                <?php if($user->phone && $user->phone != '[null]' && $user->phone != 'null'): ?>
                                    <?php echo e($user->phone); ?>

                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(strpos($user->email, 'admin') !== false): ?>
                                    <span class="badge badge-admin">Admin</span>
                                <?php elseif($user->venue_name && $user->venue_name != '[null]'): ?>
                                    <span class="badge badge-venue">Pemilik Venue</span>
                                <?php else: ?>
                                    <span class="badge badge-user">Pengguna</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-truncate">
                                <?php if($user->venue_name && $user->venue_name != '[null]'): ?>
                                    <?php echo e($user->venue_name); ?>

                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($user->created_at): ?>
                                    <?php echo e(\Carbon\Carbon::parse($user->created_at)->format('d M Y')); ?>

                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" title="Lihat Detail" data-bs-toggle="modal" data-bs-target="#detailPenggunaModal" data-user-id="<?php echo e($user->id); ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak ada data pengguna</h5>
                                    <p class="text-muted">Belum ada pengguna yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($users->hasPages()): ?>
            <div class="pagination-container">
                <div class="pagination-info">
                    <?php
                        $currentPage = $users->currentPage();
                        $perPage = request('per_page', 10);
                        $totalItems = $users->total();
                        
                        if ($perPage === 'all') {
                            $start = 1;
                            $end = $totalItems;
                        } else {
                            $start = ($currentPage - 1) * $perPage + 1;
                            $end = min($currentPage * $perPage, $totalItems);
                        }
                    ?>
                    Menampilkan <?php echo e($start); ?> - <?php echo e($end); ?> dari <?php echo e($totalItems); ?> pengguna
                </div>
                
                <div class="pagination-controls">
                    <ul class="pagination">
                        
                        <?php if($users->onFirstPage()): ?>
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </li>
                        <?php else: ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($users->previousPageUrl()); ?>" rel="prev">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        
                        <?php
                            $currentPage = $users->currentPage();
                            $lastPage = $users->lastPage();
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
                                <a class="page-link" href="<?php echo e($users->url(1)); ?>">1</a>
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
                                    <a class="page-link" href="<?php echo e($users->url($page)); ?>"><?php echo e($page); ?></a>
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
                                <a class="page-link" href="<?php echo e($users->url($lastPage)); ?>"><?php echo e($lastPage); ?></a>
                            </li>
                        <?php endif; ?>

                        
                        <?php if($users->hasMorePages()): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($users->nextPageUrl()); ?>" rel="next">
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
                    $totalItems = $users->total();
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
                        Menampilkan semua <?php echo e($totalItems); ?> pengguna
                    <?php else: ?>
                        Menampilkan <?php echo e($displayText); ?> dari <?php echo e($totalItems); ?> pengguna
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

    <!-- Modal Detail Pengguna -->
    <div class="modal fade" id="detailPenggunaModal" tabindex="-1" aria-labelledby="detailPenggunaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailPenggunaModalLabel">👤 Detail Pengguna</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="user-profile-header">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-profile-info">
                            <h4 id="detailNama">-</h4>
                            <p id="detailEmail">-</p>
                            <span class="badge badge-user" id="detailTipe">-</span>
                        </div>
                    </div>
                    
                    <div class="user-detail-item">
                        <div class="user-detail-label">ID Pengguna</div>
                        <div class="user-detail-value" id="detailId">-</div>
                    </div>
                    <div class="user-detail-item">
                        <div class="user-detail-label">Telepon</div>
                        <div class="user-detail-value" id="detailTelepon">-</div>
                    </div>
                    <div class="user-detail-item">
                        <div class="user-detail-label">Nama Venue</div>
                        <div class="user-detail-value" id="detailVenueName">-</div>
                    </div>
                    <div class="user-detail-item">
                        <div class="user-detail-label">Bergabung</div>
                        <div class="user-detail-value" id="detailBergabung">-</div>
                    </div>
                    <div class="user-detail-item">
                        <div class="user-detail-label">Terakhir Diperbarui</div>
                        <div class="user-detail-value" id="detailDiperbarui">-</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pengguna -->
    <div class="modal fade" id="tambahPenggunaModal" tabindex="-1" aria-labelledby="tambahPenggunaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPenggunaModalLabel">👤 Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="tambahPenggunaForm" action="<?php echo e(route('admin.pengguna.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="name" required placeholder="Masukkan nama lengkap">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required placeholder="contoh@email.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required placeholder="Minimal 8 karakter">
                                    <small class="text-muted">Password minimal 8 karakter</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="081234567890">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Tipe Pengguna <span class="text-danger">*</span></label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Pilih Tipe Pengguna</option>
                                        <option value="user">Pengguna Biasa</option>
                                        <option value="venue">Pemilik Venue</option>
                                        <option value="admin">Administrator</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" id="venueNameField" style="display: none;">
                                <div class="mb-3">
                                    <label for="venue_name" class="form-label">Nama Venue</label>
                                    <input type="text" class="form-control" id="venue_name" name="venue_name" placeholder="Masukkan nama venue">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-tambah">
                            <i class="fas fa-save me-2"></i>Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ========== TAMPILKAN/SEMBUNYIKAN FIELD NAMA VENUE ==========
        const roleSelect = document.getElementById('role');
        const venueNameField = document.getElementById('venueNameField');
        
        if (roleSelect && venueNameField) {
            roleSelect.addEventListener('change', function() {
                if (this.value === 'venue') {
                    venueNameField.style.display = 'block';
                    document.getElementById('venue_name').setAttribute('required', 'required');
                } else {
                    venueNameField.style.display = 'none';
                    document.getElementById('venue_name').removeAttribute('required');
                    document.getElementById('venue_name').value = '';
                }
            });
        }

        // ========== HANDLE FORM TAMBAH PENGGUNA ==========
        const tambahPenggunaForm = document.getElementById('tambahPenggunaForm');
        if (tambahPenggunaForm) {
            tambahPenggunaForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validasi password
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;
                
                if (password.length < 8) {
                    alert('Password minimal 8 karakter!');
                    return;
                }
                
                if (password !== confirmPassword) {
                    alert('Password dan konfirmasi password tidak cocok!');
                    return;
                }
                
                // Validasi email format
                const email = document.getElementById('email').value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    alert('Format email tidak valid!');
                    return;
                }
                
                // Tampilkan loading
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                submitBtn.disabled = true;
                
                // Kirim data via AJAX
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: document.getElementById('nama').value,
                        email: document.getElementById('email').value,
                        password: password,
                        password_confirmation: confirmPassword,
                        phone: document.getElementById('phone').value,
                        role: document.getElementById('role').value,
                        venue_name: document.getElementById('venue_name').value
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Tutup modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('tambahPenggunaModal'));
                        modal.hide();
                        
                        // Tampilkan pesan sukses
                        showToast('success', data.message || 'Pengguna berhasil ditambahkan!');
                        
                        // Refresh halaman setelah 1.5 detik
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Tampilkan error
                        let errorMessage = 'Terjadi kesalahan!';
                        if (data.errors) {
                            errorMessage = Object.values(data.errors).flat().join('\n');
                        } else if (data.message) {
                            errorMessage = data.message;
                        }
                        
                        showToast('error', errorMessage);
                        
                        // Reset button
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Terjadi kesalahan jaringan!');
                    
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }

        // ========== RESET FORM SAAT MODAL DITUTUP ==========
        const tambahModal = document.getElementById('tambahPenggunaModal');
        if (tambahModal) {
            tambahModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('tambahPenggunaForm').reset();
                if (venueNameField) {
                    venueNameField.style.display = 'none';
                    document.getElementById('venue_name').removeAttribute('required');
                }
            });
        }

        // ========== REAL-TIME SEARCH UNTUK PENGGUNA ==========
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#usersTableBody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                }, 300);
            });
        }

        // ========== AUTO-SUBMIT FILTER KETIKA DROPDOWN BERUBAH ==========
        document.getElementById('typeFilter').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('statusFilter').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        // ========== HANDLE MODAL DETAIL PENGGUNA ==========
        const detailModal = document.getElementById('detailPenggunaModal');
        if (detailModal) {
            detailModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-user-id');
                
                // Tampilkan loading
                document.getElementById('detailNama').textContent = 'Memuat...';
                document.getElementById('detailEmail').textContent = 'Memuat...';
                document.getElementById('detailId').textContent = 'Memuat...';
                document.getElementById('detailTelepon').textContent = 'Memuat...';
                document.getElementById('detailVenueName').textContent = 'Memuat...';
                document.getElementById('detailBergabung').textContent = 'Memuat...';
                document.getElementById('detailDiperbarui').textContent = 'Memuat...';
                document.getElementById('detailTipe').textContent = 'Memuat...';
                
                // Load data pengguna via AJAX
                fetch(`/admin/pengguna/${userId}`, {
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
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    
                    const user = data.user;
                    
                    // Isi data ke modal detail
                    document.getElementById('detailNama').textContent = user.name || '-';
                    document.getElementById('detailEmail').textContent = user.email || '-';
                    document.getElementById('detailId').textContent = user.id || '-';
                    document.getElementById('detailTelepon').textContent = (user.phone && user.phone !== '[null]' && user.phone !== 'null') ? user.phone : '-';
                    document.getElementById('detailVenueName').textContent = (user.venue_name && user.venue_name !== '[null]') ? user.venue_name : '-';
                    document.getElementById('detailBergabung').textContent = user.created_at ? new Date(user.created_at).toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) : '-';
                    document.getElementById('detailDiperbarui').textContent = user.updated_at ? new Date(user.updated_at).toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) : '-';
                    
                    // Set badge tipe
                    const tipeBadge = document.getElementById('detailTipe');
                    
                    if (user.email && user.email.includes('admin')) {
                        tipeBadge.className = 'badge badge-admin';
                        tipeBadge.textContent = 'Admin';
                    } else if (user.venue_name && user.venue_name !== '[null]') {
                        tipeBadge.className = 'badge badge-venue';
                        tipeBadge.textContent = 'Pemilik Venue';
                    } else {
                        tipeBadge.className = 'badge badge-user';
                        tipeBadge.textContent = 'Pengguna';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Gagal memuat data pengguna: ' + error.message);
                    
                    // Reset ke default jika error
                    document.getElementById('detailNama').textContent = '-';
                    document.getElementById('detailEmail').textContent = '-';
                    document.getElementById('detailId').textContent = '-';
                    document.getElementById('detailTelepon').textContent = '-';
                    document.getElementById('detailVenueName').textContent = '-';
                    document.getElementById('detailBergabung').textContent = '-';
                    document.getElementById('detailDiperbarui').textContent = '-';
                    document.getElementById('detailTipe').textContent = '-';
                });
            });
        }

        // ========== FUNCTION UNTUK MENGUBAH JUMLAH ITEM PER HALAMAN ==========
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

        // ========== EVENT LISTENER UNTUK DROPDOWN ITEMS PER PAGE ==========
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

        // ========== TOAST NOTIFICATION FUNCTION ==========
        function showToast(type, message) {
            // Buat elemen toast
            const toastEl = document.createElement('div');
            toastEl.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            toastEl.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            
            toastEl.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Tambahkan ke body
            document.body.appendChild(toastEl);
            
            // Auto close setelah 5 detik
            setTimeout(() => {
                if (toastEl.parentNode) {
                    toastEl.remove();
                }
            }, 5000);
        }

        // ========== AUTO CLOSE ALERT SETELAH 5 DETIK ==========
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (!alert.classList.contains('position-fixed')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\CariArena\resources\views/admin/manajemen_pengguna.blade.php ENDPATH**/ ?>