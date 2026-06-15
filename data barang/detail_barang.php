<?php

session_start();

include '../koneksi.php';
include 'fungsi_stok.php';

$id = $_GET['id'];

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

WHERE items.id='$id'

");

$data = mysqli_fetch_assoc($query);

?>

<h2><?= $data['nama'] ?></h2>

<img
src="../assets/uploads/<?= $data['foto_barang'] ?>"
width="200">

<p>Kode : <?= $data['kode'] ?></p>

<p>Kategori : <?= $data['kategori'] ?></p>

<p>Lokasi : <?= $data['lokasi'] ?></p>

<p>Stok : <?= $data['stok'] ?></p>

<p>Status :
<?= statusStok(
$data['stok'],
$data['stok_minimum']
); ?>
</p>

<p>Kondisi : <?= $data['kondisi'] ?></p>

<p>Keterangan : <?= $data['keterangan'] ?></p>

<h3>Riwayat Permintaan</h3>

<?php
$riwayat = mysqli_query($conn,"
    SELECT transactions.*, users.nama AS nama_user 
    FROM transactions
    LEFT JOIN users ON transactions.id_user = users.id
    WHERE transactions.id_item='$id'
    ORDER BY transactions.waktu_pinjam DESC
");

while($r=mysqli_fetch_assoc($riwayat)){
    echo "<p>
        Pemohon: $r[nama_user] | 
        Jumlah: $r[jumlah] | 
        Status: $r[status] | 
        Tanggal: $r[waktu_pinjam]
    </p>";
}
?>