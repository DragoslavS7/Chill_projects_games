<?php

namespace App\Http\Controllers\Auth;

use app\Helpers\SendVerificationMail;
use App\User;
use Illuminate\Support\Facades\Log;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '';

    protected $sendVerificationMailHelper;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'doLogout']);

        if(isUberAdminPortal()){
            $this->redirectTo = route('admin-portal.client-portals.index');
        }else{
            $this->redirectTo = route('client-portal.games.index');
        }
        $this->sendVerificationMailHelper = new SendVerificationMail();
    }

    protected function login(){
        return view('auth.login');
    }

    protected function logUberAdmin($loginCredentials,$rememberMe){
        $loginCredentials['client_portal_id'] = 0;

        return \Auth::attempt($loginCredentials,$rememberMe);
    }

    protected function doLogin(Request $request){
        $input = $request->all();

        $loginCredentials = $request->only('email', 'password');
        $clientPortal = $request->clientPortal;


        if(isUberAdminPortal()){
            $loginCredentials['role'] = User::ROLES['uberAdmin'];
        }else{
            $loginCredentials['client_portal_id'] = $clientPortal->id;
        }

        $validator = Validator::make($input, User::LOGIN_RULES);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }
        $rememberMe = false;

        if(isset($input['remember_me']) && $input['remember_me']){
            $rememberMe = true;
        }

        if(\Auth::attempt($loginCredentials,$rememberMe) || $this->logUberAdmin($loginCredentials,$rememberMe)){
            $user = \Auth::user();

            if(!$user->is_verified){
                $resendUrl = route('user.resend','_ID_');
                $resendUrl = str_replace('_ID_',$user->id,$resendUrl);
                \Auth::logout();
                $validator->errors()->add('verified', "Please check your email and verify your account.  <a href='{$resendUrl}' class='fn-c-mine-shaft'>Send again?</a>");
                return back()
                    ->withErrors($validator)
                    ->withInput($request->except('password'));
            }

            if($user->isUberAdmin() && !isUberAdminPortal() && !$clientPortal->is_costumer_service_available ){
                \Auth::logout();
                $validator->errors()->add('login', 'Costumer service for this client is not enabled.');
            }else if(!$user->isUberAdmin() && $clientPortal->id != $user->client_portal_id) {
                \Auth::logout();
            }else{
                if(isUberAdminPortal()){
                    return redirect()->route('admin-portal.client-portals.index');
                }else{
                    if($user->hasRole(User::ROLES['player'])){
                        \Auth::logout();
                    }else{
                        return redirect()->route('client-portal.games.index');
                    }
                }
            }

        }

        $validator->errors()->add('login', 'Your Email or Password did not match. Try again or click Forgot login info.');

        return back()
            ->withErrors($validator)
            ->withInput($request->except('password'));

    }

    protected function doLogout(){
        \Auth::logout();
        return redirect()->route('user.auth.login');
    }

    function verifyAccount($token, Request $request){
        $user = User::where('verification_token',$token)->first();

        if(!$user){
            return view('auth.forgot-password')->withErrors(['save' => ' verification not valid please request another ticket.']);
        }

        $user->update(['is_verified' => 1]);

        $errors = [];
        if($user->role != 'player'){
            try {
                \Mail::send(
                    'email.partials.account-verified',
                    [
                        'user' => $user,
                        'clientPortal' => $request->clientPortal
                    ],
                    function ($message) use ($user) {
                        $message->to($user->email, $user->first_name)->subject('Account verified from MyArcadeChef.com');
                    }
                );
            }catch(\Exception $e){
                $errors[] = "Your account is verified but we couldn't send you an email because: " . $e->getMessage();
            }
        }

        if(isUberAdminPortal()){
            return redirect()->route('admin-portal.client-portals.index')->withErrors($errors);
        }else{
            return redirect()->route('client-portal.games.index')->withErrors($errors);
        }
    }

    function resendVerification($id){
        try {
            $user = User::find($id);
            $this->sendVerificationMailHelper->sendMail($user);

            return back();
        } catch (\Exception $e) {
            Log::error('Error while sending verification mail: ', ['message' => $e->getMessage()]);
        }
    }
}
