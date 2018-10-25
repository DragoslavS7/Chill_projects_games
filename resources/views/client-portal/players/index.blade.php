@extends('layouts.auth')

@section('page-title', 'Home')

@section('auth-content')
    <div class="row">
        <div class="col-xs-12">
            @if(request()->clientPortal->logo)
                    <img src="{{request()->clientPortal->logo}}" class="h-100 mt-10 mb-10 img-responsive m-0-auto">
            @else
                <p class="client-logo-placeholder text-center">CLIENT LOGO</p>
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

    <div class="row mb-8">
        <div class="col-xs-offset-2 col-xs-8" id="index-game-wrapper">
            @foreach($games as $game)
                <hr />
                <div class="row p-20 index-game">
                    <a href="{{ route('client-portal.players.game',$game->url) }}">
                        <div class="col-md-4 col-xs-12">
                            <img src="{{ $game->game_icon ?  $game->game_icon : '/images/app_icon.png' }}" alt="" class="w-75 h-75 d-block m-0-auto">
                        </div>
                        <div class="col-md-8 col-xs-12 mt-25 text-center">
                            <p class="fn-s-16 fn-c-mine-shaft">
                                {{ $game->name }}
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
