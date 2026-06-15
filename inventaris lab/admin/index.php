<?php
session_start();
include "../include/header.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

header("Location: data_barang.php");
exit;
?>