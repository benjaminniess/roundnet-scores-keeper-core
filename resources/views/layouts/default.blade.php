<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Roundnet Scores Keeper</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Save your Spikeball / Roundnet scores history and play againsts your friends" />
    <link rel="icon" href="favicon.ico" />
    <script src="{{ asset('js/app.js') }}"></script>
    {!! Analytics::render() !!}
</head>
<body>
    <div class="container">
        <div class="row justify-content-md-center text-center">
            <div class="col-md-12">
                <div class="title m-b-md">
                    <a href="/">Roundnet Scores Keeper</a>
                </div>
                @include( 'components.main-nav' )
            </div>
        </div>
    </div>

        <div class="container">
            @yield('content')
        </div>
    </div>

    @include('components/copyright')

</div>
</body>
</html>
