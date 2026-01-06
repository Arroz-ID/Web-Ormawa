<?php include __DIR__ . '/../templates/header.php'; ?>
<div class="container-fluid py-4">
    <h1 class="h3 mb-4 text-gray-800">Galeri Kegiatan</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Tambah Kegiatan</h6></div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label>Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi Singkat</label>
                            <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Foto Dokumentasi</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required>
                        </div>
                        <button type="submit" name="tambah_kegiatan" class="btn btn-primary w-100">Upload</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="row">
                <?php foreach($list_kegiatan as $k): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="assets/uploads/kegiatan/<?php echo $k['foto_kegiatan']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($k['nama_kegiatan']); ?></h5>
                            <small class="text-muted"><i class="fas fa-calendar me-1"></i> <?php echo date('d M Y', strtotime($k['tanggal_kegiatan'])); ?></small>
                            <p class="card-text mt-2 small"><?php echo htmlspecialchars($k['deskripsi']); ?></p>
                            <a href="index.php?action=ormawa_kegiatan&hapus=<?php echo $k['kegiatan_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kegiatan ini?')">Hapus</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../templates/footer.php'; ?>