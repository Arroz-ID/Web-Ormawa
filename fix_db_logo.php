<?php
// File: fix_db_logo.php
require_once 'models/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    echo "<h2>🔧 Perbaikan Database: Kolom Logo</h2>";

    // 1. Cek apakah kolom 'logo' sudah ada di tabel 'organisasi'
    $check = $db->query("SHOW COLUMNS FROM organisasi LIKE 'logo'");
    
    if ($check->rowCount() == 0) {
        // Jika belum ada, tambahkan kolomnya
        $sql = "ALTER TABLE organisasi ADD COLUMN logo VARCHAR(255) DEFAULT NULL AFTER tanggal_berdiri";
        $db->exec($sql);
        echo "<p style='color:green'>✅ Berhasil menambahkan kolom <b>'logo'</b> ke tabel <b>'organisasi'</b>.</p>";
    } else {
        echo "<p style='color:blue'>ℹ️ Kolom <b>'logo'</b> sudah ada. Tidak perlu perubahan.</p>";
    }

    // 2. Buat folder assets/images jika belum ada
    $targetDir = __DIR__ . "/assets/images/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
        echo "<p style='color:green'>✅ Folder <b>assets/images/</b> berhasil dibuat.</p>";
    } else {
        echo "<p style='color:blue'>ℹ️ Folder <b>assets/images/</b> sudah siap.</p>";
    }

    // 3. (Opsional) Update data dummy agar tidak error saat tampil
    // Set default logo kosong jika null
    $db->exec("UPDATE organisasi SET logo = '' WHERE logo IS NULL");
    
    echo "<hr><h3>🎉 Perbaikan Selesai!</h3>";
    echo "<p>Silakan kembali ke Edit Profil dan coba upload logo lagi.</p>";
    echo "<a href='index.php?action=ormawa_edit_profil' style='background:blue; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Kembali ke Edit Profil</a>";

} catch (Exception $e) {
    echo "<h3 style='color:red'>Error:</h3> " . $e->getMessage();
}
?>