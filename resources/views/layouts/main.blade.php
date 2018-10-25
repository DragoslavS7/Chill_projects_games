<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page-title')</title>

    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    @if($clientPortal = request()->clientPortal)
        <style>
            @foreach($clientPortal->custom_style as $style)
                  {{$style->selector}} {
                     {{$style->style}}
                   }
            @endforeach
       </style>
    @endif
</head>
<body class="bg-athens-gray">
    @if(env('APP_ENV') != 'production')
        <div class="alert alert-info text-center m-0-auto zi-1 fn-s-20" role="alert">
            <b>Note:</b> this is <b>{{ env('APP_ENV') }}</b> environment.
        </div>
    @endif

    @if(Session::has('tokenMismatchError'))
        <div class="alert alert-danger fade in">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            {{Session::get('tokenMismatchError')}}
        </div>
    @endif

    @yield('content')
    <script src="{{ elixir('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/dateRangeFilter.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/filtersSession.js') }}"></script>
    @stack('scripts')
</body>
</html>
