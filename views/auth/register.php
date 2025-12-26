<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0 overflow-hidden rounded-4">
                
                <div class="position-relative" 
                     style="height: 250px; 
                            background-image: url('assets/images/gedung.jpg'); 
                            background-size: cover; 
                            background-position: center;">
                    
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; 
                                background: linear-gradient(to bottom, rgba(13, 110, 253, 0.3), rgba(10, 88, 202, 0.9));">
                    </div>

                    <div class="position-absolute bottom-0 start-0 p-4 text-white w-100 text-center">
                        <h2 class="fw-bold mb-1">Bergabunglah Bersama Kami</h2>
                        <p class="mb-0 opacity-90 small">Sistem Informasi Organisasi Mahasiswa Politeknik Negeri Sambas</p>
                    </div>
                </div>

                <div class="card-body p-5 bg-white">
                    
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-primary"><i class="fas fa-user-plus me-2"></i>Buat Akun Baru</h4>
                        <p class="text-muted small">Lengkapi data diri Anda untuk mengakses sistem</p>
                    </div>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo $base_path; ?>index.php?action=register" method="POST" enctype="multipart/form-data">
                        
                        <div class="bg-light p-3 rounded-3 mb-4">
                            <h6 class="text-primary fw-bold small text-uppercase mb-3 border-bottom pb-2">
                                <i class="fas fa-lock me-1"></i> Data Akun
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">NIM</label>
                                    <input type="text" class="form-control" name="nim" placeholder="Contoh: 320240xxxx" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama_lengkap" placeholder="Sesuai KTM" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="email@example.com" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Min. 6 karakter" required>
                                        <span class="input-group-text bg-white" onclick="togglePassword('password', 'icon-pass')" style="cursor: pointer;">
                                            <i class="fas fa-eye text-muted" id="icon-pass"></i>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Ulangi password" required>
                                        <span class="input-group-text bg-white" onclick="togglePassword('confirm_password', 'icon-confirm')" style="cursor: pointer;">
                                            <i class="fas fa-eye text-muted" id="icon-confirm"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded-3 mb-4">
                            <h6 class="text-success fw-bold small text-uppercase mb-3 border-bottom pb-2">
                                <i class="fas fa-graduation-cap me-1"></i> Data Akademik
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Jurusan</label>
                                    <select class="form-select" name="jurusan" id="jurusan" onchange="updateProdi()" required>
                                        <option value="">-- Pilih Jurusan --</option>
                                        <option value="Manajemen Informatika">Jurusan Manajemen Informatika</option>
                                        <option value="Agrobisnis">Jurusan Agrobisnis</option>
                                        <option value="Teknik Mesin">Jurusan Teknik Mesin</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Program Studi</label>
                                    <select class="form-select" name="prodi" id="prodi" disabled required>
                                        <option value="">-- Pilih Jurusan Terlebih Dahulu --</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Angkatan</label>
                                    <select class="form-select" name="angkatan" required>
                                        <?php 
                                        $thn = date('Y');
                                        for($i=$thn; $i>=$thn-5; $i--){ echo "<option value='$i'>$i</option>"; }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">No. WhatsApp</label>
                                    <input type="number" class="form-control" name="no_telepon" placeholder="08xxxxxxxxxx" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg shadow fw-bold">
                                <i class="fas fa-paper-plane me-2"></i>Daftar Sekarang
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="small text-muted mb-0">Sudah memiliki akun?</p>
                        <a href="<?php echo $base_path; ?>index.php?action=login" class="fw-bold text-decoration-none">Login disini</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. DATA PRODI (Sesuai Request)
    const dataProdi = {
        "Manajemen Informatika": [
            "D3 Manajemen Informatika",
            "D4 (Sarjana Terapan) Teknik Multimedia",
            "D4 (Sarjana Terapan) Akuntansi Keuangan Perusahaan",
            "D4 (Sarjana Terapan) Akuntansi Sektor Publik"
        ],
        "Agrobisnis": [
            "D3 Agrobisnis",
            "D4 (Sarjana Terapan) Agroindustri Pangan",
            "D4 (Sarjana Terapan) Agribisnis Perikanan dan Kelautan",
            "D4 (Sarjana Terapan) Manajemen Bisnis Pariwisata",
            "D4 (Sarjana Terapan) Pengelolaan Perkebunan"
        ],
        "Teknik Mesin": [
            "D3 Teknik Mesin",
            "D4 (Sarjana Terapan) Teknik Mesin Pertanian"
        ]
    };

    // 2. FUNGSI UPDATE DROPDOWN PRODI
    function updateProdi() {
        const jurusanSelect = document.getElementById("jurusan");
        const prodiSelect = document.getElementById("prodi");
        const selectedJurusan = jurusanSelect.value;

        // Reset
        prodiSelect.innerHTML = '<option value="">-- Pilih Program Studi --</option>';
        prodiSelect.disabled = true;

        if (selectedJurusan && dataProdi[selectedJurusan]) {
            prodiSelect.disabled = false;
            dataProdi[selectedJurusan].forEach(function(prodi) {
                const option = document.createElement("option");
                option.value = prodi;
                option.text = prodi;
                prodiSelect.add(option);
            });
        } else {
            prodiSelect.innerHTML = '<option value="">-- Pilih Jurusan Terlebih Dahulu --</option>';
        }
    }

    // 3. FUNGSI TOGGLE PASSWORD (Bisa dipakai untuk password & confirm password)
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>