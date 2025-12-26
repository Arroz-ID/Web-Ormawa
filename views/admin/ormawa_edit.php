<?php include __DIR__ . '/../templates/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<style>
    .img-container {
        max-height: 500px;
        background-color: #f7f7f7;
        overflow: hidden;
        text-align: center;
    }
    .img-container img { max-width: 100%; }
    .preview-box {
        width: 150px; height: 150px;
        overflow: hidden; margin: 0 auto;
        border: 2px solid #ddd;
    }
    .rounded-circle-preview { border-radius: 50%; }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark"><i class="fas fa-edit me-2"></i>Edit Profil Organisasi</h2>
                <a href="index.php?action=ormawa_dashboard" class="btn btn-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-warning bg-opacity-10 py-3">
                    <h5 class="mb-0 fw-bold text-dark">Formulir Perubahan Data</h5>
                </div>
                <div class="card-body p-4">
                    
                    <form method="POST" action="" enctype="multipart/form-data" id="formProfil">
                        <input type="hidden" name="cropped_image" id="cropped_image">

                        <div class="mb-4 text-center">
                            <label class="form-label fw-bold small text-uppercase text-muted d-block">Logo Organisasi</label>
                            
                            <div class="mb-3 position-relative d-inline-block">
                                <img id="mainPreview" 
                                     src="<?php echo (!empty($organisasi['logo']) && file_exists('assets/images/' . $organisasi['logo'])) ? 'assets/images/' . htmlspecialchars($organisasi['logo']) : ''; ?>" 
                                     class="rounded-circle border shadow-sm"
                                     style="width: 150px; height: 150px; object-fit: cover; display: <?php echo (!empty($organisasi['logo'])) ? 'block' : 'none'; ?>;">
                                
                                <div id="placeholderPreview" class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center border shadow-sm" 
                                     style="width: 150px; height: 150px; display: <?php echo (!empty($organisasi['logo'])) ? 'none' : 'flex'; ?>;">
                                    <i class="fas fa-camera fa-3x text-secondary opacity-50"></i>
                                </div>
                            </div>

                            <div class="input-group w-75 mx-auto mt-3">
                                <input type="file" class="form-control" id="inputImage" accept="image/*">
                                <label class="input-group-text small text-muted"><i class="fas fa-upload"></i></label>
                            </div>
                            <div class="form-text small">Pilih foto, lalu crop sesuai keinginan. (Max 2MB)</div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold small text-uppercase text-muted">Nama Organisasi</label>
                                <input type="text" class="form-control" name="nama_organisasi" 
                                       value="<?php echo htmlspecialchars($organisasi['nama_organisasi']); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-uppercase text-muted">Jenis</label>
                                <select class="form-select" name="jenis_organisasi">
                                    <option value="BEM" <?php echo ($organisasi['jenis_organisasi'] == 'BEM') ? 'selected' : ''; ?>>BEM</option>
                                    <option value="DPM" <?php echo ($organisasi['jenis_organisasi'] == 'DPM') ? 'selected' : ''; ?>>DPM</option>
                                    <option value="HIMA" <?php echo ($organisasi['jenis_organisasi'] == 'HIMA') ? 'selected' : ''; ?>>HIMA</option>
                                    <option value="UKM" <?php echo ($organisasi['jenis_organisasi'] == 'UKM') ? 'selected' : ''; ?>>UKM</option>
                                    <option value="KOMUNITAS" <?php echo ($organisasi['jenis_organisasi'] == 'KOMUNITAS') ? 'selected' : ''; ?>>Komunitas</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Tanggal Berdiri</label>
                            <input type="date" class="form-control" name="tanggal_berdiri" 
                                   value="<?php echo $organisasi['tanggal_berdiri']; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Deskripsi Singkat</label>
                            <textarea class="form-control" name="deskripsi" rows="4"><?php echo htmlspecialchars($organisasi['deskripsi']); ?></textarea>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Visi</label>
                                <textarea class="form-control" name="visi" rows="5"><?php echo htmlspecialchars($organisasi['visi']); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Misi</label>
                                <textarea class="form-control" name="misi" rows="5"><?php echo htmlspecialchars($organisasi['misi']); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCrop" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-crop-alt me-2"></i>Sesuaikan Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="img-container">
                            <img id="imageToCrop" src="" alt="Picture">
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="mb-2 fw-bold text-muted small">PREVIEW</div>
                        <div class="preview-box rounded-circle-preview" id="previewResult"></div>
                        
                        <div class="d-grid gap-2 mt-3">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm active" id="btnRound" onclick="setMode('round')">Bulat</button>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btnSquare" onclick="setMode('square')">Kotak</button>
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="cropper.rotate(90)"><i class="fas fa-redo"></i> Putar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary fw-bold px-4" id="cropAndSave">
                    <i class="fas fa-check me-2"></i>Potong & Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let cropper;
    const inputImage = document.getElementById('inputImage');
    const imageToCrop = document.getElementById('imageToCrop');
    const previewResult = document.getElementById('previewResult');
    const mainPreview = document.getElementById('mainPreview');
    const placeholderPreview = document.getElementById('placeholderPreview');
    const croppedImageInput = document.getElementById('cropped_image');
    
    // Instance Modal Bootstrap
    const modalElement = document.getElementById('modalCrop');
    const modalCrop = new bootstrap.Modal(modalElement);

    let cropMode = 'round';

    // 1. SAAT FILE DIPILIH
    inputImage.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            const reader = new FileReader();
            reader.onload = function (e) {
                imageToCrop.src = e.target.result;
                modalCrop.show(); // Tampilkan Modal
            };
            reader.readAsDataURL(file);
        }
    });

    // 2. INISIALISASI CROPPER SAAT MODAL MUNCUL
    modalElement.addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1, // Wajib 1:1
            viewMode: 1,
            preview: '.preview-box',
            dragMode: 'move',
            autoCropArea: 0.8
        });
        setMode('round'); // Default mode
    });

    // 3. HANCURKAN CROPPER SAAT MODAL TUTUP
    modalElement.addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        inputImage.value = ''; // Reset input agar bisa pilih file sama
    });

    // 4. GANTI MODE (Visual Saja)
    function setMode(mode) {
        cropMode = mode;
        const btnRound = document.getElementById('btnRound');
        const btnSquare = document.getElementById('btnSquare');
        
        if (mode === 'round') {
            btnRound.classList.add('active'); btnSquare.classList.remove('active');
            previewResult.classList.add('rounded-circle-preview');
        } else {
            btnRound.classList.remove('active'); btnSquare.classList.add('active');
            previewResult.classList.remove('rounded-circle-preview');
        }
    }

    // 5. TOMBOL POTONG (Simpan ke Hidden Input)
    document.getElementById('cropAndSave').addEventListener('click', function () {
        if (!cropper) return;

        // Ambil Data Gambar (Base64)
        const canvas = cropper.getCroppedCanvas({
            width: 400, // Resize agar ringan
            height: 400,
            fillColor: '#fff'
        });

        // Simpan Base64 ke Input Hidden
        const base64data = canvas.toDataURL('image/jpeg', 0.9);
        croppedImageInput.value = base64data;

        // Update Preview Utama di Halaman
        mainPreview.src = base64data;
        mainPreview.style.display = 'block';
        placeholderPreview.style.display = 'none';
        
        // Sesuaikan bentuk preview utama
        if (cropMode === 'round') mainPreview.classList.add('rounded-circle');
        else mainPreview.classList.remove('rounded-circle');

        // Tutup Modal
        modalCrop.hide();
    });
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>