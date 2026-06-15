<?php
session_start();

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Aslab')) {
    header("Location: data_barang.php");
    exit;
}

include '../koneksi.php';

$query_kategori = mysqli_query($conn, "SELECT * FROM categories ORDER BY nama ASC");
$query_lokasi   = mysqli_query($conn, "SELECT * FROM locations ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang Lab - Inventory</title>
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
        }

        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; 
        }

        body { 
            background-color: var(--bg-main); 
            color: var(--text-main); 
            min-height: 100vh; 
            display: flex;
        }

        .sidebar { 
            width: 260px; 
            background-color: var(--primary-color); 
            color: white; 
            padding: 24px 16px; 
            position: fixed; 
            height: 100vh; 
            left: 0; 
            top: 0; 
            display: flex;
            flex-direction: column;
            justify-content: space-between; 
            box-shadow: 2px 0 12px rgba(0,0,0,0.05);
            z-index: 1000;
        }

        .sidebar-brand { 
            padding: 12px 16px 24px 16px; 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
            margin-bottom: 24px; 
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand i {
            font-size: 24px;
            color: #93c5fd;
        }

        .sidebar-brand-text { 
            font-size: 16px; 
            font-weight: 700; 
            color: #ffffff; 
            line-height: 1.2;
        }

        .sidebar-subtitle { 
            font-size: 11px; 
            color: #93c5fd; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-top: 2px;
        }

        .sidebar-menu { 
            list-style: none; 
            display: flex; 
            flex-direction: column; 
            gap: 4px; 
        }

        .sidebar-menu a { 
            color: #cbd5e1; 
            text-decoration: none; 
            padding: 12px 16px; 
            display: flex;
            align-items: center;
            gap: 12px; 
            border-radius: 8px; 
            font-weight: 500; 
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .sidebar-menu li.active a { 
            background-color: var(--accent-color); 
            color: #ffffff; 
        }

        .sidebar-menu a:hover { 
            background-color: rgba(255,255,255,0.08); 
            color: #ffffff; 
        }

        .sidebar-footer { 
            border-top: 1px solid rgba(255,255,255,0.1); 
            padding-top: 16px; 
        }

        .logout-btn { 
            color: #f87171; 
            text-decoration: none; 
            padding: 12px 16px; 
            display: flex;
            align-items: center;
            gap: 12px; 
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .logout-btn:hover { 
            background-color: rgba(239, 68, 68, 0.1); 
            color: #ef4444; 
        }

        .main-wrapper { 
            margin-left: 260px; 
            width: calc(100% - 260px); 
            padding: 40px; 
        }

        .page-header { 
            margin-bottom: 32px; 
        }

        .page-header h2 { 
            font-size: 24px; 
            font-weight: 700; 
            color: #1e293b; 
        }

        .page-header p { 
            color: var(--text-muted); 
            font-size: 14px; 
            margin-top: 4px; 
        }

        .form-container {
            background: #ffffff;
            padding: 32px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            max-width: 700px;
            margin: 0 auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group-full {
            grid-column: span 2;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background: #ffffff;
            color: var(--text-main);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 90px;
        }

        input[type="file"].form-control {
            padding: 8px 12px;
            background: #f8fafc;
            cursor: pointer;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 12px;
            border-top: 1px solid var(--border-color);
            padding-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
            transition: background-color 0.2s, transform 0.1s;
        }

        .btn:active {
            transform: scale(0.98);
        }

        .btn-primary {
            background-color: var(--accent-color);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
        }

        .btn-secondary {
            background-color: #e2e8f0;
            color: #475569;
        }

        .btn-secondary:hover {
            background-color: #cbd5e1;
        }
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
        <div class="page-header">
            <h2><i class="fa-solid fa-plus"></i> Tambah Barang Baru</h2>
            <p>Masukkan data logistik atau alat laboratorium baru ke dalam sistem inventaris.</p>
        </div>

        <div class="form-container">
            <form action="proses_tambah_barang.php" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    
                    <div class="form-group">
                        <label>Kode Barang</label>
                        <input type="text" name="kode" class="form-control" placeholder="Contoh: BRG-001" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama alat / bahan" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="id_kategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php while ($kat = mysqli_fetch_assoc($query_kategori)): ?>
                                <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Lokasi Penempatan</label>
                        <select name="id_lokasi" class="form-control" required>
                            <option value="">-- Pilih Lokasi --</option>
                            <?php while ($lok = mysqli_fetch_assoc($query_lokasi)): ?>
                                <option value="<?= $lok['id'] ?>"><?= htmlspecialchars($lok['nama']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Stok Awal</label>
                        <input type="number" name="stok" min="0" class="form-control" placeholder="0" required>
                    </div>

                    <div class="form-group">
                        <label>Satuan</label>
                        <input type="text" name="satuan" class="form-control" placeholder="Pcs / Unit / Botol / Lembar" required>
                    </div>

                    <div class="form-group">
                        <label>Kondisi</label>
                        <select name="kondisi" class="form-control">
                            <option value="Bagus">Bagus</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Stok Minimum (Peringatan)</label>
                        <input type="number" name="stok_minimum" min="0" class="form-control" placeholder="Batas minimal stok peringatan" required>
                    </div>

                    <div class="form-group form-group-full">
                        <label>Foto Barang</label>
                        <input type="file" name="foto_barang" accept="image/*" class="form-control" required>
                    </div>

                    <div class="form-group form-group-full">
                        <label>Keterangan</label>
                        <textarea name="keterangan" rows="3" class="form-control" placeholder="Catatan tambahan mengenai kondisi detail atau spesifikasi barang..."></textarea>
                    </div>

                </div>

                <div class="form-actions">
                    <a href="data_barang.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>