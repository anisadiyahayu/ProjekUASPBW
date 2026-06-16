<?php

session_start();

include "../include/koneksi.php";

$email = $_POST['email'];
$password = $_POST['password'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM users
     WHERE email='$email'"
);

$data = mysqli_fetch_assoc($query);

if ($data) {

    if ($password == $data['password']) {

        $_SESSION['login'] = true;
        $_SESSION['id'] = $data['id'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        if ($data['role'] == 'admin') {

            header("Location: dashboard_admin.php");
        }

        elseif ($data['role'] == 'laboran') {

            header("Location: dashboard_admin.php");
        }

        elseif ($data['role'] == 'mahasiswa') {

            header("Location: dashboard_mahasiswa.php");
        }

    } else {

        echo "
        <script>
        alert('Password salah');
        window.location='login.php';
        </script>
        ";
    }

} else {

    echo "
    <script>
    alert('Email tidak ditemukan');
    window.location='login.php';
    </script>
    ";
}