<?php
class DatabaseConfig {
    public static $host = 'localhost';
    public static $username = 'root'; // Sesuaikan dengan user XAMPP
    public static $password = '';     // Sesuaikan dengan password XAMPP
    public static $database = 'organisasi_poltesa'; // Sesuai nama DB di laporan [cite: 301]
    
    public static function getDSN() {
        return "mysql:host=" . self::$host . ";dbname=" . self::$database . ";charset=utf8mb4";
    }
    
    public static function createDatabaseIfNotExists() {
        try {
            $tempConnection = new PDO("mysql:host=" . self::$host, self::$username, self::$password);
            $tempConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE IF NOT EXISTS " . self::$database . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            $tempConnection->exec($sql);
        } catch(PDOException $e) {
            die("Error creating database: " . $e->getMessage());
        }
    }
}
?>