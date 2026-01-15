<?php
// File: models/AnggotaModel.php

class AnggotaModel {
    private $db;
    private $table = 'anggota';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // 1. Login User (Bisa pakai NIM atau Email)
    public function login($identifier, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE nim = :id OR email = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $identifier);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi Password Hash
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // 2. Cek apakah NIM sudah terdaftar (untuk Register)
    public function cekNimExist($nim) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE nim = :nim";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nim', $nim);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // 3. Cek apakah Email sudah terdaftar
    public function cekEmailExist($email) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // 4. Registrasi Anggota Baru
    public function register($data) {
        try {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $query = "INSERT INTO " . $this->table . " 
                      (nim, nama_lengkap, email, password, jurusan, fakultas, angkatan, no_telepon) 
                      VALUES (:nim, :nama, :email, :pass, :jurusan, :fakultas, :angkatan, :telp)";
            
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':nim'      => $data['nim'],
                ':nama'     => $data['nama_lengkap'],
                ':email'    => $data['email'],
                ':pass'     => $hashedPassword,
                ':jurusan'  => $data['jurusan'],
                ':fakultas' => $data['fakultas'],
                ':angkatan' => $data['angkatan'],
                ':telp'     => $data['no_telepon']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // 5. Ambil Data Profil Anggota
    public function getAnggotaById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE anggota_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 6. Update Profil Anggota
    public function updateAnggota($data) {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET nama_lengkap = :nama, 
                          nim = :nim, 
                          email = :email, 
                          no_telepon = :no_hp, 
                          jurusan = :jurusan, 
                          prodi = :prodi,
                          angkatan = :angkatan, 
                          foto_profil = :foto
                      WHERE anggota_id = :id";
            
            // Handle jika kolom prodi tidak ada di tabel (opsional)
            // Jika error kolom tidak ditemukan, hapus baris 'prodi' => ...
            
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':nama'     => $data['nama'],
                ':nim'      => $data['nim'],
                ':email'    => $data['email'],
                ':no_hp'    => $data['no_hp'],
                ':jurusan'  => $data['jurusan'],
                ':prodi'    => $data['prodi'] ?? '', // Default kosong jika null
                ':angkatan' => $data['angkatan'],
                ':foto'     => $data['foto'],
                ':id'       => $data['id']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // 7. Ambil Kepengurusan Aktif (Untuk Dashboard)
    public function getKepengurusanByAnggota($id) {
        $query = "SELECT k.*, o.nama_organisasi, j.nama_jabatan, d.nama_divisi 
                  FROM kepengurusan k 
                  JOIN organisasi o ON k.organisasi_id = o.organisasi_id 
                  JOIN jabatan j ON k.jabatan_id = j.jabatan_id 
                  LEFT JOIN divisi d ON k.divisi_id = d.divisi_id 
                  WHERE k.anggota_id = :id 
                  AND (k.status_aktif = 'active' OR k.status_aktif IS NULL)";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>