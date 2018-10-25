<?php

namespace app\Helpers;

use App\User;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendVerificationMail
 * @package app\Helpers
 */
class SendVerificationMail
{
    /**
     * Method for sending verification mail.
     *
     * @param User $user
     */
    public function sendMail(User $user){
        $link = '';
        if($user->clientPortal){
            $link = $user->clientPortal->baseUrl() . route('user.verify', $user->verification_token, false);
        }else{
            $link = route('user.verify', $user->verification_token);
        }

        Mail::send(
            'email.partials.account-verification',
            [
                'user' => $user,
                'clientPortal' => $user->clientPortal,
                'link' => $link
            ],
            function ($message) use ($user) {
                $message->to($user->email, $user->first_name)->subject('Account verification from MyArcadeChef.com');
            }
        );
    }

    /*
     * Method for creating verification toke.
     */
    public function createVerificationToken()
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }
}
