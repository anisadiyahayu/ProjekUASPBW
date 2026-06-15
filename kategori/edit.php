<?php

include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM categories WHERE id='$id'"
);

$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Kategori</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100">

<div class="max-w-xl mx-auto mt-10">

<div class="bg-white rounded-xl shadow p-6">

<h2 class="text-2xl font-bold mb-6">
    Edit Kategori
</h2>

<form action="update.php" method="POST">

<input type="hidden"
       name="id"
       value="<?= $data['id']; ?>">

<div class="mb-4">

<label>Nama Kategori</label>

<input type="text"
       name="nama"
       value="<?= $data['nama']; ?>"
       class="w-full border p-2 rounded-lg">

</div>

<div class="mb-4">

<label>Deskripsi</label>

<textarea
name="deskripsi"
class="w-full border p-2 rounded-lg"><?= $data['deskripsi']; ?></textarea>

</div>

<button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
    Update
</button>

<a href="index.php"
   class="bg-gray-500 text-white px-4 py-2 rounded-lg">
    Kembali
</a>

</form>

</div>
</div>

</body>
</html>