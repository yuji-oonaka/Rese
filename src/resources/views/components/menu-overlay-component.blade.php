<div id="menu-overlay" class="menu-overlay">
    <div class="menu-content">
        @auth
            {{-- 管理者用メニュー --}}
            @role('admin')
                <a href="{{ route('admin.dashboard') }}">管理者ダッシュボード</a>
            @endrole

            {{-- 店舗代表者用メニュー --}}
            @role('representative')
                <a href="{{ route('representative.dashboard') }}">店舗管理ダッシュボード</a>
            @endrole

            {{-- 共通メニュー --}}
            <a href="{{ route('shop.list') }}">Home</a>
            <a href="{{ route('mypage') }}">Mypage</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-link">Logout</button>
            </form>
        @else
            {{-- 未認証ユーザーメニュー --}}
            <a href="{{ route('shop.list') }}">Home</a>
            <a href="{{ route('register') }}">Registration</a>
            <a href="{{ route('login') }}">Login</a>
        @endauth
        <button class="close-btn" onclick="toggleMenu()">X</button>
    </div>
</div>