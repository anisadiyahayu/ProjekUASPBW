<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';
require_once '../include/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = uniqid('user_');
    $npm = $_POST['npm'];
    $nama = $_POST['nama'];
    $role = $_POST['role'];
    $kelas = $_POST['kelas'];
    $angkatan = $_POST['angkatan'];
    $no_hp = $_POST['no_hp'];
    $password = password_hash('123456', PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (id, npm, nama, password, role, kelas, angkatan, no_hp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssis", $id, $npm, $nama, $password, $role, $kelas, $angkatan, $no_hp);
    
    if($stmt->execute()) {
        echo "<script>alert('Berhasil! Password default: 123456'); window.location='index.php';</script>";
    } else {
        $error = "Gagal menambah user. NPM mungkin sudah ada.";
    }
}
?>
<div class="max-w-2xl mx-auto bg-card rounded-xl shadow-md border border-border">
    <div class="p-6 border-b border-border flex justify-between items-center">
        <h2 class="text-xl font-semibold">Tambah User Baru</h2>
        <a href="index.php" class="p-2 hover:bg-muted rounded-lg"><i data-feather="x"></i></a>
    </div>
    <div class="p-6">
        <?php if(isset($error)) echo "<p class='text-red-500 mb-4'>$error</p>"; ?>
        <form method="POST" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">NPM/NIDN</label>
                    <input type="text" name="npm" required class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-primary/20">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Role</label>
                    <select name="role" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-primary/20">
                        <option value="Mahasiswa">Mahasiswa</option>
                        <option value="Aslab">Aslab</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Kelas</label>
                    <input type="text" name="kelas" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Angkatan</label>
                    <input type="number" name="angkatan" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-primary/20">
                </div>
            </div>
           <div>
                <label class="block text-sm font-medium mb-2">No HP</label>
                <input type="text" name="no_hp" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="flex gap-3 pt-4">
                <a href="index.php" class="flex-1 text-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="flex-1 px-4 py-2 bg-[var(--primary)] text-white font-medium rounded-lg hover:opacity-90 transition-opacity">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<?php require_once '../include/footer.php'; ?>