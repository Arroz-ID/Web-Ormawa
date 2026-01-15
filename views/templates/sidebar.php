<div class="col-md-3">

<?php 
// Helper untuk status aktif menu
$current_action = $_GET['action'] ?? '';

// =========================================================
// 1. SIDEBAR UNTUK SUPER ADMIN
// =========================================================
if (isset($_SESSION['admin_level']) && $_SESSION['admin_level'] == 'super_admin'): 
?>
    <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 90px; z-index: 100;">
        <div class="card-header bg-dark text-white border-0 py-3" style="border-radius: 1rem 1rem 0 0;">
            <h6 class="mb-0 fw-bold"><i class="fas fa-user-astronaut me-2"></i>Super Admin</h6>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush rounded-bottom-4">
                <a href="index.php?action=admin_dashboard" class="list-group-item list-group-item-action py-3 <?php echo ($current_action == 'admin_dashboard') ? 'bg-light fw-bold text-primary border-start border-4 border-primary' : ''; ?>">
                    <i class="fas fa-tachometer-alt me-3 text-secondary"></i>Dashboard Pusat
                </a>
                
                <div class="p-3 pb-0 text-muted small fw-bold text-uppercase">Kelola Master</div>
                
                <a href="index.php?action=admin_kelola_organisasi" class="list-group-item list-group-item-action py-3 <?php echo ($current_action == 'admin_kelola_organisasi') ? 'bg-light fw-bold text-primary border-start border-4 border-primary' : ''; ?>">
                    <i class="fas fa-university me-3 text-success"></i>Data Ormawa
                </a>
                <a href="index.php?action=admin_kelola_pengguna" class="list-group-item list-group-item-action py-3 <?php echo ($current_action == 'admin_kelola_pengguna') ? 'bg-light fw-bold text-primary border-start border-4 border-primary' : ''; ?>">
                    <i class="fas fa-users-cog me-3 text-info"></i>Manajemen User
                </a>
                <a href="index.php?action=admin_log_aktivitas" class="list-group-item list-group-item-action py-3 <?php echo ($current_action == 'admin_log_aktivitas') ? 'bg-light fw-bold text-primary border-start border-4 border-primary' : ''; ?>">
                    <i class="fas fa-history me-3 text-warning"></i>Log Sistem
                </a>

                <div class="p-3">
                    <a href="index.php?action=logout" class="btn btn-outline-danger w-100 rounded-pill" onclick="return confirm('Logout dari Super Admin?')">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php 
