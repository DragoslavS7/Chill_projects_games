<?php

namespace App\Http\Controllers\ClientPortal;

use App\Http\Controllers\Controller;
use App\InviteGameTokens;
use App\User;
use App\UserDepartmentTags;
use App\UserLocationTags;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;


class PlayersController extends Controller
{

    function game($gameSlug, Request $request)
    {
        $gameSlug = "/" . $gameSlug;

        $game = $request->clientPortal->games()->where('url', $gameSlug)->first();

        if (!$game) {
            return view('client-portal.players.game-not-defined');
        }

        $aditionalFields = $game->required_additional_player_data;

        $source = $game->template->source;

        if ($source == "") {
            return abort(404, "This game have invalid template please contact admin");
        }

        $sourcePath = "gamesSources/$source/index.html";

        if (!file_exists(public_path($sourcePath))) {
            return abort(404, "This game is missing index.html please contact admin");
        }

        $sourcePath = "/$sourcePath?gameId=$game->id";

        if(count($aditionalFields)>0){
            $client = $request->clientPortal;
            $formUrl = route('client-portal.players.update');

            $user = \Auth::user();

            //set location tags
            if($request->clientPortal->locationTags){
                $suggestedLocationTags = $request->clientPortal->locationTags->lists('tag')->toArray();
            }
            else{
                $suggestedLocationTags = [];
            }



            //set department tags
            if($request->clientPortal->departmentTags){
                $suggestedDepartmentTags = $request->clientPortal->departmentTags->lists('tag')->toArray();
            }
            else{
                $suggestedDepartmentTags = [];
            }

            if($request->has('token') && !$user){
                $token = $request->get('token');

                $inviteGame = InviteGameTokens::where('token', $token)->where('game_id', $game->id)->first();

                if($inviteGame){
                    //log user
                    $user = $inviteGame->user;
                    \Auth::login($user);
                }
            }

            if(!$user){
                $user = new User();
                $user->client_portal_id = $request->clientPortal->id;
                $user->role = "player";
                $filePathPrefix = "/gamesSources/$source";

                $locationTags = json_encode([]);
                $departmentTags=json_encode([]);

                return view('client-portal.players.partials.form', ["client" => $client,
                    "user" => $user,
                    "gameSlug"=>$gameSlug,
                    "sourcePath"=>$sourcePath,
                    "formUrl"=>$formUrl,
                    "aditionalFields"=>$aditionalFields,
                    "filePathPrefix"=>$filePathPrefix,
                    'locationTags'=>$locationTags,
                    "suggestedLocationTags"=>$suggestedLocationTags,
                    "departmentTags"=>$departmentTags,
                    "suggestedDepartmentTags"=>$suggestedDepartmentTags
                ]);
            }else{
                if($user->role == 'admin' || $user->role == 'uber_admin'){
                    $sourcePath = $sourcePath.'&userId='.$user->id;
                    $filePathPrefix = "/gamesSources/$source";
                    return view('client-portal.players.game', ["url" => $sourcePath, "game" => $game, 'user' => $user, "filePathPrefix" => $filePathPrefix]);
                }else{
                    $filePathPrefix = "/gamesSources/$source";
                    $locationTags = $user->locationTags->lists('tag')->toJson();
                    $departmentTags = $user->departmentTags->lists('tag')->toJson();

                    // if user is logged on or has a key  email is read only and fields are populated
                    return view('client-portal.players.partials.form', ["client" => $client,
                        "user" => $user,
                        "gameSlug"=>$gameSlug,
                        "sourcePath"=>$sourcePath,
                        "formUrl"=>$formUrl,
                        "aditionalFields"=>$aditionalFields,
                        "filePathPrefix"=>$filePathPrefix,
                        'locationTags'=>$locationTags,
                        "suggestedLocationTags"=>$suggestedLocationTags,
                        "departmentTags"=>$departmentTags,
                        "suggestedDepartmentTags"=>$suggestedDepartmentTags
                    ]);
                }
            }
        }else{
            $user = \Auth::user();

            if(!$user){
                $user = $request->clientPortal
                        ->users()
                        ->where('email', 'anonymous@collectedworlds.com')
                        ->where('role', User::ROLES['player'])
                        ->first();

                if(!$user){
                    $user = User::create([
                        'name' => 'Anonymous',
                        'role' => User::ROLES['player'],
                        'email' => 'anonymous@collectedworlds.com',
                        'client_portal_id' => $request->clientPortal->id
                    ]);
                }
            }
            else{
                $sourcePath = $sourcePath.'&userId='.$user->id;
            }

            $filePathPrefix = "/gamesSources/$source";
            return view('client-portal.players.game', ["iframeSrc" => $sourcePath, "game" => $game, 'user' => $user, "filePathPrefix" => $filePathPrefix]);
        }
    }

