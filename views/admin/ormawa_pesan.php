<?php include __DIR__ . '/../templates/header.php'; ?>
<div class="container-fluid py-4">
    <h1 class="h3 mb-4 text-gray-800">Broadcast Pesan Anggota</h1>
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white"><h6 class="m-0 fw-bold">Buat Pesan Baru</h6></div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Judul Pesan</label>
                            <input type="text" name="judul" class="form-control" placeholder="Contoh: Undangan Rapat..." required>
                        </div>
                        <div class="mb-3">
                            <label>Target Penerima</label>
                            <select name="target" class="form-select">
                                <option value="semua_anggota">Semua Anggota</option>
                                <option value="pengurus_inti">Hanya Pengurus Inti</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Isi Pesan</label>
                            <textarea name="isi" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-paper-plane me-2"></i> Kirim Broadcast</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header py-3"><h6 class="m-0 fw-bold text-primary">Riwayat Pesan Terkirim</h6></div>
                <div class="card-body">
                    <?php foreach($riwayat_pesan as $p): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-bold text-dark"><?php echo htmlspecialchars($p['judul_pesan']); ?></h6>
                            <small class="text-muted"><?php echo date('d M Y, H:i', strtotime($p['tanggal_kirim'])); ?></small>
                        </div>
                        <span class="badge bg-secondary mb-2"><?php echo str_replace('_', ' ', strtoupper($p['target_penerima'])); ?></span>
                        <p class="text-muted mb-0"><?php echo nl2br(htmlspecialchars($p['isi_pesan'])); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../templates/footer.php'; ?>