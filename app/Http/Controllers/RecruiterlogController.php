<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Recruiterlog;
use App\Expertlog;
use App\Position;
use App\Notelog;
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

    public function index( Request $request ){
        
        if(!Auth::check()) return redirect('login');
        $positions = Position::where('status' , 'enabled')->latest()->get();
        $logs = Recruiterlog::all();
        $platforms = $this->platforms();
        $query = $request->query();
        $name = isset( $query['s'] )? $query['s'] : '';

        return view('logs.recruiter' , compact('logs') )
            ->with('s', $name )
            ->with([
                'positions' => $positions, 
                'platforms' => $platforms
            ]);

    }

    public function listlogs( Request $request ){

        $logs = Recruiterlog::with(['user', 'position'])
            ->orderBy('created_at' , 'desc');
        if( !empty($request->query('name')) ){
            $logs->where('expert' , 'like' , '%'.$request->query('name').'%');
        }    
        $logs = $logs->paginate( $request->query('limit') );

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
        $input['date'] = Carbon::createFromFormat(config('app.date_format'), $input['date'])->format('Y-m-d');
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

    public function note( Request $request ){
        $input = $request->all();
        $log_id = $input['log_id'];
        $type = $input['type'];
        
        
        $note = Notelog::where(function($q) use ($log_id,$type){
            $q
                ->where("log_id" , $log_id)
                ->where("type" , $type);
        })->first();

        return $note;
    }

    public function listnote( Request $request ){

        $input = $request->all();
        $list_notes = array();

        $logs = Expertlog::with(['log'])->where( 'expert_id' , $input['expertId'] )->get();

        foreach ($logs as $lkey => $log) {
            $recruiterlog = Recruiterlog::with(['position'])->where('id' , $log->log_id)->first();
            $_notes = (object) array(
                "position"  => $recruiterlog->position,
                "date"      => $recruiterlog->date,
                "notes"     => array(),
                "log_id" => $log->id
            );
            $notes = Notelog::where("log_id", $log->log_id)->select("type","note")->get();
            foreach ($notes as $nkey => $note) {
                $_notes->notes[] = (object) array(
                    "type" => $note->type,
                    "note" => $note->note,
                    "type_value" => $recruiterlog[$note->type]
                );
            }
            $list_notes[] = $_notes;
        }

        return $list_notes;
    }

    public function noteSave( Request $request ){
        $input = $request->all();
        $log_id = $input['log_id'];
        $type = $input['type'];
        $note = $input['note'];

        if( $type == 'commercial' || $type == 'technique' || $type == 'psychology'){
            $input['date'] = Carbon::createFromFormat(config('app.date_format'), $input['date'])->format('Y-m-d');
        }else{
            unset( $input['date'] );
        }
        
        $note = Notelog::where(function($q) use ($log_id,$type){
            $q
                ->where("log_id" , $log_id)
                ->where("type" , $type);
        })->first();
        
        if( !empty( $note ) ){
            Notelog::where('id' , $note->id)->update($input);
            return 'update';
        }else{
            Notelog::create($input);
            return 'new';
        }
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
            (object) array(
                "value" => "email",
                "label" => "Email"
            ),
            (object) array(
                "value" => "internal database",
                "label" => "Internal Database"
            ),
        );
    }


}
