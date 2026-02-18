

<?php $__env->startSection('title', 'Manajemen Venue - CariArena'); ?>
<?php $__env->startSection('page-title', 'Manajemen Venue'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* === VARIABLES BERDASARKAN STYLE PEMESANAN === */
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

    /* === STATISTIC CARDS - STYLE KONSISTEN DENGAN PENGGUNA DAN PEMESANAN === */
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

    /* === TABLE STYLES - STYLE BARU SESUAI PENGGUNA === */
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

    .table tbody tr:last-child td {
        border-bottom: none;
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

    /* === BADGES - STYLE BARU SESUAI PENGGUNA === */
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
        border-radius: 6px;
    }

    .badge-active {
        background-color: #E8F5E8;
        color: var(--success);
    }

    .badge-maintenance {
        background-color: #FFF3E0;
        color: var(--warning);
    }

    .badge-inactive {
        background-color: #FFEBEE;
        color: var(--danger);
    }

    .badge-sport {
        background-color: #E3F2FD;
        color: var(--info);
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

    /* === ACTION BUTTONS - PERBAIKAN: TOMBOL MATA TANPA BACKGROUND === */
    .btn-group-sm .btn {
        padding: 0.4rem 0.6rem;
        font-size: 0.75rem;
        margin: 1px;
        border: none;
        background: transparent !important;
    }

    .btn-eye {
        color: var(--info) !important;
        background: transparent !important;
        border: none !important;
        padding: 0.4rem 0.6rem;
    }

    .btn-eye:hover {
        color: var(--primary-hover) !important;
        background: transparent !important;
        transform: translateY(-1px);
    }

    .btn-eye .fas {
        color: inherit !important;
    }

    /* Hover effect */
    .btn-group-sm .btn:hover {
        transform: translateY(-1px);
        box-shadow: none !important;
    }

    /* === MODAL STYLES - PERBAIKAN UKURAN DAN TAMPILAN === */
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

    .modal-title {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 15px 20px;
    }

    .venue-detail-item {
        display: flex;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
    }

    .venue-detail-label {
        font-weight: 600;
        color: var(--text-dark);
        min-width: 120px;
    }

    .venue-detail-value {
        color: var(--text-light);
    }

    .venue-avatar {
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

    .venue-profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .venue-profile-info h4 {
        margin: 0 0 5px 0;
        color: var(--text-dark);
        font-size: 18px;
    }

    .venue-profile-info p {
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
        
        .table {
            min-width: 700px;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 0.25em 0.5em;
        }
        
        .venue-avatar {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .venue-profile-info h4 {
            font-size: 1rem;
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

    <!-- Statistik Venue -->
    <div class="cards">
        <div class="card">
            <small>Total Venue</small>
            <h3><?php echo e(number_format($totalVenue ?? 0)); ?></h3>
            <small>Semua venue terdaftar</small>
        </div>

        <div class="card">
            <small>Venue Aktif</small>
            <h3><?php echo e(number_format($venueAktif ?? 0)); ?></h3>
            <small>Venue yang beroperasi</small>
        </div>

        <div class="card">
            <small>Dalam Perawatan</small>
            <h3><?php echo e(number_format($venuePerawatan ?? 0)); ?></h3>
            <small>Venue sedang maintenance</small>
        </div>

        <div class="card">
            <small>Tingkat Pemanfaatan</small>
            <h3><?php echo e($tingkatPemanfaatan ?? 0); ?>%</h3>
            <small>Rata-rata okupansi</small>
        </div>
    </div>

    <!-- Search dan Filter - PERBAIKAN TOMBOL SEBARIS -->
    <div class="filter-card">
        <div class="section-header">
            <h5>🔍 Filter Venue</h5>
        </div>
        <div class="section-body">
            <form method="GET" action="<?php echo e(route('admin.venue.index')); ?>" id="filterForm" class="filter-form">
                <input type="hidden" name="per_page" id="perPageHidden" value="<?php echo e(request('per_page', 10)); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Cari venue</label>
                        <input type="text" class="form-control" id="searchInput" name="search" 
                               placeholder="Cari nama venue atau lokasi..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="statusFilter" name="status">
                            <option value="">Semua Status</option>
                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Aktif</option>
                            <option value="maintenance" <?php echo e(request('status') == 'maintenance' ? 'selected' : ''); ?>>Perawatan</option>
                            <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" id="categoryFilter" name="category">
                            <option value="">Semua Kategori</option>
                            <option value="Futsal" <?php echo e(request('category') == 'Futsal' ? 'selected' : ''); ?>>Futsal</option>
                            <option value="Basket" <?php echo e(request('category') == 'Basket' ? 'selected' : ''); ?>>Basket</option>
                            <option value="Badminton" <?php echo e(request('category') == 'Badminton' ? 'selected' : ''); ?>>Badminton</option>
                            <option value="Soccer" <?php echo e(request('category') == 'Soccer' ? 'selected' : ''); ?>>Soccer</option>
                            <option value="Tennis" <?php echo e(request('category') == 'Tennis' ? 'selected' : ''); ?>>Tennis</option>
                            <option value="Volleyball" <?php echo e(request('category') == 'Volleyball' ? 'selected' : ''); ?>>Volleyball</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="filter-buttons">
                            <button type="submit" class="btn-tambah">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                            <a href="<?php echo e(route('admin.venue.index')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Venue -->
    <div class="dashboard-section">
        <div class="panel-header">
            <h5>📋 Daftar Venue</h5>
        </div>
        <div class="section-body">
            <div class="table-responsive">
                <table class="table table-hover" id="venuesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Venue</th>
                            <th>Lokasi</th>
                            <th>Kategori</th>
                            <th>Fasilitas</th>
                            <th>Harga/Jam</th>
                            <th>Status</th>
                            <th>Rating</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="venuesTableBody">
                        <?php $__empty_1 = true; $__currentLoopData = $venues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-bold"><?php echo e($venue->id); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if($venue->photo && $venue->photo != '[null]'): ?>
                                        <img src="<?php echo e(Storage::url($venue->photo)); ?>" alt="<?php echo e($venue->name); ?>" class="rounded-circle me-2" width="32" height="32">
                                    <?php else: ?>
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i class="fas fa-store"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-medium"><?php echo e($venue->name); ?></div>
                                        <small class="text-muted"><?php echo e($venue->category); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e(Str::limit($venue->address, 30)); ?></td>
                            <td>
                                <span class="badge badge-sport"><?php echo e($venue->category); ?></span>
                            </td>
                            <td>
                                <?php
                                    $fasilitas = $venue->facilities;
                                    if (is_string($fasilitas) && is_array(json_decode($fasilitas, true))) {
                                        $fasilitasArray = json_decode($fasilitas, true);
                                        $fasilitas = is_array($fasilitasArray) ? implode(', ', $fasilitasArray) : $fasilitas;
                                    } elseif (is_array($fasilitas)) {
                                        $fasilitas = implode(', ', $fasilitas);
                                    }
                                    $fasilitas = is_string($fasilitas) ? $fasilitas : '';
                                ?>
                                <?php echo e(Str::limit($fasilitas, 30)); ?>

                            </td>
                            <td class="fw-bold text-success">Rp <?php echo e(number_format($venue->price_per_hour, 0, ',', '.')); ?></td>
                            <td>
                                <span class="badge badge-<?php echo e($venue->status == 'active' ? 'active' : ($venue->status == 'maintenance' ? 'maintenance' : 'inactive')); ?>">
                                    <?php echo e($venue->status == 'active' ? 'Aktif' : ($venue->status == 'maintenance' ? 'Perawatan' : 'Nonaktif')); ?>

                                </span>
                            </td>
                            <td>
                                <i class="fas fa-star text-warning"></i> <?php echo e($venue->rating ?? '0.0'); ?>

                                <small class="text-muted">(<?php echo e($venue->reviews_count ?? 0); ?>)</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-eye" title="Lihat Detail" data-bs-toggle="modal" data-bs-target="#detailVenueModal" data-venue-id="<?php echo e($venue->id); ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-store fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak ada data venue</h5>
                                    <p class="text-muted">Belum ada venue yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($venues->hasPages()): ?>
            <div class="pagination-container">
                <div class="pagination-info">
                    <?php
                        $currentPage = $venues->currentPage();
                        $perPage = request('per_page', 10);
                        $totalItems = $venues->total();
                        
                        if ($perPage === 'all') {
                            $start = 1;
                            $end = $totalItems;
                        } else {
                            $start = ($currentPage - 1) * $perPage + 1;
                            $end = min($currentPage * $perPage, $totalItems);
                        }
                    ?>
                    Menampilkan <?php echo e($start); ?> - <?php echo e($end); ?> dari <?php echo e($totalItems); ?> venue
                </div>
                
                <div class="pagination-controls">
                    <ul class="pagination">
                        
                        <?php if($venues->onFirstPage()): ?>
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </li>
                        <?php else: ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($venues->previousPageUrl()); ?>" rel="prev">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        
                        <?php
                            $currentPage = $venues->currentPage();
                            $lastPage = $venues->lastPage();
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
                                <a class="page-link" href="<?php echo e($venues->url(1)); ?>">1</a>
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
                                    <a class="page-link" href="<?php echo e($venues->url($page)); ?>"><?php echo e($page); ?></a>
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
                                <a class="page-link" href="<?php echo e($venues->url($lastPage)); ?>"><?php echo e($lastPage); ?></a>
                            </li>
                        <?php endif; ?>

                        
                        <?php if($venues->hasMorePages()): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($venues->nextPageUrl()); ?>" rel="next">
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
                    $totalItems = $venues->total();
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
                        Menampilkan semua <?php echo e($totalItems); ?> venue
                    <?php else: ?>
                        Menampilkan <?php echo e($displayText); ?> dari <?php echo e($totalItems); ?> venue
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

    <!-- Modal Detail Venue -->
    <div class="modal fade" id="detailVenueModal" tabindex="-1" aria-labelledby="detailVenueModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailVenueModalLabel">🏢 Detail Venue</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="venue-profile-header">
                        <div class="venue-avatar">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="venue-profile-info">
                            <h4 id="detailNama">-</h4>
                            <p id="detailKategori">-</p>
                            <span class="badge badge-active" id="detailStatus">-</span>
                        </div>
                    </div>
                    
                    <div class="venue-detail-item">
                        <div class="venue-detail-label">ID Venue</div>
                        <div class="venue-detail-value" id="detailId">-</div>
                    </div>
                    <div class="venue-detail-item">
                        <div class="venue-detail-label">Alamat</div>
                        <div class="venue-detail-value" id="detailAlamat">-</div>
                    </div>
                    <div class="venue-detail-item">
                        <div class="venue-detail-label">Fasilitas</div>
                        <div class="venue-detail-value" id="detailFasilitas">-</div>
                    </div>
                    <div class="venue-detail-item">
                        <div class="venue-detail-label">Harga per Jam</div>
                        <div class="venue-detail-value" id="detailHarga">-</div>
                    </div>
                    <div class="venue-detail-item">
                        <div class="venue-detail-label">Rating</div>
                        <div class="venue-detail-value" id="detailRating">-</div>
                    </div>
                    <div class="venue-detail-item">
                        <div class="venue-detail-label">Jumlah Review</div>
                        <div class="venue-detail-value" id="detailReviews">-</div>
                    </div>
                    <div class="venue-detail-item">
                        <div class="venue-detail-label">Dibuat</div>
                        <div class="venue-detail-value" id="detailDibuat">-</div>
                    </div>
                    <div class="venue-detail-item">
                        <div class="venue-detail-label">Diperbarui</div>
                        <div class="venue-detail-value" id="detailDiperbarui">-</div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Fungsi untuk menampilkan pesan error
        function showError(message) {
            alert('Error: ' + message);
        }

        // Real-time search untuk venue
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#venuesTableBody tr');
                    
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

        document.getElementById('categoryFilter').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        // Handle modal detail venue
        const detailModal = document.getElementById('detailVenueModal');
        if (detailModal) {
            detailModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const venueId = button.getAttribute('data-venue-id');
                
                console.log('Loading venue ID:', venueId);
                
                // Tampilkan loading
                document.getElementById('detailNama').textContent = 'Memuat...';
                document.getElementById('detailKategori').textContent = 'Memuat...';
                document.getElementById('detailId').textContent = 'Memuat...';
                document.getElementById('detailAlamat').textContent = 'Memuat...';
                document.getElementById('detailFasilitas').textContent = 'Memuat...';
                document.getElementById('detailHarga').textContent = 'Memuat...';
                document.getElementById('detailRating').textContent = 'Memuat...';
                document.getElementById('detailReviews').textContent = 'Memuat...';
                document.getElementById('detailDibuat').textContent = 'Memuat...';
                document.getElementById('detailDiperbarui').textContent = 'Memuat...';
                document.getElementById('detailStatus').textContent = 'Memuat...';
                
                // Load data venue via AJAX
                fetch(`/admin/venue/${venueId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    
                    const venue = data.venue || data;
                    
                    // Handle fasilitas yang mungkin array/JSON
                    let fasilitasDisplay = venue.facilities || '';
                    if (typeof fasilitasDisplay === 'object' || (typeof fasilitasDisplay === 'string' && fasilitasDisplay.startsWith('['))) {
                        try {
                            const fasilitasArray = typeof fasilitasDisplay === 'string' ? JSON.parse(fasilitasDisplay) : fasilitasDisplay;
                            fasilitasDisplay = Array.isArray(fasilitasArray) ? fasilitasArray.join(', ') : fasilitasDisplay;
                        } catch (e) {
                            console.warn('Error parsing facilities:', e);
                        }
                    }
                    
                    // Isi data ke modal detail dari database
                    document.getElementById('detailNama').textContent = venue.name || '-';
                    document.getElementById('detailKategori').textContent = venue.category || '-';
                    document.getElementById('detailId').textContent = venue.id || '-';
                    document.getElementById('detailAlamat').textContent = venue.address || '-';
                    document.getElementById('detailFasilitas').textContent = fasilitasDisplay || '-';
                    document.getElementById('detailHarga').textContent = venue.price_per_hour ? 'Rp ' + new Intl.NumberFormat('id-ID').format(venue.price_per_hour) : '-';
                    document.getElementById('detailRating').textContent = venue.rating ? venue.rating + '/5.0' : '0.0/5.0';
                    document.getElementById('detailReviews').textContent = venue.reviews_count || '0';
                    document.getElementById('detailDibuat').textContent = venue.created_at ? new Date(venue.created_at).toLocaleDateString('id-ID') : '-';
                    document.getElementById('detailDiperbarui').textContent = venue.updated_at ? new Date(venue.updated_at).toLocaleDateString('id-ID') : '-';
                    
                    // Set badge status
                    const statusBadge = document.getElementById('detailStatus');
                    const statusText = venue.status === 'active' ? 'Aktif' : 
                                     venue.status === 'maintenance' ? 'Perawatan' : 'Nonaktif';
                    const statusClass = venue.status === 'active' ? 'badge-active' : 
                                     venue.status === 'maintenance' ? 'badge-maintenance' : 'badge-inactive';
                    
                    statusBadge.className = 'badge ' + statusClass;
                    statusBadge.textContent = statusText;
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Gagal memuat data venue: ' + error.message);
                    
                    // Reset ke default jika error
                    document.getElementById('detailNama').textContent = '-';
                    document.getElementById('detailKategori').textContent = '-';
                    document.getElementById('detailId').textContent = '-';
                    document.getElementById('detailAlamat').textContent = '-';
                    document.getElementById('detailFasilitas').textContent = '-';
                    document.getElementById('detailHarga').textContent = '-';
                    document.getElementById('detailRating').textContent = '-';
                    document.getElementById('detailReviews').textContent = '-';
                    document.getElementById('detailDibuat').textContent = '-';
                    document.getElementById('detailDiperbarui').textContent = '-';
                    document.getElementById('detailStatus').textContent = '-';
                });
            });
        }

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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\CariArena\resources\views/admin/venue.blade.php ENDPATH**/ ?>