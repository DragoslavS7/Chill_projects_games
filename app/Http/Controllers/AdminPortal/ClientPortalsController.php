<?php

namespace App\Http\Controllers\AdminPortal;

use App\ClientPortal;
use App\ClientPortalsGameTemplate;
use App\GameTemplate;
use app\Helpers\SendVerificationMail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;


class ClientPortalsController extends Controller
{
    protected $sendVerificationMailHelper;

    function __construct(){
        $this->sendVerificationMailHelper = new SendVerificationMail();
    }


    function index()
    {
        $areThereAnyClientPortals = ClientPortal::count() > 0;

        return view('admin-portal.client-portals.index', ['user' => \Auth::user(), 'areThereAnyClientPortals' => $areThereAnyClientPortals]);
    }

    function create()
    {
        $formUrl = route('admin-portal.client-portals.store');
        $assignedTemplates = GameTemplate::where('is_default',1)->lists('id')->toJson();
        $areThereAnyGameTemplates = GameTemplate::count() > 0;
        return view('admin-portal.client-portals.create', [ 'user' => \Auth::user(),
                                                    'clientPortal' => new ClientPortal(),
                                                    'defaultAdmin' => new User(),
                                                    'formUrl' => $formUrl,
                                                    'assignedTemplates' => $assignedTemplates,
                                                    'areThereAnyGameTemplates' => $areThereAnyGameTemplates
        ]);
    }

    function edit($id)
    {
        $clientPortal = ClientPortal::findOrFail($id);
        $defaultAdminId = (int)$clientPortal->default_admin_id;
        $defaultAdmin = User::findOrFail($defaultAdminId);
        $formUrl = route('admin-portal.client-portals.update', $id);

        $assignedTemplates = $clientPortal->gameTemplates->lists('id')->toJson();
        $areThereAnyGameTemplates = GameTemplate::count() > 0;

        return view('admin-portal.client-portals.edit', ['user' => \Auth::user(),
                                                 'clientPortal' => $clientPortal,
                                                 'defaultAdmin'=>$defaultAdmin,
                                                 'formUrl' => $formUrl,
                                                 'assignedTemplates' => $assignedTemplates,
                                                 'areThereAnyGameTemplates' => $areThereAnyGameTemplates
        ]);
    }

    function view($id)
    {
        $clientPortal = ClientPortal::findOrFail($id);
        $defaultAdminId = (int)$clientPortal->default_admin_id;
        $defaultAdmin = User::findOrFail($defaultAdminId);

        $assignedTemplates = $clientPortal->gameTemplates->lists('id')->toJson();
        $areThereAnyGameTemplates = GameTemplate::count() > 0;

        return view('admin-portal.client-portals.view', ['user' => \Auth::user(),
                                                 'clientPortal' => $clientPortal,
                                                 'defaultAdmin'=>$defaultAdmin,
                                                 'assignedTemplates' => $assignedTemplates,
                                                 'areThereAnyGameTemplates' => $areThereAnyGameTemplates
        ]);
    }

