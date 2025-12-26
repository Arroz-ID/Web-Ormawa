<?php
// File: fix_final.php
// Letakkan di: C:\xampp\htdocs\ormawa\fix_final.php

// --- PERBAIKAN DI SINI ---
// Kita harus memanggil file models/Database.php agar Class Database dikenali
if (file_exists('models/Database.php')) {
    require_once 'models/Database.php';
} else {
    die("❌ Error: File models/Database.php tidak ditemukan! Pastikan file ini ada di folder models.");
}
// -------------------------

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    echo "<h2>🔄 Sinkronisasi Database & Algoritma</h2>";

    // 1. Perbaiki Password Anggota 'Andes' (NIM 3202402021)
    $pass_andes = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Cek dulu apakah user ada
    $checkMhs = $conn->query("SELECT count(*) FROM anggota WHERE nim = '3202402021'")->fetchColumn();
    
    if ($checkMhs > 0) {
        $conn->prepare("UPDATE anggota SET password = ?, status_aktif = 'active' WHERE nim = '3202402021'")->execute([$pass_andes]);
        echo "<p>✅ Password akun <b>Andes (3202402021)</b> berhasil di-reset ke: <b>admin123</b></p>";
    } else {
        echo "<p>⚠️ Akun Andes (3202402021) tidak ditemukan di database.</p>";
    }

    // 2. Perbaiki Password Admin
    $pass_admin = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Cek admin
    $checkAdmin = $conn->query("SELECT count(*) FROM admin WHERE email = 'admin@poltesa.ac.id'")->fetchColumn();
    
    if ($checkAdmin > 0) {
        $conn->prepare("UPDATE admin SET password = ?, status_aktif = 'active' WHERE email = 'admin@poltesa.ac.id'")->execute([$pass_admin]);
        echo "<p>✅ Password akun <b>Admin</b> berhasil di-reset ke: <b>admin123</b></p>";
    } else {
        // Jika admin belum ada, buatkan baru
        $sqlInsert = "INSERT INTO admin (username, password, nama_lengkap, email, level, status_aktif) 
                      VALUES ('admin', ?, 'Super Administrator', 'admin@poltesa.ac.id', 'super_admin', 'active')";
        $conn->prepare($sqlInsert)->execute([$pass_admin]);
        echo "<p>✅ Akun <b>Admin Baru</b> telah dibuat. Password: <b>admin123</b></p>";
    }

    echo "<hr><h3>Coba Login Sekarang:</h3>";
    echo "Login Anggota: <b>3202402021</b> / <b>admin123</b><br>";
    echo "Login Admin: <b>admin@poltesa.ac.id</b> / <b>admin123</b><br>";
    echo "<br><a href='index.php?action=login' style='padding:10px; background:blue; color:white; text-decoration:none;'>Ke Halaman Login</a>";

} catch (Exception $e) {
    echo "<h1>Error System</h1>";
    echo "Pesan: " . $e->getMessage();
}
?>