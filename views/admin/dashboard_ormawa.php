<?php include 'views/templates/header.php'; ?>

<style>
    /* Dashboard Specific Styles */
    .hero-dashboard {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 16px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .hero-pattern {
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        opacity: 0.1;
        background-image: radial-gradient(#fff 1px, transparent 1px);
        background-size: 20px 20px;
    }

    .stat-card {
        border: none;
        border-radius: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .stat-icon-bg {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.1;
        transform: rotate(-15deg);
    }

    .action-btn-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .action-card-btn {
        text-align: center;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        background: #fff;
        transition: all 0.2s;
        text-decoration: none;
        color: var(--dark-color);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .action-card-btn:hover {
        background-color: #f8f9fa;
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>

<div class="container-fluid mb-5">
    <div class="row">
        <?php include 'views/templates/sidebar.php'; ?>
        
        <div class="col-md-9">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-0">Dashboard Pengurus</h1>
                    <p class="text-muted small mb-0">Overview aktivitas organisasi Anda hari ini</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-white shadow-sm border">
                            <i class="far fa-calendar-alt me-2 text-primary"></i> <?= date('d M Y') ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card hero-dashboard shadow-sm border-0 mb-4">
                <div class="card-body p-4 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="h4 fw-bold mb-2">Selamat Datang, Admin! 👋</h2>
                            <p class="mb-0 opacity-75">
                                Anda login sebagai pengelola <strong><?= htmlspecialchars($stats['nama_organisasi']) ?></strong>.
                                <br>Pantau pendaftar baru dan kelola program kerja untuk kemajuan organisasi.
                            </p>
                        </div>
                        <div class="col-lg-4 text-end d-none d-lg-block">
                            <i class="fas fa-university fa-4x opacity-25"></i>
                        </div>
                    </div>
                </div>
                <div class="hero-pattern"></div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-xl-4 col-md-6">
                    <div class="card stat-card bg-primary text-white h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                                <div>
                                    <h6 class="text-uppercase mb-1 small opacity-75 fw-bold">Pendaftar Baru</h6>
                                    <h2 class="display-5 fw-bold mb-0"><?= $stats['pending'] ?></h2>
                                    <small class="opacity-75">Menunggu seleksi</small>
                                </div>
                                <div class="stat-icon-bg">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white bg-opacity-10 border-0">
                            <a href="index.php?action=ormawa_seleksi" class="text-white text-decoration-none small d-flex justify-content-between align-items-center">
                                Proses Sekarang <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6">
                    <div class="card stat-card bg-success text-white h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                                <div>
                                    <h6 class="text-uppercase mb-1 small opacity-75 fw-bold">Total Pengurus</h6>
                                    <h2 class="display-5 fw-bold mb-0"><?= $stats['anggota'] ?></h2>
                                    <small class="opacity-75">Anggota aktif</small>
                                </div>
                                <div class="stat-icon-bg">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white bg-opacity-10 border-0">
                            <a href="index.php?action=ormawa_kelola_anggota" class="text-white text-decoration-none small d-flex justify-content-between align-items-center">
                                Lihat Data Anggota <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6">
                    <div class="card stat-card bg-warning text-dark h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center position-relative z-1">
                                <div>
                                    <h6 class="text-uppercase mb-1 small opacity-75 fw-bold">Program Kerja</h6>
                                    <h2 class="display-5 fw-bold mb-0"><?= $stats['progja'] ?></h2>
                                    <small class="opacity-75">Terencana tahun ini</small>
                                </div>
                                <div class="stat-icon-bg">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-black bg-opacity-10 border-0">
                            <a href="index.php?action=ormawa_progja" class="text-dark text-decoration-none small d-flex justify-content-between align-items-center">
                                Kelola Kegiatan <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                
                <div class="col-lg-5 order-lg-2">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h6 class="m-0 fw-bold text-dark"><i class="fas fa-bolt text-warning me-2"></i>Aksi Cepat</h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="action-btn-grid">
                                <a href="index.php?action=ormawa_seleksi" class="action-card-btn">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 mb-2">
                                        <i class="fas fa-check-double fa-lg"></i>
                                    </div>
                                    <span class="fw-semibold small">Seleksi Anggota</span>
                                </a>
                                
                                <a href="index.php?action=ormawa_progja" class="action-card-btn">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 mb-2">
                                        <i class="fas fa-plus-circle fa-lg"></i>
                                    </div>
                                    <span class="fw-semibold small">Tambah Progja</span>
                                </a>

                                <a href="index.php?action=ormawa_edit_profil" class="action-card-btn">
                                    <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 mb-2">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </div>
                                    <span class="fw-semibold small">Edit Profil</span>
                                </a>

                                <a href="index.php?action=ormawa_pesan" class="action-card-btn">
                                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 mb-2">
                                        <i class="fas fa-envelope fa-lg"></i>
                                    </div>
                                    <span class="fw-semibold small">Broadcast</span>
                                </a>
                            </div>
                            
                            <hr class="my-4">
                            
                            <a href="index.php?action=logout" class="btn btn-outline-danger w-100" onclick="return confirm('Yakin ingin keluar?')">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout Sistem
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 order-lg-1">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-dark"><i class="fas fa-chart-line text-primary me-2"></i>Status Pendaftaran</h6>
                            <a href="index.php?action=ormawa_seleksi" class="btn btn-sm btn-link text-decoration-none p-0">Lihat Semua</a>
                        </div>
                        <div class="card-body">
                            <?php if ($stats['pending'] > 0): ?>
                                <div class="alert alert-info border-0 d-flex align-items-center" role="alert">
                                    <i class="fas fa-info-circle fa-2x me-3"></i>
                                    <div>
                                        <strong>Perhatian!</strong> Terdapat <?= $stats['pending'] ?> calon anggota baru yang menunggu validasi berkas dan wawancara.
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <div class="mb-3 text-success opacity-50">
                                        <i class="fas fa-check-circle fa-4x"></i>
                                    </div>
                                    <h6 class="fw-bold text-muted">Semua Beres!</h6>
                                    <p class="text-muted small">Tidak ada pendaftaran pending saat ini.</p>
                                </div>
                            <?php endif; ?>

                            <div class="mt-4">
                                <h6 class="small fw-bold text-uppercase text-muted mb-3">Statistik Seleksi</h6>
                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" title="Diterima"></div>
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100" title="Ditolak"></div>
                                    <div class="progress-bar bg-secondary" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100" title="Pending"></div>
                                </div>
                                <div class="d-flex justify-content-between small text-muted">
                                    <span><i class="fas fa-circle text-success me-1"></i>Diterima</span>
                                    <span><i class="fas fa-circle text-danger me-1"></i>Ditolak</span>
                                    <span><i class="fas fa-circle text-secondary me-1"></i>Pending</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>