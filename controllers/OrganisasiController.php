<?php
class OrganisasiController {
    private $organisasiModel;
    private $divisiModel;
    private $anggotaModel;
    private $pendaftaranModel;
    private $fiturModel; 

    public function __construct() {
        // 1. Load Model Utama
        $this->organisasiModel = new OrganisasiModel();
        
        // 2. Load Model Pendukung
        if(class_exists('DivisiModel')) $this->divisiModel = new DivisiModel();
        else { require_once 'models/DivisiModel.php'; $this->divisiModel = new DivisiModel(); }

        if(class_exists('AnggotaModel')) $this->anggotaModel = new AnggotaModel();
        else { require_once 'models/AnggotaModel.php'; $this->anggotaModel = new AnggotaModel(); }
        
        if(class_exists('PendaftaranModel')) $this->pendaftaranModel = new PendaftaranModel();
        else { require_once 'models/PendaftaranModel.php'; $this->pendaftaranModel = new PendaftaranModel(); }

        // 3. Load Model Fitur Tambahan
        if(file_exists('models/FiturOrmawaModel.php')) {
            require_once 'models/FiturOrmawaModel.php';
            $this->fiturModel = new FiturOrmawaModel();
        }
    }

    // =========================================================
    // HALAMAN PUBLIK (USER/MAHASISWA)
    // =========================================================

    public function index() { 
        $organisations = $this->organisasiModel->getAllOrganisasi();
        require 'views/beranda.php'; 
    }

    public function daftar() { 
        $organisations = $this->organisasiModel->getAllOrganisasi();
        require 'views/organisasi/index.php'; 
    }

    // DETAIL ORGANISASI (Halaman Profil Publik)
    public function detail($id) { 
        // 1. Ambil Detail Organisasi
        $organisasi = $this->organisasiModel->getOrganisasiDetail($id);
        
        // Fallback jika detail (JOIN) gagal
        if (empty($organisasi)) {
             if (method_exists($this->organisasiModel, 'getOrganisasiById')) {
                $organisasi = $this->organisasiModel->getOrganisasiById($id);
             }
        }

        // 2. Ambil Data Divisi & Pengurus
        $divisi = $this->divisiModel->getDivisiByOrganisasi($id);
        $kepengurusan = $this->organisasiModel->getKepengurusanByOrganisasi($id);
        
        // 3. Ambil Data Fitur Tambahan
        $kegiatan = ($this->fiturModel) ? $this->fiturModel->getKegiatanByOrg($id) : [];
        $list_progja = ($this->fiturModel) ? $this->fiturModel->getProgjaByOrganisasi($id) : [];
        $list_laporan = ($this->fiturModel) ? $this->fiturModel->getLaporanByOrg($id) : [];
        $list_pesan = ($this->fiturModel) ? $this->fiturModel->getPesanByOrg($id) : [];
        
        require 'views/organisasi/detail.php'; 
    }

    // =========================================================
    // HALAMAN ADMIN ORMAWA (PENGURUS)
    // =========================================================

