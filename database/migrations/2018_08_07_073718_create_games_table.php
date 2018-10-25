<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('games',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('client_portal_id');
            $table->string('name');
            $table->string('description');
            $table->string('url');
            $table->boolean('allow_anonymous_players')->deafult(false);
            $table->string('required_additional_player_data')->nullable();
            $table->enum('score_type', ['point','percentage']);
            $table->integer('score_to_win');
            $table->integer('max_score');
            $table->boolean('is_leadboard_visible')->default(true);
            $table->string('company_info');
            $table->boolean('are_quizzes_randomized')->default(true);
            $table->string('quiz_intro_text');
            $table->string('game_icon');
            $table->string('splash_page_image');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('game_template_id');
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
        Schema::drop('games');
    }
}
