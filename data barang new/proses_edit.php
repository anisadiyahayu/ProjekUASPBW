<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id          = $_POST['id'];
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

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['foto']['tmp_name'];
        $fileName      = $_FILES['foto']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $stmt_old = $conn->prepare("SELECT foto_barang FROM items WHERE id = ?");
            $stmt_old->bind_param("s", $id);
            $stmt_old->execute();
            $res_old = $stmt_old->get_result()->fetch_assoc();
            $stmt_old->close();

            $new_foto_name = $id . '_' . time() . '.' . $fileExtension; 
            
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                if (!empty($res_old['foto_barang']) && file_exists('uploads/' . $res_old['foto_barang'])) {
                    unlink('uploads/' . $res_old['foto_barang']);
                }
                
                $stmt = $conn->prepare("UPDATE items SET kode=?, nama=?, id_kategori=?, id_lokasi=?, stok=?, satuan=?, kondisi=?, keterangan=?, foto_barang=? WHERE id=?");
                $stmt->bind_param("ssssisssss", $kode, $nama, $id_kategori, $id_lokasi, $stok, $satuan, $kondisi, $keterangan, $new_foto_name, $id);
            } else {
                echo "<script>alert('Gagal mengunggah foto baru ke server.'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Format gambar tidak valid! Hanya diperbolehkan JPG, JPEG, PNG, dan GIF.'); window.history.back();</script>";
            exit;
        }
    } else {
        $stmt = $conn->prepare("UPDATE items SET kode=?, nama=?, id_kategori=?, id_lokasi=?, stok=?, satuan=?, kondisi=?, keterangan=? WHERE id=?");
        $stmt->bind_param("ssssissss", $kode, $nama, $id_kategori, $id_lokasi, $stok, $satuan, $kondisi, $keterangan, $id);
    }

    if ($stmt->execute()) {
        header("Location: index.php?msg=edit_sukses");
    } else {
        echo "<script>alert('Gagal mengedit barang! Error: " . addslashes($stmt->error) . "'); window.history.back();</script>";
    }
    $stmt->close();
} else {
    header("Location: index.php");
}
?>