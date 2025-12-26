<?php if (isset($_SESSION['anggota_id'])): ?>
<!-- Sidebar for Logged In Users -->
<div class="col-md-3">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-user-circle me-2"></i>Profil Saya</h6>
        </div>
        <div class="card-body text-center">
            <?php if (isset($anggota['foto_profil']) && $anggota['foto_profil']): ?>
                <img src="assets/images/<?php echo $anggota['foto_profil']; ?>" 
                     alt="<?php echo htmlspecialchars($anggota['nama_lengkap']); ?>" 
                     class="rounded-circle mb-3" 
                     style="width: 80px; height: 80px; object-fit: cover;">
            <?php else: ?>
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-user fa-2x"></i>
                </div>
            <?php endif; ?>
            
            <h6 class="mb-1"><?php echo htmlspecialchars($anggota['nama_lengkap']); ?></h6>
            <p class="text-muted small mb-2"><?php echo $anggota['nim']; ?></p>
            <p class="text-muted small mb-0"><?php echo $anggota['jurusan']; ?> - <?php echo $anggota['angkatan']; ?></p>
            
            <a href="index.php?action=profile" class="btn btn-outline-primary btn-sm mt-2">
                <i class="fas fa-edit me-1"></i>Edit Profil
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h6>
        </div>
        <div class="card-body">
            <div class="list-group list-group-flush">
                <a href="index.php?action=dashboard" class="list-group-item list-group-item-action">
                    <i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard
                </a>
                <a href="index.php?action=riwayat" class="list-group-item list-group-item-action">
                    <i class="fas fa-history me-2 text-info"></i>Riwayat Pendaftaran
                </a>
                <a href="index.php?action=organisasi_saya" class="list-group-item list-group-item-action">
                    <i class="fas fa-users me-2 text-success"></i>Organisasi Saya
                </a>
            </div>
        </div>
    </div>

    <!-- My Organizations -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-users me-2"></i>Organisasi Saya</h6>
        </div>
        <div class="card-body">
            <?php if (empty($kepengurusan)): ?>
                <p class="text-muted small mb-0">Belum bergabung dengan organisasi manapun.</p>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($kepengurusan as $org): ?>
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-circle text-success fa-xs"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-0 small"><?php echo htmlspecialchars($org['nama_organisasi']); ?></h6>
                                    <small class="text-muted"><?php echo $org['nama_jabatan']; ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php else: ?>
<!-- Sidebar for Guests -->
<div class="col-md-3">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Info</h6>
        </div>
        <div class="card-body">
            <p class="small text-muted">
                Login untuk mengakses fitur lengkap seperti pendaftaran organisasi, manajemen profil, dan riwayat pendaftaran.
            </p>
            <div class="d-grid gap-2">
                <a href="index.php?action=login" class="btn btn-primary btn-sm">
                    <i class="fas fa-sign-in-alt me-1"></i>Login
                </a>
                <a href="index.php?action=register" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-user-plus me-1"></i>Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik</h6>
        </div>
        <div class="card-body">
            <div class="text-center">
                <div class="row">
                    <div class="col-6 mb-3">
                        <h4 class="text-primary mb-0">15+</h4>
                        <small class="text-muted">Organisasi</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-success mb-0">500+</h4>
                        <small class="text-muted">Anggota</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <h4 class="text-info mb-0">30+</h4>
                        <small class="text-muted">Divisi</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning mb-0">50+</h4>
                        <small class="text-muted">Kegiatan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h6 class="mb-0"><i class="fas fa-link me-2"></i>Tautan Cepat</h6>
        </div>
        <div class="card-body">
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action small">
                    <i class="fas fa-calendar me-2"></i>Kalender Kegiatan
                </a>
                <a href="#" class="list-group-item list-group-item-action small">
                    <i class="fas fa-newspaper me-2"></i>Berita Kampus
                </a>
                <a href="#" class="list-group-item list-group-item-action small">
                    <i class="fas fa-download me-2"></i>Download Form
                </a>
                <a href="#" class="list-group-item list-group-item-action small">
                    <i class="fas fa-question-circle me-2"></i>Panduan
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>