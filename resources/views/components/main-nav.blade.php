{{-- <div class="links">
    <a href="/">Home</a>
    @guest
    <a href="{{ route('login') }}">{{ __('Login') }}</a>
    @if (Route::has('register'))
        <a href="{{ route('register') }}">{{ __('Register') }}</a>
    @endif
    @else
        <a href="/games">Games</a>
        <a href="/friends">Friends</a>
        <a href="/user/stats">Your stats</a>
        <a href="/user/account">Account</a>
        <a href="/logout">Logout</a>
    @endguest
</div> --}}
<ul class="nav justify-content-center">
    <li class="nav-item">
        <a class="nav-link" href="/">Home</a>
    </li>
    @guest
    <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
    </li>
    @if (Route::has('register'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
    </li>
    @endif
    @else
    <li class="nav-item">
        <a class="nav-link" href="/games">Games</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/friends">Friends</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="/user/stats">My stats</a>
            <a class="dropdown-item" href="/user/account">My profil</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="/logout">Logout</a>
        </div>
    </li>
    @endguest
</ul>