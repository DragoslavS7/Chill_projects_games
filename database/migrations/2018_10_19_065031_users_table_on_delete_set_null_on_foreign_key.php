<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersTableOnDeleteSetNullOnForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        Schema::table('client_portals', function ($table) {
            $table->dropForeign('client_portals_default_admin_id_foreign');
            $table->integer('default_admin_id')->unsigned()->nullable()->after('id')->change();
            $table->foreign('default_admin_id')->references('id')->on('users')->onDelete('set null');
        });

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        Schema::table('client_portals', function ($table) {
            $table->dropForeign('client_portals_default_admin_id_foreign');
            $table->integer('default_admin_id')->unsigned()->after('id')->change();
            $table->foreign('default_admin_id')->references('id')->on('users');
        });

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
    }
}
