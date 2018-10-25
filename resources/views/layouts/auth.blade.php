@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-3 bg-white mt-20p shadow">
                @yield('auth-content')
            </div>
        </div>
    </div>
@endsection