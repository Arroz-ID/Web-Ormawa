<?php
/**
 * Sistem Organisasi Kampus - Main Entry Point
 * File: index.php
 */

ob_start(); // Buffer output untuk mencegah error "Headers already sent"

// Mulai sesi jika belum ada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- KONFIGURASI DASAR ---
$request_uri = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_PATH', rtrim($request_uri, '/') . '/');
define('ROOT_PATH', __DIR__); // Folder root (C:\xampp\htdocs\ormawa)
define('DEBUG', true);        // Set true untuk melihat detail error

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// --- AUTOLOAD CLASS ---
// Fungsi ini otomatis memanggil file class saat 'new ClassName()' dijalankan
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

// --- INISIALISASI DATABASE ---
try {
    // Pastikan class Database ada di models/Database.php atau config/Database.php
    $db = Database::getInstance();
} catch (Exception $e) {
    die("<div style='padding:20px; color:red; border:1px solid red;'><h3>Koneksi Database Gagal:</h3> " . $e->getMessage() . "</div>");
} catch (Error $e) {
    die("<div style='padding:20px; color:red; border:1px solid red;'><h3>Fatal Error Database:</h3> File model/config database tidak ditemukan.<br>" . $e->getMessage() . "</div>");
}

// --- ROUTING / NAVIGASI ---
$action = isset($_GET['action']) ? trim($_GET['action']) : 'index';
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$organisasi_id = isset($_GET['organisasi_id']) ? intval($_GET['organisasi_id']) : null;

try {
    switch ($action) {
        // ==========================================
        // 1. PUBLIC ROUTES (Tanpa Login)
        // ==========================================
        case 'index':
            $controller = new OrganisasiController(); $controller->index(); break;
        case 'organisasi':
            $controller = new OrganisasiController(); $controller->daftar(); break;
        case 'detail':
            $controller = new OrganisasiController();
            if ($id) $controller->detail($id); else header('Location: index.php');
            break;

        // ==========================================
        // 2. AUTHENTICATION (Login/Register/Logout)
        // ==========================================
        case 'login': 
            $controller = new AuthController(); $controller->login(); break;
        case 'register': 
            $controller = new AuthController(); $controller->register(); break;
        case 'logout': 
            $controller = new AuthController(); $controller->logout(); break;

        // ==========================================
        // 3. SUPER ADMIN
        // ==========================================
        case 'admin_dashboard':
            $controller = new OrganisasiController(); $controller->admin_dashboard(); break;
        case 'admin_tambah_organisasi':
             $controller = new OrganisasiController(); $controller->admin_tambah(); break;
        case 'admin_edit_organisasi':
             $controller = new OrganisasiController(); $controller->admin_edit(); break;
        case 'admin_hapus_organisasi':
             $controller = new OrganisasiController(); if($id) $controller->admin_hapus($id); break;

        // ==========================================
        // 4. ADMIN ORMAWA (Panel Organisasi)
        // ==========================================
        case 'ormawa_dashboard':
            if (!isset($_SESSION['admin_id'])) { header('Location: index.php?action=login'); exit; }
            $controller = new OrganisasiController(); $controller->ormawa_dashboard(); break;
        
        // Profil & Identitas (Termasuk Fitur WA)
        case 'ormawa_profil_lengkap': 
            $controller = new OrganisasiController(); $controller->ormawa_profil_lengkap(); break;
        case 'ormawa_edit_profil': 
            $controller = new OrganisasiController(); $controller->ormawa_edit_profil(); break;
            
        // Manajemen Anggota & Seleksi
        case 'ormawa_seleksi': 
            $controller = new OrganisasiController(); $controller->ormawa_seleksi(); break;
        case 'ormawa_kelola_anggota': 
            $controller = new OrganisasiController(); $controller->ormawa_kelola_anggota(); break;
        case 'ormawa_kelola_divisi': 
            $controller = new OrganisasiController(); $controller->ormawa_kelola_divisi(); break;
            
        // Kegiatan & Laporan
        case 'ormawa_kegiatan': 
            $controller = new OrganisasiController(); $controller->ormawa_kegiatan(); break;
        case 'ormawa_laporan': 
            $controller = new OrganisasiController(); $controller->ormawa_laporan(); break;
        case 'ormawa_pesan': 
            $controller = new OrganisasiController(); $controller->ormawa_pesan(); break;
        case 'ormawa_progja': 
            $controller = new OrganisasiController(); $controller->ormawa_progja(); break;

        // ==========================================
        // 5. ANGGOTA / MAHASISWA
        // ==========================================
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
                // Simpan redirect agar user kembali ke halaman daftar setelah login
                $_SESSION['redirect_after_login'] = "index.php?action=daftar_kepengurusan&organisasi_id=" . $organisasi_id;
                header('Location: index.php?action=login&error=Silakan login terlebih dahulu'); exit;
            }
            $controller = new PendaftaranController(); 
            if ($organisasi_id) $controller->kepengurusan(); else header('Location: index.php?action=organisasi');
            break;
            
        default:
            // Default ke halaman depan jika action tidak ditemukan
            $controller = new OrganisasiController(); $controller->index(); break;
    }

} catch (Throwable $e) {
    // Menangkap Error Sistem
    echo "<div style='background:#f8d7da; color:#721c24; padding:20px; border:1px solid #f5c6cb; margin:20px; border-radius:10px; font-family:sans-serif;'>";
    echo "<h3 style='margin-top:0;'>Terjadi Kesalahan Sistem</h3>";
    echo "<p><strong>Pesan:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Baris: " . $e->getLine() . ")</p>";
    if (DEBUG) {
        echo "<pre style='background:#fff; padding:10px; border:1px solid #ccc; overflow:auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
    echo "<p><a href='index.php' style='color:#721c24; font-weight:bold;'>Kembali ke Beranda</a></p>";
    echo "</div>";
}

ob_end_flush();
?>