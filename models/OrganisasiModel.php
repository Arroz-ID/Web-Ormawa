<?php
// File: models/OrganisasiModel.php

class OrganisasiModel {
    private $db;
    private $table = 'organisasi';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllOrganisasi() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE (status_aktif = 'active' OR status_aktif IS NULL OR status_aktif = '') 
                  ORDER BY nama_organisasi";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrganisasiById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE organisasi_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrganisasiDetail($id) {
        // Menghitung jumlah divisi dan pengurus secara otomatis
        $query = "SELECT o.*, COUNT(DISTINCT d.divisi_id) as jumlah_divisi, COUNT(DISTINCT k.kepengurusan_id) as jumlah_pengurus
                  FROM organisasi o
                  LEFT JOIN divisi d ON o.organisasi_id = d.organisasi_id AND (d.status_aktif = 'active' OR d.status_aktif IS NULL)
                  LEFT JOIN kepengurusan k ON o.organisasi_id = k.organisasi_id AND (k.status_aktif = 'active' OR k.status_aktif IS NULL)
                  WHERE o.organisasi_id = :id
                  GROUP BY o.organisasi_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getKepengurusanByOrganisasi($organisasi_id) {
        $query = "SELECT k.*, a.nama_lengkap, a.nim, a.jurusan, j.nama_jabatan, d.nama_divisi, a.foto_profil
                  FROM kepengurusan k
                  JOIN anggota a ON k.anggota_id = a.anggota_id
                  JOIN jabatan j ON k.jabatan_id = j.jabatan_id
                  LEFT JOIN divisi d ON k.divisi_id = d.divisi_id
                  WHERE k.organisasi_id = :organisasi_id 
                  AND (k.status_aktif = 'active' OR k.status_aktif IS NULL)
                  ORDER BY j.level_jabatan ASC, d.nama_divisi ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':organisasi_id', $organisasi_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- FUNGSI BARU UNTUK GALERI KEGIATAN ---
    public function getKegiatanByOrganisasi($organisasi_id) {
        try {
            // Mengambil 6 kegiatan terakhir
            $query = "SELECT * FROM kegiatan 
                      WHERE organisasi_id = :id 
                      ORDER BY tanggal_kegiatan DESC LIMIT 6";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $organisasi_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Jika tabel kegiatan belum dibuat, kembalikan array kosong agar tidak error
            return [];
        }
    }

    public function updateOrganisasi($data) {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET nama_organisasi = :nama, jenis_organisasi = :jenis, 
                          deskripsi = :deskripsi, visi = :visi, misi = :misi, 
                          tanggal_berdiri = :tanggal, logo = :logo
                      WHERE organisasi_id = :id";
            $stmt = $this->db->prepare($query);
            
            return $stmt->execute([
                ':nama' => $data['nama'], 
                ':jenis' => $data['jenis'], 
                ':deskripsi' => $data['deskripsi'],
                ':visi' => $data['visi'], 
                ':misi' => $data['misi'], 
                ':tanggal' => $data['tanggal'], 
                ':logo' => $data['logo'],
                ':id' => $data['id']
            ]);
        } catch (PDOException $e) { return false; }
    }
}
?>