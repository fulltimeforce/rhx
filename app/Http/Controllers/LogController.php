<?php

namespace App\Http\Controllers;

use App\Log;
use App\Position;
use App\Requirement;
use App\Expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\DB;


class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if(!Auth::check()) return redirect('login');
        $positions = Position::latest()->get();
        $logs = Log::all();
        $platforms = $this->platforms();
        // return $logs;
        return view('logs.index' , compact('logs'))->with(['positions' => $positions, 'platforms' => $platforms]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if(!Auth::check()) return redirect('login');
        try {
            
            $request->validate( [
                // 'file_cv' => 'mimes:pdf,doc,docx|max:2048',
                // 'email_address' => 'required|email:rfc,dns'
            ]);

            $input = $request->all();

            $input['id'] = Hashids::encode(time());
            $input['user_id'] = Auth::id();
            $expert = Log::create($input);

            return $expert;

        } catch (Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function show(Log $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function edit(Log $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Log $log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function destroy(Log $log)
    {
        //
    }

    public function position($positionId){
        
        $position = Position::where('id' , $positionId)->first();

        $requirements = Requirement::where('position_id' , $positionId)->get();

        $logs = Log::with(['expert' , 'position' ])->where('position_id' , $positionId)->get();

        return response()->json( (object) array(
            "requirements" => $requirements,
            "logs" => $logs ,
            "position" => $position
        ));
    }

    public function updateForm(Request $request){

        $input = $request->all();
        
        if( !isset( $input['id'] ) ){
            $expert_id = Hashids::encode( time() );
            $expert = array(
                "id"        => $expert_id,
                "fullname"  => $input['name'],
                "phone"     => $input['phone'],
                "user_id"   => Auth::id(),
                "user_name" => Auth::user()->name,
            );

            $create = Expert::create( $expert );
            $log = Log::create(
                array(
                    "expert_id"     => $expert_id,
                    "position_id"   => $input['position'],
                    "platform"      => $input['platform'],
                    "link"          => $input['link'],
                    "user_id"       => Auth::id()
                )
            )->id;
            return array(
                'type' => 'create',
                'data' => array(
                    "id"            => $log,
                    "name"          => $input['name'],
                    "phone"         => $input['phone'],
                    "platform"      => $input['platform'],
                    "position_id"   => $input['position'],
                    "link"          => $input['link'],
                    "expert_id"     => $expert_id,
                    "created_at"    => date("Y-m-d H:i:s")
                )
            );

        }else{
            Expert::where('id' , $input['expert_id'])->update(
                array(
                    "fullname"  => $input['name'],
                    "phone"     => $input['phone']
                )
            );

            $update = Log::where('id' , $input['id'])
                ->where('expert_id' , $input['expert_id'])
                ->update(
                array(
                    
                    "position_id"   => $input['position'],
                    "platform"      => $input['platform'],
                    "link"          => $input['link'],
                    "user_id"       => Auth::id(),
                    "updated_at"    => date("Y-m-d H:i:s")
                )
            );

            return array(
                'type' => 'update',
                'data' => (object) array(
                    "id" => $input['id'],
                    "name" => $input['name'],
                    "phone" => $input['phone'],
                    "expert_id" => $input['expert_id'],
                    "position_id"   => $input['position'],
                    "platform" => $input['platform'],
                    "link" => $input['link'],
                )
            );
        }

    }

    public function approveFilter( Request $request ){
        $input = $request->all();
        Log::where('id' , $input['id'])->update( array('filter' => $input['filter']) );
    }

    public function requirementByLog(Request $request){

        $input = $request->all();
        $positionId = $input["positionId"];

        $requirements = Requirement::where('position_id', $positionId)->get();

        $requirements_default = Requirement::whereNull('position_id')->get();
        $a_requirements = array();
        $a_requirements_start = array();
        $a_requirements_end = array();
        foreach ($requirements_default as $key => $default) {
            if( in_array( $default->id , array(1,2,3) ) ){
                $a_requirements_start[] = $default;
            }else{
                $a_requirements_end[] = $default;
            }
        }

        foreach ($requirements as $key_r => $requirement) {
            $a_requirements[] = $requirement;
        }

        $_a_requirements = array_merge($a_requirements_start , $a_requirements);
        $_a_requirements = array_merge($_a_requirements , $a_requirements_end);

        $logs = Log::with(['expert' , 'position' ])->where([['position_id','=', $positionId] , ['filter' , '=' , 'yes']])->get();
        $a_log_id = array();
        foreach ($logs as $key => $log) {
            $a_log_id[] = $log->id;
        }

        $req_logs = DB::table('requirement_applicants')->whereIn('log_id' , $a_log_id)->get();
        $a_req_logs = array();
        foreach ($req_logs as $key => $req_log) {
            $a_req_logs[] = $req_log;
        }

        $response = array();

        foreach ($_a_requirements as $key_r => $requirement) {
            $a_requirement = array(
                "id" => $requirement->id,
                "name" => $requirement->name,
                "logs" => array()
            );
            
            foreach ($logs as $key_l => $log) {
                $a_result = array_filter($a_req_logs , function($v) use($log , $requirement){
                    if( $v->log_id == $log->id && $v->requirement_id == $requirement->id ) return true;
                });
                $a_result = array_values($a_result);
                $a_requirement["logs"][] = array(
                    "applicant_id" => $log->id,
                    "description" => (count($a_result) > 0) ? $a_result[0]->description : ''
                );
            }
            $response[] = $a_requirement;
        }

        return array(
            "data" => $response,
            "logs" => $logs
        );

    }

    public function saveReqApplict( Request $request ){
        $input = $request->all();
        try {
            $data = $input["data"];
            $a_req_applict = array();
            $a_logs_id = array();
            $_data = json_decode($data);
            // print_r($_data);
            foreach ($_data as $req_k => $d) {
                foreach ($d as $log_k => $_d) {
                    $a_logs_id[] = $log_k;
                    $a_req_applict[] = array(
                        'log_id' => $log_k,
                        'requirement_id' => intval(substr($req_k , 4)),
                        'description' => $_d->description,
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    );
                }
            }
            $a_logs_id = array_unique($a_logs_id );

            $req_logs = DB::table('requirement_applicants')->whereIn('log_id' , $a_logs_id)->get();
            $a_req_logs = array();
            foreach ($req_logs as $key => $req_log) {
                $a_req_logs[] = $req_log;
            }

            foreach ($a_req_applict as $key => $_r) {
                $a_result = array_filter($a_req_logs , function($v) use($_r){
                    if( $v->log_id == $_r["log_id"] && $v->requirement_id == $_r["requirement_id"] ) return true;
                });
                $a_result = array_values($a_result);
                if( count($a_result) > 0){
                    unset($_r['created_at']);
                    DB::table('requirement_applicants')->where( function($query) use($_r){
                        $query
                            ->where('log_id', $_r["log_id"])
                            ->where('requirement_id' , $_r["requirement_id"]);
                    } )->update( $_r );
                    if( $_r["requirement_id"] == 6 && $_r["description"] != '' ){
                        
                        Log::where('id' , $_r["log_id"])->update( array("called" => "yes") );
                    }else if( $_r["requirement_id"] == 6 && $_r["description"] == '' ){
                        
                        Log::where('id' , $_r["log_id"])->update( array("called" => "no") );
                    }
                }else{
                    DB::table('requirement_applicants')->insert( $_r );
                }
            }
            return 'true';
        } catch ( Exception $e) {
            return $e->getMessage();
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
        );
    }

    public function synchronizationSigned( Request $request ) {
        
        $position = $request->query('position');
        $applicant = $request->query('applicant');
        
        return URL::temporarySignedRoute(
            'log.synchronization', now()->addDays(7) , ['position' => $position , 'applicant' => $applicant]
        );
        
    }

    public function synchronization( Request $request ){

        $q_position = $request->query('position');
        $q_applicant = $request->query('applicant');
        $position = Position::where('id' , $q_position )->first();
        if( empty($position) ) abort(404); 
        $log = Log::where('id' , $q_applicant )->first();
        if( empty($log) ) abort(404); 

        $expert = $this->getModelFormat();
        return view('experts.create' , array(
            'positionId'    => $position->id,
            'expert'        => $expert,
            'technologies'  => Expert::getTechnologies(),
            'positionName'    => $position->name,
            'signed' => true,
            'log' => $log->id
        ));
        // return view('experts.create')->with('positionId', '' )->with('expert', $expert)->with('technologies',Expert::getTechnologies());
    }

    public function takeUser( Request $request ){
        $input = $request->all();
        Log::where( 'id' , $input['id'] )->update(
            array(
                'user_id' => Auth::id()
            )
        );
    }

    private function getModelFormat(){
        $expert = new Expert();
        $a = [];
        foreach ($expert->getFillable() as $k => $f) {
            $a[$f] = "";
        }
        return (object) $a;
    }
}
