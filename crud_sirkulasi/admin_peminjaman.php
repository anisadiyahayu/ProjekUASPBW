<?php

include "auth.php";
include "koneksi.php";

$nama = $_SESSION['nama'];

$status = $_GET['status'] ?? '';

$where = '';

if ($status != '') {
    $where = "WHERE peminjaman.status='$status'";
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

$where

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

        .table-row:hover {
            background: #f8fafc;
        }
    </style>

</head>

<body>
    <aside
        class="fixed left-0 top-0 w-64 h-screen bg-[#1E3A8A] text-white">

        <div class="h-full flex flex-col">

            <div class="p-5 border-b border-blue-800">

                <div class="flex items-center gap-3">

                    <div
                        class="w-9 h-9 rounded-lg bg-[#3B82F6] flex items-center justify-center">

                        <i data-lucide="box" class="w-5 h-5"></i>

                    </div>

                    <div>

                        <h1 class="font-semibold">
                            Lab Inventory
                        </h1>

                        <p class="text-xs text-blue-200">
                            Admin Panel
                        </p>

                    </div>

                </div>

            </div>

            <nav class="flex-1 px-2 py-4">

                <div class="space-y-1">

                    <a href="dashboard_admin.php"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        Dashboard
                    </a>

                    <a href="barang.php"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="package" class="w-4 h-4"></i>
                        Data Barang
                    </a>

                    <a href="kategori.php"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="folder-tree" class="w-4 h-4"></i>
                        Kategori Barang
                    </a>

                    <a href="lokasi.php"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        Lokasi Penyimpanan
                    </a>

                    <a href="users.php"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        Data User
                    </a>

                    <a href="admin_peminjaman.php"
                        class="bg-[#3B82F6] flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium">
                        <i data-lucide="package-check" class="w-4 h-4"></i>
                        Peminjaman Barang
                    </a>

                    <a href="admin_pengembalian.php"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        Pengembalian Barang
                    </a>

                    <a href="laporan.php"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        Laporan Inventaris
                    </a>

                    <a href="aktivitas.php"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="history" class="w-4 h-4"></i>
                        Riwayat Aktivitas
                    </a>

                    <a href="profil_admin.php"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        Profil
                    </a>

                </div>

            </nav>

            <div class="p-4 border-t border-blue-800">

                <a
                    href="logout.php"
                    class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91] transition-all">

                    <i
                        data-lucide="log-out"
                        class="w-5 h-5 text-white group-hover:text-red-500"></i>

                    <span class="group-hover:text-red-500">
                        Logout
                    </span>

                </a>

            </div>

        </div>

    </aside>

    <div class="ml-64">

        <header
            class="bg-white border-b h-[56px] px-6 flex justify-between items-center">

            <div class="flex items-center gap-4">

                <button>
                    <i data-lucide="x" class="w-4 h-4 text-slate-500"></i>
                </button>

                <div class="relative w-[280px]">

                    <i
                        data-lucide="search"
                        class="absolute left-3 top-2.5 w-4 h-4 text-slate-400">
                    </i>

                    <input
                        type="text"
                        placeholder="Cari barang, user, atau aktivitas..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm">

                </div>

            </div>

            <div class="flex items-center gap-5">

                <div class="relative">

                    <i data-lucide="bell" class="w-5 h-5"></i>

                    <span
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
                        5
                    </span>

                </div>

                <div class="flex items-center gap-3">

                    <div class="text-right">

                        <h4 class="text-sm font-semibold">
                            <?= htmlspecialchars($nama) ?>
                        </h4>

                        <p class="text-[11px] text-slate-500">
                            Administrator
                        </p>

                    </div>

                    <div
                        class="w-10 h-10 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center font-semibold">

                        <?= strtoupper(substr($nama, 0, 2)) ?>

                    </div>

                </div>

            </div>

        </header>

        <main class="p-6">

            <div class="mb-6">

                <h1 class="text-[28px] font-semibold text-slate-800">
                    Permintaan Barang
                </h1>

                <p class="text-slate-500 text-sm">
                    Kelola permintaan peminjaman barang laboratorium
                </p>

            </div>

            <div class="card p-2 mb-5">

                <div class="grid grid-cols-3 gap-2">

                    <a
                        href="admin_peminjaman.php"
                        class="<?= $status == '' ? 'bg-[#1E3A8A] text-white' : '' ?> py-3 rounded-lg text-center">

                        Semua Permintaan

                    </a>

                    <a
                        href="?status=pending"
                        class="<?= $status == 'pending' ? 'bg-[#1E3A8A] text-white' : '' ?> py-3 rounded-lg text-center">

                        Pending

                    </a>

                    <a
                        href="?status=disetujui"
                        class="<?= $status == 'disetujui' ? 'bg-[#1E3A8A] text-white' : '' ?> py-3 rounded-lg text-center">

                        Disetujui

                    </a>

                </div>

            </div>

            <div class="card overflow-hidden">

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

                            <th class="px-5 py-4 text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>

                            <?php

                            $badge = 'bg-slate-100 text-slate-700';

                            if ($row['status'] == 'pending') {
                                $badge = 'bg-yellow-100 text-yellow-700';
                            } elseif ($row['status'] == 'disetujui') {
                                $badge = 'bg-green-100 text-green-700';
                            } elseif ($row['status'] == 'ditolak') {
                                $badge = 'bg-red-100 text-red-700';
                            } elseif ($row['status'] == 'dikembalikan') {
                                $badge = 'bg-blue-100 text-blue-700';
                            }

                            ?>

                            <tr class="border-t table-row">

                                <td class="px-5 py-4">

                                    <div>

                                        <div class="font-medium text-slate-800">

                                            <h4 class="text-sm font-semibold">
                                                <?= htmlspecialchars($nama ?? '') ?>
                                            </h4>

                                        </div>

                                        <div class="text-xs text-slate-500">

                                            <?= htmlspecialchars($row['npm'] ?? '') ?>

                                        </div>

                                    </div>

                                </td>

                                <td class="px-5 py-4">

                                    <?= htmlspecialchars($row['nama_barang'] ?? '') ?>

                                </td>

                                <td class="px-5 py-4 font-semibold">

                                    <?= $row['jumlah'] ?>

                                </td>

                                <td class="px-5 py-4">

                                    <?= htmlspecialchars($row['keperluan']) ?>

                                </td>

                                <td class="px-5 py-4">

                                    <?= date('Y-m-d', strtotime($row['tanggal_pinjam'])) ?>

                                </td>

                                <td class="px-5 py-4">

                                    <?= date('Y-m-d', strtotime($row['tanggal_kembali'])) ?>

                                </td>

                                <td class="px-5 py-4">

                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold <?= $badge ?>">

                                        <?= ucfirst($row['status']) ?>

                                    </span>

                                </td>

                                <td class="px-5 py-4">

                                    <div class="flex justify-center gap-4">

                                        <!-- DETAIL -->

                                        <button
                                            type="button"
                                            class="text-blue-600"

                                            onclick='openDetail(
