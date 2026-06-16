<?php
include "../auth.php";
include "../koneksi.php";

$id_user = $_SESSION['id'];

// Mengambil data admin yang sedang login
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id_user'");
$user = mysqli_fetch_assoc($query);
$nama = $user['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: #F1F5F9;
        }
    </style>
</head>
<body>

    <div class="flex min-h-screen">
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
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                        </a>
                        <a href="barang.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="package" class="w-4 h-4"></i> Data Barang
                        </a>
                        <a href="kategori.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="folder-tree" class="w-4 h-4"></i> Kategori Barang
                        </a>
                        <a href="lokasi.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="map-pin" class="w-4 h-4"></i> Lokasi Penyimpanan
                        </a>
                        <a href="users.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="users" class="w-4 h-4"></i> Data User
                        </a>
                        <a href="admin_peminjaman.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="package-check" class="w-4 h-4"></i> Peminjaman Barang
                        </a>
                        <a href="admin_pengembalian.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Pengembalian Barang
                        </a>
                        <a href="laporan.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="file-text" class="w-4 h-4"></i> Laporan Inventaris
                        </a>
                        <a href="aktivitas.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="history" class="w-4 h-4"></i> Riwayat Aktivitas
                        </a>
                        <a href="profil_admin.php" class="bg-[#3B82F6] flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium">
                            <i data-lucide="user" class="w-4 h-4"></i> Profil
                        </a>
                    </div>
                </nav>

                <div class="p-4 border-t border-blue-800">
                    <a href="logout.php" class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91]">
                        <i data-lucide="log-out" class="w-5 h-5 text-white group-hover:text-red-500"></i>
                        <span class="group-hover:text-red-500">Logout</span>
                    </a>
                </div>
            </div>
        </aside>

        <div class="ml-64 flex-1">
            <header class="h-[60px] bg-white border-b flex justify-between items-center px-6">
                <input type="text" placeholder="Cari barang, user, atau aktivitas..." class="w-[350px] border rounded-lg px-4 py-2">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <i data-lucide="bell"></i>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">5</span>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-sm"><?= htmlspecialchars($nama); ?></div>
                        <div class="text-xs text-slate-500">Administrator</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center font-semibold">
                        <?= strtoupper(substr($nama, 0, 2)); ?>
                    </div>
                </div>
            </header>

            <main class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800">Profil</h1>
                        <p class="text-slate-500">Kelola informasi profil Anda</p>
                    </div>
                    <button class="bg-[#1E3A8A] text-white px-5 py-3 rounded-xl">
                        Edit Profil
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow overflow-hidden mb-6">
                    <div class="h-28 bg-gradient-to-r from-[#1E3A8A] to-[#2563EB]"></div>
                    <div class="px-6 pb-6">
                        <div class="flex items-start gap-5 -mt-12">
                            <div class="w-24 h-24 bg-white rounded-full shadow flex items-center justify-center">
                                <i data-lucide="user-circle-2" class="w-16 h-16 text-[#1E3A8A]"></i>
                            </div>
                            <div class="pt-10">
                                <h2 class="text-3xl font-bold"><?= htmlspecialchars($user['nama']); ?></h2>
                                <p class="text-slate-500"><?= ucfirst($user['role']); ?></p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-10 mt-8">
                            <div class="space-y-5">
                                <div>
                                    <p class="text-sm text-slate-500">NPM / NIDN</p>
                                    <p class="font-semibold"><?= htmlspecialchars($profile['npm'] ?? '') ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">No. Telepon</p>
                                    <p class="font-semibold"><?= htmlspecialchars($profile['no_hp'] ?? '') ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Kelas</p>
                                    <p class="font-semibold"><?= htmlspecialchars($profile['kelas'] ?? '') ?></p>
                                </div>
                            </div>
                            <div class="space-y-5">
                                <div>
                                    <p class="text-sm text-slate-500">Email</p>
                                    <p class="font-semibold"><?= htmlspecialchars($user['email']); ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Angkatan</p>
                                    <p class="font-semibold"><?= htmlspecialchars($profile['angkatan'] ?? '') ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Tanggal Bergabung</p>
                                    <p class="font-semibold"><?= date('Y-m-d', strtotime($user['created_at'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow p-6">
                    <h3 class="text-xl font-bold mb-5">Ubah Password</h3>
                    <form action="update_password.php" method="POST">
                        <div class="max-w-md space-y-4">
                            <div>
                                <label class="block mb-2 text-sm">Password Lama</label>
                                <input type="password" name="password_lama" class="w-full border rounded-lg px-4 py-3" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm">Password Baru</label>
                                <input type="password" name="password_baru" class="w-full border rounded-lg px-4 py-3" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm">Konfirmasi Password Baru</label>
                                <input type="password" name="konfirmasi" class="w-full border rounded-lg px-4 py-3" required>
                            </div>
                            <button type="submit" class="bg-[#1E3A8A] text-white px-5 py-3 rounded-xl">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>