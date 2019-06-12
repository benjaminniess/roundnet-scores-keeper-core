<?php

use Illuminate\Database\Seeder;

class GamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(App\User::class, 10)->create()->each(function ($user) {

        });

        for ($i = 0; $i < 10; $i++ ) {
            DB::table('actions_types')->insert([
                'name' => Str::random(10),
                'action_type' => Str::random(10).'@gmail.com',
            ]);
        }


        for ($i = 0; $i < 10; $i++ ) {
            DB::table('games')->insert([
                'player1' => rand(1,8),
                'player2' => rand(1,8),
                'player3' => rand(1,8),
                'player4' => rand(1,8),
                'score_team_1' => rand(1,21),
                'score_team_2' => rand(1,21),
                'game_duration' => rand(100,1000),
            ]);
        }

    }
}
