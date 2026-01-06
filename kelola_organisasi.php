<?php
/**
 * Halaman Kelola Organisasi (CRUD Read & Delete + Logging)
 */

ob_start();
if (session_status() == PHP_SESSION_NONE) session_start();

// Definisi Path
if (!defined('ROOT_PATH')) define('ROOT_PATH', __DIR__);
if (!defined('BASE_PATH')) define('BASE_PATH', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/');

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/models/Database.php';

// Cek Akses Admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?action=login");
    exit;
}

$title = "Kelola Organisasi - Admin";
$current_page = "organisasi"; 

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // --- FITUR HAPUS (DELETE) ---
    if (isset($_GET['hapus_id'])) {
        $id = $_GET['hapus_id'];
        
        // 1. Ambil nama dulu untuk log
        $stmtGet = $conn->prepare("SELECT nama_organisasi FROM organisasi WHERE organisasi_id = :id");
        $stmtGet->execute([':id' => $id]);
        $dataOrg = $stmtGet->fetch(PDO::FETCH_ASSOC);
        
        if ($dataOrg) {
            // 2. Hapus Data
            $stmtDel = $conn->prepare("DELETE FROM organisasi WHERE organisasi_id = :id");
            if ($stmtDel->execute([':id' => $id])) {
                
                // 3. CATAT LOG AKTIVITAS (TRIGGER)
                // Parameter: (User ID, Role, Judul Aktivitas, Detail)
                Database::catatAktivitas(
                    $_SESSION['admin_id'], 
                    'admin', 
                    'Menghapus Organisasi', 
                    'Menghapus organisasi: ' . $dataOrg['nama_organisasi']
                );

                header("Location: kelola_organisasi.php?msg=deleted");
                exit;
            }
        }
    }

    // --- TAMPILKAN DATA (READ) ---
    // Tanpa kolom singkatan
    $query = "SELECT * FROM organisasi ORDER BY nama_organisasi ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $organisasi_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error_msg = "Error: " . $e->getMessage();
    $organisasi_list = [];
}

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
                    <a href="kelola_organisasi.php" class="list-group-item list-group-item-action active">
                        <i class="fas fa-sitemap me-2 text-warning"></i> Kelola Organisasi
                    </a>
                    <a href="kelola_pengguna.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2 text-success"></i> Kelola Pengguna
                    </a>
                    <a href="kelola_pendaftaran.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-signature me-2 text-info"></i> Kelola Pendaftaran
                    </a>
                    <a href="index.php?action=logout" class="list-group-item list-group-item-action text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted fw-bold">Total Organisasi</small>
                    <h3 class="fw-bold text-dark mt-2 mb-0"><?= count($organisasi_list) ?></h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-sitemap me-2"></i>Daftar Organisasi
                    </h5>
                    <a href="tambah_organisasi.php" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="fas fa-plus me-1"></i> Tambah Baru
                    </a>
                </div>
                
                <div class="card-body">
                    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                        <div class="alert alert-success py-2 small"><i class="fas fa-check-circle me-1"></i> Data berhasil dihapus dan tercatat di log.</div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Logo</th>
                                    <th width="40%">Nama Organisasi</th>
                                    <th width="25%">Kategori</th>
                                    <th width="20%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($organisasi_list) > 0): ?>
                                    <?php $no = 1; foreach ($organisasi_list as $org): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?php 
                                            $img_path = 'assets/images/profil/' . ($org['logo'] ?? 'default.jpg');
                                            $img_src = (file_exists($img_path) && !empty($org['logo'])) ? $img_path : null;
                                            ?>
                                            <?php if($img_src): ?>
                                                <img src="<?= $img_src ?>" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded text-secondary d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><i class="fas fa-users"></i></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold text-dark"><?= htmlspecialchars($org['nama_organisasi']) ?></td>
                                        <td>
                                            <span class="badge bg-info text-dark bg-opacity-10 border border-info px-3 py-2 rounded-pill">
                                                <?= htmlspecialchars($org['jenis_organisasi'] ?? 'Umum') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="edit_organisasi.php?id=<?= $org['organisasi_id'] ?>" class="btn btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="kelola_organisasi.php?hapus_id=<?= $org['organisasi_id'] ?>" 
                                                   class="btn btn-outline-danger" 
                                                   onclick="return confirm('Hapus organisasi ini?');"
                                                   title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada data.</td>
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
if (file_exists(ROOT_PATH . '/views/templates/footer.php')) require_once ROOT_PATH . '/views/templates/footer.php';
else echo '</body></html>';
ob_end_flush();
?>