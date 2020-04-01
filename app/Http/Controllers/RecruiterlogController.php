<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Recruiterlog;
use App\Position;
use Carbon\Carbon;

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
        return view('logs.recruiter' , compact('logs') )
            ->with([
                'positions' => $positions, 
                'platforms' => $platforms
            ]);

    }

    public function listlogs( Request $request ){

        $logs = Recruiterlog::with(['user', 'position'])->orderBy('created_at' , 'desc')->paginate( $request->query('limit') );

        return array(
            "total" => $logs->total(),
            "totalNotFiltered" => $logs->total(),
            "rows" => $logs->items()
        );
    }

    public function saveForm( Request $request ){
        $input = $request->all();
        unset($input['id']);
        $input['user_id'] = Auth::id();
        $log = Recruiterlog::create($input);

        return Recruiterlog::with(['user', 'position'])->find($log->id);
    }

    public function updateForm( Request $request ){
        $input = $request->all();
        $id = $input["id"];

        unset( $input["id"] );
        $date = '';
        if( isset( $input["date"] ) ){
            $date = $input["date"];
            $input["date"] = Carbon::createFromFormat(config('app.date_format'), $input["date"])->format('Y-m-d');
        }
        
        $log = Recruiterlog::where('id' , $id)->update($input);

        return Recruiterlog::with(['user', 'position'])->find( $id );
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
