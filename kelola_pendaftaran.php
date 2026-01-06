<?php
/**
 * Halaman Kelola Pendaftaran (Versi Final: Status Rapih & Sidebar Khusus Admin)
 */

// 1. BUFFER & SESSION
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. DEFINISI PATH
if (!defined('ROOT_PATH')) define('ROOT_PATH', __DIR__);
if (!defined('BASE_PATH')) {
    $path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    define('BASE_PATH', $path . '/');
}

// 3. INCLUDE DATABASE
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/models/Database.php';

// 4. CEK AKSES (Wajib Admin)
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?action=login");
    exit;
}

// 5. LOGIKA UTAMA
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    // --- FILTER ---
    $filter_org = isset($_GET['org_id']) ? $_GET['org_id'] : '';
    $where_sql = "";
    $params = [];

    if (!empty($filter_org)) {
        $where_sql = " WHERE p.organisasi_id = :org_id ";
        $params[':org_id'] = $filter_org;
    }

    // --- HAPUS DATA ---
    if (isset($_GET['hapus_id'])) {
        $del_id = $_GET['hapus_id'];
        $stmtDel = $conn->prepare("DELETE FROM pendaftaran_kepengurusan WHERE pendaftaran_kepengurusan_id = :id");
        $stmtDel->execute([':id' => $del_id]);
        header("Location: kelola_pendaftaran.php?msg=deleted");
        exit;
    }

    // --- AMBIL DATA PENDAFTARAN ---
    $query = "SELECT p.*, 
                     a.nama_lengkap, a.nim, a.jurusan,
                     o.nama_organisasi
              FROM pendaftaran_kepengurusan p
              LEFT JOIN anggota a ON p.anggota_id = a.anggota_id
              LEFT JOIN organisasi o ON p.organisasi_id = o.organisasi_id
              $where_sql
              ORDER BY p.tanggal_daftar DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $pendaftaran_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- LIST ORGANISASI (Dropdown Filter) ---
    $stmtOrg = $conn->query("SELECT organisasi_id, nama_organisasi FROM organisasi ORDER BY nama_organisasi ASC");
    $list_org = $stmtOrg->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error_msg = "Database Error: " . $e->getMessage();
    $pendaftaran_list = [];
}

// --- CONFIG VIEW ---
$title = "Kelola Pendaftaran";
require_once ROOT_PATH . '/views/templates/header.php';
?>

