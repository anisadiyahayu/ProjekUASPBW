<?php

include "auth.php";
include "koneksi.php";

$nama = $_SESSION['nama'];
$id_user = $_SESSION['id'];

$status_filter = $_GET['status'] ?? '';

$where = '';

if ($status_filter != '') {
    $where = "WHERE peminjaman.status='$status_filter'";
}

$query = mysqli_query($conn, "
SELECT
peminjaman.*,
users.nama,
users.npm,
items.nama_barang
FROM peminjaman
LEFT JOIN users
ON peminjaman.user_id = users.id
LEFT JOIN items
ON peminjaman.item_id = items.id
WHERE peminjaman.user_id='$id_user'
" . ($status_filter != '' ? " AND peminjaman.status='$status_filter'" : "") . "
ORDER BY peminjaman.id DESC
");

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Peminjaman Barang</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f1f5f9;
        }

        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
        }

        table {
            font-size: 13px;
        }

        thead {
            font-size: 12px;
        }
    </style>

</head>

<body>
    <aside
        class="fixed left-0 top-0 w-64 h-screen bg-[#1E3A8A] text-white">

        <div class="h-full flex flex-col">

            <div class="p-4 border-b border-blue-800">

                <div class="flex items-center gap-2">

                    <div
                        class="w-8 h-8 rounded-lg bg-[#3B82F6] flex items-center justify-center">
                        <i data-lucide="box" class="w-4 h-4"></i>
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

            <nav class="flex-1 py-3">

                <ul class="space-y-1 px-2">

                    <li>
                        <a
                            href="dashboard_mahasiswa.php"
                            class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <a
                            href="katalog.php"
                            class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="package" class="w-4 h-4"></i>
                            Katalog Barang
                        </a>
                    </li>

                    <li>
                        <a
                            href="peminjaman.php"
                            class="bg-[#3B82F6] flex items-center gap-2 px-3 py-3 rounded-lg text-sm">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                            Peminjaman Barang
                        </a>
                    </li>

                    <li>
                        <a
                            href="pengembalian.php"
                            class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            Pengembalian Barang
                        </a>
                    </li>

                    <li>
                        <a
                            href="riwayat.php"
                            class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="history" class="w-4 h-4"></i>
                            Riwayat Peminjaman
                        </a>
                    </li>

                    <li>
                        <a
                            href="profil.php"
                            class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            Profil Saya
                        </a>
                    </li>

                </ul>

            </nav>

            <div class="p-4 border-t border-blue-800">

                <a
                    href="logout.php"
                    class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91] transition-all duration-300">

                    <i
                        data-lucide="log-out"
                        class="w-5 h-5 text-white group-hover:text-red-500"></i>

                    <span class="font-medium text-white group-hover:text-red-500">
                        Logout
                    </span>

                </a>

            </div>

        </div>

    </aside>

    <div class="ml-64">

        <header
            class="bg-white border-b h-[52px] px-6 flex justify-between items-center">

            <div class="flex items-center gap-4">

                <button>
                    <i data-lucide="x" class="w-4 h-4 text-slate-500"></i>
                </button>

                <div class="relative w-[270px]">

                    <i
                        data-lucide="search"
                        class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"></i>

                    <input
                        type="text"
                        placeholder="Cari barang laboratorium..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm">

                </div>

            </div>

            <div class="flex items-center gap-5">

                <div class="relative">

                    <i data-lucide="bell" class="w-5 h-5"></i>

                    <span
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
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
                        class="w-9 h-9 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center text-sm font-semibold">
                        <?= strtoupper(substr($nama, 0, 2)); ?>
                    </div>

                </div>

            </div>

        </header>

        <main class="p-6">

            <div class="mb-6">

                <h1 class="text-[20px] font-semibold text-slate-800">
                    Permintaan Barang
                </h1>

                <p class="text-sm text-slate-500">
                    Kelola permintaan peminjaman barang laboratorium
                </p>

            </div>

            <div class="card p-2 mb-5">

                <div class="grid grid-cols-3 gap-2">

                    <a
                        href="peminjaman.php"
                        class="text-center py-2 rounded-lg text-sm font-medium
        <?= $status_filter == '' ? 'bg-[#1E3A8A] text-white' : 'text-slate-600 hover:bg-slate-100'; ?>">
                        Semua Permintaan
                    </a>

                    <a
                        href="peminjaman.php?status=pending"
                        class="text-center py-2 rounded-lg text-sm font-medium
        <?= $status_filter == 'pending' ? 'bg-[#1E3A8A] text-white' : 'text-slate-600 hover:bg-slate-100'; ?>">
                        Pending
                    </a>

                    <a
                        href="peminjaman.php?status=disetujui"
                        class="text-center py-2 rounded-lg text-sm font-medium
        <?= $status_filter == 'disetujui' ? 'bg-[#1E3A8A] text-white' : 'text-slate-600 hover:bg-slate-100'; ?>">
                        Disetujui
                    </a>

                </div>

            </div>

            <div class="card overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-slate-50">

                            <tr>

                                <th class="px-5 py-4 text-left">
                                    Peminjam
                                </th>

                                <th class="px-5 py-4 text-left">
                                    Barang
                                </th>

                                <th class="px-5 py-4 text-left">
                                    Jumlah
                                </th>

                                <th class="px-5 py-4 text-left">
                                    Keperluan
                                </th>

                                <th class="px-5 py-4 text-left">
                                    Tanggal Pinjam
                                </th>

                                <th class="px-5 py-4 text-left">
                                    Estimasi Kembali
                                </th>

                                <th class="px-5 py-4 text-left">
                                    Status
                                </th>



                            </tr>

                        </thead>

                        <tbody>

                            <?php while ($row = mysqli_fetch_assoc($query)) : ?>

                                <tr
                                    class="border-t hover:bg-slate-50 transition-all duration-200">

                                    <!-- PEMINJAM -->

                                    <td class="px-5 py-4">

                                        <h4 class="font-medium">
                                            <?= $row['nama']; ?>
                                        </h4>

                                        <p class="text-xs text-slate-500">
                                            <?= $row['npm']; ?>
                                        </p>

                                    </td>

                                    <!-- BARANG -->

                                    <td class="px-5 py-4">

                                        <?= $row['nama_barang']; ?>

                                    </td>

                                    <!-- JUMLAH -->

                                    <td class="px-5 py-4">

                                        <?= $row['jumlah']; ?>

                                    </td>

                                    <!-- KEPERLUAN -->

                                    <td class="px-5 py-4">

                                        <?= $row['keperluan']; ?>

                                    </td>

                                    <!-- TGL PINJAM -->

                                    <td class="px-5 py-4">

                                        <?= date('d M Y', strtotime($row['tanggal_pinjam'])); ?>

                                    </td>

                                    <!-- TGL KEMBALI -->

                                    <td class="px-5 py-4">

                                        <?= date('d M Y', strtotime($row['tanggal_kembali'])); ?>

                                    </td>

                                    <!-- STATUS -->

                                    <td class="px-5 py-4">

                                        <?php

                                        if ($row['status'] == 'pending') {
                                            echo '
                            <span class="bg-yellow-100 text-yellow-700 text-[11px] px-3 py-1 rounded-full font-medium">
                            Menunggu
                            </span>';
                                        } elseif ($row['status'] == 'disetujui') {
                                            echo '
                            <span class="bg-green-100 text-green-700 text-[11px] px-3 py-1 rounded-full font-medium">
                            Disetujui
                            </span>';
                                        } elseif ($row['status'] == 'ditolak') {
                                            echo '
                            <span class="bg-red-100 text-red-700 text-[11px] px-3 py-1 rounded-full font-medium">
                            Ditolak
                            </span>';
                                        } elseif ($row['status'] == 'dikembalikan') {
                                            echo '
                            <span class="bg-blue-100 text-blue-700 text-[11px] px-3 py-1 rounded-full font-medium">
                            Selesai
                            </span>';
                                        }

                                        ?>

                                    </td>

                                    <!-- AKSI -->

                                    <td class="px-5 py-4 text-center">

                                        <button
                                            type="button"
                                            onclick='openDetail(
