<?php

namespace App\Http\Controllers;

use App\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    /** 
     * Show the interviews according to the expert
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    
    */
    public function expert( Request $request ){

        $input = $request->all();

        return Interview::where('expert_id' , $input['expertId'])->get();

    }

    /** 
     * Create an interview 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    
    */

    public function save(Request $request){

        $input = $request->all();

        if( isset( $input['result'] ) ) $input['result'] = 1;
        $input['user_id'] = Auth::id();

        $input['date'] = date('Y-m-d' , strtotime($input['date']) );
        $input['description'] = is_null($input['description']) ? '' : $input['description'];
        $c = Interview::create($input);

        return $c;
    }

    public function delete(Request $request){
        $input = $request->all();
        return Interview::where('id', $input['id'])->delete();
    }

}
