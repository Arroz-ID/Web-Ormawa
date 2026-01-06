<?php include __DIR__ . '/../templates/header.php'; ?>

<?php 
    $logoFile = $organisasi['logo'] ?? '';
    $finalLogoSrc = null;
    
    // Cek di folder profil (Sistem Baru)
    if (!empty($logoFile) && file_exists('assets/images/profil/' . $logoFile)) {
        $finalLogoSrc = 'assets/images/profil/' . $logoFile . '?v=' . time();
    } 
    // Cek di folder images (Data Lama)
    elseif (!empty($logoFile) && file_exists('assets/images/' . $logoFile)) {
        $finalLogoSrc = 'assets/images/' . $logoFile . '?v=' . time();
    }
?>

<div class="position-relative bg-dark text-white" style="min-height: 350px;">
    <div class="position-absolute top-0 start-0 w-100 h-100" 
         style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.8)), url('assets/images/gedung.jpg') center/cover;">
    </div>

    <div class="container position-relative h-100 d-flex align-items-end pb-5 pt-5">
        <div class="d-flex align-items-center flex-wrap gap-4 mt-5">
            <div class="bg-white p-1 rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
                 style="width: 130px; height: 130px;">
                <?php if ($finalLogoSrc): ?>
                    <img src="<?php echo htmlspecialchars($finalLogoSrc); ?>" 
                         alt="Logo" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <i class="fas fa-users fa-3x text-secondary"></i>
                <?php endif; ?>
            </div>

            <div>
                <span class="badge bg-warning text-dark mb-2 fw-bold px-3">
                    <?php echo htmlspecialchars($organisasi['jenis_organisasi']); ?>
                </span>
                <h1 class="fw-bold display-5 mb-1"><?php echo htmlspecialchars($organisasi['nama_organisasi']); ?></h1>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-calendar-alt me-2"></i>Berdiri sejak: 
                    <?php echo !empty($organisasi['tanggal_berdiri']) ? date('d F Y', strtotime($organisasi['tanggal_berdiri'])) : '-'; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5 mt-n5 position-relative" style="z-index: 2;">
    <div class="row g-4">
        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="min-height: 600px;">
                <div class="card-header bg-white border-bottom p-0">
                    <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active py-3 fw-bold" data-bs-toggle="tab" data-bs-target="#tab-home">
                                <i class="fas fa-home me-2"></i>Beranda
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 fw-bold" data-bs-toggle="tab" data-bs-target="#tab-visimisi">
                                <i class="fas fa-bullseye me-2"></i>Visi & Misi
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 fw-bold" data-bs-toggle="tab" data-bs-target="#tab-struktur">
                                <i class="fas fa-sitemap me-2"></i>Struktur
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 fw-bold" data-bs-toggle="tab" data-bs-target="#tab-galeri">
                                <i class="fas fa-images me-2"></i>Galeri
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content">
                        
                        <div class="tab-pane fade show active" id="tab-home">
                            <h5 class="fw-bold text-primary mb-3">Tentang Kami</h5>
                            <div class="text-muted lh-lg mb-4">
                                <?php echo nl2br(htmlspecialchars($organisasi['deskripsi'] ?? 'Deskripsi organisasi belum ditambahkan.')); ?>
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded bg-light text-center h-100">
                                        <h3 class="fw-bold text-primary mb-0"><?php echo isset($organisasi['jumlah_divisi']) ? $organisasi['jumlah_divisi'] : count($divisi); ?></h3>
                                        <small class="text-muted text-uppercase fw-bold">Divisi</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded bg-light text-center h-100">
                                        <h3 class="fw-bold text-success mb-0"><?php echo isset($organisasi['jumlah_pengurus']) ? $organisasi['jumlah_pengurus'] : count($kepengurusan); ?></h3>
                                        <small class="text-muted text-uppercase fw-bold">Pengurus Aktif</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-visimisi">
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                    <h5 class="fw-bold m-0">VISI</h5>
                                </div>
                                <div class="p-4 bg-light rounded-3 border-start border-5 border-primary shadow-sm fst-italic text-muted">
                                    "<?php echo nl2br(htmlspecialchars($organisasi['visi'] ?? 'Visi belum ditambahkan.')); ?>"
                                </div>
                            </div>

                            <div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-list-ul"></i>
                                    </div>
                                    <h5 class="fw-bold m-0">MISI</h5>
                                </div>
                                <div class="p-4 bg-light rounded-3 border-start border-5 border-success shadow-sm text-muted">
                                    <?php echo nl2br(htmlspecialchars($organisasi['misi'] ?? 'Misi belum ditambahkan.')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-struktur">
                            <?php if (empty($kepengurusan)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-users-slash fa-3x text-muted opacity-25 mb-3"></i>
                                    <p class="text-muted">Data kepengurusan belum tersedia.</p>
                                </div>
                            <?php else: ?>
                                <div class="row g-3">
                                    <?php foreach ($kepengurusan as $k): ?>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center border rounded p-3 hover-shadow bg-white h-100">
                                                <div class="flex-shrink-0">
                                                    <?php 
                                                        $fotoP = $k['foto_profil'] ?? '';
                                                        $imgSrc = 'https://ui-avatars.com/api/?name='.urlencode($k['nama_lengkap']).'&background=random';
                                                        if (!empty($fotoP) && file_exists('assets/images/profil/' . $fotoP)) {
                                                            $imgSrc = 'assets/images/profil/' . $fotoP . '?v=' . time();
                                                        }
                                                    ?>
                                                    <img src="<?php echo htmlspecialchars($imgSrc); ?>" 
                                                         class="rounded-circle border" style="width: 50px; height: 50px; object-fit: cover;">
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-0 fw-bold text-dark"><?php echo htmlspecialchars($k['nama_lengkap']); ?></h6>
                                                    <small class="text-primary fw-bold"><?php echo htmlspecialchars($k['nama_jabatan']); ?></small>
                                                    <div class="small text-muted"><?php echo htmlspecialchars($k['nama_divisi'] ?? 'Pengurus Inti'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="tab-galeri">
                            <?php if (empty($kegiatan)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-camera fa-3x text-muted opacity-25 mb-3"></i>
                                    <p class="text-muted">Belum ada dokumentasi kegiatan.</p>
                                </div>
                            <?php else: ?>
                                <div class="row g-3">
                                    <?php foreach ($kegiatan as $kg): ?>
                                        <div class="col-md-6">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <?php if(!empty($kg['foto_kegiatan'])): ?>
                                                    <img src="assets/uploads/kegiatan/<?php echo $kg['foto_kegiatan']; ?>" class="card-img-top" alt="Kegiatan" style="height: 180px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                                        <i class="fas fa-image text-muted fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="card-body p-3">
                                                    <h6 class="card-title fw-bold text-dark mb-1"><?php echo htmlspecialchars($kg['nama_kegiatan']); ?></h6>
                                                    <small class="text-muted"><i class="far fa-clock me-1"></i> <?php echo date('d M Y', strtotime($kg['tanggal_kegiatan'])); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white sticky-top" style="top: 100px; z-index: 1;">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-3">Bergabunglah Bersama Kami!</h5>
                    <p class="text-muted small mb-4">
                        Jadilah bagian dari perubahan. Kembangkan potensi diri dan perluas relasi dengan bergabung di <?php echo htmlspecialchars($organisasi['nama_organisasi']); ?>.
                    </p>

                    <?php if (isset($_SESSION['anggota_id'])): ?>
                        <a href="index.php?action=daftar_kepengurusan&organisasi_id=<?php echo $organisasi['organisasi_id']; ?>" 
                           class="btn btn-primary w-100 py-2 rounded-pill fw-bold hover-scale shadow-sm mb-2">
                            <i class="fas fa-file-signature me-2"></i>Daftar Sekarang
                        </a>
                        <small class="text-muted d-block mt-2">Pastikan profil Anda sudah lengkap.</small>

                    <?php elseif (isset($_SESSION['admin_id'])): ?>
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info">
                            <i class="fas fa-info-circle me-1"></i> Anda login sebagai Admin.
                        </div>

                    <?php else: ?>
                        <a href="index.php?action=login" class="btn btn-outline-primary w-100 py-2 rounded-pill fw-bold mb-2">
                            Login untuk Mendaftar
                        </a>
                        <div class="text-muted small">Belum punya akun? <a href="index.php?action=register">Daftar disini</a></div>
                    <?php endif; ?>

                    <hr class="my-4">
                    
                    <div class="text-start">
                        <h6 class="fw-bold mb-3"><i class="fas fa-address-card me-2 text-primary"></i>Kontak Kami</h6>
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fab fa-instagram text-danger me-3 fa-lg" style="width: 20px;"></i>
                                <span class="text-dark">@<?php echo str_replace(' ', '', strtolower($organisasi['nama_organisasi'])); ?>_poltesa</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-envelope text-warning me-3 fa-lg" style="width: 20px;"></i>
                                <span class="text-dark">ormawa@poltesa.ac.id</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .nav-tabs .nav-link { color: #6c757d; border: none; border-bottom: 3px solid transparent; }
    .nav-tabs .nav-link:hover { color: #0d6efd; background: transparent; }
    .nav-tabs .nav-link.active { color: #0d6efd; background: transparent; border-bottom: 3px solid #0d6efd; }
    .hover-shadow:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; transition: all 0.3s; }
    .hover-scale:hover { transform: scale(1.02); transition: transform 0.2s; }
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>