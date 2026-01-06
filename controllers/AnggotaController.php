<?php
// File: controllers/AnggotaController.php

class AnggotaController {
    private $anggotaModel;
    private $pendaftaranModel;

    public function __construct() {
        $this->anggotaModel = new AnggotaModel();
        // Cek class PendaftaranModel agar aman
        if (class_exists('PendaftaranModel')) {
            $this->pendaftaranModel = new PendaftaranModel();
        }
    }

    public function dashboard() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['anggota_id'])) {
            header('Location: index.php?action=login'); exit;
        }

        $id = $_SESSION['anggota_id'];
        $anggota = $this->anggotaModel->getAnggotaById($id);
        
        $kepengurusan = [];
        $pendaftaran = [];
        
        if (method_exists($this->anggotaModel, 'getKepengurusanByAnggota')) {
            $kepengurusan = $this->anggotaModel->getKepengurusanByAnggota($id);
        }
        
        if ($this->pendaftaranModel) {
            $pendaftaran = $this->pendaftaranModel->getRiwayatKepengurusan($id);
        }

        require 'views/anggota/dashboard.php';
    }

    public function profile() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['anggota_id'])) {
            header('Location: index.php?action=login'); exit;
        }

        $id = $_SESSION['anggota_id'];
        $anggota = $this->anggotaModel->getAnggotaById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $id,
                'nama' => $_POST['nama_lengkap'],
                'nim' => $_POST['nim'],
                'email' => $_POST['email'],
                'no_hp' => $_POST['no_hp'],
                'jurusan' => $_POST['jurusan'],
                'prodi' => $_POST['prodi'],
                'angkatan' => $_POST['angkatan'],
                'foto' => $anggota['foto_profil'] // Default foto lama
            ];

            // --- PROSES SIMPAN FOTO ---
            if (!empty($_POST['cropped_image'])) {
                $targetDir = dirname(__DIR__) . "/assets/images/profil/";
                
                // Cek & Buat Folder jika belum ada
                if (!file_exists($targetDir)) {
                    if (!mkdir($targetDir, 0777, true)) {
                        echo "<script>alert('Gagal membuat folder profil. Hubungi Admin.'); window.location.href='index.php?action=profile';</script>";
                        exit;
                    }
                }

                // Nama file unik per user (menggunakan ID)
                $fileName = 'profil_' . $id . '.jpg';
                
                // Decode Base64
                $image_parts = explode(";base64,", $_POST['cropped_image']);
                if (count($image_parts) >= 2) {
                    $decoded = base64_decode($image_parts[1]);
                    
                    // Simpan file ke server
                    if (file_put_contents($targetDir . $fileName, $decoded)) {
                        $data['foto'] = $fileName; // Berhasil, update nama file di DB
                    } else {
                        echo "<script>alert('Gagal menyimpan file ke server (Permission Denied).'); window.location.href='index.php?action=profile';</script>";
                        exit;
                    }
                }
            }
            // -------------------------

            // Update ke Database
            if ($this->anggotaModel->updateAnggota($data)) {
                // Update session nama jika berubah
                $_SESSION['nama_lengkap'] = $data['nama'];
                
                // Redirect dengan timestamp agar browser refresh cache gambar
                echo "<script>
                        alert('Profil berhasil diperbarui!'); 
                        window.location.href='index.php?action=profile&t=" . time() . "';
                      </script>";
            } else {
                echo "<script>alert('Terjadi kesalahan database.'); window.history.back();</script>";
            }
            exit;
        }
        require 'views/anggota/profile.php';
    }

    public function riwayat() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['anggota_id'])) { header('Location: index.php?action=login'); exit; }

        $id = $_SESSION['anggota_id'];
        $pendaftaran_kepengurusan = [];

        if ($this->pendaftaranModel) {
            $pendaftaran_kepengurusan = $this->pendaftaranModel->getRiwayatKepengurusan($id);
        }
        $pendaftaran_divisi = [];

        require 'views/anggota/riwayat.php';
    }
}
?>