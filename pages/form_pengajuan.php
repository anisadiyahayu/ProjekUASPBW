<?php

$_SESSION['nama'] = 'USER';
$_SESSION['nim'] = '24102317';

?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Surat Keterangan Domisili</title>
    <link rel="stylesheet" href="/include/style.css">
</head>
<body class="dashboard-body">

    <div class="main-container">

        <div class="content-area">
            <header class="top-header">
                <div class="top-header-right">
                   <div class="notifications">Halo, <?= $_SESSION['nama'] ?? 'Warga'; ?>!
                        <i class="icon-bell"></i>
                        <span class="notification-badge">1</span>
                    </div>
                    <div class="user-profile">
                        <img src="https://via.placeholder.com/40" alt="User Profile" class="profile-img">
                        <i class="icon-status-active"></i>
                    </div>
                </div>
            </header>

            <main class="page-content">
                <div class="content-header">
                    <p class="breadcrumb">
                        <a href="menu.php" style="color: #888; text-decoration: none;">Home</a> / 
                        <a href="menu.php" style="color: #888; text-decoration: none;"><strong style="color: #333;">Pengajuan Barang</strong></a>
                    </p>
                </div>
                <div class="center-action">
                    <div class="form-card">
                        <h2 class="form-title">Form Surat Keterangan Domisili</h2>
                        
                        <form action="" method="post" enctype="multipart/form-data">
                            
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <div class="input-wrapper">
                                    <span class="input-icon">👤</span>
                                    <input type="text" name="nama_pengaju" placeholder="Masukkan nama lengkap" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Nama Barang</label>
                                <div class="input-wrapper">
                                    <span class="input-icon"></span>
                                    <input type="text" name="nama_barang" placeholder="Masukkan nama barang yang ingin dipinjam" required>
                                </div>
                            </div>
                            
                                    <input type="number" name="nim_pengaju" value="<?php $_SESSION['nim'] ?>" hidden>


                            <div class="form-group">
                                <label>Jumlah Barang</label>
                                <div class="input-wrapper">
                                    <span class="input-icon"></span>
                                    <input type="number" name="jumlah_barang" placeholder="Masukan jumlah barang yang dibutuhkan" required>
                                </div>
                            </div>
                                                            
                            <div class="form-group">
                                <label>Keperluan</label>
                                <div class="input-wrapper">
                                    <span class="input-icon"></span>
                                    <select name="keperluan">
                                        <option value="" disabled selected>Pilih Keperluan</option>
                                        <option value="praktikum">Praktikum</option>
                                        <option value="ta">TA</option>
                                        <option value="penelitian">Penelitian</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Tanggal pengembalian</label>
                                <div class="input-wrapper">
                                    <span class="input-icon">📅</span>
                                    <input type="date" name="tanggal_pengembalian" required>
                                </div>
                            </div>

                            <hr class="form-divider">
                                    <input type="text" name="status_pengajuan" value="pending" hidden>
                                    <input type="date" name="tanggal_pengajuan" value="" hidden>
                                    <input type="date" name="tanggal_approve" value="" hidden>
                                    <input type="text" name="keterangan_ditolak" value="" hidden>
                            <button type="submit" name="kirim_pengajuan" class="send-btn">Send</button>
                        </form>
                    </div>
                </div>
            </main> 
        </div> 
    </div> 
    <script src="script.js"></script>
</body>
</html>