<?= json_encode($row["nama"]) ?>,
<?= json_encode($row["npm"]) ?>,
<?= json_encode($row["nama_barang"]) ?>,
<?= $row["jumlah"] ?>,
<?= json_encode($row["keperluan"]) ?>,
<?= json_encode($row["tanggal_pinjam"]) ?>,
<?= json_encode($row["tanggal_kembali"]) ?>,
<?= json_encode($row["status"]) ?>
)'
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-100">

                                            <i data-lucide="eye" class="w-4 h-4"></i>

                                        </button>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

    <script>
        lucide.createIcons();

        function openDetail(
            nama,
            npm,
            barang,
            jumlah,
            keperluan,
            pinjam,
            kembali,
            status
        ) {

            document.getElementById('d_nama').innerHTML = nama;
            document.getElementById('d_npm').innerHTML = npm;
            document.getElementById('d_barang').innerHTML = barang;
            document.getElementById('d_jumlah').innerHTML = jumlah;
            document.getElementById('d_keperluan').innerHTML = keperluan;
            document.getElementById('d_pinjam').innerHTML = pinjam;
            document.getElementById('d_kembali').innerHTML = kembali;

            let badge = '';

            if (status == 'pending') {
                badge = '<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">Menunggu</span>';
            } else if (status == 'disetujui') {
                badge = '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">Disetujui</span>';
            } else if (status == 'ditolak') {
                badge = '<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">Ditolak</span>';
            } else {
                badge = '<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">Selesai</span>';
            }

            document.getElementById('d_status').innerHTML = badge;

            document
                .getElementById('modalDetail')
                .classList.remove('hidden');

        }

        function closeDetail() {

            document
                .getElementById('modalDetail')
                .classList.add('hidden');

        }
    </script>

    <div
        id="modalDetail"
        class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl">

            <div class="p-5 border-b flex justify-between items-center">

                <h3 class="font-semibold text-xl">
                    Detail Permintaan
                </h3>

                <button onclick="closeDetail()">
                    ✕
                </button>

            </div>

            <div class="p-5">

                <div class="bg-slate-50 rounded-xl p-4 space-y-3">

                    <p>
                        <b>Peminjam:</b>
                        <span id="d_nama"></span>
                        (<span id="d_npm"></span>)
                    </p>

                    <p>
                        <b>Barang:</b>
                        <span id="d_barang"></span>
                    </p>

                    <p>
                        <b>Jumlah:</b>
                        <span id="d_jumlah"></span>
                    </p>

                    <p>
                        <b>Keperluan:</b>
                        <span id="d_keperluan"></span>
                    </p>

                    <p>
                        <b>Tanggal Pinjam:</b>
                        <span id="d_pinjam"></span>
                    </p>

                    <p>
                        <b>Estimasi Kembali:</b>
                        <span id="d_kembali"></span>
                    </p>

                    <p>
                        <b>Status:</b>
                        <span id="d_status"></span>
                    </p>

                </div>

            </div>

            <div class="p-5 border-t flex justify-end">

                <button
                    onclick="closeDetail()"
                    class="px-5 py-2 bg-[#1E3A8A] text-white rounded-lg">
                    Tutup
                </button>

            </div>

        </div>

    </div>

</body>

</html>