<?php
session_start();
require_once '../include/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user_id'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $kelas = $_POST['kelas'];
    $angkatan = $_POST['angkatan'];

    $avatar_path = $_SESSION['avatar']; // Default lama
    
    // Handle File Upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        
        if (in_array(strtolower($ext), $allowed)) {
            // Pastikan Anda membuat folder "uploads" di root jika belum ada
            if (!is_dir('../uploads')) mkdir('../uploads', 0777, true);
            
            $new_filename = 'uploads/avatar_' . $id . '_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], '../' . $new_filename)) {
                $avatar_path = $new_filename;
                $_SESSION['avatar'] = $avatar_path;
            }
        }
    }

    $stmt = $conn->prepare("UPDATE users SET nama=?, email=?, no_hp=?, kelas=?, angkatan=?, avatar=? WHERE id=?");
    $stmt->bind_param("ssssiss", $nama, $email, $no_hp, $kelas, $angkatan, $avatar_path, $id);
    $stmt->execute();
    
    $_SESSION['nama'] = $nama;

    header("Location: index.php?msg=success");
    exit;
}
?>