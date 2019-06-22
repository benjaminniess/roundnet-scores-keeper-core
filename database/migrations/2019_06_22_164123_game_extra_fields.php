<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GameExtraFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->string('start_date')->nullable();
            $table->integer('current_server')->default(0);

            $table->dropColumn('game_duration');

            $table->foreign('current_server')->references('id')->on('users');
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
            $table->integer('game_duration')->nullable();
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('start_date');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('current_server');
        });
    }
}
