<?php

namespace App\Http\Controllers\ClientPortal;

use App\Game;
use App\User;
use App\GameSessions;
use App\QuizSessions;
use App\QuestionSessions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LeaderBoardsController extends Controller
{

    function index(Request $request){
        $games = $request->clientPortal->games()->lists('name', 'id');

        return view('client-portal.leaderboards.index', ['user' => \Auth::user(),'games'=>$games]);
    }

    function game($game_id, Request $request){
        $games = $request->clientPortal->games()->lists('name', 'id');

        $game_id = (int)$game_id;
        $game = $request->clientPortal->games->where('id',$game_id)->first();

        if(!$game){
            return redirect()->route('client-portal.leaderboards.index')->with('error','This game does not exist on this client portal.');
        }

        return view('client-portal.leaderboards.game', ['user' => \Auth::user(),
                                                             'games'=>$games,
                                                             'game'=>$game
        ]);
    }

    function dataTablesGames(Request $request){
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

    function dataTablesUsers($game_id, Request $request){
        $input = $request->all();

        $game_id = (int)$game_id;
        $game = $request->clientPortal->games->where('id',$game_id)->first();

        if(!$game){
            $users = new Collection();
            return \DataTables::of($users)->make(true);
        }

        $conditions = [
            ['game_id','=',$game_id],
            ['deleted_at','=',null]
        ];

        if(isset($input['start_date'])){
            array_push($conditions,
                ['game_start_time','>',$input['start_date'].' 0:0:0'],
                ['game_start_time','<',$input['end_date'].' 23:59:59']
            );
        }

        $queryResult =  DB::table('game_sessions')->where($conditions)->get();
        $res = [];

        foreach($queryResult as $session){
            $user_id = $session->user_id;

            $duration = strtotime($session->game_end_time)-strtotime($session->game_start_time);

            if(isset($res[$user_id])){
                $res[$user_id]['games_played'] += 1;
                $res[$user_id]['points'] += $session->points;
                $res[$user_id]['correct'] += $session->correct_answers;
                $res[$user_id]['incorrect'] += $session->incorrect_answers;
                $res[$user_id]['duration'] += $duration;
            }
            else{
                $user = User::find($user_id);

                $res[$user_id]['first_name'] = $user->first_name;
                $res[$user_id]['last_name'] = $user->last_name;
                $res[$user_id]['email'] = $user->email;
                $res[$user_id]['points'] = $session->points;
                $res[$user_id]['games_played'] = 1;
                $res[$user_id]['correct'] = $session->correct_answers;
                $res[$user_id]['incorrect'] = $session->incorrect_answers;
                $res[$user_id]['duration'] = $duration;
            }
        }
        $users = new Collection();

        foreach($res as $entry){
            $avg = $entry['duration']/$entry['games_played'];
            $avg =gmdate("H:i:s", $avg);

            $users->push([
                'first_name'=>$entry['first_name'],
                'last_name'=>$entry['last_name'],
                'email'=>$entry['email'],
                'games_played'=>$entry['games_played'],
                'average_game_time'=>$avg,
                'points'=>$entry['points'],
                'correct'=>$entry['correct'],
                'incorrect'=>$entry['incorrect']
            ]);
        }

        return \DataTables::of($users)->make(true);
    }

    function reset($id, Request $request){
        $id = (int)$id;
        $game = $request->clientPortal->games->where('id',$id)->first();

        if(!$game){
            return redirect()->route('client-portal.leaderboards.index')->with('error','This game does not exist on this client portal.');
        }

        $gameSessionIds = DB::table('game_sessions')->where([
                                                            ['game_id','=',$id],
                                                            ['deleted_at','=',null]
                                                        ])
                                                    ->select('id')->get();

        $quizSessionIds = DB::table('quiz_sessions')->where([
                                                            ['game_id','=',$id],
                                                            ['deleted_at','=',null]
                                                        ])
                                                    ->select('id')->get();

        $questionSessionIds = DB::table('question_sessions')->where([
                                                            ['game_id','=',$id],
                                                            ['deleted_at','=',null]
                                                        ])
                                                    ->select('id')->get();
        DB::beginTransaction();
        try{

            foreach($gameSessionIds as $gameSessionId){
                $gameSession = GameSessions::find($gameSessionId->id);
                $gameSession->delete();
            }

            foreach($quizSessionIds as $quizSessionId){
                $quizSession = QuizSessions::find($quizSessionId->id);
                $quizSession->delete();
            }

            foreach($questionSessionIds as $questionSessionId){
                $questionSession = QuestionSessions::find($questionSessionId->id);
                $questionSession->delete();
            }

            DB::commit();
        }catch (\Exception $e){
            DB::rollback();

            $errors = new MessageBag();
            $errors->add('reset', $e->getMessage());

            return ['success' => false, 'errors' => $errors];
        }

        return ['success' => true];
    }

    function resetBulk(Request $request){
        $ids = $request->ids;

        foreach($ids as $id){
            $this->reset($id);
        }
    }
}