<?php
include "../auth.php";
include "../koneksi.php";

$id = intval($_POST['id']);
mysqli_query($conn, "DELETE FROM users WHERE id=$id");

header("Location: users.php");
exit;