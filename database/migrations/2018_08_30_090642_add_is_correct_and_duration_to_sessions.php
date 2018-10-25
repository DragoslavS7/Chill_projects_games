<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsCorrectAndDurationToSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_sessions',function(Blueprint $table){
            $table->timestamp('duration');
        });

        Schema::table('question_sessions',function(Blueprint $table){
            $table->boolean('is_correct');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_sessions',function(Blueprint $table){
            $table->dropColumn('duration');
        });

        Schema::table('question_sessions',function(Blueprint $table){
            $table->dropColumn('is_correct');
        });
    }
}
