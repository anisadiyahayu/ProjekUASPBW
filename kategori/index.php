<?php
require_once '../auth/auth_check.php';
include "../include/koneksi.php";

$query = mysqli_query(
    $conn,
    "SELECT categories.*, COUNT(items.id) AS jumlah_barang 
     FROM categories 
     LEFT JOIN items ON categories.id = items.id_kategori 
     GROUP BY categories.id"
);
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../include/style_tailwind.css">
<link rel="stylesheet" href="../include/style_sidebar.css">
<script src="https://cdn.tailwindcss.com"></script>


<div class="min-h-screen bg-background">
    <?php $current_page = 'kategori'; ?>
    <?php if ($_SESSION['role'] === 'Admin') {
        include __DIR__ . '/../template/sidebar_admin.php';
    } else {
        include __DIR__ . '/../template/sidebar.php';
    } ?>
    <div id="main-content" class="transition-all duration-300 ml-64">
        <?php include __DIR__ . '/../template/header.php'; ?>

        <div class="p-6">

            <!-- Header -->
            <div class="flex justify-between items-start mb-8">

                <div>
                    <h1 class="text-4xl font-bold text-slate-900">
                        Kategori Barang
                    </h1>

                    <p class="text-gray-500 mt-1">
                        Kelola kategori untuk klasifikasi barang
                    </p>
                </div>

                <button
                    onclick="openTambahModal()"
                    class="bg-[#1E40AF] hover:bg-[#1D4ED8] text-white px-6 py-3 rounded-xl shadow-md font-medium">

                    <i class="fa-solid fa-plus mr-2"></i>
                    Tambah Kategori

                </button>

            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">

                <table class="w-full">

                    <thead class="bg-gray-50">

                        <tr class="text-left text-gray-700">

                            <th class="px-6 py-4 font-semibold">
                                Nama Kategori
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Deskripsi
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Jumlah Barang
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($data = mysqli_fetch_assoc($query)) { ?>

                            <tr class="border-t hover:bg-gray-50 transition">

                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-4">

                                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">

                                            <i class="fa-regular fa-folder text-blue-700"></i>

                                        </div>

                                        <span class="font-semibold text-slate-800">
                                            <?= htmlspecialchars($data['nama']) ?>
                                        </span>

                                    </div>

                                </td>

                                <td class="px-6 py-5 text-gray-600">

                                    <?= htmlspecialchars($data['deskripsi']) ?>

                                </td>

                                <td class="px-6 py-5">

                                    <span class="bg-blue-100 text-blue-700 px-4 py-1 rounded-full text-sm font-medium">

                                        <?= $data['jumlah_barang'] ?> barang

                                    </span>

                                </td>

                                <td class="px-6 py-5">

                                    <div class="flex gap-6">

                                        <button
                                            onclick="openEditModal(
                                '<?= $data['id']; ?>',
                                '<?= htmlspecialchars($data['nama'], ENT_QUOTES); ?>',
                                '<?= htmlspecialchars($data['deskripsi'], ENT_QUOTES); ?>'
                                )">

                                            <i class="fa-solid fa-pen text-green-600"></i>

                                        </button>

                                        <a
                                            href="hapus.php?id=<?= $data['id']; ?>"
                                            onclick="return confirm('Yakin ingin menghapus kategori ini?')">

                                            <i class="fa-solid fa-trash text-red-600"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

        <!-- MODAL EDIT -->
        <div id="editModal" class="hidden fixed inset-0 bg-black/40 z-50 items-center justify-center">
            <div class="bg-white w-[520px] rounded-2xl shadow-xl">
                <div class="p-6 border-b flex justify-between items-center">
                    <h2 class="text-2xl font-bold">Edit Kategori</h2>
                    <button onclick="closeEditModal()"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>

                <form action="update.php" method="POST">

                    <input type="hidden" name="id" id="edit_id">

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block mb-2 font-medium">Nama Kategori</label>
                            <input type="text" name="nama" id="edit_nama" required class="w-full border rounded-lg p-3">
                        </div>
                        <div>
                            <label class="block mb-2 font-medium">Deskripsi</label>
                            <textarea name="deskripsi" id="edit_deskripsi" rows="4" class="w-full border rounded-lg p-3"></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" onclick="closeEditModal()" class="flex-1 border rounded-lg py-3">Batal</button>

                            <button type="submit" class="flex-1 bg-[#1E40AF] text-white rounded-lg py-3">Simpan</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
        <!-- MODAL TAMBAH -->
        <div id="tambahModal"
            class="hidden fixed inset-0 bg-black/40 z-50 items-center justify-center">

            <div class="bg-white w-[520px] rounded-2xl shadow-xl">

                <div class="p-6 border-b flex justify-between items-center">

                    <h2 class="text-2xl font-bold">
                        Tambah Kategori
                    </h2>

                    <button onclick="closeTambahModal()">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>

                </div>

                <form action="tambah.php" method="POST">

                    <div class="p-6 space-y-4">

                        <div>

                            <label class="block mb-2 font-medium">
                                Nama Kategori
                            </label>

                            <input
                                type="text"
                                name="nama"
                                required
                                class="w-full border rounded-lg p-3">

                        </div>

                        <div>

                            <label class="block mb-2 font-medium">
                                Deskripsi
                            </label>

                            <textarea
                                name="deskripsi"
                                rows="4"
                                class="w-full border rounded-lg p-3"></textarea>

                        </div>

                        <div class="flex gap-3">

                            <button
                                type="button"
                                onclick="closeTambahModal()"
                                class="flex-1 border rounded-lg py-3">

                                Batal

                            </button>

                            <button
                                type="submit"
                                class="flex-1 bg-[#1E40AF] text-white rounded-lg py-3">

                                Simpan

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <script src="../include/script.js">

    </script>