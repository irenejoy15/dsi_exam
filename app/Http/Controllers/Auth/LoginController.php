<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Session;

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
    protected $redirectTo = '/customer/products';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {   
        if (Auth::check()):
            return redirect('/customer/products')->with('danger','YOUR ARE ALREADY LOG IN');
        else:
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)):
                $user = Auth::user();
                if ($user->is_active):
                    return redirect()->intended('/customer/products')->with('success','Successfully login!');
                else:
                    Auth::logout();
                    return redirect('/login')->with('danger','Your account is inactive. Please contact the administrator.');
                endif;
            else:
                return redirect('/login')->with('danger','Invalid email or password. Please try again.');
            endif;
        endif;
        
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login')->with('success','Successfully logout!');
    }
}
