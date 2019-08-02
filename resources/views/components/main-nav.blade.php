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
            <a class="dropdown-item" href="/user/{{ auth()->id() }}">My profil</a>
            <a class="dropdown-item" href="/user/account">My account</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="/logout">Logout</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                @if ( auth()->user()->unreadNotifications->count() > 0 )
                    <span class="badge badge-dark">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
                Notifications
        </a>
        <div class="dropdown-menu">
            @if ( auth()->user()->unreadNotifications->count() > 0 )
                    @foreach (auth()->user()->unreadNotifications as $notification)
                        <a class="dropdown-item" href="/user/{{ auth()->id() }}">{{ $notification->data['badge_name'] }}</a>
                    @endforeach
            @else
                        <p class="dropdown-item">You don't have any unread notification</p>
            @endif
        </div>
    </li>
    @endguest
</ul>