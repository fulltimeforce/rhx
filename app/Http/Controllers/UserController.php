<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Googl;

class UserController extends Controller
{
    private $client;

    public function __construct(Request $request)
    {
       
    }
    
    public function configuration(){

        if(!Auth::check()) return redirect('login');
        $user = User::where('id' , Auth::id() )->first();
        return view('auth.configuration', compact('user') );

    }

    public function changePage( Request $request ){

        $page = $request->input('page');
        User::where('id' , Auth::id() )
            ->update(
                array(
                    "default_page" => $page
                )
            );
    }

    public function changePassword( Request $request ){
        $password = $request->input('password');
        User::where('id' , Auth::id() )
            ->update(
                array(
                    "password" => bcrypt($password)
                )
            );
        $this->middleware('guest')->except('logout');
    }

}