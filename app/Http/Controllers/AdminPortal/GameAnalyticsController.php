<?php

namespace App\Http\Controllers\AdminPortal;

use App\Game;
use App\GameSessions;
use App\Http\Controllers\Controller;
use App\User;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class GameAnalyticsController extends Controller
{
    function index(){
        return view('admin-portal.game-analytics.index', ['user' => \Auth::user()]);
    }

    function game($game_id, Request $request){

        $game = Game::find($game_id);
        $games = DB::table('games')->where('is_active','=',1)->lists('name', 'id');
        $areThereAnyAnalytics = DB::table('game_sessions')->where('game_id',$game_id)->count() > 0;
        return view('admin-portal.game-analytics.game', ['user' => \Auth::user(),
                                                              'games'=>$games,
                                                              'game'=>$game,
                                                              'areThereAnyAnalytics'=>$areThereAnyAnalytics
        ]);
    }

    function report(Request $request){
        $statments = $this->xApiMyArcadeChef->getGames();

        $res = [];

        foreach($statments->getStatements() as $s){

            $email = $s->getActor()->getInverseFunctionalIdentifier()->getMbox()->getValue();
            $points = $s->getResult()->getScore()->getRaw();
            if(!$points){
                $points = $s->getResult()->getScore()->getMax() * $s->getResult()->getScore()->getScaled();
            }

            if(isset($res[$email])){
                $res[$email][ 'number_of_played_games'] += 1;
                $res[$email][ 'total_points'] += $points;
            }else{
                $res[$email][ 'number_of_played_games'] = 1;
                $res[$email][ 'total_points'] = $points;
            }
        }

        $res = response()->json($res);
        return view('admin-portal.game-analytics.game', ['user' => \Auth::user(),
            'res'=>$res
        ]);
    }

    function dataTable(Request $request){
        $input = $request->all();

        $conditions = [];

        if(isset($input['start_date'])){
            array_push($conditions,
                ['created_at','>',$input['start_date'].' 0:0:0'],
                ['created_at','<',$input['end_date'].' 23:59:59']
            );
        }

        return \DataTables::of(Game::query()->where($conditions))->make(true);
    }

    function dataTablesUsers($game_id, Request $request){
        $input = $request->all();

        $conditions = [
            ['game_id','=',$game_id],
            ['deleted_at','=',null]
        ];

        if(isset($input['start_date'])){
            array_push($conditions,
                ['game_start_time','>',$input['start_date'].' 00:00:00']
            );
        }

        if(isset($input['end_date'])){
            array_push($conditions,
                ['game_end_time','<',$input['end_date'].' 23:59:59']
            );
        }


        $queryResult = User::whereHas('gameSessions', function($query)  use ($conditions){
            $query->where($conditions);
        })->with(['gameSessions' => function($query) use ($conditions){
            $query->selectRaw('user_id,
                               sum(correct_answers) as sum_correct_answers,
                               sum(incorrect_answers) as sum_incorrect_answers,
                               sum(total_questions) as sum_total_questions,
                               count(*) as number_of_sessions,
                               sum(points) as sum_points,
                               avg(timestampdiff(SECOND, game_start_time, game_end_time)) as avgdiff
             ')->groupBy('user_id')
                ->where($conditions);
        }])->select('id', 'first_name','last_name','email');

        return \DataTables::of($queryResult)->make(true);
    }

    function dataTablesQuestions($game_id, Request $request){
        $input = $request->all();

        $conditions = [
            ['game_id','=',$game_id],
            ['deleted_at','=',null]
        ];

        if(isset($input['start_date'])){
            array_push($conditions,
                ['question_sessions.question_start_time','>',$input['start_date'].' 0:0:0']
            );
        }

        if(isset($input['end_date'])){
            array_push($conditions,
                ['question_sessions.question_end_time','<',$input['end_date'].' 23:59:59']
            );
        }

        $queryResult = Question::whereHas('questionSessions', function($query)  use ($conditions){
            $query->where($conditions);
        })->with(['questionSessions' => function($query) use ($conditions){
            $query->selectRaw('question_id,
                               sum(is_correct) as sum_correct_answers,
                               sum(if (is_correct=0,1,0)) as sum_incorrect_answers,
                               count(*) as total_questions,
                               avg(timestampdiff(SECOND, question_start_time, question_end_time)) as avgdiff
             ')->groupBy('question_id')
                ->where($conditions);
        }])->select('id', 'name');
        return \DataTables::of($queryResult)->make(true);
    }
}