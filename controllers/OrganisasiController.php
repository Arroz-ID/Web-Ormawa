<?php
// File: controllers/OrganisasiController.php

class OrganisasiController {
    private $organisasiModel;
    private $divisiModel;
    private $anggotaModel;
    private $pendaftaranModel;
    private $fiturModel; // Tambahan untuk fitur kegiatan/laporan

    public function __construct() {
        $this->organisasiModel = new OrganisasiModel();
        
        // Load Model dengan Cek Keberadaan Class
        if(class_exists('DivisiModel')) $this->divisiModel = new DivisiModel();
        else { require_once 'models/DivisiModel.php'; $this->divisiModel = new DivisiModel(); }

        if(class_exists('AnggotaModel')) $this->anggotaModel = new AnggotaModel();
        else { require_once 'models/AnggotaModel.php'; $this->anggotaModel = new AnggotaModel(); }
        
        if(class_exists('PendaftaranModel')) $this->pendaftaranModel = new PendaftaranModel();
        else { require_once 'models/PendaftaranModel.php'; $this->pendaftaranModel = new PendaftaranModel(); }

        // Model Tambahan untuk Fitur Ormawa (Kegiatan, Laporan, Pesan)
        if(file_exists('models/FiturOrmawaModel.php')) {
            require_once 'models/FiturOrmawaModel.php';
            $this->fiturModel = new FiturOrmawaModel();
        }
    }

    // =========================================================================
    // BAGIAN SUPER ADMIN (FIXED DASHBOARD)
    // =========================================================================
    
    public function admin_dashboard() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        // 1. Cek Login Super Admin
        if (!isset($_SESSION['admin_id'])) { 
            header('Location: index.php?action=login'); exit; 
        }

        // 2. Hitung Statistik untuk Dashboard
        $organisations = $this->organisasiModel->getAllOrganisasi();
        $total_organisasi = count($organisations);

        $total_anggota = 0;
        if (method_exists($this->anggotaModel, 'getJumlahAnggota')) {
            $total_anggota = $this->anggotaModel->getJumlahAnggota();
        }

        $total_pending = 0;
        if (method_exists($this->pendaftaranModel, 'getTotalPendingGlobal')) {
            $total_pending = $this->pendaftaranModel->getTotalPendingGlobal();
        }

