<?php 
require_once '../auth/auth_check.php';
require_once '../include/koneksi.php';
?>

<html>
<head>
    <link rel="stylesheet" href="../include/style_tailwind.css">
    <link rel="stylesheet" href="../include/style_sidebar.css">
</head>

<body>
    <div class="min-h-screen bg-background">
        <?php $current_page = 'profil'; ?>
        <?php if(($_SESSION['role'] === 'Admin') || $_SESSION['role'] === 'Aslab') { include __DIR__ . '/../template/sidebar_admin.php'; 
        } else { include __DIR__ . '/../template/sidebar.php';} ?>
        <div id="main-content" class="transition-all duration-300 ml-64">
            <?php include __DIR__ . '/../template/header.php'; ?>
            <main class="p-6">
                <?php
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->bind_param("s", $_SESSION['user_id']);
                $stmt->execute();
                $myProfile = $stmt->get_result()->fetch_assoc();

                $msg = $_GET['msg'] ?? '';
                ?>
                <div class="space-y-6">
                    <?php if ($msg == 'success'): ?>
                        <div class="p-4 bg-green-100 text-green-700 rounded-lg">Profil berhasil diperbarui.</div>
                    <?php elseif ($msg == 'pass_success'): ?>
                        <div class="p-4 bg-green-100 text-green-700 rounded-lg">Password berhasil diubah.</div>
                    <?php elseif ($msg == 'pass_err'): ?>
                        <div class="p-4 bg-red-100 text-red-700 rounded-lg">Password lama salah atau konfirmasi tidak cocok.</div>
                    <?php endif; ?>

                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold text-foreground">Profil</h1>
                            <p class="text-muted-foreground">Kelola informasi profil Anda</p>
                        </div>
                    </div>

                    <div class="bg-card rounded-xl shadow-md border overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-800 h-32"></div>
                        <form action="edit.php" method="POST" enctype="multipart/form-data" class="px-8 pb-8">
                            <div class="flex items-end gap-6 -mt-16 mb-6">
                                <div class="w-32 h-32 rounded-full bg-card border-4 border-card shadow-xl flex items-center justify-center overflow-hidden relative group">
                                    <?php if (!empty($myProfile['avatar'])): ?>
                                        <img src="../<?= htmlspecialchars($myProfile['avatar']) ?>" class="w-full h-full object-cover" />
                                    <?php else: ?>
                                        <i data-feather="user" class="w-20 h-20 text-gray-400"></i>
                                    <?php endif; ?>
                                    <label class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center cursor-pointer">
                                        <i data-feather="camera" class="text-white"></i>
                                        <input type="file" name="avatar" class="hidden" accept="image/jpeg, image/png">
                                    </label>
                                </div>
                                <div class="pb-2">
                                    <h2 class="text-2xl font-bold"><?= htmlspecialchars($myProfile['nama']) ?></h2>
                                    <p class="text-muted-foreground"><?= htmlspecialchars($myProfile['role']) ?></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-muted-foreground">Nama Lengkap</label>
                                    <input type="text" name="nama" value="<?= htmlspecialchars($myProfile['nama'] ?? '') ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium mb-2 text-muted-foreground"><i data-feather="mail" class="w-4 h-4"></i> Email</label>
                                    <input type="email" name="email" value="<?= htmlspecialchars($myProfile['email'] ?? '') ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium mb-2 text-muted-foreground"><i data-feather="phone" class="w-4 h-4"></i> No. Telepon</label>
                                    <input type="text" name="no_hp" value="<?= htmlspecialchars($myProfile['no_hp'] ?? '') ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-medium mb-2 text-muted-foreground"><i data-feather="hash" class="w-4 h-4"></i> Kelas & Angkatan</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="kelas" value="<?= htmlspecialchars($myProfile['kelas'] ?? '') ?>" placeholder="Kelas" class="w-1/2 px-4 py-2 border rounded-lg">
                                        <input type="number" name="angkatan" value="<?= htmlspecialchars($myProfile['angkatan'] ?? '') ?>" placeholder="Angkatan" class="w-1/2 px-4 py-2 border rounded-lg">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="flex items-center gap-2 px-6 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-700">
                                    <i data-feather="save" class="w-4 h-4"></i> Simpan Profil
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-card rounded-xl shadow-md border p-6">
                        <h3 class="font-semibold text-lg mb-4">Ubah Password</h3>
                        <form action="change_pw.php" method="POST" class="space-y-4 max-w-lg">
                            <div>
                                <label class="block text-sm font-medium mb-2">Password Lama</label>
                                <input type="password" name="old_pass" required class="w-full px-4 py-2 border rounded-lg focus:ring-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Password Baru</label>
                                <input type="password" name="new_pass" required class="w-full px-4 py-2 border rounded-lg focus:ring-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Konfirmasi Password Baru</label>
                                <input type="password" name="confirm_pass" required class="w-full px-4 py-2 border rounded-lg focus:ring-2">
                            </div>
                            <button type="submit" class="px-6 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-700">
                                Update Password
                            </button>
                        </form>
                    </div>
                </div> 
            </main>
        </div>
    </div>
    <script src="../include/script.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>

</html>