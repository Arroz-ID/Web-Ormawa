<?php include __DIR__ . '/../../views/templates/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../../views/templates/sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-9 px-md-4 py-4">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h3 fw-bold text-gray-800">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Identitas Organisasi
                </h1>
                <a href="index.php?action=ormawa_profil_lengkap" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <form action="index.php?action=ormawa_edit_profil" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $organisasi['organisasi_id'] ?>">
                        
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="mb-3 position-relative d-inline-block">
                                    <?php 
                                        $logo = !empty($organisasi['logo']) && file_exists('assets/images/profil/' . $organisasi['logo']) 
                                                ? 'assets/images/profil/' . $organisasi['logo'] 
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($organisasi['nama_organisasi']) . '&background=random&size=200';
                                    ?>
                                    <img src="<?= $logo ?>?t=<?= time() ?>" alt="Logo Preview" class="rounded-circle img-thumbnail shadow-sm" style="width: 200px; height: 200px; object-fit: cover;" id="logoPreview">
                                    
                                    <label for="logoInput" class="position-absolute bottom-0 end-0 bg-white border rounded-circle p-2 shadow-sm" style="cursor: pointer; width: 40px; height: 40px;">
                                        <i class="fas fa-camera text-primary"></i>
                                    </label>
                                </div>
                                
                                <div class="mt-2">
                                    <label for="logoInput" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fas fa-upload me-1"></i> Ganti Logo
                                    </label>
                                    <input type="file" id="logoInput" class="d-none" accept="image/png, image/jpeg, image/jpg">
                                    <input type="hidden" name="cropped_image" id="croppedImage">
                                </div>
                                <small class="text-muted d-block mt-2" style="font-size: 0.8rem;">
                                    Format: JPG/PNG. Maks: 2MB.<br>Disarankan rasio 1:1 (Persegi).
                                </small>
                            </div>

                            <div class="col-md-8">
                                <div class="row g-3">
                                    
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Nama Organisasi</label>
                                        <input type="text" name="nama_organisasi" class="form-control form-control-lg fw-bold" 
                                               value="<?= htmlspecialchars($organisasi['nama_organisasi']) ?>" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Nomor WhatsApp Admin</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white border-success">
                                                <i class="fab fa-whatsapp"></i>
                                            </span>
                                            <input type="text" name="no_whatsapp" class="form-control border-success" 
                                                   value="<?= htmlspecialchars($organisasi['no_whatsapp'] ?? '') ?>" 
                                                   placeholder="08123xxxx (Tanpa +62)">
                                        </div>
                                        <div class="form-text small">Nomor ini akan menjadi tombol "Hubungi Admin" bagi user.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Jenis Organisasi</label>
                                        <select name="jenis_organisasi" class="form-select">
                                            <option value="BEM" <?= ($organisasi['jenis_organisasi'] == 'BEM') ? 'selected' : '' ?>>BEM</option>
                                            <option value="DPM" <?= ($organisasi['jenis_organisasi'] == 'DPM') ? 'selected' : '' ?>>DPM</option>
                                            <option value="HIMA" <?= ($organisasi['jenis_organisasi'] == 'HIMA') ? 'selected' : '' ?>>Himpunan Mahasiswa (HIMA)</option>
                                            <option value="UKM" <?= ($organisasi['jenis_organisasi'] == 'UKM') ? 'selected' : '' ?>>UKM</option>
                                            <option value="KOMUNITAS" <?= ($organisasi['jenis_organisasi'] == 'KOMUNITAS') ? 'selected' : '' ?>>Komunitas</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Tanggal Berdiri</label>
                                        <input type="date" name="tanggal_berdiri" class="form-control" 
                                               value="<?= $organisasi['tanggal_berdiri'] ?>">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Deskripsi Singkat</label>
                                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan secara singkat tentang organisasi ini..."><?= htmlspecialchars($organisasi['deskripsi']) ?></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Visi</label>
                                        <textarea name="visi" class="form-control bg-light" rows="5" placeholder="Tuliskan visi organisasi..."><?= htmlspecialchars($organisasi['visi']) ?></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Misi</label>
                                        <textarea name="misi" class="form-control bg-light" rows="5" placeholder="Tuliskan misi organisasi..."><?= htmlspecialchars($organisasi['misi']) ?></textarea>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top text-end">
                                    <button type="reset" class="btn btn-light border rounded-pill me-2 px-4">Reset</button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-crop-alt me-2"></i>Sesuaikan Posisi Logo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 bg-dark text-center">
                <div class="img-container" style="max-height: 500px;">
                    <img id="imageToCrop" src="" style="max-width: 100%; display: block; margin: 0 auto;">
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" id="cropAndSave">
                    <i class="fas fa-check me-2"></i>Potong & Pakai
                </button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    let cropper;
    const logoInput = document.getElementById('logoInput');
    const imageToCrop = document.getElementById('imageToCrop');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

    // 1. Saat file dipilih
    logoInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            
            // Validasi tipe file
            if (!file.type.startsWith('image/')) {
                alert('Mohon pilih file gambar (JPG/PNG)');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                imageToCrop.src = e.target.result;
                
                // Tampilkan Modal
                cropModal.show();
                
                // Reset Cropper jika sudah ada sebelumnya
                if (cropper) {
                    cropper.destroy();
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // 2. Inisialisasi Cropper saat modal terbuka penuh
    document.getElementById('cropModal').addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1, // Memaksa rasio persegi 1:1
            viewMode: 1,
            autoCropArea: 0.8,
            dragMode: 'move'
        });
    });

    // 3. Saat tombol "Potong & Simpan" diklik
    document.getElementById('cropAndSave').addEventListener('click', function() {
        if (!cropper) return;

        // Ambil hasil crop
        const canvas = cropper.getCroppedCanvas({
            width: 500,  // Resize otomatis ke 500x500 px agar ringan
            height: 500
        });

        // Convert ke Base64
        const base64data = canvas.toDataURL('image/jpeg', 0.8); // Kualitas 80%

        // Masukkan ke Hidden Input untuk dikirim ke Server
        document.getElementById('croppedImage').value = base64data;
        
        // Update Preview di Halaman
        document.getElementById('logoPreview').src = base64data;
        
        // Tutup Modal
        cropModal.hide();
    });
</script>

<?php include __DIR__ . '/../../views/templates/footer.php'; ?>