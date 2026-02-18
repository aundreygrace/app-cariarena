

<?php $__env->startSection('title', 'Pengaturan - CariArena'); ?>
<?php $__env->startSection('page-title', 'Pengaturan'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Notifikasi -->
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>
    
    <?php if($errors->any()): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Horizontal Navigation -->
    <div class="horizontal-nav">
        <a href="#" class="<?php echo e(session('activeSection', 'profil-akun') == 'profil-akun' ? 'active' : ''); ?>" data-target="profil-akun">Profil Akun</a>
        <a href="#" class="<?php echo e(session('activeSection') == 'pengaturan-sistem' ? 'active' : ''); ?>" data-target="pengaturan-sistem">Pengaturan Sistem</a>
        <a href="#" class="<?php echo e(session('activeSection') == 'manajemen-admin' ? 'active' : ''); ?>" data-target="manajemen-admin">Manajemen Admin</a>
        <a href="#" class="<?php echo e(session('activeSection') == 'pengaturan-notifikasi' ? 'active' : ''); ?>" data-target="pengaturan-notifikasi">Notifikasi</a>
        <a href="#" class="<?php echo e(session('activeSection') == 'keamanan-backup' ? 'active' : ''); ?>" data-target="keamanan-backup">Keamanan & Backup</a>
        <a href="#" class="<?php echo e(session('activeSection') == 'pusat-bantuan' ? 'active' : ''); ?>" data-target="pusat-bantuan">Pusat Bantuan</a>
    </div>

    <!-- Settings Content -->
    <div class="settings-content">
        <!-- Profil Akun Section -->
        <section id="profil-akun" class="settings-section <?php echo e(session('activeSection', 'profil-akun') == 'profil-akun' ? 'active' : ''); ?>">
            <h2>👤 Profil Akun</h2>
            
            <form method="POST" action="<?php echo e(route('admin.pengaturan.profile.update')); ?>" enctype="multipart/form-data" id="profileForm">
                <?php echo csrf_field(); ?>
                
                <div class="form-group">
                    <label>Foto Profil</label>
                    <div class="photo-upload">
                        <div class="photo-preview" id="photoPreview">
                            <?php
                                $user = auth()->user();
                                $photoUrl = null;
                                $hasPhoto = false;
                                
                                if ($user->profile_photo) {
                                    // Cek file di storage
                                    $storagePath = storage_path('app/public/profile-photos/' . $user->profile_photo);
                                    
                                    if (file_exists($storagePath)) {
                                        $photoUrl = route('admin.pengaturan.profile.photo', ['filename' => $user->profile_photo]);
                                        $hasPhoto = true;
                                        
                                        // Tambahkan timestamp cache buster jika baru diupdate
                                        if(session('profile_photo_updated')) {
                                            $photoUrl .= '?t=' . time();
                                            session()->forget('profile_photo_updated');
                                        }
                                    }
                                }
                            ?>
                            
                            <?php if($hasPhoto && $photoUrl): ?>
                                <img src="<?php echo e($photoUrl); ?>" 
                                     alt="Foto Profil" 
                                     id="currentProfilePhoto"
                                     class="profile-image"
                                     data-filename="<?php echo e($user->profile_photo); ?>"
                                     onerror="this.onerror=null; this.src='<?php echo e(asset('images/default-avatar.png')); ?>'; this.classList.add('default-avatar-img');"
                                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            <?php else: ?>
                                <div class="default-avatar">
                                    <i class="fas fa-user"></i>
                                    <span>Foto Profil</span>
                                </div>
                            <?php endif; ?>
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
                            <?php if($hasPhoto): ?>
                            <div class="remove-btn" onclick="removeProfilePhoto()">
                                <i class="fas fa-trash"></i> Hapus Foto
                            </div>
                            <?php endif; ?>
                            <p class="upload-info">Format: JPG, PNG (Maks. 2MB)</p>
                            <input type="hidden" name="remove_photo" id="removePhoto" value="0">
                        </div>
                    </div>
                    <?php $__errorArgs = ['profile_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="nama-lengkap">Nama Lengkap *</label>
                    <input type="text" id="nama-lengkap" name="name" placeholder="Masukkan nama lengkap" 
                           value="<?php echo e(old('name', auth()->user()->name)); ?>" required
                           class="<?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan Email" 
                           value="<?php echo e(old('email', auth()->user()->email)); ?>" required
                           class="<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="telepon">Nomor Telepon</label>
                    <input type="tel" id="telepon" name="phone" placeholder="Masukkan nomor telepon" 
                           value="<?php echo e(old('phone', auth()->user()->phone)); ?>"
                           class="<?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="role">Role</label>
                    <input type="text" id="role" value="<?php echo e(ucfirst(auth()->user()->roles->first()->name ?? 'Admin')); ?>" readonly disabled>
                    <small class="text-muted">Role tidak dapat diubah</small>
                </div>
                
                <div class="password-section">
                    <h3><i class="fas fa-key me-2"></i>Ganti Password</h3>
                    <p class="section-info">Kosongkan jika tidak ingin mengganti password</p>
                    
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini</label>
                        <div class="password-input">
                            <input type="password" id="current_password" name="current_password" 
                                   placeholder="Masukkan password saat ini">
                            <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <div class="password-input">
                            <input type="password" id="new_password" name="new_password" 
                                   placeholder="Masukkan password baru">
                            <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="passwordStrength">
                            <span class="strength-text">Kekuatan password</span>
                        </div>
                        <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                        <div class="password-input">
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" 
                                   placeholder="Konfirmasi password baru">
                            <button type="button" class="toggle-password" onclick="togglePassword('new_password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <button type="button" class="btn btn-outline" onclick="resetProfilForm()">Reset</button>
                    <button type="submit" class="btn btn-primary" id="simpanProfil">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>

        <!-- Pengaturan Sistem Section -->
        <section id="pengaturan-sistem" class="settings-section <?php echo e(session('activeSection') == 'pengaturan-sistem' ? 'active' : ''); ?>">
            <h2>⚙️ Pengaturan Sistem</h2>
            
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <p>Atur pengaturan sistem aplikasi CariArena.</p>
            </div>
            
            <form method="POST" action="<?php echo e(route('admin.pengaturan.system.update')); ?>" id="systemForm">
                <?php echo csrf_field(); ?>
                
                <div class="form-group">
                    <label>Logo Aplikasi</label>
                    <div class="photo-upload">
                        <div class="photo-preview" id="logoPreview">
                            <?php
                                // Simulasi logo
                                $logoUrl = asset('images/logo.png');
                                $hasLogo = file_exists(public_path('images/logo.png'));
                            ?>
                            
                            <?php if($hasLogo): ?>
                                <img src="<?php echo e($logoUrl); ?>" 
                                     alt="Logo Aplikasi" 
                                     id="currentLogo"
                                     class="profile-image"
                                     onerror="this.onerror=null; this.src='<?php echo e(asset('images/default-logo.png')); ?>';"
                                     style="width: 100%; height: 100%; object-fit: contain;">
                            <?php else: ?>
                                <div class="default-avatar">
                                    <i class="fas fa-flag"></i>
                                    <span>Logo App</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="upload-controls">
                            <input type="file" id="logo" name="logo" style="display: none;" 
                                   accept="image/*" onchange="handleLogoUpload(this)">
                            <div class="upload-btn" onclick="document.getElementById('logo').click()">
                                <i class="fas fa-upload"></i> Upload Logo Baru
                            </div>
                            <?php if($hasLogo): ?>
                            <div class="remove-btn" onclick="removeLogo()">
                                <i class="fas fa-trash"></i> Hapus Logo
                            </div>
                            <?php endif; ?>
                            <p class="upload-info">Format: JPG, PNG, SVG (Maks. 2MB)</p>
                            <input type="hidden" name="remove_logo" id="removeLogoInput" value="0">
                        </div>
                    </div>
                    <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="nama-aplikasi">Nama Aplikasi *</label>
                    <input type="text" id="nama-aplikasi" name="app_name" placeholder="Masukkan nama aplikasi" 
                           value="<?php echo e(old('app_name', config('app.name', 'CariArena'))); ?>" required
                           class="<?php $__errorArgs = ['app_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['app_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="email-aplikasi">Email Aplikasi *</label>
                    <input type="email" id="email-aplikasi" name="app_email" placeholder="Masukkan email aplikasi" 
                           value="<?php echo e(old('app_email', config('mail.from.address', 'noreply@cariarena.com'))); ?>" required
                           class="<?php $__errorArgs = ['app_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['app_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="bahasa">Bahasa Default</label>
                    <select id="bahasa" name="language" class="<?php $__errorArgs = ['language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="id" <?php echo e(old('language', config('app.locale', 'id')) == 'id' ? 'selected' : ''); ?>>Bahasa Indonesia</option>
                        <option value="en" <?php echo e(old('language', config('app.locale', 'id')) == 'en' ? 'selected' : ''); ?>>English</option>
                    </select>
                    <?php $__errorArgs = ['language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="zona-waktu">Zona Waktu</label>
                    <select id="zona-waktu" name="timezone" class="<?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="Asia/Jakarta" <?php echo e(old('timezone', config('app.timezone', 'Asia/Jakarta')) == 'Asia/Jakarta' ? 'selected' : ''); ?>>WIB (UTC+7)</option>
                        <option value="Asia/Makassar" <?php echo e(old('timezone', config('app.timezone')) == 'Asia/Makassar' ? 'selected' : ''); ?>>WITA (UTC+8)</option>
                        <option value="Asia/Jayapura" <?php echo e(old('timezone', config('app.timezone')) == 'Asia/Jayapura' ? 'selected' : ''); ?>>WIT (UTC+9)</option>
                    </select>
                    <?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="format-tanggal">Format Tanggal</label>
                    <select id="format-tanggal" name="date_format" class="<?php $__errorArgs = ['date_format'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="d/m/Y" <?php echo e(old('date_format', 'd/m/Y') == 'd/m/Y' ? 'selected' : ''); ?>>DD/MM/YYYY</option>
                        <option value="m/d/Y" <?php echo e(old('date_format', 'd/m/Y') == 'm/d/Y' ? 'selected' : ''); ?>>MM/DD/YYYY</option>
                        <option value="Y-m-d" <?php echo e(old('date_format', 'd/m/Y') == 'Y-m-d' ? 'selected' : ''); ?>>YYYY-MM-DD</option>
                    </select>
                    <?php $__errorArgs = ['date_format'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="action-buttons">
                    <button type="button" class="btn btn-outline" onclick="resetSystemForm()">Reset</button>
                    <button type="submit" class="btn btn-primary" id="simpanSistem">
                        <i class="fas fa-save me-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </form>
        </section>

        <!-- Manajemen Admin Section -->
        <section id="manajemen-admin" class="settings-section <?php echo e(session('activeSection') == 'manajemen-admin' ? 'active' : ''); ?>">
            <h2>👥 Manajemen Admin</h2>
            
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <p>Kelola admin yang dapat mengakses dashboard admin.</p>
            </div>
            
            <!-- Form Tambah Admin -->
            <form method="POST" action="<?php echo e(route('admin.pengaturan.admin.add')); ?>" id="formTambahAdmin">
                <?php echo csrf_field(); ?>
                
                <div class="form-group">
                    <label for="nama-admin">Nama Lengkap *</label>
                    <input type="text" id="nama-admin" name="name" placeholder="Masukkan nama admin" 
                           value="<?php echo e(old('name')); ?>" required
                           class="<?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="email-admin">Email *</label>
                    <input type="email" id="email-admin" name="email" placeholder="Masukkan email admin" 
                           value="<?php echo e(old('email')); ?>" required
                           class="<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="telepon-admin">Nomor Telepon</label>
                    <input type="tel" id="telepon-admin" name="phone" placeholder="Masukkan nomor telepon" 
                           value="<?php echo e(old('phone')); ?>"
                           class="<?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="role-admin">Role *</label>
                    <select id="role-admin" name="role" class="<?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                        <option value="superadmin" <?php echo e(old('role') == 'superadmin' ? 'selected' : ''); ?>>Super Admin</option>
                    </select>
                    <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="password-admin">Password *</label>
                    <div class="password-input">
                        <input type="password" id="password-admin" name="password" placeholder="Masukkan password" required
                               class="<?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <button type="button" class="toggle-password" onclick="togglePassword('password-admin')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password *</label>
                    <div class="password-input">
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               placeholder="Konfirmasi password" required
                               class="<?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="tambahAdmin">
                        <i class="fas fa-plus me-2"></i>Tambah Admin
                    </button>
                </div>
            </form>
            
            <!-- Tabel Admin -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td class="fw-bold"><?php echo e($admin->name); ?></td>
                                <td><?php echo e($admin->email); ?></td>
                                <td><?php echo e($admin->phone ?: '-'); ?></td>
                                <td>
                                    <?php
                                        $role = $admin->roles->first();
                                        $roleName = $role ? $role->name : 'admin';
                                        $roleClass = $roleName == 'superadmin' ? 'badge-success' : 'badge-info';
                                    ?>
                                    <span class="badge <?php echo e($roleClass); ?>">
                                        <?php echo e(ucfirst($roleName)); ?>

                                    </span>
                                </td>
                                <td>
                                    <span id="admin-status-<?php echo e($admin->id); ?>" class="badge <?php echo e($admin->email_verified_at ? 'badge-success' : 'badge-danger'); ?>">
                                        <?php echo e($admin->email_verified_at ? 'Aktif' : 'Nonaktif'); ?>

                                    </span>
                                </td>
                                <td><?php echo e($admin->created_at->format('d/m/Y')); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-info edit-admin" 
                                                title="Edit" 
                                                data-id="<?php echo e($admin->id); ?>"
                                                data-name="<?php echo e($admin->name); ?>"
                                                data-email="<?php echo e($admin->email); ?>"
                                                data-phone="<?php echo e($admin->phone); ?>"
                                                data-role="<?php echo e($roleName); ?>"
                                                data-status="<?php echo e($admin->email_verified_at ? 'aktif' : 'nonaktif'); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if($admin->id != auth()->id()): ?>
                                        <button class="btn btn-danger delete-admin" 
                                                title="Hapus" 
                                                data-id="<?php echo e($admin->id); ?>"
                                                data-name="<?php echo e($admin->name); ?>"
                                                data-email="<?php echo e($admin->email); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-users text-muted" style="font-size: 2rem;"></i>
                                    <p class="mt-2">Belum ada data admin</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                    <?php if($admins->count() > 0): ?>
                    <div class="mt-3 text-muted">
                        <small>Total Admin: <?php echo e($admins->count()); ?></small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Pengaturan Notifikasi Section -->
        <section id="pengaturan-notifikasi" class="settings-section <?php echo e(session('activeSection') == 'pengaturan-notifikasi' ? 'active' : ''); ?>">
            <h2>🔔 Pengaturan Notifikasi</h2>
            
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <p>Atur preferensi notifikasi untuk admin.</p>
            </div>
            
            <form method="POST" action="<?php echo e(route('admin.pengaturan.notifications.update')); ?>" id="notificationForm">
                <?php echo csrf_field(); ?>
                
                <div class="notification-category">
                    <h3><i class="fas fa-envelope me-2"></i>Notifikasi Email</h3>
                    
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="email-booking" name="email_booking" 
                                   <?php echo e(old('email_booking', session('notification_settings.email_booking', true)) ? 'checked' : ''); ?>>
                            <label for="email-booking">
                                <span class="checkbox-label">Booking Baru</span>
                                <span class="checkbox-desc">Dapatkan notifikasi saat ada booking baru</span>
                            </label>
                        </div>
                        
                        <div class="checkbox-item">
                            <input type="checkbox" id="email-payment" name="email_payment" 
                                   <?php echo e(old('email_payment', session('notification_settings.email_payment', true)) ? 'checked' : ''); ?>>
                            <label for="email-payment">
                                <span class="checkbox-label">Pembayaran</span>
                                <span class="checkbox-desc">Notifikasi status pembayaran</span>
                            </label>
                        </div>
                        
                        <div class="checkbox-item">
                            <input type="checkbox" id="email-report" name="email_report" 
                                   <?php echo e(old('email_report', session('notification_settings.email_report', true)) ? 'checked' : ''); ?>>
                            <label for="email-report">
                                <span class="checkbox-label">Laporan Sistem</span>
                                <span class="checkbox-desc">Notifikasi laporan mingguan/bulanan</span>
                            </label>
                        </div>
                        
                        <div class="checkbox-item">
                            <input type="checkbox" id="email-venue" name="email_venue" 
                                   <?php echo e(old('email_venue', session('notification_settings.email_venue', true)) ? 'checked' : ''); ?>>
                            <label for="email-venue">
                                <span class="checkbox-label">Venue Baru</span>
                                <span class="checkbox-desc">Notifikasi saat venue baru bergabung</span>
                            </label>
                        </div>
                        
                        <div class="checkbox-item">
                            <input type="checkbox" id="email-support" name="email_support" 
                                   <?php echo e(old('email_support', session('notification_settings.email_support', true)) ? 'checked' : ''); ?>>
                            <label for="email-support">
                                <span class="checkbox-label">Support Ticket</span>
                                <span class="checkbox-desc">Notifikasi ticket support baru</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="notification-frequency">
                    <h3><i class="fas fa-clock me-2"></i>Frekuensi Notifikasi</h3>
                    
                    <div class="radio-group">
                        <div class="radio-item">
                            <input type="radio" id="frequency-realtime" name="notification_frequency" value="realtime" 
                                   <?php echo e(old('notification_frequency', session('notification_settings.frequency', 'realtime')) == 'realtime' ? 'checked' : ''); ?>>
                            <label for="frequency-realtime">Real-time (Segera)</label>
                        </div>
                        
                        <div class="radio-item">
                            <input type="radio" id="frequency-daily" name="notification_frequency" value="daily" 
                                   <?php echo e(old('notification_frequency', session('notification_settings.frequency', 'realtime')) == 'daily' ? 'checked' : ''); ?>>
                            <label for="frequency-daily">Ringkasan Harian</label>
                        </div>
                        
                        <div class="radio-item">
                            <input type="radio" id="frequency-weekly" name="notification_frequency" value="weekly" 
                                   <?php echo e(old('notification_frequency', session('notification_settings.frequency', 'realtime')) == 'weekly' ? 'checked' : ''); ?>>
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

        <!-- Keamanan & Backup Section -->
        <section id="keamanan-backup" class="settings-section <?php echo e(session('activeSection') == 'keamanan-backup' ? 'active' : ''); ?>">
            <h2>🔒 Keamanan & Backup</h2>
            
            <div class="info-box">
                <i class="fas fa-shield-alt"></i>
                <p>Kelola keamanan sistem dan backup data.</p>
            </div>
            
            <form method="POST" action="<?php echo e(route('admin.pengaturan.security.update')); ?>" id="securityForm">
                <?php echo csrf_field(); ?>
                
                <div class="password-change">
                    <h3><i class="fas fa-key me-2"></i>Ganti Password Admin</h3>
                    
                    <div class="form-group">
                        <label for="security-current-password">Password Saat Ini *</label>
                        <div class="password-input">
                            <input type="password" id="security-current-password" name="current_password" 
                                   placeholder="Masukkan password saat ini"
                                   class="<?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <button type="button" class="toggle-password" onclick="togglePassword('security-current-password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="security-new-password">Password Baru *</label>
                        <div class="password-input">
                            <input type="password" id="security-new-password" name="new_password" 
                                   placeholder="Masukkan password baru (min. 8 karakter)"
                                   class="<?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
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
                        <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="security-confirm-password">Konfirmasi Password Baru *</label>
                        <div class="password-input">
                            <input type="password" id="security-confirm-password" name="new_password_confirmation" 
                                   placeholder="Konfirmasi password baru"
                                   class="<?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <button type="button" class="toggle-password" onclick="togglePassword('security-confirm-password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-match" id="passwordMatch">
                            <i class="fas fa-check"></i> Password cocok
                        </div>
                        <?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary" id="updatePassword">
                            <i class="fas fa-key me-2"></i>Perbarui Password
                        </button>
                    </div>
                </div>
                
                <div class="backup-settings">
                    <h3><i class="fas fa-database me-2"></i>Pengaturan Backup</h3>
                    
                    <div class="form-group">
                        <label for="frekuensi-backup">Frekuensi Backup</label>
                        <select id="frekuensi-backup" name="backup_frequency" class="<?php $__errorArgs = ['backup_frequency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="daily" <?php echo e(old('backup_frequency', 'weekly') == 'daily' ? 'selected' : ''); ?>>Harian</option>
                            <option value="weekly" <?php echo e(old('backup_frequency', 'weekly') == 'weekly' ? 'selected' : ''); ?> selected>Mingguan</option>
                            <option value="monthly" <?php echo e(old('backup_frequency', 'weekly') == 'monthly' ? 'selected' : ''); ?>>Bulanan</option>
                        </select>
                        <?php $__errorArgs = ['backup_frequency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="jumlah-backup">Jumlah Backup yang Disimpan</label>
                        <input type="number" id="jumlah-backup" name="backup_retention" 
                               value="<?php echo e(old('backup_retention', 30)); ?>" min="1" max="365"
                               class="<?php $__errorArgs = ['backup_retention'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <small class="text-muted">Jumlah backup yang akan disimpan sebelum dihapus otomatis</small>
                        <?php $__errorArgs = ['backup_retention'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="action-buttons">
                        <button type="button" class="btn btn-outline" onclick="createBackup()">
                            <i class="fas fa-database me-2"></i>Backup Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </section>

        <!-- Pusat Bantuan Section -->
        <section id="pusat-bantuan" class="settings-section <?php echo e(session('activeSection') == 'pusat-bantuan' ? 'active' : ''); ?>">
            <h2>❓ Pusat Bantuan Admin</h2>
            
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
                    <button class="category-tab" data-category="admin">Admin</button>
                    <button class="category-tab" data-category="venue">Venue</button>
                    <button class="category-tab" data-category="user">Pengguna</button>
                    <button class="category-tab" data-category="system">Sistem</button>
                </div>
            </div>
            
            <div class="faq-container" id="faqContainer">
                <div class="faq-category" data-category="admin">
                    <h3><i class="fas fa-user-cog me-2"></i>Manajemen Admin</h3>
                    
                    <div class="faq-list" id="faq-list">
                        <div class="faq-item" data-category="admin">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>1. Bagaimana cara menambahkan admin baru?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="faq-answer">
                                <p>Pergi ke menu <strong>Pengaturan → Manajemen Admin</strong>, isi form tambah admin dengan data lengkap dan password yang aman.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item" data-category="admin">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>2. Apa perbedaan role admin dan super admin?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="faq-answer">
                                <p><strong>Super Admin</strong> memiliki akses penuh termasuk pengaturan sistem dan manajemen admin lainnya. <strong>Admin</strong> memiliki akses terbatas untuk manajemen venue dan pengguna.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="faq-category" data-category="venue">
                    <h3><i class="fas fa-store me-2"></i>Manajemen Venue</h3>
                    
                    <div class="faq-item" data-category="venue">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>3. Bagaimana cara memverifikasi venue?</span>
                                <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Pergi ke menu <strong>Venues → Pending Verification</strong>, klik venue yang ingin diverifikasi, periksa kelengkapan data, lalu klik tombol <strong>Verify</strong>.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-category" data-category="user">
                    <h3><i class="fas fa-users me-2"></i>Manajemen Pengguna</h3>
                    
                    <div class="faq-item" data-category="user">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>4. Bagaimana menonaktifkan akun pengguna?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Pergi ke menu <strong>Users</strong>, cari pengguna yang ingin dinonaktifkan, klik tombol <strong>Action</strong> lalu pilih <strong>Disable Account</strong>.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="no-results" id="noResults" style="display: none;">
                <i class="fas fa-search"></i>
                <h4>Tidak ditemukan</h4>
                <p>Tidak ada hasil untuk pencarian Anda. Coba kata kunci lain atau hubungi support.</p>
            </div>
            
            <!-- PERBAIKAN: Bagian Support dengan Email dan WhatsApp saja -->
            <div class="contact-support">
                <h3><i class="fas fa-headset me-2"></i>Butuh Bantuan Lebih Lanjut?</h3>
                <div class="support-options">
                    <!-- Hanya Email dan WhatsApp saja -->
                    <div class="support-option">
                        <i class="fas fa-envelope"></i>
                        <h4>Email Support</h4>
                        <!-- PERBAIKAN: Email yang benar -->
                        <p>cariarena.app@gmail.com</p>
                        <!-- PERBAIKAN: Link email yang benar -->
                        <a href="mailto:cariarena.app@gmail.com" class="btn btn-outline btn-sm">
                            <i class="fas fa-paper-plane me-1"></i>Kirim Email
                        </a>
                    </div>
                    
                    <div class="support-option whatsapp-option">
                        <i class="fab fa-whatsapp"></i>
                        <h4>WhatsApp Support</h4>
                        <p>08:00 - 22:00 WIB</p>
                        <!-- PERBAIKAN: WhatsApp number yang benar -->
                        <a href="https://wa.me/6285731125834?text=Halo%20Admin%20CariArena,%20saya%20membutuhkan%20bantuan" 
                        target="_blank" class="btn btn-outline btn-sm">
                            <i class="fab fa-whatsapp me-1"></i>Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>

<!-- Modal Edit Admin -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditAdmin" action="<?php echo e(route('admin.pengaturan.admin.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" id="edit_admin_id">
                
                <div class="modal-header">
                    <h5 class="modal-title">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_admin_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_admin_name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_admin_email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_admin_email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_admin_phone" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" id="edit_admin_phone" name="phone">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_admin_role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_admin_role" name="role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_admin_status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_admin_status" name="status" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                        <small class="text-muted">Admin aktif dapat login, nonaktif tidak dapat login</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_admin_password" class="form-label">Password Baru (Opsional)</label>
                        <input type="password" class="form-control" id="edit_admin_password" name="password">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_admin_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="edit_admin_password_confirmation" name="password_confirmation">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="simpanEditAdmin">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus Admin -->
<div class="modal fade" id="hapusAdminModal" tabindex="-1" aria-labelledby="hapusAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('admin.pengaturan.admin.delete')); ?>" id="formHapusAdmin">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" id="hapus_admin_id">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusAdminModalLabel">🗑 Hapus Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-center mb-3" id="hapusAdminTitle">Apakah Anda yakin ingin menghapus admin ini?</h5>
                    <p class="text-center text-muted" id="hapusAdminDetails">
                        Email: <span id="hapusAdminEmail" class="fw-bold"></span><br>
                        Status: <span id="hapusAdminStatus" class="fw-bold"></span><br>
                        Bergabung: <span id="hapusAdminJoined" class="fw-bold"></span>
                    </p>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <small>Tindakan ini tidak dapat dibatalkan dan akses admin akan dihapus secara permanen.</small>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger" id="konfirmasiHapusAdmin">
                        <i class="fas fa-trash me-2"></i>Ya, Hapus Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
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
    /* PERBAIKAN: UKURAN DIPERKECIL DAN HANYA 2 KOLOM */
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
        max-width: 600px; /* Lebar maksimum dibatasi */
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

    /* ==== TABLE STYLES ==== */
    .table-container {
        background: var(--card-bg);
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        margin-top: 20px;
    }

    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 15px;
        font-weight: 600;
        text-align: left;
    }

    .table td {
        padding: 12px 15px;
        vertical-align: middle;
        border-color: #f1f5f9;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
        border-radius: 6px;
    }

    .badge-success {
        background-color: #E8F5E8;
        color: #2E7D32;
        border: 1px solid #C8E6C9;
    }

    .badge-danger {
        background-color: #FFEBEE;
        color: #D32F2F;
        border: 1px solid #FFCDD2;
    }

    .badge-info {
        background-color: #E3F2FD;
        color: #1565C0;
        border: 1px solid #BBDEFB;
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
        
        .table-container {
            padding: 15px;
            overflow-x: auto;
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Pengaturan admin scripts loaded');
        
        // ========== INITIALIZATION ==========
        initSettingsPage();
        
        // ========== NAVIGASI ANTAR SECTION ==========
        function initSettingsPage() {
            const menuLinks = document.querySelectorAll('.horizontal-nav a');
            const settingsSections = document.querySelectorAll('.settings-section');
            
            // Set active section from session or default
            const activeSection = '<?php echo e(session('activeSection', 'profil-akun')); ?>';
            
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

        // ========== PHOTO UPLOAD HANDLING ==========
        window.handlePhotoUpload = function(input) {
            const file = input.files[0];
            if (!file) return;
            
            console.log('File selected:', {
                name: file.name,
                size: file.size,
                type: file.type
            });
            
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
                        this.src = '<?php echo e(asset('images/default-avatar.png')); ?>';
                        this.style.cssText = 'width: 100%; height: 100%; object-fit: cover; border-radius: 50%;';
                    };
                    
                    previewContainer.insertBefore(img, loadingElement);
                    
                    // Hide loading
                    if (loadingElement) loadingElement.style.display = 'none';
                    
                    // Show remove button if not already shown
                    const removeBtn = document.querySelector('.remove-btn');
                    if (removeBtn && removeBtn.style.display === 'none') {
                        removeBtn.style.display = 'block';
                    }
                    
                    // Reset remove photo flag
                    document.getElementById('removePhoto').value = '0';
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
            }
        };

        // ========== TOGGLE PASSWORD VISIBILITY ==========
        window.togglePassword = function(inputId) {
            const input = document.getElementById(inputId);
            if (!input) return;
            
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
            
            // Update requirements
            updatePasswordRequirements(password);
        };

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

        // ========== RESET PROFILE FORM ==========
        window.resetProfilForm = function() {
            if (confirm('Reset semua perubahan? Data akan dikembalikan ke nilai semula.')) {
                const form = document.getElementById('profileForm');
                if (form) {
                    form.reset();
                    
                    // Reset photo preview to original
                    const previewContainer = document.getElementById('photoPreview');
                    const userHasPhoto = <?php echo json_encode($hasPhoto, 15, 512) ?>;
                    const photoUrl = <?php echo json_encode($photoUrl, 15, 512) ?>;
                    
                    if (previewContainer) {
                        if (userHasPhoto && photoUrl) {
                            previewContainer.innerHTML = `
                                <img src="${photoUrl}" 
                                     alt="Foto Profil" 
                                     id="currentProfilePhoto"
                                     class="profile-image"
                                     data-filename="<?php echo e($user->profile_photo); ?>"
                                     onerror="this.onerror=null; this.src='<?php echo e(asset('images/default-avatar.png')); ?>'; this.classList.add('default-avatar-img');"
                                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                <div class="photo-loading" id="photoLoading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            `;
                            // Show remove button
                            const removeBtn = document.querySelector('.remove-btn');
                            if (removeBtn) removeBtn.style.display = 'block';
                        } else {
                            previewContainer.innerHTML = `
                                <div class="default-avatar">
                                    <i class="fas fa-user"></i>
                                    <span>Foto Profil</span>
                                </div>
                                <div class="photo-loading" id="photoLoading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            `;
                            // Hide remove button
                            const removeBtn = document.querySelector('.remove-btn');
                            if (removeBtn) removeBtn.style.display = 'none';
                        }
                    }
                    
                    // Reset remove photo flag
                    const removePhotoInput = document.getElementById('removePhoto');
                    if (removePhotoInput) removePhotoInput.value = '0';
                }
            }
        };

        // ========== FORM SUBMISSION HANDLERS ==========
        const forms = ['profileForm', 'systemForm', 'formTambahAdmin', 'notificationForm', 'securityForm'];
        
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

        // ========== EDIT ADMIN MODAL ==========
        document.querySelectorAll('.edit-admin').forEach(button => {
            button.addEventListener('click', function() {
                const adminId = this.getAttribute('data-id');
                const adminName = this.getAttribute('data-name');
                const adminEmail = this.getAttribute('data-email');
                const adminPhone = this.getAttribute('data-phone') || '';
                const adminRole = this.getAttribute('data-role');
                const adminStatus = this.getAttribute('data-status');
                
                // Set nilai form edit
                document.getElementById('edit_admin_id').value = adminId;
                document.getElementById('edit_admin_name').value = adminName;
                document.getElementById('edit_admin_email').value = adminEmail;
                document.getElementById('edit_admin_phone').value = adminPhone;
                document.getElementById('edit_admin_role').value = adminRole;
                document.getElementById('edit_admin_status').value = adminStatus;
                
                // Reset password fields
                document.getElementById('edit_admin_password').value = '';
                document.getElementById('edit_admin_password_confirmation').value = '';
                
                // Show modal
                const editModalElement = document.getElementById('editAdminModal');
                if (editModalElement) {
                    const editModal = new bootstrap.Modal(editModalElement);
                    editModal.show();
                }
            });
        });

        // ========== DELETE ADMIN MODAL ==========
        document.querySelectorAll('.delete-admin').forEach(button => {
            button.addEventListener('click', function() {
                const adminId = this.getAttribute('data-id');
                const adminName = this.getAttribute('data-name');
                const adminEmail = this.getAttribute('data-email');
                
                // Set data di modal
                document.getElementById('hapus_admin_id').value = adminId;
                document.getElementById('hapusAdminTitle').textContent = `Apakah Anda yakin ingin menghapus admin "${adminName}"?`;
                document.getElementById('hapusAdminEmail').textContent = adminEmail;
                
                // Get status from table
                const row = this.closest('tr');
                const statusBadge = row.querySelector('.badge')?.textContent || 'Aktif';
                document.getElementById('hapusAdminStatus').textContent = statusBadge;
                
                // Get join date
                const joinDate = row.querySelector('td:nth-child(7)')?.textContent || '-';
                document.getElementById('hapusAdminJoined').textContent = joinDate;
                
                // Show modal
                const deleteModalElement = document.getElementById('hapusAdminModal');
                if (deleteModalElement) {
                    const deleteModal = new bootstrap.Modal(deleteModalElement);
                    deleteModal.show();
                }
            });
        });

        // ========== CREATE BACKUP ==========
        window.createBackup = function() {
            if (confirm('Apakah Anda yakin ingin membuat backup sekarang?')) {
                // Submit the backup form
                fetch("<?php echo e(route('admin.pengaturan.backup.now')); ?>", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Backup berhasil dibuat: ' + data.filename);
                        location.reload();
                    } else {
                        alert('Gagal membuat backup: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat membuat backup');
                });
            }
        };

        // ========== LOGO HANDLING (Simulasi) ==========
        window.handleLogoUpload = function(input) {
            alert('Fitur upload logo akan datang');
            input.value = '';
        };

        window.removeLogo = function() {
            if (confirm('Apakah Anda yakin ingin menghapus logo?')) {
                document.getElementById('removeLogoInput').value = '1';
                const previewContainer = document.getElementById('logoPreview');
                if (previewContainer) {
                    previewContainer.innerHTML = `
                        <div class="default-avatar">
                            <i class="fas fa-flag"></i>
                            <span>Logo App</span>
                        </div>
                    `;
                }
            }
        };

        // ========== OPEN WHATSAPP SUPPORT ==========
        window.openWhatsAppSupport = function(adminNumber = null) {
            let phoneNumber = adminNumber || '6285731125834';
            const message = encodeURIComponent('Halo Admin CariArena, saya butuh bantuan terkait: ');
            const whatsappUrl = `https://wa.me/${phoneNumber}?text=${message}`;
            
            window.open(whatsappUrl, '_blank');
        };

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

        console.log('All admin settings scripts initialized successfully');
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\CariArena\resources\views/admin/pengaturan.blade.php ENDPATH**/ ?>