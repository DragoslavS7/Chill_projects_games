<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game-templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('source');
            $table->text('source_url');
            $table->text('description');
            $table->string('genre');
            $table->text('template_icon');
            $table->text('screenshot');
            $table->boolean('is_active');

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
        Schema::drop('game-templates');
    }
}
