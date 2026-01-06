<?php include 'views/templates/header.php'; ?>

<section class="position-relative" style="margin-bottom: -50px; z-index: 1;">
    <div class="container-fluid p-0">
        <div class="position-relative" style="height: 500px; overflow: hidden;">
            <img src="assets/images/GEDUNG Kuliah.jpg" alt="Gedung Kampus" class="w-100 h-100" 
                 style="object-fit: cover; object-position: center;">
            
            <div class="position-absolute top-0 start-0 w-100 h-100" 
                 style="background: linear-gradient(rgba(13, 110, 253, 0.8), rgba(13, 110, 253, 0.4));"></div>

            <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-75">
                <h1 class="display-4 fw-bold mb-3 animate__animated animate__fadeInUp">
                    Temukan Passionmu di <br><span class="text-warning">ORMAWA POLTESA</span>
                </h1>
                <p class="lead mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    Satu pintu untuk mengeksplorasi, mendaftar, dan berkembang bersama<br> 
                    organisasi mahasiswa terbaik di Politeknik Negeri Sambas.
                </p>
                <div class="d-flex justify-content-center gap-3 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                    <a href="#daftar-ormawa" class="btn btn-light text-primary fw-bold btn-lg px-4 shadow-sm rounded-pill">
                        Jelajahi Organisasi
                    </a>
                    
                    <?php if (!isset($_SESSION['anggota_id']) && !isset($_SESSION['admin_id'])): ?>
                        <a href="index.php?action=register" class="btn btn-outline-light btn-lg px-4 shadow-sm rounded-pill">
                            Daftar Akun
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container position-relative" style="z-index: 10;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="row text-center g-4">
                        <div class="col-md-4 border-end">
                            <h3 class="fw-bold text-primary mb-0"><?php echo isset($total_organisasi) ? $total_organisasi : 0; ?></h3>
                            <span class="text-muted small text-uppercase fw-bold">Organisasi Aktif</span>
                        </div>
                        <div class="col-md-4 border-end">
                            <h3 class="fw-bold text-success mb-0"><?php echo isset($total_anggota) ? $total_anggota : 0; ?></h3>
                            <span class="text-muted small text-uppercase fw-bold">Anggota Bergabung</span>
                        </div>
                        <div class="col-md-4">
                            <h3 class="fw-bold text-warning mb-0"><?php echo date('Y'); ?></h3>
                            <span class="text-muted small text-uppercase fw-bold">Periode Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="py-5 bg-light mt-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase ls-2">Alur Pendaftaran</h6>
            <h2 class="fw-bold">Mudah Bergabung dalam 4 Langkah</h2>
        </div>
        
        <div class="row g-4 text-center position-relative">
            <div class="d-none d-lg-block position-absolute top-50 start-0 w-100 border-top border-2 border-primary border-opacity-25" style="z-index: 0;"></div>

            <div class="col-lg-3 col-md-6 position-relative" style="z-index: 1;">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100 hover-lift">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">1</div>
                    <h5 class="fw-bold">Buat Akun</h5>
                    <p class="text-muted small mb-0">Daftarkan diri Anda menggunakan NIM dan email aktif kampus.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 position-relative" style="z-index: 1;">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100 hover-lift">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">2</div>
                    <h5 class="fw-bold">Pilih Organisasi</h5>
                    <p class="text-muted small mb-0">Jelajahi profil UKM/HIMA dan pilih yang sesuai minat Anda.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 position-relative" style="z-index: 1;">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100 hover-lift">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">3</div>
                    <h5 class="fw-bold">Daftar & Seleksi</h5>
                    <p class="text-muted small mb-0">Isi formulir pendaftaran dan ikuti proses seleksi/wawancara.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 position-relative" style="z-index: 1;">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100 hover-lift">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;"><i class="fas fa-check"></i></div>
                    <h5 class="fw-bold">Resmi Bergabung</h5>
                    <p class="text-muted small mb-0">Cek status penerimaan di dashboard dan mulai beraktivitas!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="daftar-ormawa" class="py-5 bg-white">
    <div class="container py-4">
        
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <h6 class="text-primary fw-bold text-uppercase ls-2">Organisasi</h6>
                <h2 class="fw-bold mb-0">Jelajahi Organisasi Kami</h2>
            </div>
            
            <div class="input-group shadow-sm" style="max-width: 350px;">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0 rounded-end-pill ps-2 py-2" placeholder="Cari nama organisasi..." autocomplete="off">
            </div>
        </div>

        <div class="row g-4" id="organisasi-grid">
            <?php if (empty($organisations)): ?>
                <div class="col-12 text-center py-5">
                    <div class="mb-3"><i class="fas fa-box-open fa-3x text-muted opacity-50"></i></div>
                    <h5 class="text-muted">Belum ada organisasi yang terdaftar.</h5>
                </div>
            <?php else: ?>
                <?php foreach ($organisations as $org): ?>
                    <div class="col-lg-4 col-md-6 mb-5 mt-4 org-item" data-kategori="<?php echo htmlspecialchars($org['jenis_organisasi']); ?>">
                        
                        <div class="card h-100 border-0 shadow-sm hover-card profile-card">
                            
                            <?php 
                                $logoFile = $org['logo'] ?? '';
                                $finalLogoSrc = null;

                                // 1. Cek di folder profil (Tempat upload baru)
                                if (!empty($logoFile) && file_exists('assets/images/profil/' . $logoFile)) {
                                    $finalLogoSrc = 'assets/images/profil/' . $logoFile;
                                } 
                                // 2. Cek di folder images biasa (Legacy/Data lama)
                                elseif (!empty($logoFile) && file_exists('assets/images/' . $logoFile)) {
                                    $finalLogoSrc = 'assets/images/' . $logoFile;
                                }
                            ?>

                            <div class="profile-img-wrapper">
                                <?php if ($finalLogoSrc): ?>
                                    <img src="<?php echo htmlspecialchars($finalLogoSrc); ?>" 
                                         alt="Logo"
                                         class="profile-img shadow-sm bg-white">
                                <?php else: ?>
                                    <div class="profile-img-placeholder shadow-sm bg-light">
                                        <i class="fas fa-users fa-2x text-primary"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="card-body pt-5 mt-3 text-center px-4 d-flex flex-column">
                                <h5 class="card-title fw-bold mt-3 mb-1 text-truncate">
                                    <?php echo htmlspecialchars($org['nama_organisasi']); ?>
                                </h5>
                                
                                <div class="mb-3">
                                    <span class="badge bg-light text-primary border border-primary border-opacity-25 rounded-pill px-3">
                                        <?php echo htmlspecialchars($org['jenis_organisasi']); ?>
                                    </span>
                                </div>
                                
                                <p class="card-text text-muted small flex-grow-1 desc-clamp">
                                    <?php echo htmlspecialchars($org['deskripsi'] ?? 'Deskripsi belum tersedia.'); ?>
                                </p>

                                <div class="mt-4">
                                    <a href="index.php?action=detail&id=<?php echo $org['organisasi_id']; ?>" 
                                       class="btn btn-outline-primary rounded-pill w-100 fw-bold">
                                        Lihat Profil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div id="noResults" class="text-center py-5" style="display: none;">
            <i class="fas fa-search fa-3x text-muted opacity-25 mb-3"></i>
            <h5 class="text-muted">Organisasi tidak ditemukan.</h5>
        </div>

    </div>
