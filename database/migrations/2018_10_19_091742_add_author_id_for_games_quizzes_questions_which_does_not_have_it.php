<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorIdForGamesQuizzesQuestionsWhichDoesNotHaveIt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $clientPortals = \App\ClientPortal::all();

        foreach ($clientPortals as $clientPortal) {
            $games = \App\Game::where('author_id', 0)
                ->where('client_portal_id', $clientPortal->id)->get();
            foreach ($games as $game) {
                $game->author_id = $clientPortal->default_admin_id;
                $game->save();
            }

            $quizzes = \App\Quiz::where('author_id', 0)
                ->where('client_portal_id', $clientPortal->id)->get();
            foreach ($quizzes as $quizz) {
                $quizz->author_id = $clientPortal->default_admin_id;
                $quizz->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
