-- ============================================
-- DATABASE: DESMARE FINANCIAL TRACKER
-- Created: 2024
-- Description: Sistem Rekapan Keuangan Internal DESMARÉ
-- ============================================

-- Create database
CREATE DATABASE IF NOT EXISTS desmare_finance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE desmare_finance;

-- ============================================
-- TABLE: users (Login System)
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin user (password: admin123)
INSERT INTO users (username, password, nama_lengkap, role) VALUES 
('admin', '$2y$10$AgflBHFNtB393XHch9G8IO2qLGI4TP.xi4OgMyvLM0oED9e3zM/gW', 'Administrator', 'admin');

-- ============================================
-- TABLE: toko (Master Data Toko)
-- ============================================
CREATE TABLE IF NOT EXISTS toko (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_toko VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    aktif TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert default toko
INSERT INTO toko (nama_toko, deskripsi) VALUES 
('DESMARE', 'Toko Utama DESMARÉ'),
('BEYA', 'Partner/Affiliate BEYA');

-- ============================================
-- TABLE: data_penarikan (Sheet 1: Data Penarikan)
-- ============================================
CREATE TABLE IF NOT EXISTS data_penarikan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    toko_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL DEFAULT 0,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (toko_id) REFERENCES toko(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================
-- TABLE: komisi (Sheet 2: Percentage/Komisi)
-- ============================================
CREATE TABLE IF NOT EXISTS komisi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    toko_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL DEFAULT 0,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (toko_id) REFERENCES toko(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================
-- TABLE: uang_masuk (Sheet 3: Uang Masuk dari Komisi)
-- ============================================
CREATE TABLE IF NOT EXISTS uang_masuk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    deskripsi VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL DEFAULT 0,
    info VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLE: uang_keluar (Sheet 4: Uang Keluar/Potongan)
-- ============================================
CREATE TABLE IF NOT EXISTS uang_keluar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    detail VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL DEFAULT 0,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLE: data_vault (Upload File Storage)
-- ============================================
CREATE TABLE IF NOT EXISTS data_vault (
    id INT AUTO_INCREMENT PRIMARY KEY,
    toko VARCHAR(100) NOT NULL,
    bulan INT NOT NULL,
    tahun INT NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    filepath VARCHAR(500) NOT NULL,
    filesize INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_period (tahun, bulan),
    INDEX idx_toko (toko)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: vault_notes (Notes per month in Data Vault)
-- ============================================
CREATE TABLE IF NOT EXISTS vault_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bulan INT NOT NULL,
    tahun INT NOT NULL,
    note TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_period (bulan, tahun)
) ENGINE=InnoDB;

-- ============================================
-- VIEWS: Summary calculations
-- ============================================

-- View untuk total penarikan
CREATE OR REPLACE VIEW v_total_penarikan AS
SELECT COALESCE(SUM(jumlah), 0) as total FROM data_penarikan;

-- View untuk total komisi
CREATE OR REPLACE VIEW v_total_komisi AS
SELECT COALESCE(SUM(jumlah), 0) as total FROM komisi;

-- View untuk total uang masuk
CREATE OR REPLACE VIEW v_total_uang_masuk AS
SELECT COALESCE(SUM(jumlah), 0) as total FROM uang_masuk;

-- View untuk total uang keluar
CREATE OR REPLACE VIEW v_total_uang_keluar AS
SELECT COALESCE(SUM(jumlah), 0) as total FROM uang_keluar;

-- ============================================
-- SAMPLE DATA (Optional - dari Excel existing)
-- ============================================

-- Sample Data Penarikan
-- INSERT INTO data_penarikan (toko_id, tanggal, jumlah, keterangan) VALUES
-- (1, '2025-09-01', 27300613, 'September'),
-- (2, '2025-09-01', 7122210, 'September'),
-- (1, '2025-10-28', 8935398, 'October'),
-- (2, '2025-10-23', 10894503, 'October');

-- Sample Komisi
-- INSERT INTO komisi (toko_id, tanggal, jumlah, keterangan) VALUES
-- (1, '2025-10-28', 8935398, 'Komisi Oktober'),
-- (2, '2025-10-23', 10894503, 'Komisi Oktober');

-- Sample Uang Masuk
-- INSERT INTO uang_masuk (deskripsi, tanggal, jumlah, info) VALUES
-- ('TF PERTAMA', '2025-09-01', 10000000, 'manual'),
-- ('TARIKAN TIKTOK', '2025-04-10', 4322734, 'beya'),
-- ('PRIBADI', '2025-09-01', 13000000, 'AGUNG');

-- Sample Uang Keluar
-- INSERT INTO uang_keluar (detail, tanggal, jumlah, keterangan) VALUES
-- ('Biaya Operasional', '2025-10-01', 1000000, NULL),
-- ('Biaya Operasional', '2025-11-01', 1000000, NULL);
