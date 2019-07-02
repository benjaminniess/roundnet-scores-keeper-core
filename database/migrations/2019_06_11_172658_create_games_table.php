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
            $table->bigInteger('player1')->unsigned();
            $table->bigInteger('player2')->unsigned();
            $table->bigInteger('player3')->unsigned();
            $table->bigInteger('player4')->unsigned();
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
            $table->bigInteger('player_id')->unsigned();
            $table->bigInteger('action_type_id')->unsigned();
            $table->integer('score_team_1');
            $table->integer('score_team_2');
            $table->timestamps();
        });

        Schema::table('game_points', function (Blueprint $table) {
            $table->foreign('action_type_id')->references('id')->on('actions_types');
        });


        Schema::create('user_relationships', function (Blueprint $table) {
            $table->bigInteger('user_id_1')->unsigned();
            $table->bigInteger('user_id_2')->unsigned();
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
