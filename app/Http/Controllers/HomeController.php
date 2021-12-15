<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function logout()
    {
        $user = Auth::user();
        $user->logged_on = null;
        $user->save();
        Auth::logout();
        return redirect()->route('login');
    }

    public function lockscreen()
    {
        // TODO: time tracking code goes here...

        $user = Auth::user();
        $email_id = $user->email;
        $user->logged_on = null;
        $user->save();

        return view('lockscreen', ['email' => $email_id]);
    }

    public function unlockscreen()
    {
        // TODO: time tracking code goes here...

        $user = Auth::user();
        $user->logged_on = date('Y-m-d H:i:s');
        $user->save();

        $module = \App\Models\Module::whereRoleId(Auth::user()->role_id)->first();

        return redirect()->route($module->route_name);
    }

}
