<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsPortalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_portals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->string('sub_domain');
            $table->unsignedInteger('number_of_admins');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('address');
            $table->string('phone');
            $table->string('fax');
            $table->string('website');
            $table->text('logo');
            $table->string('custom_style');
            $table->boolean('is_costumer_service_available')->default(false);
            $table->boolean('is_enabled')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('client_portals');
    }
}
