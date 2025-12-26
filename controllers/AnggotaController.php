<?php
require_once 'models/Database.php';

class AnggotaController {
    private $anggotaModel;
    private $organisasiModel;

    public function __construct() {
        // Autoloader akan otomatis mencari model
        $this->anggotaModel = new AnggotaModel();
        $this->organisasiModel = new OrganisasiModel();
    }

    public function dashboard() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['anggota_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $anggota_id = $_SESSION['anggota_id'];
        $anggota = $this->anggotaModel->getAnggotaById($anggota_id);
        $organisasi = $this->organisasiModel->getAllOrganisasi();
        $kepengurusan = $this->anggotaModel->getKepengurusanByAnggota($anggota_id);
        $pendaftaran = $this->anggotaModel->getPendaftaranByAnggota($anggota_id);

        require 'views/anggota/dashboard.php';
    }

    public function profile() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['anggota_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $anggota_id = $_SESSION['anggota_id'];
        $anggota = $this->anggotaModel->getAnggotaById($anggota_id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'anggota_id' => $anggota_id,
                'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
                'no_telepon' => $_POST['no_telepon'] ?? '',
                'fakultas' => $_POST['fakultas'] ?? '',
                'jurusan' => $_POST['jurusan'] ?? '',
                'angkatan' => $_POST['angkatan'] ?? ''
            ];

            // LOGIKA UPLOAD FOTO (ANTI DUPLIKAT)
            if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->uploadFoto($_FILES['foto_profil']);
                if ($uploadResult) {
                    $data['foto_profil'] = $uploadResult;
                    // Hapus foto lama jika bukan default dan beda file (opsional)
                    // ... kode hapus file lama bisa ditambahkan di sini
                } else {
                    $error = "Gagal mengupload foto. Pastikan format JPG/PNG dan ukuran maks 2MB.";
                }
            }

            // Jika tidak ada error upload atau tidak ada upload, lanjutkan update
            if (!isset($error)) {
                if ($this->anggotaModel->updateProfile($data)) {
                    $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
                    
                    Database::catatAktivitas(
                        $_SESSION['anggota_id'], 
                        'anggota', 
                        'Update Profil', 
                        'Mengubah data profil pribadi'
                    );

                    $success = "Profile berhasil diperbarui!";
                    // Refresh data anggota terbaru
                    $anggota = $this->anggotaModel->getAnggotaById($anggota_id);
                } else {
                    $error = "Terjadi kesalahan saat menyimpan data ke database.";
                }
            }
        }

        require 'views/anggota/profile.php';
    }

    public function riwayat() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['anggota_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $anggota_id = $_SESSION['anggota_id'];
        $pendaftaran_divisi = $this->anggotaModel->getRiwayatPendaftaranDivisi($anggota_id);
        $pendaftaran_kepengurusan = $this->anggotaModel->getRiwayatPendaftaranKepengurusan($anggota_id);

        require 'views/anggota/riwayat.php';
    }

    // =================================================================
    // FUNGSI HELPER UPLOAD (ANTI DUPLIKAT & HASHING)
    // =================================================================
    private function uploadFoto($file) {
        $targetDir = "assets/images/profiles/";
        
        // Buat folder jika belum ada
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $tmpName = $file["tmp_name"];
        $fileName = basename($file["name"]);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // 1. Validasi Ekstensi
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileType, $allowedTypes)) {
            return false; // Tipe file tidak diizinkan
        }

        // 2. Validasi Ukuran (Maks 2MB)
        if ($file["size"] > 2000000) {
            return false; // File terlalu besar (>2MB)
        }

        // 3. CEK DUPLIKASI KONTEN (MD5 HASH)
        // Hitung hash dari isi file sementara
        $fileHash = md5_file($tmpName);
        
        // Nama file baru menggunakan hash konten + ekstensi asli
        // Contoh: a1b2c3d4e5f6... .jpg
        $newFileName = $fileHash . '.' . $fileType;
        $targetFilePath = $targetDir . $newFileName;

        // Cek apakah file dengan hash ini SUDAH ADA di folder?
        if (file_exists($targetFilePath)) {
            // JIKA SUDAH ADA: Tidak perlu upload ulang. Gunakan file yang sudah ada.
            return $newFileName;
        } else {
            // JIKA BELUM ADA: Pindahkan file baru ke target
            if (move_uploaded_file($tmpName, $targetFilePath)) {
                return $newFileName;
            } else {
                return false; // Gagal memindahkan file
            }
        }
    }
}
?>