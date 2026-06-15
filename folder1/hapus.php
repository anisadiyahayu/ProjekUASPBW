<?php
// hapus.php
include 'koneksi.php';

if(isset($_GET['id'])) {
    // Mengamankan parameter GET
    $id = $conn->real_escape_string($_GET['id']);
    
    // Query untuk menghapus data lokasi berdasarkan ID
    $query = "DELETE FROM locations WHERE id='$id'";
    
    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Lokasi berhasil dihapus!'); window.location.href='index.php';</script>";
    } else {
        echo "Error menghapus data: " . $conn->error;
    }
} else {
    header("Location: index.php");
}

$conn->close();
?>