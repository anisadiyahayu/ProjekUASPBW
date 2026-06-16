<?php 
require_once '../auth/auth_check.php';
include "../include/koneksi.php";

// Query untuk mengambil data transaksi digabung dengan data user dan item
$query = mysqli_query($conn, "
    SELECT 
        transactions.*, 
        users.nama AS nama_peminjam, 
        users.npm AS npm_peminjam, 
        items.nama AS nama_barang 
    FROM transactions
    LEFT JOIN users ON transactions.id_user = users.id
    LEFT JOIN items ON transactions.id_item = items.id
    ORDER BY transactions.waktu_pinjam DESC
");
?>
<html>
<head>
    <meta name="color-scheme" content="light dark">
    <style>
        html,
        body,
        #container,
        #container>div {
            height: 100%;
        }
    </style>
    <link rel="stylesheet" href="/include/style_tailwind.css">
    <link rel="stylesheet" href="/include/style_sidebar.css">
</head>

<body>
    <div class="min-h-screen bg-background">
        <?php $current_page = 'permintaan'; ?>
        <?php if($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Aslab') { include __DIR__ . '/../template/sidebar_admin.php'; 
        } else { include __DIR__ . '/../template/sidebar.php';} ?>
        <div id="main-content" class="transition-all duration-300 ml-64">
            <?php include __DIR__ . '/../template/header.php'; ?>
            <main class="p-6">
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold text-foreground">Permintaan Barang</h1>
                            <p class="text-muted-foreground">Kelola permintaan peminjaman barang laboratorium</p>
                        </div><button class="flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors shadow-md"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-5 h-5">
                                <path d="M5 12h14"></path>
                                <path d="M12 5v14"></path>
                            </svg>Ajukan Permintaan</button>
                    </div>
                    <div class="bg-card rounded-xl shadow-md border border-border p-2">
                        <div class="flex gap-2"><button class="flex-1 px-4 py-2 rounded-lg font-medium transition-colors bg-primary text-primary-foreground">Semua Permintaan</button><button class="flex-1 px-4 py-2 rounded-lg font-medium transition-colors text-muted-foreground hover:bg-muted">Pending</button><button class="flex-1 px-4 py-2 rounded-lg font-medium transition-colors text-muted-foreground hover:bg-muted">Disetujui</button></div>
                    </div>
                    <div class="bg-card rounded-xl shadow-md border border-border overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-muted/50 border-b border-border">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Peminjam</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Barang</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Jumlah</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Keperluan</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Tanggal Pinjam</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Estimasi Kembali</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Status</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border">
                                    <?php if (mysqli_num_rows($query) == 0) { ?>
                                        <tr>
                                            <td colspan="8" class="px-6 py-8 text-center text-sm text-muted-foreground">
                                                Belum ada data permintaan barang.
                                            </td>
                                        </tr>
                                    <?php } else { ?>
                                        <?php while ($data = mysqli_fetch_assoc($query)) { ?>
                                            <tr class="hover:bg-muted/30 transition-colors">
                                                <td class="px-6 py-4">
                                                    <div>
                                                        <p class="font-medium text-foreground"><?= htmlspecialchars($data['nama_peminjam'] ?? 'Tidak Diketahui') ?></p>
                                                        <p class="text-sm text-muted-foreground"><?= htmlspecialchars($data['npm_peminjam'] ?? '-') ?></p>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-foreground">
                                                    <?= htmlspecialchars($data['nama_barang'] ?? 'Barang Terhapus') ?>
                                                </td>
                                                <td class="px-6 py-4 text-sm font-semibold text-foreground">
                                                    <?= htmlspecialchars($data['jumlah']) ?>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-muted-foreground">
                                                    <?= htmlspecialchars($data['keperluan'] ?? '-') ?>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-muted-foreground">
                                                    <?= date('Y-m-d', strtotime($data['waktu_pinjam'])) ?>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-muted-foreground">
                                                    <?= date('Y-m-d', strtotime($data['estimasi_kembali'])) ?>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <?php 
                                                    $status = $data['status'];
                                                    if ($status === 'Menunggu') { ?>
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-3 h-3">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <polyline points="12 6 12 12 16 14"></polyline>
                                                            </svg> Menunggu</span>
                                                    <?php } elseif ($status === 'Disetujui') { ?>
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big w-3 h-3">
                                                                <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                                                                <path d="m9 11 3 3L22 4"></path>
                                                            </svg> Disetujui</span>
                                                    <?php } elseif ($status === 'Ditolak') { ?>
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x w-3 h-3">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <path d="m15 9-6 6"></path>
                                                                <path d="m9 9 6 6"></path>
                                                            </svg> Ditolak</span>
                                                    <?php } elseif ($status === 'Sedang Dipinjam') { ?>
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package w-3 h-3">
                                                                <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                                                                <path d="M12 22V12"></path>
                                                                <polyline points="3.29 7 12 12 20.71 7"></polyline>
                                                                <path d="m7.5 4.27 9 5.15"></path>
                                                            </svg> Dipinjam</span>
                                                    <?php } else { ?>
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                                            <?= htmlspecialchars($status) ?>
                                                        </span>
                                                    <?php } ?>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-2">
                                                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4">
                                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg></button>
                                                        <?php if ($status === 'Menunggu') { ?>
                                                            <button class="px-3 py-1 text-sm bg-green-50 text-green-600 hover:bg-green-100 rounded-lg transition-colors">Validasi</button>
                                                        <?php } ?>
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