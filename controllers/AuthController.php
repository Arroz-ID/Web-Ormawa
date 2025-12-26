<?php
require_once 'models/AnggotaModel.php';
require_once 'models/AdminModel.php';
require_once 'models/Database.php';

class AuthController {
    private $anggotaModel;
    private $adminModel;

    public function __construct() {
        $this->anggotaModel = new AnggotaModel();
        $this->adminModel = new AdminModel();
    }

    // =================================================================
    // LOGIN UNIVERSAL (SATU PINTU)
    // =================================================================
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = $_POST['identifier'] ?? ''; 
            $password   = $_POST['password'] ?? '';

            // 1. Cek Login Admin
            $admin = $this->adminModel->login($identifier, $password);
            if ($admin) {
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['admin_id']     = $admin['admin_id'];
                $_SESSION['nama_lengkap'] = $admin['nama_lengkap'];
                $_SESSION['role']         = 'admin';
                $_SESSION['admin_level']  = $admin['level'];
                $_SESSION['admin_org_id'] = $admin['organisasi_id'] ?? null;
                
                // Catat Log
                Database::catatAktivitas($admin['admin_id'], $admin['level'], 'Login ke Sistem');
                
                // --- LOGIKA REDIRECT BARU ---
                if ($admin['level'] == 'super_admin') {
                    header('Location: index.php?action=admin_dashboard');
                } else {
                    // Jika Admin Ormawa (BEM, DPM, dll)
                    header('Location: index.php?action=ormawa_dashboard');
                }
                // ----------------------------
                exit;
            }

            // 2. Cek Login Anggota
            $anggota = $this->anggotaModel->login($identifier, $password);
            if ($anggota) {
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['anggota_id']   = $anggota['anggota_id'];
                $_SESSION['nama_lengkap'] = $anggota['nama_lengkap'];
                $_SESSION['email']        = $anggota['email'];
                $_SESSION['role']         = 'anggota';
                
                Database::catatAktivitas($anggota['anggota_id'], 'anggota', 'Login ke Sistem');
                
                header('Location: index.php?action=dashboard');
                exit;
            }

            // Gagal Login
            $error = "Akun tidak ditemukan atau password salah!";
            require 'views/auth/login.php';

        } else {
            require 'views/auth/login.php';
        }
    }

    // =================================================================
    // REGISTER ANGGOTA
    // =================================================================
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nim' => trim($_POST['nim'] ?? ''),
                'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'fakultas' => ($_POST['jurusan'] == 'Agrobisnis') ? 'Pertanian' : 'Teknik dan Informatika', 
                'jurusan' => $_POST['jurusan'] ?? '',
                'angkatan' => $_POST['angkatan'] ?? '',
                'no_telepon' => $_POST['no_telepon'] ?? ''
            ];

            $errors = $this->validateRegistration($data);
            
            if (empty($errors)) {
                if ($this->anggotaModel->register($data)) {
                    $success = "Pendaftaran berhasil! Silakan login dengan akun baru Anda.";
                    require 'views/auth/login.php';
                    return; 
                } else {
                    $_SESSION['error'] = "Gagal mendaftar. Silakan coba lagi atau hubungi admin.";
                }
            } else {
                $_SESSION['error'] = reset($errors);
            }
            
            require 'views/auth/register.php';
        } else {
            require 'views/auth/register.php';
        }
    }

    // =================================================================
    // LOGOUT
    // =================================================================
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        if (isset($_SESSION['admin_id'])) {
            // Gunakan session admin_level yang sudah disimpan saat login
            $level = $_SESSION['admin_level'] ?? 'admin';
            Database::catatAktivitas($_SESSION['admin_id'], $level, 'Logout dari Sistem');
        } elseif (isset($_SESSION['anggota_id'])) {
            Database::catatAktivitas($_SESSION['anggota_id'], 'anggota', 'Logout dari Sistem');
        }

        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }

    private function validateRegistration($data) {
        $errors = [];
        if (empty($data['nim'])) $errors['nim'] = 'NIM wajib diisi';
        if (empty($data['nama_lengkap'])) $errors['nama_lengkap'] = 'Nama lengkap wajib diisi';
        if (empty($data['email'])) $errors['email'] = 'Email wajib diisi';
        if (empty($data['password'])) $errors['password'] = 'Password wajib diisi';
        
        if (!empty($data['email']) && $this->anggotaModel->cekEmailExist($data['email'])) {
            $errors['email'] = 'Email sudah terdaftar';
        }
        if (!empty($data['nim']) && $this->anggotaModel->cekNimExist($data['nim'])) {
            $errors['nim'] = 'NIM sudah terdaftar';
        }
        return $errors;
    }
}
?>