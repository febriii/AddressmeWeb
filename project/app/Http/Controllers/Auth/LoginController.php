<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

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

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], 1)) 
        {
            if(auth()->user()->status == 3)
            {
                Auth::logout();
                return redirect('/login')->withStatus(_('Maaf, Anda Tidak Memiliki Hak Akses'));
            }elseif(auth()->user()->status == 0){
                Auth::logout();
                return redirect('/login')->withStatus(_('Maaf, Akun Anda tidak aktif'));
            }
            return redirect()->intended('home');
        }

        if(Auth::check())
        {
            return redirect()->intended('home');
        }
        return redirect()->back()->withInput()->withErrors([
            'password' => 'Email / Password Anda Salah!'
        ]);

    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }

    
}
