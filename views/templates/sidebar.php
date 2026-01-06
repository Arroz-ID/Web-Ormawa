<div class="col-md-3">

<?php 
// -------------------------------------------------------------
// KONDISI 1: JIKA YANG LOGIN ADALAH ADMIN ORMAWA
// -------------------------------------------------------------
if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_level']) && $_SESSION['admin_level'] == 'admin_ormawa'): 
?>
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-user-shield me-2"></i>Admin Ormawa</h6>
        </div>
        <div class="card-body">
            <div class="d-grid gap-2">
                <a href="index.php?action=ormawa_dashboard" class="btn btn-light text-start btn-sm">
                    <i class="fas fa-tachometer-alt me-2 text-primary"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-header bg-dark text-white">
            <h6 class="mb-0 small fw-bold">MANAJEMEN INTERNAL</h6>
        </div>
        <div class="list-group list-group-flush">
            <a href="index.php?action=ormawa_profil_lengkap" class="list-group-item list-group-item-action">
                <i class="fas fa-id-card me-2 text-info"></i> Identitas Organisasi
            </a>
            <a href="index.php?action=ormawa_kelola_anggota" class="list-group-item list-group-item-action">
                <i class="fas fa-users me-2 text-success"></i> Data Anggota
            </a>
            <a href="index.php?action=ormawa_seleksi" class="list-group-item list-group-item-action">
                <i class="fas fa-user-plus me-2 text-warning"></i> Seleksi Masuk
            </a>
            <a href="index.php?action=ormawa_kelola_divisi" class="list-group-item list-group-item-action">
                <i class="fas fa-sitemap me-2 text-secondary"></i> Kelola Divisi
            </a>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0 small fw-bold">PROGRAM KERJA</h6>
        </div>
        <div class="list-group list-group-flush">
            <a href="index.php?action=ormawa_kegiatan" class="list-group-item list-group-item-action">
                <i class="fas fa-camera me-2 text-danger"></i> Galeri Kegiatan
            </a>
            <a href="index.php?action=ormawa_laporan" class="list-group-item list-group-item-action">
                <i class="fas fa-file-alt me-2 text-primary"></i> Laporan Kinerja
            </a>
            <a href="index.php?action=ormawa_pesan" class="list-group-item list-group-item-action">
                <i class="fas fa-bullhorn me-2 text-warning"></i> Broadcast Pesan
            </a>
        </div>
    </div>

<?php 
// -------------------------------------------------------------
// KONDISI 2: JIKA YANG LOGIN ADALAH ANGGOTA (MAHASISWA)
// -------------------------------------------------------------
elseif (isset($_SESSION['anggota_id'])): 
    // PERBAIKAN: Ambil data dari variabel $anggota ATAU fallback ke Session
    $nama_mhs = $anggota['nama_lengkap'] ?? $_SESSION['nama_lengkap'] ?? 'Mahasiswa';
    $nim_mhs  = $anggota['nim'] ?? $_SESSION['nim'] ?? '-';
    $foto_path = $anggota['foto_profil'] ?? null;
?>
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-user-circle me-2"></i>Profil Saya</h6>
        </div>
        <div class="card-body text-center">
            <?php 
            // Cek foto profil anggota
            $foto = ($foto_path && !empty($foto_path)) 
                    ? 'assets/images/profil/' . $foto_path 
                    : null;
            
            if ($foto && file_exists($foto)): ?>
                <img src="<?php echo $foto; ?>" alt="Profil" class="rounded-circle mb-3 shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
            <?php else: ?>
                <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 80px; height: 80px;">
                    <i class="fas fa-user fa-2x"></i>
                </div>
            <?php endif; ?>
            
            <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($nama_mhs); ?></h6>
            <p class="text-muted small mb-2"><?php echo htmlspecialchars($nim_mhs); ?></p>
            
            <a href="index.php?action=profile" class="btn btn-outline-primary btn-sm mt-2 rounded-pill px-4">
                <i class="fas fa-edit me-1"></i> Edit Profil
            </a>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-bolt me-2 text-warning"></i>Menu Anggota</h6>
        </div>
        <div class="list-group list-group-flush">
            <a href="index.php?action=dashboard" class="list-group-item list-group-item-action">
                <i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard
            </a>
            <a href="index.php?action=riwayat" class="list-group-item list-group-item-action">
                <i class="fas fa-history me-2 text-info"></i>Riwayat Pendaftaran
            </a>
            <a href="index.php?action=organisasi" class="list-group-item list-group-item-action">
                <i class="fas fa-search me-2 text-success"></i>Cari Organisasi
            </a>
        </div>
    </div>

<?php 
// -------------------------------------------------------------
// KONDISI 3: TAMU (BELUM LOGIN)
// -------------------------------------------------------------
else: 
?>
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Akses Sistem</h6>
        </div>
        <div class="card-body">
            <p class="small text-muted mb-3">
                Silakan login untuk mendaftar organisasi dan mengelola profil Anda.
            </p>
            <div class="d-grid gap-2">
                <a href="index.php?action=login" class="btn btn-primary btn-sm fw-bold">Login</a>
                <a href="index.php?action=register" class="btn btn-outline-primary btn-sm fw-bold">Daftar Akun</a>
            </div>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white">
            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-info-circle me-2 text-info"></i>Informasi</h6>
        </div>
        <div class="card-body">
            <div class="list-group list-group-flush small">
                <a href="#" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-angle-right me-2"></i>Tentang ORMAWA</a>
                <a href="#" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-angle-right me-2"></i>Panduan Pendaftaran</a>
                <a href="#" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-angle-right me-2"></i>Kontak Kami</a>
            </div>
        </div>
    </div>
<?php endif; ?>

</div>