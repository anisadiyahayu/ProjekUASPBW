<?php
session_start();
if (!isset($_SESSION['role'])) { 
    header("Location: ../login.php"); 
    exit; 
}

include '../koneksi.php';
include 'fungsi_stok.php';
include "../include/header.php";

$search   = $_GET['search'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$lokasi   = $_GET['lokasi'] ?? ''; 
$kondisi  = $_GET['kondisi'] ?? ''; 

$where = "";
if ($search != '')   { $where .= " AND items.nama LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'"; }
if ($kategori != '') { $where .= " AND items.id_kategori = '" . mysqli_real_escape_string($conn, $kategori) . "'"; }
if ($lokasi != '')   { $where .= " AND items.id_lokasi = '" . mysqli_real_escape_string($conn, $lokasi) . "'"; }
if ($kondisi != '')  { $where .= " AND items.kondisi = '" . mysqli_real_escape_string($conn, $kondisi) . "'"; }

$sql = mysqli_query($conn, "
    SELECT items.*, categories.nama AS nama_kategori, locations.nama AS nama_lokasi
    FROM items
    LEFT JOIN categories ON categories.id = items.id_kategori
    LEFT JOIN locations ON locations.id = items.id_lokasi
    WHERE 1=1 $where ORDER BY items.nama ASC
");

$query_kat = mysqli_query($conn, "SELECT * FROM categories ORDER BY nama ASC");
$query_lok = mysqli_query($conn, "SELECT * FROM locations ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Inventaris Barang Lab</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a365d;       
            --primary-hover: #122542;
            --accent-color: #2563eb;        
            --bg-main: #f8fafc;             
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --status-aman-bg: #dcfce7;
            --status-aman-text: #16a34a;
            --status-menipis-bg: #fef3c7;
            --status-menipis-text: #d97706;
            --status-habis-bg: #fee2e2;
            --status-habis-text: #dc2626;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', system-ui, sans-serif; }
        body { background-color: var(--bg-main); color: var(--text-main); min-height: 100vh; display: flex; }

        .sidebar { 
            width: 260px; background-color: var(--primary-color); color: white; 
            padding: 24px 16px; position: fixed; height: 100vh; left: 0; top: 0; 
            display: flex; flex-direction: column; justify-content: space-between; 
            box-shadow: 2px 0 12px rgba(0,0,0,0.05); z-index: 1000;
        }
        .sidebar-brand { 
            padding: 12px 16px 24px 16px; border-bottom: 1px solid rgba(255,255,255,0.1); 
            margin-bottom: 24px; display: flex; align-items: center; gap: 12px;
        }
        .sidebar-brand i { font-size: 24px; color: #93c5fd; }
        .sidebar-brand-text { font-size: 16px; font-weight: 700; color: #ffffff; line-height: 1.2; }
        .sidebar-subtitle { font-size: 11px; color: #93c5fd; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-top: 2px; }
        .sidebar-menu { list-style: none; display: flex; flex-direction: column; gap: 4px; }
        .sidebar-menu a { 
            color: #cbd5e1; text-decoration: none; padding: 12px 16px; display: flex;
            align-items: center; gap: 12px; border-radius: 8px; font-weight: 500; font-size: 14px; transition: all 0.2s ease;
        }
        .sidebar-menu li.active a { background-color: var(--accent-color); color: #ffffff; }
        .sidebar-menu a:hover { background-color: rgba(255,255,255,0.08); color: #ffffff; }
        .sidebar-footer { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 16px; }
        .logout-btn { 
            color: #f87171; text-decoration: none; padding: 12px 16px; display: flex;
            align-items: center; gap: 12px; font-size: 14px; font-weight: 500; border-radius: 8px; transition: all 0.2s ease;
        }
        .logout-btn:hover { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }

        .main-wrapper { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
        .page-header h2 { font-size: 24px; font-weight: 700; color: #1e293b; }
        .page-header p { color: var(--text-muted); font-size: 14px; margin-top: 4px; }

        .btn { 
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 14px; 
            text-decoration: none; cursor: pointer; border: none; transition: background 0.2s, transform 0.1s;
        }
        .btn:active { transform: scale(0.98); }
        .btn-primary { background-color: var(--accent-color); color: white; }
        .btn-primary:hover { background-color: #1d4ed8; }
        .btn-secondary { background-color: #e2e8f0; color: #475569; }
        .btn-secondary:hover { background-color: #cbd5e1; }
        .btn-danger { background-color: #dc2626; color: white; }
        .btn-danger:hover { background-color: #b91c1c; }

        .filter-container { 
            background: white; padding: 16px; border-radius: 12px; margin-bottom: 24px; 
            display: flex; gap: 12px; align-items: center; border: 1px solid var(--border-color); 
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .search-box { position: relative; flex: 2; }
        .search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .filter-input-search { 
            width: 100%; padding: 11px 16px 11px 44px; border: 1px solid var(--border-color); 
            border-radius: 8px; outline: none; font-size: 14px; transition: border-color 0.2s; 
        }
        .filter-select {
            flex: 1; padding: 11px 16px; border: 1px solid var(--border-color); border-radius: 8px;
            font-size: 14px; background-color: #ffffff; outline: none; color: var(--text-main); cursor: pointer;
        }
        .filter-input-search:focus, .filter-select:focus { border-color: var(--accent-color); }

        .table-responsive {
            width: 100%; background: #ffffff; border-radius: 12px; border: 1px solid var(--border-color);
            overflow-x: auto; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        .data-table th {
            background-color: #f8fafc; color: var(--text-muted); font-weight: 600;
            padding: 16px 20px; border-bottom: 1px solid var(--border-color);
            text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;
        }
        .data-table td { padding: 16px 20px; border-bottom: 1px solid var(--border-color); color: var(--text-main); vertical-align: middle; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover { background-color: #f8fafc; }

        .action-link { text-decoration: none; font-weight: 600; margin-right: 12px; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; }
        .link-view { color: var(--accent-color); }
        .link-edit { color: #059669; }
        .link-delete { color: #dc2626; }
        .action-link:hover { opacity: 0.75; text-decoration: underline; }

        .badge { display: inline-flex; padding: 6px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; text-align: center; }
        .badge-aman, .badge-tersedia { background-color: var(--status-aman-bg); color: var(--status-aman-text); }
        .badge-hampir-habis, .badge-menipis { background-color: var(--status-menipis-bg); color: var(--status-menipis-text); }
        .badge-stok-habis, .badge-habis { background-color: var(--status-habis-bg); color: var(--status-habis-text); }

        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px);
            display: flex; justify-content: center; align-items: center; z-index: 2000;
            opacity: 0; pointer-events: none; transition: opacity 0.2s ease;
        }
        .modal-overlay.active { opacity: 1; pointer-events: auto; }
        .modal-card {
            background: #ffffff; border-radius: 16px; width: 100%; max-width: 520px;
            padding: 28px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transform: translateY(-20px); transition: transform 0.2s ease;
        }
        .modal-overlay.active .modal-card { transform: translateY(0); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border-color); }
        .modal-header h3 { font-size: 18px; font-weight: 700; color: var(--text-main); }
        .modal-close-btn { background: none; border: none; font-size: 18px; color: var(--text-muted); cursor: pointer; }
        .modal-close-btn:hover { color: #000000; }
        
        .detail-info-grid { display: grid; grid-template-columns: 140px 1fr; gap: 14px 12px; font-size: 14px; text-align: left; margin-bottom: 20px; }
        .detail-label { font-weight: 600; color: var(--text-muted); }
        .detail-value { color: var(--text-main); }
        
        .status-progress-bar { width: 100%; height: 8px; background-color: #e2e8f0; border-radius: 4px; margin-top: 8px; overflow: hidden; }
        .progress-fill-aman { width: 100%; height: 100%; background-color: var(--status-aman-text); }
        .progress-fill-hampir-habis { width: 35%; height: 100%; background-color: var(--status-menipis-text); }
        .progress-fill-stok-habis { width: 0%; height: 100%; background-color: var(--status-habis-text); }
        .modal-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div>
            <div class="sidebar-brand">
                <i class="fa-solid fa-cubes"></i>
                <div class="sidebar-brand-text">
                    LAB INVENTORY
                    <span class="sidebar-subtitle">Admin Panel</span>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
                <li class="active"><a href="data_barang.php"><i class="fa-solid fa-box"></i> Data Barang</a></li>
                <li><a href="#"><i class="fa-solid fa-list"></i> Kategori Barang</a></li>
                <li><a href="#"><i class="fa-solid fa-location-dot"></i> Lokasi Lab</a></li>
            </ul>
        </div>
        <div class="sidebar-footer">
            <a href="../logout.php" class="logout-btn" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar Sistem
            </a>
        </div>
    </div>

    <div class="main-wrapper">
        <div class="topbar">
            <div class="page-header">
                <h2>Data Inventaris Barang</h2>
                <p>Manajemen aset, peralatan logistik, dan ketersediaan bahan laboratorium.</p>
            </div>
            <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Aslab'): ?>
                <a href="tambah_barang.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Barang</a>
            <?php endif; ?>
        </div>

        <form method="GET" action="data_barang.php">
            <div class="filter-container">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" name="search" class="filter-input-search" placeholder="Cari nama barang..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <select name="kategori" class="filter-select">
                    <option value="">-- Semua Kategori --</option>
                    <?php while($k = mysqli_fetch_assoc($query_kat)): ?>
                        <option value="<?= $k['id'] ?>" <?= $kategori == $k['id'] ? 'selected' : '' ?>><?= htmlspecialchars($k['nama']) ?></option>
                    <?php endwhile; ?>
                </select>
                <select name="lokasi" class="filter-select">
                    <option value="">-- Semua Lokasi --</option>
                    <?php while($l = mysqli_fetch_assoc($query_lok)): ?>
                        <option value="<?= $l['id'] ?>" <?= $lokasi == $l['id'] ? 'selected' : '' ?>><?= htmlspecialchars($l['nama']) ?></option>
                    <?php endwhile; ?>
                </select>
                <select name="kondisi" class="filter-select">
                    <option value="">-- Semua Kondisi --</option>
                    <option value="Bagus" <?= $kondisi == 'Bagus' ? 'selected' : '' ?>>Bagus</option>
                    <option value="Rusak" <?= $kondisi == 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i> Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Stok</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($sql) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($sql)): 
                            $status = statusStok((int)$row['stok'], (int)$row['stok_minimum']);
                            $badgeClass = ($status == 'Tersedia') ? 'badge-aman' : (($status == 'Menipis') ? 'badge-menipis' : 'badge-habis');
                        ?>
                            <tr>
                                <td style="font-weight: 600; color: #475569;"><?= htmlspecialchars($row['kode']) ?></td>
                                <td style="font-weight: 500;"><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                <td><?= htmlspecialchars($row['nama_lokasi']) ?></td>
                                <td><?= htmlspecialchars($row['stok']) ?> <?= htmlspecialchars($row['satuan']) ?></td>
                                <td><?= htmlspecialchars($row['kondisi']) ?></td>
                                <td><span class="badge <?= $badgeClass ?>"><?= $status ?></span></td>
                                <td style="text-align: center;">
                                    <a href="detail_barang.php?id=<?= $row['id'] ?>" class="action-link link-view"><i class="fa-solid fa-eye"></i> Detail</a>
                                    
                                    <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Aslab'): ?>
                                        <a href="edit_barang.php?id=<?= $row['id'] ?>" class="action-link link-edit"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                        <a href="javascript:void(0);" class="action-link link-delete" onclick="openDelete('<?= $row['id'] ?>', '<?= addslashes($row['nama']) ?>')"><i class="fa-solid fa-trash"></i> Hapus</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 32px; color: var(--text-muted);">Tidak ada data barang ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalHapus" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Konfirmasi Hapus Data</h3>
                <button class="modal-close-btn" onclick="closeModal('modalHapus')">&times;</button>
            </div>
            <p style="font-size: 14px; margin-bottom: 20px; color: var(--text-main);">
                Apakah Anda yakin ingin menghapus data barang <strong id="hapusNamaBarang" style="color:#dc2626;"></strong>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeModal('modalHapus')">Batal</button>
                <a id="btnLinkHapus" href="#" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Ya, Hapus</a>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.add('active'); }
        function closeModal(id) { document.getElementById(id).classList.remove('active'); }
        function openDelete(id, nama) {
            document.getElementById('hapusNamaBarang').innerText = nama;
            document.getElementById('btnLinkHapus').href = 'hapus_barang.php?id=' + id;
            openModal('modalHapus');
        }
    </script>
</body>
</html>