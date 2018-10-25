<?php

namespace App\Http\Controllers\ClientPortal;

use app\Helpers\SendVerificationMail;
use App\Services\XapiMyArcadeChef;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;

class TeamMembersController extends Controller
{
    protected $sendVerificationMailHelper;

    function __construct(){
        $this->xApiMyArcadeChef = new XapiMyArcadeChef();
        $this->sendVerificationMailHelper = new SendVerificationMail();
    }

    function index(Request $request){
        $areThereAnyTeamMembers = $request->clientPortal->users()->count() > 0;

        return view('client-portal.team-members.index', ['user' => \Auth::user(),
                                                               'areThereAnyTeamMembers'=>$areThereAnyTeamMembers
            ]);
    }

    function view($id,Request $request){
        $id = (int)$id;
        $teamMember = $request->clientPortal->users->where('id',$id)->first();

        if($teamMember){
            return view('client-portal.team-members.view', [  'user' => \Auth::user(),
                'teamMember'=>$teamMember
            ]);
        }else{
            return redirect()->route('client-portal.team-members.index')->with('error','Team member does not exist on this client portal.');
        }
    }


    function create(){
        $formUrl = route('client-portal.team-members.store');
        $teamMember = new User();
        return view('client-portal.team-members.create', ['user' => \Auth::user(),
                                                                'teamMember'=>$teamMember,
                                                               'formUrl' => $formUrl
            ]);
    }

    function edit($id,Request $request){
        $id = (int)$id;
        $teamMember = $request->clientPortal->users->where('id',$id)->first();

        if($teamMember){
            $formUrl = route('client-portal.team-members.update', $id);
            return view('client-portal.team-members.edit', [  'user' => \Auth::user(),
                'teamMember'=>$teamMember,
                'formUrl' => $formUrl
            ]);
        }
        else{
            return redirect()->route('client-portal.team-members.index')->with('error','Team member does not exist on this client portal.');
        }
    }

    function save($input, $clientPortalId, User $user, $rules, $sendEmail=false){

        $input['client_portal_id'] = $clientPortalId;

        if ($user->validate($input,$rules)) {

            DB::beginTransaction();
            try{
                $input['client_portal_id'] = $clientPortalId;

                if(array_key_exists('password', $input)){
                    $input['password'] = bcrypt($input['password']);
                }

                $user->fill($input);
                $user->verification_token = $this->sendVerificationMailHelper->createVerificationToken();
                $user->save();

                $loggedUser = \Auth::user();
                $this->xApiMyArcadeChef->createUser($loggedUser, $user);

                if(!$user->hasRole(User::ROLES['player']) && $sendEmail) {
                    $this->sendVerificationMailHelper->sendMail($user);
                }

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();

                $errors = new MessageBag();
                $errors->add('save', $e->getMessage());

                return ['success' => false, 'errors' => $errors];
            }

            return ['success' => true];
        }else{
            return ['success' => false, 'errors' => $user->errors()];
        }
    }

