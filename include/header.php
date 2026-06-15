<?php
require_once __DIR__ . '/../auth/auth_check.php';
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris Laboratorium</title>
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
                        primary: '#1e3b8a',         
                        accentBtn: '#3b82f6',       
                        background: '#f4f6f9',      
                        card: '#ffffff',
                        foreground: '#1e293b',
                        muted: '#f8fafc',
                        'muted-foreground': '#64748b',
                        border: '#e2e8f0'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-foreground min-h-screen font-sans antialiased">

    <aside class="fixed top-0 left-0 h-full bg-primary text-white w-64 z-40 border-r border-blue-900 flex flex-col">
        <div class="flex flex-col h-full">
            <div class="p-5 border-b border-blue-900/60">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                        <i data-feather="package" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-sm tracking-wide leading-tight">INVENTARIS LAB</h1>
                        <p class="text-[10px] text-blue-200/80 tracking-wider">Admin Panel</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 py-6 overflow-y-auto">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all 
                            <?= ($current_page == 'index.php' && $current_dir != 'manajemen_user' && $current_dir != 'profil') ? 'bg-accentBtn text-white shadow-md shadow-blue-500/30' : 'text-blue-100/70 hover:bg-white/10 hover:text-white' ?>">
                            <i data-feather="grid" class="w-4 h-4"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <?php if ($_SESSION['role'] === 'Admin'): ?>
                    <li>
                        <a href="../manajemen_user/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all 
                            <?= ($current_dir == 'manajemen_user') ? 'bg-accentBtn text-white shadow-md shadow-blue-500/30' : 'text-blue-100/70 hover:bg-white/10 hover:text-white' ?>">
                            <i data-feather="users" class="w-4 h-4"></i> <span>Data User</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="../profil/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all 
                            <?= ($current_dir == 'profil') ? 'bg-accentBtn text-white shadow-md shadow-blue-500/30' : 'text-blue-100/70 hover:bg-white/10 hover:text-white' ?>">
                            <i data-feather="user" class="w-4 h-4"></i> <span>Profil</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-blue-900/60">
                <a href="../auth/logout.php" class="flex items-center gap-3 px-4 py-3 w-full rounded-xl font-medium text-sm text-white/80 hover:bg-red-500/20 hover:text-red-300 transition-colors">
                    <i data-feather="log-out" class="w-4 h-4"></i> <span>Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <div class="ml-64 transition-all duration-300">
        
        <header class="bg-primary text-white sticky top-0 z-30 shadow-md border-b border-blue-900/40">
            <div class="flex items-center justify-between px-8 py-4">
                <div class="flex items-center gap-4">
                    <button class="p-2 hover:bg-white/10 rounded-lg transition-colors">
                        <i data-feather="menu" class="w-5 h-5 text-white"></i>
                    </button>
                    <h2 class="text-sm font-medium tracking-wide hidden sm:block">
                        Sistem Informasi Manajemen Inventaris
                    </h2>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 pl-4 border-l border-white/20">
                        <div class="text-right">
                            <p class="font-semibold text-sm tracking-wide"><?= htmlspecialchars($_SESSION['nama']) ?></p>
                            <p class="text-[10px] text-blue-200 font-medium tracking-wider uppercase"><?= htmlspecialchars($_SESSION['role']) ?></p>
                        </div>
                        
                        <div class="w-9 h-9 rounded-full bg-accentBtn text-white flex items-center justify-center text-xs font-bold shadow-sm border border-white/20 overflow-hidden">
                            <?php if(!empty($_SESSION['avatar'])): ?>
                                <img src="../<?= htmlspecialchars($_SESSION['avatar']) ?>" class="w-full h-full object-cover" />
                            <?php else: ?>
                                <?= strtoupper(substr($_SESSION['nama'], 0, 2)) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-8">