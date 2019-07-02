<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RelationsUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if ( ! Schema::hasTable( 'players' ) ) {
		    Schema::create('players', function (Blueprint $table) {
			    $table->bigIncrements('id');
			    $table->bigInteger('user_id')->unsigned();
			    $table->bigInteger('game_id')->unsigned();
			    $table->integer('position');
            });
            Schema::table('players', function (Blueprint $table) {
			    $table->foreign('game_id')->references('id')->on('games');
			    $table->foreign('user_id')->references('id')->on('users');
		    });
	    }

	    if ( Schema::hasColumn( 'games', 'player1' ) ) {
		    Schema::table('games', function (Blueprint $table) {
			    $table->dropColumn('player1');
		    });

		    Schema::table('games', function (Blueprint $table) {
			    $table->dropColumn('player2');
		    });

		    Schema::table('games', function (Blueprint $table) {
			    $table->dropColumn('player3');
		    });

		    Schema::table('games', function (Blueprint $table) {
			    $table->dropColumn('player4');
		    });
	    }

	    Schema::dropIfExists('user_relationships');
	    if ( ! Schema::hasTable( 'user_relationships' ) ) {
		    Schema::create('user_relationships', function (Blueprint $table) {
			    $table->bigIncrements('id');
			    $table->bigInteger('user_id_1')->unsigned();
			    $table->bigInteger('user_id_2')->unsigned();
			    $table->string('status');

			    $table->timestamps();

			    $table->foreign('user_id_1')->references('id')->on('users');
			    $table->foreign('user_id_2')->references('id')->on('users');
		    });
	    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('players');

	    if ( ! Schema::hasColumn( 'games', 'player1' ) ) {
		    Schema::table('games', function (Blueprint $table) {
			    $table->bigInteger('player1')->nullable();
			    $table->bigInteger('player2')->nullable();
			    $table->bigInteger('player3')->nullable();
			    $table->bigInteger('player4')->nullable();

			    $table->foreign('player1')->references('id')->on('users');
			    $table->foreign('player2')->references('id')->on('users');
			    $table->foreign('player3')->references('id')->on('users');
			    $table->foreign('player4')->references('id')->on('users');
		    });
	    }
    }
}
