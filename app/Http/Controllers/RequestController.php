<?php

namespace App\Http\Controllers;

use App\Request as Req;
use Illuminate\Http\Request;

class RequestController extends Controller
{
   public function index(Request $request){
   	if(!Auth::check()) return redirect('login');
      if(Auth::user()->role->id >= 3) return redirect('/expert/fce');

   	$query = $request->query();
   	$name = isset( $query['name'] )? $query['name'] : '';

   	return view('requests.index',['name'=>$name]);
   }

   public function bootstrap(Request $request){

   }
   public function edit(Request $request){

   }
   public function create(Request $request){

   }
   public function save(Request $request){
   	
   }
}
