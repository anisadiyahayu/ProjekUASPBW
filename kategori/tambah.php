<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kategori</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100">

<div class="max-w-xl mx-auto mt-10">

<div class="bg-white rounded-xl shadow p-6">

<h2 class="text-2xl font-bold mb-6">
    Tambah Kategori
</h2>

<form action="simpan.php" method="POST">

    <div class="mb-4">
        <label>ID Kategori</label>

        <input type="text"
               name="id"
               placeholder="CAT007"
               class="w-full border p-2 rounded-lg"
               required>
    </div>

    <div class="mb-4">
        <label>Nama Kategori</label>

        <input type="text"
               name="nama"
               class="w-full border p-2 rounded-lg"
               required>
    </div>

    <div class="mb-4">
        <label>Deskripsi</label>

        <textarea
            name="deskripsi"
            class="w-full border p-2 rounded-lg"></textarea>
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
        Simpan
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