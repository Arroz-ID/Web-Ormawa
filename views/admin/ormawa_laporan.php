<?php include __DIR__ . '/../templates/header.php'; ?>
<div class="container-fluid py-4">
    <h1 class="h3 mb-4 text-gray-800">Laporan Kinerja & Arsip</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 fw-bold text-primary">Upload Laporan</h6></div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3"><label>Judul Dokumen</label><input type="text" name="judul" class="form-control" required></div>
                        <div class="mb-3"><label>Keterangan</label><textarea name="keterangan" class="form-control" rows="2"></textarea></div>
                        <div class="mb-3"><label>File (PDF/Word)</label><input type="file" name="file" class="form-control" required></div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Arsip</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead><tr><th>Judul</th><th>Keterangan</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php foreach($list_laporan as $l): ?>
                            <tr>
                                <td><i class="fas fa-file-alt me-2 text-primary"></i> <?php echo htmlspecialchars($l['judul_laporan']); ?></td>
                                <td><?php echo htmlspecialchars($l['keterangan']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($l['tanggal_upload'])); ?></td>
                                <td>
                                    <a href="assets/uploads/laporan/<?php echo $l['file_laporan']; ?>" class="btn btn-sm btn-info" target="_blank"><i class="fas fa-download"></i></a>
                                    <a href="index.php?action=ormawa_laporan&hapus=<?php echo $l['laporan_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../templates/footer.php'; ?>