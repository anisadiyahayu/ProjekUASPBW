<?php
session_start();
if($_SESSION['role'] != 'admin'){ die("Akses ditolak"); }
include '../koneksi.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM items WHERE id='$id'"));
?>

<h2>Edit Barang: <?= $data['nama'] ?></h2>
<form action="proses_edit_barang.php?id=<?= $data['id'] ?>" method="POST">
    <input type="text" name="kode" value="<?= $data['kode'] ?>" required>
    <input type="text" name="nama" value="<?= $data['nama'] ?>" required>
    
    <select name="id_kategori" required>
        <?php
        $kat = mysqli_query($conn, "SELECT * FROM categories");
        while($k = mysqli_fetch_assoc($kat)) {
            $selected = ($k['id'] == $data['id_kategori']) ? 'selected' : '';
            echo "<option value='$k[id]' $selected>$k[nama]</option>";
        }
        ?>
    </select>

    <select name="id_lokasi" required>
        <?php
        $lok = mysqli_query($conn, "SELECT * FROM locations");
        while($l = mysqli_fetch_assoc($lok)) {
            $selected = ($l['id'] == $data['id_lokasi']) ? 'selected' : '';
            echo "<option value='$l[id]' $selected>$l[nama]</option>";
        }
        ?>
    </select>

    <input type="number" name="stok" value="<?= $data['stok'] ?>" required>
    <input type="text" name="satuan" value="<?= $data['satuan'] ?>" required>
    
    <select name="kondisi">
        <option value="Bagus" <?= $data['kondisi'] == 'Bagus' ? 'selected' : '' ?>>Bagus</option>
        <option value="Rusak" <?= $data['kondisi'] == 'Rusak' ? 'selected' : '' ?>>Rusak</option>
    </select>

    <input type="number" name="stok_minimum" value="<?= $data['stok_minimum'] ?>" required>
    <textarea name="keterangan"><?= $data['keterangan'] ?></textarea>
    
    <button type="submit">Update</button>
</form>