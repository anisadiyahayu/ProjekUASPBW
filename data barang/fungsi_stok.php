<?php

include '../koneksi.php';

$query = mysqli_query($conn,"

SELECT
items.*,
categories.nama AS kategori,
locations.nama AS lokasi

FROM items

LEFT JOIN categories
ON items.id_kategori=categories.id

LEFT JOIN locations
ON items.id_lokasi=locations.id

");

while($row=mysqli_fetch_assoc($query)){

echo $row['kode'];
echo $row['nama'];
echo $row['kategori'];
echo $row['lokasi'];
echo $row['stok'];

echo statusStok(
$row['stok'],
$row['stok_minimum']
);

}
?>