<?php
/**
 * Halaman Kelola Pengguna (Admin)
 * Fitur: List, Cari, Hapus, Reset Password
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

// 4. CEK AKSES ADMIN
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?action=login");
    exit;
}

// 5. CONFIG VIEW
$title = "Kelola Pengguna - Admin";
$current_page = "pengguna";

// 6. LOGIKA UTAMA
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $msg_success = "";
    $msg_error = "";

    // --- AKSI: HAPUS PENGGUNA ---
    if (isset($_GET['hapus_id'])) {
        $id = $_GET['hapus_id'];
        $stmt = $conn->prepare("DELETE FROM anggota WHERE anggota_id = :id");
        if ($stmt->execute([':id' => $id])) {
            header("Location: kelola_pengguna.php?msg=deleted");
            exit;
        }
    }

    // --- AKSI: RESET PASSWORD (Default: 123456) ---
    if (isset($_GET['reset_id'])) {
        $id = $_GET['reset_id'];
        // Hash password default '123456'
        $default_pass = password_hash('123456', PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("UPDATE anggota SET password = :pass WHERE anggota_id = :id");
        if ($stmt->execute([':pass' => $default_pass, ':id' => $id])) {
            header("Location: kelola_pengguna.php?msg=reset");
            exit;
        }
    }

    // --- FITUR PENCARIAN ---
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $sql_search = "";
    $params = [];

    if (!empty($search)) {
        $sql_search = " WHERE nama_lengkap LIKE :s OR nim LIKE :s OR email LIKE :s ";
        $params[':s'] = "%$search%";
    }

    // --- QUERY DATA ---
    // Mengambil data anggota (mahasiswa)
    $query = "SELECT * FROM anggota $sql_search ORDER BY created_at DESC, nama_lengkap ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $msg_error = "Database Error: " . $e->getMessage();
    $users = [];
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
                    <a href="kelola_organisasi.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-sitemap me-2 text-primary"></i> Kelola Organisasi
                    </a>
                    <a href="kelola_pengguna.php" class="list-group-item list-group-item-action active">
                        <i class="fas fa-users me-2 text-warning"></i> Kelola Pengguna
                    </a>
                    <a href="kelola_pendaftaran.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-signature me-2 text-success"></i> Kelola Pendaftaran
                    </a>
                    <a href="index.php?action=logout" class="list-group-item list-group-item-action text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted fw-bold">Total Pengguna</small>
                    <h3 class="fw-bold text-dark mt-2 mb-0"><?= count($users) ?></h3>
                    <p class="small text-muted mb-0">Mahasiswa Terdaftar</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-users-cog me-2"></i>Manajemen Pengguna
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <form action="" method="GET" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control form-control-sm" 
                                       placeholder="Cari nama, NIM, atau email..." 
                                       value="<?= htmlspecialchars($search) ?>">
                                <button type="submit" class="btn btn-primary btn-sm px-3">
                                    <i class="fas fa-search"></i>
                                </button>
                                <?php if($search): ?>
                                    <a href="kelola_pengguna.php" class="btn btn-secondary btn-sm px-3"><i class="fas fa-sync"></i></a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <?php if ($_GET['msg'] == 'deleted'): ?>
                    <div class="alert alert-success py-2 small"><i class="fas fa-check-circle me-1"></i> Pengguna berhasil dihapus.</div>
                <?php elseif ($_GET['msg'] == 'reset'): ?>
                    <div class="alert alert-info py-2 small"><i class="fas fa-key me-1"></i> Password pengguna berhasil direset menjadi: <b>123456</b></div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($msg_error): ?>
                <div class="alert alert-danger py-2 small"><?= htmlspecialchars($msg_error) ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="px-4 py-3" width="5%">No</th>
                                    <th class="py-3" width="35%">Mahasiswa</th>
                                    <th class="py-3" width="25%">Kontak</th>
                                    <th class="py-3" width="15%">Waktu Daftar</th>
                                    <th class="py-3 text-center" width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($users) > 0): ?>
                                    <?php $no = 1; foreach ($users as $user): ?>
                                    <tr>
                                        <td class="px-4"><?= $no++ ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php 
                                                    $foto = $user['foto_profil'] ?? null;
                                                    $imgSrc = ($foto && file_exists('assets/images/profil/'.$foto)) 
                                                              ? 'assets/images/profil/'.$foto 
                                                              : null;
                                                ?>
                                                <?php if ($imgSrc): ?>
                                                    <img src="<?= $imgSrc ?>" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <span class="fw-bold"><?= strtoupper(substr($user['nama_lengkap'] ?? 'U', 0, 1)) ?></span>
                                                    </div>
                                                <?php endif; ?>

                                                <div>
                                                    <div class="fw-bold text-dark"><?= htmlspecialchars($user['nama_lengkap'] ?? 'Tanpa Nama') ?></div>
                                                    <div class="small text-muted">
                                                        <?= htmlspecialchars($user['nim'] ?? '-') ?> 
                                                        <?php if(isset($user['jurusan'])): ?>
                                                            <span class="ms-1 badge bg-light text-dark border"><?= htmlspecialchars($user['jurusan']) ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small text-dark"><i class="fas fa-envelope me-2 text-muted"></i><?= htmlspecialchars($user['email'] ?? '-') ?></div>
                                            <div class="small text-muted mt-1"><i class="fas fa-phone me-2 text-muted"></i><?= htmlspecialchars($user['no_hp'] ?? '-') ?></div>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= isset($user['created_at']) ? date('d M Y', strtotime($user['created_at'])) : '-' ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Opsi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                                    <li>
                                                        <a class="dropdown-item text-warning" href="kelola_pengguna.php?reset_id=<?= $user['anggota_id'] ?>" 
                                                           onclick="return confirm('Reset password mahasiswa ini menjadi 123456?');">
                                                            <i class="fas fa-key me-2"></i> Reset Password
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="kelola_pengguna.php?hapus_id=<?= $user['anggota_id'] ?>" 
                                                           onclick="return confirm('Hapus pengguna ini secara permanen? Data pendaftaran mereka juga mungkin akan hilang.');">
                                                            <i class="fas fa-trash-alt me-2"></i> Hapus Akun
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted opacity-50 mb-2">
                                                <i class="fas fa-user-slash fa-3x"></i>
                                            </div>
                                            <h6 class="text-muted">Tidak ada data pengguna ditemukan.</h6>
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
// 7. INCLUDE FOOTER
if (file_exists(ROOT_PATH . '/views/templates/footer.php')) {
    require_once ROOT_PATH . '/views/templates/footer.php';
} else {
    echo '</body></html>';
}
ob_end_flush();
?>