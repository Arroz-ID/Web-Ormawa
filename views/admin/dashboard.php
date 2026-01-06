<?php include __DIR__ . '/../templates/header.php'; ?>

<style>
    .card-stat {
        border-left: 5px solid;
        transition: transform 0.2s;
    }
    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .border-blue { border-color: #0d6efd; }
    .border-green { border-color: #198754; }
    .border-yellow { border-color: #ffc107; }
    .border-gray { border-color: #6c757d; }
    
    .btn-quick-action {
        height: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        transition: all 0.3s;
        /* Pastikan elemen ini bisa diklik dan pointernya muncul */
        cursor: pointer; 
        position: relative;
        z-index: 10;
    }
    .btn-quick-action:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        text-decoration: none; /* Hilangkan garis bawah saat hover */
    }
    .bg-dashboard-header {
        background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
    }
</style>

<div class="container-fluid bg-light min-vh-100 pb-5">
    <div class="container py-4">
        
        <div class="card shadow-sm border-0 mb-4 overflow-hidden">
            <div class="card-body bg-dashboard-header p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="fas fa-tachometer-alt fa-2x text-white"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">Admin Dashboard</h2>
                        <p class="mb-0 opacity-75">Selamat datang di panel administrasi ORMAWA POLTESA</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 card-stat border-blue h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-primary fw-bold small mb-1 text-uppercase">Total Organisasi</p>
                                <h3 class="mb-0 fw-bold text-dark"><?php echo isset($total_organisasi) ? $total_organisasi : 0; ?></h3>
                            </div>
                            <i class="fas fa-university fa-2x text-black-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 card-stat border-green h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-success fw-bold small mb-1 text-uppercase">Total Anggota</p>
                                <h3 class="mb-0 fw-bold text-dark"><?php echo isset($total_anggota) ? $total_anggota : 0; ?></h3>
                            </div>
                            <i class="fas fa-users fa-2x text-black-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 card-stat border-yellow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-warning fw-bold small mb-1 text-uppercase">Pendaftaran Pending</p>
                                <h3 class="mb-0 fw-bold text-dark"><?php echo isset($total_pending) ? $total_pending : 0; ?></h3>
                            </div>
                            <i class="fas fa-clock fa-2x text-black-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 card-stat border-gray h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-secondary fw-bold small mb-1 text-uppercase">Aktivitas Hari Ini</p>
                                <h3 class="mb-0 fw-bold text-dark">0</h3>
                            </div>
                            <i class="fas fa-chart-line fa-2x text-black-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-warning bg-opacity-75 text-dark fw-bold border-0 py-3">
                        <i class="fas fa-clock me-2"></i> Pendaftaran Menunggu Persetujuan
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center text-center p-5">
                        <?php if (isset($total_pending) && $total_pending > 0): ?>
                            <div class="w-100">
                                <div class="alert alert-info shadow-sm">Ada <?php echo $total_pending; ?> pendaftaran baru menunggu aksi Anda.</div>
                                <a href="kelola_pendaftaran.php" class="btn btn-primary px-4">Lihat Daftar</a>
                            </div>
                        <?php else: ?>
                            <div>
                                <div class="mb-3 text-success">
                                    <i class="fas fa-check-circle fa-4x opacity-50"></i>
                                </div>
                                <h5 class="text-muted">Tidak ada pendaftaran yang menunggu persetujuan</h5>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-primary text-white fw-bold border-0 py-3">
                        <i class="fas fa-bolt me-2"></i> Aksi Cepat
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            
                            <div class="col-6">
                                <a href="kelola_organisasi.php" class="card text-decoration-none btn-quick-action border-primary text-primary">
                                    <i class="fas fa-university fa-2x mb-2"></i>
                                    <span class="small">Kelola Organisasi</span>
                                </a>
                            </div>

                            <div class="col-6">
                                <a href="kelola_pendaftaran.php" class="card text-decoration-none btn-quick-action border-success text-success">
                                    <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                                    <span class="small">Kelola Pendaftaran</span>
                                </a>
                            </div>

                            <div class="col-6">
                                <a href="tambah_organisasi.php" class="card text-decoration-none btn-quick-action border-warning text-warning">
                                    <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                    <span class="small">Tambah Organisasi</span>
                                </a>
                            </div>

                            <div class="col-6">
                                <a href="kelola_pengguna.php" class="card text-decoration-none btn-quick-action border-info text-info">
                                    <i class="fas fa-users-cog fa-2x mb-2"></i>
                                    <span class="small">Kelola Pengguna</span>
                                </a>
                            </div>

                            <div class="col-12">
                                <a href="#" class="card text-decoration-none btn-quick-action border-dark text-dark" style="height: 60px; flex-direction: row;">
                                    <i class="fas fa-user-plus me-2"></i>
                                    <span class="small">Tambah Anggota (Coming Soon)</span>
                                </a>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2 text-primary"></i>Log Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Waktu</th>
                                        <th>Pengguna</th>
                                        <th>Role</th>
                                        <th>Aktivitas</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Pengecekan aman agar tidak error jika Database belum diload
                                    if (class_exists('Database')) {
                                        try {
                                            $conn = Database::getInstance()->getConnection();
                                            $logs = $conn->query("SELECT * FROM log_aktivitas ORDER BY created_at DESC LIMIT 15")->fetchAll();
                                        } catch (Exception $e) {
                                            $logs = [];
                                        }
                                    } else {
                                        $logs = [];
                                    }
                                    
                                    if (empty($logs)):
                                    ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Belum ada log aktivitas tercatat.</td>
                                        </tr>
                                    <?php 
                                    else:
                                        foreach ($logs as $log):
                                            $bgBadge = 'bg-primary';
                                            if (stripos($log['aktivitas'], 'Hapus') !== false) $bgBadge = 'bg-danger';
                                            if (stripos($log['aktivitas'], 'Update') !== false) $bgBadge = 'bg-warning text-dark';
                                            if (stripos($log['aktivitas'], 'Daftar') !== false) $bgBadge = 'bg-success';
                                    ?>
                                        <tr>
                                            <td class="ps-4 small text-muted"><?php echo date('d/m/Y H:i', strtotime($log['created_at'])); ?></td>
                                            <td class="fw-bold">ID: <?php echo $log['user_id']; ?></td>
                                            <td>
                                                <span class="badge <?php echo $bgBadge; ?> rounded-pill">
                                                    <?php echo strtoupper($log['role']); ?>
                                                </span>
                                            </td>
                                            <td class="fw-semibold"><?php echo htmlspecialchars($log['aktivitas']); ?></td>
                                            <td class="small text-muted"><?php echo htmlspecialchars($log['detail'] ?? '-'); ?></td>
                                        </tr>
                                    <?php 
                                        endforeach; 
                                    endif;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>