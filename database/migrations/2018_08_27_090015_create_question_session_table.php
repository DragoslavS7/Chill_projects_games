<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('game_session_id');
            $table->unsignedInteger('quiz_session_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('game_id');
            $table->unsignedInteger('quiz_id');
            $table->unsignedInteger('question_id');
            $table->string('question_name');
            $table->string('answer');
            $table->timestamp('question_start_time');
            $table->timestamp('question_end_time');
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
        Schema::drop('question_sessions');
    }
}
