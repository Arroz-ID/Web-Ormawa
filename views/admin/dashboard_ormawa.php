<?php include __DIR__ . '/../../templates/header.php'; ?>

<div class="container-fluid my-4">
    <div class="row">
        <div class="col-md-3">
             <?php include __DIR__ . '/../../templates/sidebar.php'; ?>
        </div>

        <div class="col-md-9">
            
            <div class="card border-0 shadow-sm mb-4 text-white" 
                 style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bold mb-1">Halo, Admin <?= htmlspecialchars($stats['nama_organisasi']) ?>! 👋</h4>
                            <p class="mb-0 opacity-75">Selamat datang di Panel Manajemen Organisasi.</p>
                        </div>
                        <div class="d-none d-md-block opacity-25">
                            <i class="fas fa-building fa-4x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm border-start border-4 border-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted text-uppercase small fw-bold mb-1">Pendaftar Baru</p>
                                    <h2 class="fw-bold mb-0 text-dark"><?= $stats['pendaftar_pending'] ?></h2>
                                </div>
                                <div class="bg-warning bg-opacity-10 text-warning rounded p-3">
                                    <i class="fas fa-user-clock fa-lg"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <?php if($stats['pendaftar_pending'] > 0): ?>
                                    <a href="index.php?action=ormawa_seleksi" class="btn btn-warning btn-sm w-100 fw-bold">
                                        Proses Seleksi <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small"><i class="fas fa-check-circle me-1"></i> Tidak ada antrean</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm border-start border-4 border-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted text-uppercase small fw-bold mb-1">Anggota Aktif</p>
                                    <h2 class="fw-bold mb-0 text-dark"><?= $stats['anggota_aktif'] ?></h2>
                                </div>
                                <div class="bg-success bg-opacity-10 text-success rounded p-3">
                                    <i class="fas fa-users fa-lg"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="index.php?action=ormawa_kelola_anggota" class="text-decoration-none small text-success fw-bold">
                                    Lihat Data Anggota <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm border-start border-4 border-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted text-uppercase small fw-bold mb-1">Kegiatan / Proker</p>
                                    <h2 class="fw-bold mb-0 text-dark"><?= $stats['total_kegiatan'] ?></h2>
                                </div>
                                <div class="bg-info bg-opacity-10 text-info rounded p-3">
                                    <i class="fas fa-calendar-check fa-lg"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="index.php?action=ormawa_kegiatan" class="text-decoration-none small text-info fw-bold">
                                    Kelola Kegiatan <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-rocket me-2"></i>Akses Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <a href="index.php?action=ormawa_edit_profil" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-2 h-100">
                                <i class="fas fa-edit fa-2x"></i>
                                <span class="fw-bold small">Edit Profil</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="index.php?action=ormawa_seleksi" class="btn btn-outline-warning w-100 py-3 d-flex flex-column align-items-center gap-2 h-100">
                                <i class="fas fa-user-plus fa-2x"></i>
                                <span class="fw-bold small">Seleksi Masuk</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="index.php?action=ormawa_laporan" class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center gap-2 h-100">
                                <i class="fas fa-file-alt fa-2x"></i>
                                <span class="fw-bold small">Laporan</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="index.php?action=ormawa_kelola_divisi" class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center gap-2 h-100">
                                <i class="fas fa-sitemap fa-2x"></i>
                                <span class="fw-bold small">Kelola Divisi</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../../templates/footer.php'; ?>