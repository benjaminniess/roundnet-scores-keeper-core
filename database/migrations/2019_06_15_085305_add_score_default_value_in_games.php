<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScoreDefaultValueInGames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->string('score_team_1')->default(0)->change();
            $table->string('score_team_2')->default(0)->change();
            $table->string('game_duration')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->string('score_team_1')->default(NULL)->change();
            $table->string('score_team_2')->default(NULL)->change();
            $table->string('game_duration')->default(NULL)->change();
        });
    }
}
