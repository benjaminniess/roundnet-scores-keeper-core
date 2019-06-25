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
	    if ( ! Schema::hasColumn( 'games', 'start_date' ) ) {
		    Schema::table('games', function (Blueprint $table) {
			    $table->string('start_date')->nullable();
		    });
	    }

	    if ( Schema::hasColumn( 'games', 'game_duration' ) ) {
		    Schema::table('games', function (Blueprint $table) {
			    $table->dropColumn('game_duration');
		    });
	    }

	    if ( ! Schema::hasColumn( 'games', 'current_server' ) ) {
		    Schema::table('games', function (Blueprint $table) {
			    $table->integer('current_server')->default(0);
			    $table->foreign('current_server')->references('id')->on('users');
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
    	if ( ! Schema::hasColumn( 'games', 'game_duration' ) ) {
		    Schema::table('games', function (Blueprint $table) {
			    $table->integer('game_duration')->nullable();
		    });
	    }


	    if ( Schema::hasColumn( 'games', 'start_date' ) ) {
		    Schema::table('games', function (Blueprint $table) {
			    $table->dropColumn('start_date');
		    });
	    }

	    if ( Schema::hasColumn( 'games', 'current_server' ) ) {
	        Schema::table('games', function (Blueprint $table) {
	            $table->dropColumn('current_server');
	        });
	    }
    }
}
