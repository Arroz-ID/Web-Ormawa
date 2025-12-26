<?php
/**
 * Sistem Organisasi Kampus - Main Entry Point
 * File: index.php
 */

// 1. Start Session (Wajib paling atas)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Definisi Konstanta Path
$request_uri = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_PATH', rtrim($request_uri, '/') . '/');
define('ROOT_PATH', __DIR__);
define('DEBUG', true); 

// 3. Error Reporting
if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// 4. Autoloader (Otomatis load file Controller/Model)
spl_autoload_register(function ($class) {
    $paths = [
        ROOT_PATH . '/controllers/' . $class . '.php',
        ROOT_PATH . '/models/' . $class . '.php',
        ROOT_PATH . '/config/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// 5. Inisialisasi Database
try {
    $db = Database::getInstance();
} catch (Exception $e) {
    if (DEBUG) {
        die("Database Connection Error: " . $e->getMessage());
    } else {
        die("Sistem sedang dalam pemeliharaan.");
    }
}

// 6. Routing (Menangani Action)
$action = isset($_GET['action']) ? trim($_GET['action']) : 'index';
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$organisasi_id = isset($_GET['organisasi_id']) ? intval($_GET['organisasi_id']) : null;

try {
    switch ($action) {
        // =============================================================
        // HALAMAN PUBLIK (ORGANISASI & DIVISI)
        // =============================================================
        case 'index':
            $controller = new OrganisasiController();
            $controller->index();
            break;
            
        case 'organisasi':
            $controller = new OrganisasiController();
            if (method_exists($controller, 'daftar')) {
                $controller->daftar();
            } else {
                $controller->index();
            }
            break;
            
        case 'detail':
            $controller = new OrganisasiController();
            if ($id) {
                $controller->detail($id);
            } else {
                header('Location: index.php');
            }
            break;
            
        case 'detail_divisi':
            if (class_exists('DivisiController')) {
                $controller = new DivisiController();
                if ($id) $controller->detail($id);
                else header('Location: index.php');
            } else {
                echo "Modul Divisi belum tersedia.";
            }
            break;

        case 'daftar_divisi':
             if (class_exists('DivisiController')) {
                $controller = new DivisiController();
                if ($id) $controller->daftar($id);
                else header('Location: index.php');
            }
            break;

        // =============================================================
        // AUTHENTICATION (LOGIN & REGISTER)
        // =============================================================
        case 'login':
            $controller = new AuthController();
            $controller->login();
            break;
            
        case 'register':
            $controller = new AuthController();
            $controller->register();
            break;
            
        case 'logout':
            $controller = new AuthController();
            $controller->logout();
            break;

        // =============================================================
        // AREA SUPER ADMIN (ADMIN UTAMA)
        // =============================================================
        case 'admin_dashboard':
            // 1. Cek Session Admin
            if (!isset($_SESSION['admin_id'])) {
                header('Location: index.php?action=login');
                exit;
            }
            
            // Proteksi: Jika Admin Ormawa coba akses ini, lempar ke dashboardnya
            if (isset($_SESSION['admin_level']) && $_SESSION['admin_level'] != 'super_admin') {
                header('Location: index.php?action=ormawa_dashboard');
                exit;
            }

            // 2. Ambil Koneksi Database untuk Hitung Statistik Global
            $conn = Database::getInstance()->getConnection();
            
            $total_organisasi = 0;
            $total_anggota = 0;
            $total_pending = 0;

            try {
                $stmtOrg = $conn->query("SELECT COUNT(*) FROM organisasi");
                if($stmtOrg) $total_organisasi = $stmtOrg->fetchColumn();

                $stmtAnggota = $conn->query("SELECT COUNT(*) FROM anggota");
                if($stmtAnggota) $total_anggota = $stmtAnggota->fetchColumn();

                // Hitung pending dari tabel pendaftaran (contoh)
                $stmtPending = $conn->query("SELECT COUNT(*) FROM pendaftaran_kepengurusan WHERE status_pendaftaran = 'pending'");
                if($stmtPending) $total_pending = $stmtPending->fetchColumn();
                
            } catch (PDOException $e) {}

            require ROOT_PATH . '/views/admin/dashboard.php';
            break;
            
        // Routing CRUD Super Admin
        case 'admin_tambah_organisasi':
             $controller = new OrganisasiController();
             $controller->admin_tambah();
             break;
        case 'admin_edit_organisasi':
             $controller = new OrganisasiController();
             $controller->admin_edit();
             break;
        case 'admin_hapus_organisasi':
             $controller = new OrganisasiController();
             if($id) $controller->admin_hapus($id);
             break;
        case 'kelola_pendaftaran':
             // Bisa diarahkan ke controller khusus jika sudah ada
             header('Location: index.php?action=admin_dashboard');
             break;

        // =============================================================
        // AREA ADMIN ORMAWA (FITUR YANG BARU KITA PERBAIKI)
        // =============================================================
        case 'ormawa_dashboard':
            if (!isset($_SESSION['admin_id'])) { header('Location: index.php?action=login'); exit; }
            
            // Proteksi: Jika Super Admin coba akses, lempar balik (opsional)
            if (isset($_SESSION['admin_level']) && $_SESSION['admin_level'] == 'super_admin') {
                header('Location: index.php?action=admin_dashboard');
                exit;
            }

            $org_id = $_SESSION['admin_org_id'] ?? null;
            if (!$org_id) { die("Error: Akun admin ini tidak terhubung ke organisasi manapun."); }

            $orgModel = new OrganisasiModel();
            $orgDetail = $orgModel->getOrganisasiById($org_id);
            
            // Ambil statistik
            $stats = method_exists($orgModel, 'getStatistikOrganisasi') ? $orgModel->getStatistikOrganisasi($org_id) : ['total_pengurus'=>0, 'total_pendaftar'=>0, 'pendaftar_pending'=>0];

            require ROOT_PATH . '/views/admin/dashboard_ormawa.php';
            break;

        // --- Routing Fitur Pengelolaan Ormawa ---
        
        case 'ormawa_kelola_anggota':
            if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }
            $controller = new OrganisasiController();
            $controller->ormawa_kelola_anggota();
            break;

        case 'ormawa_seleksi':
            if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }
            $controller = new OrganisasiController();
            $controller->ormawa_seleksi();
            break;

        case 'ormawa_kelola_divisi':
            if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }
            $controller = new OrganisasiController();
            $controller->ormawa_kelola_divisi();
            break;

        case 'ormawa_edit_profil':
            if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }
            $controller = new OrganisasiController();
            $controller->ormawa_edit_profil();
            break;

        // =============================================================
        // AREA ANGGOTA (MAHASISWA)
        // =============================================================
        case 'dashboard':
            if (!isset($_SESSION['anggota_id'])) {
                header('Location: index.php?action=login');
                exit;
            }
            $controller = new AnggotaController();
            $controller->dashboard();
            break;
            
        case 'profile':
            if (!isset($_SESSION['anggota_id'])) {
                header('Location: index.php?action=login');
                exit;
            }
            $controller = new AnggotaController();
            $controller->profile();
            break;
            
        case 'riwayat':
            if (!isset($_SESSION['anggota_id'])) {
                header('Location: index.php?action=login');
                exit;
            }
            $controller = new AnggotaController();
            $controller->riwayat();
            break;

        // =============================================================
        // AREA PENDAFTARAN (JOIN ORMAWA)
        // =============================================================
        case 'daftar_kepengurusan':
            if (!isset($_SESSION['anggota_id'])) {
                $_SESSION['redirect_after_login'] = "index.php?action=daftar_kepengurusan&organisasi_id=" . $organisasi_id;
                header('Location: index.php?action=login&error=Silakan login terlebih dahulu');
                exit;
            }
            
            if (class_exists('PendaftaranController')) {
                $controller = new PendaftaranController();
                if ($organisasi_id) $controller->kepengurusan();
                else header('Location: index.php?action=organisasi');
            } else {
                echo "Controller Pendaftaran belum tersedia.";
            }
            break;
            
        default:
            $controller = new OrganisasiController();
            $controller->index();
            break;
    }
} catch (Exception $e) {
    if (DEBUG) {
        echo "<div style='padding:20px; background:#f8d7da; color:#721c24; margin:20px; border:1px solid #f5c6cb; border-radius:5px;'>";
        echo "<h4>Terjadi Kesalahan (Error 500)</h4>";
        echo "<p><strong>Pesan:</strong> " . $e->getMessage() . "</p>";
        echo "<p><strong>File:</strong> " . $e->getFile() . " (Line: " . $e->getLine() . ")</p>";
        echo "</div>";
    } else {
        echo "<h1>Terjadi kesalahan pada server. Silakan coba lagi nanti.</h1>";
    }
}
?>