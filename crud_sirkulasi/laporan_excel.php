<?php

include "auth.php";
include "koneksi.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_inventaris.xls");

$query = mysqli_query($conn,"
SELECT
items.*,
categories.nama AS kategori,
locations.nama_lokasi AS lokasi

FROM items

LEFT JOIN categories
ON items.id_kategori = categories.id

LEFT JOIN locations
ON items.id_lokasi = locations.id

ORDER BY items.id DESC
");

?>

<html>
<head>
<meta charset="UTF-8">
</head>
<body>

<h2>Laporan Inventaris Laboratorium</h2>

<table border="1">

<tr>
<th>No</th>
<th>Kode Barang</th>
<th>Nama Barang</th>
<th>Kategori</th>
<th>Lokasi</th>
<th>Stok</th>
<th>Kondisi</th>
</tr>

<?php

$no=1;

while($row=mysqli_fetch_assoc($query)) :

?>

<tr>

<td><?= $no++ ?></td>

<td><?= $row['kode_barang'] ?></td>

<td><?= $row['nama_barang'] ?></td>

<td><?= $row['kategori'] ?></td>

<td><?= $row['lokasi'] ?></td>

<td><?= $row['stok'] ?></td>

<td><?= $row['kondisi'] ?></td>

</tr>

<?php endwhile; ?>

</table>

</body>
</html>