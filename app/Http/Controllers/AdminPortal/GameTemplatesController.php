<?php

namespace App\Http\Controllers\AdminPortal;

use App\ClientPortal;
use App\ClientPortalsGameTemplate;
use App\GameTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class GameTemplatesController extends Controller
{

    function index() {
        $areThereAnyGameTemplates = GameTemplate::count() > 0;
        return view('admin-portal.game-templates.index', ['user' => \Auth::user(),
                                                  'areThereAnyGameTemplates' => $areThereAnyGameTemplates]);
    }

    function create()
    {
        $formUrl = route('admin-portal.game-templates.store');

        $assignedClientPortals = '';
        $areThereAnyClientPortal = ClientPortal::count() > 0;

        $gamesSources = array_diff(scandir(public_path("/gamesSources")), ['..', '.']);
        $gamesSources = array_combine($gamesSources, $gamesSources);
        $gamesSources = ['' =>'Select Game Source'] + $gamesSources;


        return view('admin-portal.game-templates.create', ['user' => \Auth::user(),
                                                   'gameTemplate' => new GameTemplate(),
                                                   'formUrl' => $formUrl,
                                                   'assignedClientPortals' => $assignedClientPortals,
                                                   'areThereAnyClientPortal' => $areThereAnyClientPortal,
                                                   'gamesSources' => $gamesSources
        ]);
    }

    function save(Request $request, GameTemplate $gameTemplate){
        $input = $request->all();

        if ($gameTemplate->validate($input)) {
            DB::beginTransaction();

            try {
                if(array_key_exists('is_default', $input) && $input['is_default']){
                    $input['is_default'] = true;
                }else{
                    $input['is_default'] = false;
                }

                if ($templateIcon = $request->file('template_icon')) {
                    $input['template_icon'] = saveImage($templateIcon, 'game-templates/', 'template_icon',128,128);
                }

                if ($screenshot = $request->file('screenshot')) {
                    $input['screenshot'] = saveImage($screenshot, 'game-templates/', 'screenshot',800,600);
                }

                $input['is_active'] = array_key_exists('is_active', $input) && $input['is_active'] ? 1 : 0;

                $gameTemplate->fill($input);
                $gameTemplate->save();

                if (array_key_exists('assign_client_portals', $input) && $input['assign_client_portals']) {

                    // Link game templates and client portal
                    $input['assign_client_portals'] = json_decode($request->get('assign_client_portals'));

                    foreach ($input['assign_client_portals'] as $clientPortalId) {
                        if (ClientPortalsGameTemplate::isUnique($clientPortalId, $gameTemplate->id)) {
                            ClientPortalsGameTemplate::create([
                                'client_portal_id' => $clientPortalId,
                                'game_template_id' => $gameTemplate->id,
                            ]);
                        }
                    }

                    // Unlink game templates and client portal
                    $unClientPortalsIds = array_diff($gameTemplate->clientPortals()->lists('client_portals.id')->toArray(), $input['assign_client_portals']);

                    foreach ($unClientPortalsIds as $clientPortalId) {
                        ClientPortalsGameTemplate::where(['game_template_id' => $gameTemplate->id])
                            ->where(['client_portal_id' => $clientPortalId])
                            ->delete();
                    }

                    // Insure that client always have at least one template assigned
                    if (sizeof($unClientPortalsIds) > 0 && ClientPortal::findOrFail($clientPortalId)->gameTemplates()->count() == 0) {
                        throw new \Exception('You need to have at least one template assigned to client portal', 1);
                    }
                }

                if(array_key_exists('is_default',$input)){
                    $clientPortals = ClientPortal::lists('id');

                    if($input['is_default']){
                        // Link game templates to all client portals
                        foreach($clientPortals as $clientId){
                            if (ClientPortalsGameTemplate::isUnique($clientId, $gameTemplate->id)) {
                                ClientPortalsGameTemplate::create([
                                    'client_portal_id' => $clientId,
                                    'game_template_id' => $gameTemplate->id,
                                ]);
                            }
                        }
                    }
                    else{
                        // Unlink game template from all client portals
                        ClientPortalsGameTemplate::where(['game_template_id' => $gameTemplate->id])
                                ->delete();

                    }
                }
            } catch (\Exception $e) {
                DB::rollBack();

                $errors = new MessageBag();
                if ($e->getCode() == 1) {
                    $errors->add('assign_client_portals', $e->getMessage());
                } else {
                    $errors->add('save', $e->getMessage());
                }

                return ['success' => false, 'errors' => $errors];
            }

            DB::commit();
            return ['success' => true];

        }else{
            return ['success' => false, 'errors' => $gameTemplate->errors()];
        }
    }


    function store(Request $request)
    {
        $gameTemplate = new GameTemplate();
        $input = $request->all();

        if(!GameTemplate::isNameUnique($input['name'])){
            return back()
                ->withErrors(['save' => 'Name already in use, you need to have a unique name for the game template.'])
                ->withInput();
        }

        $result = $this->save($request, $gameTemplate);

        if ($result['success']) {
            return redirect()->route('admin-portal.game-templates.index')->with('success','Game template successfully created.');
        }else{
            return back()
                   ->withErrors($result['errors'])
                   ->withInput();
        }
    }

    function edit($id, Request $request){
        $gameTemplate = GameTemplate::findOrFail($id);
        $formUrl = route('admin-portal.game-templates.update', $id);

        $assignedClientPortals = $gameTemplate->clientPortals->lists('id')->toJson();
        $areThereAnyClientPortal = ClientPortal::count() > 0;

        $gameSources = array_diff(scandir(public_path("/gamesSources")), ['..', '.']);
        $gameSources = array_combine($gameSources, $gameSources);

        $gameSources = ['' =>'Select Game Source'] + $gameSources;

        return view('admin-portal.game-templates.edit', ['user' => \Auth::user(),
                                                 'gameTemplate' => $gameTemplate,
                                                 'formUrl' => $formUrl,
                                                 'assignedClientPortals' => $assignedClientPortals,
                                                 'areThereAnyClientPortal' => $areThereAnyClientPortal,
                                                 'gamesSources' => $gameSources
        ]);
    }

    function view($id, Request $request){
        $gameTemplate = GameTemplate::findOrFail($id);

        $assignedClientPortals = $gameTemplate->clientPortals->lists('id')->toJson();
        $areThereAnyClientPortal = ClientPortal::count() > 0;

        $gameSources = array_diff(scandir(public_path("/gamesSources")), ['..', '.']);
        $gameSources = array_combine($gameSources, $gameSources);

        $gameSources = ['' =>'Select Game Source'] + $gameSources;

        return view('admin-portal.game-templates.view', ['user' => \Auth::user(),
                                                 'gameTemplate' => $gameTemplate,
                                                 'assignedClientPortals' => $assignedClientPortals,
                                                 'areThereAnyClientPortal' => $areThereAnyClientPortal,
                                                 'gamesSources' => $gameSources
        ]);
    }

    function update($id, Request $request ){
        $gameTemplate = GameTemplate::findOrFail($id);

        $gameTemplate->rules['name'] = str_replace('required|', '', $gameTemplate->rules['name']);

        $result = $this->save($request, $gameTemplate);

        if($request->ajax()) {
            return $result;
        }

        if ($result['success']) {
            return redirect()->route('admin-portal.game-templates.index')->with('success','Game template successfully updated.');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }

    }

    function getGameTemplatesDataTables(){
        return \DataTables::of(GameTemplate::query()->with('instances'))->make(true);
    }

    function destroy($id){
        $gameTemplate = GameTemplate::findOrFail($id);
        $gameTemplate->delete();
    }

    function destroyBulk(Request $request){
        $ids = $request->ids;

        foreach ($ids as $id){
            $gameTemplate = GameTemplate::findOrFail($id);
            $gameTemplate->delete();
        }
    }
}