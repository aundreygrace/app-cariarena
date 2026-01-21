@extends('layouts.venue')

@section('title', 'Notifikasi')

@section('page-title', 'Notifikasi')

@section('content')
<a href="/venue/dashboard" class="back-button">
    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
</a>

<div class="filter-section">
    <div class="filter-notifikasi">
        <button class="active" data-filter="semua">Semua</button>
        <button data-filter="belum-dibaca">Belum Dibaca</button>
        <button data-filter="pembayaran">Pembayaran</button>
        <button data-filter="ulasan">Ulasan</button>
        <button data-filter="booking">Booking</button>
        <button data-filter="sistem">Sistem</button>
    </div>
</div>

<!-- Bagian header notifikasi yang dipisahkan -->
<div class="notifikasi-header-section">
    <h2>Semua Notifikasi</h2>
</div>

<div class="notifikasi-container">
    @forelse($notifications as $notification)
    <div class="notifikasi-item {{ $notification->is_read ? '' : 'unread' }}" data-id="{{ $notification->id }}" data-type="{{ $notification->type ?? 'sistem' }}">
        <div class="notifikasi-icon {{ $notification->type ?? 'sistem' }}">
            @switch($notification->type ?? 'sistem')
                @case('pembayaran')
                    <i class="fas fa-money-bill-wave"></i>
                    @break
                @case('ulasan')
                    <i class="fas fa-star"></i>
                    @break
                @case('booking')
                    <i class="fas fa-calendar-plus"></i>
                    @break
                @default
                    <i class="fas fa-cog"></i>
            @endswitch
        </div>
        <div class="notifikasi-content">
            <h4>{{ $notification->title ?? 'Notifikasi Sistem' }}</h4>
            <p>{{ $notification->message ?? ($notification->description ?? 'Tidak ada pesan') }}</p>
            <div class="notifikasi-time">{{ $notification->created_at->diffForHumans() }}</div>
        </div>
        <div class="notifikasi-actions">
            @if(!$notification->is_read)
            <button class="btn-tandai" data-id="{{ $notification->id }}">Tandai Dibaca</button>
            @else
            <button class="btn-tandai" data-id="{{ $notification->id }}" disabled>Sudah Dibaca</button>
            @endif
            <button class="btn-hapus" data-id="{{ $notification->id }}" data-title="{{ $notification->title ?? 'Notifikasi' }}"><i class="fas fa-trash"></i></button>
        </div>
    </div>
    @empty
    <div class="notifikasi-item">
        <div class="notifikasi-icon sistem">
            <i class="fas fa-bell-slash"></i>
        </div>
        <div class="notifikasi-content">
            <h4>Tidak ada notifikasi</h4>
            <p>Belum ada notifikasi yang tersedia</p>
            <div class="notifikasi-time">Sekarang</div>
        </div>
        <div class="notifikasi-actions">
            <button class="btn-tandai" disabled>Tandai Dibaca</button>
            <button class="btn-hapus" disabled><i class="fas fa-trash"></i></button>
        </div>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if(method_exists($notifications, 'hasPages') && $notifications->hasPages())
<div class="pagination-container">
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($notifications->onFirstPage())
            <button disabled><i class="fas fa-chevron-left"></i></button>
        @else
            <a href="{{ $notifications->previousPageUrl() }}"><button><i class="fas fa-chevron-left"></i></button></a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
            @if ($page == $notifications->currentPage())
                <button class="active">{{ $page }}</button>
            @else
                <a href="{{ $url }}"><button>{{ $page }}</button></a>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($notifications->hasMorePages())
            <a href="{{ $notifications->nextPageUrl() }}"><button><i class="fas fa-chevron-right"></i></button></a>
        @else
            <button disabled><i class="fas fa-chevron-right"></i></button>
        @endif
    </div>
</div>
@endif

