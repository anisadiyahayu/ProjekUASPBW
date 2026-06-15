<?php
require_once '../include/koneksi.php';
require_once '../include/header.php';

$id = $_GET['id'] ?? '';
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "<script>window.location='index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $role = $_POST['role'];
    $kelas = $_POST['kelas'];
    $angkatan = $_POST['angkatan'];
    $no_hp = $_POST['no_hp'];
    $status_akun = $_POST['status_akun'];

    $stmtUpdate = $conn->prepare("UPDATE users SET nama=?, role=?, kelas=?, angkatan=?, no_hp=?, status_akun=? WHERE id=?");
    $stmtUpdate->bind_param("sssssss", $nama, $role, $kelas, $angkatan, $no_hp, $status_akun, $id);
    
    if($stmtUpdate->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='index.php';</script>";
    }
}
?>
<div class="max-w-2xl mx-auto bg-card rounded-xl shadow-md border border-border">
    <div class="p-6 border-b flex justify-between">
        <h2 class="text-xl font-semibold">Edit User</h2>
        <a href="index.php" class="p-2 hover:bg-muted rounded-lg"><i data-feather="x"></i></a>
    </div>
    <div class="p-6">
        <form method="POST" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Status Akun</label>
                    <select name="status_akun" class="w-full px-4 py-2 border rounded-lg">
                        <option value="Aktif" <?= $user['status_akun']=='Aktif'?'selected':'' ?>>Aktif</option>
                        <option value="Nonaktif" <?= $user['status_akun']=='Nonaktif'?'selected':'' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Role</label>
                    <select name="role" class="w-full px-4 py-2 border rounded-lg">
                        <option value="Mahasiswa" <?= $user['role']=='Mahasiswa'?'selected':'' ?>>Mahasiswa</option>
                        <option value="Aslab" <?= $user['role']=='Aslab'?'selected':'' ?>>Aslab</option>
                        <option value="Admin" <?= $user['role']=='Admin'?'selected':'' ?>>Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Kelas</label>
                    <input type="text" name="kelas" value="<?= htmlspecialchars($user['kelas']) ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Angkatan</label>
                    <input type="number" name="angkatan" value="<?= htmlspecialchars($user['angkatan']) ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">No HP</label>
                <input type="text" name="no_hp" value="<?= htmlspecialchars($user['no_hp']) ?>" class="w-full px-4 py-2 border rounded-lg">
            </div>
            <div class="flex gap-3 pt-4">
                <a href="index.php" class="flex-1 text-center px-4 py-2 border rounded-lg hover:bg-muted">Batal</a>
                <button type="submit" class="flex-1 px-4 py-2 bg-primary text-primary-foreground rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?php require_once '../include/footer.php'; ?>