<?php
include "../auth.php";
include "../koneksi.php";

$nama     = $_POST['nama'];
$npm      = $_POST['npm'];
$email    = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role     = $_POST['role'];
$kelas    = $_POST['kelas'];
$angkatan = $_POST['angkatan'];
$no_hp    = $_POST['no_hp'];

mysqli_query($conn, "INSERT INTO users (nama, npm, email, password, role, kelas, angkatan, no_hp) VALUES ('$nama', '$npm', '$email', '$password', '$role', '$kelas', '$angkatan', '$no_hp')");

header("Location: users.php");
exit;