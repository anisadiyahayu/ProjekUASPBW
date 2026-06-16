<?php
include "auth.php";
include "../include/koneksi.php";

$nama = $_SESSION['nama'];
$id_user = $_SESSION['user_id'] ?? $_SESSION['id']; // Pastikan sesuai dengan nama session id anda

$status_filter = $_GET['status'] ?? '';
$where = '';

if($status_filter != ''){
    $where = " AND transactions.status='$status_filter'";
}

$query = mysqli_query($conn,"
SELECT
    transactions.*,
    users.nama,
    users.npm,
    items.nama AS nama_barang
FROM transactions
LEFT JOIN users ON transactions.id_user = users.id
LEFT JOIN items ON transactions.id_item = items.id
WHERE transactions.id_user = '$id_user' $where
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
    <title>Peminjaman Barang</title>
    <link rel="stylesheet" href="../include/style_tailwind.css">
    <link rel="stylesheet" href="../include/style_sidebar.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    <div class="min-h-screen flex">
        <?php $current_page = 'peminjaman'; ?>
        <?php include '../template/sidebar.php'; ?>

        <div id="main-content" class="flex-1 min-w-0 flex flex-col transition-all duration-300 ml-64">
            <?php include '../template/header.php'; ?>

            <main class="p-6 flex-1 max-w-7xl w-full mx-auto space-y-6">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Status Peminjaman</h1>
                        <p class="text-sm text-slate-500 mt-1">Pantau status persetujuan barang yang sedang Anda ajukan.</p>
                    </div>

                    <form action="" method="GET" class="flex items-center gap-2">
                        <select name="status" class="px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Status --</option>
                            <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Sedang Dipinjam" <?= $status_filter == 'Sedang Dipinjam' ? 'selected' : '' ?>>Disetujui / Dipinjam</option>
                            <option value="Ditolak" <?= $status_filter == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        </select>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600">
                                    <th class="px-6 py-4 font-semibold">Nama Barang</th>
                                    <th class="px-6 py-4 font-semibold text-center">Jumlah</th>
                                    <th class="px-6 py-4 font-semibold">Tanggal Pengajuan</th>
                                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                                    <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php while($row = mysqli_fetch_assoc($query)) : ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-800"><?= htmlspecialchars($row['nama_barang']) ?></td>
                                    <td class="px-6 py-4 text-center font-semibold text-slate-700"><?= htmlspecialchars($row['jumlah']) ?></td>
                                    <td class="px-6 py-4 text-slate-500"><?= date('d M Y, H:i', strtotime($row['waktu_pinjam'])) ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if($row['status'] == 'Pending'): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-600 border border-amber-200">Menunggu</span>
                                        <?php elseif($row['status'] == 'Sedang Dipinjam' || $row['status'] == 'Disetujui'): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-200">Disetujui</span>
                                        <?php elseif($row['status'] == 'Ditolak'): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-600 border border-rose-200">Ditolak</span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-50 text-slate-600 border border-slate-200"><?= htmlspecialchars($row['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="openDetail('<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['npm'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['nama_barang'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['jumlah'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['keterangan'] ?? '-', ENT_QUOTES) ?>', '<?= htmlspecialchars($row['status'], ENT_QUOTES) ?>')" class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-lg transition-colors focus:outline-none" title="Detail Permintaan">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                
                                <?php if(mysqli_num_rows($query) == 0): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        Tidak ada catatan peminjaman dengan status tersebut.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="modalDetail" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 items-center justify-center p-4 transition-all">
                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col">
                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2"><i data-lucide="info" class="w-5 h-5 text-blue-500"></i> Detail Permintaan</h3>
                            <button onclick="closeDetail()" class="text-slate-400 hover:text-slate-600 focus:outline-none"><i data-lucide="x" class="w-5 h-5"></i></button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 space-y-3 text-sm">
                                <p class="flex items-start"><span class="w-24 font-semibold text-slate-600 shrink-0">Peminjam</span><span class="text-slate-800"><span id="d_nama"></span> (<span id="d_npm"></span>)</span></p>
                                <p class="flex items-start"><span class="w-24 font-semibold text-slate-600 shrink-0">Barang</span><span id="d_barang" class="text-slate-800 font-medium"></span></p>
                                <p class="flex items-start"><span class="w-24 font-semibold text-slate-600 shrink-0">Jumlah</span><span id="d_jumlah" class="text-slate-800"></span></p>
                                <p class="flex items-start"><span class="w-24 font-semibold text-slate-600 shrink-0">Keperluan</span><span id="d_keperluan" class="text-slate-600 leading-relaxed"></span></p>
                            </div>
                            <div class="flex items-center gap-3 pt-2">
                                <span class="text-sm font-semibold text-slate-600">Status Saat Ini:</span>
                                <span id="d_status"></span>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                            <button onclick="closeDetail()" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors shadow-sm">Tutup</button>
                        </div>
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
    <script>
        lucide.createIcons();

        function openDetail(nama, npm, barang, jumlah, keperluan, status){
            document.getElementById('d_nama').innerText = nama;
            document.getElementById('d_npm').innerText = npm;
            document.getElementById('d_barang').innerText = barang;
            document.getElementById('d_jumlah').innerText = jumlah;
            document.getElementById('d_keperluan').innerText = keperluan;

            let badge = '';
            if(status.toLowerCase() === 'pending' || status.toLowerCase() === 'menunggu'){
                badge = '<span class="bg-amber-100 text-amber-700 border border-amber-200 px-3 py-1 rounded-full text-xs font-semibold">Menunggu Validasi</span>';
            } else if(status.toLowerCase() === 'sedang dipinjam' || status.toLowerCase() === 'disetujui') {
                badge = '<span class="bg-emerald-100 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-xs font-semibold">Disetujui / Dipinjam</span>';
            } else if(status.toLowerCase() === 'ditolak') {
                badge = '<span class="bg-rose-100 text-rose-700 border border-rose-200 px-3 py-1 rounded-full text-xs font-semibold">Ditolak</span>';
            } else {
                badge = '<span class="bg-blue-100 text-blue-700 border border-blue-200 px-3 py-1 rounded-full text-xs font-semibold">Selesai</span>';
            }
            document.getElementById('d_status').innerHTML = badge;

            const modal = document.getElementById('modalDetail');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDetail(){
            const modal = document.getElementById('modalDetail');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</body>
</html> 