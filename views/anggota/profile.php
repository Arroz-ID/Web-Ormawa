<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?action=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Profil</li>
                </ol>
            </nav>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i>Edit Profil Anggota</h5>
                </div>
                <div class="card-body p-4">

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?action=profile" method="POST" class="needs-validation" novalidate>
                        
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <?php if (!empty($anggota['foto_profil'])): ?>
                                    <img src="assets/images/<?php echo htmlspecialchars($anggota['foto_profil']); ?>" 
                                         class="rounded-circle border border-3 border-light shadow-sm" 
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center border shadow-sm" 
                                         style="width: 120px; height: 120px;">
                                        <i class="fas fa-user fa-4x"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="text-muted small mt-2">Untuk mengganti foto, silakan hubungi admin.</p>
                        </div>

                        <h6 class="text-primary border-bottom pb-2 mb-3">Informasi Akun (Tidak dapat diubah)</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">NIM</label>
                                <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($anggota['nim']); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Email</label>
                                <input type="email" class="form-control bg-light" value="<?php echo htmlspecialchars($anggota['email']); ?>" readonly>
                            </div>
                        </div>

                        <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">Data Pribadi</h6>

                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label fw-bold small">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                   value="<?php echo htmlspecialchars($anggota['nama_lengkap']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="no_telepon" class="form-label fw-bold small">No. WhatsApp / Telepon</label>
                            <input type="text" class="form-control" id="no_telepon" name="no_telepon" 
                                   value="<?php echo htmlspecialchars($anggota['no_telepon']); ?>" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fakultas" class="form-label fw-bold small">Fakultas</label>
                                <input type="text" class="form-control" id="fakultas" name="fakultas" 
                                       value="<?php echo htmlspecialchars($anggota['fakultas']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="jurusan" class="form-label fw-bold small">Jurusan</label>
                                <select class="form-select" id="jurusan" name="jurusan" required>
                                    <option value="Manajemen Informatika" <?php echo ($anggota['jurusan'] == 'Manajemen Informatika') ? 'selected' : ''; ?>>Manajemen Informatika</option>
                                    <option value="Teknik Mesin" <?php echo ($anggota['jurusan'] == 'Teknik Mesin') ? 'selected' : ''; ?>>Teknik Mesin</option>
                                    <option value="Agrobisnis" <?php echo ($anggota['jurusan'] == 'Agrobisnis') ? 'selected' : ''; ?>>Agrobisnis</option>
                                    </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="angkatan" class="form-label fw-bold small">Angkatan</label>
                            <select class="form-select" id="angkatan" name="angkatan" required>
                                <?php 
                                $currentYear = date('Y');
                                for ($i = $currentYear; $i >= $currentYear - 6; $i--) {
                                    $selected = ($anggota['angkatan'] == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="index.php?action=dashboard" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>