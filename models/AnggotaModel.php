<?php
// File: models/AnggotaModel.php

class AnggotaModel {
    private $db;
    private $table = 'anggota';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // --- FUNGSI LOGIN UTAMA ---
    public function login($identifier, $password) {
        // Bisa login pakai NIM atau Email
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE nim = :id OR email = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $identifier);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi Password
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    // Fungsi Register
    public function register($data) {
        // Hash Password sebelum simpan
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $query = "INSERT INTO " . $this->table . " 
                  (nim, nama_lengkap, email, password, jurusan, fakultas, angkatan, no_telepon, role) 
                  VALUES 
                  (:nim, :nama, :email, :pass, :jurusan, :fakultas, :angkatan, :hp, 'anggota')";
        
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':nim'      => $data['nim'],
                ':nama'     => $data['nama_lengkap'],
                ':email'    => $data['email'],
                ':pass'     => $hashedPassword,
                ':jurusan'  => $data['jurusan'],
                ':fakultas' => $data['fakultas'],
                ':angkatan' => $data['angkatan'],
                ':hp'       => $data['no_telepon']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Cek Duplikasi NIM
    public function cekNimExist($nim) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE nim = :nim");
        $stmt->execute([':nim' => $nim]);
        return $stmt->fetchColumn() > 0;
    }

    // Cek Duplikasi Email
    public function cekEmailExist($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public function getAnggotaById($id) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE anggota_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>