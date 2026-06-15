<?php

include("../koneksi.php");

$id = $_GET['id'];

$data = mysqli_query(
    $conn,
    "SELECT * FROM categories WHERE id='$id'"
);

$row = mysqli_fetch_assoc($data);

?>

<form action="update.php" method="POST">

<input type="hidden"
name="id"
value="<?= $row['id']; ?>">

Nama
<input type="text"
name="nama"
value="<?= $row['nama']; ?>">

<br><br>

Deskripsi

<textarea name="deskripsi"><?= $row['deskripsi']; ?></textarea>

<br><br>

<button type="submit">
Update
</button>

</form>