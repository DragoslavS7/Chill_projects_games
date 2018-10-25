@extends('email.index')
@section('page-title', 'Account verification')
@section('content')
    <p>You’re all done and your account is now active!</p>
    <p>
        <b>Username: {{ $user->email }}</b>
    </p>
    <br />
    <p>
        CLICK HERE
        @if($user->isUberAdmin())
            <a href="{{ route('user.auth.login') }}">{{ route('user.auth.login') }}</a>
        @else
            <a href="{{ $user->clientPortal->baseUrl() . route('user.auth.login', [], false) }}">{{ $user->clientPortal->baseUrl() . route('user.auth.login', [], false) }}</a>
        @endif
    </p>
    <br />
    <p>If you did not request this account confirmation, please contact our support and open a ticket at</p>
    <a href="https://www.myarcadechef.com/support/">
        https://www.myarcadechef.com/support/
    </a>
    <br />
    <p>
        Or contact us directly via email at
        <a href="mailto:support@myarcadechef.com">support@myarcadechef.com</a>
    </p>
@endsection