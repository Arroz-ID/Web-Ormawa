<?php
class FiturOrmawaModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // --- FITUR PESAN BROADCAST ---
    
    public function tambahPesan($data) {
        $query = "INSERT INTO pesan_broadcast (organisasi_id, judul, isi_pesan, tanggal_kirim) 
                  VALUES (:org, :judul, :isi, NOW())";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':org' => $data['organisasi_id'],
            ':judul' => $data['judul'],
            ':isi' => $data['isi_pesan']
        ]);
    }

    public function getPesanByOrg($org_id) {
        $query = "SELECT * FROM pesan_broadcast WHERE organisasi_id = :id ORDER BY tanggal_kirim DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $org_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // [DIPERBAIKI] Ambil pesan untuk User (Query lebih longgar: Active atau NULL)
    public function getPesanByAnggota($anggota_id) {
        $query = "SELECT p.*, o.nama_organisasi, o.logo 
                  FROM pesan_broadcast p
                  JOIN organisasi o ON p.organisasi_id = o.organisasi_id
                  JOIN kepengurusan k ON k.organisasi_id = o.organisasi_id
                  WHERE k.anggota_id = :id 
                  AND (k.status_aktif = 'active' OR k.status_aktif IS NULL) 
                  ORDER BY p.tanggal_kirim DESC LIMIT 10";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $anggota_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hapusPesan($id) {
        $query = "DELETE FROM pesan_broadcast WHERE pesan_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // --- FITUR LAIN (KEGIATAN, PROGJA, DLL) ---

    public function getKegiatanByOrg($id) {
        $query = "SELECT * FROM kegiatan WHERE organisasi_id = :id ORDER BY tanggal_kegiatan DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function tambahKegiatan($data) {
        $query = "INSERT INTO kegiatan (organisasi_id, nama_kegiatan, deskripsi, tanggal_kegiatan, foto_kegiatan) VALUES (:org, :nama, :desc, :tgl, :foto)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':org'=>$data['organisasi_id'], ':nama'=>$data['nama_kegiatan'], ':desc'=>$data['deskripsi'], ':tgl'=>$data['tanggal_kegiatan'], ':foto'=>$data['foto_kegiatan']]);
    }
    
    public function hapusKegiatan($id) {
        $stmt = $this->db->prepare("DELETE FROM kegiatan WHERE kegiatan_id = :id");
        return $stmt->execute([':id'=>$id]);
    }

    public function getProgjaByOrganisasi($id) {
        $query = "SELECT * FROM program_kerja WHERE organisasi_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id'=>$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tambahProgja($data) {
        $query = "INSERT INTO program_kerja (organisasi_id, nama_program, deskripsi, target_waktu) VALUES (:org, :nama, :desc, :target)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':org'=>$data['organisasi_id'], ':nama'=>$data['nama_program'], ':desc'=>$data['deskripsi'], ':target'=>$data['target_waktu']]);
    }

    public function hapusProgja($id) {
        $stmt = $this->db->prepare("DELETE FROM program_kerja WHERE program_kerja_id = :id");
        return $stmt->execute([':id'=>$id]);
    }

    public function getLaporanByOrg($id) {
        $query = "SELECT * FROM laporan_kinerja WHERE organisasi_id = :id ORDER BY tanggal_upload DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id'=>$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tambahLaporan($data) {
        $query = "INSERT INTO laporan_kinerja (organisasi_id, judul_laporan, file_laporan, keterangan, tanggal_upload) VALUES (:org, :judul, :file, :ket, NOW())";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':org'=>$data['organisasi_id'], ':judul'=>$data['judul_laporan'], ':file'=>$data['file_laporan'], ':ket'=>$data['keterangan']]);
    }

    public function hapusLaporan($id) {
        $stmt = $this->db->prepare("DELETE FROM laporan_kinerja WHERE laporan_id = :id");
        return $stmt->execute([':id'=>$id]);
    }
}
?>