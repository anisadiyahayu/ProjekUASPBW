<?php
function statusStok(int $stok, int $minimum): string {
    if ($stok <= 0) {
        return "Habis";
    }
    if ($stok <= $minimum) {
        return "Menipis";
    }
    return "Tersedia";
}
?>