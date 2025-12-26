<?php
require_once 'models/OrganisasiModel.php';
require_once 'models/DivisiModel.php';
require_once 'models/AnggotaModel.php';
require_once 'models/PendaftaranModel.php'; 
require_once 'models/Database.php';

class OrganisasiController {
    private $organisasiModel;
    private $divisiModel;
    private $anggotaModel;
    private $pendaftaranModel;

    public function __construct() {
        $this->organisasiModel = new OrganisasiModel();
        $this->divisiModel = new DivisiModel();
        $this->anggotaModel = new AnggotaModel();
        if(class_exists('PendaftaranModel')){
            $this->pendaftaranModel = new PendaftaranModel();
        }
    }

    // HALAMAN PUBLIK
    public function index() { 
        $organisations = $this->organisasiModel->getAllOrganisasi();
        $total_organisasi = count($organisations);
        $total_anggota = $this->anggotaModel->getJumlahAnggota();
        require 'views/beranda.php'; 
    }
    public function daftar() { 
        $organisations = $this->organisasiModel->getAllOrganisasi();
        require 'views/organisasi/index.php'; 
    }
    public function detail($id) { 
        $organisasi = $this->organisasiModel->getOrganisasiDetail($id);
        $divisi = $this->divisiModel->getDivisiByOrganisasi($id);
        $kepengurusan = $this->organisasiModel->getKepengurusanByOrganisasi($id);
        require 'views/organisasi/detail.php'; 
    }

