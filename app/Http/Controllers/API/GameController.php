<?php

namespace App\Http\Controllers\API;

use App\ClientPortal;
use App\Game;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class GameController extends Controller
{
    function getGame($gameId){
        $game = \App\Game::has('quizzes')->with([
            'template' => function($query){
                $query->select(
                    'id',
                    'name',
                    'source',
                    'description',
                    'genre',
                    'template_icon',
                    'screenshot',
                    'is_active',
                    'video_url',
                    'demo_url'
                );
            },
            'quizzes' => function($query){
                $query->select(
                    'quizzes.id',
                    'quizzes.name',
                    'quizzes.description',
                    'quizzes.is_photobombed',
                    'quizzes.is_enabled as is_active',
                    'quizzes.are_questions_randomized'
                );

                $query->has('questions');

                $query->with([
                    'questions' => function($query){
                        $query->select(
                            'questions.id',
                            'questions.name',
                            'questions.description',
                            'questions.question_type',
                            'questions.order_type',
                            'questions.is_feedback_display_available',
                            'questions.correct_feedback',
                            'questions.incorrect_feedback',
                            'questions.is_enabled as is_active',
                            'questions.question_image',
                            'questions.question_video',
                            'questions.question_meta',
                            'questions.is_puzzle'
                        );

                        $query->has('answers');

                        $query->with([
                            'answers' => function($query){
                                $query->select(
                                    'answers.id',
                                    'answers.question_id',
                                    'answers.type',
                                    'answers.answer',
                                    'answers.is_correct',
                                    'answers.min',
                                    'answers.max',
                                    'answers.start',
                                    'answers.correct_value',
                                    'answers.increment'
                                );
                            }
                        ]);
                    }
                ]);
            },

        ])
            ->select(
                'id',
                'name',
                'description',
                'url',
                'score_type',
                'score_to_win',
                'max_score',
                'is_leadboard_visible',
                'company_info',
                'are_quizzes_randomized',
                'quiz_intro_text',
                'game_icon',
                'splash_page_image',
                'is_active',
                'game_template_id'
            )->find($gameId);

        $error = [];

        if(!$game){
            $error['error'] = "Game with id `$gameId` do not exists.";
            return response($error, 404);
        }

        // Generate game url
        $url = \App\Game::find($gameId)->clientPortal->sub_domain;

        $url .= '.' . env('APP_URL');
        $url = 'https://' . $url;

        $game->url = $url . $game->url;

        // Add correct to questions
        foreach($game->quizzes as $quiz){

            foreach($quiz->questions as $question){
                $correct_answers_indexes = [];

                foreach($question->answers as $index => $answer) {
                    if ($answer->is_correct) {
                        array_push($correct_answers_indexes, $index);
                    }
                    $answer->setHidden(['is_correct']);
                }
                $question->correct_answers = $correct_answers_indexes;
            }
        }

        return response($game, 200);
    }

    function getGameAnalytics($clientPortalId){
        $clientPortal = ClientPortal::find($clientPortalId);

        $error = [];

        if(!$clientPortal){
            $error['error'] = "Client portal with id `$clientPortalId` does not exists.";
            return response($error, 404);
        }

        $game = new Game();
        $gameData = $game->getGameAnalytics($clientPortalId);

        return response()->json($gameData, 200);
    }

    function getGameAvgTime($clientPortalId){
        $clientPortal = ClientPortal::find($clientPortalId);

        $error = [];

        if(!$clientPortal){
            $error['error'] = "Client portal with id `$clientPortalId` does not exists.";
            return response($error, 404);
        }

        $game = new Game();
        $gameData = $game->getGameAvgTime($clientPortalId);

        return response()->json($gameData, 200);
    }

    function getGameAvgScore($clientPortalId){
        $clientPortal = ClientPortal::find($clientPortalId);

        $error = [];

        if(!$clientPortal){
            $error['error'] = "Client portal with id `$clientPortalId` does not exists.";
            return response($error, 404);
        }

        $game = new Game();
        $gameData = $game->getGameAvgScore($clientPortalId);

        return response()->json($gameData, 200);
    }

    function saveGameSession($gameSession, $gameSessionInput){

        if ($gameSession->validate($gameSessionInput)) {

            DB::beginTransaction();
            try{
                $gameSession->fill($gameSessionInput);
                $gameSession->save();

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                $errors = new MessageBag();
                $errors->add('save', $e->getMessage());

                return ['success' => false, 'errors' => $errors];
            }
            return ['success' => true, 'game_session_id'=>$gameSession->id];
        }else{
            return ['success' => false, 'errors' => $gameSession->errors()];
        }
    }

    function saveQuizSession($quizSession, $quizSessionInput){

        if ($quizSession->validate($quizSessionInput)) {
            DB::beginTransaction();

            try{
                $quizSession->fill($quizSessionInput);
                $quizSession->save();

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();

                $errors = new MessageBag();
                $errors->add('save', $e->getMessage());

                return ['success' => false, 'errors' => $errors];
            }
            return ['success' => true];
        }else{
            return ['success' => false, 'errors' => $quizSession->errors()];
        }
    }

    function saveQuestionSession($questionSession, $questionSessionInput){

        if ($questionSession->validate($questionSessionInput)) {
            DB::beginTransaction();
            try{
                $questionSession->fill($questionSessionInput);
                $questionSession->save();

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();

                $errors = new MessageBag();
                $errors->add('save', $e->getMessage());

                return ['success' => false, 'errors' => $errors];
            }
            return ['success' => true];
        }else{
            return ['success' => false, 'errors' => $questionSession->errors()];
        }
    }
}