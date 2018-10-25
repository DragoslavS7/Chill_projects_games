<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreteGameSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('game_id');
            $table->string('game_name');
            $table->integer('points');
            $table->integer('correct_answers');
            $table->integer('incorrect_answers');
            $table->integer('total_questions');
            $table->timestamp('game_start_time');
            $table->timestamp('game_end_time');
            $table->enum('status',['started','finished']);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('game_sessions');
    }
}
