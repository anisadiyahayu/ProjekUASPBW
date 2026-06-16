<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

$search = $_GET['search'] ?? '';
$role = $_GET['role'] ?? 'all';

$query = "SELECT * FROM users WHERE (nama LIKE ? OR npm LIKE ?)";
$params = ["%" . $search . "%", "%" . $search . "%"];
$types = "ss";

if ($role !== 'all') {
    $query .= " AND role = ?";
    $params[] = $role;
    $types .= "s";
}
$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$users = $stmt->get_result();
?>

<html>

<head>
    <link rel="stylesheet" href="/include/style_tailwind.css">
    <link rel="stylesheet" href="/include/style_sidebar.css">
</head>

<body>
    <div class="min-h-screen bg-background">
        <?php $current_page = 'manajemen_user'; ?>
        <?php if ($_SESSION['role'] === 'Admin') {
            include __DIR__ . '/../template/sidebar_admin.php';
        } else {
            include __DIR__ . '/../template/sidebar.php';
        } ?>
        <div id="main-content" class="transition-all duration-300 ml-64">
            <?php include __DIR__ . '/../template/header.php'; ?>
            <div class="p-6">
                <div class="flex items-center justify-between p-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-foreground">Data User</h1>
                        <p class="text-muted-foreground">Kelola pengguna sistem laboratorium</p>
                    </div>
                    <button onclick="openTambahModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-[#3b82f6] text-white rounded-lg hover:bg-blue-600 transition-colors font-medium text-sm shadow-sm cursor-pointer">
                        <i data-feather="plus" class="w-4 h-4"></i> Tambah User
                    </button>
                </div>

                <div class="bg-card rounded-xl shadow-md p-6 border border-border">
                    <form id="formPencarian" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="relative">
                            <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground"></i>
                            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari user..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary/20" oninput="autoSubmit()" />
                        </div>
                        <select name="role" class="px-4 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary/20" onchange="this.form.submit()">
                            <option value="all" <?= $role == 'all' ? 'selected' : '' ?>>Semua Role</option>
                            <option value="Admin" <?= $role == 'Admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="Aslab" <?= $role == 'Aslab' ? 'selected' : '' ?>>Aslab</option>
                            <option value="Mahasiswa" <?= $role == 'Mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                        </select>
                    </form>
                </div>

                <div class="bg-card rounded-xl shadow-md border border-border overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-muted/50 border-b border-border">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Nama Lengkap</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">NPM/NIDN</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Role</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Kelas</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Angkatan</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">No HP</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                <?php while ($u = $users->fetch_assoc()): ?>
                                    <tr class="hover:bg-muted/30 transition-colors">
                                        <td class="px-6 py-4 flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                                <i data-feather="user" class="w-5 h-5 text-primary"></i>
                                            </div>
                                            <span class="font-medium"><?= htmlspecialchars($u['nama'] ?? '') ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-muted-foreground"><?= htmlspecialchars($u['npm'] ?? '') ?></td>
                                        <td class="px-6 py-4">
                                            <?php
                                            $bg = $u['role'] == 'Admin' ? 'bg-purple-100 text-purple-700' : ($u['role'] == 'Aslab' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700');
                                            echo "<span class='inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {$bg}'>{$u['role']}</span>";
                                            ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-muted-foreground"><?= htmlspecialchars($u['kelas'] ?? '') ?></td>
                                        <td class="px-6 py-4 text-sm text-muted-foreground"><?= htmlspecialchars($u['angkatan'] ?? '') ?></td>
                                        <td class="px-6 py-4 text-sm text-muted-foreground"><?= htmlspecialchars($u['no_hp'] ?? '') ?></td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                <button onclick="openEditModal('<?= $u['id'] ?>', '<?= htmlspecialchars($u['npm'] ?? '') ?>', '<?= htmlspecialchars($u['nama'] ?? '') ?>', '<?= $u['role'] ?? '' ?>', '<?= htmlspecialchars($u['kelas'] ?? '') ?>', '<?= htmlspecialchars($u['angkatan'] ?? '') ?>', '<?= htmlspecialchars($u['no_hp'] ?? '') ?>', '<?= $u['status_akun'] ?? 'Aktif' ?>')" class="text-green-500 hover:text-green-700 transition-colors cursor-pointer">
                                                    <i data-feather="edit" class="w-4 h-4"></i>
                                                </button>
                                                <a href="delete.php?id=<?= $u['id'] ?>"
                                                    class="text-red-500 hover:text-red-700 transition-colors"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna <?= htmlspecialchars($u['nama']) ?>?')">
                                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="tambahModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden border border-gray-100">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Tambah User Baru</h3>
                <button onclick="closeTambahModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="create.php" method="POST" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NPM</label>
                    <input type="text" name="npm" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                        <option value="Mahasiswa">Mahasiswa</option>
                        <option value="Aslab">Aslab</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <input type="text" name="kelas" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Angkatan</label>
                        <input type="number" name="angkatan" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                    <input type="text" name="no_hp" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeTambahModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 text-sm font-medium cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium cursor-pointer">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden border border-gray-100">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Edit Data User</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="formEditUser" action="" method="POST" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">NPM (Tidak dapat diubah)</label>
                    <input type="text" id="edit_npm" disabled class="w-full px-3 py-2 border border-gray-200 bg-gray-50 text-gray-400 cursor-not-allowed text-sm rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="edit_nama" name="nama" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select id="edit_role" name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                        <option value="Mahasiswa">Mahasiswa</option>
                        <option value="Aslab">Aslab</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <input type="text" id="edit_kelas" name="kelas" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Angkatan</label>
                        <input type="number" id="edit_angkatan" name="angkatan" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                    <input type="text" id="edit_no_hp" name="no_hp" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Akun</label>
                    <select id="edit_status" name="status_akun" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 text-sm font-medium cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    <script src="../include/script.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
    <script>
        let timeoutId;
        // Fungsi membuka/menutup modal tambah
        function openTambahModal() {
            const modal = document.getElementById('tambahModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeTambahModal() {
            const modal = document.getElementById('tambahModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        // Fungsi membuka modal edit dan melempar data dari database ke form modal secara dinamis
        function openEditModal(id, npm, nama, role, kelas, angkatan, no_hp, status) {
            // Inject action form ke file edit.php dengan ID yang sesuai
            document.getElementById('formEditUser').action = 'edit.php?id=' + id;

            // Set value masing-masing field input modal
            document.getElementById('edit_npm').value = npm;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_kelas').value = kelas;
            document.getElementById('edit_angkatan').value = angkatan;
            document.getElementById('edit_no_hp').value = no_hp;
            document.getElementById('edit_status').value = status;

            // Tampilkan modal
            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        // Menutup modal otomatis jika area hitam di luar pop-up diklik
        window.onclick = function(event) {
            const tambahModal = document.getElementById('tambahModal');
            const editModal = document.getElementById('editModal');
            if (event.target == tambahModal) closeTambahModal();
            if (event.target == editModal) closeEditModal();
        }

        function autoSubmit() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                document.getElementById("formPencarian").submit();
            }, 500);
        }

    </script>
</body>

</html>