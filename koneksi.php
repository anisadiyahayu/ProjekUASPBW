<?php
$conn = new mysqli("localhost", "root", 'root', "simlab");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

$host = "localhost";
$username = "root";
$password = "root";
$database = "simlab";

$conn = mysqli_connect(
    $host,
    $username,
    $password,
    $database
);

if (!$conn) {
    die("Koneksi gagal : " . mysqli_connect_error());
}
}?>
<?php
$conn = new mysqli("localhost", "root", 'root', "simlab");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>