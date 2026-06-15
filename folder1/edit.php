<?php
// edit.php
require_once 'koneksi.php';

// Cek apakah parameter ID tersedia di URL
if (isset($_GET['id'])) {
    // Keamanan: Mencegah SQL Injection pada metode GET
    $id = $conn->real_escape_string($_GET['id']);
    
    // Mengambil data berdasarkan id
    $query = "SELECT * FROM lokasi_barang WHERE id = '$id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Data lokasi tidak ditemukan.");
    }
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lokasi Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Lokasi Barang</h2>
        
        <form action="proses_edit.php" method="POST">
            <!-- Hidden input untuk ID yang akan diubah -->
            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">

            <div class="mb-4">
                <label for="nama_lokasi" class="block text-gray-700 font-bold mb-2">Nama Lokasi</label>
                <input type="text" id="nama_lokasi" name="nama_lokasi" required value="<?= htmlspecialchars($row['nama_lokasi']); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="mb-6">
                <label for="deskripsi" class="block text-gray-700 font-bold mb-2">Deskripsi Lokasi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($row['deskripsi']); ?></textarea>
            </div>
            
            <div class="flex items-center justify-end">
                <a href="index.php" class="text-gray-500 hover:text-gray-700 font-medium mr-4">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</body>
</html>