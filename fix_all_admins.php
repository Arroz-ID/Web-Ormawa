<?php
// File: fix_all_admins.php
require_once 'models/Database.php';

try {
    $db = Database::getInstance()->getConnection();

    echo "<h2>🔧 Perbaikan Akun Admin Menyeluruh</h2>";

    // 1. Buat Hash Password 'admin123'
    $password_baru = password_hash('admin123', PASSWORD_DEFAULT);

    // 2. Update Password SEMUA Admin (Tanpa Kecuali)
    $sql_pass = "UPDATE admin SET password = :pass, status_aktif = 'active'";
    $stmt = $db->prepare($sql_pass);
    $stmt->execute([':pass' => $password_baru]);
    
    echo "<p>✅ <b>Password</b> semua admin berhasil di-reset menjadi: <code>admin123</code></p>";

    // 3. Perbaiki Level Admin Organisasi (yang bukan super_admin)
    // Ubah yang levelnya kosong atau salah menjadi 'admin_ormawa'
    $sql_level = "UPDATE admin SET level = 'admin_ormawa' WHERE username != 'admin' AND level = ''";
    $count = $db->exec($sql_level);
    
    echo "<p>✅ <b>Level Admin</b> diperbaiki. ($count akun di-update menjadi 'admin_ormawa')</p>";

    // 4. Tampilkan Tabel Hasil
    echo "<hr><h3>📋 Coba Login dengan Akun Ini:</h3>";
    echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse:collapse; width:100%;'>";
    echo "<tr style='background:#eee; text-align:left;'>
            <th>Username</th>
            <th>Email</th>
            <th>Password</th>
            <th>Level</th>
            <th>Status</th>
          </tr>";

    $admins = $db->query("SELECT * FROM admin ORDER BY admin_id ASC");
    foreach ($admins as $a) {
        $bg = ($a['level'] == 'super_admin') ? '#d1e7dd' : '#fff'; // Hijau muda untuk super admin
        echo "<tr style='background:$bg'>";
        echo "<td><b>" . htmlspecialchars($a['username']) . "</b></td>";
        echo "<td>" . htmlspecialchars($a['email']) . "</td>";
        echo "<td>admin123</td>";
        echo "<td>" . htmlspecialchars($a['level']) . "</td>";
        echo "<td>" . htmlspecialchars($a['status_aktif']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<br><br>";
    echo "<a href='index.php?action=login' style='background:#0d6efd; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>➡️ Ke Halaman Login</a>";

} catch (Exception $e) {
    echo "<h3 style='color:red'>Error:</h3> " . $e->getMessage();
}
?>