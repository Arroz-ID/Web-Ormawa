<?php include __DIR__ . '/../../views/templates/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<style>
    /* Styling area crop */
    .img-container {
        max-height: 500px;
        background-color: #333; 
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .img-container img { max-width: 100%; }
    
    /* Box preview kecil bulat */
    .preview-box {
        width: 160px; height: 160px;
        overflow: hidden; margin: 0 auto;
        border: 4px solid #fff;
        border-radius: 50%;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        background: #f8f9fa;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-primary"><i class="fas fa-edit me-2"></i>Edit Profil Organisasi</h3>
                    <p class="text-muted mb-0">Perbarui informasi utama organisasi Anda.</p>
                </div>
                <a href="index.php?action=ormawa_profil_lengkap" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-building me-2"></i>Formulir Data Organisasi</h6>
                </div>
                <div class="card-body p-4">
                    
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="cropped_image" id="cropped_image">

                        <div class="row">
                            <div class="col-md-4 text-center border-end mb-4 mb-md-0">
                                <label class="form-label fw-bold text-muted small mb-3">LOGO ORGANISASI</label>
                                
                                <div class="mb-3 position-relative mx-auto" style="width: 180px;">
                                    <?php 
                                        // Logika Penampil Logo
                                        $logoFile = $organisasi['logo'] ?? '';
                                        // Default Placeholder
                                        $src = 'https://ui-avatars.com/api/?name=' . urlencode($organisasi['nama_organisasi']) . '&background=random&size=200';
                                        
                                        // Cek Folder Profil (Baru)
                                        if (!empty($logoFile) && file_exists('assets/images/profil/' . $logoFile)) {
                                            $src = 'assets/images/profil/' . $logoFile . '?v=' . time();
                                        } 
                                        // Cek Folder Lama
                                        elseif (!empty($logoFile) && file_exists('assets/images/' . $logoFile)) {
                                            $src = 'assets/images/' . $logoFile . '?v=' . time();
                                        }
                                    ?>
                                    
                                    <img id="logoPreview" 
                                         src="<?php echo htmlspecialchars($src); ?>" 
                                         class="rounded-circle shadow-lg border p-1 bg-white"
                                         style="width: 180px; height: 180px; object-fit: cover;">
                                    
                                    <div class="position-absolute bottom-0 end-0 mb-3 me-2">
                                        <label for="inputImage" class="btn btn-warning text-dark btn-sm rounded-circle shadow hover-scale" 
                                               style="width: 45px; height: 45px; display:flex; align-items:center; justify-content:center; cursor:pointer;" 
                                               title="Ganti Logo">
                                            <i class="fas fa-camera fa-lg"></i>
                                        </label>
                                    </div>
                                </div>

                                <input type="file" class="form-control d-none" id="inputImage" accept="image/*">
                                
                                <div class="small text-muted fst-italic mt-3" id="statusLogo">
                                    Format: JPG/PNG (Max 2MB)<br>Disarankan rasio 1:1
                                </div>
                            </div>

                            <div class="col-md-8 ps-md-4">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-muted">Nama Organisasi</label>
                                        <input type="text" class="form-control" name="nama_organisasi" value="<?php echo htmlspecialchars($organisasi['nama_organisasi']); ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted">Kategori</label>
                                        <select class="form-select" name="jenis_organisasi">
                                            <option value="UKM" <?php echo ($organisasi['jenis_organisasi'] == 'UKM') ? 'selected' : ''; ?>>UKM (Unit Kegiatan Mahasiswa)</option>
                                            <option value="HIMA" <?php echo ($organisasi['jenis_organisasi'] == 'HIMA') ? 'selected' : ''; ?>>HIMA (Himpunan Mahasiswa)</option>
                                            <option value="BEM" <?php echo ($organisasi['jenis_organisasi'] == 'BEM') ? 'selected' : ''; ?>>BEM</option>
                                            <option value="MPM" <?php echo ($organisasi['jenis_organisasi'] == 'MPM') ? 'selected' : ''; ?>>MPM</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted">Tanggal Berdiri</label>
                                        <input type="date" class="form-control" name="tanggal_berdiri" value="<?php echo htmlspecialchars($organisasi['tanggal_berdiri']); ?>">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-muted">Deskripsi Singkat</label>
                                        <textarea class="form-control" name="deskripsi" rows="3"><?php echo htmlspecialchars($organisasi['deskripsi']); ?></textarea>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-muted">Visi</label>
                                        <textarea class="form-control" name="visi" rows="2"><?php echo htmlspecialchars($organisasi['visi']); ?></textarea>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-muted">Misi</label>
                                        <textarea class="form-control" name="misi" rows="3"><?php echo htmlspecialchars($organisasi['misi']); ?></textarea>
                                        <div class="form-text">Gunakan enter untuk poin baru.</div>
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary fw-bold px-5 rounded-pill shadow-sm hover-lift">
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

<div class="modal fade" id="modalCrop" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Sesuaikan Logo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="img-container rounded-3 shadow-sm">
                            <img id="imageToCrop" src="" alt="Picture">
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="mb-3 fw-bold small">PREVIEW HASIL</div>
                        <div class="preview-box mb-4" id="previewResult"></div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="if(cropper) cropper.rotate(90)">
                                <i class="fas fa-redo"></i> Putar
                            </button>
                            <button type="button" class="btn btn-success fw-bold py-2" id="cropAndSave">
                                <i class="fas fa-check-circle"></i> Gunakan Logo Ini
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-lift { transition: transform 0.2s; }
    .hover-lift:hover { transform: translateY(-3px); }
    .hover-scale:hover { transform: scale(1.1); transition: transform 0.2s; }
</style>

<?php include __DIR__ . '/../../views/templates/footer.php'; ?>

<script>
    let cropper;
    const inputImage = document.getElementById('inputImage');
    const imageToCrop = document.getElementById('imageToCrop');
    const logoPreview = document.getElementById('logoPreview');
    const croppedImageInput = document.getElementById('cropped_image');
    const statusLogo = document.getElementById('statusLogo');
    
    // Init Modal Bootstrap
    const modalElement = document.getElementById('modalCrop');
    const modalCrop = new bootstrap.Modal(modalElement);

    // 1. Pilih File
    inputImage.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar!');
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                imageToCrop.src = e.target.result;
                modalCrop.show();
            };
            reader.readAsDataURL(file);
        }
        this.value = ''; 
    });

    // 2. Init Cropper
    modalElement.addEventListener('shown.bs.modal', function () {
        if (cropper) cropper.destroy();
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1, 
            viewMode: 1,
            preview: '.preview-box',
            dragMode: 'move',
            autoCropArea: 0.8,
            restore: false,
            guides: false,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
        });
    });

    // 3. Destroy Cropper
    modalElement.addEventListener('hidden.bs.modal', function () { 
        if (cropper) { cropper.destroy(); cropper = null; } 
    });

    // 4. LOGIKA LIVE PREVIEW
    document.getElementById('cropAndSave').addEventListener('click', function () {
        if (!cropper) return;
        
        const canvas = cropper.getCroppedCanvas({ 
            width: 500, height: 500, 
            fillColor: '#fff',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        const base64data = canvas.toDataURL('image/jpeg', 0.9);
        
        // Simpan ke Input Hidden
        croppedImageInput.value = base64data;
        
        // Update Tampilan Langsung (Live Preview)
        logoPreview.src = base64data;
        
        // Feedback Visual
        statusLogo.innerHTML = '<span class="text-success fw-bold"><i class="fas fa-check-circle"></i> Logo siap disimpan! Klik "Simpan Perubahan".</span>';
        
        modalCrop.hide();
    });
</script>