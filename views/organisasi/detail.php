<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($organisasi['nama_organisasi']); ?></li>
        </ol>
    </nav>

    <!-- Organisation Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <?php if ($organisasi['logo']): ?>
                                <img src="assets/images/<?php echo $organisasi['logo']; ?>" 
                                     alt="<?php echo $organisasi['nama_organisasi']; ?>" 
                                     class="img-fluid rounded" 
                                     style="max-height: 120px;">
                            <?php else: ?>
                                <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center mx-auto" 
                                     style="width: 120px; height: 120px;">
                                    <i class="fas fa-users fa-3x"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-10">
                            <h1 class="card-title"><?php echo htmlspecialchars($organisasi['nama_organisasi']); ?></h1>
                            <span class="badge bg-primary mb-2"><?php echo $organisasi['jenis_organisasi']; ?></span>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($organisasi['deskripsi'])); ?></p>
                            <div class="d-flex gap-3 text-muted">
                                <small><i class="fas fa-users me-1"></i> <?php echo $organisasi['jumlah_pengurus']; ?> Pengurus</small>
                                <small><i class="fas fa-sitemap me-1"></i> <?php echo $organisasi['jumlah_divisi']; ?> Divisi</small>
                                <small><i class="fas fa-calendar me-1"></i> Berdiri <?php echo date('d F Y', strtotime($organisasi['tanggal_berdiri'])); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Visi Misi -->
        <div class="col-md-8">
            <!-- Visi -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Visi</h5>
                </div>
                <div class="card-body">
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($organisasi['visi'])); ?></p>
                </div>
            </div>

            <!-- Misi -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-bullseye me-2"></i>Misi</h5>
                </div>
                <div class="card-body">
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($organisasi['misi'])); ?></p>
                </div>
            </div>

            <!-- Divisi -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-sitemap me-2"></i>Divisi</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($divisi)): ?>
                        <p class="text-muted">Belum ada divisi yang terdaftar.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($divisi as $div): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        <h6 class="mb-2"><?php echo htmlspecialchars($div['nama_divisi']); ?></h6>
                                        <p class="small text-muted mb-2">
                                            <?php echo strlen($div['deskripsi_divisi']) > 100 ? 
                                                substr($div['deskripsi_divisi'], 0, 100) . '...' : 
                                                $div['deskripsi_divisi']; ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-secondary">Kuota: <?php echo $div['kuota_anggota']; ?></span>
                                            <a href="index.php?action=daftar_divisi&id=<?php echo $div['divisi_id']; ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                Daftar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column - Kepengurusan & Actions -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-rocket me-2"></i>Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="index.php?action=daftar_kepengurusan&organisasi_id=<?php echo $organisasi['organisasi_id']; ?>" 
                           class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Daftar Kepengurusan
                        </a>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#shareModal">
                            <i class="fas fa-share-alt me-2"></i>Bagikan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Kepengurusan -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Struktur Kepengurusan</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($kepengurusan)): ?>
                        <p class="text-muted">Belum ada kepengurusan yang terdaftar.</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($kepengurusan as $pengurus): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0"><?php echo htmlspecialchars($pengurus['nama_lengkap']); ?></h6>
                                            <small class="text-muted"><?php echo $pengurus['nama_jabatan']; ?></small>
                                            <?php if ($pengurus['nama_divisi']): ?>
                                                <br><small class="text-info"><?php echo $pengurus['nama_divisi']; ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bagikan Organisasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Salin link berikut untuk membagikan organisasi ini:</p>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" readonly>
                    <button class="btn btn-outline-secondary" type="button" id="copyLink">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Copy link functionality
document.getElementById('copyLink').addEventListener('click', function() {
    const linkInput = document.querySelector('#shareModal input');
    linkInput.select();
    document.execCommand('copy');
    alert('Link berhasil disalin!');
});
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>