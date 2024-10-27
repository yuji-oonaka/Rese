<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shop_list.css') }}">
    <script src="https://kit.fontawesome.com/9d85be4431.js" crossorigin="anonymous"></script>
</head>
<body>
<header>
    <div class="menu-icon" onclick="toggleMenu()">&#9776;</div>
</header>

<div id="menu-overlay" class="menu-overlay">
    <div class="menu-content">
        @auth
            <a href="{{ route('shop.list') }}">Home</a>
            <a href="{{ route('logout') }}">Logout</a>
            <a href="{{ route('mypage') }}">Mypage</a>
        @else
            <a href="{{ route('shop.list') }}">Home</a>
            <a href="{{ route('register') }}">Registration</a>
            <a href="{{ route('login') }}">Login</a>
        @endauth
        <button class="close-btn" onclick="toggleMenu()">X</button>
    </div>
</div>

<main>
    @yield('content')
</main>

<footer>
    <!-- フッターの内容 -->
</footer>

<script>
function toggleMenu() {
    const menuOverlay = document.getElementById('menu-overlay');
    menuOverlay.classList.toggle('active');
}
</script>

</body>
</html>