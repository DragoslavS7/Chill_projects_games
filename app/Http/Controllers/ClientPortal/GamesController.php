<?php

namespace App\Http\Controllers\ClientPortal;

use App\GamesQuizzes;
use App\GameTemplate;
use App\Http\Controllers\Controller;
use App\Game;
use App\InviteGameTokens;
use App\Quiz;
use App\Services\XapiMyArcadeChef;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;

class GamesController extends Controller
{
    function __construct(){
        $this->xApiMyArcadeChef = new XapiMyArcadeChef();
    }

    function index(Request $request){
        $this->authorize('index', new Game());

        $areThereAnyGames = $request->clientPortal->games()->count() > 0;

        return view('client-portal.games.index', ['user' => \Auth::user(),
                                                       'areThereAnyGames' => $areThereAnyGames
            ]);
    }

    function templates(Request $request){
        $this->authorize('select', new GameTemplate());

        $gameTemplates = $request->clientPortal->gameTemplates;

        $gameTemplatesCategories = $request->clientPortal->gameTemplates()->groupBy('genre')->lists('genre','genre');

        return view('client-portal.games.templates',['user'=> \Auth::user(),
                                                          'gameTemplates'=>$gameTemplates,
                                                          'gameTemplatesCategories'=>$gameTemplatesCategories
        ]);
    }

    function create($templateId, Request $request){
        $game = new Game();
        $this->authorize('create', $game);


        $gameTemplates = $request->clientPortal->gameTemplates;

        $hasTemplate = false;
        foreach ($gameTemplates as $template){
            if($template->id == $templateId) {
                $hasTemplate = true;
                break;
            }
        }

        if(!$hasTemplate){
            return redirect()->route('client-portal.games.templates')->withErrors(["template_error" => "*Your template does not exist please select one."]);
        }

        $game->game_template_id = $templateId;

        //check if game tempalte source starts with pb_
        $gameTemplate = GameTemplate::find($templateId);
        $quizzes =[];

        if($gameTemplate->isPhotobombed()){
            $quizzes = $request->clientPortal->quizzes()->where('is_photobombed',1)->lists('name', 'id');
        }
        else{
            $quizzes = $request->clientPortal->quizzes()->lists('name', 'id');
        }

        $selectedQuizzes = [ new Quiz(), new Quiz(), new Quiz(), new Quiz()];

        $formUrl = route('client-portal.games.store');
        return view('client-portal.games.create', ['user' => \Auth::user(),
                                                          'game' => $game,
                                                          'quizzes' => $quizzes,
                                                          'formUrl' => $formUrl,
                                                          'selectedQuizzes'=>$selectedQuizzes
            ]);
    }

    function filterGameTemplates(Request $request) {
        $this->authorize('select', new GameTemplate());

        $genre = $request->get('genre');
        $gameTemplates = $request->clientPortal->gameTemplates();

        if ($genre != 'all') {
            $gameTemplates = $gameTemplates->where('genre', $genre);
        }

        return $gameTemplates->get();
    }

    function edit(Request $request,$id){
        $id = (int)$id;
        $game = $request->clientPortal->games->where('id',$id)->first();

        if(!$game){
            return redirect()->route('client-portal.games.index')->with('error','This game does not exist on this client portal.');
        }

        $this->authorize('edit', $game);

        $gameTemplateId = $game->game_template_id;

        $quizzes = $request->clientPortal->quizzes()->lists('name', 'id');
        $selectedQuizzes = $game->quizzes;

        while(count($selectedQuizzes) < 4) {
            $selectedQuizzes[] = new Quiz();
        }

        $formUrl = route('client-portal.games.update', $id);
        return view('client-portal.games.edit', [  'user' => \Auth::user(),
                                                          'game'=>$game,
                                                          'gameTemplateId'=>$gameTemplateId,
                                                          'quizzes' => $quizzes,
                                                          'formUrl' => $formUrl,
                                                          'selectedQuizzes'=>$selectedQuizzes
                                                        ]);
    }

    function save($request, $game, $rules){
        $input = $request->all();
        $user = $user = \Auth::user();

        if ($game->validate($input,$rules)) {
            DB::beginTransaction();
            try{
                $input['client_portal_id'] = $request->clientPortal->id;

                if($game->author_id){
                    $input['updated_by_id'] = $user->id;
                }
                else{
                    $input['author_id'] = $user->id;
                }

                if(array_key_exists('player_login', $input) && $input['player_login'] == 'allow_anonymous_players' ){
                    $input['allow_anonymous_players'] = true;
                    $input['required_additional_player_data'] = null;
                }else{
                    $input['allow_anonymous_players'] = false;
                }

                if(array_key_exists('are_quizzes_randomized', $input) && $input['are_quizzes_randomized']){
                    $input['are_quizzes_randomized'] = true;
                }else{
                    $input['are_quizzes_randomized'] = false;
                }

                if($gameIcon = $request->file('game_icon')){
                    $input['game_icon'] = saveImage($gameIcon, 'game-icons/', 'game_icon',300,300);
                }

                if($splashPageImage = $request->file('splash_page_image')){
                    $input['splash_page_image'] = saveImage($splashPageImage, 'game-icons/', 'splash_page_image',300,168);
                }

                $input['is_saved_to_lrs'] = true;
                $game->fill($input);
                $game->save();

                if(array_key_exists('select_quiz',$input) && $input['select_quiz']){
                    $nonEmptyQizzIds = preg_grep("/^.+$/", $request->select_quiz);

                    if(count($nonEmptyQizzIds) == 0){
                        throw new \Exception('You need to select at least one quiz.', 1);
                    }

                    foreach ($nonEmptyQizzIds as $name => $quizId){
                        if(GamesQuizzes::isUnique($game->id, $quizId)){
                            GamesQuizzes::create([
                                'game_id'=>$game->id,
                                'quiz_id'=>$quizId
                            ]);
                        }
                    }
                }

                $user = \Auth::user();
                $isSavedToLRS = true;
                try {
                    $this->xApiMyArcadeChef->createGame($user, $game);
                }catch (\Exception $e) {
                    //mark this session as not saved in lrs and continue
                    $isSavedToLRS = false;
                    \Log::error($e);
                    $input['is_saved_to_lrs'] = $isSavedToLRS;

                    $game->is_saved_to_lrs = $isSavedToLRS;
                    $game->save();
                }

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();

                $errors = new MessageBag();
                if($e->getCode() == 1){
                    $errors->add('select_quiz', $e->getMessage());
                }else{
                    $errors->add('save', $e->getMessage());
                }
                return ['success' => false, 'errors' => $errors];
            }
            return ['success' => true];
        }else{
            return ['success' => false, 'errors' => $game->errors()];
        }
    }