<div class="container-fluid my-4">
    <div class="row">
        
        <div class="col-md-3">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-user-shield me-2"></i>Menu Admin</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a href="kelola_organisasi.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-sitemap me-2 text-primary"></i> Kelola Organisasi
                    </a>
                    <a href="kelola_pengguna.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2 text-success"></i> Kelola Pengguna
                    </a>
                    <a href="kelola_pendaftaran.php" class="list-group-item list-group-item-action active">
                        <i class="fas fa-file-signature me-2 text-warning"></i> Kelola Pendaftaran
                    </a>
                    <a href="index.php?action=logout" class="list-group-item list-group-item-action text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted fw-bold">Statistik Singkat</small>
                    <h3 class="fw-bold text-dark mt-2 mb-0"><?= count($pendaftaran_list) ?></h3>
                    <p class="small text-muted mb-0">Total Pendaftaran Masuk</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-list-alt me-2"></i>Data Pendaftaran
                    </h5>
                    
                    <form action="" method="GET" class="d-flex gap-2">
                        <select name="org_id" class="form-select form-select-sm" style="min-width: 200px;" onchange="this.form.submit()">
                            <option value="">-- Semua Organisasi --</option>
                            <?php foreach ($list_org as $lo): ?>
                                <option value="<?= $lo['organisasi_id'] ?>" <?= ($filter_org == $lo['organisasi_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lo['nama_organisasi']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(!empty($filter_org)): ?>
                            <a href="kelola_pendaftaran.php" class="btn btn-secondary btn-sm" title="Reset Filter"><i class="fas fa-sync"></i></a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                        <div class="alert alert-success m-3 py-2 small"><i class="fas fa-check-circle me-1"></i> Data berhasil dihapus.</div>
                    <?php endif; ?>

                    <?php if (isset($error_msg)): ?>
                        <div class="alert alert-danger m-3"><i class="fas fa-exclamation-triangle me-1"></i> <?= htmlspecialchars($error_msg) ?></div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="py-3">Waktu Daftar</th>
                                    <th class="py-3">Mahasiswa</th>
                                    <th class="py-3">Organisasi Tujuan</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($pendaftaran_list) > 0): ?>
                                    <?php $no = 1; foreach ($pendaftaran_list as $row): ?>
                                    <tr>
                                        <td class="px-4"><?= $no++ ?></td>
                                        <td>
                                            <?php 
                                            $tgl = $row['tanggal_daftar'] ?? $row['created_at'] ?? null;
                                            if ($tgl) {
                                                echo '<div class="fw-bold text-dark">' . date('d M Y', strtotime($tgl)) . '</div>';
                                                echo '<small class="text-muted">' . date('H:i', strtotime($tgl)) . ' WIB</small>';
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2 text-primary fw-bold" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                                    <?= strtoupper(substr($row['nama_lengkap'] ?? '?', 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark" style="font-size: 0.95rem;">
                                                        <?= htmlspecialchars($row['nama_lengkap'] ?? 'Tanpa Nama') ?>
                                                    </div>
                                                    <div class="small text-muted">
                                                        <?= htmlspecialchars($row['nim'] ?? '-') ?> • <?= htmlspecialchars($row['jurusan'] ?? '-') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-primary border border-info border-opacity-25 px-3 py-2 rounded-pill">
                                                <?= htmlspecialchars($row['nama_organisasi'] ?? 'Dihapus') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php 
                                            // LOGIKA WARNA STATUS (DIPERBAIKI)
                                            $raw_status = strtolower($row['status_pendaftaran'] ?? $row['status'] ?? 'pending');
                                            
                                            // Default (Pending/Menunggu)
                                            $badge_class = 'bg-warning text-dark'; 
                                            $icon = 'fa-clock';
                                            $label = 'Menunggu';

                                            if (in_array($raw_status, ['diterima', 'approved', 'active', 'lulus'])) {
                                                $badge_class = 'bg-success';
                                                $icon = 'fa-check-circle';
                                                $label = 'Diterima';
                                            } 
                                            elseif (in_array($raw_status, ['ditolak', 'rejected', 'non-active', 'gagal'])) {
                                                $badge_class = 'bg-danger';
                                                $icon = 'fa-times-circle';
                                                $label = 'Ditolak';
                                            }
                                            elseif (in_array($raw_status, ['wawancara', 'interview', 'seleksi'])) {
                                                $badge_class = 'bg-info text-dark';
                                                $icon = 'fa-user-tie';
                                                $label = 'Wawancara';
                                            }
                                            ?>
                                            <span class="badge <?= $badge_class ?> rounded-pill px-3 py-2">
                                                <i class="fas <?= $icon ?> me-1"></i> <?= $label ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php $pid = $row['pendaftaran_kepengurusan_id'] ?? $row['id'] ?? 0; ?>
                                            <a href="kelola_pendaftaran.php?hapus_id=<?= $pid ?>" 
                                               class="btn btn-outline-danger btn-sm rounded-circle" 
                                               style="width: 32px; height: 32px; padding: 0;"
                                               onclick="return confirm('Yakin ingin menghapus data ini secara permanen?');"
                                               title="Hapus Data">
                                                <i class="fas fa-trash-alt" style="line-height: 30px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted opacity-50 mb-2">
                                                <i class="fas fa-clipboard-list fa-3x"></i>
                                            </div>
                                            <h6 class="text-muted">Tidak ada data pendaftaran ditemukan.</h6>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// 6. INCLUDE FOOTER
if (file_exists(ROOT_PATH . '/views/templates/footer.php')) {
    require_once ROOT_PATH . '/views/templates/footer.php';
} else {
    echo '</body></html>';
}
ob_end_flush();
?>