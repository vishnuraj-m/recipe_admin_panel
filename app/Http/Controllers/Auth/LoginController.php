<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

    protected function authenticated(Request $request, $user)
    {
        //Check user role, if it is not admin then logout
        if(!$user->usertype == 1)
        {
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('/login')->with('status', 'You are unauthorized to login');
        }
    }

    protected function redirectTo()
    {
        $usertype = Auth::user()->usertype;
        switch ($usertype) {
            case 1:
                return '/dashboard';
                break;
            default:
                return '/';
                break;
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
