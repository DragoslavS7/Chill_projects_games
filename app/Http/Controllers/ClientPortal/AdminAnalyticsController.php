<?php
/**
 * Created by PhpStorm.
 * User: stojan
 * Date: 9/26/2018
 * Time: 9:46 AM
 */

namespace App\Http\Controllers\ClientPortal;


use App\Game;
use App\Http\Controllers\API\ClientPortal\QuizController;
use App\Http\Controllers\API\ClientPortal\UsersController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\Controller;
use App\Quiz;
use App\User;
use Illuminate\Http\Request;

class AdminAnalyticsController extends Controller
{
    function index(Request $request){
        $client = $request->clientPortal;

        $user = new User();

        $players = $user->getUsers($client->id);
        $players = json_encode(['client_portal_id' => $client->id, 'number_of_users' => $players]);

        $adminAnalytics = $user->getAdminAnalytics($client->id);
        $adminAnalytics = json_encode(['client_portal_id' => $client->id, 'users' => $adminAnalytics]);

        $game = new Game();

        $gameScore = $game->getGameAvgScore($client->id);
        $gameScore = json_encode($gameScore);

        $gameDuration = $game->getGameAvgTime($client->id);
        $gameDuration = json_encode($gameDuration);

        $gameAnalytics = $game->getGameAnalytics($client->id);
        $gameAnalytics = json_encode($gameAnalytics);

        $quiz = new Quiz();
        $quizAnalytics = $quiz->getQuizzes($client->id);
        $quizAnalytics = json_encode($quizAnalytics);

        return view('client-portal.admin-analytics.index', ['user' => \Auth::user(),
                                                                 'client'=>$client,
                                                                    'players'=>$players,
                                                                    'gameScore'=>$gameScore,
                                                                    'gameDuration'=>$gameDuration,
                                                                    'gameAnalytics'=>$gameAnalytics,
                                                                    'quizAnalytics'=>$quizAnalytics,
                                                                    'adminAnalytics'=>$adminAnalytics
        ]);
    }
}