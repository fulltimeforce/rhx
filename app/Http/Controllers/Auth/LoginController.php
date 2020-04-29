<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    protected function redirectTo()
    {
        
        $page = '';
        switch (auth()->user()->default_page) {
            case 'resume':
                $page = route('expert.portfolio.resume');
                break;
            case 'log':
                $page = route('recruiter.log');
                break;
            case 'expert':
                $page = route('experts.home');
                break;
            case 'careers':
                $page = RouteServiceProvider::HOME;
                break;
            default:
                $page = RouteServiceProvider::HOME;
                break;
        }
        return $page;
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
