<?php
require_once __DIR__ . '/../auth/auth_check.php';

$header_nama   = $_SESSION['nama']   ?? '';
$header_role   = $_SESSION['role']   ?? '';
$header_avatar = $_SESSION['avatar'] ?? '';
$current_dir   = basename(dirname($_SERVER['PHP_SELF']));
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
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        primary:                    '#1e3b8a',
                        accentBtn:                  '#3b82f6',
                        background:                 '#f4f6f9',
                        card:                       '#ffffff',
                        foreground:                 '#1e293b',
                        muted:                      '#f8fafc',
                        'muted-foreground':         '#64748b',
                        border:                     '#e2e8f0',
                        'input-background':         '#f8fafc',
                        sidebar:                    '#1e3b8a',
                        'sidebar-foreground':       '#ffffff',
                        'sidebar-border':           'rgba(255,255,255,0.1)',
                        'sidebar-primary':          '#3b82f6',
                        'sidebar-primary-foreground': '#ffffff',
                        'sidebar-accent':           'rgba(255,255,255,0.1)',
                        'sidebar-accent-foreground':'#ffffff',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-foreground min-h-screen font-sans antialiased">

<?php
if ($header_role === 'Admin' || $header_role === 'Aslab') {
    require_once __DIR__ . '/sidebar_admin.php';
} else {
    require_once __DIR__ . '/sidebar.php';
}
?>

<div class="ml-64 transition-all duration-300">
    <header class="bg-card border-b border-border sticky top-0 z-30 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="p-2 hover:bg-muted rounded-lg transition-colors">
                    <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x w-5 h-5">
                        <path d="M18 6 6 18"></path><path d="m6 6 12 12"></path>
                    </svg>
                </button>
                <div class="relative w-96">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground">
                        <circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path>
                    </svg>
                    <input type="text" placeholder="Cari barang laboratorium..."
                        class="w-full pl-10 pr-4 py-2 bg-input-background rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button class="relative p-2 hover:bg-muted rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-bell w-5 h-5">
                        <path d="M10.268 21a2 2 0 0 0 3.464 0"></path>
                        <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path>
                    </svg>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <div class="flex items-center gap-3 pl-4 border-l border-border">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-foreground leading-none"><?= htmlspecialchars($header_nama) ?></p>
                        <p class="text-xs text-muted-foreground mt-1 font-medium tracking-wide"><?= htmlspecialchars($header_role) ?></p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-muted border border-border flex items-center justify-center overflow-hidden shadow-sm">
                        <?php if (!empty($header_avatar)): ?>
                            <img src="../<?= htmlspecialchars($header_avatar) ?>" alt="Avatar" class="w-full h-full object-cover">
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="text-muted-foreground">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="p-8">