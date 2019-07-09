<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    /**
     * Display a listing of stats.
     *
     * @return 
     */
    public function stats()
    {
        $coming_soon = 'comming soon';

        return view('users.stats', compact('coming_soon'));
    }
}
