<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';
require_once '../template/header.php';

$search = $_GET['search'] ?? '';
$role   = $_GET['role']   ?? 'all';

$query  = "SELECT * FROM users WHERE (nama LIKE ? OR npm LIKE ?)";
$params = ["%".$search."%", "%".$search."%"];
$types  = "ss";

if ($role !== 'all') {
    $query   .= " AND role = ?";
    $params[] = $role;
    $types   .= "s";
}
$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$users = $stmt->get_result();
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-foreground">Data User</h1>
            <p class="text-muted-foreground">Kelola pengguna sistem laboratorium</p>
        </div>
        <a href="create.php" class="inline-flex items-center gap-2 px-4 py-2 bg-[#3b82f6] text-white rounded-lg hover:bg-blue-600 transition-colors font-medium text-sm shadow-sm">
            <i data-feather="plus" class="w-4 h-4 text-white"></i> Tambah User
        </a>
    </div>

    <div class="bg-card rounded-xl shadow-md p-6 border border-border">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="relative">
                <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari user..."
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary/20"
                    onchange="this.form.submit()" />
            </div>
            <select name="role" class="px-4 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary/20" onchange="this.form.submit()">
                <option value="all"       <?= $role=='all'       ? 'selected' : '' ?>>Semua Role</option>
                <option value="Admin"     <?= $role=='Admin'     ? 'selected' : '' ?>>Admin</option>
                <option value="Aslab"     <?= $role=='Aslab'     ? 'selected' : '' ?>>Aslab</option>
                <option value="Mahasiswa" <?= $role=='Mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
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
                            $bg = $u['role'] == 'Admin'
                                ? 'bg-purple-100 text-purple-700'
                                : ($u['role'] == 'Aslab'
                                    ? 'bg-blue-100 text-blue-700'
                                    : 'bg-green-100 text-green-700');
                            echo "<span class='inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {$bg}'>{$u['role']}</span>";
                            ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-muted-foreground"><?= htmlspecialchars($u['kelas'] ?? '') ?></td>
                        <td class="px-6 py-4 text-sm text-muted-foreground"><?= htmlspecialchars($u['angkatan'] ?? '') ?></td>
                        <td class="px-6 py-4 text-sm text-muted-foreground"><?= htmlspecialchars($u['no_hp'] ?? '') ?></td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="edit.php?id=<?= $u['id'] ?>" class="text-green-500 hover:text-green-700 transition-colors">
                                    <i data-feather="edit" class="w-4 h-4"></i>
                                </a>
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

<?php require_once '../include/footer.php'; ?>