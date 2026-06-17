<?php

include "../auth.php";
include "../koneksi.php";

$id = $_SESSION['id'];

$lama = $_POST['password_lama'];
$baru = $_POST['password_baru'];
$konfirmasi = $_POST['konfirmasi'];

$user = mysqli_fetch_assoc(
mysqli_query($conn,"SELECT * FROM users WHERE id='$id'")
);

$password_lama_cocok = false;

if (password_verify($lama, $user['password'])) {
    $password_lama_cocok = true;
}

if ($lama == $user['password']) {
    $password_lama_cocok = true;
}

if ($password_lama_cocok == false) {
echo "<script>alert('Password lama salah');
history.back();
</script>";
exit;
}

if ($baru != $konfirmasi) {
echo "<script>
alert('Konfirmasi password tidak cocok');
history.back();
</script>";
exit;

}

$password = password_hash($baru,PASSWORD_DEFAULT);

mysqli_query($conn,"
UPDATE users
SET password='$password'
WHERE id='$id'
");

echo "<script>
alert('Password berhasil diubah');
location='profil_admin.php';
</script>";