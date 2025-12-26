<?php
require_once 'models/PendaftaranModel.php';
require_once 'models/OrganisasiModel.php';
require_once 'models/DivisiModel.php';
require_once 'models/Database.php';

class PendaftaranController {
    private $pendaftaranModel;
    private $organisasiModel;
    private $divisiModel;

    public function __construct() {
        $this->pendaftaranModel = new PendaftaranModel();
        $this->organisasiModel = new OrganisasiModel();
        $this->divisiModel = new DivisiModel();
    }

    public function kepengurusan() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['anggota_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $organisasi_id = $_GET['organisasi_id'] ?? null;
        if (!$organisasi_id) {
            header('Location: index.php');
            exit;
        }

        $organisasi = $this->organisasiModel->getOrganisasiById($organisasi_id);
        $jabatan = $this->divisiModel->getJabatanTersedia();
        $divisi = $this->divisiModel->getDivisiByOrganisasi($organisasi_id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. PROSES UPLOAD BERKAS
            $berkasName = null;
            if (isset($_FILES['berkas_pendukung']) && $_FILES['berkas_pendukung']['error'] == 0) {
                $targetDir = "assets/uploads/berkas/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                
                $fileName = time() . '_' . basename($_FILES['berkas_pendukung']['name']);
                $targetFile = $targetDir . $fileName;
                
                $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                $allowedTypes = ['pdf', 'docx', 'doc', 'jpg', 'jpeg', 'png'];
                
                if(in_array($fileType, $allowedTypes) && $_FILES['berkas_pendukung']['size'] <= 5000000) {
                    if (move_uploaded_file($_FILES['berkas_pendukung']['tmp_name'], $targetFile)) {
                        $berkasName = $fileName;
                    }
                }
            }

            // 2. TANGKAP DATA DINAMIS & GABUNG JADI JSON (FILTERING)
            $infoTambahan = [];
            
            // Helper function agar kode rapi
            function simpanJikaAda(&$arr, $label, $postKey, $isArray = false) {
                if ($isArray) {
                    if (!empty($_POST[$postKey]) && is_array($_POST[$postKey])) {
                        $arr[$label] = implode(', ', $_POST[$postKey]);
                    }
                } else {
                    if (isset($_POST[$postKey]) && trim($_POST[$postKey]) !== '') {
                        $arr[$label] = trim($_POST[$postKey]);
                    }
                }
            }

            // Paket Top Leader
            simpanJikaAda($infoTambahan, 'Visi', 'visi');
            simpanJikaAda($infoTambahan, 'Misi', 'misi');
            simpanJikaAda($infoTambahan, 'Studi Kasus', 'studi_kasus');
            
            // Paket Administrasi
            simpanJikaAda($infoTambahan, 'Software Skill', 'skill_software', true);
            simpanJikaAda($infoTambahan, 'Kecepatan Ketik', 'kecepatan_ketik');
            
            // Paket Keuangan
            simpanJikaAda($infoTambahan, 'Pemahaman Anggaran', 'paham_anggaran');
            if (isset($_POST['integritas'])) $infoTambahan['Komitmen Integritas'] = "Bersedia Ganti Rugi";
            
            // Paket Koordinator
            simpanJikaAda($infoTambahan, 'Gaya Kepemimpinan', 'gaya_kepemimpinan');
            
            // Paket Staff
            simpanJikaAda($infoTambahan, 'Minat & Bakat', 'minat_bakat');
            simpanJikaAda($infoTambahan, 'Ketersediaan Waktu', 'ketersediaan_waktu');

            // Encode JSON
            $jsonDetail = !empty($infoTambahan) ? json_encode($infoTambahan, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : null;

            $data = [
                'anggota_id' => $_SESSION['anggota_id'],
                'organisasi_id' => $organisasi_id,
                'jabatan_id_diajukan' => $_POST['jabatan_id'] ?? '',
                'divisi_id_diajukan' => $_POST['divisi_id'] ?? null,
                'pengalaman_organisasi' => $_POST['pengalaman_organisasi'] ?? '',
                'motivasi' => $_POST['motivasi'] ?? '',
                'berkas_tambahan' => $berkasName,
                'detail_tambahan' => $jsonDetail
            ];

            if ($this->pendaftaranModel->daftarKepengurusan($data)) {
                Database::catatAktivitas(
                    $_SESSION['anggota_id'], 
                    'anggota', 
                    'Mendaftar Kepengurusan', 
                    'Mendaftar di: ' . $organisasi['nama_organisasi']
                );

                echo "<script>
                        alert('Pendaftaran berhasil dikirim! Silakan cek riwayat untuk status.');
                        window.location.href='index.php?action=riwayat';
                      </script>";
                exit;
            } else {
                $error = "Terjadi kesalahan sistem saat mendaftar. Silakan coba lagi.";
            }
        }

        require 'views/pendaftaran/kepengurusan.php';
    }
}
?>