<?php
require_once 'config/database.php';

class Database {
    private $connection;
    private static $instance = null;
// Tambahkan di dalam class Database di models/Database.php
public static function catatAktivitas($userId, $role, $aktivitas, $detail = null) {
    try {
        $db = self::getInstance()->getConnection();
        $ip = $_SERVER['REMOTE_ADDR'];
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
        try {
            // Coba konek ke database
            $this->connection = new PDO(
                DatabaseConfig::getDSN(),
                DatabaseConfig::$username,
                DatabaseConfig::$password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $e) {
            // Jika database tidak ada, buat database terlebih dahulu
            if ($e->getCode() == 1049) { // Unknown database
                DatabaseConfig::createDatabaseIfNotExists();
                
                // Coba konek lagi setelah membuat database
                $this->connection = new PDO(
                    DatabaseConfig::getDSN(),
                    DatabaseConfig::$username,
                    DatabaseConfig::$password
                );
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Buat tabel-tabel setelah database dibuat
                $this->createTables();
            } else {
                die("Connection failed: " . $e->getMessage());
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
        // SQL untuk membuat semua tabel
        $sql = file_get_contents('config/database_schema.sql');
        $this->connection->exec($sql);
        
        // Insert sample data
        $this->insertSampleData();
    }
    
    private function insertSampleData() {
        // Insert sample data seperti yang ada di script SQL sebelumnya
        $sampleData = file_get_contents('config/sample_data.sql');
        $this->connection->exec($sampleData);
    }
    
}
?>