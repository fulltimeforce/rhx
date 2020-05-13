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

    public function googleLogin( Request $request ){

        $cli = new Googl;
        $this->client = $cli->client();
        $google_oauthV2 = new \Google_Service_Oauth2( $this->client );
        if ($request->get('code')){
            $this->client->authenticate($request->get('code'));
            $request->session()->put('token', $this->client->getAccessToken());
        }
        if ($request->session()->get('token'))
        {
            $this->client->setAccessToken($request->session()->get('token'));
            session([
                'user' => [
                    'token' => $request->session()->get('token')
                ]
            ]);
        }
        if ($this->client->getAccessToken())
        {
            //For logged in user, get details from google using acces
            $user=User::find(1);
            $user->access_token=json_encode($request->session()->get('token'));
            $user->save();               
            dd("Successfully authenticated");
        } else
        {
            $user=User::find(1);
            $user->access_token="no";
            $user->save();   
            //For Guest user, get google login url
            $authUrl = $this->client->createAuthUrl();
            // return redirect()->to($authUrl);
        }

    }
}