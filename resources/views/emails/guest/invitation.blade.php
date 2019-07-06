@component('mail::message')

    Hello {{ $guest_name }},

    It seems that you've had a good Roundnet game with your friend {{ $inviter_name }}!

    We've sent you a password reset link so you can define your password, discover you game stats and create so more games.

See you soon!<br>
{{ config('app.name') }}
@endcomponent
