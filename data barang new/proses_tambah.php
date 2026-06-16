<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    $kode        = trim($_POST['kode']);
    $nama        = trim($_POST['nama']);
    
    $id_kategori = isset($_POST['id_kategori']) ? $_POST['id_kategori'] : 'NONE';
    $id_lokasi   = isset($_POST['id_lokasi']) ? $_POST['id_lokasi'] : 'NONE';
    
    if ($id_kategori === 'NONE' || $id_lokasi === 'NONE') {
        echo "<script>alert('GAGAL: Kategori dan Lokasi WAJIB dipilih!'); window.history.back();</script>";
        exit;
    }
    
    $stok        = (int)$_POST['stok'];
    $satuan      = trim($_POST['satuan']);
    $kondisi     = trim($_POST['kondisi']);
    $keterangan  = trim($_POST['keterangan']);

    $foto_name = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['foto']['tmp_name'];
        $fileName      = $_FILES['foto']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $foto_name = $id . '.' . $fileExtension;
            $uploadFileDir = 'uploads/';
            $dest_path = $uploadFileDir . $foto_name;
            
            if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                echo "<script>alert('Gagal mengunggah berkas foto ke server.'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Format gambar tidak valid! Hanya diperbolehkan format JPG, JPEG, PNG, dan GIF.'); window.history.back();</script>";
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO items (id, kode, nama, id_kategori, id_lokasi, stok, satuan, kondisi, keterangan, foto_barang) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssissss", $id, $kode, $nama, $id_kategori, $id_lokasi, $stok, $satuan, $kondisi, $keterangan, $foto_name);

    if ($stmt->execute()) {
        header("Location: index.php?msg=tambah_sukses");
    } else {
        if ($foto_name && file_exists('uploads/' . $foto_name)) {
            unlink('uploads/' . $foto_name);
        }
        echo "<script>alert('Gagal menambah barang! Pastikan kode barang tidak sama. Error: " . addslashes($stmt->error) . "'); window.history.back();</script>";
    }
    $stmt->close();
} else {
    header("Location: index.php");
}
?>