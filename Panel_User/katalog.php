<?php

include "auth.php";
include "../include/koneksi.php";

$nama = $_SESSION['nama'];

$search = $_GET['search'] ?? '';

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
WHERE items.nama LIKE '%$search%'
ORDER BY items.id DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Katalog Barang</title>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    font-family:'Poppins',sans-serif;
}

body{
    background:#f1f5f9;
}

.card{
    background:white;
    border:1px solid #e5e7eb;
    border-radius:12px;
    box-shadow:0 1px 3px rgba(0,0,0,.08);
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<aside
class="fixed left-0 top-0 w-[220px] h-screen bg-[#1E3A8A] text-white"
>

    <div class="h-full flex flex-col">

        <div class="p-4 border-b border-blue-800">

            <div class="flex items-center gap-2">

                <div
                class="w-10 h-10 rounded-lg bg-[#3B82F6] flex items-center justify-center"
                >

                    <i
                    data-lucide="box"
                    class="w-5 h-5"
                    ></i>

                </div>

                <div>

                    <h1 class="font-semibold text-base">
                        Lab Inventory
                    </h1>

                    <p class="text-[11px] text-blue-200">
                        Student Portal
                    </p>

                </div>

            </div>

        </div>

        <nav class="flex-1 py-3">

            <ul class="space-y-2 px-3">

                <li>

                    <a
                    href="dashboard_mahasiswa.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"
                    >

                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>

                        Dashboard

                    </a>

                </li>

                <li>

                    <a
                    href="katalog.php"
                    class="bg-[#3B82F6] flex items-center gap-3 px-4 py-3 rounded-lg text-sm"
                    >

                        <i data-lucide="package" class="w-4 h-4"></i>

                        Katalog Barang

                    </a>

                </li>

                <li>

                    <a
                    href="peminjaman.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"
                    >

                        <i data-lucide="clipboard-list" class="w-4 h-4"></i>

                        Peminjaman Barang

                    </a>

                </li>

                <li>

                    <a
                    href="pengembalian.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"
                    >

                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>

                        Pengembalian Barang

                    </a>

                </li>

                <li>

                    <a
                    href="riwayat.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"
                    >

                        <i data-lucide="history" class="w-4 h-4"></i>

                        Riwayat Peminjaman

                    </a>

                </li>

                <li>

                    <a
                    href="profil.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"
                    >

                        <i data-lucide="user" class="w-4 h-4"></i>

                        Profil Saya

                    </a>

                </li>

            </ul>

        </nav>

        <div class="p-4 border-t border-blue-800">

            <a
            href="logout.php"
            class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91] transition-all duration-300"
            >

                <i
                data-lucide="log-out"
                class="w-5 h-5 text-white group-hover:text-red-500"
                ></i>

                <span class="font-medium text-white group-hover:text-red-500">
                    Logout
                </span>

            </a>

        </div>

    </div>

</aside>

<!-- CONTENT -->

<div class="ml-[220px]">

<header
class="bg-white border-b h-[52px] px-6 flex justify-between items-center"
>

    <h1 class="font-semibold text-sm">
        Katalog Barang
    </h1>

    <div class="flex items-center gap-3">

        <div class="text-right">

            <h4 class="text-sm font-semibold">
                <?= $nama ?>
            </h4>

            <p class="text-xs text-slate-500">
                Mahasiswa
            </p>

        </div>

        <div
        class="w-9 h-9 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center text-sm font-semibold"
        >
            <?= strtoupper(substr($nama,0,2)); ?>
        </div>

    </div>

</header>

<main class="p-6 overflow-x-hidden">

<!-- TITLE -->

<div class="mb-6">

    <h1 class="text-[20px] font-semibold text-slate-800">
        Katalog Barang
    </h1>

    <p class="text-sm text-slate-500 mt-1">
        Jelajahi dan pinjam barang laboratorium yang tersedia
    </p>

</div>

    <!-- SEARCH -->


<!-- FILTER -->

<div class="card p-3 mb-5">

    <form method="GET">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- SEARCH -->

            <div class="relative">

                <i
                data-lucide="search"
                class="absolute left-3 top-3.5 w-4 h-4 text-slate-400"
                ></i>

                <input
             type="text"
           name="search"
         value="<?= htmlspecialchars($search) ?>"
          placeholder="Cari barang..."
             class="w-full h-11 pl-10 pr-4 border border-slate-200 rounded-xl bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
>

            </div>

            <!-- KATEGORI -->

            <select
            name="kategori"
            class="h-11 border border-slate-200 rounded-xl px-3 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
            >

                <option value="">
                    Semua Kategori
                </option>

            </select>

            <!-- LOKASI -->

            <select
            name="lokasi"
            class="border border-slate-200 rounded-lg px-3 py-2 text-sm"
            >

                <option value="">
                    Semua Lokasi
                </option>

            </select>

            <!-- KONDISI -->

            <select
            name="kondisi"
            class="border border-slate-200 rounded-lg px-3 py-2 text-sm"
            >

                <option value="">
                    Semua Kondisi
                </option>

                <option value="baik">
                    Baik
                </option>

                <option value="rusak_ringan">
                    Rusak Ringan
                </option>

                <option value="rusak_berat">
                    Rusak Berat
                </option>

            </select>

        </div>

    </form>