    // 1. DASHBOARD UTAMA
    public function ormawa_dashboard() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }

        $org_id = $_SESSION['admin_org_id'];
        
        // Data Organisasi
        $organisasi = $this->organisasiModel->getOrganisasiDetail($org_id);
        if (empty($organisasi) && method_exists($this->organisasiModel, 'getOrganisasiById')) {
             $organisasi = $this->organisasiModel->getOrganisasiById($org_id);
        }
        if (empty($organisasi)) $organisasi = ['nama_organisasi' => 'Organisasi Tidak Ditemukan'];

        // Statistik
        $list_pending = $this->pendaftaranModel->getPendingByOrganisasi($org_id);
        $jumlah_pending = is_array($list_pending) ? count($list_pending) : 0;
        
        $list_pengurus = $this->organisasiModel->getKepengurusanByOrganisasi($org_id);
        $jumlah_anggota = is_array($list_pengurus) ? count($list_pengurus) : 0;
        
        $jumlah_progja = 0;
        if ($this->fiturModel) {
            $progja_data = $this->fiturModel->getProgjaByOrganisasi($org_id);
            $jumlah_progja = is_array($progja_data) ? count($progja_data) : 0;
        }

        $stats = [
            'nama_organisasi' => $organisasi['nama_organisasi'] ?? 'Organisasi Mahasiswa',
            'pending' => $jumlah_pending,
            'anggota' => $jumlah_anggota,
            'progja' => $jumlah_progja
        ];
        
        require 'views/admin/dashboard_ormawa.php';
    }

    // 2. LIHAT PROFIL LENGKAP (IDENTITAS)
    public function ormawa_profil_lengkap() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }
        
        $org_id = $_SESSION['admin_org_id'];
        
        // Ambil Data Profil
        $organisasi = $this->organisasiModel->getOrganisasiDetail($org_id);
        if (empty($organisasi) && method_exists($this->organisasiModel, 'getOrganisasiById')) {
            $organisasi = $this->organisasiModel->getOrganisasiById($org_id);
        }

        require 'views/admin/ormawa_detail_view.php';
    }

    // 3. EDIT PROFIL & LOGO (PERBAIKAN FITUR WA DISINI)
    public function ormawa_edit_profil() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }
        
        $org_id = $_SESSION['admin_org_id'];
        
        $organisasi = (method_exists($this->organisasiModel, 'getOrganisasiById')) 
                      ? $this->organisasiModel->getOrganisasiById($org_id) 
                      : $this->organisasiModel->getOrganisasiDetail($org_id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // [FIX] Menambahkan 'no_whatsapp' ke array data agar tersimpan
            $data = [
                'id' => $org_id, 
                'nama' => $_POST['nama_organisasi'], 
                'jenis' => $_POST['jenis_organisasi'],
                'deskripsi' => $_POST['deskripsi'], 
                'visi' => $_POST['visi'], 
                'misi' => $_POST['misi'],
                'no_whatsapp' => $_POST['no_whatsapp'] ?? '', // <-- PERBAIKAN: Input WA ditambahkan
                'tanggal' => $_POST['tanggal_berdiri'], 
                'logo' => $organisasi['logo'] ?? '' 
            ];

            // Logic Upload Logo
            if (!empty($_POST['cropped_image'])) {
                $targetDir = dirname(__DIR__) . "/assets/images/profil/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                
                $fileName = 'organisasi_' . $org_id . '.jpg';
                $targetFilePath = $targetDir . $fileName;

                if (file_exists($targetFilePath)) unlink($targetFilePath);

                $image_parts = explode(";base64,", $_POST['cropped_image']);
                if (count($image_parts) >= 2) {
                    $decoded = base64_decode($image_parts[1]);
                    file_put_contents($targetFilePath, $decoded);
                    $data['logo'] = $fileName;
                }
            }

            $this->organisasiModel->updateOrganisasi($data);
            echo "<script>alert('Profil Berhasil Diupdate!'); window.location.href='index.php?action=ormawa_profil_lengkap&t=".time()."';</script>";
            exit;
        }
        require 'views/admin/ormawa_edit.php';
    }

    // 4. KELOLA KEGIATAN
    public function ormawa_kegiatan() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }
        
        $org_id = $_SESSION['admin_org_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_kegiatan'])) {
            $nama = $_POST['nama_kegiatan'];
            $tgl  = $_POST['tanggal'];
            $desk = $_POST['deskripsi'];
            
            $foto_name = '';
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto_name = 'kegiatan_' . time() . '_' . uniqid() . '.' . $ext;
                
                $target_dir = 'assets/images/kegiatan/';
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                
                move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $foto_name);
            }

            if ($this->fiturModel && !empty($foto_name)) {
                $this->fiturModel->tambahKegiatan([
                    'organisasi_id' => $org_id, 
                    'nama_kegiatan' => $nama, 
                    'deskripsi' => $desk, 
                    'tanggal_kegiatan' => $tgl, 
                    'foto_kegiatan' => $foto_name
                ]);
            }
            header('Location: index.php?action=ormawa_kegiatan&status=sukses'); exit;
        }

        if (isset($_GET['hapus_id'])) {
            $this->fiturModel->hapusKegiatan($_GET['hapus_id']);
            header('Location: index.php?action=ormawa_kegiatan'); exit;
        }

        $list_kegiatan = ($this->fiturModel) ? $this->fiturModel->getKegiatanByOrg($org_id) : [];
        require 'views/admin/ormawa_kegiatan.php';
    }

    // 5. KELOLA PROGRAM KERJA
    public function ormawa_progja() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }
        
        $org_id = $_SESSION['admin_org_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_progja'])) {
            $data = [
                'organisasi_id' => $org_id,
                'nama_program' => $_POST['nama_program'],
                'deskripsi' => $_POST['deskripsi'],
                'target_waktu' => $_POST['target_waktu']
            ];
            
            if ($this->fiturModel) {
                $this->fiturModel->tambahProgja($data);
                header('Location: index.php?action=ormawa_progja&status=sukses'); exit;
            }
        }

        if (isset($_GET['hapus_id'])) {
            if ($this->fiturModel) {
                $this->fiturModel->hapusProgja($_GET['hapus_id']);
            }
            header('Location: index.php?action=ormawa_progja'); exit;
        }

        $progja = ($this->fiturModel) ? $this->fiturModel->getProgjaByOrganisasi($org_id) : [];
        require 'views/admin/ormawa_progja.php';
    }

    // 6. KELOLA LAPORAN KINERJA
    public function ormawa_laporan() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }
        
        $org_id = $_SESSION['admin_org_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_laporan'])) {
            $judul = $_POST['judul'];
            $ket   = $_POST['keterangan'];
            
            if (isset($_FILES['file_laporan']) && $_FILES['file_laporan']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['file_laporan']['name'], PATHINFO_EXTENSION);
                
                if(in_array(strtolower($ext), ['pdf', 'doc', 'docx'])) {
                    $file_name = 'laporan_' . time() . '_' . uniqid() . '.' . $ext;
                    $target_dir = 'assets/uploads/laporan/';
                    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                    
                    move_uploaded_file($_FILES['file_laporan']['tmp_name'], $target_dir . $file_name);
                    
                    $this->fiturModel->tambahLaporan([
                        'organisasi_id' => $org_id, 
                        'judul_laporan' => $judul, 
                        'file_laporan' => $file_name, 
                        'keterangan' => $ket
                    ]);
                    header('Location: index.php?action=ormawa_laporan&status=sukses'); exit;
                } else {
                    echo "<script>alert('Format file harus PDF atau Word!'); window.location.href='index.php?action=ormawa_laporan';</script>"; exit;
                }
            }
        }

        if (isset($_GET['hapus_id'])) {
            $this->fiturModel->hapusLaporan($_GET['hapus_id']);
            header('Location: index.php?action=ormawa_laporan'); exit;
        }

        $list_laporan = ($this->fiturModel) ? $this->fiturModel->getLaporanByOrg($org_id) : [];
        require 'views/admin/ormawa_laporan.php';
    }

    // 7. BROADCAST PESAN
    public function ormawa_pesan() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }
        
        $org_id = $_SESSION['admin_org_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim_pesan'])) {
            $judul = $_POST['judul'];
            $isi   = $_POST['isi_pesan'];
            
            if ($this->fiturModel) {
                $this->fiturModel->tambahPesan([
                    'organisasi_id' => $org_id,
                    'judul' => $judul,
                    'isi_pesan' => $isi
                ]);
                header('Location: index.php?action=ormawa_pesan&status=sukses'); exit;
            }
        }

        if (isset($_GET['hapus_id'])) {
            $this->fiturModel->hapusPesan($_GET['hapus_id']);
            header('Location: index.php?action=ormawa_pesan'); exit;
        }

        $riwayat_pesan = ($this->fiturModel) ? $this->fiturModel->getPesanByOrg($org_id) : [];
        require 'views/admin/ormawa_pesan.php';
    }

    // =========================================================
    // FITUR STANDAR LAINNYA
    // =========================================================
    
    // SELEKSI PENDAFTAR (PERBAIKAN STATUS DISINI)
    public function ormawa_seleksi() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }
        
        $org_id = $_SESSION['admin_org_id'];
        $pendaftar = $this->pendaftaranModel->getPendingByOrganisasi($org_id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['pendaftaran_id'];
            $aksi = $_POST['aksi']; 
            $catatan = $_POST['catatan'] ?? '';
            
            // [FIX] Menggunakan ENUM Bahasa Inggris agar konsisten dengan Database Update
            $status = 'pending';
            if ($aksi == 'terima') {
                $status = 'approved';
            } elseif ($aksi == 'tolak') {
                $status = 'rejected';
            } elseif ($aksi == 'wawancara') {
                $status = 'interview';
            }
            
            $update = $this->pendaftaranModel->updateStatus($id, $status, $catatan);
            
            if($update && $aksi == 'terima') {
                $dataP = $this->pendaftaranModel->getPendaftaranById($id);
                if($dataP) $this->pendaftaranModel->insertPengurusBaru($dataP);
            }
            
            echo "<script>alert('Status Berhasil Diubah menjadi " . ucfirst($status) . "'); window.location.href='index.php?action=ormawa_seleksi';</script>"; 
            exit;
        }
        require 'views/admin/ormawa_seleksi.php';
    }

    // Kelola Divisi
    public function ormawa_kelola_divisi() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }
        
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

    // Kelola Anggota
    public function ormawa_kelola_anggota() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_org_id'])) { header('Location: index.php?action=login'); exit; }
        
        $pengurus = $this->organisasiModel->getKepengurusanByOrganisasi($_SESSION['admin_org_id']);
        require 'views/admin/ormawa_anggota.php';
    }

    // --- SUPER ADMIN ---
    public function admin_dashboard() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['admin_id'])) { header('Location: index.php?action=login'); exit; }

        $organisations = $this->organisasiModel->getAllOrganisasi();
        $total_organisasi = count($organisations);
        
        $total_anggota = method_exists($this->anggotaModel, 'getJumlahAnggota') ? $this->anggotaModel->getJumlahAnggota() : 0;
        $total_pending = method_exists($this->pendaftaranModel, 'getTotalPendingGlobal') ? $this->pendaftaranModel->getTotalPendingGlobal() : 0;

        require 'views/admin/dashboard.php';
    }
    public function admin_tambah() { echo "Fitur Tambah Organisasi (Super Admin)"; }
    public function admin_edit() { echo "Fitur Edit Organisasi (Super Admin)"; }
    public function admin_hapus($id) { echo "Fitur Hapus Organisasi (Super Admin)"; }
}
?>