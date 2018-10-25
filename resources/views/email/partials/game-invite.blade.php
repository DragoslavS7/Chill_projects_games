@extends('email.index')
@section('page-title', 'Account verification')
@section('content')
    <p>
        <b>Congratulations!</b>
        You’ve been invited to play an exclusive game at MyArcadeChef.com!
    </p>
    <img src="images/app_icon.png" alt="">
    <br />
    <p>
        <b>CLICK HERE TO PLAY</b>
        <a href="{{ $gameUrl }}">{{ $gameUrl }}</a>

    </p>
    <br />
    <p>If you have any questions, please contact our support and open a ticket at</p>
    <a href="https://www.myarcadechef.com/support/">
        https://www.myarcadechef.com/support/
    </a>
    <br />
    <p>
        Or contact us directly via email at
        <a href="mailto:support@myarcadechef.com">support@myarcadechef.com</a>
    </p>
@endsection