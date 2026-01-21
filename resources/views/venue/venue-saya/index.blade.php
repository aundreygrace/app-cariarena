@extends('layouts.venue')

@section('title', 'Venue Saya')

@section('page-title', 'Venue Saya')

@section('content')
<!-- Bagian Kelola Venue dengan teks dan tombol -->
<div class="kelola-venue-container">
    <h2 class="kelola-venue-text">Kelola semua venue yang anda miliki</h2>
    <button type="button" class="btn-tambah-venue" data-bs-toggle="modal" data-bs-target="#tambahVenueModal">
        <i class="fas fa-plus"></i> Tambah Venue
    </button>
</div>

<div class="venue-container">
    <div class="venue-grid" id="venueGrid">
        @forelse($venues as $venue)
            <div class="venue-item">
                <div class="venue-card card position-relative" data-venue-id="{{ $venue->id }}">
                    <img src="{{ $venue->photo ? (Str::contains($venue->photo, ['http', 'drive.google.com']) ? $venue->photo : asset('storage/' . $venue->photo)) : 'https://source.unsplash.com/400x200/?sports' }}" alt="{{ $venue->name }}">
                    <span class="badge-status 
                        @if($venue->status === 'Aktif') badge-aktif
                        @elseif($venue->status === 'Maintenance') badge-maintenance
                        @else badge-nonaktif
                        @endif">
                        {{ $venue->status }}
                    </span>
                    
                    <!-- Tombol Hapus di Sudut Kiri Atas - Dengan Popup Konfirmasi -->
                     <button class="delete-btn top-right-delete" onclick="showDeleteConfirmation({{ $venue->id }}, '{{ $venue->name }}', '{{ route('venue.hapus', $venue->id) }}')">
                      <i class="fas fa-trash"></i>
                    </button>
                    
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $venue->name }}</h5>
                        <div class="rating">
                            @php
                                $rating = $venue->rating ?? 0;
                                $reviewCount = $venue->reviews_count ?? 0;
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rating)
                                    <i class="fas fa-star"></i>
                                @elseif($i == floor($rating) + 1 && ($rating - floor($rating)) >= 0.5)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                            <span class="rating-count">{{ number_format($rating, 1) }} ({{ $reviewCount }})</span>
                        </div>
                        <span class="badge">{{ $venue->category }}</span>
                        
                        <div class="location-price">
                            <p class="text-muted mb-1"><i class="fas fa-map-marker-alt"></i> {{ $venue->address }}</p>
                            <p class="price"><i class="fas fa-clock"></i> Rp {{ number_format($venue->price_per_hour, 0, ',', '.') }}/jam</p>
                        </div>
                        
                        <div class="facility-grid">
    @if(!empty($venue->facilities) && is_array($venue->facilities) && count($venue->facilities) > 0)
        @foreach(array_slice($venue->facilities, 0, 4) as $facility)
            <span>{{ $facility }}</span>
        @endforeach
        {{-- Hanya tambahkan span kosong jika kurang dari 4 --}}
        @if(count($venue->facilities) < 4)
            @for($i = count($venue->facilities); $i < 4; $i++)
                <span class="empty-facility"></span>
            @endfor
        @endif
    @else
        <span>-</span>
        <span class="empty-facility"></span>
        <span class="empty-facility"></span>
        <span class="empty-facility"></span>
    @endif
