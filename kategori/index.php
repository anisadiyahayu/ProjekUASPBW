<?php
include "../koneksi.php";

$query = mysqli_query($koneksi,"
SELECT
c.*,
COUNT(i.id) AS jumlah_barang
FROM categories c
LEFT JOIN items i ON c.id = i.id_kategori
GROUP BY c.id
ORDER BY c.nama ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kategori Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100">

<div class="p-8">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h1 class="text-3xl font-bold">
                Kategori Barang
            </h1>

            <p class="text-gray-500">
                Kelola kategori barang laboratorium
            </p>
        </div>

        <a href="tambah.php"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            + Tambah Kategori
        </a>

    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-gray-100">

            <tr>
                <th class="p-4 text-left">ID</th>
                <th class="p-4 text-left">Nama</th>
                <th class="p-4 text-left">Deskripsi</th>
                <th class="p-4 text-left">Jumlah Barang</th>
                <th class="p-4 text-left">Aksi</th>
            </tr>

            </thead>

            <tbody>

            <?php while($data = mysqli_fetch_assoc($query)){ ?>

            <tr class="border-t">

                <td class="p-4">
                    <?= $data['id']; ?>
                </td>

                <td class="p-4 font-medium">
                    <?= $data['nama']; ?>
                </td>

                <td class="p-4">
                    <?= $data['deskripsi']; ?>
                </td>

                <td class="p-4">

                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                        <?= $data['jumlah_barang']; ?> Barang
                    </span>

                </td>

                <td class="p-4">

                    <a href="edit.php?id=<?= $data['id']; ?>"
                       class="bg-yellow-500 text-white px-3 py-1 rounded">
                        Edit
                    </a>

                    <a href="hapus.php?id=<?= $data['id']; ?>"
                       onclick="return confirm('Yakin hapus kategori?')"
                       class="bg-red-500 text-white px-3 py-1 rounded">
                        Hapus
                    </a>

                </td>

            </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>