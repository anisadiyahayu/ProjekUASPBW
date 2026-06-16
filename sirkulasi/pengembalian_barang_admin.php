<?php 
require_once '../auth/auth_check.php';
include "../include/koneksi.php";

$query = mysqli_query($conn, "
    SELECT 
        transactions.*, 
        users.nama AS nama_peminjam, \r
        users.npm AS npm_peminjam, \r
        items.nama AS nama_barang,
        items.satuan AS satuan_barang
    FROM transactions
    LEFT JOIN users ON transactions.id_user = users.id
    LEFT JOIN items ON transactions.id_item = items.id
    WHERE transactions.status = 'Sedang Dipinjam'
    ORDER BY transactions.waktu_pinjam DESC
");
?>
<html>
<head>
    <meta name="color-scheme" content="light dark">
    <title>Manajemen Pengembalian Barang - Admin</title>
    <link rel="stylesheet" href="/include/style_tailwind.css">
    <link rel="stylesheet" href="/include/style_sidebar.css">
</head>

<body>
    <div class="min-h-screen bg-background">
        <?php $current_page = 'pengembalian'; ?>
        <?php include __DIR__ . '/../template/sidebar_admin.php'; ?>

        <div id="main-content" class="transition-all duration-300 ml-64">
            <?php include __DIR__ . '/../template/header.php'; ?>

            <main class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-foreground">Pengembalian Barang</h1>
                        <p class="text-sm text-muted-foreground">Kelola dan validasi pengembalian barang inventaris oleh mahasiswa.</p>
                    </div>
                </div>

                <div class="bg-card rounded-xl border border-border shadow-sm overflow-hidden">
                    <div class="p-6 pb-0">
                        <h2 class="text-lg font-semibold text-foreground">Daftar Barang yang Sedang Dipinjam</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-border text-muted-foreground text-sm font-medium">
                                        <th class="pb-3 font-semibold">Peminjam</th>
                                        <th class="pb-3 font-semibold">Nama Barang</th>
                                        <th class="pb-3 font-semibold text-center">Jumlah</th>
                                        <th class="pb-3 font-semibold">Tanggal Pinjam</th>
                                        <th class="pb-3 font-semibold">Status</th>
                                        <th class="pb-3 font-semibold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border text-sm">
                                    <?php if (mysqli_num_rows($query) == 0) { ?>
                                        <tr>
                                            <td colspan="6" class="py-8 text-center text-muted-foreground">
                                                Tidak ada barang yang sedang dipinjam saat ini.
                                            </td>
                                        </tr>
                                    <?php } else { ?>
                                        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                                            <tr class="hover:bg-muted/50 transition-colors">
                                                <td class="py-4">
                                                    <div class="font-medium text-foreground"><?= htmlspecialchars($row['nama_peminjam']) ?></div>
                                                    <div class="text-xs text-muted-foreground"><?= htmlspecialchars($row['npm_peminjam']) ?></div>
                                                </td>
                                                <td class="py-4 font-medium text-foreground">
                                                    <?= htmlspecialchars($row['nama_barang']) ?>
                                                </td>
                                                <td class="py-4 text-center font-semibold text-foreground">
                                                    <?= htmlspecialchars($row['jumlah']) ?> <span class="text-xs font-normal text-muted-foreground"><?= htmlspecialchars($row['satuan_barang']) ?></span>
                                                </td>
                                                <td class="py-4 text-muted-foreground">
                                                    <?= date('d M Y, H:i', strtotime($row['waktu_pinjam'])) ?>
                                                </td>
                                                <td class="py-4">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/60 dark:border-amber-900/30">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Dipinjam
                                                    </span>
                                                </td>
                                                <td class="py-4 text-right">
                                                    <div class="flex justify-end gap-2">
                                                        <a href="proses_pengembalian.php?id=<?= $row['id'] ?>" 
                                                           onclick="return confirm('Apakah Anda yakin ingin memproses pengembalian barang ini dan mengembalikan stok?')"
                                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium shadow-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check">
                                                                <path d="M20 6 9 17l-5-5"/>
                                                            </svg>
                                                            Proses Kembali
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="/include/script.js"></script>
</body>
</html>