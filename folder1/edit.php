<?php
// edit.php
include 'koneksi.php';

if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $conn->real_escape_string($_GET['id']);
$query = $conn->query("SELECT * FROM locations WHERE id='$id'");
$data = $query->fetch_assoc();

if(!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lokasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-custom">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h4 class="header-title mb-0">Ubah Data Lokasi</h4>
                </div>
                <div class="card-body p-4">
                    <form action="proses_edit.php" method="POST">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Nama Lokasi</label>
                            <input type="text" name="nama" class="form-control form-control-lg bg-light" value="<?= htmlspecialchars($data['nama']) ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Keterangan Letak</label>
                            <textarea name="keterangan" class="form-control bg-light" rows="4"><?= htmlspecialchars($data['keterangan']) ?></textarea>
                        </div>
                        <div class="d-flex gap-3 pt-2">
                            <a href="index.php" class="btn btn-outline-secondary btn-lg btn-custom-action w-25">Batal</a>
                            <button type="submit" class="btn btn-primary btn-lg btn-custom-action flex-grow-1">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>