</div>
                        
                        <!-- ACTION BUTTONS - ROUTE NAMES YANG BENAR -->
                        <div class="action-buttons">
                            <!-- Tombol Lihat - ROUTE NAME YANG BENAR -->
                            <a href="{{ route('venue.detail', $venue->id) }}" class="btn">
                                <i class="fas fa-eye btn-icon"></i> Lihat
                            </a>
                            
                            <!-- Tombol Edit -->
                            <button class="btn" data-bs-toggle="modal" data-bs-target="#editVenueModal" onclick="setEditModalData({{ $venue->id }})"">
                                <i class="fas fa-edit btn-icon"></i> Edit
                            </button>
                            
                            <!-- Tombol Toggle Status - ROUTE NAME YANG BENAR -->
                            <form action="{{ route('venue.toggle-status', $venue->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn">
                                    <i class="fas fa-power-off btn-icon"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="fas fa-info-circle"></i> Belum ada venue yang ditambahkan.
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah Venue - ROUTE NAME YANG BENAR -->
<div class="modal fade" id="tambahVenueModal" tabindex="-1" aria-labelledby="tambahVenueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="tambahVenueModalLabel">Tambah Venue Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('venue.tambah') }}" method="POST" enctype="multipart/form-data" id="tambahVenueForm">
                @csrf
                <div class="modal-body">
                    <p class="text-muted">Isi informasi venue baru Anda</p>
                    
                    <div class="row">
                        <!-- Kolom Kiri: Informasi Dasar -->
                        <div class="col-md-6">
                            <div class="form-section">
                                <h6 class="form-section-title">Informasi Dasar</h6>
                                
                                <div class="mb-3">
                                    <label for="newVenueName" class="form-label required">Nama Venue</label>
                                    <input type="text" class="form-control" id="newVenueName" name="name" placeholder="Masukkan nama venue" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="newVenueCategory" class="form-label required">Kategori</label>
                                    <select class="form-select" id="newVenueCategory" name="category" required>
                                        <option value="" selected disabled>Pilih kategori</option>
                                        <option value="Futsal">Futsal</option>
                                        <option value="Badminton">Badminton</option>
                                        <option value="Basket">Basket</option>
                                        <option value="Soccer">Soccer</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="newVenueLocation" class="form-label required">Lokasi</label>
                                    <input type="text" class="form-control" id="newVenueLocation" name="address" placeholder="Masukkan lokasi venue" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="newVenuePrice" class="form-label required">Harga per Jam</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" id="newVenuePrice" name="price_per_hour" placeholder="150000" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Fasilitas</label>
                                    <div class="facilities-grid">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="newFacilityParking" name="newFacilityParking">
                                            <label class="form-check-label" for="newFacilityParking">Tempat Parkir</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="newFacilityToilet" name="newFacilityToilet">
                                            <label class="form-check-label" for="newFacilityToilet">Toilet</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="newFacilityKantin" name="newFacilityKantin">
                                            <label class="form-check-label" for="newFacilityKantin">Kantin</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="newFacilityAC" name="newFacilityAC">
                                            <label class="form-check-label" for="newFacilityAC">AC</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="newFacilityMusholla" name="newFacilityMusholla">
                                            <label class="form-check-label" for="newFacilityMusholla">Musholla</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="newFacilityRuangGanti" name="newFacilityRuangGanti">
                                            <label class="form-check-label" for="newFacilityRuangGanti">Ruang Ganti</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="newFacilityRuangTunggu" name="newFacilityRuangTunggu">
                                            <label class="form-check-label" for="newFacilityRuangTunggu">Ruang Tunggu / Tribun</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="newFacilitySoundSystem" name="newFacilitySoundSystem">
                                            <label class="form-check-label" for="newFacilitySoundSystem">Sound System</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kolom Kanan: Status & Gambar -->
                        <div class="col-md-6">
                            <div class="form-section">
                                <h6 class="form-section-title">Status & Gambar</h6>
                                
                                <div class="mb-3">
                                    <label for="newVenueStatus" class="form-label required">Status Venue</label>
                                    <select class="form-select" id="newVenueStatus" name="status" required>
                                        <option value="" selected disabled>Pilih status</option>
                                        <option value="Aktif">Aktif</option>
                                        <option value="Maintenance">Maintenance</option>
                                        <option value="Tidak Aktif">Tidak Aktif</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Gambar Venue</label>
                                    <div class="file-upload-container">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>Unggah gambar venue (opsional)</p>
                                        <div class="file-input-wrapper">
                                            <button type="button" class="file-input-label">
                                                <i class="fas fa-upload me-2"></i> Choose File
                                            </button>
                                            <input type="file" id="newVenueImage" name="photo" accept="image/*">
                                        </div>
                                        <small class="text-muted" id="file-name">No file chosen</small>
                                    </div>
                                </div>
                                
                                
                                
                                <div class="mb-3">
                                    <label for="newVenueDescription" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="newVenueDescription" name="description" rows="3" placeholder="Deskripsi venue"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="simpanVenueBtn">Simpan Venue</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Venue Modal -->
