<?php

include "auth.php";
include "../koneksi.php";

$nama = $_SESSION['nama'];

$query = mysqli_query($conn,"
SELECT
categories.*,
COUNT(items.id) AS jumlah_barang
FROM categories
LEFT JOIN items
ON categories.id = items.id_kategori
GROUP BY categories.id
ORDER BY categories.nama ASC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kategori Barang</title>

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
border-radius:16px;
box-shadow:0 1px 3px rgba(0,0,0,.08);
}

</style>

</head>

<body>

<div class="flex min-h-screen">

<!-- SIDEBAR -->

<aside
class="fixed left-0 top-0 w-64 h-screen bg-[#1E3A8A] text-white"
>

<div class="h-full flex flex-col">

<div class="p-5 border-b border-blue-800">

<div class="flex items-center gap-3">

<div
class="w-9 h-9 rounded-lg bg-[#3B82F6] flex items-center justify-center"
>
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

<a href="dashboard_admin.php"class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
<i data-lucide="layout-dashboard" class="w-4 h-4"></i>
Dashboard
</a>

<a href="barang.php"class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
<i data-lucide="package" class="w-4 h-4"></i>
Data Barang
</a>

<a href="kategori.php"class="bg-[#3B82F6] flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium">
<i data-lucide="folder-tree" class="w-4 h-4"></i>
Kategori Barang
</a>

<a href="lokasi.php"class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
<i data-lucide="map-pin" class="w-4 h-4"></i>
Lokasi Penyimpanan
</a>

<a href="users.php"class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
<i data-lucide="users" class="w-4 h-4"></i>
Data User
</a>

<a href="admin_peminjaman.php"class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
<i data-lucide="package-check" class="w-4 h-4"></i>
Peminjaman Barang
</a>

<a href="admin_pengembalian.php"class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
<i data-lucide="rotate-ccw" class="w-4 h-4"></i>
Pengembalian Barang
</a>

<a href="laporan.php"class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
<i data-lucide="file-text" class="w-4 h-4"></i>
Laporan Inventaris
</a>

<a href="aktivitas.php"class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
<i data-lucide="history" class="w-4 h-4"></i>
Riwayat Aktivitas
</a>

<a href="profil_admin.php"class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
<i data-lucide="user" class="w-4 h-4"></i>
Profil
</a>

</div>

</nav>

<div class="p-4 border-t border-blue-800">

<a href="logout.php"class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91] transition-all">

<i
data-lucide="log-out"
class="w-5 h-5 text-white group-hover:text-red-500"
></i>

<span class="group-hover:text-red-500">Logout</span>

</a>

</div>

</div>

</aside>

<!-- CONTENT -->

<div class="ml-[220px] flex-1">

<!-- TOPBAR -->

<header class="h-[60px] bg-white border-b flex items-center justify-between px-6">

<input type="text"placeholder="Cari barang, user, atau aktivitas..."class="w-[350px] border rounded-lg px-4 py-2"/>

<div class="flex items-center gap-4">

<div class="relative">

<i data-lucide="bell"></i>

<span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">5</span>

</div>

<div class="text-right">

<h4 class="font-semibold text-sm">
<?= $nama ?>
</h4>

<p class="text-xs text-slate-500">Administrator</p>

</div>

<div
class="w-10 h-10 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center"
>

<?= strtoupper(substr($nama,0,2)) ?>

</div>

</div>

</header>

<!-- PAGE -->

<main class="p-6">

<div class="flex justify-between items-center mb-6">

<div>

<h1 class="text-3xl font-bold text-slate-800">Kategori Barang</h1>

<p class="text-slate-500">Kelola kategori untuk klasifikasi barang</p>

</div>

<button
onclick="openTambahKategori()"
class="bg-[#1E3A8A] text-white px-5 py-3 rounded-xl flex items-center gap-2">

<i data-lucide="plus"></i>
Tambah Kategori
</button>

</div>

<!-- TABLE -->

<div class="card overflow-hidden">

<table class="w-full">

<thead class="bg-slate-50">

<tr>

<th class="text-left p-5 font-semibold">Nama Kategori</th>
<th class="text-left p-5 font-semibold">Deskripsi</th>
<th class="text-center p-5 font-semibold">Jumlah Barang</th>
<th class="text-center p-5 font-semibold">Aksi</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($query)) : ?>

<tr class="border-t hover:bg-slate-50">

<td class="p-5">

<div class="flex items-center gap-3">

<div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
<i data-lucide="folder-tree" class="w-5 h-5 text-slate-600"></i>

</div>

<div>

<h4 class="font-medium">
<?= htmlspecialchars($row['nama']) ?>
</h4>

</div>

</div>

</td>

<td class="p-5 text-slate-600">

<?= !empty($row['deskripsi'])
? htmlspecialchars($row['deskripsi'])
: '-'; ?>

</td>

<td class="p-5 text-center">

<span class="px-3 py-1 rounded-full bg-blue-100 text-blue-600 text-sm font-medium">

<?= $row['jumlah_barang'] ?> barang

</span>

</td>

<td class="p-5">

<div class="flex justify-center gap-4">

<!-- EDIT -->

