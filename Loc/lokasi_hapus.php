<?php
include "auth.php";
include "koneksi.php";

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    mysqli_query($conn, "DELETE FROM locations WHERE id='$id'");
}

header("Location: lokasi.php");
exit;