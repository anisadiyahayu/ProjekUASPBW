<?php

include "auth.php";
include "../koneksi.php";

if(isset($_POST['id']))
{

    $id = $_POST['id'];

    mysqli_query(
        $conn,
        "DELETE FROM categories
        WHERE id='$id'"
    );

}

header("Location:kategori.php");
exit;