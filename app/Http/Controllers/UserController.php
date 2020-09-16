<?php

namespace App\Http\Controllers;

use App\User;
use App\Expert;
use App\Role;
use App\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Googl;

class UserController extends Controller
{
    private $client;

    public function __construct(Request $request)
    {
       
    }

    public function index(Request $request){
        if(!Auth::check()) return redirect('login');
        $query = $request->query();
        $name = isset($query['name']) ? $query['name'] : '';
        $roles = Role::all();
        $fce_labels = Expert::getAllFceValue();
        return view('user.index',['name'=>$name,'roles'=> $roles,'fce_labels'=>$fce_labels]);
        //return view('user.index')->with('name',$name);
    }

    public function usersBootstrap(Request $request){
        if(!Auth::check()) return redirect('login');
        $query = $request->query();

        $users = User::whereNotNull('users.id')->latest("users.created_at");
        // $users = $this->filter(array() , array(), array());
        if(isset($query['name'])){
            $users->where('users.name' , 'like' , '%'.$query['name'].'%');
        }
        $users->distinct()
                ->leftJoin('roles' , 'roles.id' , '=' , 'users.role_id')
                ->select('users.*','roles.name AS role_name');

        $user =  $users->paginate( $query['rows'] );
        $rows = $user->items();

        return json_encode(array(
            "total" => count($rows),
            "totalNotFiltered" => $user->total(),
            "rows" => $rows
        ));
    }

    public function save(Request $request){
        $input = $request->all();
        if (!isset($input["name"]) ||
            !isset($input["password"]) ||
            !isset($input["email"])) {
            return array("status"=>"denied");
        }
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        return array("status"=>"success");
    }
    
    public function configuration(){

        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id != 1) return redirect('login');
        $user = User::where('id' , Auth::id() )->first();
        $fce_lvls = Config::getAllFceValue();
        $current_config = Config::first();
        return view('auth.configuration', ["user"=>$user,"fce_levels"=>$fce_lvls, "current_config"=>$current_config] );

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