    function store(Request $request){
        $input = $request->all();
        $input['verification_token'] = $this->sendVerificationMailHelper->createVerificationToken();
        $input['is_verified'] = 0;

        $user = new User(['client_portal_id'=>$request->clientPortal->id]);

        if($input['role'] == 'player'){
            $rules = $user->getUpdateRules();
        }
        else{
            $rules = $user->rules;
        }

        $result = $this->save($input, $request->clientPortal->id,  $user, $rules, true);

        if ($result['success']) {
            return redirect()->route('client-portal.team-members.index')->with('success','Team member successfully created.');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function update($id, Request $request){
        $id = (int)$id;
        $user =  $request->clientPortal->users->where('id',$id)->first();

        if(!$user){
            return redirect()->route('client-portal.team-members.index')->with('error','Team member does not exist on this client portal.');
        }

        $input = $request->all();
        $rules =[];

        if(!isset($input['is_active'])){
            $rules = $user->getUpdateRules();

            if($user->role != 'uber_admin'){
                $rules['client_portal_id'] = 'required';
            }
        }
        else{
            $rules['is_active'] = 'required';
        }

        $result = $this->save($input, $request->clientPortal->id, $user, $rules);

        if ($result['success']) {

            return redirect()->route('client-portal.team-members.index')->with('success','Team member successfully updated.');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function bulkStore(Request $request)
    {
        $csvPath = $request->file('csv')->getRealPath();

        $csv = array_map('str_getcsv', file($csvPath));

        if(count($csv) == 0){
            return redirect()->back()
                ->with('error', 'Incorrect file type or file is empty.');
        }

        $diff =  array_diff($csv[0], ['#',	'first_name', 'last_name', 'email', 'password', 'password_confirmation', 'role']);

        if(count($diff) > 0){
            $diffError = implode(",",$diff);
            return redirect()->back()
                ->with('error', 'Invalid csv file, the data did not coincide. Your data has the following invalid fields: '.$diffError);
        }


        $errors = [];
        $added = 0;
        array_walk($csv, function (&$input, $index) use ($csv, &$errors, &$added, $request) {
            if (trim(implode('', $input)) !== ''){
                $input = array_combine($csv[0], $input);

                if ($index > 0) {
                    $user = User::where('email', $input['email'])->first();

                    $rules = [];
                    if(!$user){
                        $user = new User();
                        $rules = $user->rules;
                    }else{
                        $rules = $user->getUpdateRules();
                    }

                    $rules['role'] = 'in:viewer,player';

                    $result = $this->save($input, $request->clientPortal->id, $user, $rules, true);

                    if (!$result['success']) {
                        $errors[$index] = $result['errors'];
                    }else{
                        $added++;
                    }
                }
            }
        });

        return redirect()->back()
            ->with('success-csv-upload', "Successfully $added team members added!")
            ->with('fail-csv-upload', $errors);
    }

    function resendVerify($id){
        app('App\Http\Controllers\Auth\AuthController')->resendVerification($id);
        return redirect()->back();
    }

    function dataTables(Request $request){
        return \DataTables::of($request->clientPortal->users())->make(true);
    }

    function destroy($id, Request $request){
        try {
            $id = (int)$id;
            $user =  $request->clientPortal->users->where('id', $id)->first();

            if(!$user){
                return redirect()->route('client-portal.team-members.index')->with('error','Team member does not exist on this client portal.');
            }

            if ($id === \Auth::user()->id) {
                return response()->json([ 'error' => 'You can not delete your own account!' ], 400);
            }

            if ($user->clientPortal->default_admin_id === $id) {
                return response()->json([ 'error' => 'You can not delete client portal default admin!' ], 400);
            }

            $user->forceDelete();

            return response()->json([ 'success' => 'Team member successfully deleted.' ], 200);
        } catch (\Exception $e) {
            Log::error('Error while deleting team member: ', ['message' => $e->getMessage()]);
            return response()->json([ 'error' => 'There was a problem deleting team member.' ], 500);
        }
    }

    function destroyBulk(Request $request){
        $ids = $request->ids;
        $usersCount = 0;
        $usersDeletedCount = 0;

        try {
            foreach ($ids as $id){
                $id = (int)$id;
                $user = $request->clientPortal->users->where('id', $id)->first();

                $usersCount++;

                if ((int)$id === \Auth::user()->id) {
                    continue;
                }
                if ($user->clientPortal->default_admin_id === $id) {
                    continue;
                }

                $user->forceDelete();

                $usersDeletedCount++;
            }

            return response()->json([ 'success' => 'You deleted ' . $usersDeletedCount . ' from ' . $usersCount . ' team members.' ], 200);
        } catch (\Exception $e) {
            Log::error('Error while deleting bulk team members: ', ['message' => $e->getMessage()]);
            return response()->json([ 'error' => 'There was a problem deleting bulk team members.' ], 500);
        }
    }
}