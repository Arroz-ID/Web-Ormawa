<?php
// File: reset_admin.php
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    // 1. Hapus Admin Lama (jika ada) agar tidak duplikat
    $conn->exec("DELETE FROM admin WHERE username = 'admin' OR email = 'admin@poltesa.ac.id'");

    // 2. Buat Password Hash Baru (admin123)
    $password = password_hash('admin123', PASSWORD_DEFAULT);

    // 3. Masukkan Admin Baru
    $sql = "INSERT INTO admin (username, password, nama_lengkap, email, level, status_aktif) 
            VALUES ('admin', :pass, 'Super Admin', 'admin@poltesa.ac.id', 'super_admin', 'active')";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([':pass' => $password]);

    echo "<h1>SUKSES!</h1>";
    echo "<p>Akun Admin berhasil di-reset.</p>";
    echo "<ul>";
    echo "<li>Email: <b>admin@poltesa.ac.id</b></li>";
    echo "<li>Password: <b>admin123</b></li>";
    echo "</ul>";
    echo "<a href='index.php?action=login_admin'>Klik disini untuk Login Admin</a>";

} catch (Exception $e) {
    echo "<h1>Gagal</h1>";
    echo "Error: " . $e->getMessage();
}
?>