<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrlsToGameTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_templates',function(Blueprint $table){
            $table->string('video_url');
            $table->string('demo_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_templates',function(Blueprint $table){
            $table->dropColumn('video_url');
            $table->dropColumn('demo_url');
        });
    }
}
