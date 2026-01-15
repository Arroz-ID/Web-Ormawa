<?php include 'views/templates/header.php'; ?>

<?php
// 1. Validasi Data Awal
$namaOrg = $organisasi['nama_organisasi'] ?? 'Organisasi Mahasiswa';
$logo = $organisasi['logo'] ?? '';

// Logic Gambar Logo
$logoSrc = !empty($logo) && file_exists('assets/images/profil/' . $logo) 
           ? 'assets/images/profil/' . $logo 
           : 'https://ui-avatars.com/api/?name=' . urlencode($namaOrg) . '&size=200&background=random';

// Format Tanggal
$tglBerdiri = !empty($organisasi['tanggal_berdiri']) 
              ? date('d F Y', strtotime($organisasi['tanggal_berdiri'])) 
              : '-';

// 2. LOGIKA WHATSAPP (Format Nomor)
$wa_number = $organisasi['no_whatsapp'] ?? '';
$wa_link = '';
$has_wa = false;

if (!empty($wa_number)) {
    // Hapus karakter selain angka
    $wa_clean = preg_replace('/[^0-9]/', '', $wa_number);
    
    // Ubah 08... menjadi 628...
    if (substr($wa_clean, 0, 1) == '0') {
        $wa_clean = '62' . substr($wa_clean, 1);
    }
    
    // Buat Link
    $pesan = "Halo Admin " . $namaOrg . ", saya ingin bertanya mengenai organisasi ini.";
    $wa_link = "https://wa.me/" . $wa_clean . "?text=" . urlencode($pesan);
    $has_wa = true;
}
?>

<style>
    /* Styling Khusus Halaman Profil */
    .header-profile {
        background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('assets/images/gedung.jpg');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 60px 0;
        margin-bottom: 30px;
        border-radius: 0 0 20px 20px;
        position: relative;
    }
    .profile-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 5px solid rgba(255,255,255,0.8);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .nav-pills .nav-link {
        color: var(--dark-color, #333);
        background: #f8f9fa;
        margin-bottom: 8px;
        border-radius: 50px;
        padding: 12px 25px;
        font-weight: 500;
        border: 1px solid #e9ecef;
        transition: all 0.3s;
    }
    .nav-pills .nav-link:hover {
        background-color: #e9ecef;
        transform: translateX(5px);
    }
    .nav-pills .nav-link.active {
        background-color: var(--primary-color, #0d6efd);
        color: white;
        border-color: var(--primary-color, #0d6efd);
        box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2);
    }
    .gallery-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 12px;
        transition: transform 0.3s;
        cursor: pointer;
    }
    .gallery-img:hover {
        transform: scale(1.03);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .timeline-item {
        border-left: 2px solid #e9ecef;
        padding-left: 20px;
        margin-bottom: 20px;
        position: relative;
    }
    .timeline-item::before {
        content: '';
        width: 12px;
        height: 12px;
        background: var(--primary-color, #0d6efd);
        border-radius: 50%;
        position: absolute;
        left: -7px;
        top: 5px;
    }

    /* 3. CSS TOMBOL WHATSAPP MELAYANG */
    .whatsapp-float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 30px;
        right: 30px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 30px;
        box-shadow: 2px 2px 3px #999;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
        animation: pulse 2s infinite;
    }

    .whatsapp-float:hover {
        background-color: #128C7E;
        transform: scale(1.1);
        color: white;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7); }
        70% { box-shadow: 0 0 0 15px rgba(37, 211, 102, 0); }
        100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
    }
</style>

