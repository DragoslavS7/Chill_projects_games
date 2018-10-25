<?php

namespace App\Repositories\User;

use App\Game;
use App\Quiz;
use App\User;

/**
 * Class UserRepository
 * @package App\Repositories\User
 */
class UserRepository
{
    /**
     * Method for deleting user related games and quizzes.
     *
     * @param User $user
     */
    public function updateUserRelatedGamesAndQuizzes(User $user)
    {
        $games = Game::where('author_id', $user->id)->get();
        foreach ($games as $game) {
            if (!is_null($game)) {
                $game->author_id = $user->clientPortal->default_admin_id;
                $game->save();
            }
        }
        $games = Game::where('updated_by_id', $user->id)->get();
        foreach ($games as $game) {
            if (!is_null($game)) {
                $game->updated_by_id = 0;
                $game->save();
            }
        }

        $quizes = Quiz::where('author_id', $user->id)->get();
        foreach ($quizes as $quiz) {
            if (!is_null($quiz)) {
                $quiz->author_id = $user->clientPortal->default_admin_id;
                $quiz->save();
            }
        }
        $quizes = Quiz::where('updated_by_id', $user->id)->get();
        foreach ($quizes as $quiz) {
            if (!is_null($quiz)) {
                $quiz->updated_by_id = 0;
                $quiz->save();
            }
        }
    }
}
