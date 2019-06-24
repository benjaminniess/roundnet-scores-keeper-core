<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGameOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->boolean('enable_turns')->default(true);
            $table->integer('referee')->default(0);
            $table->integer('points_to_win')->default(21);

            $table->foreign('referee')->references('id')->on('users');
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
            $table->dropColumn('enable_turns');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('referee');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('points_to_win');
        });
    }
}
