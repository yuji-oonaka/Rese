<!DOCTYPE html>
<html lang="ja">
<head>
    <x-head-component />
</head>
<body>
<main>
    <x-menu-overlay-component />
    @yield('content')
</main>

<footer>
    <!-- フッターの内容 -->
</footer>
    <x-scripts-component />
</body>
</html>