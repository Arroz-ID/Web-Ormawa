<?php
require_once 'Database.php';

class PendaftaranModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function daftarKepengurusan($data) {
        // Query Updated: Menambahkan berkas_tambahan dan detail_tambahan
        $query = "INSERT INTO pendaftaran_kepengurusan 
                  (anggota_id, organisasi_id, jabatan_id_diajukan, divisi_id_diajukan, 
                   pengalaman_organisasi, motivasi, berkas_tambahan, detail_tambahan, status_pendaftaran, tanggal_daftar) 
                  VALUES 
                  (:anggota_id, :organisasi_id, :jabatan_id_diajukan, :divisi_id_diajukan, 
                   :pengalaman_organisasi, :motivasi, :berkas, :detail, 'pending', NOW())";
        
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([
            ':anggota_id' => $data['anggota_id'],
            ':organisasi_id' => $data['organisasi_id'],
            ':jabatan_id_diajukan' => $data['jabatan_id_diajukan'],
            ':divisi_id_diajukan' => $data['divisi_id_diajukan'],
            ':pengalaman_organisasi' => $data['pengalaman_organisasi'],
            ':motivasi' => $data['motivasi'],
            ':berkas' => $data['berkas_tambahan'],
            ':detail' => $data['detail_tambahan']
        ]);
    }
    
    // --- FUNGSI ADMIN (TETAP SAMA) ---
    public function getPendingByOrganisasi($organisasi_id) {
        $query = "SELECT pk.*, a.nama_lengkap, a.nim, a.jurusan, j.nama_jabatan 
                  FROM pendaftaran_kepengurusan pk 
                  JOIN anggota a ON pk.anggota_id = a.anggota_id
                  JOIN jabatan j ON pk.jabatan_id_diajukan = j.jabatan_id
                  WHERE pk.organisasi_id = :org_id AND pk.status_pendaftaran = 'pending'
                  ORDER BY pk.tanggal_daftar ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':org_id' => $organisasi_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendaftaranById($id) {
        $query = "SELECT * FROM pendaftaran_kepengurusan WHERE pendaftaran_kepengurusan_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status, $catatan) {
        $query = "UPDATE pendaftaran_kepengurusan 
                  SET status_pendaftaran = :status, catatan_admin = :catatan 
                  WHERE pendaftaran_kepengurusan_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':status' => $status, ':catatan' => $catatan, ':id' => $id]);
    }
    
    public function insertPengurusBaru($data) {
        $query = "INSERT INTO kepengurusan (organisasi_id, anggota_id, jabatan_id, divisi_id, periode_mulai, periode_selesai, status_aktif)
                  VALUES (:org, :anggota, :jab, :div, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 'active')";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':org' => $data['organisasi_id'],
            ':anggota' => $data['anggota_id'],
            ':jab' => $data['jabatan_id_diajukan'],
            ':div' => $data['divisi_id_diajukan']
        ]);
    }
}
?>