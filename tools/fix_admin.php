<?php
/**
 * Fix Admin Password Script
 * Jalankan sekali untuk reset password admin ke admin123
 */

require_once 'config/database.php';

echo "<h2>Fix Admin Password</h2>";

// Check current user
$stmt = db()->query("SELECT id, username, password, nama_lengkap FROM users WHERE username = 'admin'");
$user = $stmt->fetch();

if ($user) {
    echo "<p><strong>User ditemukan:</strong></p>";
    echo "<ul>";
    echo "<li>ID: " . $user['id'] . "</li>";
    echo "<li>Username: " . $user['username'] . "</li>";
    echo "<li>Nama: " . $user['nama_lengkap'] . "</li>";
    echo "<li>Hash saat ini: <code>" . $user['password'] . "</code></li>";
    echo "</ul>";
    
    // Test current password
    $testPassword = 'admin123';
    $isValid = password_verify($testPassword, $user['password']);
    echo "<p><strong>Test password 'admin123':</strong> " . ($isValid ? "✅ VALID" : "❌ TIDAK VALID") . "</p>";
    
    if (!$isValid) {
        // Generate new hash and update
        $newHash = password_hash('admin123', PASSWORD_DEFAULT);
        
        $updateStmt = db()->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
        $updateStmt->execute([$newHash]);
        
        echo "<p style='color: green; font-weight: bold;'>✅ Password berhasil di-reset ke 'admin123'!</p>";
        echo "<p>Hash baru: <code>" . $newHash . "</code></p>";
        
        // Verify again
        $stmt2 = db()->query("SELECT password FROM users WHERE username = 'admin'");
        $user2 = $stmt2->fetch();
        $isValidNow = password_verify('admin123', $user2['password']);
        echo "<p><strong>Verifikasi ulang:</strong> " . ($isValidNow ? "✅ SUKSES" : "❌ GAGAL") . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ User 'admin' tidak ditemukan!</p>";
    
    // Create admin user
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = db()->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES ('admin', ?, 'Administrator', 'admin')");
    $stmt->execute([$hash]);
    
    echo "<p style='color: green;'>✅ User admin berhasil dibuat dengan password 'admin123'</p>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Kembali ke Login</a></p>";
?>
