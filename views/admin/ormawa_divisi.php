<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-sitemap me-2"></i>Kelola Divisi</h2>
        <a href="index.php?action=ormawa_dashboard" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-primary text-white">Tambah Divisi Baru</div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="tipe_aksi" value="tambah">
                <div class="col-md-4">
                    <input type="text" name="nama_divisi" class="form-control" placeholder="Nama Divisi" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="kuota" class="form-control" placeholder="Kuota" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi singkat">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Nama Divisi</th>
                        <th>Deskripsi</th>
                        <th>Kuota</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($divisi_list)): ?>
                        <tr><td colspan="4" class="text-center py-3">Belum ada divisi.</td></tr>
                    <?php else: foreach($divisi_list as $d): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?php echo htmlspecialchars($d['nama_divisi']); ?></td>
                            <td><?php echo htmlspecialchars($d['deskripsi_divisi']); ?></td>
                            <td><?php echo $d['kuota_anggota']; ?></td>
                            <td class="text-end pe-4">
                                <form method="POST" onsubmit="return confirm('Hapus divisi ini?');">
                                    <input type="hidden" name="tipe_aksi" value="hapus">
                                    <input type="hidden" name="divisi_id" value="<?php echo $d['divisi_id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../templates/footer.php'; ?>