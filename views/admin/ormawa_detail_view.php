<?php include __DIR__ . '/../../views/templates/header.php'; ?>

<?php 
    // Validasi Data
    if (empty($organisasi) || !is_array($organisasi)) {
        echo '<div class="container-fluid py-4"><div class="alert alert-danger">Data Organisasi Tidak Ditemukan. <a href="index.php?action=login">Login Ulang</a></div></div>';
        include __DIR__ . '/../../views/templates/footer.php'; exit;
    }

    // Logic Tampilan Logo
    $logoFile = $organisasi['logo'] ?? '';
    $namaOrg = $organisasi['nama_organisasi'] ?? 'Organisasi'; 
    $finalLogoSrc = 'https://ui-avatars.com/api/?name=' . urlencode($namaOrg) . '&background=random&size=200';
    
    // Cek path logo (support path lama dan baru)
    if (!empty($logoFile)) {
        if (file_exists(__DIR__ . '/../../assets/images/profil/' . $logoFile)) {
            $finalLogoSrc = 'assets/images/profil/' . $logoFile . '?v=' . time();
        } elseif (file_exists('assets/images/profil/' . $logoFile)) {
            $finalLogoSrc = 'assets/images/profil/' . $logoFile . '?v=' . time();
        }
    }
?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../../views/templates/sidebar.php'; ?>
        
        <div class="col-md-9 ms-sm-auto col-lg-9 px-md-4 py-4">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="h3 fw-bold mb-0">Identitas Organisasi</h1>
                    <p class="text-muted small">Profil lengkap dan informasi publik organisasi Anda.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="index.php?action=ormawa_edit_profil" class="btn btn-primary btn-sm rounded-pill shadow-sm px-3">
                        <i class="fas fa-edit me-2"></i>Edit Data
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="bg-light p-4 text-center position-relative border-bottom">
                    <div class="d-inline-block position-relative">
                        <div class="bg-white p-1 rounded-circle shadow-sm d-flex align-items-center justify-content-center" 
                             style="width: 140px; height: 140px;"> 
                                 <img src="<?php echo htmlspecialchars($finalLogoSrc); ?>" 
                                 alt="Logo Organisasi" 
                                 class="rounded-circle"
                                 style="width: 100%; height: 100%; object-fit: cover; aspect-ratio: 1/1;"> 
                        </div>
                    </div>
                    
                    <h3 class="fw-bold mt-3 mb-1 text-dark"><?php echo htmlspecialchars($namaOrg); ?></h3>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">
                        <?php echo htmlspecialchars($organisasi['jenis_organisasi'] ?? 'Organisasi Umum'); ?>
                    </span>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-white border rounded-3 h-100 shadow-sm-hover">
                                <h6 class="text-muted fw-bold text-uppercase small mb-2"><i class="far fa-calendar-alt me-2 text-warning"></i>Tanggal Berdiri</h6>
                                <p class="fs-5 fw-medium text-dark mb-0">
                                    <?php echo !empty($organisasi['tanggal_berdiri']) ? date('d F Y', strtotime($organisasi['tanggal_berdiri'])) : '-'; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="p-3 bg-white border rounded-3 h-100 shadow-sm-hover">
                                <h6 class="text-muted fw-bold text-uppercase small mb-2"><i class="fas fa-toggle-on me-2 text-success"></i>Status</h6>
                                <p class="fs-5 fw-medium text-success mb-0">
                                    Aktif
                                </p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-4 bg-light rounded-3 border">
                                <h6 class="text-primary fw-bold text-uppercase small mb-3 border-bottom pb-2">Deskripsi Organisasi</h6>
                                <div class="text-dark opacity-75">
                                    <?php echo nl2br(htmlspecialchars($organisasi['deskripsi'] ?? 'Belum ada deskripsi.')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-info bg-opacity-10">
                                <div class="card-body">
                                    <h6 class="text-info fw-bold text-uppercase small mb-3"><i class="fas fa-eye me-2"></i>Visi</h6>
                                    <p class="card-text text-dark">
                                        <?php echo nl2br(htmlspecialchars($organisasi['visi'] ?? '-')); ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-success bg-opacity-10">
                                <div class="card-body">
                                    <h6 class="text-success fw-bold text-uppercase small mb-3"><i class="fas fa-bullseye me-2"></i>Misi</h6>
                                    <p class="card-text text-dark">
                                        <?php echo nl2br(htmlspecialchars($organisasi['misi'] ?? '-')); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .shadow-sm-hover { transition: box-shadow 0.2s; }
    .shadow-sm-hover:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.05)!important; }
</style>

<?php include __DIR__ . '/../../views/templates/footer.php'; ?>