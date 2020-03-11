<?php

namespace App\Http\Controllers;

use App\Log;
use App\Position;
use App\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $logs = Log::where('positions' , $positionId)->get();

        return response()->json( (object) array(
            "requirements" => $requirements,
            "logs" => $logs ,
            "position" => $position
        ));
    }

    public function updateForm(Request $request){
        $input = $request->all();
        
        $logId = $input['id'];
        unset($input['id']);
        $input['user_id'] = Auth::id();
        // return $logId;
        if( empty($logId) ){
            $input['id'] = Hashids::encode(time());
            return array(
                'type' => 'create',
                'data' => Log::create($input)
            );

        }else{
            // if( isset($input["filter"]) ){

            //     if( $input["filter"] == 'yes' ){
                    
            //         $log = Log::where('id' , $logId)->first();
            //         $requirements_defaults = Requirement::whereNull('position_id')->orderBy('id', 'asc')->get();
            //         $requirements = Requirement::where('position_id' , $log->positions)->orderBy('id', 'asc')->get();
            //         $req_applicants = DB::table('requirement_applicants')->where('log_id', $logId)->orderBy('order', 'asc')->get();
            //         $a_req_applicants_ids = array();
            //         $a_req_applicants_ids_req = array();
            //         foreach ($req_applicants as $key => $req_applicant) {
            //             $a_req_applicants_ids[] = $req_applicant->id;
            //         }
            //         foreach ($req_applicants as $key => $req_applicant) {
            //             $a_req_applicants_ids_req[] = $req_applicant->requirement_id;
            //         }
            //         $a_requirements = array();
            //         $a_requirements_start = array();
            //         $a_requirements_end = array();

            //         foreach ($requirements_defaults as $key => $req_default) {
            //             if( in_array( $req_default->id , array(1,2,3) ) ){
            //                 if( count( array_filter( $a_req_applicants_ids_req , function($_id) use($req_default){  return ($_id == $req_default->id); } ) ) == 0 ){
            //                     $a_requirements_start[] = array(
            //                         'log_id' => $logId,
            //                         'requirement_id' => $req_default->id,
            //                         'order' => 0, //$order,
            //                         'description' => '',
            //                         'created_at' => date("Y-m-d H:i:s"),
            //                         'updated_at' => date("Y-m-d H:i:s")
            //                     );
            //                 }
                            
            //                 // $order++;
            //             }else{
            //                 if( count( array_filter( $a_req_applicants_ids_req , function($_id) use ($req_default){ return ($_id == $req_default->id); } ) ) == 0 ){
            //                     $a_requirements_end[] = array(
            //                         'log_id' => $logId,
            //                         'requirement_id' => $req_default->id,
            //                         'order' => 0, // count($requirements) + $order_end + 3,
            //                         'description' => '',
            //                         'created_at' => date("Y-m-d H:i:s"),
            //                         'updated_at' => date("Y-m-d H:i:s")
            //                     );
            //                 }
                            
            //                 // $order_end++;
            //             }
            //         }
            //         foreach ($requirements as $key => $requirement) {
            //             if( count( array_filter( $a_req_applicants_ids_req , function($_id) use ($requirement){  return ($_id == $requirement->id); } ) ) == 0 ){
            //                 $a_requirements[] = array(
            //                     'log_id' => $logId,
            //                     'requirement_id' => $requirement->id,
            //                     'order' => 0 , //$order,
            //                     'description' => '',
            //                     'created_at' => date("Y-m-d H:i:s"),
            //                     'updated_at' => date("Y-m-d H:i:s")
            //                 );
            //             }
            //             // $order++;

            //         }
                    
            //         $insert_req_apli = array_merge( $a_requirements_start,  $a_requirements );

            //         $insert_req_apli = array_merge( $insert_req_apli,  $a_requirements_end );
            //         // print_r( $insert_req_apli );
            //         $a_ids = array();
            //         foreach ($insert_req_apli as $key => $r) {
            //             # code...
            //             $a_ids[] = DB::table('requirement_applicants')->insertGetId($r);
            //         }

            //         if( count($a_req_applicants_ids) > 5) {
            //             array_splice( $a_req_applicants_ids , (count($a_req_applicants_ids) - 3) , 0 , $a_ids);
            //         }else{
            //             $a_req_applicants_ids = $a_ids;
            //         }
            //         $order = 1;
                    
            //         foreach ($a_req_applicants_ids as $key => $id) {
                        
            //             DB::table('requirement_applicants')->where('id' , $id)->update(array('order' => $order)); $order++;
            //         }
                    
            //     }
            // }
            Log::where('id', $logId)->update($input);
            $input["id"] = $logId;
            
            return array(
                'type' => 'update',
                'data' => (object) $input
            );
        }

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

        $logs = Log::where([['positions','=', $positionId] , ['filter' , '=' , 'yes']])->get();
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

    public function saveReqApplic( Request $request ){
        $input = $request->all();
        $data = $input["data"];

        return $data;
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
