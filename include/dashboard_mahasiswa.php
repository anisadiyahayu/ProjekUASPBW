
<?php

include "auth.php";
include "koneksi.php";

$nama = $_SESSION['nama'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Mahasiswa</title>

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

table{
    font-size:13px;
}

thead{
    font-size:12px;
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<aside
class="fixed left-0 top-0 w-64 h-screen bg-[#1E3A8A] text-white"
>

    <div class="h-full flex flex-col">

        <!-- LOGO -->

        <div class="p-4 border-b border-blue-800">

            <div class="flex items-center gap-2">

                <div
                class="w-8 h-8 rounded-lg bg-[#3B82F6] flex items-center justify-center"
                >

                    <i
                    data-lucide="box"
                    class="w-4 h-4"
                    ></i>

                </div>

                <div>

                    <h1 class="font-semibold text-sm">
                        Lab Inventory
                    </h1>

                    <p class="text-[10px] text-blue-200">
                        Student Portal
                    </p>

                </div>

            </div>

        </div>

        <!-- MENU -->

        <nav class="flex-1 py-3">

            <ul class="space-y-1 px-2">

                <li>

                    <a
                    href="dashboard_mahasiswa.php"
                    class="bg-[#3B82F6] flex items-center gap-2 px-3 py-3 rounded-lg text-sm"
                    >

                        <i
                        data-lucide="layout-dashboard"
                        class="w-4 h-4"
                        ></i>

                        Dashboard

                    </a>

                </li>

                <li>

                    <a
                    href="katalog.php"
                    class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm"
                    >

                        <i
                        data-lucide="package"
                        class="w-4 h-4"
                        ></i>

                        Katalog Barang

                    </a>

                </li>

                <li>

                    <a
                    href="peminjaman.php"
                    class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm"
                    >

                        <i
                        data-lucide="clipboard-list"
                        class="w-4 h-4"
                        ></i>

                        Peminjaman Barang

                    </a>

                </li>

                <li>

                    <a
                    href="pengembalian.php"
                    class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm"
                    >

                        <i
                        data-lucide="rotate-ccw"
                        class="w-4 h-4"
                        ></i>

                        Pengembalian Barang

                    </a>

                </li>

                <li>

                    <a
                    href="riwayat.php"
                    class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm"
                    >

                        <i
                        data-lucide="history"
                        class="w-4 h-4"
                        ></i>

                        Riwayat Peminjaman

                    </a>

                </li>

                <li>

                    <a
                    href="profil.php"
                    class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm"
                    >

                        <i
                        data-lucide="user"
                        class="w-4 h-4"
                        ></i>

                        Profil Saya

                    </a>

                </li>

            </ul>

        </nav>

        <div class="p-4 border-t border-blue-800">

    <a
    href="logout.php"
    class="group flex items-center gap-3 px-4 py-4 rounded-xl
    hover:bg-[#4C3F91]
    transition-all duration-300"
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

<div class="ml-64">

<header
class="bg-white border-b h-[52px] px-6 flex justify-between items-center"
>

    <div class="flex items-center gap-4">

        <button>

            <i
            data-lucide="x"
            class="w-4 h-4 text-slate-500"
            ></i>

        </button>

        <div class="relative w-[270px]">

            <i
            data-lucide="search"
            class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"
            ></i>

            <input
            type="text"
            placeholder="Cari barang laboratorium..."
            class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm"
            >

        </div>

    </div>

    <div class="flex items-center gap-5">

        <div class="relative">

            <i
            data-lucide="bell"
            class="w-5 h-5"
            ></i>

            <span
            class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center"
            >
            2
            </span>

        </div>

        <div class="flex items-center gap-3">

            <div class="text-right">

                <h4 class="text-sm font-semibold">
                    <?= $nama ?>
                </h4>

                <p class="text-[11px] text-slate-500">
                    Mahasiswa
                </p>

            </div>

            <div
            class="w-9 h-9 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center text-sm font-semibold"
            >

                <?= strtoupper(substr($nama,0,2)); ?>

            </div>

        </div>

    </div>

</header>

<main class="p-6">

<!-- PAGE HEADER -->

<div class="mb-6">

    <h1 class="text-[20px] font-semibold text-slate-800">
        Dashboard
    </h1>

    <p class="text-sm text-slate-500">
        Selamat datang, <?= $nama ?>
    </p>

</div>

<!-- SUMMARY CARD -->

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    <!-- CARD 1 -->

    <div class="card p-4 h-[82px] flex items-center justify-between">

        <div>

            <p class="text-xs text-slate-500">
                Permintaan Menunggu
            </p>

            <h3 class="text-[20px] font-bold mt-1">
                2
            </h3>

        </div>

        <div
        class="w-10 h-10 rounded-lg bg-yellow-500 flex items-center justify-center text-white"
        >

            <i
            data-lucide="clock"
            class="w-5 h-5"
            ></i>

        </div>

    </div>

    <!-- CARD 2 -->

    <div class="card p-4 h-[82px] flex items-center justify-between">

        <div>

            <p class="text-xs text-slate-500">
                Barang Sedang Dipinjam
            </p>

            <h3 class="text-[20px] font-bold mt-1">
                3
            </h3>

        </div>

        <div
        class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center text-white"
        >

            <i
            data-lucide="package"
            class="w-5 h-5"
            ></i>

        </div>

    </div>

    <!-- CARD 3 -->

    <div class="card p-4 h-[82px] flex items-center justify-between">

        <div>

            <p class="text-xs text-slate-500">
                Riwayat Selesai
            </p>

            <h3 class="text-[20px] font-bold mt-1">
                12
            </h3>

        </div>

        <div
        class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center text-white"
        >

            <i
            data-lucide="check-circle"
            class="w-5 h-5"
            ></i>

        </div>

    </div>

</div>

<!-- BARANG TERBARU -->

<div class="card overflow-hidden mb-6">

    <div
    class="px-5 py-4 border-b border-slate-200 flex items-center justify-between"
    >

        <h3 class="font-semibold text-sm">
            Barang Terbaru
        </h3>

        <a
        href="katalog.php"
        class="text-blue-700 text-xs font-medium"
        >
            Lihat Katalog
        </a>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-slate-50">

                <tr class="border-t hover:bg-slate-50 transition-all duration-200 cursor-pointer">

                    <th class="px-5 py-3 text-left">
                        Nama Barang
                    </th>

                    <th class="px-5 py-3 text-left">
                        Kategori
                    </th>

                    <th class="px-5 py-3 text-left">
                        Lokasi
                    </th>

                    <th class="px-5 py-3 text-left">
                        Stok
                    </th>

                    <th class="px-5 py-3 text-left">
                        Kondisi
                    </th>

                    <th class="px-5 py-3 text-left">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

                <tr class="border-t">

                    <td class="px-5 py-3 font-medium">
                        ESP32 DevKit
                    </td>

                    <td class="px-5 py-3 text-slate-500">
                        Mikrokontroler
                    </td>

                    <td class="px-5 py-3 text-slate-500">
                        Lab IoT
                    </td>

                    <td class="px-5 py-3">

                        <span
                        class="bg-green-100 text-green-700 text-[11px] px-2 py-1 rounded-full"
                        >
                            18 tersedia
                        </span>

                    </td>

                    <td class="px-5 py-3">
                        Baik
                    </td>

                    <td class="px-5 py-3">

                        <button
                        class="bg-[#1E3A8A] text-white text-[11px] px-3 py-1 rounded-md"
                        >
                            Pinjam
                        </button>

                    </td>

                </tr>

                <tr class="border-t">

                    <td class="px-5 py-3 font-medium">
                        RFID RC522
                    </td>

                    <td class="px-5 py-3 text-slate-500">
                        Sensor
                    </td>

                    <td class="px-5 py-3 text-slate-500">
                        Lab IoT
                    </td>

                    <td class="px-5 py-3">

                        <span
                        class="bg-green-100 text-green-700 text-[11px] px-2 py-1 rounded-full"
                        >
                            15 tersedia
                        </span>

                    </td>

                    <td class="px-5 py-3">
                        Baik
                    </td>

                    <td class="px-5 py-3">

                        <button
                        class="bg-[#1E3A8A] text-white text-[11px] px-3 py-1 rounded-md"
                        >
                            Pinjam
                        </button>

                    </td>

                </tr>

                <tr class="border-t">

                    <td class="px-5 py-3 font-medium">
                        Mikrotik hAP AC2
                    </td>

                    <td class="px-5 py-3 text-slate-500">
                        Networking
                    </td>

                    <td class="px-5 py-3 text-slate-500">
                        Lab Jaringan
                    </td>

                    <td class="px-5 py-3">

                        <span
                        class="bg-green-100 text-green-700 text-[11px] px-2 py-1 rounded-full"
                        >
                            8 tersedia
                        </span>

                    </td>

                    <td class="px-5 py-3">
                        Baik
                    </td>

                    <td class="px-5 py-3">

                        <button
                        class="bg-[#1E3A8A] text-white text-[11px] px-3 py-1 rounded-md"
                        >
                            Pinjam
                        </button>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

<!-- SECTION BAWAH -->

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    <!-- AKTIVITAS TERBARU -->

    <div class="card">

        <div class="px-5 py-4 border-b border-slate-200">

            <h3 class="font-semibold text-sm">
                Aktivitas Terbaru
            </h3>

        </div>

        <div class="p-5 space-y-4">

            <div class="flex gap-3">

                <div
                class="w-8 h-8 rounded-full bg-green-100 text-green-700 flex items-center justify-center"
                >

                    <i
                    data-lucide="check-circle"
                    class="w-4 h-4"
                    ></i>

                </div>

                <div>

                    <p class="text-sm font-medium">
                        Permintaan peminjaman Arduino Uno disetujui
                    </p>

                    <p class="text-xs text-slate-500">
                        2 jam yang lalu
                    </p>

                </div>

            </div>

            <div class="flex gap-3">

                <div
                class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center"
                >

                    <i
                    data-lucide="package"
                    class="w-4 h-4"
                    ></i>

                </div>

                <div>

                    <p class="text-sm font-medium">
                        Anda meminjam ESP32 DevKit
                    </p>

                    <p class="text-xs text-slate-500">
                        Kemarin
                    </p>

                </div>

            </div>

            <div class="flex gap-3">

                <div
                class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center"
                >

                    <i
                    data-lucide="clock"
                    class="w-4 h-4"
                    ></i>

                </div>

                <div>

                    <p class="text-sm font-medium">
                        Permintaan RFID RC522 sedang diproses
                    </p>

                    <p class="text-xs text-slate-500">
                        2 hari yang lalu
                    </p>

                </div>

            </div>

            <div class="flex gap-3">

                <div
                class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center"
                >

                    <i
                    data-lucide="rotate-ccw"
                    class="w-4 h-4"
                    ></i>

                </div>

                <div>

                    <p class="text-sm font-medium">
                        Pengembalian Mikrotik berhasil diverifikasi
                    </p>

                    <p class="text-xs text-slate-500">
                        4 hari yang lalu
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- RIWAYAT PEMINJAMAN -->

    <div class="card">

        <div
        class="px-5 py-4 border-b border-slate-200 flex justify-between items-center"
        >

            <h3 class="font-semibold text-sm">
                Riwayat Peminjaman Terakhir
            </h3>

            <a
            href="riwayat.php"
            class="text-xs text-blue-700 font-medium"
            >
                Lihat Semua
            </a>

        </div>

        <div class="p-5 space-y-4">

            <div
            class="flex justify-between items-center pb-3 border-b"
            >

                <div>

                    <h4 class="text-sm font-medium">
                        Arduino Uno R3
                    </h4>

                    <p class="text-xs text-slate-500">
                        Dipinjam 15 Juni 2026
                    </p>

                </div>

                <span
                class="bg-blue-100 text-blue-700 text-[11px] px-2 py-1 rounded-full"
                >
                    Sedang Dipinjam
                </span>

            </div>

            <div
            class="flex justify-between items-center pb-3 border-b"
            >

                <div>

                    <h4 class="text-sm font-medium">
                        ESP32 DevKit
                    </h4>

                    <p class="text-xs text-slate-500">
                        Dipinjam 12 Juni 2026
                    </p>

                </div>

                <span
                class="bg-blue-100 text-blue-700 text-[11px] px-2 py-1 rounded-full"
                >
                    Sedang Dipinjam
                </span>

            </div>

            <div
            class="flex justify-between items-center pb-3 border-b"
            >

                <div>

                    <h4 class="text-sm font-medium">
                        ESP8266 NodeMCU
                    </h4>

                    <p class="text-xs text-slate-500">
                        Dipinjam 5 Juni 2026
                    </p>

                </div>

                <span
                class="bg-green-100 text-green-700 text-[11px] px-2 py-1 rounded-full"
                >
                    Selesai
                </span>

            </div>

            <div class="flex justify-between items-center">

                <div>

                    <h4 class="text-sm font-medium">
                        Mikrotik hAP AC2
                    </h4>

                    <p class="text-xs text-slate-500">
                        Dipinjam 28 Mei 2026
                    </p>

                </div>

                <span
                class="bg-green-100 text-green-700 text-[11px] px-2 py-1 rounded-full"
                >
                    Selesai
                </span>

            </div>

        </div>

    </div>

</div>

<!-- FLOATING BUTTON -->

<a
href="#"
class="fixed bottom-5 right-5 w-12 h-12 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center shadow-lg"
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