</section>

<section class="py-5 bg-primary text-white position-relative overflow-hidden">
    <div class="position-absolute top-0 end-0 p-5 opacity-10">
        <i class="fas fa-graduation-cap fa-10x"></i>
    </div>
    <div class="container position-relative py-4 text-center">
        <h2 class="fw-bold mb-3">Siap Mengembangkan Potensi Dirimu?</h2>
        <p class="lead mb-4 opacity-75 col-lg-8 mx-auto">
            Bergabunglah dengan ratusan mahasiswa lainnya yang telah aktif berorganisasi. 
            Kembangkan softskill, kepemimpinan, dan jaringanmu di sini.
        </p>
        
        <?php if (!isset($_SESSION['anggota_id']) && !isset($_SESSION['admin_id'])): ?>
            <a href="index.php?action=register" class="btn btn-warning text-dark fw-bold btn-lg rounded-pill px-5 shadow">
                Daftar Sekarang <i class="fas fa-arrow-right ms-2"></i>
            </a>
        <?php else: ?>
            <a href="index.php?action=organisasi" class="btn btn-warning text-dark fw-bold btn-lg rounded-pill px-5 shadow">
                Cari Organisasi <i class="fas fa-search ms-2"></i>
            </a>
        <?php endif; ?>
        
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const orgItems = document.querySelectorAll('.org-item');
    const noResultsMsg = document.getElementById('noResults');

    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filterValue = this.value.toLowerCase();
            let hasResults = false;

            orgItems.forEach(item => {
                const title = item.querySelector('.card-title').textContent.toLowerCase();
                const category = item.getAttribute('data-kategori').toLowerCase();
                
                if (title.includes(filterValue) || category.includes(filterValue)) {
                    item.style.display = 'block';
                    item.classList.add('animate__animated', 'animate__fadeIn');
                    hasResults = true;
                } else {
                    item.style.display = 'none';
                    item.classList.remove('animate__animated', 'animate__fadeIn');
                }
            });

            if(hasResults) {
                noResultsMsg.style.display = 'none';
            } else {
                noResultsMsg.style.display = 'block';
            }
        });
    }
});
</script>

<style>
    .profile-card {
        overflow: visible !important; 
        margin-top: 50px;
    }

    .profile-img-wrapper {
        position: absolute;
        top: -50px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 50%;
        padding: 5px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .profile-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 1px solid #eee;
    }

    .profile-img-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
    }

    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.15)!important;
    }

    .desc-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 4.5em;
    }
    
    .ls-2 { letter-spacing: 2px; }
    .org-item { transition: all 0.3s ease; }
</style>

<?php include 'views/templates/footer.php'; ?>