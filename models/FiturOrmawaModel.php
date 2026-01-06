<?php
require_once 'Database.php';

class FiturOrmawaModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // --- FITUR KEGIATAN ---
    public function getKegiatanByOrg($org_id) {
        $stmt = $this->db->prepare("SELECT * FROM kegiatan WHERE organisasi_id = :id ORDER BY tanggal_kegiatan DESC");
        $stmt->execute([':id' => $org_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tambahKegiatan($data) {
        $sql = "INSERT INTO kegiatan (organisasi_id, nama_kegiatan, deskripsi, tanggal_kegiatan, foto_kegiatan) 
                VALUES (:org_id, :nama, :desc, :tgl, :foto)";
        return $this->db->prepare($sql)->execute($data);
    }

    public function hapusKegiatan($id) {
        // Ambil nama file dulu untuk dihapus dari folder
        $stmt = $this->db->prepare("SELECT foto_kegiatan FROM kegiatan WHERE kegiatan_id = :id");
        $stmt->execute([':id' => $id]);
        $foto = $stmt->fetchColumn();
        
        if ($foto && file_exists(__DIR__ . '/../assets/uploads/kegiatan/' . $foto)) {
            unlink(__DIR__ . '/../assets/uploads/kegiatan/' . $foto);
        }

        return $this->db->prepare("DELETE FROM kegiatan WHERE kegiatan_id = :id")->execute([':id' => $id]);
    }

    // --- FITUR LAPORAN ---
    public function getLaporanByOrg($org_id) {
        $stmt = $this->db->prepare("SELECT * FROM laporan_kerja WHERE organisasi_id = :id ORDER BY tanggal_upload DESC");
        $stmt->execute([':id' => $org_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tambahLaporan($data) {
        $sql = "INSERT INTO laporan_kerja (organisasi_id, judul_laporan, file_laporan, keterangan) 
                VALUES (:org_id, :judul, :file, :ket)";
        return $this->db->prepare($sql)->execute($data);
    }

    public function hapusLaporan($id) {
        $stmt = $this->db->prepare("SELECT file_laporan FROM laporan_kerja WHERE laporan_id = :id");
        $stmt->execute([':id' => $id]);
        $file = $stmt->fetchColumn();
        
        if ($file && file_exists(__DIR__ . '/../assets/uploads/laporan/' . $file)) {
            unlink(__DIR__ . '/../assets/uploads/laporan/' . $file);
        }

        return $this->db->prepare("DELETE FROM laporan_kerja WHERE laporan_id = :id")->execute([':id' => $id]);
    }

    // --- FITUR PESAN BROADCAST ---
    public function getPesanByOrg($org_id) {
        $stmt = $this->db->prepare("SELECT * FROM pesan_broadcast WHERE organisasi_id = :id ORDER BY tanggal_kirim DESC");
        $stmt->execute([':id' => $org_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function kirimPesan($data) {
        $sql = "INSERT INTO pesan_broadcast (organisasi_id, judul_pesan, isi_pesan, target_penerima) 
                VALUES (:org_id, :judul, :isi, :target)";
        return $this->db->prepare($sql)->execute($data);
    }
}
?>