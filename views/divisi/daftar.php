<?php include '../templates/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Daftar Organisasi Baru</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Form pendaftaran organisasi baru di kampus. Pastikan data yang diisi valid dan lengkap.
                    </p>

                    <form method="POST" action="index.php?action=proses_daftar_organisasi" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_organisasi" class="form-label">Nama Organisasi *</label>
                                    <input type="text" class="form-control" id="nama_organisasi" name="nama_organisasi" required>
                                    <div class="invalid-feedback">
                                        Harap masukkan nama organisasi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_organisasi" class="form-label">Jenis Organisasi *</label>
                                    <select class="form-select" id="jenis_organisasi" name="jenis_organisasi" required>
                                        <option value="">Pilih Jenis</option>
                                        <option value="BEM">BEM</option>
                                        <option value="DPM">DPM</option>
                                        <option value="HIMA">HIMA</option>
                                        <option value="UKM">UKM</option>
                                        <option value="KOMUNITAS">Komunitas</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Harap pilih jenis organisasi.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Organisasi *</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" 
                                      placeholder="Jelaskan tentang organisasi Anda..." 
                                      data-max-length="500" required></textarea>
                            <div class="invalid-feedback">
                                Harap masukkan deskripsi organisasi.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="visi" class="form-label">Visi Organisasi *</label>
                            <textarea class="form-control" id="visi" name="visi" rows="2" 
                                      placeholder="Visi organisasi..." 
                                      data-max-length="300" required></textarea>
                            <div class="invalid-feedback">
                                Harap masukkan visi organisasi.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="misi" class="form-label">Misi Organisasi *</label>
                            <textarea class="form-control" id="misi" name="misi" rows="3" 
                                      placeholder="Misi organisasi (point-point)..." 
                                      data-max-length="1000" required></textarea>
                            <div class="invalid-feedback">
                                Harap masukkan misi organisasi.
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_berdiri" class="form-label">Tanggal Berdiri *</label>
                                    <input type="date" class="form-control" id="tanggal_berdiri" name="tanggal_berdiri" required>
                                    <div class="invalid-feedback">
                                        Harap pilih tanggal berdiri.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo Organisasi</label>
                                    <input type="file" class="form-control" id="logo" name="logo" 
                                           accept="image/*" data-preview="logo-preview">
                                    <div class="form-text">
                                        Format: JPG, PNG. Maksimal 2MB.
                                    </div>
                                    <img id="logo-preview" class="mt-2" style="display: none; max-height: 100px;">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Struktur Awal Kepengurusan</label>
                            <div class="border rounded p-3 bg-light">
                                <small class="text-muted d-block mb-2">
                                    Isi data ketua organisasi sebagai struktur awal
                                </small>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label small">Nama Ketua *</label>
                                            <input type="text" class="form-control" name="nama_ketua" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label small">NIM Ketua *</label>
                                            <input type="text" class="form-control" name="nim_ketua" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label small">Email Ketua *</label>
                                            <input type="email" class="form-control" name="email_ketua" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label small">No. Telepon</label>
                                            <input type="tel" class="form-control" name="telepon_ketua">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="agree_terms" name="agree_terms" required>
                            <label class="form-check-label" for="agree_terms">
                                Saya menyatakan bahwa data yang diisi adalah benar dan siap bertanggung jawab
                            </label>
                            <div class="invalid-feedback">
                                Anda harus menyetujui pernyataan ini.
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Ajukan Pendaftaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h6><i class="fas fa-info-circle text-primary me-2"></i>Informasi Pendaftaran</h6>
                    <ul class="small text-muted mb-0">
                        <li>Pendaftaran organisasi akan diverifikasi oleh admin terlebih dahulu</li>
                        <li>Pastikan organisasi yang didaftarkan belum terdaftar sebelumnya</li>
                        <li>Proses verifikasi membutuhkan waktu 1-3 hari kerja</li>
                        <li>Anda akan menerima email konfirmasi setelah pendaftaran disetujui</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>