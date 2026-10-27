<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan 250441100115</title>
    @vite(['resources/css/app.css'])
</head>
<body>

    <header class="header">
        <h1>Perpustakaan 250441100115</h1>
        <p>Sistem Informasi Perpustakaan Sederhana</p>
    </header>

    <nav class="navbar">
        <a href="{{ route('home') }}">Beranda</a>
        <a href="{{ route('buku.index') }}">Daftar Buku</a>
    </nav>

    <main class="container">
        @yield('content') 
    </main>

    <footer class="footer">
        <p>&copy; 2026 Perpustakaan 250441100115</p>
    </footer>

</body>
</html>