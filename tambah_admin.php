<?php
// File: tambah_admin.php
// Script untuk membuat akun Super Admin secara manual

// PERBAIKAN: Mengambil file dari folder models
if (file_exists('models/Database.php')) {
    require_once 'models/Database.php';
} elseif (file_exists('config/Database.php')) {
    require_once 'config/Database.php';
} else {
    die("<h1>Error Fatal</h1><p>File Database.php tidak ditemukan di folder 'models/' maupun 'config/'. Pastikan file Database.php ada.</p>");
}

try {
    // 1. Koneksi ke Database
    $db = Database::getInstance();
    $conn = $db->getConnection();

    // 2. Data Super Admin
    $username = 'admin';
    $email    = 'admin@poltesa.ac.id';
    $password = 'admin123'; // Password mentah
    $nama     = 'Super Admin Utama';

    // 3. Enkripsi Password (WAJIB)
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 4. Hapus data lama jika ada (agar tidak error duplicate entry)
    // Kita gunakan try-catch kecil disini kalau tabel belum ada
    try {
        $conn->exec("DELETE FROM admin WHERE email = '$email'");
    } catch (Exception $e) {
        // Abaikan error jika tabel belum ada (nanti dibuat insert)
    }

    // 5. Masukkan ke Database
    $sql = "INSERT INTO admin (username, password, nama_lengkap, email, level, status_aktif) 
            VALUES (:username, :password, :nama, :email, 'super_admin', 'active')";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':password' => $password_hash,
        ':nama'     => $nama,
        ':email'    => $email
    ]);

    echo "<div style='font-family: Arial; text-align: center; margin-top: 50px;'>";
    echo "<h1 style='color: green;'>BERHASIL!</h1>";
    echo "<p>Akun Super Admin telah dibuat.</p>";
    echo "<div style='background: #f0f0f0; display: inline-block; padding: 20px; border-radius: 10px; text-align: left;'>";
    echo "Email: <b>$email</b><br>";
    echo "Password: <b>$password</b>";
    echo "</div><br><br>";
    echo "<a href='index.php?action=login_admin' style='background: blue; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Login Admin Sekarang</a>";
    echo "</div>";

} catch (Exception $e) {
    echo "<h1>Gagal</h1>";
    echo "Error: " . $e->getMessage();
}
?>