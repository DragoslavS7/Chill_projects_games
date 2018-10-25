<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSavedToLrsToTablesAnswersQuestionsQuizzesGamesUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('answers', function(Blueprint $table){
            $table->boolean('is_saved_to_lrs')->default(true);
        });

        Schema::table('questions', function(Blueprint $table){
            $table->boolean('is_saved_to_lrs')->default(true);
        });

        Schema::table('quizzes', function(Blueprint $table){
            $table->boolean('is_saved_to_lrs')->default(true);
        });

        Schema::table('games', function(Blueprint $table){
            $table->boolean('is_saved_to_lrs')->default(true);
        });

        Schema::table('users', function(Blueprint $table){
            $table->boolean('is_saved_to_lrs')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('answers', function(Blueprint $table){
            $table->dropColumn('is_saved_to_lrs');
        });

        Schema::table('questions', function(Blueprint $table){
            $table->dropColumn('is_saved_to_lrs');
        });

        Schema::table('quizzes', function(Blueprint $table){
            $table->dropColumn('is_saved_to_lrs');
        });

        Schema::table('games', function(Blueprint $table){
            $table->dropColumn('is_saved_to_lrs');
        });

        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('is_saved_to_lrs');
        });
    }
}
