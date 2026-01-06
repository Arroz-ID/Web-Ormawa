<?php
/**
 * Sistem Organisasi Kampus - Main Entry Point
 * File: index.php
 */

// 1. BUFFERING OUTPUT (PENTING UNTUK LOGIN)
// Ini mencegah error "Headers already sent" saat redirect
ob_start();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$request_uri = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_PATH', rtrim($request_uri, '/') . '/');
define('ROOT_PATH', __DIR__);

// Ubah ke false saat production
define('DEBUG', true); 

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Autoload Class
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

try {
    // Test Koneksi Database Dulu
    $db = Database::getInstance();
} catch (Exception $e) {
    die("<h3>Koneksi Database Gagal:</h3> " . $e->getMessage());
} catch (Error $e) {
    // Menangkap Error Class Not Found jika file Database.php hilang
    die("<h3>Fatal Error:</h3> File model atau konfigurasi database tidak ditemukan.<br>" . $e->getMessage());
}

$action = isset($_GET['action']) ? trim($_GET['action']) : 'index';
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$organisasi_id = isset($_GET['organisasi_id']) ? intval($_GET['organisasi_id']) : null;

try {
    switch ($action) {
        // --- PUBLIC ---
        case 'index':
            $controller = new OrganisasiController(); $controller->index(); break;
        case 'organisasi':
            $controller = new OrganisasiController(); $controller->daftar(); break;
        case 'detail':
            $controller = new OrganisasiController();
            if ($id) $controller->detail($id); else header('Location: index.php');
            break;

        // --- AUTH ---
        case 'login': $controller = new AuthController(); $controller->login(); break;
        case 'register': $controller = new AuthController(); $controller->register(); break;
        case 'logout': $controller = new AuthController(); $controller->logout(); break;

        // --- SUPER ADMIN ---
        case 'admin_dashboard':
            $controller = new OrganisasiController(); $controller->admin_dashboard(); break;
        case 'admin_tambah_organisasi':
             $controller = new OrganisasiController(); $controller->admin_tambah(); break;
        case 'admin_edit_organisasi':
             $controller = new OrganisasiController(); $controller->admin_edit(); break;
        case 'admin_hapus_organisasi':
             $controller = new OrganisasiController(); if($id) $controller->admin_hapus($id); break;

        // --- ADMIN ORMAWA ---
        case 'ormawa_dashboard':
            if (!isset($_SESSION['admin_id'])) { header('Location: index.php?action=login'); exit; }
            header('Location: index.php?action=ormawa_profil_lengkap');
            break;

        case 'ormawa_profil_lengkap': $controller = new OrganisasiController(); $controller->ormawa_profil_lengkap(); break;
        case 'ormawa_edit_profil': $controller = new OrganisasiController(); $controller->ormawa_edit_profil(); break;
        case 'ormawa_seleksi': $controller = new OrganisasiController(); $controller->ormawa_seleksi(); break;

        // --- ANGGOTA ---
        case 'dashboard':
            if (!isset($_SESSION['anggota_id'])) { header('Location: index.php?action=login'); exit; }
            $controller = new AnggotaController(); $controller->dashboard(); break;
        case 'profile':
            if (!isset($_SESSION['anggota_id'])) { header('Location: index.php?action=login'); exit; }
            $controller = new AnggotaController(); $controller->profile(); break;
        case 'riwayat':
            if (!isset($_SESSION['anggota_id'])) { header('Location: index.php?action=login'); exit; }
            $controller = new AnggotaController(); $controller->riwayat(); break;
        case 'daftar_kepengurusan':
            if (!isset($_SESSION['anggota_id'])) {
                $_SESSION['redirect_after_login'] = "index.php?action=daftar_kepengurusan&organisasi_id=" . $organisasi_id;
                header('Location: index.php?action=login&error=Silakan login terlebih dahulu'); exit;
            }
            $controller = new PendaftaranController(); 
            if ($organisasi_id) $controller->kepengurusan(); else header('Location: index.php?action=organisasi');
            break;
            
        default:
            $controller = new OrganisasiController(); $controller->index(); break;
    }
} catch (Throwable $e) {
    // Menangkap semua jenis Error dan Exception
    echo "<div style='background:#f8d7da; color:#721c24; padding:20px; border:1px solid #f5c6cb; margin:20px;'>";
    echo "<h3>Terjadi Kesalahan Sistem</h3>";
    echo "<p><strong>Pesan:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . " (Baris: " . $e->getLine() . ")</p>";
    if (DEBUG) {
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
}

// Flush output buffer
ob_end_flush();
?>