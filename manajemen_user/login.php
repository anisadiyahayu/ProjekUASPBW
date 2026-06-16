<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login SIMLAB</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-700 via-blue-800 to-blue-950 flex items-center justify-center p-4">
    <div class="w-full max-w-5xl grid lg:grid-cols-2 gap-8 items-center">
        <div class="hidden lg:flex flex-col items-center justify-center text-white">
            <div class="w-32 h-32 bg-white/20 rounded-3xl flex items-center justify-center text-6xl">📦</div>
            <div class="text-center mt-8">
                <h1 class="text-4xl font-bold">Sistem Manajemen</h1>
                <h2 class="text-3xl font-semibold mt-2">Inventaris Laboratorium</h2>
                <p class="text-blue-100 mt-3">Universitas Singaperbangsa Karawang</p>
            </div>
        </div>
        <div class="bg-white rounded-3xl shadow-2xl p-10">
            <h2 class="text-3xl font-bold text-slate-800">Login ke Sistem</h2>
            <p class="text-slate-500 mt-2 mb-8">Silakan masuk menggunakan akun Anda</p>
            <form action="proses_login.php" method="POST">
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" required class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" required class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white py-3 rounded-xl font-semibold transition">Login</button>
            </form>
        </div>
    </div>
</body>
</html>