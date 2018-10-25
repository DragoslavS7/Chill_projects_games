<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['text', 'slider', 'boolean']);

            $table->text('answer');
            $table->unsignedInteger('question_id');
            $table->boolean('is_correct');

            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->integer('start')->nullable();
            $table->integer('correct_value')->nullable();
            $table->integer('increment')->nullable();

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
        Schema::drop('answers');
    }
}
