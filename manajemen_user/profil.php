<?php
include "../auth.php";
include "../koneksi.php";

$id = $_SESSION['id'];

// Mengambil data user yang sedang login
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
$user = mysqli_fetch_assoc($query);
$nama = $user['nama'];

// Logika Proses Ubah Password
if (isset($_POST['ubah_password'])) {
    $lama = $_POST['lama'];
    $baru = $_POST['baru'];
    $konfirmasi = $_POST['konfirmasi'];

    if ($lama != $user['password']) {
        echo "<script>alert('Password lama salah');</script>";
    } elseif ($baru != $konfirmasi) {
        echo "<script>alert('Konfirmasi password tidak sama');</script>";
    } else {
        mysqli_query($conn, "UPDATE users SET password = '$baru' WHERE id = '$id'");
        echo "
        <script>
            alert('Password berhasil diubah');
            location='profil.php';
        </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    
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
            <div class="p-4 border-b border-blue-800">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#3B82F6] flex items-center justify-center">
                        <i data-lucide="box" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h1 class="font-semibold text-sm">Lab Inventory</h1>
                        <p class="text-[10px] text-blue-200">Student Portal</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 py-3">
                <ul class="space-y-1 px-2">
                    <li>
                        <a href="dashboard_mahasiswa.php" class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="katalog.php" class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="package" class="w-4 h-4"></i> Katalog Barang
                        </a>
                    </li>
                    <li>
                        <a href="peminjaman.php" class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i> Peminjaman Barang
                        </a>
                    </li>
                    <li>
                        <a href="pengembalian.php" class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Pengembalian Barang
                        </a>
                    </li>
                    <li>
                        <a href="riwayat.php" class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="history" class="w-4 h-4"></i> Riwayat Peminjaman
                        </a>
                    </li>
                    <li>
                        <a href="profil.php" class="bg-[#3B82F6] flex items-center gap-2 px-3 py-3 rounded-lg text-sm">
                            <i data-lucide="user" class="w-4 h-4"></i> Profil Saya
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-blue-800">
                <a href="logout.php" class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91] transition-all duration-300">
                    <i data-lucide="log-out" class="w-5 h-5 text-white group-hover:text-red-500"></i>
                    <span class="font-medium text-white group-hover:text-red-500">Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <div class="ml-64">
        <header class="bg-white border-b h-[52px] px-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <button>
                    <i data-lucide="x" class="w-4 h-4 text-slate-500"></i>
                </button>
                <div class="relative w-[270px]">
                    <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"></i>
                    <input type="text" placeholder="Cari barang laboratorium..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm">
                </div>
            </div>

            <div class="flex items-center gap-5">
                <div class="relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">2</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <h4 class="text-sm font-semibold"><?= htmlspecialchars($nama); ?></h4>
                        <p class="text-[11px] text-slate-500">Mahasiswa</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center text-sm font-semibold">
                        <?= strtoupper(substr($nama, 0, 2)); ?>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-[20px] font-semibold text-slate-800">Profil</h1>
                    <p class="text-sm text-slate-500">Kelola informasi profil Anda</p>
                </div>
                <button class="bg-[#1E3A8A] hover:bg-[#16306d] text-white px-4 py-2 rounded-lg text-sm">
                    Edit Profil
                </button>
            </div>

            <div class="card overflow-hidden mb-6">
                <div class="h-24 bg-gradient-to-r from-[#1E3A8A] to-[#2563EB]"></div>
                <div class="px-6 pb-6">
                    <div class="flex items-start gap-5 -mt-10">
                        <div class="w-24 h-24 rounded-full bg-white shadow-lg flex items-center justify-center">
                            <i data-lucide="user-circle-2" class="w-16 h-16 text-[#1E3A8A]"></i>
                        </div>
                        <div class="pt-10">
                            <h2 class="text-3xl font-semibold"><?= htmlspecialchars($user['nama']); ?></h2>
                            <p class="text-slate-500 capitalize"><?= htmlspecialchars($user['role']); ?></p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-8 mt-8">
                        <div>
                            <div class="mb-5">
                                <p class="text-xs text-slate-500 mb-1">NPM</p>
                                <p class="font-medium"><?= htmlspecialchars($user['npm']); ?></p>
                            </div>
                            <div class="mb-5">
                                <p class="text-xs text-slate-500 mb-1">Email</p>
                                <p class="font-medium"><?= htmlspecialchars($user['email']); ?></p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-5">
                                <p class="text-xs text-slate-500 mb-1">Role</p>
                                <p class="font-semibold capitalize"><?= htmlspecialchars($user['role']); ?></p>
                            </div>
                            <div class="mb-5">
                                <p class="text-xs text-slate-500 mb-1">Tanggal Bergabung</p>
                                <p class="font-medium"><?= date('d F Y', strtotime($user['created_at'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-semibold text-lg mb-5">Ubah Password</h3>
                <form method="POST">
                    <div class="max-w-md space-y-4">
                        <div>
                            <label class="text-sm block mb-2">Password Lama</label>
                            <input type="password" name="lama" class="w-full border rounded-lg px-4 py-3" required>
                        </div>
                        <div>
                            <label class="text-sm block mb-2">Password Baru</label>
                            <input type="password" name="baru" class="w-full border rounded-lg px-4 py-3" required>
                        </div>
                        <div>
                            <label class="text-sm block mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="konfirmasi" class="w-full border rounded-lg px-4 py-3" required>
                        </div>
                        <button type="submit" name="ubah_password" class="bg-[#1E3A8A] hover:bg-[#16306d] text-white px-5 py-3 rounded-lg">
                            Update Password
                        </button>
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