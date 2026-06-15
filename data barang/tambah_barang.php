<?php

session_start();

if($_SESSION['role'] != 'admin'){
    die("Akses ditolak");
}

include '../koneksi.php';

?>

<form action="proses_tambah_barang.php"
method="POST"
enctype="multipart/form-data">

<input type="text" name="kode" required>
<input type="text" name="nama" required>

<select name="id_kategori" required>
    <option value="">-- Pilih Kategori --</option>
    <?php
    $kat = mysqli_query($conn, "SELECT * FROM categories");
    while($k = mysqli_fetch_assoc($kat)) {
        echo "<option value='$k[id]'>$k[nama]</option>";
    }
    ?>
</select>

<select name="id_lokasi" required>
    <option value="">-- Pilih Lokasi --</option>
    <?php
    $lok = mysqli_query($conn, "SELECT * FROM locations");
    while($l = mysqli_fetch_assoc($lok)) {
        echo "<option value='$l[id]'>$l[nama]</option>";
    }
    ?>
</select>

<input type="number" name="stok" required>

<input type="text" name="satuan" required>

<select name="kondisi">
    <option value="Bagus">Bagus</option>
    <option value="Rusak">Rusak</option>
</select>

<input type="number"
name="stok_minimum"
required>

<input type="file"
name="foto"
required>

<textarea
name="keterangan">
</textarea>

<button type="submit">
Simpan
</button>

</form>