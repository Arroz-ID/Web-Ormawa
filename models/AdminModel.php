<?php
require_once 'Database.php';

class AdminModel {
    private $db;
    private $table = 'admin';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Cek Login: Menerima Username ATAU Email
    public function login($identifier, $password) {
        // Query cek Username ATAU Email
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE (username = :id OR email = :id) 
                  AND status_aktif = 'active'";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $identifier);
        $stmt->execute();
        
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }
}
?>