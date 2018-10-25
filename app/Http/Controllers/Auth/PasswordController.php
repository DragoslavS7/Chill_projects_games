<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PasswordReset;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function forgotPassword(){
        return view('auth.forgot-password');
    }

    protected function passwordResetRequest(Request $request){
        $input = $request->all();

        $clientPortal = $request->clientPortal;

        $validator =  \Validator::make($request->all(), [
            'email' => 'required|exists:users,email'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator);
        }
        $user = User::where('email',$input['email']);

        if(isUberAdminPortal()){
            $user->where('role', User::ROLES['uberAdmin']);
        }else{
            $user->where('client_portal_id', $clientPortal->id);
        }

        $user = $user->first();

        if(!$user){
            $validator->errors()->add('email', 'The selected email is invalid.');
            return back()->withErrors($validator);
        }


        DB::beginTransaction();
        $token = str_random(64);

        $passwordReset = PasswordReset::where('email', $user->email)->first();

        if($passwordReset){
            $passwordReset->update([
                'token' => $token
            ]);
        }else{
            PasswordReset::create([
                'email' => $user->email,
                'token' => $token
            ]);
        }

        try {
            Mail::send(
                'email.partials.reset-password',
                [
                    'user' => $user,
                    'clientPortal' => $clientPortal
                ],
                function ($message) use ($user) {
                    $message->to($user->email, $user->first_name)->subject('Password Reset requested from MyArcadeChef.com');
                }
            );
            DB::commit();
        }catch (\Exception $e){
            DB::rollback();
            return back()->withErrors([$e->getMessage()]);
        }

        return back()->with('success',' email with reset password instructions sent.');

    }

    protected function passwordResetForm(Request $request){

        $token = $request->token;

        $email =  DB::table('password_resets')->where('token',$token)->select('email')->first();

        if(!$email){
            return view('auth.forgot-password')->withErrors(' verification link not valid please request another ticket.');
        }

        $user = User::where('email',$email->email)->first();

        if(!$user){
            return view('auth.forgot-password')->withErrors(' verification link not valid please request another ticket.');
        }

        return view('auth.reset-password',['user'=>$user,'token'=>$token]);
    }

    protected function passwordReset(Request $request){
        $input = $request->all();

        $validator =  \Validator::make($input, [
            'email' => 'required|exists:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator);
        }

        $passwordReset =  PasswordReset::where('token',$input['token'])->where('email',$input['email'])->first();

        if(!$passwordReset){
            return view('auth.forgot-password')->withErrors(' verification not valid please request another ticket.');
        }

        if($request->clientPortal){
            $clientPortalId = (int)$request->clientPortal->id;
            $user = User::where('email',$input['email'])->where('client_portal_id',$clientPortalId)->first();
        }
        else{
            $user = User::where('email',$input['email'])->first();
        }

        if(!$user){
            return view('auth.forgot-password')->withErrors(' verification not valid please request another ticket.');
        }


        DB::beginTransaction();
        try{

            if(array_key_exists('password', $input)){
                $input['password'] = bcrypt($input['password']);
            }

            $user->fill($input);
            $user->save();

            DB::commit();
            $result = ['success' => true];
        }catch (\Exception $e){
            DB::rollback();

            $errors = new MessageBag();
            $errors->add('save', $e->getMessage());

            $result = ['success' => false, 'errors' => $errors];
        }

        if ($result['success']) {
            return redirect()->route('user.auth.login');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }
}
