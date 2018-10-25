<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToAllSessionsIsSavedToLrsField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_sessions', function(Blueprint $table){
            $table->boolean('is_saved_to_lrs')->default(true);
        });

        Schema::table('quiz_sessions', function(Blueprint $table){
            $table->boolean('is_saved_to_lrs')->default(true);
        });

        Schema::table('question_sessions', function(Blueprint $table){
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
        Schema::table('game_sessions', function(Blueprint $table){
            $table->dropColumn('is_saved_to_lrs');
        });

        Schema::table('quiz_sessions', function(Blueprint $table){
            $table->dropColumn('is_saved_to_lrs');
        });

        Schema::table('question_sessions', function(Blueprint $table){
            $table->dropColumn('is_saved_to_lrs');
        });
    }
}
