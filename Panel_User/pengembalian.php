<?php
include "auth.php";
include "../include/koneksi.php";

$nama = $_SESSION['nama'];
$id_user = $_SESSION['user_id'] ?? $_SESSION['id'];

// Menampilkan data yang statusnya 'Sedang Dipinjam' / Disetujui untuk dikembalikan
$query = mysqli_query($conn,"
SELECT
    transactions.*,
    items.nama AS nama_barang
FROM transactions
LEFT JOIN items ON transactions.id_item = items.id
WHERE transactions.id_user = '$id_user' 
AND (transactions.status = 'Sedang Dipinjam' OR transactions.status = 'disetujui')
ORDER BY transactions.id DESC
");

if(!$query){
    die(mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Barang</title>
    <link rel="stylesheet" href="../include/style_tailwind.css">
    <link rel="stylesheet" href="../include/style_sidebar.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    <div class="min-h-screen flex">
        <?php $current_page = 'pengembalian'; ?>
        <?php include '../template/sidebar.php'; ?>

        <div id="main-content" class="flex-1 min-w-0 flex flex-col transition-all duration-300 ml-64">
            <?php include '../template/header.php'; ?>

            <main class="p-6 flex-1 max-w-7xl w-full mx-auto space-y-6">
                
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Barang Dipinjam</h1>
                    <p class="text-sm text-slate-500 mt-1">Daftar instrumen yang saat ini masih Anda pinjam.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600">
                                    <th class="px-6 py-4 font-semibold">Nama Barang</th>
                                    <th class="px-6 py-4 font-semibold text-center">Jumlah</th>
                                    <th class="px-6 py-4 font-semibold">Tanggal Pinjam</th>
                                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                                    <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php while($row = mysqli_fetch_assoc($query)) : ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-800"><?= htmlspecialchars($row['nama_barang']) ?></td>
                                    <td class="px-6 py-4 text-center font-semibold text-slate-700"><?= htmlspecialchars($row['jumlah']) ?></td>
                                    <td class="px-6 py-4 text-slate-500"><?= date('d M Y', strtotime($row['waktu_pinjam'] ?? $row['tanggal_pinjam'])) ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Sedang Dipinjam
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="proses_pengembalian.php?id=<?= $row['id'] ?>" onclick="return confirm('Ajukan pengembalian untuk barang ini?')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg shadow-sm transition-all focus:outline-none">
                                            <i data-lucide="corner-down-left" class="w-3.5 h-3.5"></i> Kembalikan
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                
                                <?php if(mysqli_num_rows($query) == 0): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i data-lucide="check-circle" class="w-10 h-10 text-slate-300"></i>
                                            <p>Anda tidak memiliki barang yang sedang dipinjam saat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="../include/script.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
    <script>lucide.createIcons();</script>
</body>
</html>