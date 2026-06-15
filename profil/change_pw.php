<?php
session_start();
require_once '../include/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user_id'];
    $old = $_POST['old_pass'];
    $new = $_POST['new_pass'];
    $confirm = $_POST['confirm_pass'];

    if ($new !== $confirm) {
        header("Location: index.php?msg=pass_err");
        exit;
    }

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($old === $user['password'] || password_verify($old, $user['password'])) {
        $hashed_new = password_hash($new, PASSWORD_DEFAULT);
        
        $stmtUpdate = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmtUpdate->bind_param("ss", $hashed_new, $id);
        $stmtUpdate->execute();

        header("Location: index.php?msg=pass_success");
        exit;
    } else {
        header("Location: index.php?msg=pass_err");
        exit;
    }
}
?>