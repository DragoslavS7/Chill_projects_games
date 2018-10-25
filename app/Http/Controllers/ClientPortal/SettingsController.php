<?php

namespace App\Http\Controllers\ClientPortal;

use App\ClientPortal;
use App\ClientPortalDepartmentTags;
use App\ClientPortalLocationTags;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class settingsController extends Controller
{

    function index(Request $request){
        $formUrl = route('client-portal.settings.store');
        $client = $request->clientPortal;

        $deafultAdmin = $client->users->where('id',$client->default_admin_id)->first();

        $styles = [];

        //set location tags
        if($request->clientPortal->locationTags){
            $locationTags = $request->clientPortal->locationTags->lists('tag')->toArray();
        }
        else{
            $locationTags = [];
        }

        //set department tags
        if($request->clientPortal->departmentTags){
            $departmentTags = $request->clientPortal->departmentTags->lists('tag')->toArray();
        }
        else{
            $departmentTags = [];
        }

        foreach($client->custom_style as $style){
            $styles[$style->name] = $style->color;
        }

        return view('client-portal.settings.index', ['user' => \Auth::user(),
                                                           'client'=>$client,
                                                           'deafultAdmin'=>$deafultAdmin,
                                                           'formUrl' => $formUrl,
                                                           'styles' => $styles,
                                                           'locationTags'=>$locationTags,
                                                           'departmentTags'=>$departmentTags
            ]);
    }

    function save(Request $request, ClientPortal $client){
        $input = $request->all();

        if ($client->validate($input)) {
            DB::beginTransaction();
            try{
                $input['client_portal_id'] = $request->clientPortal->id;

                if(array_key_exists('show_index', $input)){
                    $input['show_index'] = true;
                }else{
                    $input['show_index'] = false;
                }

                if(array_key_exists('is_costumer_service_available', $input)){
                    $input['is_costumer_service_available'] = true;
                }else {
                    if (!\Auth::user()->isUberAdmin()) {
                        $input['is_costumer_service_available'] = false;
                    }
                }

                //Handle location tags
                if(array_key_exists('location_tags',$input)){
                    $locationTags = explode(',',$input['location_tags']);

                    //set tags
                    foreach($locationTags as $location_tag){
                        if($location_tag && ClientPortalLocationTags::isUnique($request->clientPortal->id,$location_tag)){
                            ClientPortalLocationTags::create([
                                'client_portal_id'=>$request->clientPortal->id,
                                'tag'=>$location_tag
                            ]);
                        }
                    }

                    //unlink tags
                    if($request->clientPortal->locationTags){
                        $linkedLocationTags = $request->clientPortal->locationTags->lists('tag','id')->toArray();

                        foreach($linkedLocationTags as $locationTag){
                            if(!in_array($locationTag,$locationTags)){
                                ClientPortalLocationTags::where(['client_portal_id'=>$request->clientPortal->id])->where('tag',$locationTag)->delete();
                            }
                        }
                    }
                }

                //Handle deapartment tags
                if(array_key_exists('department_tags',$input)){
                    $departmentTags = explode(',',$input['department_tags']);

                    //set tags
                    foreach($departmentTags as $department_tag){
                        if($department_tag && ClientPortalDepartmentTags::isUnique($request->clientPortal->id,$department_tag)){
                            ClientPortalDepartmentTags::create([
                                'client_portal_id'=>$request->clientPortal->id,
                                'tag'=>$department_tag
                            ]);
                        }
                    }

                    //unlink tags
                    if($request->clientPortal->departmentTags){
                        $linkedDepartmentTags = $request->clientPortal->departmentTags->lists('tag','id')->toArray();

                        foreach($linkedDepartmentTags as $departmentTag){
                            if(!in_array($departmentTag,$departmentTags)){
                                ClientPortalDepartmentTags::where(['client_portal_id'=>$request->clientPortal->id])->where('tag',$departmentTag)->delete();
                            }
                        }
                    }
                }


                $custom_styles = [];

                if(array_key_exists('menu_background',$input)){
                    $custom_styles[] = [ 'selector'=>'.menu-background',
                                         'style'=>'background:'.$input['menu_background'].';',
                                          'name' => 'menu_background',
                                          'color' => $input['menu_background']

                    ];
                }

                if(array_key_exists('wraper_background',$input)){
                    $custom_styles[] = ['selector'=>'.wraper-background',
                                        'style'=>'background:'.$input['wraper_background'].';',
                                        'name'=>'wraper_background',
                                        'color'=>$input['wraper_background']
                    ];
                }

                if(array_key_exists('menu_top_level_font',$input)){
                    $custom_styles[] = ['selector'=>'.menu-top-level-font',
                                        'style'=>'color:'.$input['menu_top_level_font'].';',
                                        'name'=>'menu_top_level_font',
                                        'color'=>$input['menu_top_level_font']
                    ];
                }

                if(array_key_exists('menu_low_level_font',$input)){
                    $custom_styles[] = ['selector'=>'.menu-low-level-font',
                                        'style'=>'color:'.$input['menu_low_level_font'].';',
                                        'name'=>'menu_low_level_font',
                                        'color'=>$input['menu_low_level_font']
                    ];
                }

                if(array_key_exists('colored_button_background',$input)){
                    $custom_styles[] = ['selector'=>'.btn-default',
                                        'style'=>'background:'.$input['colored_button_background'].';',
                                        'name'=>'colored_button_background',
                                        'color'=>$input['colored_button_background']
                    ];
                }

                if(array_key_exists('colored_button_font',$input)){
                    $custom_styles[] = ['selector'=>'.btn-default',
                                        'style'=>'color:'.$input['colored_button_font'].';',
                                        'name'=>'colored_button_font',
                                        'color'=>$input['colored_button_font']
                    ];
                }
                if(array_key_exists('colored_button_rollover',$input)){
                    $custom_styles[] = ['selector'=>'.btn-default:hover',
                                        'style'=>'background:'.$input['colored_button_rollover'].';',
                                        'name'=>'colored_button_rollover',
                                        'color'=>$input['colored_button_rollover']
                    ];
                }

                $client->setCustomStyleAttribute($custom_styles);

                if ($logo = $request->file('logo')) {
                    $input['logo'] = saveImage($logo, 'client-portals/', 'logo',300 ,300);
                }

                $client->fill($input);
                $client->save();

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();

                $errors = new MessageBag();
                $errors->add('save', $e->getMessage());

                return ['success' => false, 'errors' => $errors];
            }

            return ['success' => true];
        }else{
            return ['success' => false, 'errors' => $client->errors()];
        }
    }

    function store(Request $request){
        $client = $request->clientPortal;

        $result = $this->save($request,$client);

        if ($result['success']) {
            return redirect()->route('client-portal.settings.index');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function update($id, Request $request){
        $client = ClientPortal::findOrFail($id);

        $result = $this->save($request, $client);

        if ($result['success']) {
            return redirect()->route('client-portal.settings.index');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }
}