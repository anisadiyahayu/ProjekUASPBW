<?php
// index.php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

// Mengambil data dari tabel locations diurutkan dari yang terbaru
$query = "SELECT * FROM locations ORDER BY created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokasi Penyimpanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../include/style_tailwind.css">
    <link rel="stylesheet" href="../include/style_sidebar.css">
</head>

<body>
    <div class="min-h-screen bg-background">
        <?php $current_page = 'Location'; ?>
        <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Aslab') {
            include __DIR__ . '/../template/sidebar_admin.php';
        } else {
            include __DIR__ . '/../template/sidebar.php';
        } ?>
        <div id="main-content" class="transition-all duration-300 ml-64">
            <?php include __DIR__ . '/../template/header.php'; ?>
            <main class="p-8">
                <div class="max-w-6xl mx-auto">

                    <!-- Header Section -->
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-[#1e293b] mb-1">Lokasi Penyimpanan</h1>
                            <p class="text-slate-500 text-sm">Kelola lokasi penyimpanan barang laboratorium</p>
                        </div>
                        <a href="tambahlokasi.php" class="bg-[#1e3a8a] hover:bg-[#172554] text-white font-medium py-2.5 px-5 rounded-lg flex items-center gap-2 transition-colors shadow-sm">
                            <!-- Icon Plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah Lokasi
                        </a>
                    </div>

                    <!-- Alert Notification (Pesan Sukses) -->
                    <?php if (isset($_GET['pesan'])): ?>
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative mb-6 flex items-center gap-2 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <span class="block sm:inline font-medium">
                                <?php
                                if ($_GET['pesan'] == 'tambah_sukses') echo "Lokasi berhasil ditambahkan!";
                                elseif ($_GET['pesan'] == 'edit_sukses') echo "Lokasi berhasil diperbarui!";
                                elseif ($_GET['pesan'] == 'hapus_sukses') echo "Lokasi berhasil dihapus!";
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <!-- Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Mengambil id lokasi saat ini
                                $id_lokasi_sekarang = $row['id'];
                                
                                // Query untuk menghitung jumlah barang berdasarkan id_lokasi
                                $query_total = "SELECT COUNT(*) as total FROM items WHERE id_lokasi = '$id_lokasi_sekarang'";
                                $result_total = $conn->query($query_total);
                                $row_total = $result_total->fetch_assoc();
                                
                                // Memasukkan hasil count ke dalam variabel $total_barang
                                $total_barang = $row_total['total'];
                        ?>
                                <!-- Card Item -->
                                <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 hover:shadow-md transition-shadow">

                                    <!-- Bagian Atas Card (Ikon & Teks) -->
                                    <div class="flex items-start gap-4 mb-5">
                                        <div class="w-14 h-14 rounded-xl bg-[#f1f5f9] flex items-center justify-center text-[#1e3a8a] shrink-0">
                                            <!-- Icon Map Pin -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                        </div>
                                        <div class="overflow-hidden pt-1">
                                            <h3 class="text-[1.1rem] font-bold text-slate-800 truncate leading-tight mb-1">
                                                <?= htmlspecialchars($row['nama']); ?>
                                            </h3>
                                            <p class="text-slate-500 text-[13px] line-clamp-2 leading-snug">
                                                <?= htmlspecialchars($row['keterangan']); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Garis Pemisah -->
                                    <hr class="border-slate-100 my-4">

                                    <!-- Bagian Bawah Card (Jumlah Barang & Aksi) -->
                                    <div class="flex justify-between items-center pt-1">
                                        <div class="text-sm">
                                            <span class="font-bold text-slate-800 text-base"><?= $total_barang; ?></span>
                                            <span class="text-slate-500 ml-1">barang</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <!-- Tombol Edit (Hijau) -->
                                            <a href="edit.php?id=<?= $row['id']; ?>" class="text-[#10b981] hover:bg-green-50 p-1.5 rounded-lg transition-colors" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 20h9"></path>
                                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                                </svg>
                                            </a>
                                            <!-- Tombol Hapus (Merah) -->
                                            <a href="hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')" class="text-[#ef4444] hover:bg-red-50 p-1.5 rounded-lg transition-colors" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <!-- Empty State jika data kosong -->
                            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-100 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-slate-300 mb-3">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <p class="text-slate-500 font-medium">Belum ada lokasi penyimpanan.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
    <script src="../include/script.js"></script>
</body>

</html>