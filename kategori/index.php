<?php
include("../koneksi.php");

$data = mysqli_query(
    $koneksi,
    "SELECT * FROM categories"
);
?>

<a href="tambah.php">
    Tambah Kategori
</a>


<table border="1">

<tr>
    <th>ID</th>
    <th>Nama</th>
    <th>Deskripsi</th>
    <th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)) { ?>

<tr>
    <td><?= $row['id']; ?></td>
    <td><?= $row['nama']; ?></td>
    <td><?= $row['deskripsi']; ?></td>

    <td>
        <a href="edit.php?id=<?= $row['id']; ?>">Edit</a>
        <a href="hapus.php?id=<?= $row['id']; ?>">Hapus</a>
    </td>
</tr>

<?php } ?>

</table>