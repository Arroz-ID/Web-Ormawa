<?php
require_once 'Database.php';

class DivisiModel {
    private $db;
    private $table = 'divisi';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getDivisiByOrganisasi($organisasi_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE organisasi_id = :organisasi_id AND status_aktif = 'active'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':organisasi_id', $organisasi_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDivisiById($id) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE divisi_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // --- FUNGSI BARU (CRUD) ---
    public function tambahDivisi($data) {
        $query = "INSERT INTO " . $this->table . " (organisasi_id, nama_divisi, deskripsi_divisi, kuota_anggota, status_aktif) 
                  VALUES (:org_id, :nama, :desc, :kuota, 'active')";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':org_id' => $data['organisasi_id'],
            ':nama' => $data['nama_divisi'],
            ':desc' => $data['deskripsi_divisi'],
            ':kuota' => $data['kuota_anggota']
        ]);
    }

    public function updateDivisi($data) {
        $query = "UPDATE " . $this->table . " SET nama_divisi = :nama, deskripsi_divisi = :desc, kuota_anggota = :kuota 
                  WHERE divisi_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':nama' => $data['nama_divisi'],
            ':desc' => $data['deskripsi_divisi'],
            ':kuota' => $data['kuota_anggota'],
            ':id' => $data['divisi_id']
        ]);
    }

    public function hapusDivisi($id) {
        // Soft delete
        $query = "UPDATE " . $this->table . " SET status_aktif = 'inactive' WHERE divisi_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    public function getJabatanTersedia() {
        $stmt = $this->db->query("SELECT * FROM jabatan ORDER BY level_jabatan");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Untuk fitur Anggota
    public function getAnggotaByDivisi($divisi_id) {
        // (Kode lama Anda di sini, biarkan kosong jika belum ada atau copy dari sebelumnya)
        return []; 
    }
}
?>