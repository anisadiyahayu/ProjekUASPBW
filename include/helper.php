<?php
/**
 * Helper functions for security
 */

// Generate CSRF Token
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validate CSRF Token
function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Escape output untuk mencegah XSS
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Flash message
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Format tanggal Indonesia
function formatTanggal($datetime) {
    if (!$datetime) return '-';
    return date('d M Y', strtotime($datetime));
}

// Format datetime Indonesia
function formatDateTime($datetime) {
    if (!$datetime) return '-';
    return date('d M Y H:i', strtotime($datetime));
}

// Badge status transaksi
function getStatusBadge($status) {
    $badges = [
        'Menunggu' => 'bg-yellow-100 text-yellow-700',
        'Disetujui' => 'bg-green-100 text-green-700',
        'Ditolak' => 'bg-red-100 text-red-700',
        'Sedang Dipinjam' => 'bg-blue-100 text-blue-700',
        'Menunggu Verifikasi Pengembalian' => 'bg-orange-100 text-orange-700',
        'Selesai' => 'bg-gray-100 text-gray-700',
        'Dibatalkan' => 'bg-slate-100 text-slate-700'
    ];
    return $badges[$status] ?? 'bg-gray-100 text-gray-700';
}

// Badge kondisi barang
function getKondisiBadge($kondisi) {
    return $kondisi === 'Bagus' 
        ? 'bg-green-100 text-green-700' 
        : 'bg-red-100 text-red-700';
}

// Log aktivitas
function logAktivitas($conn, $aktivitas, $id_user = null, $role = null) {
    $stmt = $conn->prepare("INSERT INTO activity_logs (aktivitas, id_user, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $aktivitas, $id_user, $role);
    $stmt->execute();
}
?>