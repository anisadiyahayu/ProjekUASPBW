<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php"); 
    exit;
}

include '../koneksi.php';

$id = $_GET['id'] ?? '';
if ($id == '') {
    header("Location: data_barang.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT items.*, categories.nama AS kategori, locations.nama AS lokasi
    FROM items
    LEFT JOIN categories ON categories.id = items.id_kategori
    LEFT JOIN locations ON locations.id = items.id_lokasi
    WHERE items.id = '" . mysqli_real_escape_string($conn, $id) . "'
");
$barang = mysqli_fetch_assoc($query);

if (!$barang) {
    echo "<script>alert('Data barang tidak ditemukan!'); window.location='data_barang.php';</script>";
    exit;
}

$query_transaksi = mysqli_query($conn, "
    SELECT * FROM transactions 
    WHERE id_item = '" . mysqli_real_escape_string($conn, $id) . "' 
    ORDER BY tanggal_transaksi DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang - <?= htmlspecialchars($barang['nama']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a365d;       
            --accent-color: #2563eb;        
            --bg-main: #f8fafc;             
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
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
        .page-header { margin-bottom: 28px; }
        .page-header h2 { font-size: 24px; font-weight: 700; color: #1e293b; }
        .page-header p { color: var(--text-muted); font-size: 14px; margin-top: 4px; }
        
        .btn { 
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 14px; 
            text-decoration: none; cursor: pointer; border: none; transition: background 0.2s;
        }
        .btn-secondary { background-color: #ffffff; color: #475569; border: 1px solid var(--border-color); }
        .btn-secondary:hover { background-color: #f8fafc; }

        .detail-card { 
            display: flex; gap: 32px; background: white; padding: 32px; 
            border-radius: 12px; border: 1px solid var(--border-color); 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); margin-bottom: 32px;
        }
        .image-section { width: 220px; flex-shrink: 0; }
        .item-img { width: 100%; height: 220px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color); background-color: #fafafa; }
        .no-img-box { width: 100%; height: 220px; border-radius: 8px; background: #f1f5f9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text-muted); gap: 8px; font-size: 13px; font-weight: 500; }
        .no-img-box i { font-size: 32px; color: #cbd5e1; }

        .info-section { flex: 1; }
        .info-grid { display: grid; grid-template-columns: 160px 1fr; gap: 16px 12px; font-size: 14px; }
        .label-field { font-weight: 600; color: var(--text-muted); }
        .value-field { color: var(--text-main); }

        .section-title { font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #1e293b; display: flex; align-items: center; gap: 8px; }
        .table-responsive { width: 100%; background: #ffffff; border-radius: 12px; border: 1px solid var(--border-color); overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        .data-table th { background-color: #f8fafc; color: var(--text-muted); font-weight: 600; padding: 14px 20px; border-bottom: 1px solid var(--border-color); font-size: 12px; text-transform: uppercase; }
        .data-table td { padding: 14px 20px; border-bottom: 1px solid var(--border-color); color: var(--text-main); }
        .data-table tr:last-child td { border-bottom: none; }
        .badge-log { display: inline-block; padding: 4px 10px; border-radius: 50px; font-size: 12px; font-weight: 600; }
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
        <div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h2>Detail Informasi Barang</h2>
                <p>Melihat spesifikasi berkas fisik dan riwayat keluar masuk instrumen.</p>
            </div>
            <a href="data_barang.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>

        <div class="detail-card">
            <div class="image-section">
                <?php if (!empty($barang['foto_barang']) && file_exists("../uploads/" . $barang['foto_barang'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($barang['foto_barang']) ?>" alt="Foto Barang" class="item-img">
                <?php else: ?>
                    <div class="no-img-box">
                        <i class="fa-solid fa-image"></i>
                        <span>Tidak Ada Foto</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="info-section">
                <div class="info-grid">
                    <div class="label-field">Kode Barang</div>
                    <div class="value-field" style="font-weight:700; color:var(--accent-color);"><?= htmlspecialchars($barang['kode']) ?></div>

                    <div class="label-field">Nama Alat/Bahan</div>
                    <div class="value-field" style="font-weight:600; font-size:16px;"><?= htmlspecialchars($barang['nama']) ?></div>

                    <div class="label-field">Kategori</div>
                    <div class="value-field"><?= htmlspecialchars($barang['kategori'] ?? 'Tanpa Kategori') ?></div>

                    <div class="label-field">Lokasi Penyimpanan</div>
                    <div class="value-field"><i class="fa-solid fa-location-dot" style="color:var(--text-muted); margin-right:4px;"></i> <?= htmlspecialchars($barang['lokasi'] ?? 'Belum Ditentukan') ?></div>

                    <div class="label-field">Persediaan Aktif</div>
                    <div class="value-field" style="font-weight:600;"><?= htmlspecialchars($barang['stok']) ?> <?= htmlspecialchars($barang['satuan']) ?></div>

                    <div class="label-field">Batas Minimal Peringatan</div>
                    <div class="value-field"><?= htmlspecialchars($barang['stok_minimum']) ?> <?= htmlspecialchars($barang['satuan']) ?></div>

                    <div class="label-field">Kondisi Fisik</div>
                    <div class="value-field"><?= htmlspecialchars($barang['kondisi']) ?></div>

                    <div class="label-field">Keterangan Tambahan</div>
                    <div class="value-field" style="line-height:1.5; color:#475569;"><?= !empty($barang['keterangan']) ? nl2br(htmlspecialchars($barang['keterangan'])) : '-' ?></div>
                </div>
            </div>
        </div>

        <div class="section-title">
            <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi & Mutasi Barang
        </div>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal Transaksi</th>
                        <th>Petugas (User)</th>
                        <th>Jenis Aktivitas</th>
                        <th>Jumlah Mutasi</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query_transaksi) > 0): ?>
                        <?php while ($t = mysqli_fetch_assoc($query_transaksi)): ?>
                            <tr>
                                <td style="color:#475569;"><?= date('d M Y, H:i', strtotime($t['tanggal_transaksi'])) ?></td>
                                <td style="font-weight:500;">User ID: <?= htmlspecialchars($t['id_user']) ?></td>
                                <td>
                                    <?php if($t['jenis_transaksi'] == 'Masuk'): ?>
                                        <span class="badge-log" style="background-color: #dcfce7; color: #16a34a;"><i class="fa-solid fa-arrow-down-long"></i> Barang Masuk</span>
                                    <?php else: ?>
                                        <span class="badge-log" style="background-color: #fee2e2; color: #dc2626;"><i class="fa-solid fa-arrow-up-long"></i> Barang Keluar</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-weight: 600;"><?= htmlspecialchars($t['jumlah']) ?></td>
                                <td style="color:#475569;"><?= htmlspecialchars($t['keterangan']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" align="center" style="padding:24px; color:var(--text-muted);">Belum ada catatan aktivitas riwayat masuk, peminjaman, atau mutasi untuk barang ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>