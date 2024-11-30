<div id="menu-overlay" class="menu-overlay">
    <div class="menu-content">
        @auth
            <a href="{{ route('shop.list') }}">Home</a>
            <form method="POST" action="{{ route('logout') }}" >
                @csrf
                <button type="submit" class="btn-link">Logout</button>
            </form>
            <a href="{{ route('mypage') }}">Mypage</a>
        @else
            <a href="{{ route('shop.list') }}">Home</a>
            <a href="{{ route('register') }}">Registration</a>
            <a href="{{ route('login') }}">Login</a>
        @endauth
        <button class="close-btn" onclick="toggleMenu()">X</button>
    </div>
</div>