<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Socialite;

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
            case 'fce':
                $page = route('recruit.fce.menu');
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

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        $parameters = ['access_type' => 'offline'];
        return Socialite::driver('google')
        ->scopes(["https://www.googleapis.com/auth/drive"])->with($parameters)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $auth_user = Socialite::driver('google')->stateless()->user();
        $page = '/';
        if( User::where('email' , $auth_user->email )->where('status','ENABLED')->count() > 0 ){
            $user = User::updateOrCreate(
                ['email' => $auth_user->email], 
                [
                    'access_token' => $auth_user->refreshToken,
                    'name'  => $auth_user->name
                ]);
            Auth::login($user, true);
            switch ( $user->default_page ) {
                case 'resume':
                    $page = '/resume';
                    break;
                case 'log': 
                    $page = '/recruiter/log';
                    break;
                case 'expert':
                    $page = '/experts';
                    break;
                case 'recruitment':
                    $page = '/recruits';
                    break;
                case 'careers':
                    $page = '/';
                    break;
                case 'fce':
                    $page = '/recruits/show/fce';
                    break;
                case 'test':
                    $page = '/recruits/show/test';
                    break;
                default:
                    $page = '/';
                    break;
            }
        }
        return redirect()->to( $page );
        
    }

    public function logout( Request $request ){
        session('g_token', '');
        $this->guard()->logout();
        $request->session()->invalidate();

        return redirect('/'); 
    }

}
