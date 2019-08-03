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
    public function show(User $user)
    {
        // Check is the user is authorized to view the user profile
        $this->authorize('view', $user);

        $auth_user_object = User::find(auth()->id());

        $points_types_chart = $user->get_chart_js_points_types();
        $victory_stats_chart = $user->get_chart_js_victory_stats();

        $badges = $user->badges;

        foreach ($user->unreadNotifications as $notification) {
            if ($user->id === auth()->id() ) {
                $notification->markAsRead();
            }
        }

        return view('users.show',compact(
            'user',
            'badges',
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
        // Check if the user is authorized to update the user profile
        $this->authorize('update', $user);

        $attributes = request()->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

	    $validator = Validator::make( $request->all(), [] );

	    if ( ! empty( $existing_nickname = User::where('name', $attributes['name'] ) -> first() ) ) {
		    $validator
			    ->errors()
			    ->add(
				    'existing-nickname',
				    'This nickname is already taken'
			    );
		    return back()->withErrors( $validator );
	    }

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
        // Check if the user is authorized to update the user profile
        $this->authorize('update', $user);

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
        // Check if the user is authorized to delete the user profile
        $this->authorize('delete', $user);

        $user->destroy_user();

        return redirect('/')->with('account-deleted-message', 'Your account has been successfully deleted.');
    }
}
