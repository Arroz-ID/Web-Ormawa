<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-users me-2"></i>Kelola Anggota</h2>
        <a href="index.php?action=ormawa_dashboard" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Nama Lengkap</th>
                            <th>NIM</th>
                            <th>Jabatan</th>
                            <th>Divisi</th>
                            <th>Jurusan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($pengurus)): ?>
                            <tr><td colspan="6" class="text-center py-4">Belum ada anggota aktif.</td></tr>
                        <?php else: foreach($pengurus as $p): ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?php echo htmlspecialchars($p['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($p['nim']); ?></td>
                                <td><span class="badge bg-primary"><?php echo htmlspecialchars($p['nama_jabatan']); ?></span></td>
                                <td><?php echo htmlspecialchars($p['nama_divisi'] ?? '-'); ?></td>
                                <td class="small text-muted"><?php echo htmlspecialchars($p['jurusan']); ?></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../templates/footer.php'; ?>