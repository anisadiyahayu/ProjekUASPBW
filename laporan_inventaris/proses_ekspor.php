<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';
include '../admin_inventaris_lab/fungsi_stok.php'; // Digunakan untuk fungsi statusStok() jika diperlukan

// Ambil parameter filter jika ada
$kategori_filter = $_GET['kategori'] ?? '';
$lokasi_filter   = $_GET['lokasi'] ?? '';
$kondisi_filter  = $_GET['kondisi'] ?? '';

// Ambil data untuk opsi filter
$query_kategori = $conn->query("SELECT * FROM categories ORDER BY nama ASC");
$query_lokasi   = $conn->query("SELECT * FROM locations ORDER BY nama ASC");

// Susun Query data barang berdasarkan filter
$where_clauses = [];
if (!empty($kategori_filter)) {
    $where_clauses[] = "items.id_kategori = '" . $conn->real_escape_string($kategori_filter) . "'";
}
if (!empty($lokasi_filter)) {
    $where_clauses[] = "items.id_lokasi = '" . $conn->real_escape_string($lokasi_filter) . "'";
}
if (!empty($kondisi_filter)) {
    $where_clauses[] = "items.kondisi = '" . $conn->real_escape_string($kondisi_filter) . "'";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where_clauses);
}

// Jalankan query penarikan data utama
$sql_barang = "
    SELECT items.*, categories.nama AS nama_kategori, locations.nama AS nama_lokasi
    FROM items
    LEFT JOIN categories ON categories.id = items.id_kategori
    LEFT JOIN locations ON locations.id = items.id_lokasi
    $where_sql
    ORDER BY items.kode ASC
