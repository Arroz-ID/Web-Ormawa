<?php include __DIR__ . '/../../views/templates/header.php'; ?>

<?php 
    // 1. PERBAIKAN: Cek apakah data organisasi valid/ada
    // Mencegah error "Trying to access array offset on value of type bool"
    if (empty($organisasi) || !is_array($organisasi)) {
        echo '<div class="container py-5">
                <div class="alert alert-danger shadow-sm border-0 rounded-3 p-4">
                    <h4 class="alert-heading fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Data Tidak Ditemukan</h4>
                    <p class="mb-0">Maaf, profil organisasi tidak dapat dimuat. Hal ini mungkin terjadi karena:</p>
                    <ul class="mb-3">
                        <li>Sesi login Anda telah kedaluwarsa.</li>
                        <li>Akun admin Anda tidak terhubung ke organisasi manapun.</li>
                        <li>Data organisasi telah dihapus.</li>
                    </ul>
                    <hr>
                    <a href="index.php?action=login" class="btn btn-danger px-4 rounded-pill">Login Ulang</a>
                </div>
              </div>';
        include __DIR__ . '/../../views/templates/footer.php';
        exit; // Hentikan script agar tidak lanjut ke bawah
    }

    // 2. PERBAIKAN: Ambil data dengan aman (Null Coalescing)
    $logoFile = $organisasi['logo'] ?? '';
    
    // Mencegah error Deprecated urlencode(null)
    $namaOrg = $organisasi['nama_organisasi'] ?? 'Organisasi'; 
    
    // Default avatar jika logo kosong
    $finalLogoSrc = 'https://ui-avatars.com/api/?name=' . urlencode($namaOrg) . '&background=random&size=200';
    
    // Cek file logo fisik
    // Menggunakan path absolute/relative yang benar dari root project
    if (!empty($logoFile) && file_exists(__DIR__ . '/../../assets/images/profil/' . $logoFile)) {
        $finalLogoSrc = 'assets/images/profil/' . $logoFile . '?v=' . time();
    } elseif (!empty($logoFile) && file_exists('assets/images/profil/' . $logoFile)) {
        // Fallback cek path standar
        $finalLogoSrc = 'assets/images/profil/' . $logoFile . '?v=' . time();
    }
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="bg-primary bg-gradient text-white p-5 text-center position-relative">
                    
                    <div class="position-relative d-inline-block mx-auto mb-3">
                        <div class="bg-white p-1 rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
                             style="width: 160px; height: 160px;"> 
                                 <img src="<?php echo htmlspecialchars($finalLogoSrc); ?>" 
                                 alt="Logo Organisasi" 
                                 class="rounded-circle"
                                 style="width: 100%; height: 100%; object-fit: cover; aspect-ratio: 1/1;"> 
                        </div>
                    </div>

                    <h2 class="fw-bold mb-1"><?php echo htmlspecialchars($namaOrg); ?></h2>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">
                        <?php echo htmlspecialchars($organisasi['jenis_organisasi'] ?? 'Umum'); ?>
                    </span>
                    
                    <div class="mt-4">
                        <a href="index.php?action=ormawa_edit_profil" class="btn btn-light text-primary fw-bold rounded-pill px-4 shadow-sm hover-scale">
                            <i class="fas fa-edit me-2"></i>Edit Profil
                        </a>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold text-uppercase small mb-2">Tanggal Berdiri</h6>
                            <p class="fs-5 fw-medium text-dark">
                                <i class="far fa-calendar-alt me-2 text-primary"></i>
                                <?php echo !empty($organisasi['tanggal_berdiri']) ? date('d F Y', strtotime($organisasi['tanggal_berdiri'])) : '-'; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold text-uppercase small mb-2">Status</h6>
                            <p class="fs-5 fw-medium text-success">
                                <i class="fas fa-check-circle me-2"></i>Aktif
                            </p>
                        </div>
                        <div class="col-12">
                            <h6 class="text-muted fw-bold text-uppercase small mb-2">Deskripsi</h6>
                            <div class="p-3 bg-light rounded-3 border">
                                <?php echo nl2br(htmlspecialchars($organisasi['deskripsi'] ?? '-')); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold text-uppercase small mb-2">Visi</h6>
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <?php echo nl2br(htmlspecialchars($organisasi['visi'] ?? '-')); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold text-uppercase small mb-2">Misi</h6>
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <?php echo nl2br(htmlspecialchars($organisasi['misi'] ?? '-')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-scale:hover { transform: scale(1.05); transition: transform 0.2s; }
</style>

<?php include __DIR__ . '/../../views/templates/footer.php'; ?>