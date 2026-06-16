<?php
include "auth.php";
include "koneksi.php";

$nama = $_SESSION['nama'];

$query = mysqli_query($conn, "SELECT locations.*, COUNT(items.id) as jumlah_barang FROM locations LEFT JOIN items ON locations.id = items.id_lokasi GROUP BY locations.id ORDER BY locations.nama_lokasi ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokasi Penyimpanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* * KETERANGAN STYLE (CSS):
         * 1. * { ... } -> Mengatur jenis font global di seluruh halaman menggunakan 'Poppins'.
         * 2. body { ... } -> Mengatur warna latar belakang utama halaman dengan nuansa abu-abu muda (#f1f5f9).
         * 3. .card { ... } -> Desain komponen kartu (box) lokasi dengan latar belakang putih, 
         * sudut melengkung (16px), dan bayangan halus (box-shadow) agar terlihat melayang.
         */
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f1f5f9; }
        .card { background: white; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    </style>
</head>
<body>
    <div class="flex min-h-screen">
        <aside class="fixed left-0 top-0 w-64 h-screen bg-[#1E3A8A] text-white">
            <div class="h-full flex flex-col">
                <div class="p-5 border-b border-blue-800">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-[#3B82F6] flex items-center justify-center">
                            <i data-lucide="box" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h1 class="font-semibold">Lab Inventory</h1>
                            <p class="text-xs text-blue-200">Admin Panel</p>
                        </div>
                    </div>
                </div>
                <nav class="flex-1 px-2 py-4">
                    <div class="space-y-1">
                        <a href="dashboard_admin.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                        </a>
                        <a href="barang.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="package" class="w-4 h-4"></i> Data Barang
                        </a>
                        <a href="kategori.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="folder-tree" class="w-4 h-4"></i> Kategori Barang
                        </a>
                        <a href="lokasi.php" class="bg-[#3B82F6] flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium">
                            <i data-lucide="map-pin" class="w-4 h-4"></i> Lokasi Penyimpanan
                        </a>
                        <a href="users.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="users" class="w-4 h-4"></i> Data User
                        </a>
                        <a href="admin_peminjaman.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="package-check" class="w-4 h-4"></i> Peminjaman Barang
                        </a>
                        <a href="admin_pengembalian.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Pengembalian Barang
                        </a>
                        <a href="laporan.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="file-text" class="w-4 h-4"></i> Laporan Inventaris
                        </a>
                        <a href="aktivitas.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="history" class="w-4 h-4"></i> Riwayat Aktivitas
                        </a>
                        <a href="profil_admin.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="user" class="w-4 h-4"></i> Profil
                        </a>
                    </div>
                </nav>
                <div class="p-4 border-t border-blue-800">
                    <a href="logout.php" class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91] transition-all">
                        <i data-lucide="log-out" class="w-5 h-5 text-white group-hover:text-red-500"></i>
                        <span class="group-hover:text-red-500">Logout</span>
                    </a>
                </div>
            </div>
        </aside>

        <div class="ml-64 flex-1">
            <header class="h-[60px] bg-white border-b flex justify-between items-center px-6">
                <input type="text" placeholder="Cari barang, user, atau aktivitas..." class="w-[350px] border rounded-lg px-4 py-2">
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="font-semibold text-sm"><?= $nama ?></div>
                        <div class="text-xs text-slate-500">Administrator</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center">
                        <?= strtoupper(substr($nama, 0, 2)) ?>
                    </div>
                </div>
            </header>

            <main class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800">Lokasi Penyimpanan</h1>
                        <p class="text-slate-500">Kelola lokasi penyimpanan barang laboratorium</p>
                    </div>
                    <button onclick="openTambahLokasi()" class="bg-[#1E3A8A] text-white px-5 py-3 rounded-xl flex items-center gap-2">
                        <i data-lucide="plus"></i> Tambah Lokasi
                    </button>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                        <div class="card p-5">
                            <div class="flex justify-between items-start">
                                <div class="flex gap-3">
                                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                                        <i data-lucide="map-pin"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-lg"><?= htmlspecialchars($row['nama_lokasi']) ?></h3>
                                        <p class="text-slate-500 text-sm"><?= htmlspecialchars($row['keterangan']) ?></p>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="flex justify-between items-center">
                                <div class="font-semibold text-slate-700"><?= $row['jumlah_barang'] ?> barang</div>
                                <div class="flex gap-4">
                                    <button onclick='openEditLokasi(<?= $row["id"] ?>, <?= json_encode($row["nama_lokasi"]) ?>, <?= json_encode($row["keterangan"]) ?>)' class="text-green-600">
                                        <i data-lucide="pencil"></i>
                                    </button>
                                    <button onclick='openHapusLokasi(<?= $row["id"] ?>, <?= json_encode($row["nama_lokasi"]) ?>)' class="text-red-600">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        /* * KETERANGAN JAVASCRIPT FUNCTIONS:
         * Seluruh fungsi di bawah ini digunakan untuk mengatur interaksi antarmuka (UI), 
         * khususnya membuka dan menutup modal dialog serta mempassing data dinamis dari database ke dalam form modal.
         */

        // Memastikan librari ikon Lucide dirender hanya ketika semua elemen HTML selesai dimuat.
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
        });

        /* --- MANAJEMEN MODAL TAMBAH LOKASI --- */

        // Menampilkan modal dialog "Tambah Lokasi" dengan menghapus utilitas class 'hidden' dari Tailwind.
        function openTambahLokasi() {
            document.getElementById('modalTambahLokasi').classList.remove('hidden');
        }

        // Menyembunyikan modal dialog "Tambah Lokasi" kembali dengan menambahkan class 'hidden'.
        function closeTambahLokasi() {
            document.getElementById('modalTambahLokasi').classList.add('hidden');
        }

        /* --- MANAJEMEN MODAL EDIT LOKASI --- */

        // Menampilkan modal dialog "Edit Lokasi" sekaligus mengisi nilai input form secara otomatis
        // berdasarkan data ID, nama lokasi, dan keterangan dari baris data yang diklik.
        function openEditLokasi(id, nama, keterangan) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama_lokasi').value = nama;
            document.getElementById('edit_keterangan').value = keterangan;
            document.getElementById('modalEditLokasi').classList.remove('hidden');
        }

        // Menyembunyikan modal dialog "Edit Lokasi" kembali.
        function closeEditLokasi() {
            document.getElementById('modalEditLokasi').classList.add('hidden');
        }

        /* --- MANAJEMEN MODAL HAPUS LOKASI --- */

        // Menampilkan modal konfirmasi "Hapus Lokasi", memasukkan parameter ID lokasi ke input hidden, 
        // serta mencetak nama lokasi pada elemen teks konfirmasi agar admin tahu lokasi mana yang akan dihapus.
        function openHapusLokasi(id, nama) {
            document.getElementById('hapus_id').value = id;
            document.getElementById('hapus_nama').innerHTML = nama;
            document.getElementById('modalHapusLokasi').classList.remove('hidden');
        }

        // Menyembunyikan modal dialog konfirmasi hapus kembali.
        function closeHapusLokasi() {
            document.getElementById('modalHapusLokasi').classList.add('hidden');
        }
    </script>

    <div id="modalHapusLokasi" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
            <form action="lokasi_hapus.php" method="POST">
                <input type="hidden" name="id" id="hapus_id">
                <div class="p-5 border-b">
                    <h3 class="font-semibold text-xl text-red-600">Hapus Lokasi</h3>
                </div>
                <div class="p-6">
                    <p class="text-slate-600">Apakah Anda yakin ingin menghapus lokasi <strong id="hapus_nama"></strong>?</p>
                    <p class="text-red-500 text-sm mt-2">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="p-5 border-t flex justify-end gap-3">
                    <button type="button" onclick="closeHapusLokasi()" class="px-5 py-2 border rounded-lg">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-lg">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEditLokasi" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl">
            <form action="lokasi_edit.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="p-5 border-b">
                    <h3 class="font-semibold text-xl">Edit Lokasi</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block mb-2">Nama Lokasi</label>
                        <input type="text" name="nama_lokasi" id="edit_nama_lokasi" required class="w-full border rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block mb-2">Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" rows="4" class="w-full border rounded-lg px-4 py-3"></textarea>
                    </div>
                </div>
                <div class="p-5 border-t flex justify-end gap-3">
                    <button type="button" onclick="closeEditLokasi()" class="px-5 py-2 border rounded-lg">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalTambahLokasi" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl">
            <form action="lokasi_tambah.php" method="POST">
                <div class="p-5 border-b">
                    <h3 class="font-semibold text-xl">Tambah Lokasi</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block mb-2">Nama Lokasi</label>
                        <input type="text" name="nama_lokasi" required class="w-full border rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="4" class="w-full border rounded-lg px-4 py-3"></textarea>
                    </div>
                </div>
                <div class="p-5 border-t flex justify-end gap-3">
                    <button type="button" onclick="closeTambahLokasi()" class="px-5 py-2 border rounded-lg">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>