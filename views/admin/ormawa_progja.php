<?php include __DIR__ . '/../../views/templates/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../../views/templates/sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-9 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h3 fw-bold text-gray-800"><i class="fas fa-list-check text-purple me-2" style="color: #6f42c1;"></i>Program Kerja</h1>
            </div>

            <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                    <i class="fas fa-check-circle me-2"></i>Data program kerja berhasil disimpan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top: 100px;">
                        <div class="card-header bg-white py-3 border-0">
                            <h6 class="m-0 fw-bold text-primary"><i class="fas fa-plus-circle me-2"></i>Input Program Baru</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="index.php?action=ormawa_progja">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Nama Program</label>
                                    <input type="text" name="nama_program" class="form-control" placeholder="Contoh: Webinar Nasional" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Target Waktu</label>
                                    <input type="text" name="target_waktu" class="form-control" placeholder="Contoh: Maret 2024 / Semester Ganjil" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan tujuan dan sasaran program..." required></textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" name="tambah_progja" class="btn btn-primary fw-bold">
                                        <i class="fas fa-save me-2"></i>Simpan Program
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-dark"><i class="fas fa-tasks me-2"></i>Daftar Rencana Kerja</h6>
                            <span class="badge bg-primary rounded-pill"><?= count($progja) ?> Program</span>
                        </div>
                        <div class="card-body p-0">
                            <?php if(empty($progja)): ?>
                                <div class="text-center py-5">
                                    <img src="assets/images/empty_list.svg" alt="" style="width: 100px; opacity: 0.5;" onerror="this.style.display='none'">
                                    <div class="mt-3 text-muted fw-bold">Belum ada program kerja yang dibuat.</div>
                                    <p class="small text-muted">Silakan input program kerja organisasi Anda di formulir samping.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4" style="width: 5%;">No</th>
                                                <th style="width: 30%;">Nama Program</th>
                                                <th style="width: 15%;">Target</th>
                                                <th>Deskripsi</th>
                                                <th class="text-end pe-4" style="width: 10%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=1; foreach($progja as $p): ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-muted"><?= $no++ ?></td>
                                                <td>
                                                    <span class="fw-bold text-dark"><?= htmlspecialchars($p['nama_program']) ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-2">
                                                        <i class="far fa-clock me-1"></i> <?= htmlspecialchars($p['target_waktu']) ?>
                                                    </span>
                                                </td>
                                                <td class="small text-muted text-truncate" style="max-width: 250px;">
                                                    <?= htmlspecialchars($p['deskripsi']) ?>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="index.php?action=ormawa_progja&hapus_id=<?= $p['progja_id'] ?>" 
                                                       class="btn btn-sm btn-light text-danger border" 
                                                       onclick="return confirm('Yakin ingin menghapus program ini?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
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