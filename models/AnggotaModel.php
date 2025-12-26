<?php
require_once 'Database.php';

class AnggotaModel {
    private $db;
    private $table = 'anggota';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // =========================================================
    // 1. LOGIN SYSTEM
    // =========================================================
    public function login($identifier, $password) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE (email = :id OR nim = :id)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $identifier);
        $stmt->execute();
        
        $anggota = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$anggota) {
            return false;
        }

        if ($anggota['status_aktif'] !== 'active') {
            return false;
        }

        if (password_verify($password, $anggota['password'])) {
            return $anggota;
        }

        return false;
    }

    // =========================================================
    // 2. REGISTER SYSTEM
    // =========================================================
    public function register($data) {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      (nim, nama_lengkap, email, password, fakultas, jurusan, angkatan, no_telepon, status_aktif) 
                      VALUES 
                      (:nim, :nama_lengkap, :email, :password, :fakultas, :jurusan, :angkatan, :no_telepon, 'active')";
            
            $stmt = $this->db->prepare($query);
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

            $stmt->execute([
                ':nim'          => $data['nim'],
                ':nama_lengkap' => $data['nama_lengkap'],
                ':email'        => $data['email'],
                ':password'     => $password_hash,
                ':fakultas'     => $data['fakultas'],
                ':jurusan'      => $data['jurusan'],
                ':angkatan'     => $data['angkatan'],
                ':no_telepon'   => $data['no_telepon']
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Register Error: " . $e->getMessage());
            return false;
        }
    }

    // =========================================================
    // 3. FUNGSI PENDUKUNG (GET, CEK, UPDATE)
    // =========================================================

    public function getAnggotaById($id) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE anggota_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cekEmailExist($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM " . $this->table . " WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0;
    }

    public function cekNimExist($nim) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM " . $this->table . " WHERE nim = :nim");
        $stmt->execute([':nim' => $nim]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0;
    }

    public function updateProfile($data) {
        $query = "UPDATE " . $this->table . " SET nama_lengkap = :nama_lengkap, no_telepon = :no_telepon, fakultas = :fakultas, jurusan = :jurusan, angkatan = :angkatan WHERE anggota_id = :anggota_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function getJumlahAnggota() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM " . $this->table . " WHERE status_aktif = 'active'");
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (Exception $e) { return 0; }
    }

    // =========================================================
    // 4. FITUR DASHBOARD & RIWAYAT
    // =========================================================

    public function getKepengurusanByAnggota($id) {
        try {
            $query = "SELECT k.*, o.nama_organisasi, j.nama_jabatan, d.nama_divisi 
                      FROM kepengurusan k 
                      JOIN organisasi o ON k.organisasi_id=o.organisasi_id 
                      JOIN jabatan j ON k.jabatan_id=j.jabatan_id 
                      LEFT JOIN divisi d ON k.divisi_id=d.divisi_id 
                      WHERE k.anggota_id=:id AND k.status_aktif='active'";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id'=>$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { return []; }
    }

    public function getPendaftaranByAnggota($id) {
        try {
            $query = "SELECT pk.*, o.nama_organisasi, j.nama_jabatan, 'kepengurusan' as jenis 
                      FROM pendaftaran_kepengurusan pk
                      JOIN organisasi o ON pk.organisasi_id = o.organisasi_id
                      JOIN jabatan j ON pk.jabatan_id_diajukan = j.jabatan_id
                      WHERE pk.anggota_id = :id
                      ORDER BY pk.tanggal_daftar DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id'=>$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { return []; }
    }
    
    // --- FUNGSI RIWAYAT (DIPERBAIKI) ---

    public function getRiwayatPendaftaranDivisi($anggota_id) {
        try {
            $query = "SELECT pd.*, d.nama_divisi, o.nama_organisasi 
                      FROM pendaftaran_divisi pd
                      JOIN divisi d ON pd.divisi_id = d.divisi_id
                      JOIN organisasi o ON d.organisasi_id = o.organisasi_id
                      WHERE pd.anggota_id = :id
                      ORDER BY pd.pendaftaran_divisi_id DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $anggota_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { return []; }
    }

    public function getRiwayatPendaftaranKepengurusan($anggota_id) {
        try {
            // PERBAIKAN: Mengganti 'pk.pendaftaran_id' menjadi 'pk.pendaftaran_kepengurusan_id'
            $query = "SELECT pk.*, o.nama_organisasi, j.nama_jabatan 
                      FROM pendaftaran_kepengurusan pk
                      JOIN organisasi o ON pk.organisasi_id = o.organisasi_id
                      JOIN jabatan j ON pk.jabatan_id_diajukan = j.jabatan_id
                      WHERE pk.anggota_id = :id
                      ORDER BY pk.pendaftaran_kepengurusan_id DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $anggota_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { 
            // error_log($e->getMessage()); // Uncomment jika ingin log error
            return []; 
        }
    }

} // End Class
?>