<?php include __DIR__ . '/../templates/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<style>
    /* Styling area crop di dalam modal */
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
        <div class="col-lg-9">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-dark"><i class="fas fa-user-cog me-2"></i>Edit Profil Saya</h3>
                <a href="index.php?action=dashboard" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Dashboard
                </a>
            </div>

            <div class="card border-0 shadow rounded-4 overflow-hidden">
                <div class="card-header bg-primary bg-gradient text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-id-card me-2"></i>Informasi Pribadi</h6>
                </div>
                <div class="card-body p-4">
                    
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="cropped_image" id="cropped_image">

                        <div class="row">
                            <div class="col-md-4 text-center border-end mb-4 mb-md-0">
                                <label class="form-label fw-bold text-muted small mb-3">FOTO PROFIL</label>
                                
                                <div class="mb-3 position-relative mx-auto" style="width: 180px;">
                                    <?php 
                                        // Logika PHP untuk menentukan gambar awal
                                        $fotoFile = isset($anggota['foto_profil']) ? $anggota['foto_profil'] : '';
                                        
                                        // Default: Gunakan Avatar Generator jika belum ada foto
                                        $src = 'https://ui-avatars.com/api/?name=' . urlencode($anggota['nama_lengkap']) . '&background=random&size=200';
                                        
                                        // Cek apakah file ada di folder profil (sistem baru)
                                        if (!empty($fotoFile) && file_exists('assets/images/profil/' . $fotoFile)) {
                                            $src = 'assets/images/profil/' . $fotoFile . '?v=' . time();
                                        } 
                                        // Cek di folder lama (backup data lama)
                                        elseif (!empty($fotoFile) && file_exists('assets/images/' . $fotoFile)) {
                                            $src = 'assets/images/' . $fotoFile . '?v=' . time();
                                        }
                                    ?>
                                    
                                    <img id="profilePreview" 
                                         src="<?php echo htmlspecialchars($src); ?>" 
                                         class="rounded-circle shadow-sm border p-1 bg-white"
                                         style="width: 160px; height: 160px; object-fit: cover;">
                                    
                                    <div class="position-absolute bottom-0 end-0 mb-2 me-2">
                                        <label for="inputImage" class="btn btn-primary btn-sm rounded-circle shadow" 
                                               style="width: 40px; height: 40px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition: transform 0.2s;" 
                                               title="Ganti Foto">
                                            <i class="fas fa-camera"></i>
                                        </label>
                                    </div>
                                </div>

                                <input type="file" class="form-control d-none" id="inputImage" accept="image/*">
                                
                                <div class="small text-muted fst-italic mt-2" id="statusFoto">
                                    Klik ikon kamera untuk mengganti.<br>Format: JPG/PNG
                                </div>
                            </div>

                            <div class="col-md-8 ps-md-4">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="nama_lengkap" value="<?php echo htmlspecialchars($anggota['nama_lengkap']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted">NIM</label>
                                        <input type="text" class="form-control bg-light" name="nim" value="<?php echo htmlspecialchars($anggota['nim']); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted">No. WhatsApp / HP</label>
                                        <input type="number" class="form-control" name="no_hp" value="<?php echo htmlspecialchars($anggota['no_telepon'] ?? $anggota['no_hp'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-muted">Email</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($anggota['email']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted">Jurusan</label>
                                        <input type="text" class="form-control" name="jurusan" value="<?php echo htmlspecialchars($anggota['jurusan']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted">Program Studi</label>
                                        <input type="text" class="form-control" name="prodi" value="<?php echo htmlspecialchars($anggota['fakultas'] ?? $anggota['prodi'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted">Angkatan</label>
                                        <input type="number" class="form-control" name="angkatan" value="<?php echo htmlspecialchars($anggota['angkatan']); ?>" required>
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary fw-bold px-5 rounded-pill shadow-sm">
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
                <h5 class="modal-title fw-bold">Sesuaikan Foto</h5>
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
                                <i class="fas fa-check-circle"></i> Gunakan Foto Ini
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>

<script>
    let cropper;
    const inputImage = document.getElementById('inputImage');
    const imageToCrop = document.getElementById('imageToCrop');
    const profilePreview = document.getElementById('profilePreview');
    const croppedImageInput = document.getElementById('cropped_image');
    const statusFoto = document.getElementById('statusFoto');
    
    // Inisialisasi Modal Bootstrap
    const modalElement = document.getElementById('modalCrop');
    const modalCrop = new bootstrap.Modal(modalElement);

    // 1. Saat File Dipilih -> Buka Modal
    inputImage.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            
            // Validasi: Harus Gambar
            if (!file.type.startsWith('image/')) {
                alert('File yang dipilih bukan gambar!');
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                imageToCrop.src = e.target.result;
                modalCrop.show(); // Tampilkan Modal
            };
            reader.readAsDataURL(file);
        }
        // Jangan reset value di sini agar user bisa cancel jika mau
        this.value = ''; 
    });

    // 2. Saat Modal Muncul -> Inisialisasi Cropper
    modalElement.addEventListener('shown.bs.modal', function () {
        if (cropper) cropper.destroy();
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1, // Wajib 1:1 agar bulat sempurna
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

    // 3. Saat Modal Tutup -> Bersihkan Memori Cropper
    modalElement.addEventListener('hidden.bs.modal', function () { 
        if (cropper) { cropper.destroy(); cropper = null; } 
    });

    // 4. LOGIKA PENTING: Saat Klik "Gunakan Foto Ini"
    document.getElementById('cropAndSave').addEventListener('click', function () {
        if (!cropper) return;
        
        // Ambil hasil crop
        const canvas = cropper.getCroppedCanvas({ 
            width: 500, 
            height: 500, 
            fillColor: '#fff',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        // Konversi ke Base64 (String gambar)
        const base64data = canvas.toDataURL('image/jpeg', 0.9);
        
        // A. Simpan ke Input Hidden (Untuk dikirim ke Server saat submit)
        croppedImageInput.value = base64data;
        
        // B. LIVE PREVIEW: Langsung ganti gambar di halaman dengan hasil crop
        profilePreview.src = base64data;
        
        // C. Beri tanda visual bahwa foto sudah siap
        statusFoto.innerHTML = '<span class="text-success fw-bold"><i class="fas fa-check-circle"></i> Foto siap disimpan! Jangan lupa klik "Simpan Perubahan".</span>';
        
        // Tutup Modal
        modalCrop.hide();
    });
</script>