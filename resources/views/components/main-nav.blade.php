<div class="links">
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
</div>