    function home(Request $request)
    {
        $client = $request->clientPortal;
        $games = $request->clientPortal->games;

        if($client->show_index){
            return view('client-portal.players.index', ['client' => $client,
                'games' => $games
            ]);
        }

        return view('client-portal.players.logo', ["client" => $client]);
    }

    function save(User $user, $userData,$rules,Request $request){

        if ($user->validate($userData,$rules)) {
            DB::beginTransaction();

            try {
                $user->fill($userData);
                $user->save();

                if(array_key_exists('location_tags',$userData)){
                    $locationTags = $request->clientPortal->locationTags->lists('tag','id')->toArray();
                    $userLocationTags = explode(',',$userData['location_tags']);

                    //set tags
                    foreach($locationTags as $tagID => $location_tag){
                        if($location_tag && in_array($location_tag, $userLocationTags)  && UserLocationTags::isUnique($user->id,$tagID)){
                            UserLocationTags::create([
                                'user_id'=>$user->id,
                                'client_portal_location_tag_id'=>$tagID
                            ]);
                        }
                    }

                    //unlink tags
                    foreach($locationTags as $tagID => $locationTag){
                        if(!in_array($locationTag,$userLocationTags)){
                            UserLocationTags::where(['user_id'=>$user->id])->where('client_portal_location_tag_id',$tagID)->delete();
                        }
                    }

                }

                if(array_key_exists('department_tags',$userData)){
                    $departmentTags = $request->clientPortal->departmentTags->lists('tag','id')->toArray();
                    $userDepartmentTags = explode(',',$userData['department_tags']);

                    //set tags
                    foreach($departmentTags as $tagID => $department_tag){
                        if($department_tag && in_array($department_tag, $userDepartmentTags)  && UserDepartmentTags::isUnique($user->id,$tagID)){
                            UserDepartmentTags::create([
                                'user_id'=>$user->id,
                                'client_portal_department_tag_id'=>$tagID
                            ]);
                        }
                    }

                    //unlink tags
                    foreach($departmentTags as $tagID => $departmentTag){
                        if(!in_array($departmentTag,$userDepartmentTags)){
                            UserDepartmentTags::where(['user_id'=>$user->id])->where('client_portal_department_tag_id',$tagID)->delete();
                        }
                    }

                }

            }catch (\Exception $e){
                DB::rollBack();

                $errors = new MessageBag();
                $errors->add('save', $e->getMessage());

                return ['success' => false, 'errors' => $errors];
            }

            DB::commit();

            return ['success' => true];
        }else{
            return ['success' => false, 'errors' => $user->errors()];
        }
    }

    function update(Request $request){
        $input = $request->except(["_token","game_slug","source_path"]);

        $rules = [];
        foreach ($input as $field => $value){
            $rules[$field] = "required";
        }

        $user = \Auth::user(); // logovan

        // if there is not logged user try to find user in DB
        if(!$user){
            $user = $request->clientPortal->users()->where('email',$input['email'])->first();
        }

        // if we didn't find user in db we will create new player user
        if(!$user) {
            $user = new User();
            $user->client_portal_id = $request->clientPortal->id;
            $user->role = "player";
        }

        $rules['email'] = "required|email|max:255|unique:users,email,{$user->id}";
        $result = $this->save($user,$input,$rules,$request);

        $sourcePath = $request->get('source_path');
        $sourcePath = $sourcePath.'&userId='.$user->id;


        $game = $request->clientPortal->games()->where('url', $request->get("game_slug"))->first();
        $source = $game->template->source;
        $filePathPrefix = "/gamesSources/$source";

        if ($result['success']) {
            \Auth::loginUsingId($user->id);
            return view('client-portal.players.game', ["url" => $sourcePath,"user"=>$user,"game"=>$game,"filePathPrefix"=>$filePathPrefix]);
        } else {
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }
}