<button
onclick='openEditKategori(
<?= $row["id"] ?>,
<?= json_encode($row["nama"]) ?>,
<?= json_encode($row["deskripsi"]) ?>
)'
class="text-green-600 hover:text-green-800"
>

<i data-lucide="pencil" class="w-4 h-4"></i>

</button>

<!-- HAPUS -->

<button
onclick='openHapusKategori(
<?= $row["id"] ?>,
<?= json_encode($row["nama"]) ?>
)'
class="text-red-600 hover:text-red-800">
<i data-lucide="trash-2" class="w-4 h-4"></i>

</button>

</div>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</main>

<!-- MODAL TAMBAH -->

<div
id="modalTambahKategori"
class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">

<div class="bg-white rounded-2xl shadow-xl w-full max-w-xl">

<div class="border-b p-5 flex justify-between items-center">

<h3 class="text-xl font-semibold">Tambah Kategori</h3>
<button onclick="closeTambahKategori()">

<i data-lucide="x"></i>

</button>

</div>

<form action="kategori_tambah.php"
method="POST">

<div class="p-6">

<div class="mb-4">

<label class="block mb-2 font-medium">Nama Kategori</label>

<input
type="text"
name="nama"
required
class="w-full border border-slate-200 rounded-lg px-4 py-3"
placeholder="Masukkan nama kategori">

</div>

<div>

<label class="block mb-2 font-medium">Deskripsi</label>

<textarea
name="deskripsi"
rows="4"
class="w-full border border-slate-200 rounded-lg px-4 py-3"
placeholder="Deskripsi kategori"
></textarea>

</div>

</div>

<div class="border-t p-5 flex justify-end gap-3">

<button
type="button"
onclick="closeTambahKategori()"
class="px-5 py-3 border rounded-lg"
>
Batal
</button>

<button
type="submit"
class="px-5 py-3 bg-[#1E3A8A] text-white rounded-lg">
Simpan
</button>

</div>

</form>

</div>

</div>

<!-- MODAL EDIT -->

<div
id="modalEditKategori"
class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">

<div class="bg-white rounded-2xl shadow-xl w-full max-w-xl">

<div class="border-b p-5 flex justify-between items-center">

<h3 class="text-xl font-semibold">Edit Kategori</h3>

<button onclick="closeEditKategori()">

<i data-lucide="x"></i>

</button>

</div>

<form action="kategori_edit.php"
method="POST">

<input
type="hidden"
name="id"
id="edit_id"
>

<div class="p-6">

<div class="mb-4">

<label class="block mb-2 font-medium">Nama Kategori</label>

<input
type="text"
name="nama"
id="edit_nama"
required
class="w-full border border-slate-200 rounded-lg px-4 py-3"
>

</div>

<div>

<label class="block mb-2 font-medium">Deskripsi</label>

<textarea
name="deskripsi"
id="edit_deskripsi"
rows="4"
class="w-full border border-slate-200 rounded-lg px-4 py-3"
></textarea>

</div>

</div>

<div class="border-t p-5 flex justify-end gap-3">

<button
type="button"
onclick="closeEditKategori()"
class="px-5 py-3 border rounded-lg">
Batal
</button>

<button
type="submit"
class="px-5 py-3 bg-[#1E3A8A] text-white rounded-lg">
Update
</button>

</div>

</form>

</div>

</div>

<!-- MODAL HAPUS -->

<div
id="modalHapusKategori"
class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
>

<div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">

<div class="border-b p-5 flex justify-between items-center">

<h3 class="text-xl font-semibold text-red-600">Konfirmasi Hapus</h3>
<button onclick="closeHapusKategori()">
<i data-lucide="x"></i>
</button>

</div>

<form action="kategori_hapus.php"
method="POST">

<input
type="hidden"
name="id"
id="hapus_id"
>

<div class="p-6">

<p class="text-slate-600">Apakah Anda yakin ingin menghapus kategori<strong id="hapus_nama"></strong> ?</p>
<p class="text-red-500 text-sm mt-2">Data yang dihapus tidak dapat dikembalikan.</p>

</div>

<div class="border-t p-5 flex justify-end gap-3">

<button
type="button"
onclick="closeHapusKategori()"
class="px-5 py-3 border rounded-lg">
Batal
</button>

<button
type="submit"
class="px-5 py-3 bg-red-600 text-white rounded-lg">
Hapus
</button>

</div>

</form>

</div>

</div>

<script>
function openTambahKategori(){
document.getElementById('modalTambahKategori').classList.remove('hidden');
}

function closeTambahKategori(){
document.getElementById('modalTambahKategori').classList.add('hidden');
}

function openEditKategori(id,nama,deskripsi){
document.getElementById('edit_id').value=id;
document.getElementById('edit_nama').value=nama;
document.getElementById('edit_deskripsi').value=deskripsi;
document.getElementById('modalEditKategori').classList.remove('hidden');

}

function closeEditKategori(){
document.getElementById('modalEditKategori').classList.add('hidden');
}

function openHapusKategori(id,nama){
document.getElementById('hapus_id').value=id;
document.getElementById('hapus_nama').innerText=nama;
document.getElementById('modalHapusKategori').classList.remove('hidden');
}

function closeHapusKategori(){
document.getElementById('modalHapusKategori').classList.add('hidden');
}

lucide.createIcons();

</script>

</body>
</html>

