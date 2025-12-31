<?php
/**
 * Database Configuration
 * DESMARÉ Financial Tracker
 * 
 * Ubah setting ini sesuai dengan server kamu
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'desmare_finance');
define('DB_USER', 'root');           // Ganti dengan username MySQL kamu
define('DB_PASS', '');               // Ganti dengan password MySQL kamu (kosong untuk XAMPP default)

// Application settings
define('APP_NAME', 'DESMARÉ Finance');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/desmare');  // Ganti sesuai URL kamu

// Session settings
define('SESSION_LIFETIME', 3600); // 1 hour

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Error reporting (set to 0 for production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Database Connection Class
 */
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}

/**
 * Helper function to get database connection
 */
function db() {
    return Database::getInstance()->getConnection();
}

/**
 * Format currency to Indonesian Rupiah
 */
function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}

/**
 * Format date to Indonesian format
 */
function formatTanggal($date) {
    if (empty($date)) return '-';
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}

/**
 * Sanitize input
 */
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Redirect helper
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Flash message helper
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
