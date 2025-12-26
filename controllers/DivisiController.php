<?php
require_once 'models/DivisiModel.php';
require_once 'models/OrganisasiModel.php';
// Memanggil Database.php untuk fungsi catatAktivitas
require_once 'models/Database.php';

class DivisiController {
    private $divisiModel;
    private $organisasiModel;

    public function __construct() {
        $this->divisiModel = new DivisiModel();
        $this->organisasiModel = new OrganisasiModel();
    }

    public function detail($id) {
        $divisi = $this->divisiModel->getDivisiById($id);
        
        if (!$divisi) {
            header('Location: index.php');
            exit;
        }

        $anggota_divisi = $this->divisiModel->getAnggotaByDivisi($id);
        $organisasi = $this->organisasiModel->getOrganisasiById($divisi['organisasi_id']);

        require 'views/divisi/detail.php';
    }

    public function daftar($id) {
        // Cek session start
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['anggota_id'])) {
            header('Location: index.php?action=login&redirect=daftar_divisi&id=' . $id);
            exit;
        }

        $divisi = $this->divisiModel->getDivisiById($id);
        
        if (!$divisi) {
            header('Location: index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'anggota_id' => $_SESSION['anggota_id'],
                'divisi_id' => $id,
                'alasan_bergabung' => $_POST['alasan_bergabung'] ?? ''
            ];

            // Cek apakah sudah mendaftar
            require_once 'models/PendaftaranModel.php';
            $pendaftaranModel = new PendaftaranModel();
            
            if ($pendaftaranModel->cekPendaftaranDivisi($_SESSION['anggota_id'], $id)) {
                $error = "Anda sudah mendaftar ke divisi ini sebelumnya!";
            } else {
                if ($pendaftaranModel->daftarDivisi($data)) {
                    
                    // CATAT AKTIVITAS: Daftar Divisi (DITAMBAHKAN)
                    Database::catatAktivitas(
                        $_SESSION['anggota_id'], 
                        'anggota', 
                        'Daftar Divisi', 
                        'Mendaftar ke Divisi: ' . $divisi['nama_divisi'] . ' (ID: ' . $id . ')'
                    );

                    $success = "Pendaftaran berhasil! Tunggu konfirmasi dari admin.";
                } else {
                    $error = "Terjadi kesalahan saat mendaftar. Silakan coba lagi.";
                }
            }
        }

        $organisasi = $this->organisasiModel->getOrganisasiById($divisi['organisasi_id']);
        require 'views/pendaftaran/divisi.php';
    }
}
?>