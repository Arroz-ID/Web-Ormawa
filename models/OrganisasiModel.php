<?php
require_once 'Database.php';

class OrganisasiModel {
    private $db;
    private $table = 'organisasi';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // --- PERBAIKAN 1: QUERY LEBIH AMAN AGAR DATA MUNCUL ---
    public function getAllOrganisasi() {
        // Kita gunakan logika OR agar data lama (yang statusnya NULL/Kosong) tetap muncul
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
        $query = "SELECT k.*, a.nama_lengkap, a.nim, a.jurusan, j.nama_jabatan, d.nama_divisi
                  FROM kepengurusan k
                  JOIN anggota a ON k.anggota_id = a.anggota_id
                  JOIN jabatan j ON k.jabatan_id = j.jabatan_id
                  LEFT JOIN divisi d ON k.divisi_id = d.divisi_id
                  WHERE k.organisasi_id = :organisasi_id 
                  AND (k.status_aktif = 'active' OR k.status_aktif IS NULL)
                  ORDER BY j.level_jabatan, d.nama_divisi";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':organisasi_id', $organisasi_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tambahOrganisasi($data) {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      (nama_organisasi, jenis_organisasi, deskripsi, visi, misi, tanggal_berdiri, logo, status_aktif) 
                      VALUES (:nama, :jenis, :deskripsi, :visi, :misi, :tanggal, :logo, 'active')";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':nama' => $data['nama'], ':jenis' => $data['jenis'], ':deskripsi' => $data['deskripsi'],
                ':visi' => $data['visi'], ':misi' => $data['misi'], ':tanggal' => $data['tanggal'], ':logo' => $data['logo']
            ]);
        } catch (PDOException $e) { return false; }
    }

    // --- PERBAIKAN 2: CEK PERUBAHAN LEBIH TELITI ---
    public function updateOrganisasi($data) {
        try {
            $oldData = $this->getOrganisasiById($data['id']);
            
            // Bandingkan data (Perhatikan penanganan NULL pada logo)
            $oldLogo = $oldData['logo'] ?? '';
            $newLogo = $data['logo'] ?? '';

            if (
                $oldData['nama_organisasi'] == $data['nama'] &&
                $oldData['jenis_organisasi'] == $data['jenis'] &&
                $oldData['deskripsi'] == $data['deskripsi'] &&
                $oldData['visi'] == $data['visi'] &&
                $oldData['misi'] == $data['misi'] &&
                $oldData['tanggal_berdiri'] == $data['tanggal'] &&
                $oldLogo == $newLogo
            ) {
                return 'no_change';
            }

            // Jalankan Update
            $query = "UPDATE " . $this->table . " 
                      SET nama_organisasi = :nama, jenis_organisasi = :jenis, 
                          deskripsi = :deskripsi, visi = :visi, misi = :misi, 
                          tanggal_berdiri = :tanggal, logo = :logo
                      WHERE organisasi_id = :id";
            $stmt = $this->db->prepare($query);
            
            $stmt->execute([
                ':nama' => $data['nama'], 
                ':jenis' => $data['jenis'], 
                ':deskripsi' => $data['deskripsi'],
                ':visi' => $data['visi'], 
                ':misi' => $data['misi'], 
                ':tanggal' => $data['tanggal'], 
                ':logo' => $newLogo,
                ':id' => $data['id']
            ]);

            return true;
        } catch (PDOException $e) { 
            return false; 
        }
    }

    public function hapusOrganisasi($id) {
        try {
            $query = "UPDATE " . $this->table . " SET status_aktif = 'inactive' WHERE organisasi_id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function getStatistikOrganisasi($organisasi_id) {
        $stats = ['total_pengurus' => 0, 'total_pendaftar' => 0, 'pendaftar_pending' => 0];
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM kepengurusan WHERE organisasi_id = :id AND (status_aktif = 'active' OR status_aktif IS NULL)");
            $stmt->execute([':id' => $organisasi_id]);
            $stats['total_pengurus'] = $stmt->fetchColumn();

            $stmt = $this->db->prepare("SELECT COUNT(*) FROM pendaftaran_kepengurusan WHERE organisasi_id = :id");
            $stmt->execute([':id' => $organisasi_id]);
            $stats['total_pendaftar'] = $stmt->fetchColumn();

            $stmt = $this->db->prepare("SELECT COUNT(*) FROM pendaftaran_kepengurusan WHERE organisasi_id = :id AND status_pendaftaran = 'pending'");
            $stmt->execute([':id' => $organisasi_id]);
            $stats['pendaftar_pending'] = $stmt->fetchColumn();
        } catch (PDOException $e) {}
        return $stats;
    }
}
?>