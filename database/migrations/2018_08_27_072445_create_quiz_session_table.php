<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('game_session_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('game_id');
            $table->unsignedInteger('quiz_id');
            $table->string('quiz_name');
            $table->integer('correct_answers');
            $table->integer('incorrect_answers');
            $table->integer('total_questions');
            $table->timestamp('quiz_start_time');
            $table->timestamp('quiz_end_time');
            $table->string('status');
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
        Schema::drop('quiz_sessions');
    }
}
