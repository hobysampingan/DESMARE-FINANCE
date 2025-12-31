<?php
require_once __DIR__ . '/../config/database.php';

// Check login
if (!isLoggedIn() && basename($_SERVER['PHP_SELF']) !== 'index.php') {
    redirect('index.php');
}

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? APP_NAME ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php if (isLoggedIn()): ?>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">DESMAR<span>É</span></div>
            <small style="color: var(--gray-400);">Finance Tracker</small>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="dashboard.php" class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="penarikan.php" class="nav-link <?= $currentPage === 'penarikan' ? 'active' : '' ?>">
                    <i class="bi bi-cash-stack"></i>
                    <span>Data Penarikan</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="komisi.php" class="nav-link <?= $currentPage === 'komisi' ? 'active' : '' ?>">
                    <i class="bi bi-percent"></i>
                    <span>Komisi</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="uang_masuk.php" class="nav-link <?= $currentPage === 'uang_masuk' ? 'active' : '' ?>">
                    <i class="bi bi-arrow-down-circle-fill"></i>
                    <span>Uang Masuk</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="uang_keluar.php" class="nav-link <?= $currentPage === 'uang_keluar' ? 'active' : '' ?>">
                    <i class="bi bi-arrow-up-circle-fill"></i>
                    <span>Uang Keluar</span>
                </a>
            </div>
            <div class="nav-item" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <a href="toko.php" class="nav-link <?= $currentPage === 'toko' ? 'active' : '' ?>">
                    <i class="bi bi-shop"></i>
                    <span>Master Toko</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="import.php" class="nav-link <?= $currentPage === 'import' ? 'active' : '' ?>">
                    <i class="bi bi-upload"></i>
                    <span>Import Data</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="vault.php" class="nav-link <?= $currentPage === 'vault' ? 'active' : '' ?>">
                    <i class="bi bi-archive-fill"></i>
                    <span>Data Vault</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="export.php" class="nav-link <?= $currentPage === 'export' ? 'active' : '' ?>">
                    <i class="bi bi-download"></i>
                    <span>Export Data</span>
                </a>
            </div>
            <div class="nav-item" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <a href="backup.php" class="nav-link <?= $currentPage === 'backup' ? 'active' : '' ?>">
                    <i class="bi bi-shield-check"></i>
                    <span>Backup & Restore</span>
                </a>
            </div>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <?= strtoupper(substr($_SESSION['nama_lengkap'] ?? 'U', 0, 1)) ?>
                </div>
                <div>
                    <div style="color: var(--white); font-weight: 500;"><?= $_SESSION['nama_lengkap'] ?? 'User' ?></div>
                    <a href="logout.php" style="font-size: 0.75rem; color: var(--gray-400);">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="d-flex align-center gap-2">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title"><?= $pageTitle ?? 'Dashboard' ?></h1>
            </div>
            <div class="breadcrumb">
                <i class="bi bi-house"></i>
                <span>/</span>
                <span><?= $pageTitle ?? 'Dashboard' ?></span>
            </div>
        </div>
        
        <!-- Flash Messages -->
        <?php if ($flash = getFlash()): ?>
        <div class="alert alert-<?= $flash['type'] ?>">
            <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : ($flash['type'] === 'danger' ? 'exclamation-circle' : 'info-circle') ?>"></i>
            <?= $flash['message'] ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
