<?php
session_start();
require_once '../include/koneksi.php';

$id = $_GET['id'] ?? '';
if ($id && $id !== $_SESSION['user_id']) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
}
header("Location: index.php");
exit;
?>