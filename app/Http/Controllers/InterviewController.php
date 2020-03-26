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
        unset($input['id']);
        $input['user_id'] = Auth::id();

        $input['date'] = date('Y-m-d' , strtotime($input['date']) );
        $input['description'] = is_null($input['description']) ? '' : $input['description'];
        $c = Interview::create($input);

        return $c;
    }

    public function update(Request $request){
        $input = $request->all();
        $id = $input['id'];
        $input['user_id'] = Auth::id();

        $input['date'] = date('Y-m-d' , strtotime($input['date']) );
        $input['description'] = is_null($input['description']) ? '' : $input['description'];
        $input['result'] = isset($input['result'])? 1 : 0;

        Interview::where('id' , $id)->update($input);
        return $input;
    }

    public function delete(Request $request){
        $input = $request->all();
        return Interview::where('id', $input['id'])->delete();
    }

    public function edit(Request $request){
        $input = $request->all();
        return Interview::find($input["id"]);
    }

}
