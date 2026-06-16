<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

// Mengambil data Kategori & Lokasi ke Array untuk Dropdown Filter
$kategori_data = [];
$q_kat = $conn->query("SELECT id, nama FROM categories ORDER BY nama ASC");
if ($q_kat) {
    while ($row = $q_kat->fetch_assoc()) $kategori_data[] = $row;
}

$lokasi_data = [];
$q_lok = $conn->query("SELECT id, nama FROM locations ORDER BY nama ASC");
if ($q_lok) {
    while ($row = $q_lok->fetch_assoc()) $lokasi_data[] = $row;
}

?>

<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../include/style_tailwind.css">
<link rel="stylesheet" href="../include/style_sidebar.css">
<div class="min-h-screen bg-background">
    <?php $current_page = 'laporan'; ?>
    <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Aslab') {
        include __DIR__ . '/../template/sidebar_admin.php';
    } else {
        include __DIR__ . '/../template/sidebar.php';
    } ?>
    <div id="main-content" class="transition-all duration-300 ml-64">
        <?php include __DIR__ . '/../template/header.php'; ?>
        <div class="space-y-6 max-w-full mx-auto pb-10 p-8">

            <!-- Bagian Judul -->
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-1">Laporan Inventaris</h1>
                <p class="text-slate-500 text-sm">Buat dan kelola laporan inventaris laboratorium</p>
            </div>

            <!-- Form Pembungkus -->
            <form id="formLaporan" action="#" method="GET" class="space-y-6">

                <!-- Hidden input untuk menampung tipe laporan yang dipilih -->
                <input type="hidden" name="tipe_laporan" id="tipe_laporan" value="permintaan">

                <!-- 1. KARTU PILIHAN TIPE LAPORAN -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                    <!-- Card 1 -->
                    <div class="report-card cursor-pointer bg-white rounded-xl p-5 border-2 border-slate-100 hover:border-blue-200 transition-all flex gap-4 items-start" data-type="data_barang">
                        <div class="icon-box w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 shrink-0 transition-colors">
                            <i data-feather="file-text" class="w-6 h-6"></i>
                        </div>
                        <div class="pt-1">
                            <h3 class="font-bold text-slate-800 mb-1 leading-tight">Laporan Data Barang</h3>
                            <p class="text-xs text-slate-500 leading-snug">Laporan lengkap semua barang di laboratorium</p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="report-card cursor-pointer bg-white rounded-xl p-5 border-2 border-slate-100 hover:border-blue-200 transition-all flex gap-4 items-start" data-type="barang_masuk">
                        <div class="icon-box w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 shrink-0 transition-colors">
                            <i data-feather="file-text" class="w-6 h-6"></i>
                        </div>
                        <div class="pt-1">
                            <h3 class="font-bold text-slate-800 mb-1 leading-tight">Laporan Barang Masuk</h3>
                            <p class="text-xs text-slate-500 leading-snug">Laporan barang yang masuk ke inventaris</p>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="report-card cursor-pointer bg-white rounded-xl p-5 border-2 border-slate-100 hover:border-blue-200 transition-all flex gap-4 items-start" data-type="barang_keluar">
                        <div class="icon-box w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 shrink-0 transition-colors">
                            <i data-feather="file-text" class="w-6 h-6"></i>
                        </div>
                        <div class="pt-1">
                            <h3 class="font-bold text-slate-800 mb-1 leading-tight">Laporan Barang Keluar</h3>
                            <p class="text-xs text-slate-500 leading-snug">Laporan barang yang keluar/dipinjam</p>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="report-card cursor-pointer bg-white rounded-xl p-5 border-2 border-slate-100 hover:border-blue-200 transition-all flex gap-4 items-start" data-type="stok_barang">
                        <div class="icon-box w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 shrink-0 transition-colors">
                            <i data-feather="file-text" class="w-6 h-6"></i>
                        </div>
                        <div class="pt-1">
                            <h3 class="font-bold text-slate-800 mb-1 leading-tight">Laporan Stok Barang</h3>
                            <p class="text-xs text-slate-500 leading-snug">Laporan status stok barang saat ini</p>
                        </div>
                    </div>

                    <!-- Card 5 (Akan di-set Aktif secara default via JS sesuai gambar) -->
                    <div class="report-card cursor-pointer bg-white rounded-xl p-5 border-2 border-slate-100 hover:border-blue-200 transition-all flex gap-4 items-start" data-type="permintaan">
                        <div class="icon-box w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 shrink-0 transition-colors">
                            <i data-feather="file-text" class="w-6 h-6"></i>
                        </div>
                        <div class="pt-1">
                            <h3 class="font-bold text-slate-800 mb-1 leading-tight">Laporan Permintaan Barang</h3>
                            <p class="text-xs text-slate-500 leading-snug">Laporan riwayat permintaan peminjaman</p>
                        </div>
                    </div>

                </div>

                <!-- 2. FILTER LAPORAN -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 flex items-center gap-2">
                        <i data-feather="calendar" class="w-5 h-5 text-slate-700"></i>
                        <h3 class="font-bold text-slate-800 text-lg">Filter Laporan</h3>
                    </div>
                    <div class="p-6 pt-2">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm text-slate-700">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Akhir</label>
                                <input type="date" name="tgl_akhir" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm text-slate-700">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori Barang</label>
                                <select name="kategori" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm bg-white text-slate-700">
                                    <option value="all">Semua Kategori</option>
                                    <?php foreach ($kategori_data as $k): ?>
                                        <option value="<?= htmlspecialchars($k['id']) ?>"><?= htmlspecialchars($k['nama']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Lokasi Penyimpanan</label>
                                <select name="lokasi" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm bg-white text-slate-700">
                                    <option value="all">Semua Lokasi</option>
                                    <?php foreach ($lokasi_data as $l): ?>
                                        <option value="<?= htmlspecialchars($l['id']) ?>"><?= htmlspecialchars($l['nama']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. AKSI LAPORAN (Tombol) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-lg">Aksi Laporan</h3>
                    </div>
                    <div class="p-6 flex flex-wrap items-center gap-4">
                        <button type="button" class="flex-1 min-w-[200px] flex items-center justify-center gap-2 py-3 px-4 rounded-xl font-bold text-sm transition-all bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-100">
                            <i data-feather="eye" class="w-4 h-4"></i> Preview Laporan
                        </button>
                        <button type="button" class="flex-1 min-w-[200px] flex items-center justify-center gap-2 py-3 px-4 rounded-xl font-bold text-sm transition-all bg-green-50 text-green-600 hover:bg-green-100 border border-green-100">
                            <i data-feather="printer" class="w-4 h-4"></i> Cetak Laporan
                        </button>
                        <button type="button" class="flex-1 min-w-[200px] flex items-center justify-center gap-2 py-3 px-4 rounded-xl font-bold text-sm transition-all bg-red-50 text-red-500 hover:bg-red-100 border border-red-100">
                            <i data-feather="download" class="w-4 h-4"></i> Export PDF
                        </button>
                        <button type="button" class="flex-1 min-w-[200px] flex items-center justify-center gap-2 py-3 px-4 rounded-xl font-bold text-sm transition-all bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-100">
                            <i data-feather="file-text" class="w-4 h-4"></i> Export Excel
                        </button>
                    </div>
                </div>

                <!-- 4. PREVIEW LAPORAN (Kosong Default) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-lg">Preview Laporan</h3>
                    </div>
                    <div class="p-12 flex flex-col items-center justify-center text-center bg-slate-50/50 min-h-[350px]">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-slate-400 mb-4 bg-slate-200/50">
                            <i data-feather="file-text" class="w-8 h-8"></i>
                        </div>
                        <p class="text-slate-600 font-medium mb-1">Preview laporan akan ditampilkan di sini setelah Anda mengklik tombol "Preview Laporan"</p>
                        <p class="text-slate-400 text-sm">Pilih rentang tanggal dan filter yang sesuai, kemudian klik Preview</p>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace();
</script>
<script src="../include/script.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.report-card');
        const hiddenInput = document.getElementById('tipe_laporan');

        // Fungsi klik kartu
        cards.forEach(card => {
            card.addEventListener('click', () => {
                // Hapus status aktif dari semua kartu
                cards.forEach(c => {
                    c.classList.remove('border-[#1e3b8a]', 'bg-blue-50/20', 'shadow-md');
                    c.classList.add('border-slate-100');
                    const iconBox = c.querySelector('.icon-box');
                    iconBox.classList.remove('bg-[#1e3b8a]', 'text-white');
                    iconBox.classList.add('bg-slate-50', 'text-slate-400');
                });

                // Berikan status aktif ke kartu yang diklik (Biru gelap seperti sidebar)
                card.classList.remove('border-slate-100');
                card.classList.add('border-[#1e3b8a]', 'bg-blue-50/20', 'shadow-md');

                const iconBox = card.querySelector('.icon-box');
                iconBox.classList.remove('bg-slate-50', 'text-slate-400');
                iconBox.classList.add('bg-[#1e3b8a]', 'text-white');

                // Simpan tipe laporan ke hidden input form
                hiddenInput.value = card.dataset.type;
            });
        });

        // Meniru Gambar: Buat Kartu "Laporan Permintaan Barang" (Index ke-4) aktif secara default
        if (cards.length > 4) {
            cards[4].click();
        }
    });
</script>