</div>


<!-- GRID BARANG -->

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

<?php while($barang = mysqli_fetch_assoc($query)) : ?>

<div
class="card overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col"
>

    <!-- GAMBAR -->

    <div
    class="h-36 bg-slate-100 flex items-center justify-center overflow-hidden"
    >

        <?php if(!empty($barang['gambar'])) : ?>

            <img
            src="assets/uploads/<?= $barang['gambar']; ?>"
            alt="<?= $barang['nama_barang']; ?>"
            class="w-full h-full object-cover"
            >

        <?php else : ?>

            <i
            data-lucide="package"
            class="w-14 h-14 text-slate-400"
            ></i>

        <?php endif; ?>

    </div>

    <!-- CONTENT -->

    <div class="p-4">

      
<div class="flex flex-wrap gap-2 mb-3">

    <span
    class="bg-blue-100 text-blue-700 text-[10px] px-2 py-1 rounded-md font-medium"
    >
        <?= $barang['kategori']; ?>
    </span>

    <span
    class="bg-purple-100 text-purple-700 text-[10px] px-2 py-1 rounded-md font-medium"
    >
        <?= $barang['lokasi']; ?>
    </span>

    <p class="text-xs text-slate-500 mb-3">

    <?= $barang['lokasi']; ?>

</p>

</div>



        <h3 class="font-semibold text-[18px] text-slate-800 mb-2">

            <?= $barang['nama']; ?>

        </h3>

        <p class="text-sm text-slate-500 mb-4 line-clamp-2"> <?= !empty($barang['deskripsi']) ? substr($barang['deskripsi'],0,80).'...' : 'Tidak ada deskripsi'; ?> </p>

        <!-- STOK -->

        <div class="flex items-center justify-between mb-4">

            <span class="text-xs text-slate-500">
                Stok
            </span>

            <?php if($barang['stok'] > 0) : ?>

                <span
                class="bg-green-100 text-green-700 text-[11px] px-2 py-1 rounded-full"
                >
                    <?= $barang['stok']; ?> tersedia
                </span>

            <?php else : ?>

                <span
                class="bg-red-100 text-red-700 text-[11px] px-2 py-1 rounded-full"
                >
                    Habis
                </span>

            <?php endif; ?>

        </div>

        <!-- KONDISI -->

        <div class="flex items-center justify-between mb-5">

            <span class="text-xs text-slate-500">
                Kondisi
            </span>

            <?php

            if($barang['kondisi']=="baik"){
                echo '<span class="text-green-600 text-xs font-medium">Baik</span>';
            }
            elseif($barang['kondisi']=="rusak_ringan"){
                echo '<span class="text-yellow-600 text-xs font-medium">Rusak Ringan</span>';
            }
            else{
                echo '<span class="text-red-600 text-xs font-medium">Rusak Berat</span>';
            }

            ?>

        </div>

        <!-- BUTTON -->

        <div class="flex gap-2">

            <a
            href="detail_barang.php?id=<?= $barang['id']; ?>"
            class="flex-1 border border-slate-200 text-center py-2 rounded-lg text-sm hover:bg-slate-50"
            >
                Detail
            </a>

            <a
            href="peminjaman.php?id=<?= $barang['id']; ?>"
            class="flex-1 bg-[#1E3A8A] text-white text-center py-2 rounded-lg text-sm hover:bg-blue-700"
            >
                Pinjam
            </a>

        </div>

    </div>

</div>

<?php endwhile; ?>

</div>

<!-- EMPTY STATE -->

<?php

mysqli_data_seek($query, 0);

if(mysqli_num_rows($query) == 0):

?>

<div class="card p-10 text-center mt-6">

    <div
    class="w-20 h-20 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4"
    >

        <i
        data-lucide="package-x"
        class="w-10 h-10 text-slate-400"
        ></i>

    </div>

    <h3 class="font-semibold text-lg text-slate-700">
        Barang Tidak Ditemukan
    </h3>

    <p class="text-sm text-slate-500 mt-2">
        Tidak ada barang yang sesuai dengan pencarian.
    </p>

</div>

<?php endif; ?>

<!-- FLOATING BUTTON -->

<a
href="#"
class="fixed bottom-5 right-5 w-12 h-12 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center shadow-lg hover:bg-blue-700 transition-all duration-300"
>

    <i
    data-lucide="menu"
    class="w-5 h-5"
    ></i>

</a>

</main>

</div>

<script>

lucide.createIcons();

</script>

</body>
</html>