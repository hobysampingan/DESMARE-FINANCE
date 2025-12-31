<?php
/**
 * Create vault_notes table
 */
require_once 'config/database.php';

try {
    db()->exec("
        CREATE TABLE IF NOT EXISTS vault_notes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            bulan INT NOT NULL,
            tahun INT NOT NULL,
            note TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_period (bulan, tahun)
        ) ENGINE=InnoDB
    ");
    echo "<p style='color: green; font-weight: bold;'>✅ Tabel vault_notes berhasil dibuat!</p>";
    echo "<p><a href='vault.php'>← Kembali ke Data Vault</a></p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
