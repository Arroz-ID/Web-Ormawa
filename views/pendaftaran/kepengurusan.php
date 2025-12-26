<?php include __DIR__ . '/../templates/header.php'; ?>

<style>
    .registration-section {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding-bottom: 5rem;
    }
    
    .form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        overflow: hidden;
        background: #fff;
    }

    .form-header {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        padding: 3rem 2rem;
        position: relative;
        color: white;
        text-align: center;
        overflow: hidden;
    }

    .form-header::before, .form-header::after {
        content: '';
        position: absolute;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .form-header::before { top: -30px; left: -30px; width: 150px; height: 150px; }
    .form-header::after { bottom: -50px; right: -20px; width: 200px; height: 200px; }

    .form-section-title {
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }
    .form-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
        margin-left: 1rem;
    }

    /* Animasi Form */
    .form-khusus {
        animation: slideUp 0.4s ease-out;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Custom File Input */
    .file-upload-wrapper {
        position: relative;
        height: 100px;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background: #f8fafc;
        cursor: pointer;
    }
    .file-upload-wrapper:hover { border-color: #2563eb; background: #eff6ff; }
    .file-upload-wrapper input[type="file"] {
        position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer;
    }
    .file-upload-text { text-align: center; color: #64748b; pointer-events: none; }

    /* Floating Labels */
    .form-floating > .form-control, .form-floating > .form-select {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    .form-floating > .form-control:focus ~ label { color: #2563eb; }
</style>

<div class="registration-section pt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                
                <a href="index.php?action=detail&id=<?php echo $organisasi['organisasi_id']; ?>" class="text-decoration-none text-muted mb-4 d-inline-flex align-items-center fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Profil Organisasi
                </a>

                <div class="form-card mt-3">
                    <div class="form-header">
                        <div class="position-relative z-1">
                            <div class="bg-white bg-opacity-25 rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas fa-file-signature fa-2x"></i>
                            </div>
                            <h2 class="fw-bold mb-1">Formulir Pendaftaran</h2>
                            <p class="mb-0 opacity-90"><?php echo htmlspecialchars($organisasi['nama_organisasi']); ?></p>
                        </div>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fa-lg me-3"></i>
                                <div><?php echo $error; ?></div>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="" enctype="multipart/form-data" class="needs-validation" novalidate>
                            
                            <div class="mb-5">
                                <div class="form-section-title">Langkah 1: Pilih Posisi</div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="jabatan_id" name="jabatan_id" onchange="tampilkanFormKhusus()" required>
                                        <option value="" selected disabled data-level="">-- Pilih Posisi yang Dilamar --</option>
                                        <?php foreach ($jabatan as $j): ?>
                                            <option value="<?php echo $j['jabatan_id']; ?>" data-level="<?php echo strtolower($j['nama_jabatan']); ?>">
                                                <?php echo htmlspecialchars($j['nama_jabatan']); ?> 
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="jabatan_id">Posisi yang Dilamar</label>
                                </div>
                                <div class="d-flex align-items-start text-muted small">
                                    <i class="fas fa-info-circle me-2 mt-1 text-primary"></i>
                                    <span>Formulir di bawah akan otomatis menyesuaikan dengan posisi yang Anda pilih.</span>
                                </div>
                            </div>

                            <div class="mb-5">
                                <div class="form-section-title">Langkah 2: Informasi Dasar</div>
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" name="pengalaman_organisasi" id="pengalaman" style="height: 120px" placeholder="Tuliskan pengalaman..." required></textarea>
                                    <label for="pengalaman">Pengalaman Organisasi Sebelumnya</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" name="motivasi" id="motivasi" style="height: 120px" placeholder="Tuliskan motivasi..." required></textarea>
                                    <label for="motivasi">Motivasi Bergabung</label>
                                </div>
                            </div>

                            <div id="dynamic-form-container">
                                <div class="form-section-title">Langkah 3: Pertanyaan Khusus</div>

                                <div id="form-leader" class="form-khusus d-none">
                                    <div class="bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-3 p-3 mb-4">
                                        <h6 class="text-warning fw-bold mb-1"><i class="fas fa-crown me-2"></i>Paket Top Leader</h6>
                                        <small class="text-muted">Fokus pada Visi, Misi, dan Strategi Jangka Panjang.</small>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="visi" style="height: 100px" placeholder="Visi"></textarea>
                                        <label>Visi Anda untuk Organisasi</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="misi" style="height: 150px" placeholder="Misi"></textarea>
                                        <label>Misi (Langkah Konkret)</label>
                                    </div>
                                    <div class="form-floating mb-4">
                                        <textarea class="form-control" name="studi_kasus" style="height: 100px" placeholder="Studi Kasus"></textarea>
                                        <label>Solusi Konflik Internal (Studi Kasus)</label>
                                    </div>
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Upload Grand Design (PDF)</label>
                                    <div class="file-upload-wrapper mb-2">
                                        <input type="file" name="berkas_pendukung" accept=".pdf" onchange="updateFileName(this)">
                                        <div class="file-upload-text">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-primary"></i><br>
                                            <span class="fw-bold text-dark">Klik untuk Upload</span><br>
                                            <span class="small file-name-display">Format PDF (Maks. 5MB)</span>
                                        </div>
                                    </div>
                                </div>

                                <div id="form-sekretaris" class="form-khusus d-none">
                                    <div class="bg-info bg-opacity-10 border border-info border-opacity-25 rounded-3 p-3 mb-4">
                                        <h6 class="text-info fw-bold mb-1"><i class="fas fa-book me-2"></i>Paket Administrasi</h6>
                                        <small class="text-muted">Fokus pada Kerapian, Surat Menyurat, dan Notulensi.</small>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Keahlian Software</label>
                                        <div class="d-flex flex-wrap gap-2">
                                            <input type="checkbox" class="btn-check" id="btn-word" name="skill_software[]" value="Ms. Word" autocomplete="off">
                                            <label class="btn btn-outline-primary rounded-pill btn-sm px-3" for="btn-word">Ms. Word</label>

                                            <input type="checkbox" class="btn-check" id="btn-excel" name="skill_software[]" value="Ms. Excel" autocomplete="off">
                                            <label class="btn btn-outline-primary rounded-pill btn-sm px-3" for="btn-excel">Ms. Excel</label>

                                            <input type="checkbox" class="btn-check" id="btn-canva" name="skill_software[]" value="Canva/Desain" autocomplete="off">
                                            <label class="btn btn-outline-primary rounded-pill btn-sm px-3" for="btn-canva">Canva/Desain</label>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-4">
                                        <select class="form-select" name="kecepatan_ketik">
                                            <option value="Biasa">Biasa</option>
                                            <option value="Cepat">Cepat</option>
                                            <option value="Sangat Cepat">Sangat Cepat (10 Jari)</option>
                                        </select>
                                        <label>Kecepatan Mengetik</label>
                                    </div>
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Upload Contoh Surat/Proposal</label>
                                    <div class="file-upload-wrapper mb-2">
                                        <input type="file" name="berkas_pendukung" accept=".pdf,.docx" onchange="updateFileName(this)">
                                        <div class="file-upload-text">
                                            <i class="fas fa-file-alt fa-2x mb-2 text-info"></i><br>
                                            <span class="fw-bold text-dark">Upload Dokumen</span><br>
                                            <span class="small file-name-display">PDF/DOCX (Contoh surat resmi)</span>
                                        </div>
                                    </div>
                                </div>

                                <div id="form-bendahara" class="form-khusus d-none">
                                    <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 p-3 mb-4">
                                        <h6 class="text-success fw-bold mb-1"><i class="fas fa-wallet me-2"></i>Paket Keuangan</h6>
                                        <small class="text-muted">Fokus pada Ketelitian, Pembukuan, dan Integritas.</small>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="paham_anggaran" style="height: 120px" placeholder="RAB"></textarea>
                                        <label>Jelaskan Cara Menyusun RAB Efektif</label>
                                    </div>
                                    <div class="card border-danger border-opacity-25 bg-danger bg-opacity-10 mb-3">
                                        <div class="card-body py-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" name="integritas" id="integritas">
                                                <label class="form-check-label small text-danger fw-bold" for="integritas">
                                                    Saya bersedia mengganti kerugian jika terjadi selisih kas karena kelalaian pribadi.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="form-koordinator" class="form-khusus d-none">
                                    <div class="bg-secondary bg-opacity-10 border border-secondary border-opacity-25 rounded-3 p-3 mb-4">
                                        <h6 class="text-dark fw-bold mb-1"><i class="fas fa-users-cog me-2"></i>Paket Koordinator</h6>
                                        <small class="text-muted">Fokus pada Manajemen Tim dan Teknis Divisi.</small>
                                    </div>
                                    <?php if (!empty($divisi)): ?>
                                    <div class="form-floating mb-3">
                                        <select class="form-select" name="divisi_id">
                                            <option value="">-- Pilih Divisi --</option>
                                            <?php foreach ($divisi as $d): ?>
                                                <option value="<?php echo $d['divisi_id']; ?>"><?php echo htmlspecialchars($d['nama_divisi']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label>Pilih Divisi yang Dipimpin</label>
                                    </div>
                                    <?php endif; ?>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="gaya_kepemimpinan" style="height: 100px" placeholder="Gaya Pimpin"></textarea>
                                        <label>Cara Menegur Anggota Pasif</label>
                                    </div>
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Upload Portofolio (Opsional)</label>
                                    <div class="file-upload-wrapper mb-2">
                                        <input type="file" name="berkas_pendukung" onchange="updateFileName(this)">
                                        <div class="file-upload-text">
                                            <i class="fas fa-briefcase fa-2x mb-2 text-dark"></i><br>
                                            <span class="fw-bold text-dark">Upload Portofolio</span><br>
                                            <span class="small file-name-display">Karya Terbaik (Opsional)</span>
                                        </div>
                                    </div>
                                </div>

                                <div id="form-anggota" class="form-khusus d-none">
                                    <div class="bg-light border rounded-3 p-3 mb-4">
                                        <h6 class="text-primary fw-bold mb-1"><i class="fas fa-hands-helping me-2"></i>Paket Staff/Anggota</h6>
                                        <small class="text-muted">Fokus pada Minat, Bakat, dan Kontribusi.</small>
                                    </div>
                                    <?php if (!empty($divisi)): ?>
                                    <div class="form-floating mb-3">
                                        <select class="form-select" name="divisi_id">
                                            <option value="">-- Pilih Divisi --</option>
                                            <?php foreach ($divisi as $d): ?>
                                                <option value="<?php echo $d['divisi_id']; ?>"><?php echo htmlspecialchars($d['nama_divisi']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label>Pilih Divisi Peminatan</label>
                                    </div>
                                    <?php endif; ?>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="minat_bakat" style="height: 100px" placeholder="Minat Bakat"></textarea>
                                        <label>Minat & Bakat Khusus</label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">Ketersediaan Waktu (Skala 1-10)</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <input type="range" class="form-range flex-grow-1" name="ketersediaan_waktu" min="1" max="10" value="5" oninput="this.nextElementSibling.value = this.value">
                                            <output class="fw-bold text-primary fs-5 border px-3 py-1 rounded">5</output>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-5">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold py-3 shadow-sm hover-scale">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Pendaftaran
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    tampilkanFormKhusus();
});

function tampilkanFormKhusus() {
    var select = document.getElementById("jabatan_id");
    var selectedOption = select.options[select.selectedIndex];
    var namaJabatan = selectedOption.getAttribute('data-level') ? selectedOption.getAttribute('data-level').toLowerCase() : '';

    // 1. RESET SEMUA: Sembunyikan & DISABLE
    var forms = document.querySelectorAll('.form-khusus');
    forms.forEach(function(el) {
        el.classList.add('d-none');
        
        // PENTING: Disable input agar tidak terkirim ke server
        var inputs = el.querySelectorAll('input, textarea, select');
        inputs.forEach(function(input) {
            input.disabled = true; 
            if(input.type === 'checkbox' || input.type === 'radio') {
                input.checked = false;
            } else {
                input.value = ''; // Bersihkan nilai agar aman
            }
        });
    });

    // 2. TENTUKAN TARGET
    var targetId = "form-anggota"; // Default

    if (namaJabatan.includes("ketua") || namaJabatan.includes("wakil") || namaJabatan.includes("gubernur") || namaJabatan.includes("presiden")) {
        targetId = "form-leader";
    } else if (namaJabatan.includes("sekretaris")) {
        targetId = "form-sekretaris";
    } else if (namaJabatan.includes("bendahara")) {
        targetId = "form-bendahara";
    } else if (namaJabatan.includes("koordinator") || namaJabatan.includes("kepala") || namaJabatan.includes("ketua divisi")) {
        targetId = "form-koordinator";
    }

    // 3. AKTIFKAN TARGET: Tampilkan & ENABLE
    var targetEl = document.getElementById(targetId);
    if (targetEl) {
        targetEl.classList.remove('d-none');
        
        // Hidupkan kembali input
        var activeInputs = targetEl.querySelectorAll('input, textarea, select');
        activeInputs.forEach(function(input) {
            input.disabled = false;
        });

        targetEl.style.animation = 'none';
        targetEl.offsetHeight; 
        targetEl.style.animation = null; 
    }
}

function updateFileName(input) {
    var fileName = input.files[0] ? input.files[0].name : "Format PDF (Maks. 5MB)";
    var display = input.nextElementSibling.querySelector('.file-name-display');
    var icon = input.nextElementSibling.querySelector('i');
    
    if(input.files[0]) {
        display.textContent = "File Terpilih: " + fileName;
        display.classList.add('text-success', 'fw-bold');
        icon.classList.replace('text-primary', 'text-success');
        icon.classList.replace('text-info', 'text-success');
        icon.classList.replace('text-dark', 'text-success');
        icon.classList.replace('fa-cloud-upload-alt', 'fa-check-circle');
    }
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>