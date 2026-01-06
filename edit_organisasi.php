<?php
/**
 * Halaman Edit Organisasi (Update + Logging)
 */

ob_start();
if (session_status() == PHP_SESSION_NONE) session_start();

if (!defined('ROOT_PATH')) define('ROOT_PATH', __DIR__);
if (!defined('BASE_PATH')) define('BASE_PATH', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/');

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/models/Database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?action=login");
    exit;
}

$title = "Edit Organisasi";
$id = $_GET['id'] ?? 0;
$error = null;

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    // 1. Ambil Data Lama
    $stmt = $conn->prepare("SELECT * FROM organisasi WHERE organisasi_id = :id");
    $stmt->execute([':id' => $id]);
    $org = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$org) {
        die("Data organisasi tidak ditemukan.");
    }

    // 2. PROSES UPDATE
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama_organisasi = trim($_POST['nama_organisasi']);
        $kategori        = trim($_POST['kategori']);
        $deskripsi       = trim($_POST['deskripsi']);
        $visi            = trim($_POST['visi']);
        $misi            = trim($_POST['misi']);
        $tanggal_berdiri = !empty($_POST['tanggal_berdiri']) ? $_POST['tanggal_berdiri'] : null;

        // Cek Upload Logo Baru
        $logoName = $org['logo']; // Default logo lama
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $filename = $_FILES['logo']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) throw new Exception("Format logo harus JPG/PNG.");
            
            $logoName = time() . '_' . rand(100,999) . '.' . $ext;
            $destination = ROOT_PATH . '/assets/images/profil/' . $logoName;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $destination)) {
                // Hapus logo lama jika bukan default
                if ($org['logo'] && file_exists(ROOT_PATH . '/assets/images/profil/' . $org['logo'])) {
                    // unlink(ROOT_PATH . '/assets/images/profil/' . $org['logo']); // Uncomment jika ingin hapus file lama
                }
            }
        }

        // Update Database (Tanpa Singkatan)
        $sql = "UPDATE organisasi SET 
                nama_organisasi = :nama, 
                jenis_organisasi = :kategori, 
                deskripsi = :deskripsi, 
                visi = :visi, 
                misi = :misi, 
                tanggal_berdiri = :tanggal, 
                logo = :logo 
                WHERE organisasi_id = :id";
        
        $stmtUpdate = $conn->prepare($sql);
        $stmtUpdate->execute([
            ':nama' => $nama_organisasi,
            ':kategori' => $kategori,
            ':deskripsi' => $deskripsi,
            ':visi' => $visi,
            ':misi' => $misi,
            ':tanggal' => $tanggal_berdiri,
            ':logo' => $logoName,
            ':id' => $id
        ]);

        // --- LOGGING ---
        Database::catatAktivitas(
            $_SESSION['admin_id'], 
            'admin', 
            'Update Organisasi', 
            'Mengupdate data organisasi: ' . $nama_organisasi
        );

        echo "<script>alert('Perubahan berhasil disimpan!'); window.location.href='kelola_organisasi.php';</script>";
        exit;
    }

} catch (Exception $e) {
    $error = $e->getMessage();
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
        </div>
        
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Edit Organisasi
                    </h5>
                    <a href="kelola_organisasi.php" class="btn btn-secondary btn-sm rounded-pill px-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Nama Organisasi</label>
                                    <input type="text" name="nama_organisasi" class="form-control" value="<?= htmlspecialchars($org['nama_organisasi']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Kategori</label>
                                    <select name="kategori" class="form-select">
                                        <?php 
                                        $opts = ['UKM', 'HIMA', 'BEM', 'DPM', 'Lainnya'];
                                        $curr = $org['jenis_organisasi'] ?? $org['kategori'] ?? '';
                                        foreach($opts as $opt) {
                                            $sel = ($curr == $opt) ? 'selected' : '';
                                            echo "<option value='$opt' $sel>$opt</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Tanggal Berdiri</label>
                                    <input type="date" name="tanggal_berdiri" class="form-control" value="<?= $org['tanggal_berdiri'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Ganti Logo (Opsional)</label>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengganti logo.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($org['deskripsi'] ?? '') ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Visi</label>
                                    <textarea name="visi" class="form-control" rows="2"><?= htmlspecialchars($org['visi'] ?? '') ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Misi</label>
                                    <textarea name="misi" class="form-control" rows="2"><?= htmlspecialchars($org['misi'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
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