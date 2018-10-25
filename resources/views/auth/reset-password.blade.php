@extends('layouts.auth')

@section('page-title', 'Reset Password')

@section('auth-content')
    <div class="row">
        <div class="col-xs-offset-4 col-xs-8">
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

    <?php
        $errorClass = '';
    ?>

    @if($errors->any())
        <div class="row p-20">
            <div class="col-xs-12 alert alert-danger">
                <p class="text-center"><i>{{$errors->first()}}</i></p>
            </div>
        </div>
        <?php
            $errorClass = ' has-error';
        ?>
    @endif

    {{ Form::open(['class' => 'fn-s-19', 'route' => 'user.auth.password-reset' ]) }}
        <div class="row mb-8">
             <div class="col-xs-4 text-right">
                {{ Form::label('email', 'Email') }}
            </div>
            <div class="col-xs-8">
                {{ Form::email('email', $user->email, [ 'class' => 'form-control' , 'id' => 'email','readonly']) }}
            </div>
            <div class="col-xs-12 d-none">
                {{ Form::email('token', $token, [ 'class' => 'form-control' , 'id' => 'token','readonly']) }}
            </div>
        </div>

    <div class="row mt-5">
        <div class="col-xs-4 text-right password">
            <b>{{ Form::label('password', 'Password*') }}</b>
        </div>
        <div class="col-xs-8 {{ $errorClass }} password">
            {{ Form::password('password',  [ 'class' => 'form-control' , 'id' => 'password']) }}
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xs-4 text-right password_confirmation">
            <b>{{ Form::label('password_confirmation', 'Confirm Password*') }}</b>
        </div>
        <div class="col-xs-8 {{ $errorClass }} password_confirmation">
            {{ Form::password('password_confirmation',  [ 'class' => 'form-control' , 'id' => 'password_confirmation']) }}
        </div>
    </div>

    <div class="row mb-8 mt-5">
        <div class="col-xs-8 col-xs-offset-4">
            {{ Form::submit('Reset My Password', ['class' => 'btn btn-default btn-block','id'=>'reset']) }}
        </div>
    </div>
    {{ Form::close() }}
@endsection