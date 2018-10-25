@extends('layouts.auth')

@section('page-title', 'Not Authorized')

@section('auth-content')
    <div class="row">
        <div class="col-xs-offset-2 col-xs-8">
            @if(isUberAdminPortal())
                <p class="client-logo-placeholder">UBER ADMIN LOGO</p>
            @else
                @if(request()->clientPortal->logo)
                        <img src="{{request()->clientPortal->logo}}" class="h-100 mt-10 mb-10 img-responsive">
                @else
                    <p class="client-logo-placeholder">CLIENT LOGO</p>
                @endif
            @endif
        </div>
    </div>

        <div class="row mb-8">
            <div class="text-center fn-s-16">
                <p><b>@yield('errorMessage','You do not have permissions to view this page please contact your admin.')</b></p>
            </div>
        </div>

@endsection