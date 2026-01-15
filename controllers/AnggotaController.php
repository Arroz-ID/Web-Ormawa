<?php
class AnggotaController {
    private $anggotaModel;
    private $pendaftaranModel;
    private $fiturModel; // Tambahan Property

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        // 1. Load Model Anggota (Wajib)
        $this->anggotaModel = new AnggotaModel();
        
        // 2. Load Model Pendaftaran (Cek exist)
        if(class_exists('PendaftaranModel')) {
            $this->pendaftaranModel = new PendaftaranModel();
        } else { 
            require_once 'models/PendaftaranModel.php'; 
            $this->pendaftaranModel = new PendaftaranModel(); 
        }

        // 3. Load FiturOrmawaModel (UNTUK PESAN BROADCAST)
        if(file_exists('models/FiturOrmawaModel.php')) {
            require_once 'models/FiturOrmawaModel.php';
            $this->fiturModel = new FiturOrmawaModel();
        }
    }

    public function dashboard() {
        $id = $_SESSION['anggota_id'];
        
        // Ambil Data Profil
        $anggota = $this->anggotaModel->getAnggotaById($id);
        
        // Ambil Kepengurusan Aktif (Penting untuk filter pesan)
        $kepengurusan = $this->anggotaModel->getKepengurusanByAnggota($id);
        
        // Ambil Riwayat Pendaftaran
        $pendaftaran = $this->pendaftaranModel->getRiwayatKepengurusan($id);

        // [PENTING] Ambil Pesan Broadcast
        $pesan_broadcast = [];
        if ($this->fiturModel) {
            $pesan_broadcast = $this->fiturModel->getPesanByAnggota($id);
        }

        require 'views/anggota/dashboard.php';
    }

    public function profile() {
        $id = $_SESSION['anggota_id'];
        $anggota = $this->anggotaModel->getAnggotaById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $id,
                'nama' => $_POST['nama_lengkap'],
                'nim' => $_POST['nim'],
                'email' => $_POST['email'],
                'no_hp' => $_POST['no_telepon'],
                'jurusan' => $_POST['jurusan'],
                'prodi' => $_POST['prodi'] ?? '',
                'angkatan' => $_POST['angkatan'],
                'foto' => $anggota['foto_profil']
            ];

            if (!empty($_FILES['foto_profil']['name'])) {
                $target_dir = "assets/images/profil/";
                $file_name = time() . '_' . basename($_FILES["foto_profil"]["name"]);
                move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_dir . $file_name);
                $data['foto'] = $file_name;
            }

            $this->anggotaModel->updateAnggota($data);
            header('Location: index.php?action=profile&status=sukses');
            exit;
        }

        require 'views/anggota/profile.php';
    }

    public function riwayat() {
        $id = $_SESSION['anggota_id'];
        $pendaftaran_kepengurusan = $this->pendaftaranModel->getRiwayatKepengurusan($id);
        
        $pendaftaran_divisi = [];
        if(method_exists($this->pendaftaranModel, 'getRiwayatDivisi')) {
            $pendaftaran_divisi = $this->pendaftaranModel->getRiwayatDivisi($id);
        }

        require 'views/anggota/riwayat.php';
    }
}
?>