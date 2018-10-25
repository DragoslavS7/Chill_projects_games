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

    @if(Session::has('success'))
        <div class="row p-20">
            <div class="alert alert-success text-center">
                {{ Session::get('success')}}
            </div>
        </div>
    @endif

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

    {{ Form::open(['class' => 'fn-s-19', 'route' => 'user.auth.password-reset-request' ]) }}
        <div class="row mb-8">
             <div class="col-xs-4 text-right">
                {{ Form::label('email', 'Email') }}
            </div>
            <div class="col-xs-8 {{ $errorClass }}">
                {{ Form::email('email', null, [ 'class' => 'form-control' , 'id' => 'email']) }}
            </div>
        </div>

        <div class="row mb-8">
            <div class="col-xs-8 col-xs-offset-4">
                {{ Form::submit('Reset My Password', ['class' => 'btn btn-default btn-block']) }}
            </div>
        </div>
    {{ Form::close() }}
@endsection