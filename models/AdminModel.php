<?php
// File: models/AdminModel.php

class AdminModel {
    private $db;
    private $table = 'admin';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function login($identifier, $password) {
        // Login Admin via Username atau Email
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE username = :id OR email = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $identifier);
        $stmt->execute();
        
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi Password (jika password di DB di-hash)
        // Jika password admin Anda masih plain text (belum di-hash), 
        // gunakan: if ($admin && $admin['password'] == $password) { ... }
        
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        
        return false;
    }
}
?>