    // ADMIN SUPER (CRUD SEDERHANA)
    public function admin_tambah() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama' => $_POST['nama_organisasi'], 'jenis' => $_POST['jenis_organisasi'],
                'deskripsi' => $_POST['deskripsi'], 'visi' => $_POST['visi'],
                'misi' => $_POST['misi'], 'tanggal' => $_POST['tanggal_berdiri'],
                'logo' => 'default_logo.png'
            ];
            if ($this->organisasiModel->tambahOrganisasi($data)) {
                Database::catatAktivitas($_SESSION['admin_id'], 'super_admin', 'Tambah Data', 'Menambahkan organisasi: ' . $data['nama']);
                header('Location: index.php?action=admin_dashboard&msg=sukses_tambah'); exit;
            }
        }
    }

    public function admin_edit() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->organisasiModel->getOrganisasiById($_POST['organisasi_id']);
            $data = [
                'id' => $_POST['organisasi_id'], 'nama' => $_POST['nama_organisasi'],
                'jenis' => $_POST['jenis_organisasi'], 'deskripsi' => $_POST['deskripsi'],
                'visi' => $_POST['visi'], 'misi' => $_POST['misi'],
                'tanggal' => $_POST['tanggal_berdiri'], 'logo' => $old['logo']
            ];
            $res = $this->organisasiModel->updateOrganisasi($data);
            if ($res === true) {
                Database::catatAktivitas($_SESSION['admin_id'], 'super_admin', 'Update Data', 'Mengubah data organisasi ID: ' . $data['id']);
                header('Location: index.php?action=admin_dashboard&msg=sukses_update');
            } elseif ($res === 'no_change') {
                header('Location: index.php?action=admin_dashboard&msg=no_change');
            } else { header('Location: index.php?action=admin_dashboard&msg=gagal'); }
            exit;
        }
    }

    public function admin_hapus($id) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }
        $org = $this->organisasiModel->getOrganisasiById($id);
        if ($this->organisasiModel->hapusOrganisasi($id)) {
            Database::catatAktivitas($_SESSION['admin_id'], 'super_admin', 'Hapus Data', 'Menghapus organisasi: ' . $org['nama_organisasi']);
            header('Location: index.php?action=admin_dashboard&msg=sukses_hapus'); exit;
        }
    }

    // =================================================================
    // ADMIN ORMAWA - EDIT PROFIL (DIPERBAIKI)
    // =================================================================
    public function ormawa_edit_profil() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }

        $org_id = $_SESSION['admin_org_id'];
        $organisasi_lama = $this->organisasiModel->getOrganisasiById($org_id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Data Dasar
            $data = [
                'id' => $org_id,
                'nama' => $_POST['nama_organisasi'],
                'jenis' => $_POST['jenis_organisasi'],
                'deskripsi' => $_POST['deskripsi'],
                'visi' => $_POST['visi'],
                'misi' => $_POST['misi'],
                'tanggal' => $_POST['tanggal_berdiri'],
                'logo' => $organisasi_lama['logo'] // Default logo lama
            ];

            // 2. CEK GAMBAR DARI CROPPER (BASE64)
            if (!empty($_POST['cropped_image'])) {
                $newLogo = $this->saveBase64Image($_POST['cropped_image']);
                if ($newLogo) {
                    $data['logo'] = $newLogo; // Timpa dengan logo baru
                }
            }
            // 3. FALLBACK: CEK UPLOAD BIASA (JIKA CROPPER TIDAK DIPAKAI)
            elseif (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
                $targetDir = "assets/images/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = time() . '_' . basename($_FILES['logo']["name"]);
                if (move_uploaded_file($_FILES['logo']["tmp_name"], $targetDir . $fileName)) {
                    $data['logo'] = $fileName;
                }
            }

            // 4. Update Database
            $result = $this->organisasiModel->updateOrganisasi($data);

            if ($result === 'no_change') {
                echo "<script>alert('Tidak ada perubahan data yang disimpan.'); window.location.href='index.php?action=ormawa_edit_profil';</script>";
            } elseif ($result === true) {
                Database::catatAktivitas($_SESSION['admin_id'], 'admin_ormawa', 'Update Profil', 'Mengubah profil organisasi');
                echo "<script>alert('Profil Berhasil Diupdate!'); window.location.href='index.php?action=ormawa_dashboard';</script>";
            } else {
                echo "<script>alert('Gagal update. Silakan coba lagi.'); window.history.back();</script>";
            }
            exit;
        }

        $organisasi = $organisasi_lama;
        require 'views/admin/ormawa_edit.php';
    }

    // FUNGSI SIMPAN BASE64
    private function saveBase64Image($base64String) {
        $targetDir = "assets/images/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

        $image_parts = explode(";base64,", $base64String);
        if (count($image_parts) < 2) return false;

        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1] ?? 'jpeg'; // Default jpeg jika error
        $image_base64 = base64_decode($image_parts[1]);

        $fileName = time() . '_logo.' . $image_type;
        $file = $targetDir . $fileName;

        if (file_put_contents($file, $image_base64)) {
            return $fileName;
        }
        return false;
    }

    // FUNGSI LAINNYA
    public function ormawa_kelola_anggota() {
        $org_id = $_SESSION['admin_org_id'];
        $pengurus = $this->organisasiModel->getKepengurusanByOrganisasi($org_id);
        require 'views/admin/ormawa_anggota.php';
    }
    public function ormawa_seleksi() {
        $org_id = $_SESSION['admin_org_id'];
        $pendaftaranModel = new PendaftaranModel();
        $pendaftar = $pendaftaranModel->getPendingByOrganisasi($org_id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             $id_daftar = $_POST['pendaftaran_id'];
            $aksi = $_POST['aksi']; 
            $catatan = $_POST['catatan'] ?? '';
            $status = 'pending'; $pesan_alert = '';
            if ($aksi === 'terima') { $status = 'approved'; $pesan_alert = 'Anggota diterima!'; } 
            elseif ($aksi === 'tolak') { $status = 'rejected'; $pesan_alert = 'Pendaftaran ditolak.'; } 
            elseif ($aksi === 'interview') { $status = 'interview'; $pesan_alert = 'Status: Wawancara.'; }
            $pendaftaranModel->updateStatus($id_daftar, $status, $catatan);
            if($aksi == 'terima') {
                $data_calon = $pendaftaranModel->getPendaftaranById($id_daftar);
                $pendaftaranModel->insertPengurusBaru($data_calon);
            }
            echo "<script>alert('$pesan_alert'); window.location.href='index.php?action=ormawa_seleksi';</script>";
            exit;
        }
        require 'views/admin/ormawa_seleksi.php';
    }
    public function ormawa_kelola_divisi() {
        $org_id = $_SESSION['admin_org_id'];
        $divisi_list = $this->divisiModel->getDivisiByOrganisasi($org_id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipe = $_POST['tipe_aksi'];
            if ($tipe === 'tambah') {
                $data = ['organisasi_id' => $org_id, 'nama_divisi' => $_POST['nama_divisi'], 'deskripsi_divisi' => $_POST['deskripsi'], 'kuota_anggota' => $_POST['kuota']];
                $this->divisiModel->tambahDivisi($data);
            } elseif ($tipe === 'hapus') {
                $this->divisiModel->hapusDivisi($_POST['divisi_id']);
            }
            header('Location: index.php?action=ormawa_kelola_divisi'); exit;
        }
        require 'views/admin/ormawa_divisi.php';
    }
}
?>