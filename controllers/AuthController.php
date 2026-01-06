<?php
// File: controllers/AuthController.php

class AuthController {
    private $anggotaModel;
    private $adminModel;

    public function __construct() {
        $this->anggotaModel = new AnggotaModel();
        $this->adminModel = new AdminModel();
    }

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
                
                Database::catatAktivitas($admin['admin_id'], $admin['level'], 'Login ke Sistem');
                
                if ($admin['level'] == 'super_admin') {
                    header('Location: index.php?action=admin_dashboard');
                } else {
                    header('Location: index.php?action=ormawa_dashboard');
                }
                exit;
            }

            // 2. Cek Login Anggota
            $anggota = $this->anggotaModel->login($identifier, $password);
            if ($anggota) {
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['anggota_id']   = $anggota['anggota_id'];
                $_SESSION['nama_lengkap'] = $anggota['nama_lengkap'];
                $_SESSION['role']         = 'anggota';
                
                Database::catatAktivitas($anggota['anggota_id'], 'anggota', 'Login ke Sistem');
                
                header('Location: index.php?action=dashboard');
                exit;
            }

            $error = "Akun tidak ditemukan atau password salah!";
            require 'views/auth/login.php';

        } else {
            require 'views/auth/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nim' => trim($_POST['nim'] ?? ''),
                'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'jurusan' => $_POST['jurusan'] ?? '',
                'fakultas' => ($_POST['jurusan'] == 'Agrobisnis') ? 'Pertanian' : 'Teknik dan Informatika', 
                'angkatan' => $_POST['angkatan'] ?? '',
                'no_telepon' => $_POST['no_telepon'] ?? ''
            ];

            if ($this->anggotaModel->cekNimExist($data['nim'])) {
                $_SESSION['error'] = "NIM sudah terdaftar!";
            } else if ($this->anggotaModel->cekEmailExist($data['email'])) {
                $_SESSION['error'] = "Email sudah terdaftar!";
            } else {
                if ($this->anggotaModel->register($data)) {
                    $success = "Pendaftaran berhasil! Silakan login.";
                    require 'views/auth/login.php';
                    return; 
                } else {
                    $_SESSION['error'] = "Gagal mendaftar. Coba lagi.";
                }
            }
            require 'views/auth/register.php';
        } else {
            require 'views/auth/register.php';
        }
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
?>