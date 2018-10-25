<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientPortalsGameTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_portals_game_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_portal_id');
            $table->unsignedInteger('game_template_id');
        });

        Schema::table('client_portals_game_templates', function(Blueprint $table){
            $table->foreign('client_portal_id')
                    ->references('id')
                    ->on('client_portals')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('game_template_id')
                    ->references('id')
                    ->on('game-templates')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('client_portals_game_templates');
    }
}
