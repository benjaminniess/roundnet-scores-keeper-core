<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Action_Type;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of stats.
     *
     * @return 
     */
    public function stats()
    {
        // The logged user
    	$user_obj = User::find(auth()->id());

        // get user total time spent playing
        $games_as_player = $user_obj->games;
        $player_games_duration = [];
        foreach ($games_as_player as $game) {
            $game->duration = $game->duration();
            array_push($player_games_duration,$game->duration());
        }
        $time_spent_playing = gmdate('H:i:s', array_sum($player_games_duration));

        // get user total time spent refereing
        $games_as_referee = $user_obj->games_as_referee()->get();
        $referee_games_duration = [];
        foreach ($games_as_referee as $game) {
            $game->duration = $game->duration();
            array_push($referee_games_duration,$game->duration());
        }
        $time_spent_refereing = gmdate('H:i:s', array_sum($referee_games_duration));

        $points_types_chart = $user_obj->get_chart_js_points_types();
        $victory_stats_chart = $user_obj->get_chart_js_victory_stats();

        return view('users.stats',compact(
            'time_spent_playing',
            'time_spent_refereing',
            'victory_stats_chart',
            'points_types_chart'
        ));
    }

    /**
     * Show the form to edit the authentificated user
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = User::find(auth()->id());
        return view('users.account', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $attributes = request()->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        $user->update($attributes);

        return back()->with(
            'info-message-success',
            'Your information has been updated.'
        );
    }

    /**
     * Update the specified user password in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update_password( Request $request, User $user )
    {
        $attributes = request()->validate([
            'old_password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $validator = Validator::make( $request->all(), [] );

        $old_password = $user->password;

        // Check if the current user password match with the old password typed in by the user
        if ( !Hash::check( $request->old_password, $old_password ) ) {
            $validator
                ->errors()
                ->add(
                    'different-old-password',
                    'Your old password is incorrect.'
                );
                return back()->withErrors( $validator );
        }

        // Update user hashed password
        $user->update( ['password' => Hash::make( $attributes['new_password'] )] );

        return back()->with(
            'password-message-success',
            'Your password has been updated.'
        );
    }

    /**
     * Delete the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user){
        // Delete all user players
        if(!$user->players->isEmpty()){
            foreach ($user->players as $player) {
                $player->delete();
            }
        }

        // Delete all user_relationships entries
        if (!$user->get_all_relationships()->isEmpty()) {
            foreach ($user->get_all_relationships() as $relationship) {
                $relationship->delete();
            }
        }

        // Delete the user himself
        $user->delete();

        return redirect('/')->with('account-deleted-message', 'Your account has been successfully deleted.');
    }
}
