<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Ambil data dari session dengan fallback jika session kosong (misal belum login)
$header_nama   = $_SESSION['nama'] ?? 'Pengguna';
$header_role   = $_SESSION['role'] ?? 'User';
$header_avatar = $_SESSION['avatar'] ?? '';
?>
<header class="bg-card border-b border-border sticky top-0 z-30 shadow-sm">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center gap-4">
            <button id="sidebar-toggle" class="p-2 hover:bg-muted rounded-lg transition-colors">
                <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-5 h-5">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
            <div class="relative w-96">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                <input type="text" placeholder="Cari barang laboratorium..." class="w-full pl-10 pr-4 py-2 bg-input-background rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-ring/20 transition-all">
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <button class="relative p-2 hover:bg-muted rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell w-5 h-5">
                    <path d="M10.268 21a2 2 0 0 0 3.464 0"></path>
                    <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path>
                </svg>
                <span class="absolute -top-1 -right-1 w-2 h-2 bg-destructive rounded-full"></span>
            </button>

            <div class="flex items-center gap-3 pl-4 border-l border-border">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-foreground leading-none">
                        <?= htmlspecialchars($header_nama) ?>
                    </p>
                    <p class="text-xs text-muted-foreground mt-1 font-medium tracking-wide">
                        <?= htmlspecialchars($header_role) ?>
                    </p>
                </div>
                
                <div class="w-9 h-9 rounded-full bg-muted border border-border flex items-center justify-center overflow-hidden shadow-sm">
                    <?php if (!empty($header_avatar)): ?>
                        <img src="/uploads/avatars/<?= htmlspecialchars($header_avatar) ?>" alt="Avatar" class="w-full h-full object-cover">
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</header>