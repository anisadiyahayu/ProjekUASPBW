<?php
session_start();
require_once '../include/koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../profil/index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mencari user berdasarkan NPM / Username
    $stmt = $conn->prepare("SELECT * FROM users WHERE npm = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $status_akun = isset($row['status_akun']) ? $row['status_akun'] : 'Aktif';
        
        if ($status_akun !== 'Aktif') {
            $error = 'Akun Anda nonaktif.';
        } else {
            // Mendukung password teks biasa '123' atau password_verify jika sudah di-hash
            if ($password === $row['password'] || password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['npm']     = $row['npm'];
                $_SESSION['nama']    = $row['nama'];
                $_SESSION['role']    = $row['role'];
                $_SESSION['avatar']  = isset($row['avatar']) ? $row['avatar'] : '';
                
                header("Location: ../profil/index.php");
                exit;
            } else {
                $error = 'Password salah.';
            }
        }
    } else {
        $error = 'NPM/Username tidak ditemukan.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventaris Laboratorium</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        tailwind.config = { 
            theme: { 
                extend: { 
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: { 
                        primary: '#1e3b8a', // Diubah menjadi warna figma pilihanmu
                        card: '#ffffff', 
                        foreground: '#1e293b', 
                        muted: '#f1f5f9', 
                        'muted-foreground': '#64748b', 
                        'input-background': '#f8fafc', 
                        border: '#e2e8f0' 
                    } 
                } 
            } 
        }
    </script>
</head>
<body class="bg-[#1e3b8a] min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-5xl grid lg:grid-cols-2 gap-8 items-center">
        <div class="hidden lg:flex flex-col items-center justify-center text-white space-y-6">
            <div class="w-32 h-32 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center">
                <i data-feather="package" class="w-16 h-16 text-white"></i>
            </div>
            <div class="text-center space-y-3">
                <h1 class="text-4xl font-bold tracking-wide">Sistem Manajemen</h1>
                <h2 class="text-3xl font-semibold opacity-90">Inventaris Laboratorium</h2>
                <p class="text-blue-100/80 text-lg">Universitas Singaperbangsa Karawang</p>
            </div>
        </div>

        <div class="bg-card rounded-2xl shadow-2xl p-8 lg:p-12">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-foreground">Login ke Sistem</h2>
                <p class="text-muted-foreground mt-2 text-sm">Masukkan kredensial Anda untuk melanjutkan</p>
                <?php if($error): ?>
                    <p class="text-red-500 mt-3 text-sm bg-red-50 p-3 rounded-lg border border-red-200"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
            </div>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-2 text-foreground">NPM / Username</label>
                    <input type="text" name="username" class="w-full px-4 py-3 bg-input-background rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all text-sm" placeholder="Masukkan NPM atau Username" required />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-foreground">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" class="w-full px-4 py-3 bg-input-background rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all pr-12 text-sm" placeholder="Masukkan password" required />
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 p-2 hover:bg-muted rounded-lg transition-colors">
                            <i data-feather="eye" id="eyeIcon" class="w-5 h-5 text-muted-foreground"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full px-4 py-3 bg-primary text-white rounded-lg hover:bg-opacity-90 transition-all shadow-lg font-semibold text-sm tracking-wide">Login</button>
                
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-xs text-blue-900 leading-relaxed">
                        <span class="font-semibold">Info:</span> Akun mahasiswa dibuat oleh Admin atau Aslab. Hubungi admin laboratorium jika Anda belum memiliki akun.
                    </p>
                </div>
            </form>
            <div class="mt-8 text-center"><p class="text-xs text-muted-foreground">© 2024 Universitas Singaperbangsa Karawang. All rights reserved.</p></div>
        </div>
    </div>
    <script>
        feather.replace();
        function togglePassword() {
            var x = document.getElementById("password");
            var icon = document.getElementById("eyeIcon");
            if (x.type === "password") { 
                x.type = "text";
                icon.setAttribute("data-feather", "eye-off");
            } else { 
                x.type = "password"; 
                icon.setAttribute("data-feather", "eye");
            }
            feather.replace();
        }
    </script>
</body>
</html>