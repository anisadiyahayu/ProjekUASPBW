<?php
include "../auth.php";
include "../koneksi.php";

$id       = $_POST['id'];
$nama     = $_POST['nama'];
$npm      = $_POST['npm'];
$email    = $_POST['email'];
$role     = $_POST['role'];
$kelas    = $_POST['kelas'];
$angkatan = $_POST['angkatan'];
$no_hp    = $_POST['no_hp'];

if (!empty($_POST['password'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE users SET nama='$nama', npm='$npm', email='$email', password='$password', role='$role', kelas='$kelas', angkatan='$angkatan', no_hp='$no_hp' WHERE id='$id'");
} else {
    mysqli_query($conn, "UPDATE users SET nama='$nama', npm='$npm', email='$email', role='$role', kelas='$kelas', angkatan='$angkatan', no_hp='$no_hp' WHERE id='$id'");
}

header("Location: users.php");
exit;