<div class="modal fade" id="editVenueModal" tabindex="-1" aria-labelledby="editVenueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVenueModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Venue
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editVenueForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <p class="text-muted mb-3">Edit informasi venue Anda</p>
                        <hr class="mb-3">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-section">
                                <span class="section-title">Informasi Dasar</span>
                                <div class="mb-3">
                                    <label for="venueName" class="form-label">Nama Venue</label>
                                    <input type="text" class="form-control" id="venueName" name="name">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="venueCategory" class="form-label">Kategori</label>
                                    <select class="form-select" id="venueCategory" name="category">
                                        <option value="Futsal">Futsal</option>
                                        <option value="Badminton">Badminton</option>
                                        <option value="Basket">Basket</option>
                                        <option value="Soccer">Soccer</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="venueLocation" class="form-label">Lokasi</label>
                                    <input type="text" class="form-control" id="venueLocation" name="address">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="venuePrice" class="form-label">Harga per Jam</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" id="venuePrice" name="price_per_hour">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-section">
                                <span class="section-title">Status & Gambar</span>
                                
                                <div class="mb-3">
                                    <label for="venueStatus" class="form-label">Status Venue</label>
                                    <select class="form-select" id="venueStatus" name="status">
                                        <option value="Aktif">Aktif</option>
                                        <option value="Maintenance">Maintenance</option>
                                        <option value="Tidak Aktif">Tidak Aktif</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="venueImage" class="form-label">Gambar Venue</label>
                                    <input class="form-control" type="file" id="venueImage" name="photo">
                                    <div class="form-text">Unggah gambar baru untuk mengganti gambar saat ini</div>
                                </div>
                                
                                
                                <div class="mb-3">
                                    <label for="venueDescription" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="venueDescription" name="description" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section mt-3">
                        <span class="section-title">Fasilitas</span>
                        <div class="checkbox-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="facilityParking" name="facilityParking" value="1">
                                <label class="form-check-label" for="facilityParking">
                                    <i class="fas fa-parking me-1"></i> Tempat Parkir
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="facilityToilet" name="facilityToilet" value="1">
                                <label class="form-check-label" for="facilityToilet">
                                    <i class="fas fa-restroom me-1"></i> Toilet
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="facilityKantin" name="facilityKantin" value="1">
                                <label class="form-check-label" for="facilityKantin">
                                    <i class="fas fa-utensils me-1"></i> Kantin
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="facilityAC" name="facilityAC" value="1">
                                <label class="form-check-label" for="facilityAC">
                                    <i class="fas fa-snowflake me-1"></i> AC
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="facilityMusholla" name="facilityMusholla" value="1">
                                <label class="form-check-label" for="facilityMusholla">
                                    <i class="fas fa-mosque me-1"></i> Musholla
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="facilityRuangGanti" name="facilityRuangGanti" value="1">
                                <label class="form-check-label" for="facilityRuangGanti">
                                    <i class="fas fa-tshirt me-1"></i> Ruang Ganti
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="facilityRuangTunggu" name="facilityRuangTunggu" value="1">
                                <label class="form-check-label" for="facilityRuangTunggu">
                                    <i class="fas fa-couch me-1"></i> Ruang Tunggu / Tribun
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="facilitySoundSystem" name="facilitySoundSystem" value="1">
                                <label class="form-check-label" for="facilitySoundSystem">
                                    <i class="fas fa-volume-up me-1"></i> Sound System
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                </div>
                <h6 class="fw-bold mb-2">Hapus Venue?</h6>
                <p class="text-muted" id="deleteVenueMessage">
                    Apakah Anda yakin ingin menghapus venue <span class="fw-bold" id="venueNameToDelete"></span>?
                </p>
                <p class="text-danger small">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    Tindakan ini tidak dapat dibatalkan. Semua data venue akan dihapus secara permanen.
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Batal
                </button>
                <form id="deleteVenueForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-1"></i> Ya, Hapus Venue
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ==== VARIABEL CSS ==== */
    :root {
        --primary-color: #63b3ed;
        --primary-hover: #4299e1;
        --primary-light: #ebf8ff;
        --text-dark: #2d3748;
        --text-light: #718096;
        --success: #48bb78;
        --warning: #ed8936;
        --danger: #f56565;
    }
    
    /* ==== STYLE TOMBOL TAMBAH VENUE ==== */
    .btn-tambah-venue {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 1rem;
        color: white;
        box-shadow: 0 4px 12px rgba(99, 179, 237, 0.3);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-tambah-venue:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(99, 179, 237, 0.4);
        background: linear-gradient(135deg, var(--primary-hover) 0%, var(--primary-color) 100%);
        color: white;
    }

    .btn-tambah-venue:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(99, 179, 237, 0.3);
    }

    /* ==== STYLE UNTUK TEKS KELOLA VENUE ==== */
    .kelola-venue-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .kelola-venue-text {
        color: var(--text-light);
        font-size: 1.1rem;
        font-weight: 400;
        margin: 0;
    }

    /* ==== MODAL TAMBAH VENUE ==== */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background-color: var(--primary-light);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px 12px 0 0;
        padding: 1.2rem 1.5rem;
    }

    .modal-title {
        color: var(--text-dark);
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

    .file-input-label:hover {
        background-color: #f8f9fa;
    }

    /* ==== VENUE CARD STYLES - IMPROVED ==== */
    .venue-card {
        border-radius: 12px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        border: none;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        min-height: 480px;
    }
    
    /* Efek Hover Card */
    .venue-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }
    
    /* EFEK KILAT (SHIMMER) PADA CARD */
    .venue-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.4),
            transparent
        );
        transition: left 0.7s;
        z-index: 1;
    }
    
    .venue-card:hover::before {
        left: 100%;
    }
    
    .venue-card img {
        height: 180px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.5s ease;
    }
    
    .venue-card:hover img {
        transform: scale(1.05);
    }
    
    .badge-status {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 20px;
        color: #fff;
        z-index: 2;
    }
    
    .badge-aktif { 
        background: var(--success); 
    }
    
    .badge-maintenance { 
        background: var(--warning); 
    }
    
    .badge-nonaktif { 
        background: var(--danger); 
    }
    
    /* ==== CARD BODY LAYOUT ==== */
    .venue-card .card-body {
        padding: 1.25rem 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        text-align: left;
    }
    
    .venue-card .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
        text-align: left;
    }
    
    .rating {
        color: #FFC107;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        text-align: left;
    }
    
    .rating-count {
        color: var(--text-light);
        font-size: 0.8rem;
    }
    
    /* ==== PERBAIKAN STYLE BADGE ==== */
    .venue-card .badge {
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        background-color: transparent;
        color: var(--text-dark);
        font-weight: 600;
        padding: 0;
        text-transform: none;
        letter-spacing: normal;
        text-align: left;
        display: block;
        width: 100%;
    }
    
    .venue-card .location-price {
        margin-bottom: 0.75rem;
        text-align: left;
    }
    
    .venue-card .location-price p {
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
        text-align: left;
    }
    
    .venue-card .location-price .price {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1rem;
    }
    
    /* ==== FACILITY GRID ==== */
    .facility-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-bottom: 1rem;
        text-align: left;
    }
    
    .facility-grid span {
        display: block;
        background: #e9ecef;
        padding: 6px 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        text-align: center;
        transition: all 0.2s ease;
    }
    
    .venue-card:hover .facility-grid span {
        background: #dee2e6;
        transform: translateY(-2px);
    }

    .facility-grid span.empty-facility {
        background: transparent !important;
    }
    
    /* ==== ACTION BUTTONS STYLES ==== */
    .action-buttons {
        display: flex;
        gap: 0;
        width: 100%;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        margin-top: auto;
        text-align: left;
    }
    
    .action-buttons .btn {
        flex: 1;
        font-size: 0.9rem;
        padding: 10px 12px;
        text-align: center;
        border-radius: 0;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: white;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    
    .action-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .action-buttons .btn:not(:last-child) {
        border-right: 1px solid #dee2e6;
    }
    
    /* Hover effects for each button */
    .action-buttons .btn:nth-child(1):hover {
        background: var(--primary-light);
        color: var(--primary-color);
    }
    
    .action-buttons .btn:nth-child(2):hover {
        background: #e8f5e8;
        color: var(--success);
    }
    
    .action-buttons .btn:nth-child(3):hover {
        background: #ffeaea;
        color: var(--danger);
    }
    
    /* Status button styles */
    .action-buttons .btn-status.aktif {
        color: var(--success);
    }
    
    .action-buttons .btn-status.maintenance {
        color: var(--warning);
    }
    
    .action-buttons .btn-status.nonaktif {
        color: var(--danger);
    }
    
    .action-buttons .btn-status:hover {
        transform: translateY(-2px);
    }
    
    .btn-icon {
        font-size: 1rem;
    }

    /* ==== CARD HEIGHT CONSISTENCY ==== */
    .venue-container {
        margin-bottom: 2rem;
    }
    
    .venue-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;
    }
    
    .venue-item {
        display: flex;
        flex-direction: column;
    }

    /* ==== TOMBOL HAPUS STYLES ==== */
    .delete-btn {
        background-color: var(--danger);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .delete-btn:hover {
        background-color: #e53e3e;
        transform: translateY(-2px);
    }
    
    /* Opsi 1: Tombol hapus di sudut kiri atas */
    .top-right-delete {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 3;
    }
    
    /* Opsi 3: Dropdown menu */
    .dropdown-toggle::after {
        display: none;
    }
    
    .dropdown-menu {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: none;
        padding: 0.5rem;
    }
    
    .dropdown-item {
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .dropdown-item:hover {
        background-color: #f7fafc;
    }
    
    .dropdown-item.text-danger:hover {
        background-color: #fed7d7;
    }

    /* ==== STYLE MODAL KONFIRMASI HAPUS ==== */
    #deleteConfirmationModal .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    #deleteConfirmationModal .modal-header {
        background-color: #fff5f5;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px 12px 0 0;
        padding: 1.2rem 1.5rem;
    }

    #deleteConfirmationModal .modal-title {
        color: var(--text-dark);
        font-weight: 700;
        font-size: 1.25rem;
    }

    #deleteConfirmationModal .modal-body {
        padding: 1.5rem;
    }

    #deleteConfirmationModal .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem 1.5rem;
    }

    /* ==== STYLE NOTIFIKASI SUKSES ==== */
    .alert-success {
        background-color: #d1f2eb;
        border-color: #a3e4d7;
        color: #0d6d5e;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .position-fixed {
        position: fixed;
    }

    /* ==== RESPONSIVE STYLES ==== */

    /* Tablet Styles (768px - 1024px) */
    @media (max-width: 1024px) and (min-width: 768px) {
        .kelola-venue-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .kelola-venue-text {
            font-size: 1rem;
        }
        
        .venue-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }
        
        .venue-card {
            min-height: 460px;
        }
        
        .facility-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            padding: 8px 10px;
            font-size: 0.85rem;
        }
        
        .action-buttons .btn:not(:last-child) {
            border-right: none;
            border-bottom: 1px solid #dee2e6;
        }
        
        .modal-dialog.modal-lg {
            max-width: 90%;
            margin: 1.75rem auto;
        }
        
        .facilities-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Mobile Styles (max-width: 767px) */
    @media (max-width: 767px) {
        .kelola-venue-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        
        .kelola-venue-text {
            font-size: 1rem;
            text-align: left;
        }
        
        .btn-tambah-venue {
            width: 100%;
            justify-content: center;
            padding: 10px 16px;
            font-size: 0.95rem;
        }
        
        .venue-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .venue-card {
            min-height: auto;
        }
        
        .venue-card .card-body {
            padding: 1rem;
        }
        
        .venue-card .card-title {
            font-size: 1.1rem;
        }
        
        .facility-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            padding: 8px 10px;
            font-size: 0.85rem;
            justify-content: flex-start;
        }
        
        .action-buttons .btn:not(:last-child) {
            border-right: none;
            border-bottom: 1px solid #dee2e6;
        }
        
        .modal-dialog.modal-lg {
            max-width: 95%;
            margin: 0.5rem auto;
        }
        
        .modal-header {
            padding: 1rem;
        }
        
        .modal-body {
            padding: 1rem;
        }
        
        .modal-footer {
            padding: 0.75rem 1rem;
        }
        
        .modal-body .row {
            flex-direction: column;
        }
        
        .modal-body .col-md-6 {
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .facilities-grid {
            grid-template-columns: 1fr;
        }
        
        .file-upload-container {
            padding: 1rem;
        }
        
        .file-upload-container i {
            font-size: 1.5rem;
        }
        
        .badge-status {
            font-size: 10px;
            padding: 4px 8px;
        }
        
        .delete-btn {
            padding: 4px 8px;
            font-size: 0.7rem;
        }
    }

    /* Small Mobile Styles (max-width: 480px) */
    @media (max-width: 480px) {
        .venue-card .card-body {
            padding: 0.75rem;
        }
        
        .venue-card .card-title {
            font-size: 1rem;
        }
        
        .rating {
            font-size: 0.8rem;
        }
        
        .venue-card .location-price p {
            font-size: 0.8rem;
        }
        
        .venue-card .location-price .price {
            font-size: 0.9rem;
        }
        
        .facility-grid span {
            font-size: 0.7rem;
            padding: 4px 6px;
        }
        
        .action-buttons .btn {
            font-size: 0.8rem;
            padding: 6px 8px;
        }
        
        .btn-icon {
            font-size: 0.9rem;
        }
        
        .modal-dialog.modal-lg {
            max-width: 98%;
        }
    }

    /* Large Desktop Styles (min-width: 1200px) */
    @media (min-width: 1200px) {
        .venue-grid {
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        }
    }
