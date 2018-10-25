<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStatusToEnumInQuizSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //TODO check validity of migration
        DB::statement("ALTER TABLE quiz_sessions MODIFY status ENUM('started', 'finished');");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE quiz_sessions MODIFY status STRING;');
    }
}
