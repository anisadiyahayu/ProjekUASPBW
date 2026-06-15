<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lokasi Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h4 class="mb-0 text-primary fw-bold">📍 Tambah Lokasi Baru</h4>
                </div>
                <div class="card-body p-4">
                    <form action="proses_tambah.php" method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Nama Lokasi</label>
                            <input type="text" name="nama" class="form-control form-control-lg bg-light" placeholder="Cth: Lab Komputer 1" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Keterangan Letak / Posisi</label>
                            <textarea name="keterangan" class="form-control bg-light" rows="4" placeholder="Detail ruangan, letak rak, atau nomor lemari..."></textarea>
                        </div>
                        <div class="d-flex gap-3 pt-2">
                            <a href="index.php" class="btn btn-outline-secondary btn-lg w-25">Batal</a>
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1 fw-bold">Simpan Lokasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>