<?php include __DIR__ . '/../templates/header.php'; ?>

<?php
// LOGIKA TAB AKTIF
$active_tab = isset($_GET['tab']) && $_GET['tab'] == 'kepengurusan' ? 'kepengurusan' : 'divisi';
?>

<style>
    /* 1. Layout Dasar */
    .history-section {
        background-color: #f8f9fa;
        padding-bottom: 3rem;
        min-height: 100vh;
    }

    /* 2. Header Gradient */
    .page-header {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        padding: 2rem 0 5rem 0;
        color: white;
        margin-bottom: -4rem;
        position: relative;
        overflow: hidden;
    }
    
    .page-header::after {
        content: '';
        position: absolute;
        bottom: -20px;
        right: -20px;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .history-container {
        position: relative;
        z-index: 10;
    }

    /* 3. TAB NAVIGASI */
    .custom-tab-container {
        display: inline-flex;
        justify-content: center;
        width: 100%;
        max-width: 500px;
        margin: 0 auto 2rem auto;
        gap: 15px;
    }

    .history-container .nav-pills .nav-link {
        border-radius: 50px;
        font-weight: 600;
        padding: 12px 30px;
        width: 100%;
        text-align: center;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        position: relative;
        
        /* STATE TIDAK AKTIF */
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border: 2px solid rgba(255, 255, 255, 0.3) !important;
        backdrop-filter: blur(5px);
    }

    .history-container .nav-pills .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.2) !important;
        border-color: white !important;
        transform: translateY(-2px);
    }

    /* STATE AKTIF */
    .history-container .nav-pills .nav-link.active {
        background-color: white !important;
        color: #2563eb !important;
        border-color: white !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        padding-bottom: 15px;
    }

    /* GARIS DI BAWAH TOMBOL AKTIF */
    .history-container .nav-pills .nav-link.active::after {
        content: '';
        display: block !important;
        width: 30px;
        height: 3px;
        background-color: #2563eb;
        margin: 4px auto 0 auto;
        border-radius: 10px;
    }
    
    .nav-pills .nav-item { flex: 1; }

    /* 4. CARD STYLE */
    .history-card {
        background: white;
        border: none;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 5px solid transparent;
        position: relative;
    }

    .history-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        z-index: 5;
    }

    /* Status Colors */
    .border-pending { border-left-color: #f59e0b; }
    .border-approved { border-left-color: #10b981; }
    .border-rejected { border-left-color: #ef4444; }
    .border-interview { border-left-color: #3b82f6; }

    .status-pill {
        padding: 5px 15px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-pending { background: #fff7ed; color: #b45309; border: 1px solid #ffedd5; }
    .status-approved { background: #ecfdf5; color: #047857; border: 1px solid #d1fae5; }
    .status-rejected { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }
    .status-interview { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }

    .org-icon {
        width: 50px;
        height: 50px;
        background: #f1f5f9;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 1.2rem;
    }

    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 3rem 1.5rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    
    /* MODAL STYLE */
    .detail-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #64748b; display: block; margin-bottom: 4px; }
    .detail-value { font-size: 0.95rem; color: #334155; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; }
</style>

<div class="history-section">
    
    <div class="page-header text-center">
        <div class="container">
            <h3 class="fw-bold mb-1">Riwayat Pendaftaran</h3>
            <p class="mb-0 opacity-75 small">Pantau status pengajuan Anda</p>
        </div>
    </div>

    <div class="container history-container">
        
        <div class="d-flex justify-content-center">
            <div class="custom-tab-container">
                <ul class="nav nav-pills w-100" id="riwayatTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $active_tab == 'divisi' ? 'active' : ''; ?>" 
                                id="divisi-tab" data-bs-toggle="tab" data-bs-target="#divisi" type="button" role="tab" onclick="updateUrl('divisi')">
                            <i class="fas fa-users me-1"></i> Divisi
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $active_tab == 'kepengurusan' ? 'active' : ''; ?>" 
                                id="kepengurusan-tab" data-bs-toggle="tab" data-bs-target="#kepengurusan" type="button" role="tab" onclick="updateUrl('kepengurusan')">
                            <i class="fas fa-crown me-1"></i> Pengurus Inti
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="text-center mb-4">
            <a href="index.php?action=organisasi" class="btn btn-light text-primary btn-sm rounded-pill px-4 shadow-sm fw-bold">
                <i class="fas fa-plus me-1"></i> Daftar Baru
            </a>
        </div>

        <div class="tab-content" id="riwayatTabContent">
            
            <div class="tab-pane fade <?php echo $active_tab == 'divisi' ? 'show active' : ''; ?>" id="divisi" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        <?php if (empty($pendaftaran_divisi)): ?>
                            <div class="empty-state">
                                <div class="mb-3 text-muted opacity-25"><i class="fas fa-clipboard-list fa-3x"></i></div>
                                <h6 class="text-dark fw-bold">Belum Ada Riwayat</h6>
                                <p class="text-muted small mb-3">Anda belum mendaftar ke divisi manapun.</p>
                                <a href="index.php?action=organisasi" class="btn btn-outline-primary btn-sm rounded-pill px-4">Cari Organisasi</a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($pendaftaran_divisi as $row): 
                                $statusRaw = strtolower($row['status_pendaftaran']);
                                $statusClass = 'status-pending'; $borderClass = 'border-pending'; $label = 'Menunggu'; $icon = 'fa-clock';
                                if ($statusRaw == 'approved' || $statusRaw == 'diterima') { $statusClass = 'status-approved'; $borderClass = 'border-approved'; $label = 'Diterima'; $icon = 'fa-check'; } 
                                elseif ($statusRaw == 'rejected' || $statusRaw == 'ditolak') { $statusClass = 'status-rejected'; $borderClass = 'border-rejected'; $label = 'Ditolak'; $icon = 'fa-times'; }
                            ?>
                            <div class="history-card <?php echo $borderClass; ?>">
                                <div class="row align-items-center">
                                    <div class="col-md-7 mb-3 mb-md-0">
                                        <div class="d-flex align-items-center">
                                            <div class="org-icon flex-shrink-0 me-3"><i class="fas fa-sitemap"></i></div>
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($row['nama_organisasi']); ?></h6>
                                                <div class="text-primary small fw-semibold">Divisi <?php echo htmlspecialchars($row['nama_divisi']); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-md-end">
                                        <span class="status-pill <?php echo $statusClass; ?> mb-2"><i class="fas <?php echo $icon; ?>"></i> <?php echo $label; ?></span>
                                        <div class="text-muted small"><i class="far fa-calendar-alt me-1"></i> <?php echo date('d M Y', strtotime($row['created_at'] ?? date('Y-m-d'))); ?></div>
                                    </div>
                                </div>
                                <?php if (!empty($row['catatan_admin'])): ?>
                                <div class="mt-3 pt-3 border-top bg-light rounded p-3 d-flex align-items-start">
                                    <i class="fas fa-info-circle text-info mt-1 me-2"></i>
                                    <div><strong class="d-block small text-dark">Catatan Admin:</strong><span class="small text-muted fst-italic">"<?php echo htmlspecialchars($row['catatan_admin']); ?>"</span></div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade <?php echo $active_tab == 'kepengurusan' ? 'show active' : ''; ?>" id="kepengurusan" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        <?php if (empty($pendaftaran_kepengurusan)): ?>
                            <div class="empty-state">
                                <div class="mb-3 text-muted opacity-25"><i class="fas fa-user-tie fa-3x"></i></div>
                                <h6 class="text-dark fw-bold">Belum Ada Pendaftaran</h6>
                                <p class="text-muted small">Anda belum mendaftar sebagai pengurus inti.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($pendaftaran_kepengurusan as $row): 
                                $statusRaw = strtolower($row['status_pendaftaran']);
                                $statusClass = 'status-pending'; $borderClass = 'border-pending'; $label = 'Menunggu'; $icon = 'fa-clock';
                                if ($statusRaw == 'approved' || $statusRaw == 'diterima') { $statusClass = 'status-approved'; $borderClass = 'border-approved'; $label = 'Diterima'; $icon = 'fa-check'; } 
                                elseif ($statusRaw == 'rejected' || $statusRaw == 'ditolak') { $statusClass = 'status-rejected'; $borderClass = 'border-rejected'; $label = 'Ditolak'; $icon = 'fa-times'; }
                                elseif ($statusRaw == 'interview' || $statusRaw == 'wawancara') { $statusClass = 'status-interview'; $borderClass = 'border-interview'; $label = 'Wawancara'; $icon = 'fa-comments'; }
                                
                                $detailForm = !empty($row['detail_tambahan']) ? json_decode($row['detail_tambahan'], true) : [];
                                $uniqueId = $row['pendaftaran_kepengurusan_id'];
                                $jabatan = strtolower($row['nama_jabatan']);
                            ?>
                            
                            <div class="history-card <?php echo $borderClass; ?>">
                                <div class="row align-items-center">
                                    <div class="col-md-7 mb-3 mb-md-0">
                                        <div class="d-flex align-items-center">
                                            <div class="org-icon flex-shrink-0 me-3 text-primary bg-primary bg-opacity-10"><i class="fas fa-crown"></i></div>
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($row['nama_organisasi']); ?></h6>
                                                <div class="text-primary small fw-semibold">Posisi: <?php echo htmlspecialchars($row['nama_jabatan']); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-md-end">
                                        <span class="status-pill <?php echo $statusClass; ?> mb-2"><i class="fas <?php echo $icon; ?>"></i> <?php echo $label; ?></span>
                                        <div class="text-muted small"><i class="far fa-calendar-alt me-1"></i> <?php echo date('d M Y', strtotime($row['created_at'] ?? date('Y-m-d'))); ?></div>
                                        
                                        <div class="mt-2 d-flex gap-2 justify-content-md-end justify-content-start flex-wrap">
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1" 
                                                    style="font-size: 0.75rem;" onclick="showDetail(<?php echo $uniqueId; ?>)">
                                                <i class="fas fa-eye me-1"></i> Lihat Formulir
                                            </button>

                                            <?php if ($statusRaw == 'interview'): ?>
                                                <button class="btn btn-primary btn-sm rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                                                    <i class="fas fa-calendar-check me-1"></i> Jadwal
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if (!empty($row['catatan_admin'])): ?>
                                <div class="mt-3 pt-3 border-top bg-light rounded p-3 d-flex align-items-start">
                                    <i class="fas fa-info-circle text-info mt-1 me-2"></i>
                                    <div><strong class="d-block small text-dark">Info dari Admin:</strong><span class="small text-muted fst-italic">"<?php echo htmlspecialchars($row['catatan_admin']); ?>"</span></div>
                                </div>
                                <?php endif; ?>
                                
                                <div id="hidden-data-<?php echo $uniqueId; ?>" class="d-none">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <span class="detail-label">Organisasi</span>
                                            <div class="detail-value"><?php echo htmlspecialchars($row['nama_organisasi']); ?></div>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="detail-label">Posisi Dilamar</span>
                                            <div class="detail-value fw-bold text-primary"><?php echo htmlspecialchars($row['nama_jabatan']); ?></div>
                                        </div>
                                        <div class="col-12">
                                            <span class="detail-label">Motivasi</span>
                                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($row['motivasi'])); ?></div>
                                        </div>
                                        <div class="col-12">
                                            <span class="detail-label">Pengalaman Organisasi</span>
                                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($row['pengalaman_organisasi'])); ?></div>
                                        </div>
                                        
                                        <?php if (!empty($detailForm)): ?>
                                            <div class="col-12 mt-4">
                                                <h6 class="border-bottom pb-2 fw-bold text-dark mb-3">Jawaban Khusus</h6>
                                                <div class="row g-3">
                                                    <?php foreach ($detailForm as $key => $val): 
                                                        // LOGIKA FILTERING (BLACKLIST)
                                                        $skip = false;

                                                        // Jika Jabatan adalah LEADER (Ketua/Wakil/Gubernur), sembunyikan skill teknis
                                                        if (strpos($jabatan, 'ketua') !== false || strpos($jabatan, 'wakil') !== false || strpos($jabatan, 'gubernur') !== false || strpos($jabatan, 'presiden') !== false) {
                                                            if (in_array($key, ['Kecepatan Ketik', 'Software Skill', 'Pemahaman Anggaran', 'Ketersediaan Waktu', 'Minat & Bakat'])) $skip = true;
                                                        }
                                                        
                                                        // Jika Jabatan adalah SEKRETARIS, sembunyikan Visi Misi
                                                        else if (strpos($jabatan, 'sekretaris') !== false) {
                                                            if (in_array($key, ['Visi', 'Misi', 'Studi Kasus', 'Pemahaman Anggaran', 'Gaya Kepemimpinan'])) $skip = true;
                                                        }
                                                        
                                                        // Jika Jabatan adalah BENDAHARA
                                                        else if (strpos($jabatan, 'bendahara') !== false) {
                                                            if (in_array($key, ['Visi', 'Misi', 'Kecepatan Ketik', 'Gaya Kepemimpinan'])) $skip = true;
                                                        }

                                                        // Jika Nilai Kosong atau Masuk Blacklist, jangan tampilkan
                                                        if ($skip || trim($val) === '') continue;
                                                    ?>
                                                        <div class="col-12">
                                                            <span class="detail-label"><?php echo htmlspecialchars($key); ?></span>
                                                            <div class="detail-value bg-white border-primary border-opacity-25"><?php echo nl2br(htmlspecialchars($val)); ?></div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($row['berkas_tambahan'])): ?>
                                            <div class="col-12 mt-4">
                                                <div class="alert alert-info d-flex align-items-center mb-0">
                                                    <i class="fas fa-file-download fa-2x me-3"></i>
                                                    <div><strong class="d-block">Berkas Pendukung</strong><small>Anda mengupload file tambahan.</small></div>
                                                    <a href="assets/uploads/berkas/<?php echo htmlspecialchars($row['berkas_tambahan']); ?>" target="_blank" class="btn btn-sm btn-light fw-bold ms-auto">Download <i class="fas fa-arrow-down ms-1"></i></a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="globalDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-primary">
                    <i class="fas fa-file-signature me-2"></i>Detail Formulir Pendaftaran
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="globalModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary btn-sm px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function updateUrl(tabName) {
    const url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    window.history.pushState({}, '', url);
}

function showDetail(id) {
    var content = document.getElementById('hidden-data-' + id).innerHTML;
    document.getElementById('globalModalBody').innerHTML = content;
    var myModal = new bootstrap.Modal(document.getElementById('globalDetailModal'));
    myModal.show();
}
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>