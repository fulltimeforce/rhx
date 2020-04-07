<?php

namespace App\Http\Controllers;

use App\Expertlog;
use App\Recruiterlog;
use Illuminate\Http\Request;

class ExpertlogController extends Controller
{

    public function union( Request $request ){

        $input = $request->input();
        $e = Expertlog::create($input);
        Recruiterlog::where( 'id' , $input['log_id'] )
            ->update(
                array(
                    "contact" => "filled form"
                )
            );
        return $e->id;
    }


}