";
$result_barang = $conn->query($sql_barang);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Lab</title>
    <link rel="stylesheet" href="../include/style_tailwind.css">
    <link rel="stylesheet" href="../include/style_sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-['Poppins']">

    <div class="min-h-screen flex">
        <?php $current_page = 'laporan'; ?>
        <?php if ($_SESSION['role'] === 'Admin' || ($_SESSION['role'] === 'Aslab')) {
            include __DIR__ . '/../template/sidebar_admin.php';
        } else {
            include __DIR__ . '/../template/sidebar.php';
        } ?>
        <div id=\"main-content\" class="flex-1 min-w-0 flex flex-col transition-all duration-300 ml-64">
            <?php include __DIR__ . '/../template/header.php'; ?>

            <main class="p-6 flex-1 max-w-7xl w-full mx-auto">
                
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Laporan Inventaris Barang</h1>
                        <p class="text-sm text-slate-500 mt-1">Cetak, filter, dan pantau status ketersediaan logistik laboratorium.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-sm font-medium rounded-xl shadow-sm transition-all cursor-pointer">
                            <i class="fa-solid fa-print text-slate-400"></i> Cetak Halaman
                        </button>
                        <a href="proses_ekspor.php?kategori=<?= $kategori_filter ?>&lokasi=<?= $lokasi_filter ?>&kondisi=<?= $kondisi_filter ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-sm transition-all">
                            <i class="fa-solid fa-file-pdf"></i> Ekspor PDF / Dokumen
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6 print:hidden">
                    <h2 class="text-sm font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-indigo-500"></i> Filter Parameter Laporan
                    </h2>
                    <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                        
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-slate-600">Kategori</label>
                            <select name="kategori" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="">Semua Kategori</option>
                                <?php while ($kat = $query_kategori->fetch_assoc()): ?>
                                    <option value="<?= $kat['id'] ?>" <?= $kategori_filter == $kat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($kat['nama']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-slate-600">Lokasi Penyimpanan</label>
                            <select name="lokasi" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="">Semua Lokasi</option>
                                <?php while ($lok = $query_lokasi->fetch_assoc()): ?>
                                    <option value="<?= $lok['id'] ?>" <?= $lokasi_filter == $lok['id'] ? 'selected' : '' ?>><?= htmlspecialchars($lok['nama']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-slate-600">Kondisi Barang</label>
                            <select name="kondisi" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="">Semua Kondisi</option>
                                <option value="Bagus" <?= $kondisi_filter == 'Bagus' ? 'selected' : '' ?>>Bagus</option>
                                <option value="Rusak" <?= $kondisi_filter == 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-all shadow-sm shadow-indigo-100 cursor-pointer">
                                <i class="fa-solid fa-filter mr-1"></i> Terapkan
                            </button>
                            <?php if(!empty($kategori_filter) || !empty($lokasi_filter) || !empty($kondisi_filter)): ?>
                                <a href="laporan_inventaris.php" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm transition-all text-center" title="Reset Filter">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 text-xs font-semibold uppercase tracking-wider">
                                    <th class="px-6 py-4 text-center w-16">No</th>
                                    <th class="px-6 py-4">Kode</th>
                                    <th class="px-6 py-4">Nama Barang</th>
                                    <th class="px-6 py-4">Kategori</th>
                                    <th class="px-6 py-4">Lokasi</th>
                                    <th class="px-6 py-4 text-center">Stok</th>
                                    <th class="px-6 py-4 text-center">Kondisi</th>
                                    <th class="px-6 py-4 text-center">Status Stok</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-600 font-normal">
                                <?php 
                                $no = 1;
                                if ($result_barang && $result_barang->num_rows > 0):
                                    while ($b = $result_barang->fetch_assoc()): 
                                        // Memproses relasi penentuan status stok menggunakan fungsi helper bawaan
                                        $status = statusStok((int)$b['stok'], (int)$b['stok_minimum']);
                                        
                                        // Badge styling logic
                                        if ($status === 'Tersedia') {
                                            $badge_stok = 'bg-emerald-50 text-emerald-700 border-emerald-200/60';
                                        } elseif ($status === 'Menipis') {
                                            $badge_stok = 'bg-amber-50 text-amber-700 border-amber-200/60';
                                        } else {
                                            $badge_stok = 'bg-rose-50 text-rose-700 border-rose-200/60';
                                        }
                                ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 text-center text-slate-400 font-medium"><?= $no++; ?></td>
                                        <td class="px-6 py-4 font-mono text-xs text-slate-900 font-semibold uppercase"><?= htmlspecialchars($b['kode']); ?></td>
                                        <td class="px-6 py-4 font-medium text-slate-900">
                                            <div class="flex items-center gap-3">
                                                <?php if(!empty($b['foto_barang'])): ?>
                                                    <img src="../uploads/<?= htmlspecialchars($b['foto_barang']); ?>" alt="" class="w-8 h-8 rounded-lg object-cover border border-slate-100">
                                                <?php else: ?>
                                                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 text-xs"><i class="fa-solid fa-box"></i></div>
                                                <?php endif; ?>
                                                <span><?= htmlspecialchars($b['nama']); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500"><?= htmlspecialchars($b['nama_kategori'] ?? 'Tanpa Kategori'); ?></td>
                                        <td class="px-6 py-4 text-slate-500">
                                            <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-location-dot text-slate-400 text-xs"></i> <?= htmlspecialchars($b['nama_lokasi'] ?? 'Belum Diatur'); ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-center font-semibold text-slate-800"><?= $b['stok'] ?> <span class="text-xs text-slate-400 font-normal"><?= htmlspecialchars($b['satuan']) ?></span></td>
                                        <td class="px-6 py-4 text-center">
                                            <?php if ($b['kondisi'] === 'Bagus'): ?>
                                                <span class="inline-flex items-center gap-1 text-emerald-600 text-xs font-medium"><i class="fa-solid fa-circle text-[6px]"></i> Bagus</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 text-rose-600 text-xs font-medium"><i class="fa-solid fa-circle text-[6px]"></i> Rusak</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-block px-2.5 py-1 text-xs font-medium rounded-lg border <?= $badge_stok ?>">
                                                <?= $status ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php 
                                    endwhile; 
                                else:
                                ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-12 text-slate-400">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <i class="fa-solid fa-box-open text-2xl text-slate-300"></i>
                                                <p class="text-sm">Data tidak ditemukan atau tidak ada barang yang sesuai filter.</p>
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
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
        <script>
            feather.replace();
        </script>
    <script src="../include/script.js"></script>
</body>
</html>