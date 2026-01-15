<?php include __DIR__ . '/../templates/header.php'; ?>

<style>
    /* Styling Dashboard Modern */
    .welcome-banner {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
        border-radius: 15px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background-image: url('assets/images/pattern.png'); /* Opsional */
        opacity: 0.1;
    }
    .profile-card {
        border-radius: 15px;
        overflow: hidden;
    }
    .profile-img-container {
        width: 110px;
        height: 110px;
        margin: -55px auto 15px auto;
        border-radius: 50%;
        overflow: hidden;
        border: 5px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        background-color: #f8f9fa;
        position: relative;
        z-index: 2;
    }
    .profile-header-bg {
        height: 100px;
        background: #e9ecef;
    }
    
    /* Style Pesan Broadcast */
    .message-card {
        border-left: 4px solid #0d6efd;
        background: #f8f9fa;
        transition: transform 0.2s;
    }
    .message-card:hover { 
        transform: translateX(5px); 
        background: #fff; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
    }
</style>

<div class="container py-4">

    <div class="welcome-banner shadow-sm">
        <div class="row align-items-center position-relative" style="z-index: 1;">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-2">Halo, <?php echo htmlspecialchars($anggota['nama_lengkap'] ?? 'Mahasiswa'); ?>! 👋</h2>
                <p class="mb-0 opacity-90 fs-5">Selamat datang di Sistem Informasi Ormawa Poltesa.</p>
                <p class="mb-0 small opacity-75 mt-2">Kelola aktivitas organisasi dan pendaftaran Anda di sini.</p>
            </div>
            <div class="col-lg-4 text-end d-none d-lg-block">
                <i class="fas fa-users fa-5x opacity-25"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-8">
            
            <?php if (!empty($pesan_broadcast)): ?>
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-bullhorn me-2 text-danger"></i>Pengumuman Terbaru
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($pesan_broadcast as $pesan): ?>
                            <div class="list-group-item p-4 message-card mb-2 mx-3 rounded border">
                                <div class="d-flex w-100 justify-content-between mb-2">
                                    <h6 class="mb-1 fw-bold text-primary">
                                        <i class="fas fa-envelope-open-text me-2"></i><?php echo htmlspecialchars($pesan['judul']); ?>
                                    </h6>
                                    <small class="text-muted">
                                        <?php echo date('d M Y, H:i', strtotime($pesan['tanggal_kirim'])); ?> WIB
                                    </small>
                                </div>
                                <p class="mb-2 text-dark small" style="white-space: pre-line;"><?php echo htmlspecialchars($pesan['isi_pesan']); ?></p>
                                <small class="text-muted fst-italic">
                                    Dari: <strong><?php echo htmlspecialchars($pesan['nama_organisasi']); ?></strong>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-id-card-clip me-2 text-primary"></i>Kepengurusan Saat Ini
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($kepengurusan)): ?>
                        <div class="text-center py-5 text-muted">
                            <img src="assets/images/no-data.svg" alt="" style="width: 80px; opacity: 0.5; margin-bottom: 15px;" onerror="this.style.display='none'">
                            <p class="mb-2">Anda belum terdaftar aktif di organisasi manapun.</p>
                            <a href="index.php?action=organisasi" class="btn btn-sm btn-outline-primary rounded-pill">
                                Cari Organisasi
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($kepengurusan as $org): ?>
                                <div class="list-group-item p-4 border-light">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3 text-primary">
                                            <i class="fas fa-building fa-lg"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1 fw-bold text-dark"><?php echo htmlspecialchars($org['nama_organisasi']); ?></h6>
                                            <div class="d-flex align-items-center text-muted small">
                                                <span class="badge bg-primary me-2"><?php echo htmlspecialchars($org['nama_jabatan']); ?></span>
                                                <?php if(!empty($org['nama_divisi'])): ?>
                                                    <span class="me-2">•</span>
                                                    <span><?php echo htmlspecialchars($org['nama_divisi']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Aktif</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-history me-2 text-warning"></i>Pendaftaran Terbaru
                    </h5>
                    <a href="index.php?action=riwayat" class="btn btn-sm btn-light text-muted small">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($pendaftaran)): ?>
                        <div class="text-center py-5 text-muted">
                            <p class="mb-0">Belum ada riwayat pendaftaran.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Organisasi</th>
                                        <th>Posisi</th>
                                        <th>Tanggal</th>
                                        <th class="text-end pe-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($pendaftaran, 0, 5) as $daftar): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark">
                                                <?php echo htmlspecialchars($daftar['nama_organisasi'] ?? '-'); ?>
                                            </td>
                                            <td class="small text-muted">
                                                <?php 
                                                    $posisi = !empty($daftar['nama_jabatan']) ? $daftar['nama_jabatan'] : ($daftar['nama_divisi'] ?? 'Anggota');
                                                    echo htmlspecialchars($posisi);
                                                ?>
                                            </td>
                                            <td class="small text-muted">
                                                <?php 
                                                    $tgl = $daftar['tanggal_daftar'] ?? date('Y-m-d');
                                                    echo date('d M Y', strtotime($tgl)); 
                                                ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <?php 
                                                    $status = strtolower($daftar['status_pendaftaran'] ?? 'pending');
                                                    $badgeClass = 'bg-secondary';
                                                    $statusText = ucfirst($status);

                                                    if(in_array($status, ['diterima', 'approved'])) {
                                                        $badgeClass = 'bg-success';
                                                        $statusText = 'Diterima';
                                                    } elseif(in_array($status, ['ditolak', 'rejected'])) {
                                                        $badgeClass = 'bg-danger';
                                                        $statusText = 'Ditolak';
                                                    } elseif(in_array($status, ['wawancara', 'interview'])) {
                                                        $badgeClass = 'bg-info text-dark';
                                                        $statusText = 'Wawancara';
                                                    } else {
                                                        $badgeClass = 'bg-warning text-dark';
                                                        $statusText = 'Menunggu';
                                                    }
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?> rounded-pill">
                                                    <?php echo $statusText; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 profile-card sticky-top" style="top: 90px; z-index: 1;">
                <div class="profile-header-bg"></div>
                
                <div class="card-body text-center pt-0 px-4 pb-4">
                    <div class="profile-img-container d-flex align-items-center justify-content-center">
                        <?php 
                            $foto = $anggota['foto_profil'] ?? '';
                            $pathFoto = 'assets/images/profil/' . $foto;
                            if (!empty($foto) && file_exists($pathFoto)): 
                        ?>
                            <img src="<?php echo htmlspecialchars($pathFoto) . '?v=' . time(); ?>" class="w-100 h-100 object-fit-cover" alt="Profil">
                        <?php else: ?>
                            <i class="fas fa-user fa-3x text-secondary"></i>
                        <?php endif; ?>
                    </div>

                    <h5 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($anggota['nama_lengkap']); ?></h5>
                    <p class="text-muted small mb-3"><?php echo htmlspecialchars($anggota['nim']); ?></p>

                    <div class="d-grid gap-2 mb-4">
                        <a href="index.php?action=profile" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">
                            <i class="fas fa-user-edit me-2"></i>Edit Profil
                        </a>
                    </div>

                    <ul class="list-group list-group-flush text-start small">
                        <li class="list-group-item d-flex justify-content-between px-0 py-3 border-bottom">
                            <span class="text-muted">Jurusan</span>
                            <span class="fw-bold text-end w-50"><?php echo htmlspecialchars($anggota['jurusan']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0 py-3 border-bottom">
                            <span class="text-muted">Prodi</span>
                            <span class="fw-bold text-end w-50"><?php echo htmlspecialchars($anggota['prodi'] ?? '-'); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0 py-3">
                            <span class="text-muted">Angkatan</span>
                            <span class="fw-bold"><?php echo htmlspecialchars($anggota['angkatan']); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>