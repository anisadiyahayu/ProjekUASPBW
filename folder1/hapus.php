<?php
// hapus.php
include 'koneksi.php';

if(isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Eksekusi hapus berdasarkan id varchar(50)
    $query = "DELETE FROM locations WHERE id='$id'";
    
    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Data lokasi berhasil dihapus!'); window.location.href='index.php';</script>";
    } else {
        echo "Error menghapus data: " . $conn->error;
    }
} else {
    header("Location: index.php");
}
$conn->close();
?>