<?php
// File: fix_organisasi_status.php
require_once 'models/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    echo "<h2>🔧 Perbaikan Database: Status Organisasi</h2>";

    // 1. Cek apakah kolom 'status_aktif' sudah ada di tabel 'organisasi'
    $check = $db->query("SHOW COLUMNS FROM organisasi LIKE 'status_aktif'");
    
    if ($check->rowCount() == 0) {
        // Jika belum ada, tambahkan kolomnya
        $sql = "ALTER TABLE organisasi ADD COLUMN status_aktif ENUM('active', 'inactive') DEFAULT 'active'";
        $db->exec($sql);
        echo "<p style='color:green'>✅ Berhasil menambahkan kolom <b>'status_aktif'</b> ke tabel <b>'organisasi'</b>.</p>";
    } else {
        echo "<p style='color:blue'>ℹ️ Kolom <b>'status_aktif'</b> sudah ada.</p>";
    }

    // 2. UPDATE semua data lama menjadi 'active'
    // Agar organisasi yang sudah ada muncul kembali
    $sql_update = "UPDATE organisasi SET status_aktif = 'active' WHERE status_aktif IS NULL OR status_aktif = ''";
    $affected = $db->exec($sql_update);
    
    echo "<p style='color:green'>✅ Berhasil mengaktifkan kembali <b>$affected</b> organisasi lama.</p>";
    
    echo "<hr><h3>🎉 Perbaikan Selesai!</h3>";
    echo "<p>Silakan kembali ke halaman utama, organisasi seharusnya sudah muncul.</p>";
    echo "<a href='index.php' style='background:blue; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Ke Beranda</a>";

} catch (Exception $e) {
    echo "<h3 style='color:red'>Error:</h3> " . $e->getMessage();
}
?>  