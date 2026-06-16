<?php

include "auth.php";
include "koneksi.php";

$query = mysqli_query($conn, "
SELECT
items.*,
categories.nama AS kategori,
locations.nama_lokasi AS lokasi

FROM items

LEFT JOIN categories
ON items.id_kategori = categories.id

LEFT JOIN locations
ON items.id_lokasi = locations.id

ORDER BY items.id DESC
");

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Laporan Inventaris</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 12px;
        }

        th {
            background: #eee;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        @media print {

            button {
                display: none;
            }

        }
    </style>

</head>

<body>

    <button onclick="window.print()">
        Cetak / Simpan PDF
    </button>

    <h2>
        LAPORAN INVENTARIS LABORATORIUM
    </h2>

    <table>

        <tr>

            <th>No</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Lokasi</th>
            <th>Stok</th>
            <th>Kondisi</th>

        </tr>

        <?php

        $no = 1;

        while ($row = mysqli_fetch_assoc($query)) :

        ?>

            <tr>

                <td><?= $no++ ?></td>

                <td><?= $row['kode_barang'] ?></td>

                <td><?= $row['nama_barang'] ?></td>

                <td><?= $row['kategori'] ?></td>

                <td><?= $row['lokasi'] ?></td>

                <td><?= $row['stok'] ?></td>

                <td><?= ucfirst($row['kondisi']) ?></td>

            </tr>

        <?php endwhile; ?>

    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>

</html>