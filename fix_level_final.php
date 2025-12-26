<?php
require_once 'models/Database.php';

try {
    $db = Database::getInstance()->getConnection();

    echo "<h3>🔧 Perbaikan Data Level Admin & Log</h3>";

    // 1. Perbaiki Level di Tabel Admin (Force to 'admin_ormawa')
    // Ubah semua yang bukan 'super_admin' menjadi 'admin_ormawa'
    $sql = "UPDATE admin SET level = 'admin_ormawa' WHERE level != 'super_admin' OR level IS NULL OR level = ''";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    echo "✅ Level Admin Organisasi diperbaiki menjadi 'admin_ormawa'.<br>";

    // 2. Cek Struktur Tabel log_aktivitas
    // Pastikan kolom role menerima 'admin_ormawa'
    $sqlAlter = "ALTER TABLE log_aktivitas MODIFY COLUMN role ENUM('super_admin','admin_ormawa','anggota','admin') NOT NULL";
    $db->exec($sqlAlter);
    echo "✅ Struktur Tabel Log diperbarui (Support 'admin_ormawa').<br>";

    echo "<hr><h3>🎉 Selesai! Silakan Logout dan Login Ulang.</h3>";
    echo "<a href='index.php?action=logout' style='background:red; color:white; padding:10px; text-decoration:none;'>Logout Sekarang</a>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>