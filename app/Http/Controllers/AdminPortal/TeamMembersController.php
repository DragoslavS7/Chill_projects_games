<?php

namespace App\Http\Controllers\AdminPortal;

use App\ClientPortal;
use app\Helpers\SendVerificationMail;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;

class TeamMembersController extends Controller
{
    protected $sendVerificationMailHelper;

    public function __construct()
    {
        $this->sendVerificationMailHelper = new SendVerificationMail();
    }

    function index(){

        $areThereAnyTeamMembers = User::count() > 0;

        return view('admin-portal.team-members.index', ['user' => \Auth::user(),
                                                              'areThereAnyTeamMembers'=>$areThereAnyTeamMembers]
            );
    }

    function create(){
        $formUrl = route('admin-portal.team-members.store');
        $teamMember = new User();
        $clientPortals = ClientPortal::lists('company_name', 'id')->toArray();
        $clientPortals = ['' =>'Select Client Portal'] + $clientPortals;

        return view('admin-portal.team-members.create', ['user' => \Auth::user(),
                                                              'teamMember'=>$teamMember,
                                                              'formUrl' => $formUrl,
                                                              'clientPortals' => $clientPortals
            ]);
    }

    function edit($id){
        $formUrl = route('admin-portal.team-members.update', $id);
        $teamMember = User::findOrFail($id);

        $clientPortals = ClientPortal::lists('company_name', 'id')->toArray();
        $clientPortals = ['' =>'Select Client Portal'] + $clientPortals;

        return view('admin-portal.team-members.edit', [  'user' => \Auth::user(),
                                                              'teamMember'=>$teamMember,
                                                              'formUrl' => $formUrl,
                                                              'clientPortals' => $clientPortals
        ]);
    }

    function save($input, User $user, $rules, $sendEmail=false){

        if ($user->validate($input,$rules)) {

            DB::beginTransaction();
            try{

                if(array_key_exists('password', $input)){
                    $input['password'] = bcrypt($input['password']);
                }

                if(array_key_exists('role',$input) && $input['role'] == User::ROLES['uberAdmin'] && isset( $input['client_portal_id'] ) ){
                    unset($input['client_portal_id']);
                }

                $user->fill($input);
                $user->save();

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

        $user = new User(['client_portal_id'=>$input['client_portal_id']]);

        $result = $this->save($input, $user,  $user->rules, true);

        if ($result['success']) {
            return redirect()->route('admin-portal.team-members.index')->with('success','Team member successfully created.');
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

        $diff =  array_diff($csv[0], ['#',	'first_name', 'last_name', 'email', 'password', 'password_confirmation', 'role', 'client_portal_id']);

        if(count($diff) > 0){
            $diffError = implode(",",$diff);
            return redirect()->back()
                ->with('error', 'Invalid csv file, the data did not coincide. Your data is: '.$diffError);
        }

        $errors = [];
        $added = 0;
        array_walk($csv, function (&$input, $index) use ($csv, &$errors, &$added) {
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

                    $result = $this->save($input, $user, $rules, true);

                    if (!$result['success']) {
                        $errors[$index] = $result['errors'];
                    }else{
                        $added++;
                    }
                }
            }
        });

        return redirect()->back()
            ->with('success-csv-upload', "Successfully added $added team members!")
            ->with('fail-csv-upload', $errors);
    }

    function update($id, Request $request){
        $user = User::findOrFail($id);
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

        if($user->role == 'uber_admin'){
            $input['role'] = 'uber_admin';
        }

        $result = $this->save($input, $user, $rules);

        if ($result['success']) {
            return redirect()->route('admin-portal.team-members.index')->with('success','Team member successfully updated.');;
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function resendVerify($id){
        app('App\Http\Controllers\Auth\AuthController')->resendVerification($id);
        return redirect()->back();
    }

    function dataTables(){
        return \DataTables::of(User::query()->with('clientPortal')->where('users.role','=','uber_admin')->orWhere('users.role','=','admin'))->make(true);
    }

    function destroy($id){
        try {
            $user = User::findOrFail($id);

            if ((int)$id === \Auth::user()->id) {
                return response()->json([ 'error' => 'You can not delete your own account!' ], 400);
            }

            if ($user->isUberAdmin() && User::where('role', 'uber_admin')->count() === 1) {
                return response()->json([ 'error' => 'Admin portal MUST have at least one uber admin!' ], 400);
            }

            if (!$user->isUberAdmin()) {
                if ($user->clientPortal->default_admin_id === (int)$id) {
                    return response()->json(['error' => 'You can not delete client portal default admin!'], 400);
                }
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
                $user = User::findOrFail($id);

                $usersCount++;

                if ((int)$id === \Auth::user()->id) {
                    continue;
                }
                if ($user->isUberAdmin() && User::where('role', 'uber_admin')->count() === 1) {
                    continue;
                }
                if (!$user->isUberAdmin()) {
                    if ($user->clientPortal->default_admin_id === (int)$id) {
                        continue;
                    }
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