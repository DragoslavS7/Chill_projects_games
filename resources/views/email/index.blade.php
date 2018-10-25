<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page-title')</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }

        .container{
            width: 70%;
            margin: 0 auto;
            padding: 50px 10px;
        }

        .fn-c-gray{
            color: #777777;
        }

        .d-block{
            display: block;
        }

        .logo{
            padding: 50px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        @yield('content')
        <br />
        <p>Warm Regards,</p>
        <p>MyArcadeChef.com Support Team</p>
        <a href="https://myarcadechef.com" class="d-block">https://myarcadechef.com</a>
        <a href="https://restaurantplaybooks.com/"  class="d-block">https://restaurantplaybooks.com/</a>
        @if($clientPortal)
            <img src="{{ request()->root() . $clientPortal->logo }}" alt="" class="logo">
        @endif
        <h4>Attract, Engage, Train, and Retain ‘A’ Players</h4>
        <br />
        <p class="fn-c-gray">
            <i>
                The content of this email is confidential and intended for the recipient(s) specified in message only.
                It is strictly forbidden to share any part of this message with any third party, without a written consent of the sender.
                If you received this message by mistake, please reply to this message and follow with its deletion,
                so that we can ensure such a mistake does not occur in the future.
            </i>
        </p>
    </div>
</div>

</body>
</html>
