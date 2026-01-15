<?php include __DIR__ . '/../../views/templates/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../../views/templates/sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-9 px-md-4 py-4">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h3 fw-bold text-gray-800"><i class="fas fa-camera text-primary me-2"></i>Galeri Kegiatan</h1>
            </div>

            <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Data berhasil disimpan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top: 100px;">
                        <div class="card-header bg-white py-3 border-0">
                            <h6 class="m-0 fw-bold text-primary"><i class="fas fa-plus-circle me-2"></i>Tambah Kegiatan Baru</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="index.php?action=ormawa_kegiatan" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Nama Kegiatan</label>
                                    <input type="text" name="nama_kegiatan" class="form-control" placeholder="Contoh: Bakti Sosial 2024" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Tanggal Pelaksanaan</label>
                                    <input type="date" name="tanggal" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan kegiatan secara singkat..." required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Foto Dokumentasi</label>
                                    <input type="file" name="foto" class="form-control" accept="image/jpeg, image/png, image/jpg" required>
                                    <small class="text-muted" style="font-size: 0.75rem;">Max 2MB (JPG/PNG)</small>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" name="tambah_kegiatan" class="btn btn-primary fw-bold">
                                        <i class="fas fa-upload me-2"></i>Upload Kegiatan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3 border-0">
                            <h6 class="m-0 fw-bold text-dark"><i class="fas fa-images me-2"></i>Daftar Kegiatan Terupload</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <?php if(empty($list_kegiatan)): ?>
                                    <div class="col-12 text-center py-5">
                                        <div class="mb-3 opacity-25">
                                            <i class="fas fa-folder-open fa-4x"></i>
                                        </div>
                                        <p class="text-muted fw-bold">Belum ada kegiatan yang diupload.</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach($list_kegiatan as $k): ?>
                                    <div class="col-md-6">
                                        <div class="card h-100 shadow-sm border hover-card">
                                            <div class="position-relative">
                                                <img src="assets/images/kegiatan/<?= htmlspecialchars($k['foto_kegiatan']) ?>" 
                                                     class="card-img-top" 
                                                     style="height: 180px; object-fit: cover;"
                                                     onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                                                <div class="position-absolute top-0 end-0 p-2">
                                                    <span class="badge bg-light text-dark shadow-sm"><?= date('d M Y', strtotime($k['tanggal_kegiatan'])) ?></span>
                                                </div>
                                            </div>
                                            <div class="card-body p-3">
                                                <h6 class="card-title fw-bold text-truncate mb-1" title="<?= htmlspecialchars($k['nama_kegiatan']) ?>">
                                                    <?= htmlspecialchars($k['nama_kegiatan']) ?>
                                                </h6>
                                                <p class="card-text text-muted small mb-3 text-truncate-2">
                                                    <?= htmlspecialchars($k['deskripsi']) ?>
                                                </p>
                                                <a href="index.php?action=ormawa_kegiatan&hapus_id=<?= $k['kegiatan_id'] ?>" 
                                                   class="btn btn-outline-danger btn-sm w-100" 
                                                   onclick="return confirm('Yakin ingin menghapus kegiatan ini? Foto akan hilang permanen.')">
                                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .hover-card:hover {
        transform: translateY(-3px);
        transition: transform 0.2s;
    }
</style>

<?php include __DIR__ . '/../../views/templates/footer.php'; ?>