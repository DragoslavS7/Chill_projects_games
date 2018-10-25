<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveQuestionNameFromQuestionSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_sessions',function(Blueprint $table){
            $table->dropColumn('question_name');
            $table->dropColumn('answer');
            $table->unsignedInteger('answer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_sessions',function(Blueprint $table){
            $table->string('question_name');
            $table->string('answer');
            $table->dropColumn('answer_id');
        });
    }
}
