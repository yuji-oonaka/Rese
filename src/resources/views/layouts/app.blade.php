<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @yield('css')
<header>
    <div class="header-container">
        <h1>
            <div class="icon-box">
                <i class="fas fa-align-left"></i>
            </div>
            Rese
        </h1>
    </div>
</header>

    <main>
        @yield('content')
    </main>

    <footer>
        <!-- フッターの内容 -->
    </footer>
</body>
</html>