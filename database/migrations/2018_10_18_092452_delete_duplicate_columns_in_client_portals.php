<?php

use Illuminate\Database\Migrations\Migration;

class DeleteDuplicateColumnsInClientPortals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $clientPortal = \App\ClientPortal::find(1);

        $newUser = new \App\User();
        $newUser->first_name = 'Demo Name';
        $newUser->last_name = 'Demo Last Name';
        $newUser->email = 'demo@myarcadechef.com';
        $newUser->password = bcrypt('123456');
        $newUser->client_portal_id = $clientPortal->id;
        $newUser->role = 'admin';
        $newUser->is_verified = 1;
        $newUser->is_active = 1;
        $newUser->save();

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        \Illuminate\Support\Facades\Schema::table('client_portals', function($table) {
            $table->integer('default_admin_id')->unsigned()->nullable()->after('id')->change();
            $table->integer('default_admin_id')->unsigned()->after('id')->change();
        });

        $clientPortals = \App\ClientPortal::where('id', '!=', 1)->get();

        foreach ($clientPortals as $portal) {
            $user = \Illuminate\Support\Facades\DB::table('users')->where('email', $portal->email)->first();
            if (!is_null($user)) {
                $portal->default_admin_id = $user->id;
                $portal->save();
            }
        }

        \Illuminate\Support\Facades\Schema::table('client_portals', function($table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('email');
            $table->dropColumn('phone');
        });

        $clientPortal->default_admin_id = $newUser->id;
        $clientPortal->save();

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        \Illuminate\Support\Facades\Schema::table('client_portals', function($table) {
            $table->string('first_name')->after('number_of_admins');
            $table->string('last_name')->after('first_name');
            $table->string('email')->after('last_name');
            $table->string('phone')->after('email');
        });

        $users = \App\User::all();

        foreach ($users as $user) {
            $portal = \App\ClientPortal::where('default_admin_id', $user->id)->first();
            if (!is_null($portal)) {
                $portal->email = $user->email;
                $portal->save();
            }
        }

        \Illuminate\Support\Facades\Schema::table('client_portals', function($table) {
            $table->dropForeign('client_portals_default_admin_id_foreign');
            $table->dropColumn('default_admin_id');
        });

        \Illuminate\Support\Facades\DB::table('users')->where('email', 'demo@myarcadechef.com')->delete();

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
