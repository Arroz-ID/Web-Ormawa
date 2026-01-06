<?php
class PendaftaranController {
    private $pendaftaranModel;
    private $divisiModel;
    private $organisasiModel;

    public function __construct() {
        $this->pendaftaranModel = new PendaftaranModel();
        if(class_exists('DivisiModel')) $this->divisiModel = new DivisiModel();
        else { require_once 'models/DivisiModel.php'; $this->divisiModel = new DivisiModel(); }
        
        if(class_exists('OrganisasiModel')) $this->organisasiModel = new OrganisasiModel();
        else { require_once 'models/OrganisasiModel.php'; $this->organisasiModel = new OrganisasiModel(); }
    }

    public function kepengurusan() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        $organisasi_id = isset($_GET['organisasi_id']) ? intval($_GET['organisasi_id']) : 0;
        if ($organisasi_id == 0) { header('Location: index.php?action=organisasi'); exit; }

        $organisasi = $this->organisasiModel->getOrganisasiById($organisasi_id);
        $divisi_tersedia = $this->divisiModel->getDivisiByOrganisasi($organisasi_id);
        $jabatan = $this->divisiModel->getJabatanTersedia(); 

        // Variable bantu untuk menampung data lama di view
        $old = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Simpan inputan user ke variabel $old agar bisa dikembalikan ke View
            $old = $_POST;

            // 1. Validasi Wajib
            if (empty($_POST['jabatan_id']) || empty($_POST['motivasi'])) {
                $_SESSION['error'] = "Harap isi Jabatan dan Motivasi!";
                require 'views/pendaftaran/kepengurusan.php'; // Panggil view kembali dengan data $old
                return;
            }

            // 2. Upload Berkas
            $targetDir = "assets/uploads/berkas/";
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
            
            // CV/KTM (Wajib)
            $fileWajib = '';
            // Cek apakah file diupload
            if (isset($_FILES['berkas']) && $_FILES['berkas']['error'] == 0) {
                $ext = strtolower(pathinfo($_FILES['berkas']['name'], PATHINFO_EXTENSION));
                $fileWajib = time() . '_' . $_SESSION['anggota_id'] . '.' . $ext;
                if(!move_uploaded_file($_FILES['berkas']['tmp_name'], $targetDir . $fileWajib)) {
                     $_SESSION['error'] = "Gagal upload berkas wajib.";
                     require 'views/pendaftaran/kepengurusan.php'; return;
                }
            } else {
                // Error jika file tidak ada
                $_SESSION['error'] = "Berkas CV/KTM Wajib diupload! Silakan pilih file kembali.";
                require 'views/pendaftaran/kepengurusan.php'; return;
            }

            // Berkas Pendukung (Opsional)
            $filePendukung = '';
            if (isset($_FILES['berkas_pendukung']) && $_FILES['berkas_pendukung']['error'] == 0) {
                $ext2 = strtolower(pathinfo($_FILES['berkas_pendukung']['name'], PATHINFO_EXTENSION));
                $filePendukung = time() . '_' . $_SESSION['anggota_id'] . '_supp.' . $ext2;
                move_uploaded_file($_FILES['berkas_pendukung']['tmp_name'], $targetDir . $filePendukung);
            }

            // 3. Packing Data Dinamis ke JSON
            $detailKhusus = [];
            
            if ($filePendukung) $detailKhusus['File Tambahan'] = $filePendukung;

            if (!empty($_POST['alasan_ketua'])) $detailKhusus['Alasan Jadi Ketua'] = $_POST['alasan_ketua'];
            if (!empty($_POST['visi'])) $detailKhusus['Visi'] = $_POST['visi'];
            if (!empty($_POST['misi'])) $detailKhusus['Misi'] = $_POST['misi'];
            if (!empty($_POST['studi_kasus'])) $detailKhusus['Studi Kasus'] = $_POST['studi_kasus'];
            if (!empty($_POST['skill_software'])) $detailKhusus['Skill'] = implode(', ', $_POST['skill_software']);
            if (!empty($_POST['kecepatan_ketik'])) $detailKhusus['Kecepatan Ketik'] = $_POST['kecepatan_ketik'];
            if (!empty($_POST['paham_anggaran'])) $detailKhusus['Paham Anggaran'] = $_POST['paham_anggaran'];
            if (isset($_POST['integritas'])) $detailKhusus['Integritas'] = 'Bersedia Tanggung Jawab';
            if (!empty($_POST['gaya_kepemimpinan'])) $detailKhusus['Gaya Pimpin'] = $_POST['gaya_kepemimpinan'];
            if (!empty($_POST['minat_bakat'])) $detailKhusus['Minat Bakat'] = $_POST['minat_bakat'];
            if (!empty($_POST['ketersediaan_waktu'])) $detailKhusus['Komitmen Waktu'] = $_POST['ketersediaan_waktu'] . '/10';

            $jsonDetail = !empty($detailKhusus) ? json_encode($detailKhusus, JSON_UNESCAPED_UNICODE) : '-';

            // 4. Siapkan Data
            $data = [
                'anggota_id' => $_SESSION['anggota_id'],
                'organisasi_id' => $organisasi_id,
                'jabatan_id_diajukan' => $_POST['jabatan_id'],
                'divisi_id_diajukan' => !empty($_POST['divisi_id']) ? $_POST['divisi_id'] : null,
                'motivasi' => $_POST['motivasi'],
                'pengalaman_organisasi' => !empty($_POST['pengalaman_organisasi']) ? $_POST['pengalaman_organisasi'] : '-',
                'berkas_tambahan' => $fileWajib, 
                'detail_tambahan' => $jsonDetail 
            ];

            // 5. Simpan
            if ($this->pendaftaranModel->daftarKepengurusan($data)) {
                echo "<script>alert('Pendaftaran Berhasil!'); window.location.href = 'index.php?action=dashboard';</script>";
                exit;
            } else {
                $_SESSION['error'] = "Gagal menyimpan data ke database.";
            }
        }

        require 'views/pendaftaran/kepengurusan.php';
    }
}
?>  