</style>
@endpush

@push('scripts')
<script>
   <!-- JavaScript untuk Edit Modal -->
function setEditModalData(venueId) {
    fetch(`/venue/venue/${venueId}/edit`)
        .then(response => response.json())
        .then(venue => {
            // Set form action
            document.getElementById('editVenueForm').action = `/venue/venue/${venueId}`;
                
                // Isi form dengan data venue
                document.getElementById('venueName').value = venue.name || '';
                document.getElementById('venueCategory').value = venue.category || '';
                document.getElementById('venueLocation').value = venue.address || '';
                document.getElementById('venuePrice').value = venue.price_per_hour || '';
                document.getElementById('venueStatus').value = venue.status || '';
                document.getElementById('venueImageLink').value = venue.photo_link || '';
                document.getElementById('venueDescription').value = venue.description || '';
                
                // Set fasilitas yang sudah ada
                if (venue.facilities && Array.isArray(venue.facilities)) {
                    const facilities = venue.facilities;
                    document.getElementById('facilityParking').checked = facilities.includes('Parkir');
                    document.getElementById('facilityToilet').checked = facilities.includes('Toilet');
                    document.getElementById('facilityKantin').checked = facilities.includes('Kantin');
                    document.getElementById('facilityAC').checked = facilities.includes('AC');
                    document.getElementById('facilityMusholla').checked = facilities.includes('Musholla');
                    document.getElementById('facilityRuangGanti').checked = facilities.includes('Ruang Ganti');
                    document.getElementById('facilityRuangTunggu').checked = facilities.includes('Ruang Tunggu/Tribun');
                    document.getElementById('facilitySoundSystem').checked = facilities.includes('Sound System');
                }
            })
            .catch(error => {
                console.error('Error fetching venue data:', error);
                alert('Gagal memuat data venue');
            });
    }

    // Script untuk menampilkan nama file yang dipilih
    document.getElementById('newVenueImage').addEventListener('change', function(e) {
        const fileName = e.target.files.length > 0 ? e.target.files[0].name : 'No file chosen';
        document.getElementById('file-name').textContent = fileName;
    });

    // Script untuk validasi form tambah venue
    document.getElementById('tambahVenueForm').addEventListener('submit', function(e) {
        const venueName = document.getElementById('newVenueName').value;
        const venueCategory = document.getElementById('newVenueCategory').value;
        const venueLocation = document.getElementById('newVenueLocation').value;
        const venuePrice = document.getElementById('newVenuePrice').value;
        
        if (!venueName || !venueCategory || !venueLocation || !venuePrice) {
            e.preventDefault();
            alert('Harap isi semua field yang wajib diisi!');
            return;
        }
    });

    // Format harga input
    document.getElementById('newVenuePrice').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d]/g, '');
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
        }
        e.target.value = value;
    });

    document.getElementById('venuePrice').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d]/g, '');
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
        }
        e.target.value = value;
    });

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Tambahkan event listener untuk file input di modal edit
        document.getElementById('venueImage')?.addEventListener('change', function(e) {
            const fileName = e.target.files.length > 0 ? e.target.files[0].name : 'No file chosen';
            // Anda bisa menampilkan nama file di modal edit jika diperlukan
        });
    });

    // Variabel untuk menyimpan data venue yang akan dihapus