    function save(Request $request, ClientPortal $clientPortal, User $user, $rules){
        $inputs = $request->except(['_token']);

        if ($clientPortal->validate($inputs, $rules)) {
            DB::beginTransaction();

            try {
                // 1. Create client portal
                if($logo = $request->file('logo')){
                    $inputs['logo'] = saveImage($logo, 'client-portals/', 'logo',300,300);
                }

                $clientPortal->fill($inputs);
                $clientPortal->save();

                // 2. Create admin user and link it with client portal
                if(array_key_exists('password', $inputs)){
                    $inputs['password'] = bcrypt($inputs['password']);
                }

                $user->fill($inputs);
                $user->verification_token = $this->sendVerificationMailHelper->createVerificationToken();
                $user->role = User::ROLES['admin'];

                $user->client_portal_id = $clientPortal->id;
                $user->save();

                $clientPortal->default_admin_id = $user->id;
                $clientPortal->save();

                if(array_key_exists('assign_templates', $inputs) && $inputs['assign_templates']) {

                    // 4. Link game templates and client portal
                    $inputs['assign_templates'] = json_decode($request->get('assign_templates'));

                    foreach ($inputs['assign_templates'] as $gameTemplateId) {
                        if (ClientPortalsGameTemplate::isUnique($clientPortal->id, $gameTemplateId)) {
                            ClientPortalsGameTemplate::create([
                                'client_portal_id' => $clientPortal->id,
                                'game_template_id' => $gameTemplateId,
                            ]);
                        }
                    }

                    // 5. Unlink game templates and client portal
                    $unLinkTemplatesIds = array_diff($clientPortal->gameTemplates->lists('id')->toArray(), $inputs['assign_templates']);

                    foreach ($unLinkTemplatesIds as $gameTemplateId) {
                        ClientPortalsGameTemplate::where(['game_template_id' => $gameTemplateId])
                            ->where(['client_portal_id' => $clientPortal->id])
                            ->delete();
                    }
                }

            }catch (\Exception $e){
                DB::rollBack();

                if(array_key_exists('logo', $inputs) && $inputs['logo']) {
                    $logo_path = public_path($inputs['logo']);
                    if (file_exists($logo_path)) {
                        unlink($logo_path);
                    }
                }

                $errors = new MessageBag();
                $errors->add('save', $e->getMessage());

                return ['success' => false, 'errors' => $errors];
            }

            DB::commit();

            return ['success' => true, 'user' => $user];
        }else{
            return ['success' => false, 'errors' => $clientPortal->errors()];
        }
    }

    function store(Request $request){
        $input = $request->all();
        $input['verification_token'] = str_random(64);
        $clientPortal = new ClientPortal();
        $user = new User(['verification_token'=>$input['verification_token']]);

        $clientPortalAndUserRules =  array_merge(ClientPortal::CREATION_RULES, $user->getClientPortalRules() );

        $result = $this->save($request, $clientPortal, $user, $clientPortalAndUserRules);

        if ($result['success']) {
            $savedUser = $result['user'];
            try {
                $this->sendVerificationMailHelper->sendMail($savedUser);
            } catch (\Exception $e) {
                Log::error('Error while sending verification mail: ', ['message' => $e->getMessage()]);
            }
            return redirect()->route('admin-portal.client-portals.index')->with('success','Client successfully created.');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function update($id, Request $request){
        $clientPortal = ClientPortal::where('id', $id)->firstOrFail();
        $user = User::where('id',$clientPortal->default_admin_id)->firstOrFail();

        $clientPortalAndUserRules =  array_merge($clientPortal->getUpdateRules(), $user->getClientPortalRules() );
        $result = $this->save($request, $clientPortal, $user, $clientPortalAndUserRules);

        if($request->ajax()) {
            return $result;
        }

        if ($result['success']) {
            return redirect()->route('admin-portal.client-portals.index')->with('success','Client successfully updated.');
        } else {
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function getClientPortalsDataTables()
    {
        return \DataTables::of(ClientPortal::with('defaultAdmin'))->make(true);
    }

    function dataTablesTemplatesAssignedOnly($id)
    {
        $clientPortal = ClientPortal::find($id);

        return \DataTables::of($clientPortal->gameTemplates())->make(true);
    }

    function destroy($id){
        $clientPortal = ClientPortal::where('id', $id)->firstOrFail();

        // soft-delete all client portal users
        $clientPortal->users()->delete();

        // soft-delete client portal
        $clientPortal->delete();
    }

    function destroyBulk(Request $request){
        $ids = $request->ids;

        foreach ($ids as $id){
            $clientPortal = ClientPortal::where('id', $id)->firstOrFail();

            // soft-delete all client portal users
            $clientPortal->users()->delete();

            // soft-delete client portal
            $clientPortal->delete();
        }
    }

}