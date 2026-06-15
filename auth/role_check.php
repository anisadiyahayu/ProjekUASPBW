<?php
// Pastikan auth_check sudah dijalankan (user harus login)
require_once __DIR__ . '/auth_check.php';

// Hanya role Admin yang boleh mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../profil/index.php");
    exit;
}