let venueToDelete = {
    id: null,
    name: null,
    url: null
};

// Fungsi untuk menampilkan modal konfirmasi hapus
function showDeleteConfirmation(venueId, venueName, deleteUrl) {
    // Simpan data venue yang akan dihapus
    venueToDelete = {
        id: venueId,
        name: venueName,
        url: deleteUrl
    };
    
    // Update teks di modal
    document.getElementById('venueNameToDelete').textContent = venueName;
    document.getElementById('deleteVenueForm').action = deleteUrl;
    
    // Tampilkan modal konfirmasi hapus
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    deleteModal.show();
}

// Fungsi untuk menghandle submit form hapus
document.addEventListener('DOMContentLoaded', function() {
    const deleteForm = document.getElementById('deleteVenueForm');
    
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            // Optional: Tampilkan loading state
            const submitBtn = document.getElementById('confirmDeleteBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menghapus...';
            submitBtn.disabled = true;
        });
    }
});

// Fungsi untuk menampilkan notifikasi sukses (opsional)
function showSuccessNotification(message) {
    // Buat elemen notifikasi
    const notification = document.createElement('div');
    notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Tambahkan ke body
    document.body.appendChild(notification);
    
    // Hapus notifikasi setelah 3 detik
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Reset form ketika modal ditutup
document.getElementById('deleteConfirmationModal').addEventListener('hidden.bs.modal', function () {
    venueToDelete = {
        id: null,
        name: null,
        url: null
    };
    
    // Reset button state
    const submitBtn = document.getElementById('confirmDeleteBtn');
    submitBtn.innerHTML = '<i class="fas fa-trash me-1"></i> Ya, Hapus Venue';
    submitBtn.disabled = false;
});
</script>
@endpush