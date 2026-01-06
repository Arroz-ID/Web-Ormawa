<?php
// File: models/DivisiModel.php

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

    public function hapusDivisi($id) {
        $query = "UPDATE " . $this->table . " SET status_aktif = 'inactive' WHERE divisi_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    public function getJabatanTersedia() {
        $stmt = $this->db->query("SELECT * FROM jabatan ORDER BY level_jabatan");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAnggotaByDivisi($divisi_id) {
        // Mengambil anggota yang diterima di divisi tersebut
        $query = "SELECT a.nama_lengkap, a.foto_profil, a.jurusan, a.angkatan, j.nama_jabatan 
                  FROM kepengurusan k
                  JOIN anggota a ON k.anggota_id = a.anggota_id
                  JOIN jabatan j ON k.jabatan_id = j.jabatan_id
                  WHERE k.divisi_id = :div_id AND k.status_aktif = 'active'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':div_id' => $divisi_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>