<?= json_encode($row["nama"]) ?>,
<?= json_encode($row["npm"]) ?>,
<?= json_encode($row["nama_barang"]) ?>,
<?= $row["jumlah"] ?>,
<?= json_encode($row["keperluan"]) ?>,
<?= json_encode($row["tanggal_pinjam"]) ?>,
<?= json_encode($row["tanggal_kembali"]) ?>,
<?= json_encode($row["status"]) ?>,
<?= json_encode($row["catatan"]) ?>
)'>

                                            <i data-lucide="eye"></i>

                                        </button>

                                        <!-- VALIDASI -->

                                        <?php if ($row['status'] == 'pending') : ?>

                                            <button
                                                type="button"

                                                onclick='openValidasi(
<?= $row["id"] ?>,
<?= json_encode($row["nama"]) ?>,
<?= json_encode($row["npm"]) ?>,
<?= json_encode($row["nama_barang"]) ?>,
<?= $row["jumlah"] ?>,
<?= json_encode($row["keperluan"]) ?>,
<?= json_encode($row["tanggal_pinjam"]) ?>,
<?= json_encode($row["tanggal_kembali"]) ?>
)'

                                                class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-xs">

                                                Validasi

                                            </button>

                                        <?php endif; ?>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </main>

    </div>

    <!-- MODAL DETAIL -->

    <div
        id="modalDetail"
        class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">

        <button
            type="button"
            onclick="closeValidasi()"
            class="border px-4 py-2 rounded-lg">
            Batal
        </button>

        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl">

            <div class="p-5 border-b">

                <h3 class="font-semibold text-lg">
                    Detail Peminjaman
                </h3>

            </div>

            <div class="p-5">

                <div class="grid md:grid-cols-2 gap-5">

                    <div>

                        <label class="text-sm text-slate-500">
                            Peminjam
                        </label>

                        <div
                            id="d_nama"
                            class="font-medium mt-1">
                            -
                        </div>

                    </div>

                    <div>

                        <label class="text-sm text-slate-500">
                            NPM
                        </label>

                        <div
                            id="d_npm"
                            class="font-medium mt-1">
                            -
                        </div>

                    </div>

                    <div>

                        <label class="text-sm text-slate-500">
                            Barang
                        </label>

                        <div
                            id="d_barang"
                            class="font-medium mt-1">
                            -
                        </div>

                    </div>

                    <div>

                        <label class="text-sm text-slate-500">
                            Jumlah
                        </label>

                        <div
                            id="d_jumlah"
                            class="font-medium mt-1">
                            -
                        </div>

                    </div>

                    <div>

                        <label class="text-sm text-slate-500">
                            Tanggal Pinjam
                        </label>

                        <div
                            id="d_tgl"
                            class="font-medium mt-1">
                            -
                        </div>

                    </div>

                    <div>

                        <label class="text-sm text-slate-500">
                            Tanggal Kembali
                        </label>

                        <div
                            id="d_kembali"
                            class="font-medium mt-1">
                            -
                        </div>

                    </div>

                </div>

                <div class="mt-5">

                    <label class="text-sm text-slate-500">
                        Keperluan
                    </label>

                    <div
                        id="d_keperluan"
                        class="bg-slate-50 rounded-lg p-3 mt-2">
                        -
                    </div>

                </div>

                <div class="mt-5">

                    <label class="text-sm text-slate-500">
                        Catatan Admin
                    </label>

                    <div
                        id="d_catatan"
                        class="bg-slate-50 rounded-lg p-3 mt-2">
                        -
                    </div>

                </div>

                <div class="mt-5">

                    <label class="text-sm text-slate-500">
                        Status
                    </label>

                    <div
                        id="d_status"
                        class="mt-2">
                        -
                    </div>

                </div>

            </div>

            <div class="p-5 border-t flex justify-end">

                <button
                    onclick="closeDetail()"
                    class="px-5 py-2 border rounded-lg">

                    Tutup

                </button>

            </div>

        </div>

    </div>

    <!-- MODAL VALIDASI -->

    <div
        id="modalValidasi"
        class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">

        <div class="bg-white rounded-xl shadow-xl w-full max-w-xl">

            <form
                action="validasi_peminjaman.php"
                method="POST">

                <input
                    type="hidden"
                    name="id"
                    id="validasi_id">

                <div class="p-5 border-b">

                    <h3 class="font-semibold text-lg">
                        Validasi Permintaan
                    </h3>

                </div>

                <div class="p-5">

                    <div class="bg-slate-50 rounded-lg p-4 text-sm space-y-2">

                        <div>
                            <b>Peminjam :</b>
                            <span id="v_nama"></span>
                        </div>

                        <div>
                            <b>Barang :</b>
                            <span id="v_barang"></span>
                        </div>

                        <div>
                            <b>Jumlah :</b>
                            <span id="v_jumlah"></span>
                        </div>

                        <div>
                            <b>Keperluan :</b>
                            <span id="v_keperluan"></span>
                        </div>

                        <div>
                            <b>Periode :</b>
                            <span id="v_periode"></span>
                        </div>

                    </div>

                    <div class="mt-4">

                        <label class="block text-sm mb-2">
                            Catatan Validasi
                        </label>

                        <textarea
                            name="catatan"
                            rows="4"
                            class="w-full border rounded-lg px-4 py-3"
                            placeholder="Masukkan catatan untuk peminjam..."></textarea>

                    </div>

                </div>

                <div class="p-5 border-t flex justify-end gap-3">

                    <button
                        type="button"
                        onclick="closeValidasi()"
                        class="px-5 py-3 border rounded-lg">

                        Batal

                    </button>

                    <button
                        type="submit"
                        name="aksi"
                        value="ditolak"
                        class="px-5 py-3 bg-red-100 text-red-600 rounded-lg">

                        Tolak

                    </button>

                    <button
                        type="submit"
                        name="aksi"
                        value="disetujui"
                        class="px-5 py-3 bg-green-100 text-green-600 rounded-lg">

                        Setujui

                    </button>

                </div>

            </form>

        </div>

    </div>

    <script>
        lucide.createIcons();

        /* DETAIL */

        function openDetail(
            nama,
            npm,
            barang,
            jumlah,
            keperluan,
            tgl,
            kembali,
            status,
            catatan
        ) {

            document.getElementById('d_nama').innerHTML = nama;
            document.getElementById('d_npm').innerHTML = npm;
            document.getElementById('d_barang').innerHTML = barang;
            document.getElementById('d_jumlah').innerHTML = jumlah;
            document.getElementById('d_tgl').innerHTML = tgl;
            document.getElementById('d_kembali').innerHTML = kembali;
            document.getElementById('d_keperluan').innerHTML = keperluan;
            document.getElementById('d_status').innerHTML = status;
            document.getElementById('d_catatan').innerHTML =
                catatan ? catatan : '-';

            document
                .getElementById('modalDetail')
                .classList.remove('hidden');

        }

        function closeDetail() {

            document
                .getElementById('modalDetail')
                .classList.add('hidden');

        }

        /* VALIDASI */

        function openValidasi(
            id,
            nama,
            npm,
            barang,
            jumlah,
            keperluan,
            tgl,
            kembali
        ) {

            document.getElementById('validasi_id').value = id;

            document.getElementById('v_nama').innerHTML =
                nama + ' (' + npm + ')';

            document.getElementById('v_barang').innerHTML =
                barang;

            document.getElementById('v_jumlah').innerHTML =
                jumlah;

            document.getElementById('v_keperluan').innerHTML =
                keperluan;

            document.getElementById('v_periode').innerHTML =
                tgl + ' s/d ' + kembali;

            document
                .getElementById('modalValidasi')
                .classList.remove('hidden');

        }

        function closeValidasi() {

            document
                .getElementById('modalValidasi')
                .classList.add('hidden');

        }
    </script>