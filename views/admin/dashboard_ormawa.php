<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container py-5">
    
    <div class="card shadow-sm border-0 mb-4 bg-primary text-white">
        <div class="card-body p-4">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                    <i class="fas fa-university fa-2x text-white"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0">Dashboard <?php echo htmlspecialchars($orgDetail['nama_organisasi']); ?></h2>
                    <p class="mb-0 opacity-75">Selamat datang, Admin. Kelola organisasi Anda di sini.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1 text-uppercase fw-bold">Total Pengurus</p>
                            <h2 class="fw-bold text-primary mb-0"><?php echo $stats['total_pengurus']; ?></h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-3">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1 text-uppercase fw-bold">Pendaftaran Pending</p>
                            <h2 class="fw-bold text-warning mb-0"><?php echo $stats['pendaftar_pending']; ?></h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning rounded p-3">
                            <i class="fas fa-user-clock fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1 text-uppercase fw-bold">Total Pelamar</p>
                            <h2 class="fw-bold text-success mb-0"><?php echo $stats['total_pendaftar']; ?></h2>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded p-3">
                            <i class="fas fa-file-alt fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-cogs me-2"></i>Menu Pengelolaan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="index.php?action=ormawa_kelola_anggota" class="btn btn-outline-primary w-100 p-3 text-start d-flex align-items-center">
                                <i class="fas fa-users-cog fa-2x me-3"></i>
                                <div>
                                    <div class="fw-bold">Kelola Anggota</div>
                                    <div class="small text-muted">Lihat dan atur pengurus aktif</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="index.php?action=ormawa_seleksi" class="btn btn-outline-success w-100 p-3 text-start d-flex align-items-center">
                                <i class="fas fa-clipboard-check fa-2x me-3"></i>
                                <div>
                                    <div class="fw-bold">Seleksi Pendaftaran</div>
                                    <div class="small text-muted">Terima atau tolak pelamar</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="index.php?action=ormawa_edit_profil" class="btn btn-outline-warning text-dark w-100 p-3 text-start d-flex align-items-center">
                                <i class="fas fa-edit fa-2x me-3"></i>
                                <div>
                                    <div class="fw-bold">Edit Profil Organisasi</div>
                                    <div class="small text-muted">Ubah deskripsi, visi, misi</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="index.php?action=ormawa_kelola_divisi" class="btn btn-outline-info text-dark w-100 p-3 text-start d-flex align-items-center">
                                <i class="fas fa-sitemap fa-2x me-3"></i>
                                <div>
                                    <div class="fw-bold">Kelola Divisi</div>
                                    <div class="small text-muted">Tambah atau edit divisi</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Aktivitas Terakhir Anda</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php
                        $conn = Database::getInstance()->getConnection();
                        $logs = $conn->prepare("SELECT * FROM log_aktivitas WHERE user_id = :uid ORDER BY created_at DESC LIMIT 5");
                        $logs->execute([':uid' => $_SESSION['admin_id']]);
                        $dataLogs = $logs->fetchAll();

                        if($dataLogs):
                            foreach($dataLogs as $log):
                        ?>
                            <li class="list-group-item small py-3">
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($log['aktivitas']); ?></div>
                                <div class="text-muted" style="font-size: 0.85em;">
                                    <?php echo htmlspecialchars($log['detail'] ?? ''); ?>
                                </div>
                                <div class="text-end text-muted" style="font-size: 0.75em;">
                                    <?php echo date('d M H:i', strtotime($log['created_at'])); ?>
                                </div>
                            </li>
                        <?php endforeach; else: ?>
                            <li class="list-group-item text-center py-4 text-muted small">Belum ada aktivitas.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>