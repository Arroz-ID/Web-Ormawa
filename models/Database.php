<?php
// File: models/Database.php

class Database {
    private $connection;
    private static $instance = null;

    // Fungsi statis untuk mencatat log aktivitas pengguna
    public static function catatAktivitas($userId, $role, $aktivitas, $detail = null) {
        try {
            $db = self::getInstance()->getConnection();
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
            $sql = "INSERT INTO log_aktivitas (user_id, role, aktivitas, detail, ip_address) 
                    VALUES (:uid, :role, :act, :detail, :ip)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':uid'    => $userId,
                ':role'   => $role,
                ':act'    => $aktivitas,
                ':detail' => $detail,
                ':ip'     => $ip
            ]);
        } catch (Exception $e) {
            // Abaikan error log agar tidak mengganggu alur utama
        }
    }

    public function __construct() {
        // --- PERBAIKAN DI SINI ---
        // Panggil manual config karena nama file 'database.php' tidak sama dengan class 'DatabaseConfig'
        // sehingga Autoloader gagal menemukannya.
        $configPath = __DIR__ . '/../config/database.php';
        if (file_exists($configPath)) {
            require_once $configPath;
        } else {
            die("Critical Error: File konfigurasi database tidak ditemukan di: " . $configPath);
        }
        // -------------------------

        try {
            $this->connection = new PDO(
                DatabaseConfig::getDSN(),
                DatabaseConfig::$username,
                DatabaseConfig::$password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $e) {
            // Jika error code 1049 (Database not found), coba buat database otomatis
            if ($e->getCode() == 1049) { 
                DatabaseConfig::createDatabaseIfNotExists();
                // Coba konek ulang setelah database dibuat
                $this->connection = new PDO(
                    DatabaseConfig::getDSN(),
                    DatabaseConfig::$username,
                    DatabaseConfig::$password
                );
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->createTables();
            } else {
                die("Koneksi Database Gagal: " . $e->getMessage());
            }
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
    
    private function createTables() {
        $schemaPath = __DIR__ . '/../config/database_schema.sql';
        $dataPath = __DIR__ . '/../config/sample_data.sql';

        if (file_exists($schemaPath)) {
            $sql = file_get_contents($schemaPath);
            $this->connection->exec($sql);
        }

        if (file_exists($dataPath)) {
            $sampleData = file_get_contents($dataPath);
            $this->connection->exec($sampleData);
        }
    }
}
?>