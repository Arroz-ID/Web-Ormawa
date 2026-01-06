<?php include __DIR__ . '/../templates/header.php'; ?>

<?php $old = isset($old) ? $old : []; ?>

<style>
    .registration-section { background-color: #f8f9fa; min-height: 100vh; padding-bottom: 5rem; }
    .form-card { border: none; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); overflow: hidden; background: #fff; }
    .form-header { background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); padding: 3rem 2rem; position: relative; color: white; text-align: center; overflow: hidden; }
    .form-header::before, .form-header::after { content: ''; position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; }
    .form-header::before { top: -30px; left: -30px; width: 150px; height: 150px; }
    .form-header::after { bottom: -50px; right: -20px; width: 200px; height: 200px; }
    .form-section-title { font-size: 0.9rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #94a3b8; margin-bottom: 1.5rem; display: flex; align-items: center; }
    .form-section-title::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; margin-left: 1rem; }
    .form-khusus { animation: slideUp 0.4s ease-out; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .file-upload-wrapper { position: relative; height: 100px; border: 2px dashed #cbd5e1; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; background: #f8fafc; cursor: pointer; }
    .file-upload-wrapper:hover { border-color: #2563eb; background: #eff6ff; }
    .file-upload-wrapper input[type="file"] { position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    .file-upload-text { text-align: center; color: #64748b; pointer-events: none; }
    .form-floating > .form-control, .form-floating > .form-select { border-radius: 12px; border: 1px solid #e2e8f0; }
    .form-floating > .form-control:focus ~ label { color: #2563eb; }
</style>

<div class="registration-section pt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                
                <a href="index.php?action=detail&id=<?php echo $organisasi['organisasi_id']; ?>" class="text-decoration-none text-muted mb-4 d-inline-flex align-items-center fw-bold hover-scale">
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
                        
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fa-lg me-3"></i>
                                <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php?action=daftar_kepengurusan&organisasi_id=<?php echo $organisasi['organisasi_id']; ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
                            
                            <div class="mb-5">
                                <div class="form-section-title">Langkah 1: Pilih Posisi</div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="jabatan_id" name="jabatan_id" onchange="tampilkanFormKhusus()" required>
                                        <option value="" disabled <?php echo empty($old['jabatan_id']) ? 'selected' : ''; ?> data-level="">-- Pilih Posisi yang Dilamar --</option>
                                        <?php foreach ($jabatan as $j): ?>
                                            <option value="<?php echo $j['jabatan_id']; ?>" 
                                                    data-level="<?php echo strtolower($j['nama_jabatan']); ?>"
                                                    <?php echo (isset($old['jabatan_id']) && $old['jabatan_id'] == $j['jabatan_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($j['nama_jabatan']); ?> 
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="jabatan_id">Posisi yang Dilamar <span class="text-danger">*</span></label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" name="divisi_id">
                                        <option value="">-- Pilih Divisi (Jika Ada) --</option>
                                        <?php foreach ($divisi_tersedia as $d): ?>
                                            <option value="<?php echo $d['divisi_id']; ?>"
                                                <?php echo (isset($old['divisi_id']) && $old['divisi_id'] == $d['divisi_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($d['nama_divisi']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label>Divisi Pilihan <span class="text-muted small">(Opsional untuk Ketua)</span></label>
                                </div>
                            </div>

                            <div class="mb-5">
                                <div class="form-section-title">Langkah 2: Informasi Dasar</div>
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" name="pengalaman_organisasi" id="pengalaman" style="height: 120px" placeholder="Tuliskan pengalaman..."><?php echo isset($old['pengalaman_organisasi']) ? htmlspecialchars($old['pengalaman_organisasi']) : ''; ?></textarea>
                                    <label for="pengalaman">Pengalaman Organisasi <span class="text-muted small">(Opsional)</span></label>
                                </div>
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" name="motivasi" id="motivasi" style="height: 120px" placeholder="Tuliskan motivasi..." required><?php echo isset($old['motivasi']) ? htmlspecialchars($old['motivasi']) : ''; ?></textarea>
                                    <label for="motivasi">Motivasi Bergabung <span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div id="dynamic-form-container">
                                <div class="form-section-title">Langkah 3: Pertanyaan Khusus</div>
                                <p class="text-muted small text-center fst-italic mb-3" id="no-position-msg">Silakan pilih posisi terlebih dahulu untuk melihat pertanyaan khusus.</p>

                                <div id="form-leader" class="form-khusus d-none">
                                    <div class="bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-3 p-3 mb-4">
                                        <h6 class="text-warning fw-bold mb-1"><i class="fas fa-crown me-2"></i>Paket Top Leader</h6>
                                        <small class="text-muted">Fokus pada Visi, Misi, dan Strategi.</small>
                                    </div>
                                    
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="alasan_ketua" style="height: 120px" placeholder="Alasan Utama"><?php echo isset($old['alasan_ketua']) ? htmlspecialchars($old['alasan_ketua']) : ''; ?></textarea>
                                        <label>Alasan Ingin Menjadi Ketua <span class="text-danger">*</span></label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="visi" style="height: 100px" placeholder="Visi"><?php echo isset($old['visi']) ? htmlspecialchars($old['visi']) : ''; ?></textarea>
                                        <label>Visi Anda <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="misi" style="height: 150px" placeholder="Misi"><?php echo isset($old['misi']) ? htmlspecialchars($old['misi']) : ''; ?></textarea>
                                        <label>Misi (Langkah Konkret) <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="form-floating mb-4">
                                        <textarea class="form-control" name="studi_kasus" style="height: 100px" placeholder="Studi Kasus"><?php echo isset($old['studi_kasus']) ? htmlspecialchars($old['studi_kasus']) : ''; ?></textarea>
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
                                        <small class="text-muted">Fokus pada Kerapian dan Surat Menyurat.</small>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Keahlian Software <span class="text-danger">*</span></label>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php $skills = isset($old['skill_software']) ? $old['skill_software'] : []; ?>
                                            
                                            <input type="checkbox" class="btn-check" id="btn-word" name="skill_software[]" value="Ms. Word" autocomplete="off" <?php echo in_array('Ms. Word', $skills) ? 'checked' : ''; ?>>
                                            <label class="btn btn-outline-primary rounded-pill btn-sm px-3" for="btn-word">Ms. Word</label>

                                            <input type="checkbox" class="btn-check" id="btn-excel" name="skill_software[]" value="Ms. Excel" autocomplete="off" <?php echo in_array('Ms. Excel', $skills) ? 'checked' : ''; ?>>
                                            <label class="btn btn-outline-primary rounded-pill btn-sm px-3" for="btn-excel">Ms. Excel</label>

                                            <input type="checkbox" class="btn-check" id="btn-canva" name="skill_software[]" value="Canva/Desain" autocomplete="off" <?php echo in_array('Canva/Desain', $skills) ? 'checked' : ''; ?>>
                                            <label class="btn btn-outline-primary rounded-pill btn-sm px-3" for="btn-canva">Canva/Desain</label>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-4">
                                        <select class="form-select" name="kecepatan_ketik">
                                            <option value="Biasa" <?php echo (isset($old['kecepatan_ketik']) && $old['kecepatan_ketik'] == 'Biasa') ? 'selected' : ''; ?>>Biasa</option>
                                            <option value="Cepat" <?php echo (isset($old['kecepatan_ketik']) && $old['kecepatan_ketik'] == 'Cepat') ? 'selected' : ''; ?>>Cepat</option>
                                            <option value="Sangat Cepat" <?php echo (isset($old['kecepatan_ketik']) && $old['kecepatan_ketik'] == 'Sangat Cepat') ? 'selected' : ''; ?>>Sangat Cepat (10 Jari)</option>
                                        </select>
                                        <label>Kecepatan Mengetik</label>
                                    </div>
                                </div>

                                <div id="form-bendahara" class="form-khusus d-none">
                                    <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 p-3 mb-4">
                                        <h6 class="text-success fw-bold mb-1"><i class="fas fa-wallet me-2"></i>Paket Keuangan</h6>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="paham_anggaran" style="height: 120px" placeholder="RAB"><?php echo isset($old['paham_anggaran']) ? htmlspecialchars($old['paham_anggaran']) : ''; ?></textarea>
                                        <label>Pengalaman Menyusun RAB</label>
                                    </div>
                                    <div class="card border-danger border-opacity-25 bg-danger bg-opacity-10 mb-3">
                                        <div class="card-body py-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" name="integritas" id="integritas" <?php echo isset($old['integritas']) ? 'checked' : ''; ?>>
                                                <label class="form-check-label small text-danger fw-bold" for="integritas">
                                                    Saya bersedia bertanggung jawab penuh atas kas organisasi. <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="form-koordinator" class="form-khusus d-none">
                                    <div class="bg-secondary bg-opacity-10 border border-secondary border-opacity-25 rounded-3 p-3 mb-4">
                                        <h6 class="text-dark fw-bold mb-1"><i class="fas fa-users-cog me-2"></i>Paket Koordinator</h6>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="gaya_kepemimpinan" style="height: 100px" placeholder="Gaya Pimpin"><?php echo isset($old['gaya_kepemimpinan']) ? htmlspecialchars($old['gaya_kepemimpinan']) : ''; ?></textarea>
                                        <label>Gaya Kepemimpinan Anda</label>
                                    </div>
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2">Upload Portofolio (Opsional)</label>
                                    <div class="file-upload-wrapper mb-2">
                                        <input type="file" name="berkas_pendukung" onchange="updateFileName(this)">
                                        <div class="file-upload-text">
                                            <i class="fas fa-briefcase fa-2x mb-2 text-dark"></i><br>
                                            <span class="fw-bold text-dark">Upload Portofolio</span>
                                        </div>
                                    </div>
                                </div>

                                <div id="form-anggota" class="form-khusus d-none">
                                    <div class="bg-light border rounded-3 p-3 mb-4">
                                        <h6 class="text-primary fw-bold mb-1"><i class="fas fa-hands-helping me-2"></i>Paket Anggota</h6>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="minat_bakat" style="height: 100px" placeholder="Minat Bakat"><?php echo isset($old['minat_bakat']) ? htmlspecialchars($old['minat_bakat']) : ''; ?></textarea>
                                        <label>Minat & Bakat Khusus</label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">Komitmen Waktu (1-10)</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <input type="range" class="form-range flex-grow-1" name="ketersediaan_waktu" min="1" max="10" value="<?php echo isset($old['ketersediaan_waktu']) ? $old['ketersediaan_waktu'] : '5'; ?>" oninput="this.nextElementSibling.value = this.value">
                                            <output class="fw-bold text-primary fs-5 border px-3 py-1 rounded"><?php echo isset($old['ketersediaan_waktu']) ? $old['ketersediaan_waktu'] : '5'; ?></output>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-5 mt-4 border-top pt-4">
                                <div class="form-section-title">Langkah 4: Berkas Wajib</div>
                                <label class="form-label fw-bold small text-muted text-uppercase mb-2">Upload CV / KTM / KHS <span class="text-danger">*</span></label>
                                <div class="file-upload-wrapper mb-2 bg-white">
                                    <input type="file" name="berkas" accept=".pdf,.jpg,.jpeg,.png" onchange="updateFileName(this)" required>
                                    <div class="file-upload-text">
                                        <i class="fas fa-id-card fa-2x mb-2 text-primary"></i><br>
                                        <span class="fw-bold text-dark">Upload Berkas Wajib</span><br>
                                        <span class="small file-name-display text-muted">Format PDF/JPG (Maks. 2MB)</span>
                                    </div>
                                </div>
                                <?php if(isset($_SESSION['error']) && strpos($_SESSION['error'], 'upload') !== false): ?>
                                    <div class="text-danger small fw-bold mt-2"><i class="fas fa-exclamation-triangle"></i> Mohon upload ulang file ini.</div>
                                <?php endif; ?>
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

    // 1. Reset Semua
    var forms = document.querySelectorAll('.form-khusus');
    document.getElementById('no-position-msg').style.display = 'none';
    
    forms.forEach(function(el) {
        el.classList.add('d-none');
        // PENTING: Hanya disable jika element SEDANG tersembunyi
        // Jika tidak, nanti validasi required browser jadi kacau
        var inputs = el.querySelectorAll('input, textarea, select');
        inputs.forEach(function(input) {
            input.disabled = true; 
        });
    });

    if(namaJabatan === '') {
        document.getElementById('no-position-msg').style.display = 'block';
        return;
    }

    // 2. Tentukan Target
    var targetId = "form-anggota"; // Default fallback
    if (namaJabatan.includes("ketua") || namaJabatan.includes("wakil") || namaJabatan.includes("gubernur") || namaJabatan.includes("presiden")) {
        targetId = "form-leader";
    } else if (namaJabatan.includes("sekretaris")) {
        targetId = "form-sekretaris";
    } else if (namaJabatan.includes("bendahara")) {
        targetId = "form-bendahara";
    } else if (namaJabatan.includes("koordinator") || namaJabatan.includes("kepala") || namaJabatan.includes("ketua divisi")) {
        targetId = "form-koordinator";
    }

    // 3. Aktifkan Target
    var targetEl = document.getElementById(targetId);
    if (targetEl) {
        targetEl.classList.remove('d-none');
        var activeInputs = targetEl.querySelectorAll('input, textarea, select');
        activeInputs.forEach(function(input) {
            input.disabled = false;
        });
    }
}

function updateFileName(input) {
    var fileName = input.files[0] ? input.files[0].name : "Belum ada file";
    var container = input.nextElementSibling;
    var display = container.querySelector('.file-name-display');
    var icon = container.querySelector('i');
    
    if(input.files[0]) {
        display.textContent = "File: " + fileName;
        display.classList.remove('text-muted');
        display.classList.add('text-success', 'fw-bold');
        icon.className = 'fas fa-check-circle fa-2x mb-2 text-success';
    }
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>