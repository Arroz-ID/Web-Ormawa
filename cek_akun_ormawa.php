<?php
require_once 'models/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Cari akun 'bem'
    $stmt = $db->prepare("SELECT * FROM admin WHERE username = 'bem'");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3>🔍 Cek Akun Admin BEM</h3>";
    if ($user) {
        echo "✅ Akun Ditemukan!<br>";
        echo "Username: " . $user['username'] . "<br>";
        echo "Hash Password di DB: " . substr($user['password'], 0, 10) . "...<br>";
        
        // Cek Password
        if (password_verify('admin123', $user['password'])) {
            echo "✅ Password 'admin123' COCOK!";
        } else {
            echo "❌ Password 'admin123' TIDAK COCOK. Hash salah.";
        }
    } else {
        echo "❌ Akun 'bem' tidak ditemukan di database.";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>