<?php
// tambahlokasi.php
include "../include/header.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lokasi Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Lokasi Barang Baru</h2>
        
        <form action="proses_tambah.php" method="POST">
            <div class="mb-4">
                <label for="nama" class="block text-gray-700 font-bold mb-2">Nama Lokasi</label>
                <input type="text" id="nama" name="nama" required placeholder="Contoh: Lemari Kaca 01" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="mb-6">
                <label for="keterangan" class="block text-gray-700 font-bold mb-2">Deskripsi Lokasi</label>
                <textarea id="keterangan" name="keterangan" rows="4" required placeholder="Contoh: Lokasi untuk alat-alat gelas kimia"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            
            <div class="flex items-center justify-end">
                <a href="index.php" class="text-gray-500 hover:text-gray-700 font-medium mr-4">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</body>
</html>