<!-- Modal Hapus Notifikasi -->
<div class="modal-overlay" id="modalHapus">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Hapus Notifikasi</h2>
        </div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus notifikasi ini?</p>
            <p>Notifikasi dengan ID <strong id="modalNotifikasiId">N001</strong> akan dihapus secara permanent.<br>
            Tindakan ini tidak dapat dibatalkan.</p>
            
            <div class="notifikasi-detail">
                <h4>Detail Notifikasi:</h4>
                <p id="modalNotifikasiTitle">Pembayaran Diterima</p>
                <p id="modalNotifikasiType">Jenis: Pembayaran</p>
                <p id="modalNotifikasiTime">Waktu: 2 menit yang lalu</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-batal" id="btnBatal">Batal</button>
            <button class="btn-hapus-modal" id="btnHapusModal">Hapus Notifikasi</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Variabel CSS untuk konsistensi */
    :root {
        --mobile-padding: 12px;
        --tablet-padding: 15px;
        --mobile-font-size: 14px;
        --tablet-font-size: 15px;
        --mobile-icon-size: 16px;
        --tablet-icon-size: 18px;
        
        /* Breakpoints */
        --tablet-breakpoint: 1024px;
        --mobile-breakpoint: 768px;
        --small-mobile-breakpoint: 480px;
    }

    /* Style untuk memisahkan bagian "Semua Notifikasi" dari isi notifikasi */
    .notifikasi-header-section {
        background: var(--card-bg);
        border-radius: 14px 14px 0 0;
        padding: 20px;
        margin-bottom: 0;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .notifikasi-header-section h2 {
        color: var(--primary-color);
        margin: 0;
        font-size: 20px;
        font-weight: 600;
    }
    
    /* Mengubah container notifikasi agar tidak memiliki radius di bagian atas */
    .notifikasi-container {
        border-radius: 0 0 14px 14px;
    }
    
    /* ==== FILTER NOTIFIKASI - DIUBAH ALIGNMENT MENJADI CENTER ==== */
    .filter-section {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .filter-notifikasi {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .filter-notifikasi button {
        padding: 8px 16px;
        border: none;
        border-radius: 20px;
        background: white;
        color: var(--text-light);
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    .filter-notifikasi button:hover {
        background: var(--primary-light);
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .filter-notifikasi button.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    /* ==== NOTIFIKASI ITEM ==== */
    .notifikasi-container {
        background: var(--card-bg);
        border-radius: 0 0 14px 14px;
        padding: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .notifikasi-item {
        display: flex;
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.3s ease;
        position: relative;
    }

    .notifikasi-item:hover {
        background: var(--primary-light);
    }

    .notifikasi-item:last-child {
        border-bottom: none;
    }

    .notifikasi-item.unread {
        background: #f0f9ff;
        border-left: 4px solid var(--primary-color);
    }

    .notifikasi-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
        font-size: 20px;
    }

    .notifikasi-icon.pembayaran {
        background: #C6F6D5;
        color: var(--success);
    }

    .notifikasi-icon.ulasan {
        background: #FEFCBF;
        color: var(--warning);
    }

    .notifikasi-icon.booking {
        background: #BEE3F8;
        color: var(--primary-color);
    }

    .notifikasi-icon.sistem {
        background: #E9D8FD;
        color: #9F7AEA;
    }

    .notifikasi-content {
        flex: 1;
    }

    .notifikasi-content h4 {
        font-size: 16px;
        margin-bottom: 5px;
        color: var(--text-dark);
        font-weight: 600;
    }

    .notifikasi-content p {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 5px;
        line-height: 1.5;
    }

    .notifikasi-time {
        font-size: 12px;
        color: var(--text-light);
        margin-top: 5px;
    }

    .notifikasi-actions {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .notifikasi-actions button {
        background: none;
        border: none;
        color: var(--text-light);
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
        padding: 5px 10px;
        border-radius: 4px;
    }

    .notifikasi-actions button:hover {
        background: rgba(0,0,0,0.05);
        color: var(--text-dark);
    }

    .notifikasi-actions .btn-hapus:hover {
        color: var(--danger);
    }

    .notifikasi-actions button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* ==== PAGINATION ==== */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 25px;
    }

    .pagination {
        display: flex;
        gap: 5px;
    }

    .pagination button {
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 8px;
        background: white;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .pagination button:hover {
        background: var(--primary-light);
        color: var(--primary-color);
    }

    .pagination button.active {
        background: var(--primary-color);
        color: white;
    }

    .pagination button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

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

    /* ==== MODAL HAPUS NOTIFIKASI ==== */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        transform: translateY(-20px);
        transition: transform 0.3s ease;
        overflow: hidden;
    }

    .modal-overlay.active .modal-content {
        transform: translateY(0);
    }

    .modal-header {
        background-color: var(--primary-light);
        color: white;
        padding: 20px;
        text-align: center;
    }

    .modal-header h2 {
        color: var(--text-dark);
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .modal-body {
        padding: 25px;
        border-bottom: 1px solid #e2e8f0;
    }

    .modal-body p {
        margin-bottom: 15px;
        color: var(--text-dark);
        line-height: 1.6;
    }

    .modal-body .notifikasi-detail {
        background: #f7fafc;
        border-radius: 8px;
        padding: 15px;
        margin: 20px 0;
    }

    .modal-body .notifikasi-detail h4 {
        margin-top: 0;
        margin-bottom: 10px;
        color: var(--text-dark);
        font-size: 16px;
    }

    .modal-body .notifikasi-detail p {
        margin-bottom: 8px;
        color: var(--text-light);
    }

    .modal-footer {
        padding: 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .modal-footer button {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-batal {
        background: white;
        color: var(--text-light);
        border: 1px solid #e2e8f0;
    }

    .btn-batal:hover {
        background: #f7fafc;
    }

    .btn-hapus-modal {
        background: var(--danger);
        color: white;
    }

    .btn-hapus-modal:hover {
        background: #e53e3e;
    }

    /* ========== RESPONSIVE DESIGN ========== */

    /* Tablet (768px - 1024px) */
    @media (max-width: 1024px) {
        .filter-section {
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .filter-notifikasi {
            gap: 8px;
        }
        
        .filter-notifikasi button {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        .notifikasi-header-section {
            padding: 15px;
        }
        
        .notifikasi-header-section h2 {
            font-size: 18px;
        }
        
        .notifikasi-item {
            padding: 15px;
            flex-direction: row;
            align-items: flex-start;
        }
        
        .notifikasi-icon {
            width: 45px;
            height: 45px;
            margin-right: 12px;
            font-size: 18px;
        }
        
        .notifikasi-content h4 {
            font-size: 15px;
        }
        
        .notifikasi-content p {
            font-size: 13px;
        }
        
        .notifikasi-actions {
            flex-direction: column;
            gap: 8px;
            align-items: flex-end;
        }
        
        .notifikasi-actions button {
            font-size: 12px;
            padding: 4px 8px;
        }
        
        .back-button {
            font-size: 14px;
            margin-bottom: 12px;
        }
        
        .pagination button {
            width: 35px;
            height: 35px;
        }
        
        .modal-content {
            max-width: 450px;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 15px 20px;
        }
    }

    /* Mobile (480px - 768px) */
    @media (max-width: 768px) {
        .filter-section {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        
        .filter-notifikasi {
            gap: 6px;
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 5px;
            -webkit-overflow-scrolling: touch;
        }
        
        .filter-notifikasi::-webkit-scrollbar {
            height: 4px;
        }
        
        .filter-notifikasi::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 2px;
        }
        
        .filter-notifikasi button {
            padding: 6px 12px;
            font-size: 12px;
            white-space: nowrap;
            flex-shrink: 0;
            min-height: 32px;
        }
        
        .notifikasi-header-section {
            padding: 12px 15px;
            border-radius: 8px 8px 0 0;
        }
        
        .notifikasi-header-section h2 {
            font-size: 16px;
        }
        
        .notifikasi-container {
            border-radius: 0 0 8px 8px;
        }
        
        .notifikasi-item {
            padding: 12px;
            flex-direction: column;
            align-items: stretch;
        }
        
        .notifikasi-item.unread {
            border-left: 3px solid var(--primary-color);
        }
        
        .notifikasi-icon {
            width: 40px;
            height: 40px;
            margin-right: 0;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .notifikasi-content {
            margin-bottom: 10px;
        }
        
        .notifikasi-content h4 {
            font-size: 14px;
            margin-bottom: 4px;
        }
        
        .notifikasi-content p {
            font-size: 12px;
            line-height: 1.4;
        }
        
        .notifikasi-time {
            font-size: 11px;
        }
        
        .notifikasi-actions {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #f1f5f9;
        }
        
        .notifikasi-actions button {
            font-size: 11px;
            padding: 4px 8px;
            min-height: 28px;
        }
        
        .back-button {
            font-size: 13px;
            margin-bottom: 10px;
            padding: 8px 0;
        }
        
        .pagination-container {
            margin-top: 20px;
        }
        
        .pagination {
            gap: 3px;
        }
        
        .pagination button {
            width: 32px;
            height: 32px;
            font-size: 12px;
            border-radius: 6px;
        }
        
        .modal-content {
            max-width: 90%;
            margin: 20px;
        }
        
        .modal-header {
            padding: 15px;
        }
        
        .modal-header h2 {
            font-size: 1.3rem;
        }
        
        .modal-body {
            padding: 15px;
        }
        
        .modal-body p {
            font-size: 14px;
        }
        
        .modal-body .notifikasi-detail {
            padding: 12px;
        }
        
        .modal-body .notifikasi-detail h4 {
            font-size: 14px;
        }
        
        .modal-body .notifikasi-detail p {
            font-size: 13px;
        }
        
        .modal-footer {
            padding: 12px 15px;
            flex-direction: column;
            gap: 8px;
        }
        
        .modal-footer button {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            min-height: 44px;
        }
    }

    /* Small Mobile (320px - 480px) */
    @media (max-width: 480px) {
        .filter-section {
            padding: 10px;
            margin-bottom: 12px;
        }
        
        .filter-notifikasi {
            gap: 4px;
        }
        
        .filter-notifikasi button {
            padding: 5px 10px;
            font-size: 11px;
            border-radius: 15px;
            min-height: 30px;
        }
        
        .notifikasi-header-section {
            padding: 10px 12px;
        }
        
        .notifikasi-header-section h2 {
            font-size: 15px;
        }
        
        .notifikasi-item {
            padding: 10px;
        }
        
        .notifikasi-icon {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }
        
        .notifikasi-content h4 {
            font-size: 13px;
        }
        
        .notifikasi-content p {
            font-size: 11px;
        }
        
        .notifikasi-time {
            font-size: 10px;
        }
        
        .notifikasi-actions {
            gap: 6px;
        }
        
        .notifikasi-actions button {
            font-size: 10px;
            padding: 3px 6px;
            min-height: 26px;
        }
        
        .back-button {
            font-size: 12px;
            gap: 6px;
        }
        
        .back-button i {
            font-size: 12px;
        }
        
        .pagination button {
            width: 30px;
            height: 30px;
            font-size: 11px;
        }
        
        .modal-content {
            max-width: 95%;
            margin: 10px;
        }
        
        .modal-header {
            padding: 12px;
        }
        
        .modal-header h2 {
            font-size: 1.2rem;
        }
        
        .modal-body {
            padding: 12px;
        }
        
        .modal-body p {
            font-size: 13px;
            margin-bottom: 12px;
        }
        
        .modal-body .notifikasi-detail {
            padding: 10px;
            margin: 15px 0;
        }
        
        .modal-body .notifikasi-detail h4 {
            font-size: 13px;
        }
        
        .modal-body .notifikasi-detail p {
            font-size: 12px;
        }
        
        .modal-footer {
            padding: 10px 12px;
            gap: 6px;
        }
        
        .modal-footer button {
            padding: 8px;
            font-size: 13px;
            min-height: 42px;
        }
    }

    /* Very Small Mobile (max-width: 320px) */
    @media (max-width: 320px) {
        .filter-notifikasi button {
            padding: 4px 8px;
            font-size: 10px;
        }
        
        .notifikasi-item {
            padding: 8px;
        }
        
        .notifikasi-icon {
            width: 32px;
            height: 32px;
            font-size: 13px;
        }
        
        .notifikasi-actions {
            flex-direction: column;
            gap: 5px;
        }
        
        .notifikasi-actions button {
            width: 100%;
            text-align: center;
        }
        
        .pagination button {
            width: 28px;
            height: 28px;
            font-size: 10px;
        }
        
        .modal-content {
            max-width: 98%;
            margin: 5px;
        }
    }

    /* Tambahan untuk landscape mode pada mobile */
    @media (max-height: 500px) and (max-width: 768px) {
        .modal-content {
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .notifikasi-container {
            max-height: 60vh;
            overflow-y: auto;
        }
    }

    /* Touch device improvements */
    @media (hover: none) and (pointer: coarse) {
        .filter-notifikasi button,
        .notifikasi-actions button,
        .pagination button,
        .modal-footer button {
            min-height: 44px;
        }
        
        .back-button {
            padding: 12px 0;
            min-height: 44px;
        }
        
        /* Improve touch targets */
        .notifikasi-item {
            cursor: pointer;
        }
        
        .filter-notifikasi button:active,
        .notifikasi-actions button:active,
        .pagination button:active {
            transform: scale(0.95);
            transition: transform 0.1s ease;
        }
    }

    /* High DPI screen optimizations */
    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
        .notifikasi-icon {
            font-size: 18px;
        }
        
        @media (max-width: 768px) {
            .notifikasi-icon {
                font-size: 16px;
            }
        }
        
        @media (max-width: 480px) {
            .notifikasi-icon {
                font-size: 14px;
            }
        }
    }

    /* Print styles */
    @media print {
        .filter-section,
        .back-button,
        .notifikasi-actions,
        .pagination-container,
        .modal-overlay {
            display: none !important;
        }
        
        .notifikasi-container {
            box-shadow: none;
            border: 1px solid #ccc;
        }
        
        .notifikasi-item {
            break-inside: avoid;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Variabel untuk menyimpan notifikasi yang akan dihapus
    let notifikasiToDelete = null;

    // Filter notifikasi
    document.querySelectorAll('.filter-notifikasi button').forEach(button => {
        button.addEventListener('click', function() {
            // Hapus class active dari semua button
            document.querySelectorAll('.filter-notifikasi button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Tambah class active ke button yang diklik
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            filterNotifikasi(filter);
        });
    });

    function filterNotifikasi(filter) {
        const notifikasiItems = document.querySelectorAll('.notifikasi-item');
        
        notifikasiItems.forEach(item => {
            if (filter === 'semua') {
                item.style.display = 'flex';
            } else if (filter === 'belum-dibaca') {
                if (item.classList.contains('unread')) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            } else {
                const itemType = item.getAttribute('data-type');
                if (itemType === filter) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            }
        });
    }

    // Tandai notifikasi sebagai sudah dibaca
    document.querySelectorAll('.btn-tandai').forEach(button => {
        button.addEventListener('click', function() {
            if (this.disabled) return;
            
            const notifikasiId = this.getAttribute('data-id');
            const notifikasiItem = this.closest('.notifikasi-item');
            
            // Kirim request AJAX untuk update status
            fetch(`/venue/notifikasi/${notifikasiId}/baca`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notifikasiItem.classList.remove('unread');
                    this.textContent = 'Sudah Dibaca';
                    this.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menandai notifikasi sebagai dibaca');
            });
        });
    });

    // Modal hapus notifikasi
    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function() {
            if (this.disabled) return;
            
            const notifikasiItem = this.closest('.notifikasi-item');
            const notifikasiId = this.getAttribute('data-id');
            const notifikasiTitle = this.getAttribute('data-title');
            const notifikasiType = notifikasiItem.getAttribute('data-type');
            const notifikasiTime = notifikasiItem.querySelector('.notifikasi-time').textContent;
            
            // Simpan referensi ke notifikasi yang akan dihapus
            notifikasiToDelete = {
                element: notifikasiItem,
                id: notifikasiId
            };
            
            // Isi data modal
            document.getElementById('modalNotifikasiId').textContent = 'N' + notifikasiId;
            document.getElementById('modalNotifikasiTitle').textContent = notifikasiTitle;
            document.getElementById('modalNotifikasiType').textContent = `Jenis: ${capitalizeFirstLetter(notifikasiType)}`;
            document.getElementById('modalNotifikasiTime').textContent = `Waktu: ${notifikasiTime}`;
            
            // Tampilkan modal
            document.getElementById('modalHapus').classList.add('active');
        });
    });

    // Fungsi helper untuk kapitalisasi huruf pertama
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // Tutup modal ketika tombol batal diklik
    document.getElementById('btnBatal').addEventListener('click', function() {
        document.getElementById('modalHapus').classList.remove('active');
        notifikasiToDelete = null;
    });

    // Hapus notifikasi ketika tombol hapus di modal diklik
    document.getElementById('btnHapusModal').addEventListener('click', function() {
        if (notifikasiToDelete) {
            const { element, id } = notifikasiToDelete;
            
            // Kirim request AJAX untuk menghapus
            fetch(`/venue/notifikasi/${id}/hapus`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    element.style.display = 'none';
                    document.getElementById('modalHapus').classList.remove('active');
                    notifikasiToDelete = null;
                    
                    // Tampilkan pesan sukses
                    alert('Notifikasi berhasil dihapus!');
                } else {
                    alert('Gagal menghapus notifikasi');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus notifikasi');
            });
        }
    });

    // Tutup modal ketika klik di luar modal
    document.getElementById('modalHapus').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
            notifikasiToDelete = null;
        }
    });

    // Optimasi untuk perangkat touch
    document.addEventListener('DOMContentLoaded', function() {
        // Improved touch handling untuk filter
        const filterButtons = document.querySelectorAll('.filter-notifikasi button');
        filterButtons.forEach(button => {
            button.addEventListener('touchstart', function(e) {
                this.style.transform = 'scale(0.95)';
            });
            
            button.addEventListener('touchend', function(e) {
                this.style.transform = 'scale(1)';
            });
        });

        // Improved touch handling untuk notifikasi items
        const notifikasiItems = document.querySelectorAll('.notifikasi-item');
        notifikasiItems.forEach(item => {
            let touchStartX = 0;
            let touchEndX = 0;
            
            item.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
                this.style.transition = 'none';
            });
            
            item.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                this.style.transition = 'all 0.3s ease';
                
                // Reset transform setelah touch
                this.style.transform = 'translateX(0)';
            });
        });

        // Prevent zoom on double tap untuk elemen interaktif
        const interactiveElements = document.querySelectorAll('button, .notifikasi-item, .back-button');
        interactiveElements.forEach(el => {
            el.addEventListener('touchend', function(e) {
                if (e.touches && e.touches.length > 1) {
                    e.preventDefault();
                }
            });
        });

        // Optimasi scroll untuk filter di mobile
        const filterContainer = document.querySelector('.filter-notifikasi');
        if (filterContainer && window.innerWidth <= 768) {
            filterContainer.addEventListener('wheel', function(e) {
                if (e.deltaY !== 0) {
                    e.preventDefault();
                    this.scrollLeft += e.deltaY;
                }
            });
        }

        // Improved modal handling untuk mobile
        const modal = document.getElementById('modalHapus');
        if (modal) {
            modal.addEventListener('touchmove', function(e) {
                e.preventDefault();
            }, { passive: false });
        }
    });

    // Handle orientation changes
    window.addEventListener('orientationchange', function() {
        // Tunggu sebentar untuk orientasi berubah
        setTimeout(() => {
            // Refresh filter position jika diperlukan
            const activeFilter = document.querySelector('.filter-notifikasi button.active');
            if (activeFilter && window.innerWidth <= 768) {
                activeFilter.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            }
        }, 300);
    });

    // Keyboard navigation support
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('modalHapus');
            if (modal && modal.classList.contains('active')) {
                modal.classList.remove('active');
                notifikasiToDelete = null;
            }
        }
    });
</script>
@endpush