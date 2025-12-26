<?php include __DIR__ . '/../templates/header.php'; ?>

<style>
    /* Styling Banner Selamat Datang */
    .welcome-banner {
        background-color: #0d6efd; /* Primary Blue */
        color: white;
        border-radius: 6px;
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    
    /* Styling Foto Profil di Sidebar */
    .profile-img-container {
        width: 120px;
        height: 120px;
        margin: 0 auto 15px auto;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .profile-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Styling Label Detail Profil */
    .profile-label {
        font-weight: 700;
        font-size: 0.85rem;
        color: #333;
    }
    .profile-value {
        font-size: 0.9rem;
        color: #555;
        margin-bottom: 0.8rem;
    }
</style>

<div class="container py-4">

    <div class="welcome-banner shadow-sm">
        <h2 class="fw-bold mb-1">Selamat Datang, <?php echo htmlspecialchars($anggota['nama_lengkap']); ?>!</h2>
        <p class="mb-0 opacity-75">Ini adalah ringkasan aktivitas dan keanggotaan organisasi Anda.</p>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-8">
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-user-tie me-2"></i>Kepengurusan Aktif
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($kepengurusan)): ?>
                        <div class="text-muted py-3">
                            Anda belum memiliki jabatan di kepengurusan manapun.
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($kepengurusan as $org): ?>
                                <div class="list-group-item px-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 bg-light rounded p-2 text-primary">
                                            <i class="fas fa-sitemap fa-lg"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($org['nama_organisasi']); ?></h6>
                                            <p class="mb-0 small text-muted">
                                                <span class="badge bg-primary me-1"><?php echo htmlspecialchars($org['nama_jabatan']); ?></span>
                                                <?php if($org['nama_divisi']) echo " • " . htmlspecialchars($org['nama_divisi']); ?>
                                            </p>
                                        </div>
                                        <span class="badge bg-success rounded-pill">Aktif</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-history me-2"></i>Pendaftaran Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($pendaftaran)): ?>
                        <div class="text-muted py-3">
                            Belum ada riwayat pendaftaran.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Organisasi</th>
                                        <th>Posisi</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($pendaftaran, 0, 5) as $daftar): ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo htmlspecialchars($daftar['nama_organisasi']); ?></td>
                                            <td class="small">
                                                <?php 
                                                    echo $daftar['jenis'] == 'kepengurusan' 
                                                        ? htmlspecialchars($daftar['nama_jabatan']) 
                                                        : htmlspecialchars($daftar['nama_divisi']); 
                                                ?>
                                            </td>
                                            <td class="small text-muted">
                                                <?php echo date('d M Y', strtotime($daftar['tanggal_daftar'])); ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $statusClass = 'bg-secondary';
                                                    if($daftar['status_pendaftaran'] == 'approved') $statusClass = 'bg-success';
                                                    if($daftar['status_pendaftaran'] == 'rejected') $statusClass = 'bg-danger';
                                                    if($daftar['status_pendaftaran'] == 'pending') $statusClass = 'bg-warning text-dark';
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?> rounded-pill">
                                                    <?php echo ucfirst($daftar['status_pendaftaran']); ?>
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
            <div class="card shadow-sm border-0 sticky-top" style="top: 90px; z-index: 1;">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-user-circle me-2"></i>Profil Anggota
                    </h5>
                </div>
                <div class="card-body p-4">
                    
                    <div class="text-center mb-4">
                        <div class="profile-img-container bg-light d-flex align-items-center justify-content-center">
                            <?php if (!empty($anggota['foto_profil'])): ?>
                                <img src="assets/images/<?php echo htmlspecialchars($anggota['foto_profil']); ?>" class="profile-img" alt="Foto Profil">
                            <?php else: ?>
                                <i class="fas fa-user fa-4x text-secondary"></i>
                            <?php endif; ?>
                        </div>
                        <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($anggota['nama_lengkap']); ?></h5>
                        <p class="text-muted small mb-0">Mahasiswa Aktif</p>
                    </div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <div class="profile-label">NIM</div>
                        <div class="profile-value fw-bold text-dark">
                            <?php echo htmlspecialchars($anggota['nim']); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="profile-label">Email</div>
                        <div class="profile-value text-truncate">
                            <?php echo htmlspecialchars($anggota['email']); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="profile-label">Jurusan</div>
                        <div class="profile-value">
                            <?php echo htmlspecialchars($anggota['jurusan']); ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="profile-label">Fakultas</div>
                        <div class="profile-value">
                            <?php echo htmlspecialchars($anggota['fakultas']); ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="profile-label">Angkatan</div>
                        <div class="profile-value">
                            <?php echo htmlspecialchars($anggota['angkatan']); ?>
                        </div>
                    </div>

                    <div class="d-grid">
                        <a href="index.php?action=profile" class="btn btn-primary fw-bold">
                            <i class="fas fa-edit me-2"></i>Edit Profil
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>