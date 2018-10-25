@extends('email.index')
@section('page-title', 'Account verification')
@section('content')

    <p>You’re one step away! </p>
    <p>Please click this link to verify your email and to finalize your new account!</p>
    <p>
        <b>Username: {{ $user->clientPortal->email }}</b>
    </p>
    <p>
        <b>Server ip: {{ $serverIp }}</b>
    </p>
    <p>
        <b>Subdomain: {{ $user->clientPortal->sub_domain}}</b>
    </p>
    <br />
    <p>If you did not request this account, please contact our support and open a ticket at</p>
    <a href="https://www.myarcadechef.com/support/">
        https://www.myarcadechef.com/support/
    </a>
    <br/>
    <p>
        Or contact us directly via email at
        <a href="mailto:support@myarcadechef.com">support@myarcadechef.com</a>
    </p>
@endsection