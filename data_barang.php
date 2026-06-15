<?php

session_start();

include '../koneksi.php';
include 'fungsi_stok.php';

$kategori = $_GET['kategori'] ?? '';
$lokasi   = $_GET['lokasi'] ?? '';

$where = "";

if($kategori != ''){
    $where .= " AND items.id_kategori='$kategori'";
}

if($lokasi != ''){
    $where .= " AND items.id_lokasi='$lokasi'";
}

$query = mysqli_query($conn,"
SELECT
items.*,
categories.nama AS kategori,
locations.nama AS lokasi

FROM items

LEFT JOIN categories
ON items.id_kategori = categories.id

LEFT JOIN locations
ON items.id_lokasi = locations.id

WHERE 1=1
$where

ORDER BY items.nama ASC
");
?>

<h2>Data Barang</h2>

<?php if($_SESSION['role'] == 'admin'){ ?>

<a href="tambah_barang.php">
Tambah Barang
</a>

<?php } ?>

<table border="1">

<tr>

<th>Kode</th>
<th>Nama</th>
<th>Kategori</th>
<th>Lokasi</th>
<th>Stok</th>
<th>Status</th>
<th>Aksi</th>

</tr>

<?php while($row=mysqli_fetch_assoc($query)){ ?>

<tr>

<td><?= $row['kode'] ?></td>
<td><?= $row['nama'] ?></td>
<td><?= $row['kategori'] ?></td>
<td><?= $row['lokasi'] ?></td>
<td><?= $row['stok'] ?></td>

<td>
<?= statusStok(
$row['stok'],
$row['stok_minimum']
) ?>
</td>

<td>

<a href="detail_barang.php?id=<?= $row['id'] ?>">
Detail
</a>

<?php if($_SESSION['role']=='admin'){ ?>

<a href="edit_barang.php?id=<?= $row['id'] ?>">
Edit
</a>

<a
href="hapus_barang.php?id=<?= $row['id'] ?>"
onclick="return confirm('Hapus data?')">
Hapus
</a>

<?php } ?>

</td>

</tr>

<?php } ?>

</table>