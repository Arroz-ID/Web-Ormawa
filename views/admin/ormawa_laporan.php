<?php include __DIR__ . '/../../views/templates/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../../views/templates/sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-9 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h3 fw-bold text-gray-800"><i class="fas fa-file-alt text-primary me-2"></i>Laporan Kinerja</h1>
            </div>

            <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Laporan berhasil diupload!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3 border-0">
                            <h6 class="m-0 fw-bold text-primary"><i class="fas fa-cloud-upload-alt me-2"></i>Upload Laporan Baru</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="index.php?action=ormawa_laporan" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Judul Laporan</label>
                                    <input type="text" name="judul" class="form-control" placeholder="Contoh: LPJ Kegiatan X" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">File Dokumen</label>
                                    <input type="file" name="file_laporan" class="form-control" accept=".pdf,.doc,.docx" required>
                                    <small class="text-muted" style="font-size: 0.75rem;">Format: PDF/DOC (Max 5MB)</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Keterangan (Opsional)</label>
                                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Deskripsi singkat laporan..."></textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" name="upload_laporan" class="btn btn-primary fw-bold">
                                        <i class="fas fa-save me-2"></i>Simpan Laporan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3 border-0">
                            <h6 class="m-0 fw-bold text-dark"><i class="fas fa-folder-open me-2"></i>Arsip Laporan</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Judul & Tanggal</th>
                                            <th>File</th>
                                            <th class="text-end pe-4">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($list_laporan)): ?>
                                            <tr>
                                                <td colspan="3" class="text-center py-5 text-muted">Belum ada laporan yang diupload.</td>
                                            </tr>
                                        <?php else: foreach($list_laporan as $lap): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="fw-bold text-dark"><?= htmlspecialchars($lap['judul_laporan']) ?></div>
                                                    <small class="text-muted"><i class="far fa-calendar me-1"></i> <?= date('d M Y', strtotime($lap['tanggal_upload'])) ?></small>
                                                </td>
                                                <td>
                                                    <a href="assets/uploads/laporan/<?= $lap['file_laporan'] ?>" target="_blank" class="btn btn-sm btn-outline-info rounded-pill">
                                                        <i class="fas fa-file-download me-1"></i> Download
                                                    </a>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="index.php?action=ormawa_laporan&hapus_id=<?= $lap['laporan_id'] ?>" 
                                                       class="btn btn-sm btn-light text-danger" 
                                                       onclick="return confirm('Hapus laporan ini?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../views/templates/footer.php'; ?>