<div class="header-profile text-center">
    <div class="container">
        <img src="<?= htmlspecialchars($logoSrc) ?>" alt="Logo" class="rounded-circle profile-img mb-3">
        
        <h1 class="fw-bold mb-2"><?= htmlspecialchars($namaOrg) ?></h1>
        <p class="mb-4 opacity-75">
            <span class="badge bg-warning text-dark me-2"><?= htmlspecialchars($organisasi['jenis_organisasi'] ?? 'UKM') ?></span>
            <i class="far fa-calendar-alt me-1"></i> Berdiri sejak: <?= $tglBerdiri ?>
        </p>
        
        <?php if(isset($_SESSION['anggota_id'])): ?>
            <a href="index.php?action=daftar_kepengurusan&organisasi_id=<?= $organisasi['organisasi_id'] ?>" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                <i class="fas fa-plus-circle me-2"></i>Daftar Anggota
            </a>
        <?php elseif(isset($_SESSION['admin_id'])): ?>
            <button class="btn btn-secondary rounded-pill px-4 py-2" disabled>Mode Admin</button>
        <?php else: ?>
            <a href="index.php?action=login" class="btn btn-outline-light rounded-pill px-4 py-2">
                <i class="fas fa-sign-in-alt me-2"></i>Login untuk Mendaftar
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="container pb-5">
    <div class="row">
        
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-muted text-uppercase small mb-3 ms-2">Menu Informasi</h6>
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active text-start" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab">
                            <i class="fas fa-info-circle me-3"></i> Profil & Visi Misi
                        </button>
                        
                        <button class="nav-link text-start" id="v-pills-pesan-tab" data-bs-toggle="pill" data-bs-target="#v-pills-pesan" type="button" role="tab">
                            <i class="fas fa-bullhorn me-3"></i> Pengumuman
                        </button>
                        
                        <button class="nav-link text-start" id="v-pills-progja-tab" data-bs-toggle="pill" data-bs-target="#v-pills-progja" type="button" role="tab">
                            <i class="fas fa-tasks me-3"></i> Program Kerja
                        </button>
                        <button class="nav-link text-start" id="v-pills-gallery-tab" data-bs-toggle="pill" data-bs-target="#v-pills-gallery" type="button" role="tab">
                            <i class="fas fa-images me-3"></i> Galeri Kegiatan
                        </button>
                        <button class="nav-link text-start" id="v-pills-struktur-tab" data-bs-toggle="pill" data-bs-target="#v-pills-struktur" type="button" role="tab">
                            <i class="fas fa-sitemap me-3"></i> Struktur & Divisi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="tab-content" id="v-pills-tabContent">
                
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold text-dark mb-3">Tentang Organisasi</h4>
                            <p class="text-muted" style="line-height: 1.8;">
                                <?= nl2br(htmlspecialchars($organisasi['deskripsi'] ?? 'Deskripsi organisasi belum ditambahkan.')) ?>
                            </p>
                            
                            <div class="row g-4 mt-2">
                                <div class="col-md-6">
                                    <div class="p-4 bg-primary bg-opacity-10 rounded-4 h-100">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-primary text-white rounded-circle p-2 me-3">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                            <h5 class="fw-bold text-primary mb-0">Visi</h5>
                                        </div>
                                        <p class="mb-0 small text-dark opacity-75">
                                            <?= nl2br(htmlspecialchars($organisasi['visi'] ?? '-')) ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-4 bg-success bg-opacity-10 rounded-4 h-100">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-success text-white rounded-circle p-2 me-3">
                                                <i class="fas fa-bullseye"></i>
                                            </div>
                                            <h5 class="fw-bold text-success mb-0">Misi</h5>
                                        </div>
                                        <p class="mb-0 small text-dark opacity-75">
                                            <?= nl2br(htmlspecialchars($organisasi['misi'] ?? '-')) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-pills-pesan" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold text-dark mb-4">Pengumuman & Informasi Terbaru</h4>
                            <?php if(!empty($list_pesan)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach($list_pesan as $pesan): ?>
                                    <div class="list-group-item p-4 border rounded mb-3 bg-light position-relative">
                                        <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                            <h5 class="mb-1 fw-bold text-primary">
                                                <i class="fas fa-bullhorn me-2"></i><?= htmlspecialchars($pesan['judul']) ?>
                                            </h5>
                                            <small class="text-muted bg-white px-2 py-1 rounded border">
                                                <i class="far fa-clock me-1"></i><?= date('d M Y, H:i', strtotime($pesan['tanggal_kirim'])) ?>
                                            </small>
                                        </div>
                                        <p class="mb-1 text-dark opacity-75" style="white-space: pre-line; line-height: 1.6;">
                                            <?= htmlspecialchars($pesan['isi_pesan']) ?>
                                        </p>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-light text-center border py-5">
                                    <i class="fas fa-bullhorn fa-3x text-muted mb-3 opacity-50"></i>
                                    <h6 class="text-muted">Belum ada pengumuman dari organisasi ini.</h6>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-pills-progja" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold text-dark mb-4">Program Kerja Unggulan</h4>
                            <?php if(!empty($list_progja)): ?>
                                <div class="mt-3">
                                    <?php foreach($list_progja as $prog): ?>
                                    <div class="timeline-item">
                                        <span class="badge bg-light text-dark border mb-2">
                                            <i class="far fa-clock me-1"></i> <?= htmlspecialchars($prog['target_waktu']) ?>
                                        </span>
                                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($prog['nama_program']) ?></h5>
                                        <p class="text-muted mb-0"><?= htmlspecialchars($prog['deskripsi']) ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <img src="assets/images/empty.svg" alt="" style="width: 100px; opacity: 0.5;" class="mb-3" onerror="this.style.display='none'">
                                    <h6 class="text-muted">Belum ada program kerja yang dipublikasikan.</h6>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-pills-gallery" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="fw-bold text-dark mb-0">Dokumentasi Kegiatan</h4>
                                <span class="badge bg-primary rounded-pill"><?= count($kegiatan ?? []) ?> Foto</span>
                            </div>

                            <?php if (!empty($kegiatan)): ?>
                                <div class="row g-3">
                                    <?php foreach ($kegiatan as $foto): ?>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="position-relative rounded-3 overflow-hidden shadow-sm h-100">
                                                <?php 
                                                    $imgFile = 'assets/images/kegiatan/' . $foto['foto_kegiatan'];
                                                    $imgSrc = file_exists($imgFile) ? $imgFile : 'https://via.placeholder.com/400x300?text=No+Image';
                                                ?>
                                                <img src="<?= $imgSrc ?>" class="gallery-img" alt="Kegiatan">
                                                
                                                <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark text-white" 
                                                     style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                                    <h6 class="fw-bold mb-0 text-truncate text-white"><?= htmlspecialchars($foto['nama_kegiatan']) ?></h6>
                                                    <small class="opacity-75" style="font-size: 0.75rem;">
                                                        <?= date('d M Y', strtotime($foto['tanggal_kegiatan'])) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-light text-center border py-5">
                                    <i class="far fa-images fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">Belum ada dokumentasi kegiatan.</h6>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-pills-struktur" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold text-dark mb-4">Struktur Organisasi</h4>
                            
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3"><i class="fas fa-crown me-2"></i>Pengurus Inti</h6>
                            <div class="row g-3 mb-5">
                                <?php 
                                $hasInti = false;
                                if(!empty($kepengurusan)):
                                    foreach($kepengurusan as $urus): 
                                        if(in_array($urus['nama_jabatan'], ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara'])):
                                            $hasInti = true;
                                            $fotoP = !empty($urus['foto_profil']) ? 'assets/images/profil/' . $urus['foto_profil'] : null;
                                            $srcP = ($fotoP && file_exists($fotoP)) ? $fotoP : 'https://ui-avatars.com/api/?name='.urlencode($urus['nama_lengkap']);
                                ?>
                                <div class="col-6 col-md-3 text-center">
                                    <div class="card h-100 border-0 shadow-sm py-3 hover-card">
                                        <div class="card-body p-2">
                                            <img src="<?= $srcP ?>" class="rounded-circle mb-2 border" style="width: 70px; height: 70px; object-fit: cover;">
                                            <h6 class="small fw-bold mb-0 text-truncate"><?= htmlspecialchars($urus['nama_lengkap']) ?></h6>
                                            <span class="badge bg-light text-primary border mt-1"><?= htmlspecialchars($urus['nama_jabatan']) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; endforeach; endif; ?>
                                
                                <?php if(!$hasInti): ?>
                                    <div class="col-12"><p class="text-muted small">Data pengurus inti belum diinput.</p></div>
                                <?php endif; ?>
                            </div>

                            <h6 class="fw-bold text-info border-bottom pb-2 mb-3"><i class="fas fa-layer-group me-2"></i>Divisi & Unit</h6>
                            <?php if(!empty($divisi)): ?>
                                <div class="list-group list-group-flush rounded-3 border">
                                    <?php foreach($divisi as $div): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($div['nama_divisi']) ?></h6>
                                                <small class="text-muted"><?= htmlspecialchars($div['deskripsi_divisi']) ?></small>
                                            </div>
                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">
                                                <?= $div['kuota_anggota'] ?> Kuota
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-light border text-center">Belum ada data divisi.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php if ($has_wa): ?>
    <a href="<?= $wa_link ?>" class="whatsapp-float" target="_blank" title="Hubungi Admin">
        <i class="fab fa-whatsapp"></i>
    </a>
<?php endif; ?>

<?php include 'views/templates/footer.php'; ?>