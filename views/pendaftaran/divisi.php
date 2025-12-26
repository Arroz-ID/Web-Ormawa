<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Pendaftaran Divisi <?php echo htmlspecialchars($divisi['nama_divisi']); ?>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Organization Info -->
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1"><?php echo htmlspecialchars($organisasi['nama_organisasi']); ?></h6>
                                <p class="mb-0 small">Anda akan mendaftar ke divisi <?php echo htmlspecialchars($divisi['nama_divisi']); ?> 
                                pada organisasi <?php echo htmlspecialchars($organisasi['nama_organisasi']); ?></p>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Division Details -->
                    <div class="card border-0 bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title">Detail Divisi</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Nama Divisi:</strong> <?php echo htmlspecialchars($divisi['nama_divisi']); ?></p>
                                    <p class="mb-1"><strong>Kuota:</strong> <?php echo $divisi['kuota_anggota']; ?> anggota</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Status:</strong> 
                                        <span class="badge bg-<?php echo $divisi['status_aktif'] == 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo $divisi['status_aktif'] == 'active' ? 'Aktif' : 'Non-Aktif'; ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <p class="mb-0"><strong>Deskripsi:</strong> <?php echo nl2br(htmlspecialchars($divisi['deskripsi_divisi'])); ?></p>
                        </div>
                    </div>

                    <!-- Registration Form -->
                    <form method="POST" action="index.php?action=daftar_divisi&id=<?php echo $divisi['divisi_id']; ?>" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="alasan_bergabung" class="form-label">
                                Alasan Bergabung *
                            </label>
                            <textarea class="form-control" id="alasan_bergabung" name="alasan_bergabung" 
                                      rows="5" placeholder="Jelaskan mengapa Anda ingin bergabung dengan divisi ini..." 
                                      data-max-length="500" required></textarea>
                            <div class="form-text">
                                Jelaskan motivasi, latar belakang, dan kontribusi yang dapat Anda berikan (maksimal 500 karakter).
                            </div>
                            <div class="invalid-feedback">
                                Harap jelaskan alasan Anda bergabung dengan divisi ini.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterampilan yang Dimiliki</label>
                            <div class="border rounded p-3">
                                <?php
                                $skills = [
                                    'Komunikasi' => 'communication',
                                    'Kerja Tim' => 'teamwork', 
                                    'Kepemimpinan' => 'leadership',
                                    'Manajemen Waktu' => 'time-management',
                                    'Problem Solving' => 'problem-solving',
                                    'Kreativitas' => 'creativity',
                                    'Teknis' => 'technical'
                                ];
                                ?>
                                
                                <div class="row">
                                    <?php foreach ($skills as $skill => $icon): ?>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="keterampilan[]" value="<?php echo $skill; ?>" 
                                                       id="skill-<?php echo $icon; ?>">
                                                <label class="form-check-label" for="skill-<?php echo $icon; ?>">
                                                    <i class="fas fa-<?php echo $icon; ?> me-1 text-primary"></i>
                                                    <?php echo $skill; ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="pengalaman" class="form-label">Pengalaman Organisasi Sebelumnya</label>
                            <textarea class="form-control" id="pengalaman" name="pengalaman" 
                                      rows="3" placeholder="Pengalaman organisasi sebelumnya (jika ada)..." 
                                      data-max-length="300"></textarea>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="commitment" name="commitment" required>
                            <label class="form-check-label" for="commitment">
                                Saya berkomitmen untuk aktif mengikuti kegiatan divisi jika diterima
                            </label>
                            <div class="invalid-feedback">
                                Anda harus menyetujui komitmen ini.
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="index.php?action=detail&id=<?php echo $organisasi['organisasi_id']; ?>" 
                               class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane me-2"></i>Ajukan Pendaftaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6><i class="fas fa-clock text-warning me-2"></i>Proses Seleksi</h6>
                    <ol class="small text-muted mb-0">
                        <li>Pendaftaran akan diverifikasi oleh admin organisasi</li>
                        <li>Anda mungkin akan dihubungi untuk wawancara</li>
                        <li>Hasil seleksi akan diumumkan dalam 1-2 minggu</li>
                        <li>Status pendaftaran dapat dilihat di dashboard anggota</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>