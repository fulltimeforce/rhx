<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = User::where('id' , Auth::id() )->first();
            $page = '';
            switch ($user->default_page) {
                case 'resume':
                    $page = 'expert.portfolio.resume';
                    break;
                case 'log':
                    $page = 'recruiter.log';
                    break;
                case 'expert':
                    $page = 'experts.home';
                    break;
                case 'careers':
                    $page = RouteServiceProvider::HOME;
                    break;
                default:
                    $page = RouteServiceProvider::HOME;
                    break;
            }
            return redirect( $page );
        }

        return $next($request);
    }
}
