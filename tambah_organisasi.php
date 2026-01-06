<?php
/**
 * Halaman Tambah Organisasi (Create + Logging)
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

$title = "Tambah Organisasi Baru";

// --- PROSES SIMPAN (CREATE) ---
$error = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $nama_organisasi = trim($_POST['nama_organisasi']);
        $kategori        = trim($_POST['kategori']);
        $deskripsi       = trim($_POST['deskripsi']);
        $visi            = trim($_POST['visi']);
        $misi            = trim($_POST['misi']);
        $tanggal_berdiri = !empty($_POST['tanggal_berdiri']) ? $_POST['tanggal_berdiri'] : null;

        if (empty($nama_organisasi)) {
            throw new Exception("Nama Organisasi wajib diisi!");
        }

        // Upload Logo
        $logoName = null;
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $filename = $_FILES['logo']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) throw new Exception("Format logo harus JPG/PNG.");
            
            // Generate nama file (tanpa singkatan, pakai timestamp + random)
            $logoName = time() . '_' . rand(100,999) . '.' . $ext;
            $destination = ROOT_PATH . '/assets/images/profil/' . $logoName;

            if (!is_dir(dirname($destination))) mkdir(dirname($destination), 0777, true);
            move_uploaded_file($_FILES['logo']['tmp_name'], $destination);
        }

        // Insert Database (Tanpa Singkatan)
        $sql = "INSERT INTO organisasi (nama_organisasi, jenis_organisasi, deskripsi, visi, misi, tanggal_berdiri, logo, status_aktif) 
                VALUES (:nama, :kategori, :deskripsi, :visi, :misi, :tanggal, :logo, 'active')";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nama' => $nama_organisasi,
            ':kategori' => $kategori,
            ':deskripsi' => $deskripsi,
            ':visi' => $visi,
            ':misi' => $misi,
            ':tanggal' => $tanggal_berdiri,
            ':logo' => $logoName
        ]);

        // --- LOGGING ---
        Database::catatAktivitas(
            $_SESSION['admin_id'], 
            'admin', 
            'Menambah Organisasi', 
            'Menambah organisasi baru: ' . $nama_organisasi
        );

        echo "<script>alert('Organisasi berhasil ditambahkan!'); window.location.href='kelola_organisasi.php';</script>";
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
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
                        <i class="fas fa-plus-circle me-2"></i>Tambah Organisasi
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
                                    <label class="form-label fw-bold small">Nama Organisasi <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_organisasi" class="form-control" placeholder="Contoh: Badan Eksekutif Mahasiswa" required value="<?= $_POST['nama_organisasi'] ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Kategori</label>
                                    <select name="kategori" class="form-select">
                                        <option value="UKM">UKM (Unit Kegiatan Mahasiswa)</option>
                                        <option value="HIMA">HIMA (Himpunan Mahasiswa)</option>
                                        <option value="BEM">BEM (Badan Eksekutif)</option>
                                        <option value="DPM">DPM (Dewan Perwakilan)</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Tanggal Berdiri</label>
                                    <input type="date" name="tanggal_berdiri" class="form-control" value="<?= $_POST['tanggal_berdiri'] ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Upload Logo</label>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control" rows="4"><?= $_POST['deskripsi'] ?? '' ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Visi</label>
                                    <textarea name="visi" class="form-control" rows="2"><?= $_POST['visi'] ?? '' ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Misi</label>
                                    <textarea name="misi" class="form-control" rows="2"><?= $_POST['misi'] ?? '' ?></textarea>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan</button>
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