<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('actions_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('action_type');
            $table->timestamps();
        });

        Schema::create('games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('player1');
            $table->integer('player2');
            $table->integer('player3');
            $table->integer('player4');
            $table->integer('score_team_1');
            $table->integer('score_team_2');
            $table->integer('game_duration');
            $table->timestamps();
        });

        Schema::table('games', function (Blueprint $table) {
            $table->foreign('player1')->references('id')->on('users');
            $table->foreign('player2')->references('id')->on('users');
            $table->foreign('player3')->references('id')->on('users');
            $table->foreign('player4')->references('id')->on('users');
        });

        Schema::create('game_points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('player_id');
            $table->integer('action_type_id');
            $table->integer('score_team_1');
            $table->integer('score_team_2');
            $table->timestamps();
        });

        Schema::table('game_points', function (Blueprint $table) {
            $table->foreign('action_type_id')->references('id')->on('actions_types');
        });


        Schema::create('user_relationships', function (Blueprint $table) {
            $table->integer('user_id_1');
            $table->integer('user_id_2');
            $table->integer('status');
            $table->timestamps();
        });
        Schema::table('user_relationships', function (Blueprint $table) {
            $table->foreign('user_id_1')->references('id')->on('users');
            $table->foreign('user_id_2')->references('id')->on('users');

            $table->primary(['user_id_1', 'user_id_2']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions_types');
        Schema::dropIfExists('games');
        Schema::dropIfExists('game_points');
        Schema::dropIfExists('user_relationships');
    }
}
