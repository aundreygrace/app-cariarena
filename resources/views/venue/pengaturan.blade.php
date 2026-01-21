@extends('layouts.venue')

@section('title', 'Pengaturan - CariArena')
@section('page-title', 'Pengaturan')

@section('content')
    <!-- Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Horizontal Navigation -->
    <div class="horizontal-nav">
        <a href="#" class="{{ session('activeSection', 'profil-akun') == 'profil-akun' ? 'active' : '' }}" data-target="profil-akun">Profil Akun</a>
        <a href="#" class="{{ session('activeSection') == 'jadwal-slot' ? 'active' : '' }}" data-target="jadwal-slot">Jadwal & Slot Waktu</a>
        <a href="#" class="{{ session('activeSection') == 'notifikasi' ? 'active' : '' }}" data-target="notifikasi">Notifikasi</a>
        <a href="#" class="{{ session('activeSection') == 'keamanan' ? 'active' : '' }}" data-target="keamanan">Keamanan</a>
        <a href="#" class="{{ session('activeSection') == 'pusat-bantuan' ? 'active' : '' }}" data-target="pusat-bantuan">Pusat Bantuan</a>
    </div>

    <!-- Settings Content -->
    <div class="settings-content">
        <!-- Profil Akun Section -->
        <section id="profil-akun" class="settings-section {{ session('activeSection', 'profil-akun') == 'profil-akun' ? 'active' : '' }}">
            <h2>👤 Profil Akun</h2>
            
            <form method="POST" action="{{ route('venue.pengaturan.profile.update') }}" enctype="multipart/form-data" id="profileForm">
                @csrf
                
                <div class="form-group">
                    <label>Foto Profil</label>
                    <div class="photo-upload">
                        <div class="photo-preview" id="photoPreview">
                            @php
                                $user = auth()->user();
                                $photoUrl = null;
                                $hasPhoto = false;
                                
                                if ($user->profile_photo) {
                                    // Cek file di berbagai lokasi
                                    $publicPath = public_path('storage/profile-photos/' . $user->profile_photo);
                                    $storagePath = storage_path('app/public/profile-photos/' . $user->profile_photo);
                                    
                                    // Prioritaskan public path
                                    if (file_exists($publicPath)) {
                                        $photoUrl = asset('storage/profile-photos/' . $user->profile_photo);
                                        $hasPhoto = true;
                                    } 
                                    // Cek storage path
                                    elseif (file_exists($storagePath)) {
                                        $photoUrl = asset('storage/profile-photos/' . $user->profile_photo);
                                        $hasPhoto = true;
                                        // Coba buat symlink jika belum ada
                                        try {
                                            if (!file_exists(public_path('storage/profile-photos'))) {
                                                mkdir(public_path('storage/profile-photos'), 0755, true);
                                            }
                                            symlink($storagePath, $publicPath);
                                        } catch (\Exception $e) {
                                            // Fallback: gunakan route khusus
                                            $photoUrl = route('venue.profile.photo', ['filename' => $user->profile_photo]);
                                        }
                                    }
                                }
                                
                                // Tambahkan timestamp untuk force refresh jika baru diupdate
                                if(session('profile_photo_updated') && $hasPhoto) {
                                    $photoUrl .= '?t=' . time();
                                    session()->forget('profile_photo_updated');
                                }
                            @endphp
                            
                            @if($hasPhoto && $photoUrl)
                                <img src="{{ $photoUrl }}" 
                                     alt="Foto Profil" 
                                     id="currentProfilePhoto"
                                     class="profile-image"
                                     data-filename="{{ $user->profile_photo }}"
                                     onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}'; this.classList.add('default-avatar-img');">
                            @else
                                <div class="default-avatar">
                                    <i class="fas fa-user"></i>
                                    <span>Foto Profil</span>
                                </div>
                            @endif
                            <div class="photo-loading" id="photoLoading" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="upload-controls">
                            <input type="file" id="profile_photo" name="profile_photo" style="display: none;" 
                                   accept="image/*" onchange="handlePhotoUpload(this)">
                            <div class="upload-btn" onclick="document.getElementById('profile_photo').click()">
                                <i class="fas fa-upload"></i> Upload Foto Baru
                            </div>
                            @if($hasPhoto)
                            <div class="remove-btn" onclick="removeProfilePhoto()">
                                <i class="fas fa-trash"></i> Hapus Foto
                            </div>
                            @endif
                            <p class="upload-info">Format: JPG, PNG (Maks. 2MB)</p>
                            <input type="hidden" name="remove_photo" id="removePhoto" value="0">
                        </div>
                    </div>
                    @error('profile_photo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="nama-lengkap">Nama Lengkap *</label>
                    <input type="text" id="nama-lengkap" name="name" placeholder="Masukkan nama lengkap" 
                           value="{{ old('name', auth()->user()->name) }}" required
                           class="@error('name') is-invalid @enderror">
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan Email" 
                           value="{{ old('email', auth()->user()->email) }}" required
                           class="@error('email') is-invalid @enderror">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="telepon">Nomor Telepon</label>
                    <input type="tel" id="telepon" name="phone" placeholder="Masukkan nomor telepon" 
                           value="{{ old('phone', auth()->user()->phone) }}"
                           class="@error('phone') is-invalid @enderror">
                    @error('phone')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="nama-venue">Nama Venue</label>
                    <input type="text" id="nama-venue" name="venue_name" placeholder="Masukkan nama venue" 
                           value="{{ old('venue_name', auth()->user()->venue_name) }}"
                           class="@error('venue_name') is-invalid @enderror">
                    @error('venue_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Singkat</label>
                    <textarea id="deskripsi" name="description" placeholder="Masukkan deskripsi singkat"
                              class="@error('description') is-invalid @enderror">{{ old('description', auth()->user()->description) }}</textarea>
                    <div class="char-count">
                        <span id="charCount">0</span>/500 karakter
                    </div>
                    @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="action-buttons">
                    <button type="button" class="btn btn-outline" onclick="resetProfilForm()">Reset</button>
                    <button type="submit" class="btn btn-primary" id="simpanProfil">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>

        <!-- Jadwal & Slot Waktu Section -->
        <section id="jadwal-slot" class="settings-section {{ session('activeSection') == 'jadwal-slot' ? 'active' : '' }}">
            <h2>📅 Jadwal & Slot Waktu</h2>
            
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <p>Atur jadwal operasional venue Anda. Jadwal ini akan ditampilkan kepada pengguna yang ingin booking.</p>
            </div>
            
            <form method="POST" action="{{ route('venue.pengaturan.schedule.update') }}" id="scheduleForm">
                @csrf
                
                <div class="form-group">
                    <label for="hari">Hari Operasional *</label>
                    <select id="hari" name="day" class="@error('day') is-invalid @enderror">
                        <option value="">Pilih hari...</option>
                        <option value="setiap hari" {{ old('day', isset($schedule->day) && $schedule->day == 'setiap hari' ? 'selected' : '') }}>Setiap Hari</option>
                        <option value="senin" {{ old('day', isset($schedule->day) && $schedule->day == 'senin' ? 'selected' : '') }}>Senin</option>
                        <option value="selasa" {{ old('day', isset($schedule->day) && $schedule->day == 'selasa' ? 'selected' : '') }}>Selasa</option>
                        <option value="rabu" {{ old('day', isset($schedule->day) && $schedule->day == 'rabu' ? 'selected' : '') }}>Rabu</option>
                        <option value="kamis" {{ old('day', isset($schedule->day) && $schedule->day == 'kamis' ? 'selected' : '') }}>Kamis</option>
                        <option value="jumat" {{ old('day', isset($schedule->day) && $schedule->day == 'jumat' ? 'selected' : '') }}>Jumat</option>
                        <option value="sabtu" {{ old('day', isset($schedule->day) && $schedule->day == 'sabtu' ? 'selected' : '') }}>Sabtu</option>
                        <option value="minggu" {{ old('day', isset($schedule->day) && $schedule->day == 'minggu' ? 'selected' : '') }}>Minggu</option>
                    </select>
                    @error('day')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="time-group">
                    <div class="form-group">
                        <label for="jam-buka">Jam Buka *</label>
                        <input type="time" id="jam-buka" name="open_time" 
                               value="{{ old('open_time', isset($schedule->waktu_mulai) && !empty($schedule->waktu_mulai) ? substr($schedule->waktu_mulai, 0, 5) : '08:00') }}"
                               class="@error('open_time') is-invalid @enderror">
                        @error('open_time')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="jam-tutup">Jam Tutup *</label>
                        <input type="time" id="jam-tutup" name="close_time" 
                               value="{{ old('close_time', isset($schedule->waktu_selesai) && !empty($schedule->waktu_selesai) ? substr($schedule->waktu_selesai, 0, 5) : '22:00') }}"
                               class="@error('close_time') is-invalid @enderror">
                        @error('close_time')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="@error('status') is-invalid @enderror">
                        <option value="available" {{ old('status', isset($schedule->status) && $schedule->status == 'available' ? 'selected' : '') }}>Tersedia</option>
                        <option value="busy" {{ old('status', isset($schedule->status) && $schedule->status == 'busy' ? 'selected' : '') }}>Sibuk</option>
                        <option value="closed" {{ old('status', isset($schedule->status) && $schedule->status == 'closed' ? 'selected' : '') }}>Tutup</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="catatan">Catatan (Opsional)</label>
                    <textarea id="catatan" name="catatan" placeholder="Contoh: Libur nasional, event khusus, dll."
                              class="@error('catatan') is-invalid @enderror">{{ old('catatan', isset($schedule->catatan) ? $schedule->catatan : '') }}</textarea>
                    @error('catatan')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="current-schedule" style="display: {{ isset($schedule->waktu_mulai) ? 'block' : 'none' }};">
                    <h4>Jadwal Saat Ini:</h4>
                    <p>
                        <i class="fas fa-calendar me-2"></i>
                        {{ isset($schedule->day) ? ucfirst($schedule->day) : 'Setiap Hari' }}: 
                        {{ isset($schedule->waktu_mulai) ? substr($schedule->waktu_mulai, 0, 5) : '08:00' }} - 
                        {{ isset($schedule->waktu_selesai) ? substr($schedule->waktu_selesai, 0, 5) : '22:00' }}
                    </p>
                </div>
                
                <div class="action-buttons">
                    <button type="button" class="btn btn-outline" onclick="resetScheduleForm()">Reset</button>
                    <button type="submit" class="btn btn-primary" id="simpanJadwal">
                        <i class="fas fa-save me-2"></i>Simpan Jadwal
                    </button>
                </div>
            </form>
        </section>

        <!-- Notifikasi Section -->
        <section id="notifikasi" class="settings-section {{ session('activeSection') == 'notifikasi' ? 'active' : '' }}">
            <h2>🔔 Pengaturan Notifikasi</h2>
            
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <p>Atur preferensi notifikasi untuk tetap update dengan aktivitas venue Anda.</p>
            </div>
            
            <form method="POST" action="{{ route('venue.pengaturan.notifications.update') }}" id="notificationForm">
                @csrf
                
                <div class="notification-category">
                    <h3><i class="fas fa-envelope me-2"></i>Notifikasi Email</h3>
                    
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="email-booking" name="email_booking" 
                                   {{ old('email_booking', session('notification_settings.email_booking', true)) ? 'checked' : '' }}>
                            <label for="email-booking">
                                <span class="checkbox-label">Booking Baru</span>
                                <span class="checkbox-desc">Dapatkan notifikasi saat ada booking baru</span>
                            </label>
                        </div>
                        
                        <div class="checkbox-item">
                            <input type="checkbox" id="email-payment" name="email_payment" 
                                   {{ old('email_payment', session('notification_settings.email_payment', true)) ? 'checked' : '' }}>
                            <label for="email-payment">
                                <span class="checkbox-label">Pembayaran</span>
                                <span class="checkbox-desc">Notifikasi status pembayaran</span>
                            </label>
                        </div>
                        
                        <div class="checkbox-item">
                            <input type="checkbox" id="email-review" name="email_review" 
                                   {{ old('email_review', session('notification_settings.email_review', true)) ? 'checked' : '' }}>
                            <label for="email-review">
                                <span class="checkbox-label">Review & Rating</span>
                                <span class="checkbox-desc">Notifikasi saat ada review baru</span>
                            </label>
                        </div>
                        
                        <div class="checkbox-item">
                            <input type="checkbox" id="email-update" name="email_update" 
                                   {{ old('email_update', session('notification_settings.email_update', true)) ? 'checked' : '' }}>
                            <label for="email-update">
                                <span class="checkbox-label">Update Sistem</span>
                                <span class="checkbox-desc">Informasi update dan maintenance</span>
                            </label>
                        </div>
                        
                        <div class="checkbox-item">
                            <input type="checkbox" id="email-promo" name="email_promo" 
                                   {{ old('email_promo', session('notification_settings.email_promo', false)) ? 'checked' : '' }}>
                            <label for="email-promo">
                                <span class="checkbox-label">Promo & Penawaran</span>
                                <span class="checkbox-desc">Informasi promo dan penawaran khusus</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="notification-category">
                    <h3><i class="fas fa-bell me-2"></i>Notifikasi Browser</h3>
                    
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="browser-notifications" name="browser_notifications" 
                                   {{ old('browser_notifications', session('notification_settings.browser_notifications', false)) ? 'checked' : '' }}
                                   onchange="toggleBrowserNotifications(this)">
                            <label for="browser-notifications">
                                <span class="checkbox-label">Aktifkan Notifikasi Browser</span>
                                <span class="checkbox-desc">Dapatkan notifikasi real-time di browser</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="browser-permission" id="browserPermission" style="display: none;">
                        <p class="permission-info">
                            <i class="fas fa-exclamation-triangle"></i>
                            Anda perlu mengizinkan notifikasi browser untuk fitur ini
                        </p>
                        <button type="button" class="btn btn-sm" onclick="requestBrowserPermission()">
                            <i class="fas fa-check"></i> Izinkan Notifikasi
                        </button>
                    </div>
                </div>
                
                <div class="notification-frequency">
                    <h3><i class="fas fa-clock me-2"></i>Frekuensi Notifikasi</h3>
                    
                    <div class="radio-group">
                        <div class="radio-item">
                            <input type="radio" id="frequency-realtime" name="notification_frequency" value="realtime" 
                                   {{ old('notification_frequency', session('notification_settings.frequency', 'realtime')) == 'realtime' ? 'checked' : '' }}>
                            <label for="frequency-realtime">Real-time (Segera)</label>
                        </div>
                        
                        <div class="radio-item">
                            <input type="radio" id="frequency-daily" name="notification_frequency" value="daily" 
                                   {{ old('notification_frequency', session('notification_settings.frequency', 'realtime')) == 'daily' ? 'checked' : '' }}>
                            <label for="frequency-daily">Ringkasan Harian</label>
                        </div>
                        
                        <div class="radio-item">
                            <input type="radio" id="frequency-weekly" name="notification_frequency" value="weekly" 
                                   {{ old('notification_frequency', session('notification_settings.frequency', 'realtime')) == 'weekly' ? 'checked' : '' }}>
                            <label for="frequency-weekly">Ringkasan Mingguan</label>
                        </div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="simpanNotifikasi">
                        <i class="fas fa-save me-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </form>
        </section>

        <!-- Keamanan Section -->
        <section id="keamanan" class="settings-section {{ session('activeSection') == 'keamanan' ? 'active' : '' }}">
            <h2>🔒 Keamanan Akun</h2>
            
            <div class="info-box">
                <i class="fas fa-shield-alt"></i>
                <p>Kelola keamanan akun Anda. Pastikan password kuat dan aman.</p>
            </div>
            
            <form method="POST" action="{{ route('venue.pengaturan.security.update') }}" id="securityForm">
                @csrf
                
                <div class="password-change">
                    <h3><i class="fas fa-key me-2"></i>Ganti Password</h3>
                    
                    <div class="form-group">
                        <label for="security-current-password">Password Saat Ini *</label>
                        <div class="password-input">
                            <input type="password" id="security-current-password" name="current_password" 
                                   placeholder="Masukkan password saat ini"
                                   class="@error('current_password') is-invalid @enderror">
                            <button type="button" class="toggle-password" onclick="togglePassword('security-current-password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="security-new-password">Password Baru *</label>
                        <div class="password-input">
                            <input type="password" id="security-new-password" name="new_password" 
                                   placeholder="Masukkan password baru (min. 8 karakter)"
                                   class="@error('new_password') is-invalid @enderror"
                                   oninput="checkPasswordStrength(this.value)">
                            <button type="button" class="toggle-password" onclick="togglePassword('security-new-password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-requirements">
                            <p class="req-title">Password harus mengandung:</p>
                            <ul>
                                <li id="req-length"><i class="fas fa-circle"></i> Minimal 8 karakter</li>
                                <li id="req-uppercase"><i class="fas fa-circle"></i> Huruf besar</li>
                                <li id="req-lowercase"><i class="fas fa-circle"></i> Huruf kecil</li>
                                <li id="req-number"><i class="fas fa-circle"></i> Angka</li>
                                <li id="req-special"><i class="fas fa-circle"></i> Karakter khusus</li>
                            </ul>
                        </div>
                        @error('new_password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="security-confirm-password">Konfirmasi Password Baru *</label>
                        <div class="password-input">
                            <input type="password" id="security-confirm-password" name="new_password_confirmation" 
                                   placeholder="Konfirmasi password baru"
                                   class="@error('new_password_confirmation') is-invalid @enderror">
                            <button type="button" class="toggle-password" onclick="togglePassword('security-confirm-password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-match" id="passwordMatch">
                            <i class="fas fa-check"></i> Password cocok
                        </div>
                        @error('new_password_confirmation')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary" id="updatePassword">
                            <i class="fas fa-key me-2"></i>Perbarui Password
                        </button>
                    </div>
                </div>
                
                <div class="session-management">
                    <h3><i class="fas fa-laptop me-2"></i>Manajemen Sesi</h3>
                    <p class="session-info">Anda saat ini login dari perangkat ini.</p>
                    
                    <div class="current-session">
                        <div class="session-icon">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <div class="session-details">
                            <h4>Sesi Saat Ini</h4>
                            <p>
                                <i class="fas fa-globe"></i> {{ request()->ip() }}
                                <br>
                                <i class="fas fa-calendar"></i> {{ now()->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div class="session-status">
                            <span class="badge active">Aktif</span>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button type="button" class="btn btn-outline" onclick="logoutOtherSessions()">
                            <i class="fas fa-sign-out-alt me-2"></i>Keluar dari Semua Sesi Lain
                        </button>
                        <button type="button" class="btn btn-outline" onclick="showAllSessions()">
                            <i class="fas fa-list me-2"></i>Lihat Semua Sesi
                        </button>
                    </div>
                </div>
                
                <div class="two-factor" style="display: none;">
                    <h3><i class="fas fa-mobile-alt me-2"></i>Two-Factor Authentication (Coming Soon)</h3>
                    <div class="coming-soon">
                        <p>Tingkatkan keamanan dengan verifikasi dua langkah</p>
                    </div>
                </div>
            </form>
        </section>

        <!-- Pusat Bantuan Section -->
        <section id="pusat-bantuan" class="settings-section {{ session('activeSection') == 'pusat-bantuan' ? 'active' : '' }}">
            <h2>❓ Pusat Bantuan & FAQ</h2>
            
            <div class="help-search">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" class="search-box" placeholder="Cari pertanyaan atau masalah..." id="search-faq">
                </div>
                <p class="search-info">Ketik kata kunci untuk mencari solusi</p>
            </div>
            
            <div class="faq-categories">
                <div class="category-tabs">
                    <button class="category-tab active" data-category="all">Semua</button>
                    <button class="category-tab" data-category="venue">Venue</button>
                    <button class="category-tab" data-category="booking">Booking</button>
                    <button class="category-tab" data-category="payment">Pembayaran</button>
                    <button class="category-tab" data-category="account">Akun</button>
                </div>
            </div>
            
            <div class="faq-container" id="faqContainer">
                <div class="faq-category" data-category="venue">
                    <h3><i class="fas fa-store me-2"></i>Pengaturan Venue</h3>
                    
                    <div class="faq-list" id="faq-list">
                        <div class="faq-item" data-category="venue">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>1. Bagaimana cara mengubah nama venue?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Pergi ke menu <strong>Venue Saya</strong>, klik tombol <strong>Edit</strong> pada venue yang ingin diubah, lalu ubah nama sesuai keinginan. Pastikan nama venue mudah dikenali oleh pengguna.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item" data-category="venue">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>2. Bagaimana cara menonaktifkan venue?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Pergi ke menu <strong>Venue Saya</strong>, klik tombol <strong>Status</strong> pada venue yang ingin dinonaktifkan. Status akan otomatis berubah menjadi "Nonaktif". Venue yang nonaktif tidak akan muncul di pencarian pengguna.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item" data-category="venue">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>3. Bagaimana cara mengatur jam buka/tutup venue?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Pergi ke menu <strong>Pengaturan → Jadwal & Slot Waktu</strong>, atur jam buka dan jam tutup sesuai operasional venue Anda. Anda juga bisa mengatur hari operasional.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item" data-category="venue">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>4. Bagaimana cara menambahkan venue baru?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Pergi ke menu <strong>Venue Saya</strong>, klik tombol <strong>Tambah Venue</strong> di pojok kanan atas, atau dari dashboard klik <strong>Aksi Cepat → Tambah Venue</strong>. Isi data venue lengkap untuk pengalaman pengguna yang lebih baik.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="faq-category" data-category="booking">
                    <h3><i class="fas fa-calendar-check me-2"></i>Manajemen Booking</h3>
                    
                    <div class="faq-item" data-category="booking">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>5. Bagaimana cara menambahkan booking manual?</span>
                                <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Pergi ke menu <strong>Jadwal</strong>, klik tombol <strong>Tambah Booking</strong> pada jam yang tersedia, atau dari dashboard klik <strong>Aksi Cepat → Atur Jadwal</strong>. Isi data booking lengkap termasuk nama penyewa dan durasi.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item" data-category="booking">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>6. Bagaimana cara membatalkan booking?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Pergi ke menu <strong>Jadwal</strong>, cari booking yang ingin dibatalkan, klik tombol <strong>Titik Tiga (⋮)</strong> lalu pilih <strong>Batalkan Booking</strong>. Pastikan untuk menginformasikan kepada penyewa.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-category" data-category="payment">
                    <h3><i class="fas fa-credit-card me-2"></i>Pembayaran & Transaksi</h3>
                    
                    <div class="faq-item" data-category="payment">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>7. Kapan pembayaran booking masuk ke rekening?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Pembayaran akan diproses dalam 1-3 hari kerja setelah booking selesai. Sistem akan otomatis mentransfer ke rekening yang terdaftar di pengaturan pembayaran.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-category" data-category="account">
                    <h3><i class="fas fa-user-cog me-2"></i>Pengaturan Akun</h3>
                    
                    <div class="faq-item" data-category="account">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>8. Bagaimana cara mengganti foto profil?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Pergi ke menu <strong>Pengaturan → Profil Akun</strong>, klik area foto profil, pilih foto baru (format JPG/PNG, maksimal 2MB). Klik <strong>Simpan Perubahan</strong> untuk mengupdate.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="no-results" id="noResults" style="display: none;">
                <i class="fas fa-search"></i>
                <h4>Tidak ditemukan</h4>
                <p>Tidak ada hasil untuk pencarian Anda. Coba kata kunci lain atau hubungi support.</p>
            </div>
            
            <div class="contact-support">
                <h3><i class="fas fa-headset me-2"></i>Butuh Bantuan Lebih Lanjut?</h3>
                <div class="support-options">
                    <!-- Hanya Email dan WhatsApp saja -->
                    <div class="support-option">
                        <i class="fas fa-envelope"></i>
                        <h4>Email Support</h4>
                        <p>cariarena.app@gmail.com</p>
                        <a href="mailto:cariarena.app@gmail.com" class="btn btn-outline btn-sm">
                            <i class="fas fa-paper-plane me-1"></i>Kirim Email
                        </a>
                    </div>
                    
                    <div class="support-option whatsapp-option">
                        <i class="fab fa-whatsapp"></i>
                        <h4>WhatsApp Support</h4>
                        <p>08:00 - 22:00 WIB</p>
                        <a href="https://wa.me/6285731125834?text=Halo%20Admin%20CariArena,%20saya%20membutuhkan%20bantuan" 
                        target="_blank" class="btn btn-outline btn-sm">
                            <i class="fab fa-whatsapp me-1"></i>Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
<style>
    /* ==== VARIABLES ==== */
    :root {
        --primary-color: #3498db;
        --primary-hover: #2980b9;
        --primary-light: #ebf5fb;
        --secondary-color: #2c3e50;
        --success-color: #27ae60;
        --warning-color: #f39c12;
        --danger-color: #e74c3c;
        --text-dark: #2c3e50;
        --text-light: #7f8c8d;
        --border-color: #ecf0f1;
        --card-bg: #ffffff;
        --body-bg: #f8f9fa;
    }

    /* ==== HORIZONTAL NAVIGATION ==== */
    .horizontal-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0;
        margin-bottom: 30px;
        gap: 0;
        border-bottom: 2px solid var(--border-color);
        scrollbar-width: thin;
    }

    .horizontal-nav::-webkit-scrollbar {
        height: 4px;
    }

    .horizontal-nav::-webkit-scrollbar-thumb {
        background: #bdc3c7;
        border-radius: 2px;
    }

    .horizontal-nav a {
        color: var(--text-light);
        text-decoration: none;
        padding: 15px 25px;
        border-radius: 0;
        transition: all 0.3s ease;
        font-weight: 500;
        white-space: nowrap;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        flex-shrink: 0;
        font-size: 15px;
    }

    .horizontal-nav a:hover {
        color: var(--primary-color);
        background-color: var(--primary-light);
    }

    .horizontal-nav a.active {
        color: var(--primary-color);
        font-weight: 600;
        background-color: transparent;
        border-bottom: 3px solid var(--primary-color);
    }

    .horizontal-nav a i {
        margin-right: 8px;
        font-size: 16px;
    }

    /* ==== SETTINGS CONTENT ==== */
    .settings-content {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 30px;
    }

    .settings-content h2 {
        color: var(--secondary-color);
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
        font-size: 24px;
        font-weight: 600;
    }

    .settings-content h3 {
        color: var(--text-dark);
        margin: 25px 0 15px;
        font-size: 18px;
        font-weight: 600;
    }

    .settings-section {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .settings-section.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ==== INFO BOX ==== */
    .info-box {
        background-color: var(--primary-light);
        border-left: 4px solid var(--primary-color);
        padding: 15px;
        margin-bottom: 25px;
        border-radius: 0 8px 8px 0;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .info-box i {
        color: var(--primary-color);
        font-size: 18px;
        margin-top: 2px;
    }

    .info-box p {
        margin: 0;
        color: var(--text-dark);
        font-size: 14px;
        line-height: 1.5;
    }

    /* ==== FORM STYLES ==== */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--text-dark);
        font-size: 14px;
    }

    .form-group label.required::after {
        content: " *";
        color: var(--danger-color);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s;
        box-sizing: border-box;
        background-color: #fff;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }

    .form-group textarea {
        height: 120px;
        resize: vertical;
        min-height: 100px;
        line-height: 1.5;
    }

    /* ==== TIME GROUP ==== */
    .time-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    /* ==== PHOTO UPLOAD SECTION ==== */
    .photo-upload {
        display: flex;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 25px;
        margin-bottom: 25px;
    }

    .photo-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-light) 0%, #ffffff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 3px solid var(--border-color);
        position: relative;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .photo-preview:hover {
        border-color: var(--primary-color);
        transform: scale(1.02);
    }

    .photo-preview img.profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .photo-preview .default-avatar {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 10px;
    }

    .photo-preview .default-avatar i {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 8px;
    }

    .photo-preview .default-avatar span {
        font-size: 12px;
        color: var(--text-light);
        font-weight: 500;
    }

    .photo-loading {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .photo-loading i {
        font-size: 2rem;
        color: var(--primary-color);
    }

    .upload-controls {
        flex: 1;
        min-width: 250px;
    }

    .upload-btn,
    .remove-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s;
        font-size: 14px;
        margin-bottom: 10px;
        width: 100%;
        text-align: center;
    }

    .upload-btn {
        background: var(--primary-light);
        border: 2px dashed var(--primary-color);
        color: var(--primary-color);
    }

    .upload-btn:hover {
        background: var(--primary-color);
        color: white;
        border-style: solid;
    }

    .remove-btn {
        background: #fff5f5;
        border: 1px solid var(--danger-color);
        color: var(--danger-color);
    }

    .remove-btn:hover {
        background: var(--danger-color);
        color: white;
    }

    .upload-info {
        font-size: 13px;
        color: var(--text-light);
        margin-top: 5px;
        text-align: center;
    }

    /* ==== PASSWORD INPUT ==== */
    .password-input {
        position: relative;
    }

    .password-input input {
        padding-right: 45px;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-light);
        cursor: pointer;
        padding: 5px;
        font-size: 16px;
    }

    .toggle-password:hover {
        color: var(--primary-color);
    }

    /* ==== PASSWORD SECTION ==== */
    .password-section {
        background: var(--primary-light);
        padding: 20px;
        border-radius: 8px;
        margin: 25px 0;
    }

    .password-section h3 {
        margin-top: 0;
        color: var(--primary-color);
    }

    .section-info {
        color: var(--text-light);
        font-size: 14px;
        margin-bottom: 20px;
        font-style: italic;
    }

    /* ==== PASSWORD STRENGTH ==== */
    .password-strength {
        margin-top: 10px;
    }

    .strength-bar {
        height: 4px;
        background: #eee;
        border-radius: 2px;
        margin-bottom: 5px;
        overflow: hidden;
    }

    .strength-bar::after {
        content: '';
        display: block;
        height: 100%;
        width: 0%;
        background: var(--danger-color);
        transition: width 0.3s, background 0.3s;
    }

    .strength-text {
        font-size: 12px;
        color: var(--text-light);
    }

    /* ==== PASSWORD REQUIREMENTS ==== */
    .password-requirements {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
        border-left: 3px solid var(--primary-color);
    }

    .req-title {
        font-weight: 500;
        margin-bottom: 10px;
        color: var(--text-dark);
        font-size: 14px;
    }

    .password-requirements ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .password-requirements li {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 5px;
        font-size: 13px;
        color: var(--text-light);
    }

    .password-requirements li.valid {
        color: var(--success-color);
    }

    .password-requirements li.valid i {
        color: var(--success-color);
    }

    .password-requirements li i {
        font-size: 8px;
        color: #ddd;
    }

    .password-match {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--success-color);
        margin-top: 5px;
        display: none;
    }

    .password-match.show {
        display: flex;
    }

    /* ==== CHAR COUNT ==== */
    .char-count {
        text-align: right;
        font-size: 12px;
        color: var(--text-light);
        margin-top: 5px;
    }

    /* ==== CURRENT SCHEDULE ==== */
    .current-schedule {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
        border-left: 4px solid var(--success-color);
    }

    .current-schedule h4 {
        margin-top: 0;
        margin-bottom: 10px;
        color: var(--text-dark);
        font-size: 16px;
    }

    .current-schedule p {
        margin: 0;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ==== CHECKBOX & RADIO GROUPS ==== */
    .checkbox-group,
    .radio-group {
        margin-bottom: 20px;
    }

    .checkbox-item,
    .radio-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 8px;
        transition: background 0.3s;
    }

    .checkbox-item:hover,
    .radio-item:hover {
        background: #f8f9fa;
    }

    .checkbox-item input[type="checkbox"],
    .radio-item input[type="radio"] {
        width: 20px;
        height: 20px;
        margin-right: 12px;
        margin-top: 2px;
        accent-color: var(--primary-color);
        flex-shrink: 0;
    }

    .checkbox-item label,
    .radio-item label {
        flex: 1;
        margin-bottom: 0;
        font-weight: normal;
        cursor: pointer;
    }

    .checkbox-label {
        display: block;
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .checkbox-desc {
        display: block;
        font-size: 13px;
        color: var(--text-light);
        line-height: 1.4;
    }

    .notification-category {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }

    .notification-category:last-child {
        border-bottom: none;
    }

    /* ==== BROWSER PERMISSION ==== */
    .browser-permission {
        background: #fff8e1;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
        border-left: 4px solid var(--warning-color);
    }

    .permission-info {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        color: var(--text-dark);
        font-size: 14px;
    }

    .permission-info i {
        color: var(--warning-color);
    }

    /* ==== COMING SOON ==== */
    .coming-soon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        margin: 15px 0;
    }

    .coming-soon p {
        margin: 0;
        font-size: 14px;
    }

    /* ==== SESSION MANAGEMENT ==== */
    .session-management {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid var(--border-color);
    }

    .session-info {
        color: var(--text-light);
        margin-bottom: 20px;
        font-size: 14px;
    }

    .current-session {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid var(--border-color);
    }

    .session-icon {
        width: 50px;
        height: 50px;
        background: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .session-details {
        flex: 1;
    }

    .session-details h4 {
        margin: 0 0 5px 0;
        color: var(--text-dark);
        font-size: 16px;
    }

    .session-details p {
        margin: 0;
        color: var(--text-light);
        font-size: 13px;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .session-details i {
        margin-right: 5px;
        width: 15px;
        text-align: center;
    }

    .session-status .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .session-status .badge.active {
        background: #d4edda;
        color: #155724;
    }

    /* ==== FAQ STYLES ==== */
    .help-search {
        margin-bottom: 30px;
    }

    .search-container {
        position: relative;
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .search-container i {
        position: absolute;
        left: 15px;
        color: var(--text-light);
        font-size: 16px;
        z-index: 1;
    }

    .search-box {
        flex: 1;
        padding: 12px 15px 12px 45px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 15px;
    }

    .search-btn {
        padding: 12px 25px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 0 8px 8px 0;
        cursor: pointer;
        transition: background 0.3s;
    }

    .search-btn:hover {
        background: var(--primary-hover);
    }

    .search-info {
        color: var(--text-light);
        font-size: 13px;
        margin: 5px 0 0 0;
    }

    /* ==== FAQ CATEGORIES ==== */
    .faq-categories {
        margin-bottom: 25px;
    }

    .category-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .category-tab {
        padding: 8px 16px;
        background: var(--primary-light);
        border: 1px solid transparent;
        border-radius: 20px;
        color: var(--primary-color);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
    }

    .category-tab:hover {
        background: var(--primary-color);
        color: white;
    }

    .category-tab.active {
        background: var(--primary-color);
        color: white;
    }

    /* ==== FAQ CONTAINER ==== */
    .faq-category {
        margin-bottom: 30px;
    }

    .faq-category h3 {
        color: var(--secondary-color);
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border-color);
        margin-bottom: 20px;
    }

    .faq-item {
        margin-bottom: 10px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s;
    }

    .faq-item:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.1);
    }

    .faq-question {
        padding: 15px 20px;
        background: #f8f9fa;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 500;
        color: var(--text-dark);
        transition: background 0.3s;
    }

    .faq-question:hover {
        background: #f0f7ff;
    }

    .faq-question.active {
        background: #f0f7ff;
        color: var(--primary-color);
    }

    .faq-question i {
        transition: transform 0.3s;
    }

    .faq-question.active i {
        transform: rotate(180deg);
    }

    .faq-answer {
        padding: 0 20px;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s;
        background: white;
    }

    .faq-answer.show {
        padding: 20px;
        max-height: 500px;
    }

    .faq-answer p {
        margin: 0;
        color: var(--text-light);
        line-height: 1.6;
    }

    /* ==== NO RESULTS ==== */
    .no-results {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-light);
    }

    .no-results i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #ddd;
    }

    .no-results h4 {
        margin-bottom: 10px;
        color: var(--text-dark);
    }

    /* ==== CONTACT SUPPORT ==== */
    /* UKURAN DIPERKECIL DAN HANYA 2 KOLOM */
    .contact-support {
        margin-top: 30px;
        padding-top: 25px;
        border-top: 1px solid var(--border-color);
    }

    .support-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr); /* Hanya 2 kolom */
        gap: 15px; /* Gap diperkecil */
        margin-top: 15px;
        max-width: 1200; /* Lebar maksimum dibatasi */
        margin-left: auto;
        margin-right: auto;
    }

    .support-option {
        background: #f8f9fa;
        padding: 20px 15px; /* Padding diperkecil */
        border-radius: 8px;
        text-align: center;
        border: 1px solid var(--border-color);
        transition: all 0.3s;
    }

    .support-option:hover {
        transform: translateY(-3px); /* Efek hover lebih kecil */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border-color: var(--primary-color);
    }

    .support-option i {
        font-size: 1.8rem; /* Icon lebih kecil */
        color: var(--primary-color);
        margin-bottom: 10px;
    }

    .support-option h4 {
        margin: 8px 0 5px 0;
        color: var(--text-dark);
        font-size: 15px;
    }

    .support-option p {
        color: var(--text-light);
        margin-bottom: 12px;
        font-size: 13px;
        line-height: 1.4;
    }

    .support-option .btn {
        width: 100%;
        padding: 8px 12px; /* Button lebih kecil */
        font-size: 13px;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 13px;
    }

    /* ==== ACTION BUTTONS ==== */
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        margin-top: 30px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        border: none;
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        color: white;
    }

    .btn-outline {
        background: transparent;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
    }

    .btn-outline:hover {
        background: var(--primary-light);
        color: var(--primary-color);
    }

    /* ==== FORM ERROR STYLES ==== */
    .is-invalid {
        border-color: var(--danger-color) !important;
        background-color: #fff8f8;
    }

    .invalid-feedback {
        color: var(--danger-color);
        font-size: 13px;
        margin-top: 5px;
        display: block;
    }

    /* ==== ALERT MESSAGES ==== */
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert i {
        margin-right: 10px;
        font-size: 18px;
    }

    /* ========== RESPONSIVE STYLES ========== */

    /* Tablet Breakpoint */
    @media (max-width: 992px) {
        .settings-content {
            padding: 25px;
        }
        
        .horizontal-nav a {
            padding: 12px 20px;
            font-size: 14px;
        }
        
        .time-group {
            grid-template-columns: 1fr;
        }
        
        .support-options {
            grid-template-columns: repeat(2, 1fr); /* Tetap 2 kolom */
            gap: 12px;
        }
    }

    /* Mobile Breakpoint */
    @media (max-width: 768px) {
        .horizontal-nav {
            justify-content: flex-start;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
        
        .horizontal-nav a {
            padding: 10px 15px;
            font-size: 13px;
        }
        
        .settings-content {
            padding: 20px;
            border-radius: 8px;
        }
        
        .settings-content h2 {
            font-size: 22px;
            margin-bottom: 20px;
        }
        
        .photo-upload {
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        
        .photo-preview {
            width: 100px;
            height: 100px;
        }
        
        .upload-controls {
            width: 100%;
            text-align: center;
        }
        
        .support-options {
            grid-template-columns: 1fr; /* Di mobile jadi 1 kolom */
            max-width: 300px; /* Lebar maksimum lebih kecil */
        }
        
        .action-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .action-buttons .btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Small Mobile Breakpoint */
    @media (max-width: 480px) {
        .settings-content {
            padding: 15px;
        }
        
        .settings-content h2 {
            font-size: 20px;
            margin-bottom: 15px;
        }
        
        .settings-content h3 {
            font-size: 17px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px 12px;
            font-size: 14px;
        }
        
        .category-tabs {
            justify-content: center;
        }
        
        .category-tab {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        .faq-question {
            padding: 12px 15px;
            font-size: 14px;
        }
        
        .faq-answer.show {
            padding: 15px;
        }
        
        .btn {
            padding: 10px 15px;
            font-size: 14px;
        }
        
        .support-option {
            padding: 15px 12px; /* Lebih kecil di mobile */
        }
        
        .support-option i {
            font-size: 1.5rem;
        }
        
        .support-option h4 {
            font-size: 14px;
        }
        
        .support-option p {
            font-size: 12px;
        }
        
        .support-option .btn {
            padding: 7px 10px;
            font-size: 12px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Pengaturan venue scripts loaded');
        
        // ========== INITIALIZATION ==========
        initSettingsPage();
        
        // ========== NAVIGASI ANTAR SECTION ==========
        function initSettingsPage() {
            const menuLinks = document.querySelectorAll('.horizontal-nav a');
            const settingsSections = document.querySelectorAll('.settings-section');
            
            // Set active section from session or default
            const activeSection = '{{ session('activeSection', 'profil-akun') }}';
            
            menuLinks.forEach(link => {
                if (link.getAttribute('data-target') === activeSection) {
                    link.classList.add('active');
                }
            });
            
            settingsSections.forEach(section => {
                if (section.id === activeSection) {
                    section.classList.add('active');
                }
            });
            
            // Add click listeners
            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Update navigation
                    menuLinks.forEach(l => l.classList.remove('active'));
                    settingsSections.forEach(s => s.classList.remove('active'));
                    
                    this.classList.add('active');
                    const targetId = this.getAttribute('data-target');
                    const targetSection = document.getElementById(targetId);
                    
                    if (targetSection) {
                        targetSection.classList.add('active');
                        // Smooth scroll to top of section
                        window.scrollTo({
                            top: targetSection.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        }

        // ========== CHARACTER COUNT FOR DESCRIPTION ==========
        const descriptionTextarea = document.getElementById('deskripsi');
        if (descriptionTextarea) {
            const charCount = document.getElementById('charCount');
            
            descriptionTextarea.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = count;
                
                if (count > 500) {
                    charCount.style.color = 'var(--danger-color)';
                    this.classList.add('is-invalid');
                } else if (count > 450) {
                    charCount.style.color = 'var(--warning-color)';
                    this.classList.remove('is-invalid');
                } else {
                    charCount.style.color = 'var(--success-color)';
                    this.classList.remove('is-invalid');
                }
            });
            
            // Initial count
            charCount.textContent = descriptionTextarea.value.length;
        }

        // ========== PHOTO UPLOAD HANDLING ==========
        window.handlePhotoUpload = function(input) {
            const file = input.files[0];
            if (!file) return;
            
            // Validasi ukuran file (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                input.value = '';
                return;
            }
            
            // Validasi tipe file
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Format file harus JPG, PNG, atau GIF');
                input.value = '';
                return;
            }
            
            // Show loading
            const loadingElement = document.getElementById('photoLoading');
            const previewContainer = document.getElementById('photoPreview');
            
            if (loadingElement) loadingElement.style.display = 'flex';
            
            // Preview gambar
            const reader = new FileReader();
            reader.onload = function(e) {
                if (previewContainer) {
                    // Remove default avatar
                    const defaultAvatar = previewContainer.querySelector('.default-avatar');
                    if (defaultAvatar) defaultAvatar.remove();
                    
                    // Remove existing image
                    const existingImage = previewContainer.querySelector('img');
                    if (existingImage) existingImage.remove();
                    
                    // Add new image
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Preview Foto';
                    img.className = 'profile-image';
                    img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; border-radius: 50%;';
                    img.onerror = function() {
                        this.src = '{{ asset('images/default-avatar.png') }}';
                    };
                    
                    previewContainer.insertBefore(img, loadingElement);
                    
                    // Hide loading
                    if (loadingElement) loadingElement.style.display = 'none';
                    
                    // Store in localStorage for persistence during form submission
                    localStorage.setItem('profilePhotoPreview', e.target.result);
                    localStorage.setItem('profilePhotoUpdated', 'true');
                    
                    // Show remove button if not already shown
                    const removeBtn = document.querySelector('.remove-btn');
                    if (removeBtn && removeBtn.style.display === 'none') {
                        removeBtn.style.display = 'block';
                    }
                }
            };
            
            reader.onerror = function(error) {
                console.error('Error reading file:', error);
                alert('Gagal membaca file gambar');
                input.value = '';
                if (loadingElement) loadingElement.style.display = 'none';
            };
            
            reader.readAsDataURL(file);
        };

        // ========== REMOVE PROFILE PHOTO ==========
        window.removeProfilePhoto = function() {
            if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                const previewContainer = document.getElementById('photoPreview');
                const removePhotoInput = document.getElementById('removePhoto');
                
                // Set remove flag
                if (removePhotoInput) removePhotoInput.value = '1';
                
                // Update preview
                if (previewContainer) {
                    // Remove existing image
                    const existingImage = previewContainer.querySelector('img');
                    if (existingImage) existingImage.remove();
                    
                    // Add default avatar
                    const defaultAvatar = document.createElement('div');
                    defaultAvatar.className = 'default-avatar';
                    defaultAvatar.innerHTML = '<i class="fas fa-user"></i><span>Foto Profil</span>';
                    
                    previewContainer.appendChild(defaultAvatar);
                    
                    // Hide remove button
                    const removeBtn = document.querySelector('.remove-btn');
                    if (removeBtn) removeBtn.style.display = 'none';
                }
                
                // Clear localStorage
                localStorage.removeItem('profilePhotoPreview');
                localStorage.removeItem('profilePhotoUpdated');
            }
        };

        // ========== RESTORE PHOTO FROM LOCALSTORAGE ==========
        function restorePhotoPreview() {
            const preview = localStorage.getItem('profilePhotoPreview');
            const previewContainer = document.getElementById('photoPreview');
            
            if (preview && previewContainer && !previewContainer.querySelector('img')) {
                // Remove default avatar if exists
                const defaultAvatar = previewContainer.querySelector('.default-avatar');
                if (defaultAvatar) defaultAvatar.remove();
                
                // Add image from localStorage
                const img = document.createElement('img');
                img.src = preview;
                img.alt = 'Preview Foto';
                img.className = 'profile-image';
                img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; border-radius: 50%;';
                
                previewContainer.insertBefore(img, document.getElementById('photoLoading'));
                
                // Show remove button
                const removeBtn = document.querySelector('.remove-btn');
                if (removeBtn) removeBtn.style.display = 'block';
            }
        }
        
        // Restore on page load
        restorePhotoPreview();

        // ========== TOGGLE PASSWORD VISIBILITY ==========
        window.togglePassword = function(inputId) {
            const input = document.getElementById(inputId);
            const toggleBtn = input.nextElementSibling;
            const icon = toggleBtn.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        };

        // ========== PASSWORD STRENGTH CHECKER ==========
        window.checkPasswordStrength = function(password) {
            const strengthBar = document.querySelector('.strength-bar');
            const strengthText = document.querySelector('.strength-text');
            
            if (!strengthBar || !strengthText) return;
            
            let strength = 0;
            let color = '#e74c3c';
            let text = 'Lemah';
            
            // Check length
            if (password.length >= 8) strength += 20;
            if (password.length >= 12) strength += 10;
            
            // Check lowercase
            if (/[a-z]/.test(password)) strength += 20;
            
            // Check uppercase
            if (/[A-Z]/.test(password)) strength += 20;
            
            // Check numbers
            if (/[0-9]/.test(password)) strength += 20;
            
            // Check special characters
            if (/[^A-Za-z0-9]/.test(password)) strength += 20;
            
            // Cap at 100
            strength = Math.min(strength, 100);
            
            // Set color and text based on strength
            if (strength >= 80) {
                color = '#27ae60';
                text = 'Sangat Kuat';
            } else if (strength >= 60) {
                color = '#2ecc71';
                text = 'Kuat';
            } else if (strength >= 40) {
                color = '#f39c12';
                text = 'Cukup';
            } else if (strength >= 20) {
                color = '#e67e22';
                text = 'Lemah';
            } else {
                color = '#e74c3c';
                text = 'Sangat Lemah';
            }
            
            // Update UI
            if (strengthBar) {
                strengthBar.style.width = strength + '%';
                strengthBar.style.backgroundColor = color;
            }
            if (strengthText) {
                strengthText.textContent = 'Kekuatan password: ' + text;
                strengthText.style.color = color;
            }
            
            // Check password match
            checkPasswordMatch();
            
            // Update requirements
            updatePasswordRequirements(password);
        };

        // ========== PASSWORD MATCH CHECKER ==========
        function checkPasswordMatch() {
            const newPassword = document.getElementById('new_password')?.value || 
                               document.getElementById('security-new-password')?.value;
            const confirmPassword = document.getElementById('new_password_confirmation')?.value || 
                                   document.getElementById('security-confirm-password')?.value;
            
            const matchElement = document.getElementById('passwordMatch');
            
            if (!matchElement || !newPassword || !confirmPassword) return;
            
            if (newPassword === confirmPassword && newPassword.length > 0) {
                matchElement.classList.add('show');
            } else {
                matchElement.classList.remove('show');
            }
        }
        
        // Add event listeners for password match checking
        const passwordInputs = [
            'new_password', 'new_password_confirmation',
            'security-new-password', 'security-confirm-password'
        ];
        
        passwordInputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', checkPasswordMatch);
            }
        });

        // ========== PASSWORD REQUIREMENTS UPDATER ==========
        function updatePasswordRequirements(password) {
            const requirements = {
                'req-length': password.length >= 8,
                'req-lowercase': /[a-z]/.test(password),
                'req-uppercase': /[A-Z]/.test(password),
                'req-number': /[0-9]/.test(password),
                'req-special': /[^A-Za-z0-9]/.test(password)
            };
            
            for (const [id, isValid] of Object.entries(requirements)) {
                const element = document.getElementById(id);
                if (element) {
                    if (isValid) {
                        element.classList.add('valid');
                        const icon = element.querySelector('i');
                        if (icon) icon.style.color = 'var(--success-color)';
                    } else {
                        element.classList.remove('valid');
                        const icon = element.querySelector('i');
                        if (icon) icon.style.color = '#ddd';
                    }
                }
            }
        }

        // ========== RESET PROFILE FORM ==========
        window.resetProfilForm = function() {
            if (confirm('Reset semua perubahan? Data akan dikembalikan ke nilai semula.')) {
                const form = document.getElementById('profileForm');
                if (form) {
                    form.reset();
                    
                    // Reset photo preview
                    const previewContainer = document.getElementById('photoPreview');
                    if (previewContainer) {
                        previewContainer.innerHTML = `
                            <div class="default-avatar">
                                <i class="fas fa-user"></i>
                                <span>Foto Profil</span>
                            </div>
                            <div class="photo-loading" id="photoLoading" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        `;
                    }
                    
                    // Clear localStorage
                    localStorage.removeItem('profilePhotoPreview');
                    localStorage.removeItem('profilePhotoUpdated');
                    
                    // Hide remove button
                    const removeBtn = document.querySelector('.remove-btn');
                    if (removeBtn) removeBtn.style.display = 'none';
                    
                    // Reset remove photo flag
                    const removePhotoInput = document.getElementById('removePhoto');
                    if (removePhotoInput) removePhotoInput.value = '0';
                    
                    // Reset character count
                    const charCount = document.getElementById('charCount');
                    if (charCount) charCount.textContent = '0';
                }
            }
        };

        // ========== RESET SCHEDULE FORM ==========
        window.resetScheduleForm = function() {
            if (confirm('Reset jadwal ke nilai default?')) {
                const form = document.getElementById('scheduleForm');
                if (form) {
                    form.reset();
                    
                    // Set default values
                    const daySelect = document.getElementById('hari');
                    const openTime = document.getElementById('jam-buka');
                    const closeTime = document.getElementById('jam-tutup');
                    
                    if (daySelect) daySelect.value = 'setiap hari';
                    if (openTime) openTime.value = '08:00';
                    if (closeTime) closeTime.value = '22:00';
                }
            }
        };

        // ========== FORM SUBMISSION HANDLERS ==========
        const forms = ['profileForm', 'scheduleForm', 'notificationForm', 'securityForm'];
        
        forms.forEach(formId => {
            const form = document.getElementById(formId);
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    
                    if (submitBtn && !submitBtn.disabled) {
                        // Show loading state
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                        submitBtn.disabled = true;
                        
                        // Auto reset after 30 seconds
                        setTimeout(() => {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }, 30000);
                    }
                });
            }
        });

        // ========== FAQ SEARCH FUNCTIONALITY ==========
        window.searchFAQ = function() {
            const searchTerm = document.getElementById('search-faq').value.toLowerCase().trim();
            const faqItems = document.querySelectorAll('.faq-item');
            const noResults = document.getElementById('noResults');
            let found = false;
            
            if (searchTerm === '') {
                // Reset search - show all items
                faqItems.forEach(item => {
                    item.style.display = 'block';
                });
                if (noResults) noResults.style.display = 'none';
                return;
            }
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question span')?.textContent.toLowerCase() || '';
                const answer = item.querySelector('.faq-answer p')?.textContent.toLowerCase() || '';
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                    found = true;
                    
                    // Auto expand matching items
                    const questionElement = item.querySelector('.faq-question');
                    if (questionElement && !questionElement.classList.contains('active')) {
                        toggleFAQ(questionElement);
                    }
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            if (noResults) {
                noResults.style.display = found ? 'none' : 'block';
            }
        };
        
        // Add search functionality
        const searchInput = document.getElementById('search-faq');
        if (searchInput) {
            // Search on enter
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    searchFAQ();
                }
            });
            
            // Live search with debounce
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(searchFAQ, 300);
            });
        }

        // ========== FAQ TOGGLE FUNCTION ==========
        window.toggleFAQ = function(element) {
            const answer = element.nextElementSibling;
            const icon = element.querySelector('i');
            
            if (!answer || !icon) return;
            
            if (answer.classList.contains('show')) {
                answer.classList.remove('show');
                element.classList.remove('active');
                icon.style.transform = 'rotate(0deg)';
            } else {
                // Close other open FAQs
                document.querySelectorAll('.faq-answer.show').forEach(openAnswer => {
                    openAnswer.classList.remove('show');
                    const prevElement = openAnswer.previousElementSibling;
                    if (prevElement && prevElement.classList.contains('faq-question')) {
                        prevElement.classList.remove('active');
                        const prevIcon = prevElement.querySelector('i');
                        if (prevIcon) prevIcon.style.transform = 'rotate(0deg)';
                    }
                });
                
                // Open clicked FAQ
                answer.classList.add('show');
                element.classList.add('active');
                icon.style.transform = 'rotate(180deg)';
                
                // Smooth scroll to FAQ
                setTimeout(() => {
                    element.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 300);
            }
        };

        // ========== FAQ CATEGORY FILTER ==========
        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Update active tab
                document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const category = this.getAttribute('data-category');
                const faqItems = document.querySelectorAll('.faq-item');
                const faqCategories = document.querySelectorAll('.faq-category');
                
                if (category === 'all') {
                    // Show all
                    faqItems.forEach(item => item.style.display = 'block');
                    faqCategories.forEach(cat => cat.style.display = 'block');
                } else {
                    // Filter by category
                    faqItems.forEach(item => {
                        if (item.getAttribute('data-category') === category) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    
                    // Show/hide category headers
                    faqCategories.forEach(cat => {
                        if (cat.getAttribute('data-category') === category) {
                            cat.style.display = 'block';
                        } else {
                            cat.style.display = 'none';
                        }
                    });
                }
                
                // Reset search
                if (searchInput) {
                    searchInput.value = '';
                    searchFAQ(); // Reset search results
                }
                
                // Hide no results
                const noResults = document.getElementById('noResults');
                if (noResults) noResults.style.display = 'none';
            });
        });

        // ========== BROWSER NOTIFICATIONS ==========
        window.toggleBrowserNotifications = function(checkbox) {
            const permissionDiv = document.getElementById('browserPermission');
            if (permissionDiv) {
                permissionDiv.style.display = checkbox.checked ? 'block' : 'none';
            }
        };

        window.requestBrowserPermission = function() {
            if ('Notification' in window) {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        alert('Notifikasi browser diizinkan!');
                        const browserPermissionDiv = document.getElementById('browserPermission');
                        if (browserPermissionDiv) {
                            browserPermissionDiv.innerHTML = `
                                <p class="permission-info" style="color: var(--success-color);">
                                    <i class="fas fa-check-circle"></i>
                                    Notifikasi browser telah diizinkan
                                </p>
                            `;
                        }
                    } else {
                        alert('Anda tidak mengizinkan notifikasi browser.');
                        const browserCheckbox = document.getElementById('browser-notifications');
                        if (browserCheckbox) browserCheckbox.checked = false;
                    }
                });
            } else {
                alert('Browser Anda tidak mendukung notifikasi.');
                const browserCheckbox = document.getElementById('browser-notifications');
                if (browserCheckbox) browserCheckbox.checked = false;
            }
        };

        // ========== LOGOUT OTHER SESSIONS ==========
        window.logoutOtherSessions = function() {
            const currentPassword = prompt('Masukkan password Anda untuk mengkonfirmasi logout dari semua sesi lain:');
            
            if (!currentPassword) {
                return;
            }
            
            if (confirm('Apakah Anda yakin ingin logout dari semua sesi lain? Anda akan tetap login di perangkat ini.')) {
                fetch("{{ route('venue.pengaturan.logout-other-sessions') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        current_password: currentPassword
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Berhasil logout dari semua sesi lain');
                    } else {
                        alert(data.message || 'Gagal logout dari sesi lain');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat logout');
                });
            }
        };

        // ========== SHOW ALL SESSIONS ==========
        window.showAllSessions = function() {
            alert('Fitur ini akan segera hadir!');
        };

        // ========== OPEN WHATSAPP SUPPORT ==========
        window.openWhatsAppSupport = function(adminNumber = null) {
            // PERUBAHAN: WhatsApp number yang benar
            let phoneNumber = adminNumber || '6285731125834';
            const message = encodeURIComponent('Halo Admin CariArena, saya butuh bantuan terkait: ');
            const whatsappUrl = `https://wa.me/${phoneNumber}?text=${message}`;
            
            window.open(whatsappUrl, '_blank');
        };

        // ========== AUTO-HIDE ALERTS ==========
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);

        // ========== ADD EVENT LISTENERS FOR WHATSAPP BUTTONS ==========
        // Add click events for WhatsApp buttons
        document.addEventListener('click', function(e) {
            // Check if clicked element is a WhatsApp button
            if (e.target.closest('.whatsapp-btn') || 
                e.target.closest('.support-option.whatsapp-option .btn')) {
                
                // If it's already a link with href, let it work normally
                if (e.target.tagName === 'A' || e.target.closest('a')) {
                    return;
                }
                
                // Otherwise open WhatsApp
                const button = e.target.closest('button');
                if (button && button.getAttribute('onclick') === 'openWhatsAppSupport()') {
                    e.preventDefault();
                    window.openWhatsAppSupport();
                }
            }
        });

        console.log('All settings scripts initialized successfully');
    });
</script>
@endpush