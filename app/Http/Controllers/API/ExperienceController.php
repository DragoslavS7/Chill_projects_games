<?php

namespace App\Http\Controllers\API;


use App\Answer;
use App\Game;
use App\GameSessions;
use App\QuizSessions;
use App\QuestionSessions;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\GameController;
use App\Question;
use App\Quiz;
use App\Services\XapiMyArcadeChef;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExperienceController extends Controller
{

    function __construct(){
        $this->xApiMyArcadeChef = new XapiMyArcadeChef();
        $this->GameController = new GameController();
    }

    function gameStart($gameId, Request $request){
        $inputs = $request->all();
        $inputs['game_id'] = $gameId;

        $validator = \Validator::make($inputs, [
            'user_id' => 'required|exists:users,id,deleted_at,NULL',
            'start_time' => 'required|date',
            'game_id' => 'required|exists:games,id,deleted_at,NULL',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'errors' => $validator->messages()],422);

        }

        $game = Game::find($gameId);
        $user = User::find($request->get('user_id'));

        $isSavedToLRS = true;
        try {
            $this->xApiMyArcadeChef->startGame($user, $game);
        }catch (\Exception $e) {
            //mark this session as not saved in lrs and continue
            $isSavedToLRS = false;
            \Log::error($e);
        }

        $startTime = new \DateTime( $inputs['start_time']);
        $gameSession = new GameSessions();

        $gameSessionInput = [
            'user_id'=>$user->id,
            'game_id'=>$game->id,
            'game_name'=>$game->name,
            'points'=>0,
            'game_start_time'=>$startTime,
            'status'=>'started',
            'is_saved_to_lrs' => $isSavedToLRS
        ];

        $res = $this->GameController->saveGameSession($gameSession, $gameSessionInput);

        if(!$res['success']){
            return response()->json($res, 400);
        }

        return response()->json(['game_session_id'=> $gameSession->id],200);
    }

    function gameFinish($gameId, Request $request){
        $game = Game::find($gameId);

        if(!$game){
            return response()->json(['error' => "Game with id `$gameId` do not exists"], 404 );
        }

        $inputs = $request->all();
        $inputs['game_id'] = $gameId;

        if($request->has('didTheUserCompletedTheGame')){
             if(in_array(strtolower($inputs['didTheUserCompletedTheGame']), [1, 'true'], true)){
                 $inputs['didTheUserCompletedTheGame'] = 1;
             }
             else if(in_array(strtolower($inputs['didTheUserCompletedTheGame']), [0, 'false'], true)){
                 $inputs['didTheUserCompletedTheGame'] = 0;
             }
        }

        $validator = \Validator::make($inputs, [
            'user_id' => 'required|exists:users,id,deleted_at,NULL',
            'game_id' => 'required|exists:games,id,deleted_at,NULL',
            'game_session_id' => 'required|exists:game_sessions,id,deleted_at,NULL',
            'points' => 'required|integer|min:0',
            'didTheUserCompletedTheGame' => 'required|boolean',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'errors' => $validator->messages()],422);

        }

        $startTime = new \DateTime( $inputs['start_time']);
        $endTime = new \DateTime($inputs['end_time']);

        $duration = date_diff($startTime, $endTime)->format("P%yY%mM%dDT%hH%iM%sS");

        $duration = str_replace(["M0S", "H0M", "D0H", "M0D", "Y0M", "P0Y"], ["M", "H", "D", "M", "Y0M", "P"], $duration);

        $user = User::find($request->get('user_id'));

        $isSavedToLRS = true;
        try {
            $this->xApiMyArcadeChef->finishGame($user, $game, $inputs['points'], (boolean)$inputs['didTheUserCompletedTheGame'], $duration);
        }catch (\Exception $e) {
            //mark this session as not saved in lrs and continue
            $isSavedToLRS = false;
            \Log::error($e);
        }

        $gameSession = GameSessions::find($inputs['game_session_id']);

        $correct = DB::table('question_sessions')
            ->where('game_session_id','=',$gameSession->id)
            ->where('is_correct','=','1')
            ->count();

        $incorrect = DB::table('question_sessions')
            ->where('game_session_id','=',$gameSession->id)
            ->where('is_correct','=','0')
            ->count();

        $total = DB::table('question_sessions')
            ->where('game_session_id','=',$gameSession->id)
            ->count();

        $diff = date_diff($startTime, $endTime);
        $gameDuration  = Carbon::createFromTime($diff->h, $diff->i, $diff->s, null)->toDateTimeString();

        $gameSessionInput = [
            'user_id'=>$user->id,
            'game_id'=>$game->id,
            'points'=>$inputs['points'],
            'correct_answers'=>$correct,
            'incorrect_answers'=>$incorrect,
            'total_questions'=>$total,
            'game_start_time'=>$startTime,
            'game_end_time'=>$endTime,
            'duration'=>$gameDuration,
            'status'=>'finished',
            'is_saved_to_lrs' => $isSavedToLRS
        ];

        $res = $this->GameController->saveGameSession($gameSession, $gameSessionInput);

        if(!$res['success']){
            return response()->json($res, 400);
        }

        return response()->json(null,202);
    }

    function quizStart($quizId, Request $request){
        $quiz = Quiz::find($quizId);

        $inputs = $request->all();
        $inputs['quiz_id'] = $quizId;

        $quizSession = new QuizSessions();

        $validator = \Validator::make($inputs, [
            'user_id' => 'required|exists:users,id,deleted_at,NULL',
            'game_id' => 'required|exists:games,id,deleted_at,NULL',
            'quiz_id' => 'required|exists:quizzes,id,deleted_at,NULL',
            'game_session_id' => 'required|exists:game_sessions,id,deleted_at,NULL',
            'start_time' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'errors' => $validator->messages()],422);

        }

        $user = User::find($request->get('user_id'));
        $game = Game::find($request->get('game_id'));
        $startTime = new \DateTime( $inputs['start_time']);
        $gameSession = GameSessions::find($inputs['game_session_id']);

        $isSavedToLRS = true;
        try{
            $this->xApiMyArcadeChef->startQuiz($user, $quiz);
        }catch (\Exception $e) {
            //mark this session as not saved in lrs and continue
            $isSavedToLRS = false;
            \Log::error($e);
        }

        $gameSessionInput = [
            'game_session_id'=>$gameSession->id,
            'user_id'=>$user->id,
            'game_id'=>$game->id,
            'quiz_id'=>$quiz->id,
            'game_start_time'=>$startTime,
            'status'=>'started',
            'is_saved_to_lrs' => $isSavedToLRS
        ];

        $res = $this->GameController->saveQuizSession($quizSession, $gameSessionInput);

        if(!$res['success']){
            return response()->json($res, 400);
        }

        return response()->json(['quiz_session_id'=>$quizSession->id],200);
    }

    function quizFinish($quizId,Request $request){

        $quiz = Quiz::find($quizId);


        if(!$quiz){
            return response()->json(['error' => "Quiz with id `$quizId` does not exists"], 404 );
        }

        $inputs = $request->all();
        $inputs['quiz_id'] = $quizId;

        $gameId = $inputs['game_id'];
        $game = Game::find($gameId);

        if(!$game){
            return response()->json(['error' => "Game with id `$gameId` does not exists"], 404 );
        }

        if($request->has('didTheUserCompleteTheQuiz')){
            if(in_array(strtolower($inputs['didTheUserCompleteTheQuiz']), [1, 'true'], true)){
                $inputs['didTheUserCompleteTheQuiz'] = 1;
            }
            else if(in_array(strtolower($inputs['didTheUserCompleteTheQuiz']), [0, 'false'], true)){
                $inputs['didTheUserCompleteTheQuiz'] = 0;
            }
        }

        $validator = \Validator::make($inputs, [
            'user_id' => 'required|exists:users,id,deleted_at,NULL',
            'game_id' => 'required|exists:games,id,deleted_at,NULL',
            'quiz_id' => 'required|exists:quizzes,id,deleted_at,NULL',
            'quiz_session_id' => 'required|exists:quiz_sessions,id,deleted_at,NULL',
            'game_session_id' => 'required|exists:game_sessions,id,deleted_at,NULL',
            'points' => 'required|integer|min:0',
            'didTheUserCompleteTheQuiz' => 'required|boolean',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'errors' => $validator->messages()],422);

        }

        $startTime = new \DateTime($inputs['start_time']);
        $endTime = new \DateTime( $inputs['end_time']);

        $duration = date_diff($startTime, $endTime)->format("P%yY%mM%dDT%hH%iM%sS");

        $duration = str_replace(["M0S", "H0M", "D0H", "M0D", "Y0M", "P0Y"], ["M", "H", "D", "M", "Y0M", "P"], $duration);

        $user = User::find($request->get('user_id'));

        $isSavedToLRS = true;
        try{
            $this->xApiMyArcadeChef->finishQuiz($user, $quiz, $game, $inputs['points'], (boolean) $inputs['didTheUserCompleteTheQuiz'], $duration);
        }catch (\Exception $e) {
            //mark this session as not saved in lrs and continue
            $isSavedToLRS = false;
            \Log::error($e);
        }

        $gameSession = GameSessions::find($inputs['game_session_id']);
        $quizSession = QuizSessions::find($inputs['quiz_session_id']);


        $correct = DB::table('question_sessions')
            ->where('quiz_session_id','=',$quizSession->id)
            ->where('is_correct','=','1')
            ->count();

        $incorrect = DB::table('question_sessions')
            ->where('quiz_session_id','=',$quizSession->id)
            ->where('is_correct','=','0')
            ->count();

        $total = DB::table('question_sessions')
            ->where('quiz_session_id','=',$quizSession->id)
            ->count();

        $quizSessionInput = [
            'game_session_id'=>$gameSession->id,
            'user_id'=>$user->id,
            'game_id'=>$game->id,
            'quiz_id'=>$quiz->id,
            'correct_answers'=>$correct,
            'incorrect_answers'=>$incorrect,
            'total_questions'=>$total,
            'quiz_start_time'=>$startTime,
            'quiz_end_time'=>$endTime,
            'status'=>'finished',
            'is_saved_to_lrs' => $isSavedToLRS
        ];

        $res = $this->GameController->saveQuizSession($quizSession, $quizSessionInput);

        if(!$res['success']){
            return response()->json($res, 400);
        }

        return response()->json(null,202);
    }

    function questionFinish($questionId, Request $request){

        $question = Question::find($questionId);

        if(!$question){
            return response()->json(['error' => "Question with id `$questionId` does not exists"], 404 );
        }

        $inputs = $request->all();

        if($request->has('didTheUserAnswerQuestion')){
            if(in_array(strtolower($inputs['didTheUserAnswerQuestion']), [1, 'true'], true)){
                $inputs['didTheUserAnswerQuestion'] = 1;
            }
            else if(in_array(strtolower($inputs['didTheUserAnswerQuestion']), [0, 'false'], true)){
                $inputs['didTheUserAnswerQuestion'] = 0;
            }
        }

        $validator = \Validator::make($inputs, [
            'user_id' => 'required|exists:users,id,deleted_at,NULL',
            'answer_id' => 'required|exists:answers,id,deleted_at,NULL',
            'quiz_id' => 'required|exists:quizzes,id,deleted_at,NULL',
            'game_id' => 'required|exists:games,id,deleted_at,NULL',
            'answer_id' => 'required|exists:answers,id,deleted_at,NULL',
            'quiz_session_id' => 'required|exists:quiz_sessions,id,deleted_at,NULL',
            'game_session_id' => 'required|exists:game_sessions,id,deleted_at,NULL',
            'didTheUserAnswerQuestion' => 'required|boolean',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'errors' => $validator->messages()],422);

        }

        $startTime = new \DateTime( $inputs['start_time']);
        $endTime = new \DateTime( $inputs['end_time']);

        $duration = date_diff($startTime, $endTime)->format("P%yY%mM%dDT%hH%iM%sS");

        $duration = str_replace(["M0S", "H0M", "D0H", "M0D", "Y0M", "P0Y"], ["M", "H", "D", "M", "Y0M", "P"], $duration);

        $user = User::find($request->get('user_id'));

        $answer = Answer::find($inputs['answer_id']);

        $quiz = Quiz::find($inputs['quiz_id']);

        $game = Game::find($inputs['game_id']);

        $isSavedToLRS = true;
        try{
            $this->xApiMyArcadeChef->answerQuestion($user, $question, $answer, $quiz, $game,(boolean) $inputs['didTheUserAnswerQuestion'], $duration);
        }catch (\Exception $e) {
            //mark this session as not saved in lrs and continue
            $isSavedToLRS = false;
            \Log::error($e);
        }

        $gameSession = GameSessions::find($inputs['game_session_id']);
        $quizSession = QuizSessions::find($inputs['quiz_session_id']);
        $questionSession = new QuestionSessions();
        $questionSessionInput = [
            'game_session_id'=>$gameSession->id,
            'quiz_session_id'=>$quizSession->id,
            'user_id'=>$user->id,
            'game_id'=>$game->id,
            'quiz_id'=>$quiz->id,
            'question_id'=>$question->id,
            'question_start_time'=>$startTime,
            'question_end_time'=>$endTime,
            'answer_id'=>$answer->id,
            'is_correct'=>$answer->is_correct,
            'is_saved_to_lrs' => $isSavedToLRS
        ];

        $res = $this->GameController->saveQuestionSession($questionSession, $questionSessionInput);

        if(!$res['success']){
            return response()->json($res, 400);
        }

        return response()->json(null,202);
    }

}