<?php
/**
 * Debug vault notes
 */
require_once 'config/database.php';

echo "<h2>Debug Vault Notes</h2>";

// 1. Check if table exists
echo "<h3>1. Cek Tabel</h3>";
try {
    $result = db()->query("SHOW TABLES LIKE 'vault_notes'");
    if ($result->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Tabel vault_notes EXISTS</p>";
        
        // Show table structure
        $cols = db()->query("DESCRIBE vault_notes")->fetchAll();
        echo "<pre>";
        print_r($cols);
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ Tabel vault_notes TIDAK ADA - Creating now...</p>";
        
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
        echo "<p style='color: green;'>✅ Tabel berhasil dibuat!</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// 2. Test insert
echo "<h3>2. Test Insert</h3>";
try {
    $stmt = db()->prepare("INSERT INTO vault_notes (bulan, tahun, note) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE note = ?, updated_at = NOW()");
    $stmt->execute([12, 2025, 'Test note', 'Test note']);
    echo "<p style='color: green;'>✅ Insert berhasil!</p>";
    
    // Read back
    $check = db()->query("SELECT * FROM vault_notes WHERE bulan = 12 AND tahun = 2025")->fetch();
    echo "<p>Data: </p><pre>";
    print_r($check);
    echo "</pre>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>Insert Error: " . $e->getMessage() . "</p>";
}

// 3. Test AJAX simulation
echo "<h3>3. Test AJAX Endpoint</h3>";
$_POST['action'] = 'save_note';
$_POST['note_bulan'] = 12;
$_POST['note_tahun'] = 2025;
$_POST['note_text'] = 'Test dari debug script';

echo "<p>Simulating POST data:</p><pre>";
print_r($_POST);
echo "</pre>";

echo "<hr>";
echo "<p><a href='vault.php'>← Kembali ke Data Vault</a></p>";
?>
