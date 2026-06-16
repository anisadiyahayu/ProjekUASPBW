<?php
include "../auth.php";
include "../koneksi.php";

$nama = $_SESSION['nama'];
$cari = $_GET['cari'] ?? '';
$role = $_GET['role'] ?? '';
$where = "WHERE 1=1";

if ($cari != '') {
    $where .= " AND (nama LIKE '%$cari%' OR npm LIKE '%$cari%' OR email LIKE '%$cari%')";
}
if ($role != '') {
    $where .= " AND role='$role'";
}

$query = mysqli_query($conn, "SELECT * FROM users $where ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f1f5f9; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .table-row:hover { background: #f8fafc; }
    </style>
</head>
<body>
    <aside class="fixed left-0 top-0 w-64 h-screen bg-[#1E3A8A] text-white">
        <div class="h-full flex flex-col">
            <div class="p-5 border-b border-blue-800">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-[#3B82F6] flex items-center justify-center"><i data-lucide="box" class="w-5 h-5"></i></div>
                    <div>
                        <h1 class="font-semibold">Lab Inventory</h1>
                        <p class="text-xs text-blue-200">Admin Panel</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 px-2 py-4">
                <div class="space-y-1">
                    <a href="dashboard_admin.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"><i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard</a>
                    <a href="barang.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"><i data-lucide="package" class="w-4 h-4"></i> Data Barang</a>
                    <a href="kategori.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"><i data-lucide="folder-tree" class="w-4 h-4"></i> Kategori Barang</a>
                    <a href="lokasi.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"><i data-lucide="map-pin" class="w-4 h-4"></i> Lokasi Penyimpanan</a>
                    <a href="users.php" class="bg-[#3B82F6] flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium"><i data-lucide="users" class="w-4 h-4"></i> Data User</a>
                    <a href="admin_peminjaman.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"><i data-lucide="package-check" class="w-4 h-4"></i> Peminjaman Barang</a>
                    <a href="admin_pengembalian.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"><i data-lucide="rotate-ccw" class="w-4 h-4"></i> Pengembalian Barang</a>
                    <a href="laporan.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"><i data-lucide="file-text" class="w-4 h-4"></i> Laporan Inventaris</a>
                    <a href="aktivitas.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"><i data-lucide="history" class="w-4 h-4"></i> Riwayat Aktivitas</a>
                    <a href="profil_admin.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm"><i data-lucide="user" class="w-4 h-4"></i> Profil</a>
                </div>
            </nav>
            <div class="p-4 border-t border-blue-800">
                <a href="logout.php" class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91] transition-all">
                    <i data-lucide="log-out" class="w-5 h-5 text-white group-hover:text-red-500"></i>
                    <span class="group-hover:text-red-500">Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <div class="ml-64">
        <header class="bg-white border-b h-[56px] px-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <button><i data-lucide="x" class="w-4 h-4 text-slate-500"></i></button>
                <div class="relative w-[280px]">
                    <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"></i>
                    <input type="text" placeholder="Cari barang, user, atau aktivitas..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm">
                </div>
            </div>
            <div class="flex items-center gap-5">
                <div class="relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">5</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <h4 class="text-sm font-semibold"><?= htmlspecialchars($nama) ?></h4>
                        <p class="text-[11px] text-slate-500">Administrator</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center font-semibold">
                        <?= strtoupper(substr($nama, 0, 2)) ?>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-[28px] font-semibold text-slate-800">Data User</h1>
                    <p class="text-slate-500 text-sm">Kelola pengguna sistem laboratorium</p>
                </div>
                <button onclick="openTambahUser()" class="bg-[#1E3A8A] text-white px-5 py-3 rounded-xl flex items-center gap-2">
                    <i data-lucide="plus"></i> Tambah User
                </button>
            </div>

            <div class="card p-4 mb-5">
                <form method="GET">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-slate-400"></i>
                            <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari user..." class="w-full pl-10 pr-4 py-3 border rounded-lg">
                        </div>
                        <select name="role" onchange="this.form.submit()" class="border rounded-lg px-4 py-3">
                            <option value="">Semua Role</option>
                            <option value="admin" <?= $role=='admin'?'selected':'' ?>>Admin</option>
                            <option value="laboran" <?= $role=='laboran'?'selected':'' ?>>Laboran</option>
                            <option value="mahasiswa" <?= $role=='mahasiswa'?'selected':'' ?>>Mahasiswa</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="card overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-4 text-left">Nama Lengkap</th>
                            <th class="px-5 py-4 text-left">NPM/NIDN</th>
                            <th class="px-5 py-4 text-left">Role</th>
                            <th class="px-5 py-4 text-left">Kelas</th>
                            <th class="px-5 py-4 text-left">Angkatan</th>
                            <th class="px-5 py-4 text-left">No HP</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($query)) : ?>
                        <tr class="border-t hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center"><i data-lucide="user"></i></div>
                                    <div>
                                        <div class="font-medium"><?= htmlspecialchars($row['nama'] ?? '') ?></div>
                                        <div class="text-xs text-slate-500"><?= htmlspecialchars($row['email'] ?? '') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4"><?= htmlspecialchars($row['npm'] ?? '') ?: '-' ?></td>
                            <td class="px-5 py-4">
                                <?php
                                $badge = 'bg-green-100 text-green-700';
                                if($row['role'] == 'admin') { $badge = 'bg-purple-100 text-purple-700'; }
                                elseif($row['role'] == 'laboran') { $badge = 'bg-blue-100 text-blue-700'; }
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $badge ?>"><?= ucfirst($row['role']) ?></span>
                            </td>
                            <td class="px-5 py-4"><?= htmlspecialchars($row['kelas'] ?? '') ?: '-' ?></td>
                            <td class="px-5 py-4"><?= htmlspecialchars($row['angkatan'] ?? '') ?: '-' ?></td>
                            <td class="px-5 py-4"><?= htmlspecialchars($row['no_hp'] ?? '') ?: '-' ?></td>
                            <td class="px-5 py-4">
                                <div class="flex justify-center gap-4">
                                    <button type="button" class="text-green-600" onclick='openEditUser(<?= $row["id"] ?>, <?= json_encode($row["nama"]) ?>, <?= json_encode($row["npm"]) ?>, <?= json_encode($row["email"]) ?>, <?= json_encode($row["role"]) ?>, <?= json_encode($row["kelas"]) ?>, <?= json_encode($row["angkatan"]) ?>, <?= json_encode($row["no_hp"]) ?>)'><i data-lucide="pencil"></i></button>
                                    <button type="button" class="text-red-600" onclick='openHapusUser(<?= $row["id"] ?>, <?= json_encode($row["nama"]) ?>)'><i data-lucide="trash-2"></i></button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="modalHapusUser" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
            <form action="user_hapus.php" method="POST">
                <input type="hidden" name="id" id="hapus_id">
                <div class="p-5 border-b"><h3 class="font-semibold text-lg text-red-600">Hapus User</h3></div>
                <div class="p-6">
                    <p>Apakah Anda yakin ingin menghapus user <strong id="hapus_nama"></strong>?</p>
                    <p class="text-sm text-red-500 mt-2">Data tidak dapat dikembalikan.</p>
                </div>
                <div class="p-5 border-t flex justify-end gap-3">
                    <button type="button" onclick="closeHapusUser()" class="px-5 py-2 border rounded-lg">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-lg">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEditUser" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl">
            <form action="user_edit.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="p-5 border-b"><h3 class="font-semibold text-lg">Edit User</h3></div>
                <div class="p-5 grid md:grid-cols-2 gap-4">
                    <div><label class="block text-sm mb-2">Nama Lengkap</label><input type="text" name="nama" id="edit_nama" required class="w-full border rounded-lg px-4 py-3"></div>
                    <div><label class="block text-sm mb-2">NPM / NIDN</label><input type="text" name="npm" id="edit_npm" required class="w-full border rounded-lg px-4 py-3"></div>
                    <div><label class="block text-sm mb-2">Email</label><input type="email" name="email" id="edit_email" required class="w-full border rounded-lg px-4 py-3"></div>
                    <div><label class="block text-sm mb-2">Password Baru</label><input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full border rounded-lg px-4 py-3"></div>
                    <div>
                        <label class="block text-sm mb-2">Role</label>
                        <select name="role" id="edit_role" class="w-full border rounded-lg px-4 py-3">
                            <option value="admin">Admin</option>
                            <option value="laboran">Laboran</option>
                            <option value="mahasiswa">Mahasiswa</option>
                        </select>
                    </div>
                    <div><label class="block text-sm mb-2">Kelas</label><input type="text" name="kelas" id="edit_kelas" class="w-full border rounded-lg px-4 py-3"></div>
                    <div><label class="block text-sm mb-2">Angkatan</label><input type="text" name="angkatan" id="edit_angkatan" class="w-full border rounded-lg px-4 py-3"></div>
                    <div><label class="block text-sm mb-2">No HP</label><input type="text" name="no_hp" id="edit_no_hp" class="w-full border rounded-lg px-4 py-3"></div>
                </div>
                <div class="p-5 border-t flex justify-end gap-3">
                    <button type="button" onclick="closeEditUser()" class="px-5 py-2 border rounded-lg">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalTambahUser" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl">
            <form action="user_tambah.php" method="POST">
                <div class="p-5 border-b"><h3 class="font-semibold text-lg">Tambah User</h3></div>
                <div class="p-5 grid md:grid-cols-2 gap-4">
                    <div><label class="block text-sm mb-2">Nama Lengkap</label><input type="text" name="nama" required class="w-full border rounded-lg px-4 py-3"></div>
                    <div><label class="block text-sm mb-2">NPM / NIDN</label><input type="text" name="npm" required class="w-full border rounded-lg px-4 py-3"></div>
                    <div><label class="block text-sm mb-2">Email</label><input type="email" name="email" required class="w-full border rounded-lg px-4 py-3"></div>
                    <div><label class="block text-sm mb-2">Password</label><input type="password" name="password" required class="w-full border rounded-lg px-4 py-3"></div>
                    <div>
                        <label class="block text-sm mb-2">Role</label>
                        <select name="role" required class="w-full border rounded-lg px-4 py-3">
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="laboran">Laboran</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div><label class="block text-sm mb-2">Kelas</label><input type="text" name="kelas" class="w-full border rounded-lg px-4 py-3"></div>
                    <div><label class="block text-sm mb-2">Angkatan</label><input type="text" name="angkatan" class="w-full border rounded-lg px-4 py-3"></div>
                    <div><label class="block text-sm mb-2">No HP</label><input type="text" name="no_hp" class="w-full border rounded-lg px-4 py-3"></div>
                </div>
                <div class="p-5 border-t flex justify-end gap-3">
                    <button type="button" onclick="closeTambahUser()" class="px-5 py-2 border rounded-lg">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#1E3A8A] text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();
        /* TAMBAH */
        function openTambahUser(){ document.getElementById('modalTambahUser').classList.remove('hidden'); }
        function closeTambahUser(){ document.getElementById('modalTambahUser').classList.add('hidden'); }
        /* EDIT */
        function openEditUser(id, nama, npm, email, role, kelas, angkatan, nohp){
            document.getElementById('edit_id').value=id;
            document.getElementById('edit_nama').value=nama;
            document.getElementById('edit_npm').value=npm;
            document.getElementById('edit_email').value=email;
            document.getElementById('edit_role').value=role;
            document.getElementById('edit_kelas').value=kelas;
            document.getElementById('edit_angkatan').value=angkatan;
            document.getElementById('edit_no_hp').value=nohp;
            document.getElementById('modalEditUser').classList.remove('hidden');
        }
        function closeEditUser(){ document.getElementById('modalEditUser').classList.add('hidden'); }
        /* HAPUS */
        function openHapusUser(id,nama){
            document.getElementById('hapus_id').value=id;
            document.getElementById('hapus_nama').textContent=nama;
            document.getElementById('modalHapusUser').classList.remove('hidden');
        }
        function closeHapusUser(){ document.getElementById('modalHapusUser').classList.add('hidden'); }
    </script>
</body>
</html>