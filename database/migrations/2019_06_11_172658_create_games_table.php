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

		    $table->foreign('action_type_id')->references('id')->on('actions_types');
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
    }
}
