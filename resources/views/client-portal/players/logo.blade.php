@extends('layouts.auth')

@section('page-title', 'Login')

@section('auth-content')
    <div class="row">
        <div class="col-xs-12">
            @if(request()->clientPortal->logo)
                    <img src="{{request()->clientPortal->logo}}" class="h-100 mt-10 mb-10 img-responsive m-0-auto">
            @else
                <p class="client-logo-placeholder">CLIENT LOGO</p>
            @endif
        </div>
    </div>

    <?php
        $errorClass = '';
    ?>

    @if($errors->any())
        <div class="row">
            <div class="col-xs-10 col-xs-offset-2">
                <p class="text-center text-danger"><i>{{$errors->first()}}</i></p>
            </div>
        </div>
        <?php
            $errorClass = ' has-error';
        ?>
    @endif

    <div class="row mb-8">
        <div class="col-xs-12 text-center">
            @if(request()->clientPortal->company_name)
                <h2>{{ request()->clientPortal->company_name}}</h2>
            @else
                <p class="client-logo-placeholder">CLIENT NAME</p>
            @endif
        </div>
    </div>
@endsection