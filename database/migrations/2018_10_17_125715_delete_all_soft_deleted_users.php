<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteAllSoftDeletedUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = \App\User::whereNotNull('deleted_at')->get();

        foreach ($users as $user) {
            if ($user->id !== $user->clientPortal->default_admin_id) {
                $games = \App\Game::where('author_id', $user->id)->get();
                foreach ($games as $game) {
                    if (!is_null($game)) {
                        $game->author_id = $user->clientPortal->default_admin_id;
                        $game->save();
                    }
                }
                $games = \App\Game::where('updated_by_id', $user->id)->get();
                foreach ($games as $game) {
                    if (!is_null($game)) {
                        $game->updated_by_id = 0;
                        $game->save();
                    }
                }

                $quizes = \App\Quiz::where('author_id', $user->id)->get();
                foreach ($quizes as $quiz) {
                    if (!is_null($quiz)) {
                        $quiz->author_id = $user->clientPortal->default_admin_id;
                        $quiz->save();
                    }
                }
                $quizes = \App\Quiz::where('updated_by_id', $user->id)->get();
                foreach ($quizes as $quiz) {
                    if (!is_null($quiz)) {
                        $quiz->updated_by_id = 0;
                        $quiz->save();
                    }
                }

                $user->delete();
            }
        }

        \Illuminate\Support\Facades\Schema::table('users', function($table) {
            $table->dropColumn('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\Schema::table('users', function($table) {
            $table->softDeletes();
        });
    }
}
