<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Venue</title>

    <style>
    /* ============================
       RESET & BASE STYLES
    ============================ */
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
        margin: 15px 20px;
        color: #1e293b;
        background: #ffffff;
        line-height: 1.4;
    }

    h1 {
        text-align: center;
        margin-bottom: 5px;
        font-size: 16px;
        text-transform: uppercase;
        font-weight: bold;
        color: #0f172a;
    }

    .header-info {
        text-align: center;
        font-size: 9px;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 1px solid #cbd5e1;
    }

    .header-info p {
        margin: 3px 0;
    }

    /* ============================
       STATS GRID - LEBIH KECIL
    ============================ */
    .cards {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .card-stat {
        flex: 1;
        min-width: 180px;
        background: #ffffff;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
        min-height: 80px;
        border-left: 3px solid;
        margin-bottom: 5px;
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
        color: #718096;
        font-size: 9px;
        display: block;
        font-weight: 500;
        margin-bottom: 3px;
    }

    .card-stat h3 {
        margin: 3px 0;
        font-size: 18px;
        font-weight: 700;
        line-height: 1.2;
    }

    .card-stat small:last-of-type {
        font-size: 8px;
        color: #718096;
        opacity: 0.8;
        margin-top: 2px;
    }

    /* ============================
       CHART CONTAINER - LEBIH KECIL DENGAN ASPECT RATIO TEPAT
    ============================ */
    .charts-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .chart-section {
        flex: 1;
        min-width: 280px;
        background: #ffffff;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    .chart-header {
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
    }

    .chart-title {
        font-size: 12px;
        font-weight: 600;
        margin: 0;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .chart-container {
    width: 100%;
    height: 220px;          /* FIX HEIGHT */
    text-align: center;
    margin: 0 auto;
    }

    .chart-img {
        max-width: 100%;
        max-height: 220px;
        width: auto;            /* PENTING */
        height: auto;           /* PENTING */
        display: inline-block;
    }

    .chart-img.pie {
    max-height: 200px;
    }



    /* ============================
       TABLE STYLES
    ============================ */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 9px;
    }

    th {
        background: #4299E1;
        color: #fff;
        padding: 6px 5px;
        font-size: 9px;
        border: 1px solid #4299E1;
        text-align: center;
        font-weight: 600;
        text-transform: uppercase;
    }

    td {
        padding: 6px 5px;
        border: 1px solid #cbd5e1;
        font-size: 9px;
    }

    tr:nth-child(even) {
        background: #f8fafc;
    }

    .text-right {
        text-align: right;
    }

    /* Badge untuk status */
    .badge {
        font-size: 8px;
        padding: 2px 5px;
        font-weight: 500;
        border-radius: 3px;
        display: inline-block;
        text-align: center;
        white-space: nowrap;
    }

    .badge-success {
        background-color: #E8F5E8;
        color: #48BB78;
    }

    .badge-warning {
        background-color: #FFF3E0;
        color: #ED8936;
    }

    .badge-danger {
        background-color: #FFEBEE;
        color: #F56565;
    }

    .badge-info {
        background-color: #E8F4FD;
        color: #4299E1;
    }

    .badge-secondary {
    background-color: #F1F5F9;
    color: #64748B;
    }

    /* ============================
       FOOTER
    ============================ */
    .footer {
        margin-top: 20px;
        border-top: 1px solid #cbd5e1;
        padding-top: 8px;
        text-align: right;
        font-size: 9px;
        color: #475569;
    }

    .footer p {
        margin: 2px 0;
    }

    /* ============================
       PRINT OPTIMIZATIONS
    ============================ */
    @media print {
        body {
            font-size: 9pt;
            margin: 10px 15px;
        }
        
        .cards {
            page-break-inside: avoid;
        }
        
        .card-stat {
            min-width: 160px;
            padding: 10px;
            min-height: 70px;
        }
        
        .card-stat h3 {
            font-size: 16px;
        }
        
        .chart-section {
            page-break-inside: avoid;
            min-width: 250px;
            padding: 10px;
        }
        
        .chart-container {
            height: 160px;
        }
        
        table {
            page-break-inside: auto;
            font-size: 8pt;
        }
        
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        thead {
            display: table-header-group;
        }
        
        th, td {
            padding: 5px 4px;
        }
    }

    /* ============================
       SPACING UTILITIES
    ============================ */
    .mb-1 { margin-bottom: 5px !important; }
    .mb-2 { margin-bottom: 10px !important; }
    .mb-3 { margin-bottom: 15px !important; }
    .mb-4 { margin-bottom: 20px !important; }
    
    .mt-1 { margin-top: 5px !important; }
    .mt-2 { margin-top: 10px !important; }
    .mt-3 { margin-top: 15px !important; }
    .mt-4 { margin-top: 20px !important; }
</style>

</head>
<body>

    <h1>Laporan Venue</h1>

    <div class="header-info">
    <p>
    <strong>
        <?php echo e(($isAdmin ?? false) ? 'Admin:' : 'Pemilik Venue:'); ?>

    </strong>
    <?php echo e($user->name); ?>

</p>
        <p><strong>Email:</strong> <?php echo e($user->email); ?></p>
        <p><strong>Periode:</strong> <?php echo e($periode); ?></p>
        <p><strong>Tanggal Export:</strong> <?php echo e($tanggalExport); ?></p>
    </div>

    <!-- ============================
         STATISTIK - 4 KOLOM LEBIH KECIL
    ============================ -->
    <div class="cards mb-4">
        <div class="card-stat">
            <small>Total Transaksi</small>
            <h3><?php echo e($totalTransaksi); ?></h3>
            <small>Transaksi terdaftar</small>
        </div>

        <div class="card-stat">
            <small>Total Pendapatan</small>
            <h3>Rp <?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?></h3>
            <small>Pendapatan kotor</small>
        </div>

        <div class="card-stat">
            <small>Rata-rata / Hari</small>
            <h3><?php echo e($rataRata); ?></h3>
            <small>Booking per hari</small>
        </div>

        <div class="card-stat">
            <small>Venue Terpopuler</small>
            <h3><?php echo e($venueTerpopuler); ?></h3>
            <small>
    <?php echo e(($isAdmin ?? false) ? 'Semua Venue' : ($jumlahPemesananVenue ?? 0).'x dipesan'); ?>

</small>
        </div>
    </div>

    <!-- ============================
         GRAFIK - DUA KOLOM LEBIH KECIL
    ============================ -->
    <div class="charts-row mb-4">
        <!-- Revenue Chart -->
        <div class="chart-section">
            <div class="chart-header">
                <h5 class="chart-title">📈 Pendapatan Bulanan</h5>
            </div>
            <div class="chart-container">
                <img class="chart-img" src="<?php echo e($revenueChartImg); ?>">
            </div>
        </div>

        <!-- Venue Distribution Chart -->
        <div class="chart-section">
            <div class="chart-header">
                <h5 class="chart-title">🥧 Distribusi Booking per Venue</h5>
            </div>
            <div class="chart-container">
                <img class="chart-img pie" src="<?php echo e($venueChartImg); ?>">
            </div>
        </div>
    </div>

    <br><br><br>


    <!-- ============================
         TABEL TRANSAKSI
    ============================ -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Customer</th>
                <th>Venue</th>
                <th>Metode</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Durasi</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            <?php $no = 1; ?>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($no++); ?></td>
                <td><?php echo e($row->transaction_number ?? $row->kode); ?></td>
                <td><?php echo e($row->pengguna ?? $row->customer); ?></td>
                <td><?php echo e($row->nama_venue ?? $row->venue); ?></td>
                <td><?php echo e($row->metode_pembayaran ?? $row->metode); ?></td>
                <td class="text-right">Rp <?php echo e(number_format($row->amount ?? $row->jumlah, 0, ',', '.')); ?></td>
                <td>
                    <?php echo e(\Carbon\Carbon::parse($row->transaction_date ?? $row->tanggal)->format('d/m/Y')); ?>

                </td>
                <td><?php echo e($row->durasi); ?> jam</td>
                <td>
                    <?php if(($row->status ?? '') === 'completed' || ($row->status ?? '') === 'Selesai'): ?>
                        <span class="badge badge-success">Selesai</span>
                    <?php elseif(($row->status ?? '') === 'pending' || ($row->status ?? '') === 'Pending'): ?>
                        <span class="badge badge-warning">Pending</span>
                    <?php elseif(($row->status ?? '') === 'cancelled' || ($row->status ?? '') === 'Dibatalkan'): ?>
                        <span class="badge badge-danger">Dibatalkan</span>
                    <?php elseif(($row->status ?? '') === 'refunded' || ($row->status ?? '') === 'Dikembalikan'): ?>
                        <span class="badge badge-info">Dikembalikan</span>
                    <?php else: ?>
                        <span class="badge badge-secondary"><?php echo e($row->status); ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="footer mt-4">
        <p>Dibuat oleh: <?php echo e($user->name); ?></p>
        <p>© <?php echo e(date('Y')); ?> CariArena</p>
    </div>

</body>
</html><?php /**PATH D:\CariArena\resources\views/venue/exports/pdf.blade.php ENDPATH**/ ?>