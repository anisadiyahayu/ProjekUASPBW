<?php
// index.php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modul 3 - Lokasi Barang</title>
    <!-- Link CSS Bootstrap 5.3.8 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Memanggil file CSS Custom -->
    <link href="style.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="header-title mb-1">📍 Data Lokasi Penyimpanan</h3>
            <p class="text-muted small mb-0">Manajemen Lokasi Inventaris Laboratorium</p>
        </div>
        <a href="tambah.php" class="btn btn-primary btn-custom-action shadow-sm">+ Tambah Lokasi</a>
    </div>

    <div class="card card-custom">
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="py-3">Nama Lokasi</th>
                        <th class="py-3">Keterangan</th>
                        <th class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Mengambil data dari tabel locations, diurutkan dari yang terbaru
                    $query = $conn->query("SELECT * FROM locations ORDER BY created_at DESC");
                    
                    if($query && $query->num_rows > 0) {
                        while($row = $query->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="px-4 fw-medium text-secondary"><?= $no++ ?></td>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($row['nama']) ?></td>
                        <td class="text-muted"><?= htmlspecialchars($row['keterangan']) ?: '<em>Tidak ada keterangan</em>' ?></td>
                        <td class="text-center">
                            <!-- Tombol Edit & Hapus yang mengarah ke halamannya masing-masing beserta ID -->
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary btn-custom-action px-3 me-1">Edit</a>
                            <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger btn-custom-action px-3" onclick="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                    } else {
                        // Tampilan jika data lokasi masih kosong
                        echo "<tr><td colspan='4' class='text-center py-5 text-muted'>Belum ada data lokasi yang terdaftar.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Link JS Bootstrap 5.3.8 Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<!-- Memanggil file Custom JS -->
<script src="script.js"></script>
</body>
</html>