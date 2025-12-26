<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="index.php?action=detail&id=<?php echo $organisasi['organisasi_id']; ?>">
                <?php echo htmlspecialchars($organisasi['nama_organisasi']); ?>
            </a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($divisi['nama_divisi']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="card-title h3"><?php echo htmlspecialchars($divisi['nama_divisi']); ?></h1>
                            <p class="text-muted mb-0">Divisi dari <?php echo htmlspecialchars($organisasi['nama_organisasi']); ?></p>
                        </div>
                        <span class="badge bg-primary fs-6">Kuota: <?php echo $divisi['kuota_anggota']; ?> Anggota</span>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-primary"><i class="fas fa-info-circle me-2"></i>Deskripsi Divisi</h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($divisi['deskripsi_divisi'])); ?></p>
                    </div>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4">
                        <?php if (isset($_SESSION['anggota_id'])): ?>
                            <a href="index.php?action=daftar_divisi&id=<?php echo $divisi['divisi_id']; ?>" 
                               class="btn btn-primary me-md-2">
                                <i class="fas fa-user-plus me-2"></i>Daftar Divisi Ini
                            </a>
                        <?php else: ?>
                            <a href="index.php?action=login&redirect=daftar_divisi&id=<?php echo $divisi['divisi_id']; ?>" 
                               class="btn btn-primary me-md-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Login untuk Mendaftar
                            </a>
                        <?php endif; ?>
                        
                        <a href="index.php?action=detail&id=<?php echo $organisasi['organisasi_id']; ?>" 
                           class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Organisasi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Anggota Divisi -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Anggota Divisi</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($anggota_divisi)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada anggota di divisi ini.</p>
                            <p class="text-muted small">Jadilah yang pertama bergabung!</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($anggota_divisi as $anggota): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <?php if ($anggota['foto_profil']): ?>
                                                        <img src="assets/images/<?php echo $anggota['foto_profil']; ?>" 
                                                             alt="<?php echo htmlspecialchars($anggota['nama_lengkap']); ?>" 
                                                             class="rounded-circle" 
                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 50px;">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($anggota['nama_lengkap']); ?></h6>
                                                    <p class="text-muted small mb-1"><?php echo $anggota['nama_jabatan']; ?></p>
                                                    <p class="text-muted small mb-0">
                                                        <?php echo $anggota['jurusan']; ?> - <?php echo $anggota['angkatan']; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Organisasi Info -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-university me-2"></i>Info Organisasi</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <?php if ($organisasi['logo']): ?>
                            <img src="assets/images/<?php echo $organisasi['logo']; ?>" 
                                 alt="<?php echo htmlspecialchars($organisasi['nama_organisasi']); ?>" 
                                 class="img-fluid rounded mb-2" 
                                 style="max-height: 80px;">
                        <?php else: ?>
                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center mx-auto mb-2" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        <?php endif; ?>
                        <h6><?php echo htmlspecialchars($organisasi['nama_organisasi']); ?></h6>
                    </div>
                    <p class="small text-muted">
                        <?php echo strlen($organisasi['deskripsi']) > 100 ? 
                            substr($organisasi['deskripsi'], 0, 100) . '...' : 
                            $organisasi['deskripsi']; ?>
                    </p>
                    <a href="index.php?action=detail&id=<?php echo $organisasi['organisasi_id']; ?>" 
                       class="btn btn-outline-primary btn-sm w-100">
                        Lihat Organisasi
                    </a>
                </div>
            </div>

            <!-- Statistik -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0"><?php echo count($anggota_divisi); ?></h4>
                                <small class="text-muted">Anggota Aktif</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-0"><?php echo $divisi['kuota_anggota']; ?></h4>
                            <small class="text-muted">Kuota Tersedia</small>
                        </div>
                    </div>
                    <hr>
                    <div class="progress mb-2">
                        <?php 
                        $percentage = $divisi['kuota_anggota'] > 0 ? 
                            (count($anggota_divisi) / $divisi['kuota_anggota']) * 100 : 0;
                        ?>
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: <?php echo $percentage; ?>%"
                             aria-valuenow="<?php echo $percentage; ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <?php echo round($percentage); ?>%
                        </div>
                    </div>
                    <small class="text-muted">Kapasitas divisi terisi <?php echo round($percentage); ?>%</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>