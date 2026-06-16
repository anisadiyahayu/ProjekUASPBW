<?php
// Pastikan path ini sesuai dengan letak file koneksi Anda
require_once '../include/koneksi.php';

// ========================================================================
// LOGIKA READ (R) & COUNT BARANG
// Menggunakan LEFT JOIN untuk menghitung jumlah barang di setiap lokasi
// ========================================================================
$query = "
    SELECT l.id, l.nama, l.keterangan, l.created_at, COUNT(i.id) AS total_barang
    FROM locations l
    LEFT JOIN items i ON l.id = i.id_lokasi
    GROUP BY l.id, l.nama, l.keterangan, l.created_at
    ORDER BY l.created_at DESC
";
$result = $conn->query($query);

// Panggil Header
require_once '../include/header.php';
?>

<div class="max-w-6xl mx-auto space-y-6">
    
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 mb-1">Lokasi Penyimpanan</h1>
            <p class="text-slate-500 text-sm">Kelola lokasi penyimpanan barang laboratorium</p>
        </div>
        <a href="tambahlokasi.php" class="bg-[#1e3b8a] hover:bg-blue-900 text-white font-medium py-2.5 px-5 rounded-lg flex items-center gap-2 transition-colors shadow-sm text-sm">
            <i data-feather="plus" class="w-4 h-4"></i>
            Tambah Lokasi
        </a>
    </div>

    <!-- Alert Notification (Pesan Sukses) -->
    <?php if (isset($_GET['pesan'])): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative mb-6 flex items-center gap-2 shadow-sm">
            <i data-feather="check-circle" class="w-5 h-5 text-green-500"></i>
            <span class="block sm:inline font-medium text-sm">
                <?php 
                    if($_GET['pesan'] == 'tambah_sukses') echo "Lokasi berhasil ditambahkan!";
                    elseif($_GET['pesan'] == 'edit_sukses') echo "Lokasi berhasil diperbarui!";
                    elseif($_GET['pesan'] == 'hapus_sukses') echo "Lokasi berhasil dihapus!";
                ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php 
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { 
                // total_barang sekarang otomatis terisi dari hasil query COUNT()
                $total_barang = $row['total_barang']; 
        ?>
            <!-- Card Item -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                
                <!-- Bagian Atas Card (Ikon & Teks) -->
                <div class="flex items-start gap-4 mb-5">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-[#1e3b8a] shrink-0 border border-slate-200">
                        <i data-feather="map-pin" class="w-5 h-5"></i>
                    </div>
                    <div class="overflow-hidden pt-1">
                        <h3 class="text-lg font-bold text-slate-800 truncate leading-tight mb-1">
                            <?= htmlspecialchars($row['nama']); ?>
                        </h3>
                        <p class="text-slate-500 text-[13px] line-clamp-2 leading-snug">
                            <?= htmlspecialchars($row['keterangan'] ?? 'Tidak ada keterangan'); ?>
                        </p>
                    </div>
                </div>
                
                <!-- Garis Pemisah -->
                <hr class="border-slate-100 my-4">
                
                <!-- Bagian Bawah Card (Jumlah Barang & Aksi) -->
                <div class="flex justify-between items-center pt-1">
                    <div class="text-sm flex items-baseline gap-1.5">
                        <span class="font-bold text-slate-800 text-base"><?= number_format($total_barang); ?></span> 
                        <span class="text-slate-500 text-sm">barang</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- Tombol Edit (Hijau) -->
                        <a href="edit.php?id=<?= $row['id']; ?>" class="text-[#10b981] hover:bg-green-50 p-1.5 rounded-lg transition-colors" title="Edit Lokasi">
                            <i data-feather="edit-2" class="w-4 h-4"></i>
                        </a>
                        <!-- Tombol Hapus (Merah) -->
                        <a href="hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus lokasi <?= htmlspecialchars($row['nama']) ?>?')" class="text-[#ef4444] hover:bg-red-50 p-1.5 rounded-lg transition-colors" title="Hapus Lokasi">
                            <i data-feather="trash-2" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

            </div>
        <?php 
            }
        } else { 
        ?>
            <!-- Empty State jika data kosong -->
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-200 shadow-sm">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <i data-feather="map" class="w-8 h-8"></i>
                </div>
                <p class="text-slate-500 font-medium">Belum ada lokasi penyimpanan.</p>
            </div>
        <?php } ?>
    </div>
</div>

<?php 
// Panggil Footer
require_once '../include/footer.php'; 
?>