// =========================================================
// 2. SIDEBAR UNTUK ADMIN ORMAWA
// =========================================================
elseif (isset($_SESSION['admin_level']) && $_SESSION['admin_level'] == 'admin_ormawa'): 
?>
    <style>
        .nav-link-custom {
            color: #495057;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 4px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-weight: 500;
        }
        .nav-link-custom:hover {
            background-color: #f8f9fa;
            color: var(--primary-color, #0d6efd);
            transform: translateX(3px);
        }
        .nav-link-custom.active {
            background-color: rgba(13, 110, 253, 0.1); 
            color: var(--primary-color, #0d6efd);
            font-weight: 600;
        }
        .nav-link-custom i {
            width: 24px;
            text-align: center;
            margin-right: 12px;
        }
        .sidebar-heading {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #adb5bd;
            font-weight: 700;
            margin: 20px 0 10px 20px;
        }
    </style>

    <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 90px; z-index: 100;">
        <div class="card-body p-2">
            
            <div class="p-3 text-center mb-2 bg-light rounded-3 mx-2 mt-2">
                <div class="fw-bold text-dark"><i class="fas fa-user-shield text-primary me-2"></i>Admin Panel</div>
                <div class="small text-muted">Kelola Organisasi</div>
            </div>

            <a href="index.php?action=ormawa_dashboard" class="nav-link-custom <?php echo ($current_action == 'ormawa_dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt text-primary"></i> Dashboard
            </a>

            <div class="sidebar-heading">Manajemen Internal</div>
            
            <a href="index.php?action=ormawa_profil_lengkap" class="nav-link-custom <?php echo ($current_action == 'ormawa_profil_lengkap') ? 'active' : ''; ?>">
                <i class="fas fa-id-card text-info"></i> Identitas
            </a>
            
            <a href="index.php?action=ormawa_kelola_anggota" class="nav-link-custom <?php echo ($current_action == 'ormawa_kelola_anggota') ? 'active' : ''; ?>">
                <i class="fas fa-users text-success"></i> Data Anggota
            </a>
            
            <a href="index.php?action=ormawa_seleksi" class="nav-link-custom <?php echo ($current_action == 'ormawa_seleksi') ? 'active' : ''; ?>">
                <i class="fas fa-user-plus text-warning"></i> Seleksi Masuk
            </a>
            
            <a href="index.php?action=ormawa_kelola_divisi" class="nav-link-custom <?php echo ($current_action == 'ormawa_kelola_divisi') ? 'active' : ''; ?>">
                <i class="fas fa-sitemap text-secondary"></i> Kelola Divisi
            </a>
            
            <a href="index.php?action=ormawa_progja" class="nav-link-custom <?php echo ($current_action == 'ormawa_progja') ? 'active' : ''; ?>">
                <i class="fas fa-list-check" style="color: #6f42c1;"></i> Program Kerja
            </a>

            <div class="sidebar-heading">Laporan & Publikasi</div>
            
            <a href="index.php?action=ormawa_kegiatan" class="nav-link-custom <?php echo ($current_action == 'ormawa_kegiatan') ? 'active' : ''; ?>">
                <i class="fas fa-camera text-danger"></i> Galeri Kegiatan
            </a>
            
            <a href="index.php?action=ormawa_laporan" class="nav-link-custom <?php echo ($current_action == 'ormawa_laporan') ? 'active' : ''; ?>">
                <i class="fas fa-file-alt text-primary"></i> Laporan Kinerja
            </a>
            
            <a href="index.php?action=ormawa_pesan" class="nav-link-custom <?php echo ($current_action == 'ormawa_pesan') ? 'active' : ''; ?>">
                <i class="fas fa-bullhorn text-warning"></i> Broadcast
            </a>
        </div>
    </div>

<?php 
// =========================================================
// 3. SIDEBAR UNTUK ANGGOTA (MAHASISWA)
// =========================================================
elseif (isset($_SESSION['anggota_id'])): 
    // Ambil data nama & foto jika ada di session atau variabel global
    $nama_mhs = $anggota['nama_lengkap'] ?? $_SESSION['nama_lengkap'] ?? 'Mahasiswa';
    $nim_mhs  = $anggota['nim'] ?? $_SESSION['nim'] ?? '-';
    $foto_path = $anggota['foto_profil'] ?? null;
?>
    <div class="card mb-4 border-0 shadow-sm rounded-4 sticky-top" style="top: 90px;">
        <div class="card-header bg-primary text-white border-0 py-3" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
            <h6 class="mb-0 text-center"><i class="fas fa-user-circle me-2"></i>Profil Saya</h6>
        </div>
        <div class="card-body text-center pt-4">
            <?php 
            $foto = ($foto_path && !empty($foto_path)) ? 'assets/images/profil/' . $foto_path : null;
            if ($foto && file_exists($foto)): ?>
                <img src="<?php echo $foto; ?>" alt="Profil" class="rounded-circle mb-3 shadow-sm border border-3 border-light" style="width: 90px; height: 90px; object-fit: cover;">
            <?php else: ?>
                <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 90px; height: 90px;">
                    <i class="fas fa-user fa-3x"></i>
                </div>
            <?php endif; ?>
            
            <h6 class="mb-1 fw-bold text-dark"><?php echo htmlspecialchars($nama_mhs); ?></h6>
            <p class="text-muted small mb-3 badge bg-light text-secondary border"><?php echo htmlspecialchars($nim_mhs); ?></p>
            
            <a href="index.php?action=profile" class="btn btn-outline-primary btn-sm rounded-pill px-4 mb-4">
                <i class="fas fa-edit me-1"></i> Edit Profil
            </a>

            <div class="list-group list-group-flush text-start border-top pt-2">
                <a href="index.php?action=dashboard" class="list-group-item list-group-item-action border-0 px-2 rounded <?php echo ($current_action == 'dashboard') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : ''; ?>">
                    <i class="fas fa-tachometer-alt me-3 text-primary"></i>Dashboard
                </a>
                <a href="index.php?action=riwayat" class="list-group-item list-group-item-action border-0 px-2 rounded <?php echo ($current_action == 'riwayat') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : ''; ?>">
                    <i class="fas fa-history me-3 text-info"></i>Riwayat Pendaftaran
                </a>
                <a href="index.php?action=organisasi" class="list-group-item list-group-item-action border-0 px-2 rounded <?php echo ($current_action == 'organisasi') ? 'bg-primary bg-opacity-10 text-primary fw-bold' : ''; ?>">
                    <i class="fas fa-search me-3 text-success"></i>Cari Organisasi
                </a>
            </div>
            
             <div class="mt-3">
                <a href="index.php?action=logout" class="btn btn-danger btn-sm w-100 rounded-pill" onclick="return confirm('Keluar dari aplikasi?')">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </div>
        </div>
    </div>

<?php 
// =========================================================
// 4. SIDEBAR UNTUK TAMU (BELUM LOGIN)
// =========================================================
else: 
?>
    <div class="card mb-4 border-0 shadow-sm rounded-4">
        <div class="card-body p-4 text-center">
            <div class="mb-3 text-primary">
                <i class="fas fa-lock fa-3x opacity-50"></i>
            </div>
            <h6 class="fw-bold">Akses Terbatas</h6>
            <p class="small text-muted mb-4">Silakan login untuk mendaftar ke organisasi mahasiswa.</p>
            <div class="d-grid gap-2">
                <a href="index.php?action=login" class="btn btn-primary fw-bold rounded-pill">Login</a>
                <a href="index.php?action=register" class="btn btn-outline-primary fw-bold rounded-pill">Daftar Akun</a>
            </div>
        </div>
    </div>
<?php endif; ?>

</div>