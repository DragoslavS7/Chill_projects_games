<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->text('description');

            $table->enum('question_type', ['multiple_choice', 'multi_answer', 'slider', 'boolean']);
            $table->enum('order_type', ['numerical', 'alphabetical', 'none']);

            $table->boolean('is_feedback_display_available');
            $table->text('correct_feedback');
            $table->text('incorrect_feedback');

            $table->boolean('is_enabled')->default(true);
            $table->unsignedInteger('client_portal_id');

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
        Schema::drop('questions');
    }
}