    function store(Request $request){
        $game = new Game();
        $this->authorize('create', $game);

        $result = $this->save($request,$game, $game->rules);

        if ($result['success']) {
            return redirect()->route('client-portal.games.index')->with('success','Game successfully created.');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function update($id, Request $request){
        $id = (int)$id;
        $game = $request->clientPortal->games->where('id',$id)->first();

        if(!$game){
            return redirect()->route('client-portal.games.index')->with('error','This game does not exist on this client portal.');
        }

        $rules = $game->rules;

        if($request->exists('is_active')){
            $rules =  $game->getUpdateRules();
        }

        $this->authorize('edit', $game);

        $result = $this->save($request,$game, $rules);

        if ($result['success']) {
            return redirect()->route('client-portal.games.index')->with('success','Game successfully updated.');
        }else{
            return back()
                ->withErrors($result['errors'])
                ->withInput();
        }
    }

    function dataTables(Request $request){
       return \DataTables::of($request->clientPortal->games()->with('quizzes')->with('template'))->make(true);
    }

    function dataTablesAnalytics(Request $request){
        $input = $request->all();

        $conditions = [];

        if(isset($input['start_date'])){
            array_push($conditions,
                ['created_at','>',$input['start_date'].' 0:0:0'],
                ['created_at','<',$input['end_date'].' 23:59:59']
            );
        }

        return \DataTables::of($request->clientPortal->games()->where($conditions))->make(true);
    }

    function destroy($id,Request $request){
        $id = (int)$id;
        $game = $request->clientPortal->games->where('id',$id)->first();

        if(!$game){
            return redirect()->route('client-portal.games.index')->with('error','This game does not exist on this client portal.');
        }

        $this->authorize('destroy', $game);

        // soft-delete question
        $game->delete();
    }

    function destroyBulk(Request $request){
        $ids = $request->ids;
        $this->authorize('destroy', new Game());
        foreach ($ids as $id){
            $id = (int)$id;
            $game = $request->clientPortal->games->where('id',$id)->first();

            if($game){
                // soft-delete question
                $game->delete();
            }
        }
    }

    function inviteBulk(Request $request)
    {
        $this->authorize('invite', new Game());
        $ids = $request->all();

        if (isset($ids['game_ids'])) {
            $ids['game_ids'] = json_decode($ids['game_ids']);
        }

        if (isset($ids['user_ids'])) {
            $ids['user_ids'] = json_decode($ids['user_ids']);
        }

        $rules = [
            'game_ids' => 'required|array',
            'game_ids.*' => 'exists:games,id',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ];

        $validator = \Validator::make($ids, $rules, [
            'game_ids.required' => 'You need to select at least one game',
            'user_ids.required' => 'You need to select at least one user'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator);
        }

        $errors = [];
        foreach ($ids['user_ids'] as $userId) {
            $user = User::where('id', $userId)->where('client_portal_id', $request->clientPortal->id)->first();

            if ($user) {
                foreach ($ids['game_ids'] as $gameId) {
                    $game = Game::where('id', $gameId)->where('client_portal_id', $request->clientPortal->id)->first();
                    if ($game) {
                        $inviteGameToken = InviteGameTokens::where('user_id', $userId)
                                                           ->where('game_id', $gameId)->first();

                        if(!$inviteGameToken){
                            $inviteGameToken = InviteGameTokens::create([
                                'user_id' => $userId,
                                'game_id' => $gameId,
                                'token' => str_random(64)
                            ]);
                        }

                        $gameUrl = $request->clientPortal->baseUrl() . $game->url . '?' . http_build_query(['token' => $inviteGameToken->token ]);

                        try {
                            Mail::send(
                                'email.partials.game-invite',
                                [
                                    'clientPortal' => $request->clientPortal,
                                    'gameUrl' => $gameUrl
                                ],
                                function ($message) use ($user, $game) {
                                    $message->to($user->email, $user->first_name)->subject("Invite for play '$game->name' game from MyArcadeChef.com");
                                }
                            );
                        }catch (\Exception $e){
                            $errors[] = "Invite is not sent for '$game->name' game  to '$user->email' because: " . $e->getMessage();
                        }
                    }

                }
            }
        }

        if(count($errors) > 0){
            return back()->withErrors($errors);
        }

        return back()->with('success', 'Invites are sent to users.');
    }
}