<?php
// Pastikan session sudah dimulai jika menggunakan variabel $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// require_once __DIR__ . '/../auth/auth_check.php'; // Uncomment ini sesuai dengan sistem auth Anda
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Inventory - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        // Warna disesuaikan dengan palet pada gambar UI
                        primarySidebar: '#243e8a', // Biru gelap sidebar
                        activeMenu: '#3b82f6',     // Biru terang menu aktif
                        background: '#f4f7fb',
                        foreground: '#1e293b',
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar untuk sidebar agar rapi */
        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-background text-foreground min-h-screen font-sans antialiased">

    <!-- SIDEBAR KIRI -->
    <aside class="fixed top-0 left-0 h-full bg-primarySidebar text-white w-64 z-40 flex flex-col shadow-xl">
        <div class="flex flex-col h-full">
            
            <!-- Bagian Logo -->
            <div class="p-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0 shadow-sm">
                        <i data-feather="box" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-[1.15rem] tracking-wide leading-tight">Lab Inventory</h1>
                        <p class="text-[11px] text-blue-200 tracking-wider mt-0.5">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- Bagian Navigasi Menu -->
            <nav class="flex-1 py-4 overflow-y-auto sidebar-scroll">
                <ul class="space-y-1 px-3">
                    
                    <li>
                        <a href="../dashboard/index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-sm transition-all 
                            <?= ($current_dir == 'dashboard') ? 'bg-activeMenu text-white shadow-md' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
                            <i data-feather="grid" class="w-5 h-5"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="../data_barang/index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-sm transition-all 
                            <?= ($current_dir == 'data_barang') ? 'bg-activeMenu text-white shadow-md' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
                            <i data-feather="package" class="w-5 h-5"></i> <span>Data Barang</span>
                        </a>
                    </li>

                    <li>
                        <a href="../kategori_barang/index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-sm transition-all 
                            <?= ($current_dir == 'kategori_barang') ? 'bg-activeMenu text-white shadow-md' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
                            <i data-feather="layers" class="w-5 h-5"></i> <span>Kategori Barang</span>
                        </a>
                    </li>

                    <li>
                        <!-- Anda bisa menyesuaikan nama folder "lokasi_penyimpanan" sesuai nama folder XAMPP Anda -->
                        <a href="../Location/index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-sm transition-all 
                            <?= ($current_dir == 'lokasi_penyimpanan' || $current_dir == 'folder1') ? 'bg-activeMenu text-white shadow-md' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
                            <i data-feather="map-pin" class="w-5 h-5"></i> <span>Lokasi Penyimpanan</span>
                        </a>
                    </li>

                    <li>
                        <a href="../data_user/index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-sm transition-all 
                            <?= ($current_dir == 'data_user' || $current_dir == 'manajemen_user') ? 'bg-activeMenu text-white shadow-md' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
                            <i data-feather="users" class="w-5 h-5"></i> <span>Data User</span>
                        </a>
                    </li>

                    <li>
                        <a href="../peminjaman/index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-sm transition-all 
                            <?= ($current_dir == 'peminjaman') ? 'bg-activeMenu text-white shadow-md' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
                            <i data-feather="inbox" class="w-5 h-5"></i> <span>Peminjaman Barang</span>
                        </a>
                    </li>

                    <li>
                        <a href="../pengembalian/index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-sm transition-all 
                            <?= ($current_dir == 'pengembalian') ? 'bg-activeMenu text-white shadow-md' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
                            <i data-feather="refresh-cw" class="w-5 h-5"></i> <span>Pengembalian Barang</span>
                        </a>
                    </li>

                    <li>
                        <a href="../laporan/index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-sm transition-all 
                            <?= ($current_dir == 'laporan') ? 'bg-activeMenu text-white shadow-md' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
                            <i data-feather="file-text" class="w-5 h-5"></i> <span>Laporan Inventaris</span>
                        </a>
                    </li>

                </ul>
            </nav>

            <!-- Bagian Logout -->
            <div class="p-4 bg-black/10">
                <a href="../auth/logout.php" class="flex items-center gap-3 px-4 py-3 w-full rounded-lg font-medium text-sm text-blue-100 hover:bg-white/10 hover:text-white transition-colors">
                    <i data-feather="log-out" class="w-5 h-5"></i> <span>Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- KONTEN UTAMA (Kanan) -->
    <div class="ml-64 transition-all duration-300">
        
        <!-- TOPBAR ATAS (Search & Profil Putih) -->
        <header class="bg-white sticky top-0 z-30 shadow-sm border-b border-slate-200">
            <div class="flex items-center justify-between px-8 py-3.5">
                
                <!-- Kiri: Menu Toggle & Search Bar -->
                <div class="flex items-center gap-4 flex-1">
                    <button class="p-2 hover:bg-slate-100 text-slate-500 rounded-lg transition-colors md:hidden">
                        <i data-feather="menu" class="w-5 h-5"></i>
                    </button>
                    
                    <div class="relative w-full max-w-md hidden md:block">
                        <i data-feather="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" placeholder="Cari barang, user, atau aktivitas..." 
                               class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-slate-700">
                    </div>
                </div>
                
                <!-- Kanan: Notifikasi & User Profil -->
                <div class="flex items-center gap-5">
                    
                    <button class="relative p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-full transition-colors">
                        <i data-feather="bell" class="w-5 h-5"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    <div class="flex items-center gap-3 pl-5 border-l border-slate-200 cursor-pointer">
                        <div class="text-right hidden sm:block">
                            <!-- Menampilkan nama, fallback jika belum diset -->
                            <p class="font-semibold text-sm text-slate-800 tracking-wide"><?= isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Admin Laboratorium' ?></p>
                            <p class="text-[11px] text-slate-500 font-medium"><?= isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'Administrator' ?></p>
                        </div>
                        
                        <div class="w-10 h-10 rounded-full bg-primarySidebar text-white flex items-center justify-center text-sm font-bold shadow-sm overflow-hidden">
                            <?php if(!empty($_SESSION['avatar'])): ?>
                                <img src="../<?= htmlspecialchars($_SESSION['avatar']) ?>" class="w-full h-full object-cover" />
                            <?php else: ?>
                                <?= strtoupper(substr(isset($_SESSION['nama']) ? $_SESSION['nama'] : 'AL', 0, 2)) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- KONTEN FITUR MUNCUL DI SINI -->
        <main class="p-8">