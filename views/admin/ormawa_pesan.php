<?php include __DIR__ . '/../../views/templates/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../../views/templates/sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-9 px-md-4 py-4">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h3 fw-bold text-gray-800"><i class="fas fa-bullhorn text-warning me-2"></i>Broadcast Pesan</h1>
            </div>

            <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-paper-plane me-2"></i>Pesan berhasil dipublikasikan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top: 100px;">
                        <div class="card-header bg-white py-3 border-0">
                            <h6 class="m-0 fw-bold text-primary"><i class="fas fa-edit me-2"></i>Buat Pengumuman Baru</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="index.php?action=ormawa_pesan">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Judul Pengumuman</label>
                                    <input type="text" name="judul" class="form-control" placeholder="Contoh: Rapat Evaluasi Bulanan" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Isi Pesan</label>
                                    <textarea name="isi_pesan" class="form-control" rows="6" placeholder="Tulis pesan lengkap di sini..." required></textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" name="kirim_pesan" class="btn btn-warning text-white fw-bold">
                                        <i class="fas fa-paper-plane me-2"></i>Kirim Pengumuman
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-dark"><i class="fas fa-history me-2"></i>Riwayat Pengumuman</h6>
                            <span class="badge bg-secondary rounded-pill"><?= count($riwayat_pesan) ?> Pesan</span>
                        </div>
                        <div class="card-body p-0">
                            <?php if(empty($riwayat_pesan)): ?>
                                <div class="text-center py-5">
                                    <img src="assets/images/no_message.svg" alt="" style="width: 80px; opacity: 0.5;" onerror="this.style.display='none'">
                                    <div class="mt-3 text-muted fw-bold">Belum ada pengumuman dibuat.</div>
                                    <p class="small text-muted">Gunakan formulir di samping untuk menyebarkan informasi.</p>
                                </div>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach($riwayat_pesan as $pesan): ?>
                                    <div class="list-group-item p-4 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="mb-1 fw-bold text-dark"><?= htmlspecialchars($pesan['judul']) ?></h5>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                                    <li>
                                                        <a class="dropdown-item text-danger small" href="index.php?action=ormawa_pesan&hapus_id=<?= $pesan['pesan_id'] ?>" onclick="return confirm('Hapus pesan ini?')">
                                                            <i class="fas fa-trash-alt me-2"></i>Hapus Pesan
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <p class="mb-2 text-secondary" style="white-space: pre-line;"><?= htmlspecialchars($pesan['isi_pesan']) ?></p>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i> Diposting pada: <?= date('d F Y, H:i', strtotime($pesan['tanggal_kirim'])) ?> WIB
                                        </small>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../views/templates/footer.php'; ?>