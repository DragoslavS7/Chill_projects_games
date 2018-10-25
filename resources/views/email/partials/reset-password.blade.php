@extends('email.index')
@section('page-title', 'Reset Password')
@section('content')
    <p>We’re sorry you have forgotten your password!</p>
    <p>Please click this link to verify your password reset request and to create a new password.</p>
    <br />
    <p>
        CLICK HERE
        <a href="{{ request()->root() }}/password-reset-request/{{ $user->passwordToken->token }}">{{ request()->root() }}/password-reset-request/{{ $user->passwordToken->token }}</a>
    </p>
    <br />
    <p>If you did not request this password reset, please contact our support and open a ticket at</p>
    <a href="https://www.myarcadechef.com/support/">
        https://www.myarcadechef.com/support/
    </a>
    <p>
        Or contact us directly via email at
        <a href="mailto:support@myarcadechef.com">support@myarcadechef.com</a>
    </p>
@endsection