
<?php
require_once __DIR__ . '/auth_check.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Aslab')) {
    header("Location: ../profil/index.php");
    exit;
}

?>
