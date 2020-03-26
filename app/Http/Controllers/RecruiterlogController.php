<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Recruiterlog;
use App\Position;

class RecruiterlogController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
        
    }

    public function index(){
        $positions = Position::where('status' , 'enabled')->latest()->get();
        $logs = Recruiterlog::all();
        $platforms = $this->platforms();
        return view('logs.recruiter' , compact('logs') )->with(['positions' => $positions, 'platforms' => $platforms]);

    }

    public function saveForm( Request $request ){
        $input = $request->all();
        unset($input['id']);
        $input['user_id'] = Auth::id();
        $log = Recruiterlog::create($input);

        return array(
            'type' => 'create',
            'data' => array(
                "id"            => $log->id,
                "expert"          => $input['expert'],
                "date"         => $input['date'],
                "platform"      => $input['platform'],
                "position_id"   => $input['position_id'],
                "link"          => $input['link'],
                "created_at"    => date("Y-m-d H:i:s")
            )
        );
    }

    public function updateForm( Request $request ){
        $input = $request->all();
        $id = $input["id"];

        unset( $input["id"] );

        $log = Recruiterlog::where('id' , $id)->update($input);

        return array(
            "id" => $id,
            "expert" => isset( $input['expert'] )? $input['expert'] : '' ,
            "date" => isset( $input['date'] )? $input['date'] : '',
            "position_id"   => isset( $input['position_id'] )? $input['position_id'] : '',
            "platform" => isset( $input['platform'] )? $input['platform'] : '',
            "link" => isset( $input['link'] )? $input['link'] : '',
        );
    }

    public function deleteForm( Request $request ){
        $input = $request->all();
        $id = $input["id"];

        Recruiterlog::where('id' , $id)->delete();
    }

    private function platforms(){
        return array(
            (object) array(
                "value" => "linkedin",
                "label" => "Linkedin"
            ),
            (object) array(
                "value" => "computrabajo",
                "label" => "Computrabajo"
            ),
            (object) array(
                "value" => "indeed",
                "label" => "Indeed"
            ),
            (object) array(
                "value" => "getonboard",
                "label" => "GetOnBoard"
            ),
            (object) array(
                "value" => "bumeran",
                "label" => "Bumeran"
            ),
            (object) array(
                "value" => "catolica",
                "label" => "PUCP"
            ),
            (object) array(
                "value" => "upc",
                "label" => "UPC"
            ),
            (object) array(
                "value" => "ulima",
                "label" => "UL"
            ),
            (object) array(
                "value" => "ricardopalma",
                "label" => "URP"
            ),
            (object) array(
                "value" => "utp",
                "label" => "UTP"
            ),
            (object) array(
                "value" => "fb",
                "label" => "Facebook"
            ),
        );
    }


}
