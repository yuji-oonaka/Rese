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
    <x-scripts-component />
    @yield('js')
</body>
</html>