<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveDurationGameNameFromGameSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_sessions',function(Blueprint $table){
            $table->dropColumn('duration');
            $table->dropColumn('game_name');
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
            $table->timestamp('duration');
            $table->string('game_name');
        });
    }
}
