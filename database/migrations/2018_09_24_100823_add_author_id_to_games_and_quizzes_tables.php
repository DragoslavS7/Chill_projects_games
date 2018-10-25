<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorIdToGamesAndQuizzesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games',function(Blueprint $table){
            $table->integer('author_id');
        });
        Schema::table('quizzes',function(Blueprint $table){
            $table->integer('author_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games',function(Blueprint $table){
            $table->dropColumn('author_id');
        });
        Schema::table('quizzes',function(Blueprint $table){
            $table->dropColumn('author_id');
        });
    }
}