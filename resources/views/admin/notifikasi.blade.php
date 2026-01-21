@extends('layouts.admin')

@section('title', 'Notifikasi - Admin')

@section('page-title', 'Notifikasi - Admin')

@section('content')
<a href="{{ route('admin.dashboard') }}" class="back-button">
    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
</a>

<div class="filter-section">
    <div class="filter-notifikasi">
        <button class="active" data-filter="semua">Semua</button>
        <button data-filter="belum-dibaca">Belum Dibaca</button>
        <button data-filter="pembayaran">Pembayaran</button>
        <button data-filter="ulasan">Ulasan</button>
        <button data-filter="booking">Booking</button>
        <button data-filter="venue">Venue</button>
        <button data-filter="user">User</button>
        <button data-filter="sistem">Sistem</button>
    </div>
</div>

<!-- Bagian header notifikasi yang dipisahkan -->
<div class="notifikasi-header-section">
    <h2>Semua Notifikasi</h2>
    <div class="header-actions">
        <button class="btn-tandai-semua" id="markAllRead">
            <i class="fas fa-check-double"></i> Tandai Semua Sudah Dibaca
        </button>
        <button class="btn-hapus-semua" id="deleteAll">
            <i class="fas fa-trash"></i> Hapus Semua
        </button>
    </div>
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
                @case('venue')
                    <i class="fas fa-store"></i>
                    @break
                @case('user')
                    <i class="fas fa-user-plus"></i>
                    @break
                @default
                    <i class="fas fa-cog"></i>
            @endswitch
        </div>
        <div class="notifikasi-content">
            <h4>{{ $notification->title ?? 'Notifikasi Sistem' }}</h4>
            <p>{{ $notification->message ?? 'Tidak ada pesan' }}</p>
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
        --primary-color: #63B3ED;
            --primary-hover: #90CDF4;
            --primary-light: #EBF8FF;
            --text-dark: #1A202C;
            --text-light: #718096;
            --bg-light: #EDF2F7;
            --card-bg: #FFFFFF;
            --success: #48BB78;
            --warning: #ECC94B;
            --danger: #F56565;
            --sidebar-bg: #FFFFFF;
            --btn-blue: #4299E1;
            --btn-green: #48BB78;
            --btn-yellow: #ED8936;
    }

    /* Style untuk memisahkan bagian "Semua Notifikasi" dari isi notifikasi */
    .notifikasi-header-section {
        background: var(--card-bg);
        border-radius: 14px 14px 0 0;
        padding: 20px;
        margin-bottom: 0;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .notifikasi-header-section h2 {
        color: var(--primary-color);
        margin: 0;
        font-size: 20px;
        font-weight: 600;
    }
    
    .header-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-tandai-semua, .btn-hapus-semua {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-tandai-semua {
        background: var(--primary-color);
        color: white;
    }
    
    .btn-tandai-semua:hover {
        background: var(--primary-hover);
    }
    
    .btn-hapus-semua {
        background: var(--danger);
        color: white;
    }
    
    .btn-hapus-semua:hover {
        background: #c53030;
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

    .notifikasi-icon.venue {
        background: #E9D8FD;
        color: #9F7AEA;
    }

    .notifikasi-icon.user {
        background: #FED7D7;
        color: var(--danger);
    }

    .notifikasi-icon.sistem {
        background: #CBD5E0;
        color: var(--text-dark);
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
        padding: 10px 0;
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .notifikasi-header-section {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        
        .header-actions {
            width: 100%;
            justify-content: space-between;
        }
        
        .btn-tandai-semua, .btn-hapus-semua {
            flex: 1;
            justify-content: center;
        }
        
        .filter-notifikasi {
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 5px;
        }
        
        .filter-notifikasi button {
            white-space: nowrap;
            flex-shrink: 0;
        }
        
        .notifikasi-item {
            flex-direction: column;
        }
        
        .notifikasi-icon {
            margin-right: 0;
            margin-bottom: 10px;
        }
        
        .notifikasi-actions {
            justify-content: space-between;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #f1f5f9;
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
            fetch(`{{ url("/admin/notifikasi") }}/${notifikasiId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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

    // Tandai semua sebagai sudah dibaca
    document.getElementById('markAllRead').addEventListener('click', function() {
        if (confirm('Tandai semua notifikasi sebagai sudah dibaca?')) {
            fetch('{{ url("/admin/notifikasi/mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menandai semua notifikasi');
            });
        }
    });

    // Hapus semua notifikasi
    document.getElementById('deleteAll').addEventListener('click', function() {
        if (confirm('Hapus semua notifikasi? Tindakan ini tidak dapat dibatalkan.')) {
            fetch('{{ url("/admin/notifikasi/delete-all") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus semua notifikasi');
            });
        }
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
            fetch(`{{ url("/admin/notifikasi") }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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