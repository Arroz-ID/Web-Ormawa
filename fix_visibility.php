<?php
// File: fix_visibility.php
require_once 'models/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    echo "<h2>🔧 Perbaikan Database: Status Organisasi</h2>";

    // 1. Pastikan kolom ada & tipenya aman (VARCHAR)
    try {
        // Coba tambah kolom jika belum ada
        $db->exec("ALTER TABLE organisasi ADD COLUMN status_aktif VARCHAR(20) DEFAULT 'active'");
        echo "<p>✅ Kolom 'status_aktif' berhasil ditambahkan.</p>";
    } catch (Exception $e) {
        echo "<p>ℹ️ Kolom 'status_aktif' sudah ada.</p>";
    }

    // 2. FORCE UPDATE: Setel semua data lama menjadi 'active'
    $sql = "UPDATE organisasi SET status_aktif = 'active' WHERE status_aktif IS NULL OR status_aktif = ''";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $count = $stmt->rowCount();

    echo "<p>✅ Berhasil mengaktifkan kembali <b>$count</b> organisasi lama.</p>";
    
    echo "<hr><h3>🎉 Selesai!</h3>";
    echo "<p>Organisasi Anda seharusnya sudah muncul kembali.</p>";
    echo "<a href='index.php' style='background:blue; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Ke Beranda</a>";

} catch (Exception $e) {
    echo "<h3 style='color:red'>Error:</h3> " . $e->getMessage();
}
?>