<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\User;
use App\Providers\RouteServiceProvider;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if(User::whereEmail($credentials['email'])->whereStatus('1')->exists()) {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $user->logged_on = date('Y-m-d H:i:s');
                $user->save();

                if($request->has('remember_me')) {
                    Cookie::queue(Cookie::forever('login_email', $credentials['email']));
                    Cookie::queue(Cookie::forever('login_password', $credentials['password']));
                }else {
                    Cookie::queue(Cookie::forget('login_email'));
                    Cookie::queue(Cookie::forget('login_password'));
                }

                $module = Module::whereRoleId($user->role_id)->whereStatus('1')->first();
                if(!empty($module)) {
                    return redirect()->route($module->route_name);
                } else {
                    return redirect()->route('logout');
                }

            } else {
                return back()->withInput()->with('error', 'Invalid Credentials');
            }
        } else {
            return back()->withInput()->with('error', 'Account suspended, contact admin.');
        }

    }


}
