<?php
// File: debug_admin.php
require_once 'config/database.php';

echo "<h3>🔍 DIAGNOSA LOGIN ADMIN</h3>";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $email_target = 'admin@poltesa.ac.id';
    $password_baru = 'admin123';

    // 1. CEK APAKAH EMAIL ADA DI TABEL ADMIN
    echo "1. Mencari email <b>$email_target</b> di tabel <b>admin</b>...<br>";
    
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = :email");
    $stmt->execute([':email' => $email_target]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        echo "✅ Akun DITEMUKAN. (ID: " . $admin['admin_id'] . ")<br>";
        
        // 2. CEK STATUS AKTIF
        echo "2. Mengecek status akun... ";
        if ($admin['status_aktif'] == 'active') {
            echo "✅ Status OK (active).<br>";
        } else {
            echo "❌ Status saat ini: " . $admin['status_aktif'] . ". <br>🔄 Memperbaiki status menjadi active...<br>";
            $conn->exec("UPDATE admin SET status_aktif = 'active' WHERE email = '$email_target'");
        }

        // 3. RESET PASSWORD (WAJIB)
        echo "3. Me-reset password menjadi '<b>$password_baru</b>'...<br>";
        $new_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        
        $update = $conn->prepare("UPDATE admin SET password = :pass WHERE email = :email");
        $update->execute([
            ':pass' => $new_hash,
            ':email' => $email_target
        ]);
        echo "✅ Password berhasil di-update dengan hash baru.<br>";

    } else {
        echo "❌ Akun TIDAK DITEMUKAN.<br>";
        echo "🔄 Membuat akun Admin baru...<br>";
        
        $new_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $sqlInsert = "INSERT INTO admin (username, password, nama_lengkap, email, level, status_aktif) 
                      VALUES ('admin', :pass, 'Super Administrator', :email, 'super_admin', 'active')";
        
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->execute([
            ':pass' => $new_hash,
            ':email' => $email_target
        ]);
        echo "✅ Akun Admin Baru Berhasil Dibuat.<br>";
    }

    echo "<hr>";
    echo "<h2 style='color:green'>PERBAIKAN SELESAI</h2>";
    echo "<p>Silakan coba login kembali dengan data:</p>";
    echo "<ul>
            <li>Email: <b>$email_target</b></li>
            <li>Password: <b>$password_baru</b></li>
          </ul>";
    echo "<a href='index.php?action=login' style='background:blue; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Login Sekarang</a>";

} catch (Exception $e) {
    echo "<h1 style='color:red'>ERROR SYSTEM</h1>";
    echo $e->getMessage();
}
?>