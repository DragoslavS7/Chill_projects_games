<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetDefaultSplashAndIconForGame extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $platform = Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        $gamesWithoutGameIcon = \App\Game::where('game_icon', '')->get();

        foreach ($gamesWithoutGameIcon as $gameWithoutGameIcon){
            $gameWithoutGameIcon->game_icon = '/images/game-icons/ArcadeChef_logo_icon_default.png';
            $gameWithoutGameIcon->save();
        }

        $gamesWithoutSplashIcon = \App\Game::where('splash_page_image', '')->get();

        foreach ($gamesWithoutSplashIcon as $gameWithoutSplashIcon){
            $gameWithoutSplashIcon->splash_page_image = '/images/game-icons/ArcadeChef_logo_icon_splash.png';
            $gameWithoutSplashIcon->save();
        }


        Schema::table('games', function (Blueprint $table) {
            $table->string('game_icon')->default('/images/game-icons/ArcadeChef_logo_icon_default.png')->change();
            $table->string('splash_page_image')->default('/images/game-icons/ArcadeChef_logo_icon_splash.png')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $platform = Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');


        $gamesWithoutGameIcon = \App\Game::where('game_icon', '/images/game-icons/ArcadeChef_logo_icon_default.png')->get();

        foreach ($gamesWithoutGameIcon as $gameWithoutGameIcon){
            $gameWithoutGameIcon->game_icon = '';
            $gameWithoutGameIcon->save();
        }

        $gamesWithoutSplashIcon = \App\Game::where('splash_page_image', '/images/game-icons/ArcadeChef_logo_icon_splash.png')->get();

        foreach ($gamesWithoutSplashIcon as $gameWithoutSplashIcon){
            $gameWithoutSplashIcon->splash_page_image = '';
            $gameWithoutSplashIcon->save();
        }



        Schema::table('games', function (Blueprint $table) {
            $table->string('game_icon')->default(NULL)->change();
            $table->string('splash_page_image')->default(NULL)->change();
        });
    }
}
