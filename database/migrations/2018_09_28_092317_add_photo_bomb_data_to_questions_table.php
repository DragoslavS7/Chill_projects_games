<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhotoBombDataToQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions',function(Blueprint $table){
            $table->text('question_image');
            $table->text('question_video');
            $table->string('question_meta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions',function(Blueprint $table){
            $table->dropColumn('question_image');
            $table->dropColumn('question_video');
            $table->dropColumn('question_meta');
        });
    }
}
