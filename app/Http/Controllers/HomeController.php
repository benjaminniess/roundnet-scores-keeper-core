<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /** @var User $user_obj */
        $user_obj = \App\User::find(Auth::id());
        if (empty($user_obj)) {
            return view('home-not-logged');
        }

        if ('true' === request('flush_token')) {
            $user_obj->get_access_token(true);

            return redirect(url('/games/live'));
        }

        $live_game = $user_obj->get_live_game();
        if (!empty($live_game)) {
            return redirect(url('/games/live'));
        }

        return view('home');
    }
}
