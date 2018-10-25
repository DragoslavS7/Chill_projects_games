<?php

namespace App\Http\Controllers\API\ClientPortal;

use App\ClientPortal;
use App\Http\Controllers\Controller;
use App\Quiz;

class QuizController extends Controller
{
    function getQuizzes($clientPortalId){
        $clientPortal = ClientPortal::find($clientPortalId);

        if(!$clientPortal){
            return response()->json([
                'error' => "Client portal with id `$clientPortalId` does not exists."
            ], 404);
        }

        $quiz = new Quiz();
        $quizData = $quiz->getQuizzes($clientPortalId);

        return response()->json($quizData, 200);
    }
}