        // 3. Load View Dashboard Super Admin
        require 'views/admin/dashboard.php';
    }

    // =========================================================================
    // BAGIAN PUBLIK
    // =========================================================================
    
    public function index() { 
        $organisations = $this->organisasiModel->getAllOrganisasi();
        $total_organisasi = count($organisations);
        $total_anggota = method_exists($this->anggotaModel, 'getJumlahAnggota') ? $this->anggotaModel->getJumlahAnggota() : 0;
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
        
        // Cek apakah ada method getKegiatanByOrganisasi
        $kegiatan = method_exists($this->organisasiModel, 'getKegiatanByOrganisasi') ? $this->organisasiModel->getKegiatanByOrganisasi($id) : [];
        
        require 'views/organisasi/detail.php'; 
    }

    // =========================================================================
    // BAGIAN ADMIN ORMAWA (KELOLA ORGANISASI)
    // =========================================================================

    // --- METHOD BARU: DASHBOARD ORMAWA ---
    public function ormawa_dashboard() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }

        $org_id = $_SESSION['admin_org_id'];
        
        // Data default
        $stats = [
            'nama_organisasi'   => 'Organisasi',
            'pendaftar_pending' => 0,
            'anggota_aktif'     => 0,
            'total_kegiatan'    => 0,
            'pesan_masuk'       => 0
        ];

        try {
            $db = Database::getInstance()->getConnection();

            // 1. Ambil Nama Organisasi
            $stmt = $db->prepare("SELECT nama_organisasi FROM organisasi WHERE organisasi_id = ?");
            $stmt->execute([$org_id]);
            $org = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($org) $stats['nama_organisasi'] = $org['nama_organisasi'];

            // 2. Hitung Pendaftar Pending
            // Cek apakah tabel ada untuk mencegah error
            $stmt = $db->prepare("SELECT COUNT(*) FROM pendaftaran_kepengurusan WHERE organisasi_id = ? AND status_pendaftaran = 'pending'");
            $stmt->execute([$org_id]);
            $stats['pendaftar_pending'] = $stmt->fetchColumn();

            // 3. Hitung Anggota Aktif
            $stmt = $db->prepare("SELECT COUNT(*) FROM kepengurusan WHERE organisasi_id = ? AND status_aktif = 'active'");
            $stmt->execute([$org_id]);
            $stats['anggota_aktif'] = $stmt->fetchColumn();

            // 4. Hitung Kegiatan (Jika tabel ada)
            $cekKegiatan = $db->query("SHOW TABLES LIKE 'kegiatan'")->rowCount();
            if ($cekKegiatan > 0) {
                $stmt = $db->prepare("SELECT COUNT(*) FROM kegiatan WHERE organisasi_id = ?");
                $stmt->execute([$org_id]);
                $stats['total_kegiatan'] = $stmt->fetchColumn();
            }

        } catch (Exception $e) {
            // Abaikan error jika tabel belum lengkap, agar dashboard tetap tampil
        }

        require 'views/admin/dashboard_ormawa.php';
    }

    public function ormawa_profil_lengkap() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }
        
        $org_id = $_SESSION['admin_org_id'];
        $organisasi = $this->organisasiModel->getOrganisasiDetail($org_id);
        require 'views/admin/ormawa_detail_view.php';
    }

    // --- FITUR EDIT PROFIL DENGAN CROP FOTO ---
    public function ormawa_edit_profil() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }

        $org_id = $_SESSION['admin_org_id'];
        $organisasi = $this->organisasiModel->getOrganisasiById($org_id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $org_id, 
                'nama' => $_POST['nama_organisasi'], 
                'jenis' => $_POST['jenis_organisasi'],
                'deskripsi' => $_POST['deskripsi'], 
                'visi' => $_POST['visi'], 
                'misi' => $_POST['misi'],
                'tanggal' => $_POST['tanggal_berdiri'], 
                'logo' => $organisasi['logo'] // Default pakai logo lama
            ];

            // LOGIKA UPLOAD HASIL CROP (BASE64)
            if (!empty($_POST['cropped_image'])) {
                $targetDir = dirname(__DIR__) . "/assets/images/profil/";
                
                // Buat folder jika belum ada
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

                $fileName = 'organisasi_' . $org_id . '.jpg';
                $targetFilePath = $targetDir . $fileName;

                // HAPUS FILE LAMA DULU (PENTING! Agar tidak kena cache)
                if (file_exists($targetFilePath)) {
                    unlink($targetFilePath);
                }

                // Decode Base64 dan Simpan
                $image_parts = explode(";base64,", $_POST['cropped_image']);
                if (count($image_parts) >= 2) {
                    $decoded = base64_decode($image_parts[1]);
                    if (file_put_contents($targetFilePath, $decoded)) {
                        $data['logo'] = $fileName; // Update nama file di array
                    }
                }
            }

            if ($this->organisasiModel->updateOrganisasi($data)) {
                // Redirect dengan timestamp time() agar browser dipaksa refresh gambar
                echo "<script>
                        alert('Profil Berhasil Diupdate!'); 
                        window.location.href='index.php?action=ormawa_profil_lengkap&t=" . time() . "';
                      </script>";
            } else {
                echo "<script>alert('Gagal update data.'); window.history.back();</script>";
            }
            exit;
        }
        require 'views/admin/ormawa_edit.php';
    }

    public function ormawa_seleksi() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }
        
        $org_id = $_SESSION['admin_org_id'];
        $pendaftar = $this->pendaftaranModel->getPendingByOrganisasi($org_id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['pendaftaran_id'];
            $aksi = $_POST['aksi']; 
            $catatan = $_POST['catatan'] ?? '';
            
            $status = ($aksi == 'terima') ? 'approved' : (($aksi == 'tolak') ? 'rejected' : 'interview');
            
            $this->pendaftaranModel->updateStatus($id, $status, $catatan);
            
            if($aksi == 'terima') {
                $dataP = $this->pendaftaranModel->getPendaftaranById($id);
                if($dataP) $this->pendaftaranModel->insertPengurusBaru($dataP);
            }
            
            echo "<script>alert('Status Berhasil Diubah'); window.location.href='index.php?action=ormawa_seleksi';</script>"; exit;
        }
        require 'views/admin/ormawa_seleksi.php';
    }

    // --- FITUR KELOLA DIVISI ---
    public function ormawa_kelola_divisi() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) header('Location: index.php?action=login');
        
        $org_id = $_SESSION['admin_org_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['tipe_aksi'] == 'tambah') {
                $this->divisiModel->tambahDivisi([
                    'organisasi_id' => $org_id,
                    'nama_divisi' => $_POST['nama_divisi'],
                    'kuota_anggota' => $_POST['kuota'],
                    'deskripsi_divisi' => $_POST['deskripsi']
                ]);
            } elseif ($_POST['tipe_aksi'] == 'hapus') {
                $this->divisiModel->hapusDivisi($_POST['divisi_id']);
            }
            header('Location: index.php?action=ormawa_kelola_divisi'); exit;
        }

        $divisi_list = $this->divisiModel->getDivisiByOrganisasi($org_id);
        require 'views/admin/ormawa_divisi.php';
    }

    // --- FITUR KELOLA ANGGOTA ---
    public function ormawa_kelola_anggota() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) header('Location: index.php?action=login');
        
        $pengurus = $this->organisasiModel->getKepengurusanByOrganisasi($_SESSION['admin_org_id']);
        require 'views/admin/ormawa_anggota.php';
    }

    // --- FITUR KEGIATAN (GALERI) ---
    public function ormawa_kegiatan() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) header('Location: index.php?action=login');
        
        if ($this->fiturModel && $_SERVER['REQUEST_METHOD'] === 'POST') {
            // Logika upload foto kegiatan bisa ditambahkan di sini
            // Simpan file ke assets/uploads/kegiatan/
        }
        
        $list_kegiatan = $this->fiturModel ? $this->fiturModel->getKegiatanByOrg($_SESSION['admin_org_id']) : [];
        if(file_exists('views/admin/ormawa_kegiatan.php')) require 'views/admin/ormawa_kegiatan.php';
        else echo "Fitur Kegiatan Belum Tersedia (File View Missing)";
    }

    // --- FITUR LAPORAN ---
    public function ormawa_laporan() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) header('Location: index.php?action=login');
        
        $list_laporan = $this->fiturModel ? $this->fiturModel->getLaporanByOrg($_SESSION['admin_org_id']) : [];
        if(file_exists('views/admin/ormawa_laporan.php')) require 'views/admin/ormawa_laporan.php';
        else echo "Fitur Laporan Belum Tersedia";
    }

    // --- FITUR PESAN ---
    public function ormawa_pesan() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) header('Location: index.php?action=login');
        
        $riwayat_pesan = $this->fiturModel ? $this->fiturModel->getPesanByOrg($_SESSION['admin_org_id']) : [];
        if(file_exists('views/admin/ormawa_pesan.php')) require 'views/admin/ormawa_pesan.php';
        else echo "Fitur Pesan Belum Tersedia";
    }

    // Placeholder method admin (Super Admin)
    public function admin_tambah() {}
    public function admin_edit() {}
    public function admin_hapus($id) {}
}
?>