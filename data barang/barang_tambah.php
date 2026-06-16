<?php

include "auth.php";
include "koneksi.php";

$nama = $_SESSION['nama'];

$kategori = mysqli_query($conn, "
SELECT *
FROM categories
ORDER BY nama ASC
");

$lokasi = mysqli_query($conn, "
SELECT *
FROM locations
ORDER BY nama_lokasi ASC
");

$laboratorium = mysqli_query($conn, "
SELECT *
FROM laboratorium
ORDER BY nama_lab ASC
");

if (isset($_POST['simpan'])) {
    $kode_barang = mysqli_real_escape_string($conn, $_POST['kode_barang']);
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $id_kategori = $_POST['id_kategori'];
    $id_lokasi = $_POST['id_lokasi'];
    $id_lab = $_POST['id_lab'];
    $stok = $_POST['stok'];
    $kondisi = $_POST['kondisi'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $gambar = '';

    if (!empty($_FILES['gambar']['name'])) {
        $ext = strtolower(
            pathinfo(
                $_FILES['gambar']['name'],
                PATHINFO_EXTENSION
            )
        );

        $gambar = time() . '_' . rand(1000, 9999) . '.' . $ext;

        move_uploaded_file(
            $_FILES['gambar']['tmp_name'],
            'uploads/' . $gambar
        );
    }

    mysqli_query($conn, "
    INSERT INTO items
    (
    kode_barang,
    nama_barang,
    id_kategori,
    id_lokasi,
    id_lab,
    stok,
    kondisi,
    gambar,
    deskripsi,
    created_at
    )
    VALUES
    (
    '$kode_barang',
    '$nama_barang',
    '$id_kategori',
    '$id_lokasi',
    '$id_lab',
    '$stok',
    '$kondisi',
    '$gambar',
    '$deskripsi',
    NOW()
    )
    ");

    echo "
    <script>
    alert('Barang berhasil ditambahkan');
    location='barang.php';
    </script>
    ";
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f1f5f9;
        }

        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
        }
    </style>
</head>

<body>

    <aside class="fixed left-0 top-0 w-64 h-screen bg-[#1E3A8A] text-white">
        <div class="h-full flex flex-col">
            <div class="p-5 border-b border-blue-800">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-[#3B82F6] flex items-center justify-center">
                        <i data-lucide="box" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h1 class="font-semibold">Lab Inventory</h1>
                        <p class="text-xs text-blue-200">Admin Panel</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-2 py-4">
                <div class="space-y-1">
                    <a href="dashboard_admin.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        Dashboard
                    </a>
                    <a href="barang.php" class="bg-[#3B82F6] flex items-center gap-3 px-4 py-3 rounded-lg text-sm">
                        <i data-lucide="package" class="w-4 h-4"></i>
                        Data Barang
                    </a>
                    <a href="kategori.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="folder-tree" class="w-4 h-4"></i>
                        Kategori Barang
                    </a>
                    <a href="lokasi.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        Lokasi Penyimpanan
                    </a>
                    <a href="users.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        Data User
                    </a>
                </div>
            </nav>

            <div class="p-4 border-t border-blue-800">
                <a href="logout.php" class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91]">
                    <i data-lucide="log-out" class="w-5 h-5 group-hover:text-red-500"></i>
                    <span class="group-hover:text-red-500">Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <div class="ml-64">
        <header class="bg-white border-b h-[56px] px-6 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-semibold">Tambah Barang</h1>
                <p class="text-sm text-slate-500">Tambahkan inventaris baru ke sistem</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right">
                    <h4 class="text-sm font-semibold"><?= htmlspecialchars($nama) ?></h4>
                    <p class="text-xs text-slate-500">Administrator</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center font-semibold">
                    <?= strtoupper(substr($nama, 0, 2)) ?>
                </div>
            </div>
        </header>

        <main class="p-6">
            <div class="card p-6">
                <form method="POST" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium mb-2">Kode Barang</label>
                            <input type="text" name="kode_barang" required class="w-full border border-slate-200 rounded-lg px-4 py-3" placeholder="BRG-001">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Nama Barang</label>
                            <input type="text" name="nama_barang" required class="w-full border border-slate-200 rounded-lg px-4 py-3" placeholder="Masukkan nama barang">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Kategori</label>
                            <select name="id_kategori" required class="w-full border border-slate-200 rounded-lg px-4 py-3">
                                <option value="">Pilih Kategori</option>
                                <?php while ($k = mysqli_fetch_assoc($kategori)) : ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nama'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Lokasi</label>
                            <select name="id_lokasi" required class="w-full border border-slate-200 rounded-lg px-4 py-3">
                                <option value="">Pilih Lokasi</option>
                                <?php while ($l = mysqli_fetch_assoc($lokasi)) : ?>
                                    <option value="<?= $l['id'] ?>"><?= $l['nama_lokasi'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Laboratorium</label>
                            <select name="id_lab" required class="w-full border border-slate-200 rounded-lg px-4 py-3">
                                <option value="">Pilih Laboratorium</option>
                                <?php while ($lab = mysqli_fetch_assoc($laboratorium)) : ?>
                                    <option value="<?= $lab['id'] ?>"><?= $lab['nama_lab'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Stok</label>
                            <input type="number" name="stok" required min="0" class="w-full border border-slate-200 rounded-lg px-4 py-3" placeholder="0">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Kondisi</label>
                            <select name="kondisi" required class="w-full border border-slate-200 rounded-lg px-4 py-3">
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Gambar Barang</label>
                            <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp" class="w-full border border-slate-200 rounded-lg px-4 py-3">
                        </div>
                    </div>

                    <div class="mt-5">
                        <label class="block text-sm font-medium mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="5" class="w-full border border-slate-200 rounded-lg px-4 py-3" placeholder="Deskripsi barang..."></textarea>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit" name="simpan" class="bg-[#1E3A8A] hover:bg-[#16306d] text-white px-6 py-3 rounded-lg">
                            Simpan Barang
                        </button>
                        <a href="barang.php" class="border border-slate-200 px-6 py-3 rounded-lg hover:bg-slate-50">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>