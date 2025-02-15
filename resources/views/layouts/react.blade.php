<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico" />
    <title>Roundnet Scores Keeper</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {!! Analytics::render() !!}
</head>
<body>
        @yield('content')

        <div class="container">
            <div class="row justify-content-md-center text-center">
                <div class="col-md-12">
                    <div class="m-b-md">
                        <a href="/games"><< Back to games</a>
                    </div>
                </div>
            </div>
        </div>
    <script src="{{ asset('js/game-live.js') }}"></script>
@include('components/copyright')
</body>
</html>
