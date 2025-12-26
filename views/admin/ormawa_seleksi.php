<?php include __DIR__ . '/../templates/header.php'; ?>

<style>
    .admin-section {
        background-color: #f1f5f9;
        min-height: 100vh;
        padding-bottom: 5rem;
    }
    
    /* Header Gradient */
    .admin-header {
        background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
        padding: 2.5rem 0 5rem 0;
        color: white;
        margin-bottom: -3rem;
        border-bottom-left-radius: 30px;
        border-bottom-right-radius: 30px;
        position: relative;
        overflow: hidden;
    }
    
    /* Stats Card */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border: none;
        transition: transform 0.2s;
        text-align: center;
    }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-number { font-size: 2rem; font-weight: 800; color: #1e293b; line-height: 1; margin-bottom: 0.5rem; }
    .stat-label { font-size: 0.85rem; font-weight: 600; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px; }

    /* Applicant Card */
    .applicant-card {
        background: white;
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .applicant-card:hover { box-shadow: 0 15px 30px rgba(0,0,0,0.08); transform: translateY(-3px); }
    
    .card-top {
        padding: 1.5rem;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
    }
    .card-body-custom {
        padding: 1.5rem;
        flex-grow: 1;
    }
    .card-actions {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
    }

    /* Badge & Icons */
    .role-badge {
        background: #eff6ff; color: #2563eb; 
        padding: 5px 12px; border-radius: 50px; 
        font-size: 0.75rem; font-weight: 700;
        display: inline-block; margin-bottom: 10px;
    }
    
    .detail-row {
        display: flex;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }
    .detail-icon { width: 24px; color: #94a3b8; }
    
    /* Modal Styling */
    .modal-detail-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700; display: block; margin-bottom: 4px; }
    .modal-detail-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; color: #334155; font-size: 0.95rem; }
</style>

<div class="admin-section">
    
    <div class="admin-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1"><i class="fas fa-user-shield me-2"></i>Seleksi Anggota</h2>
                    <p class="mb-0 opacity-75">Kelola pendaftaran masuk organisasi Anda</p>
                </div>
                <a href="index.php?action=ormawa_dashboard" class="btn btn-outline-light btn-sm rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: -3rem; position: relative; z-index: 10;">
        
        <div class="row g-3 mb-5">
            <?php 
                $total = count($pendaftar);
                $pending = 0;
                $interview = 0;
                foreach($pendaftar as $p) {
                    if($p['status_pendaftaran'] == 'pending') $pending++;
                    if($p['status_pendaftaran'] == 'interview') $interview++;
                }
            ?>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number text-primary"><?php echo $total; ?></div>
                    <div class="stat-label">Total Pelamar</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number text-warning"><?php echo $pending; ?></div>
                    <div class="stat-label">Perlu Review</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number text-info"><?php echo $interview; ?></div>
                    <div class="stat-label">Tahap Wawancara</div>
                </div>
            </div>
        </div>

        <?php if(empty($pendaftar)): ?>
            <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                <img src="assets/images/empty.svg" alt="" style="width:150px; opacity:0.5;" onerror="this.style.display='none'">
                <h5 class="mt-3 text-muted">Belum ada pendaftaran masuk.</h5>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach($pendaftar as $row): 
                    // Tentukan warna border/badge berdasarkan status
                    $statusColor = 'warning'; 
                    $statusText = 'Menunggu Review';
                    if($row['status_pendaftaran'] == 'interview') { $statusColor = 'info'; $statusText = 'Tahap Wawancara'; }
                    elseif($row['status_pendaftaran'] == 'approved') { $statusColor = 'success'; $statusText = 'Diterima'; }
                    elseif($row['status_pendaftaran'] == 'rejected') { $statusColor = 'danger'; $statusText = 'Ditolak'; }
                    
                    // Siapkan Data JSON untuk JS
                    $jsonData = htmlspecialchars($row['detail_tambahan'] ?? '{}', ENT_QUOTES, 'UTF-8');
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="applicant-card border-top border-4 border-<?php echo $statusColor; ?>">
                        <div class="card-top">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="role-badge"><?php echo htmlspecialchars($row['nama_jabatan']); ?></span>
                                    <h5 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($row['nama_lengkap']); ?></h5>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['nim']); ?></small>
                                </div>
                                <span class="badge bg-<?php echo $statusColor; ?> bg-opacity-10 text-<?php echo $statusColor; ?> rounded-pill px-3">
                                    <?php echo $statusText; ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body-custom">
                            <div class="detail-row">
                                <i class="fas fa-envelope detail-icon"></i>
                                <span class="text-truncate"><?php echo htmlspecialchars($row['email']); ?></span>
                            </div>
                            <div class="detail-row">
                                <i class="fas fa-calendar detail-icon"></i>
                                <span><?php echo date('d M Y', strtotime($row['tanggal_daftar'])); ?></span>
                            </div>
                            <div class="detail-row mt-3">
                                <div class="w-100 bg-light rounded p-2 text-muted fst-italic small">
                                    "<?php echo substr(htmlspecialchars($row['motivasi']), 0, 80) . '...'; ?>"
                                </div>
                            </div>
                        </div>

                        <div class="card-actions d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm fw-bold rounded-pill" 
                                    onclick='openDetailModal(<?php echo json_encode($row); ?>, <?php echo $jsonData; ?>)'>
                                <i class="fas fa-eye me-1"></i> Lihat Detail & Aksi
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="adminDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fas fa-user-check me-2 text-primary"></i>Review Pelamar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-lg-8 p-4" style="max-height: 70vh; overflow-y: auto;">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0" id="modalName">Nama Pelamar</h5>
                                <span class="badge bg-primary mt-1" id="modalJabatan">Jabatan</span>
                                <span class="text-muted small ms-2" id="modalNim">NIM</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 fw-bold text-dark"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <span class="modal-detail-label">Pengalaman Organisasi</span>
                                    <div class="modal-detail-box" id="modalPengalaman">-</div>
                                </div>
                                <div class="col-12">
                                    <span class="modal-detail-label">Motivasi</span>
                                    <div class="modal-detail-box" id="modalMotivasi">-</div>
                                </div>
                            </div>
                        </div>

                        <div id="dynamicContentArea" class="d-none">
                            <h6 class="border-bottom pb-2 fw-bold text-dark mt-4"><i class="fas fa-list-alt me-2"></i>Jawaban Khusus</h6>
                            <div class="row g-3" id="dynamicQuestions">
                                </div>
                        </div>

                        <div id="fileArea" class="mt-4 d-none">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fas fa-file-pdf fa-2x me-3"></i>
                                <div>
                                    <strong>Berkas Pendukung</strong><br>
                                    <small>Pelamar melampirkan file tambahan.</small>
                                </div>
                                <a href="#" id="fileLink" target="_blank" class="btn btn-sm btn-light fw-bold ms-auto">Download</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 bg-light p-4 border-start">
                        <h6 class="fw-bold mb-3">Keputusan Admin</h6>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="pendaftaran_id" id="formPendaftaranId">
                            
                            <div class="mb-3">
                                <label class="small fw-bold text-muted">Status Saat Ini</label>
                                <input type="text" class="form-control form-control-sm bg-white" id="modalStatusDisplay" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="small fw-bold text-muted">Catatan (Opsional)</label>
                                <textarea name="catatan" class="form-control" rows="3" placeholder="Alasan diterima/ditolak/jadwal interview..."></textarea>
                            </div>

                            <hr>

                            <div class="d-grid gap-2">
                                <button type="submit" name="aksi" value="interview" class="btn btn-info text-white fw-bold">
                                    <i class="fas fa-comments me-2"></i>Panggil Wawancara
                                </button>
                                <button type="submit" name="aksi" value="terima" class="btn btn-success fw-bold" onclick="return confirm('Yakin terima anggota ini?');">
                                    <i class="fas fa-check-circle me-2"></i>Terima Anggota
                                </button>
                                <button type="submit" name="aksi" value="tolak" class="btn btn-outline-danger fw-bold" onclick="return confirm('Yakin tolak pelamar ini?');">
                                    <i class="fas fa-times-circle me-2"></i>Tolak
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
function openDetailModal(data, jsonDetails) {
    // 1. Isi Data Statis
    document.getElementById('modalName').textContent = data.nama_lengkap;
    document.getElementById('modalJabatan').textContent = data.nama_jabatan;
    document.getElementById('modalNim').textContent = data.nim;
    document.getElementById('modalPengalaman').innerText = data.pengalaman_organisasi;
    document.getElementById('modalMotivasi').innerText = data.motivasi;
    
    document.getElementById('formPendaftaranId').value = data.pendaftaran_kepengurusan_id;
    document.getElementById('modalStatusDisplay').value = data.status_pendaftaran.toUpperCase();

    // 2. Isi Data Dinamis (JSON)
    var dynamicArea = document.getElementById('dynamicContentArea');
    var questionsContainer = document.getElementById('dynamicQuestions');
    questionsContainer.innerHTML = ''; // Reset

    // Cek jika JSON tidak kosong dan valid object
    if (jsonDetails && Object.keys(jsonDetails).length > 0) {
        dynamicArea.classList.remove('d-none');
        
        for (var key in jsonDetails) {
            // Filter Sampah: Hanya tampilkan jika nilai tidak kosong
            if (jsonDetails[key] && jsonDetails[key].trim() !== "") {
                var col = document.createElement('div');
                col.className = 'col-12';
                col.innerHTML = `
                    <span class="modal-detail-label">${key}</span>
                    <div class="modal-detail-box bg-white">${jsonDetails[key].replace(/\n/g, '<br>')}</div>
                `;
                questionsContainer.appendChild(col);
            }
        }
    } else {
        dynamicArea.classList.add('d-none');
    }

    // 3. Handle File
    var fileArea = document.getElementById('fileArea');
    var fileLink = document.getElementById('fileLink');
    if (data.berkas_tambahan) {
        fileArea.classList.remove('d-none');
        fileLink.href = 'assets/uploads/berkas/' + data.berkas_tambahan;
    } else {
        fileArea.classList.add('d-none');
    }

    // 4. Buka Modal
    var myModal = new bootstrap.Modal(document.getElementById('adminDetailModal'));
    myModal.show();
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>