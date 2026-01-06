<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-primary"><i class="fas fa-sitemap me-2"></i>Daftar Organisasi</h2>
            <p class="text-muted">Jelajahi berbagai organisasi kemahasiswaan yang ada di Poltesa.</p>
        </div>
        <div class="col-md-4">
            <input type="text" id="searchOrganisasi" class="form-control rounded-pill" placeholder="Cari organisasi...">
        </div>
    </div>

    <?php if (empty($organisations)): ?>
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-folder-open fa-4x text-muted opacity-25"></i>
            </div>
            <h5 class="text-muted">Belum ada organisasi yang terdaftar.</h5>
        </div>
    <?php else: ?>
        
        <div class="row g-4" id="organisasiList">
            <?php foreach ($organisations as $org): ?>
                <div class="col-lg-4 col-md-6 organisasi-item">
                    <div class="card h-100 shadow-sm border-0 hover-lift">
                        <div class="card-body text-center p-4">
                            
                            <?php 
                                $logoFile = $org['logo'] ?? '';
                                $finalLogoSrc = null;

                                // 1. Cek di folder profil (Tempat upload baru)
                                if (!empty($logoFile) && file_exists('assets/images/profil/' . $logoFile)) {
                                    $finalLogoSrc = 'assets/images/profil/' . $logoFile;
                                } 
                                // 2. Cek di folder images biasa (Data lama)
                                elseif (!empty($logoFile) && file_exists('assets/images/' . $logoFile)) {
                                    $finalLogoSrc = 'assets/images/' . $logoFile;
                                }
                            ?>

                            <div class="mb-3 d-flex justify-content-center">
                                <?php if ($finalLogoSrc): ?>
                                    <img src="<?php echo htmlspecialchars($finalLogoSrc); ?>" 
                                         alt="<?php echo htmlspecialchars($org['nama_organisasi']); ?>"
                                         class="rounded-circle shadow-sm border" 
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <h5 class="card-title fw-bold mb-1 text-dark">
                                <?php echo htmlspecialchars($org['nama_organisasi']); ?>
                            </h5>
                            
                            <span class="badge bg-light text-primary border mb-3">
                                <?php echo htmlspecialchars($org['jenis_organisasi'] ?? 'Organisasi'); ?>
                            </span>

                            <p class="card-text text-muted small mb-4" style="min-height: 40px;">
                                <?php 
                                    $desc = htmlspecialchars($org['deskripsi'] ?? '');
                                    echo strlen($desc) > 80 ? substr($desc, 0, 80) . '...' : $desc;
                                ?>
                            </p>

                            <div class="d-grid">
                                <a href="index.php?action=detail&id=<?php echo $org['organisasi_id']; ?>" 
                                   class="btn btn-outline-primary rounded-pill btn-sm">
                                    Lihat Profil <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<script>
document.getElementById('searchOrganisasi').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let items = document.querySelectorAll('.organisasi-item');

    items.forEach(function(item) {
        let text = item.textContent.toLowerCase();
        if (text.includes(filter)) {
            item.style.display = '';
            item.classList.add('animate__animated', 'animate__fadeIn');
        } else {
            item.style.display = 'none';
        }
    });
});
</script>

<style>
/* Efek Hover Card */
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>