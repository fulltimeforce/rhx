<?php

namespace App\Http\Controllers;

use App\Recruit;
use App\RecruitPosition;
use App\Position;
use App\User;

use Exception;
use Google_Client;
use Google_Service_Drive;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecruitController extends Controller
{
    public function __construct(Request $request)
    {
       
    }

    public function index(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call route parameters
        $query = $request->query();

        //check if parameter (name) exists
        $name = isset($query['name']) ? $query['name'] : '';

        //call positions and platforms
        $positions = Position::where('status' , 'enabled')->latest()->get();
        $platforms = $this->platforms();

        //return view with values ( name search, positions, platforms, recruits menu option)
        return view('recruit.index')
            ->with('s', $name )
            ->with([
                'positions' => $positions, 
                'platforms' => $platforms,
                'tab' => "postulant",
            ]);
    }

    public function outstanding(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call route parameters
        $query = $request->query();

        //check if parameter (name) exists
        $name = isset($query['name']) ? $query['name'] : '';

        //return view with values ( name search, recruits menu option)
        return view('recruit.outstanding')
            ->with('s', $name )
            ->with([
                'tab' => "outstanding",
            ]);
    }

    public function preselected(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call route parameters
        $query = $request->query();

        //check if parameter (name) exists
        $name = isset($query['name']) ? $query['name'] : '';

        //return view with values ( name search, recruits menu option)
        return view('recruit.preselected')
            ->with('s', $name )
            ->with([
                'tab' => "preselected",
            ]);
    }

    public function softskills(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call route parameters
        $query = $request->query();

        //check if parameter (name) exists
        $name = isset($query['name']) ? $query['name'] : '';

        //return view with values ( name search, recruits menu option)
        return view('recruit.softskills')
            ->with('s', $name )
            ->with([
                'tab' => "softskills",
            ]);
    }

    public function selected(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call route parameters
        $query = $request->query();

        //check if parameter (name) exists
        $name = isset($query['name']) ? $query['name'] : '';

        //return view with values ( name search, recruits menu option)
        return view('recruit.selected')
            ->with('s', $name )
            ->with([
                'tab' => "selected",
            ]);
    }

    public function recruitsBootstrap(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call route parameters
        $query = $request->query();

        //create query to call recruits
        $recruits = Recruit::whereNotNull('recruit.id');
        
        //check if $name route parameter is setted
        if(isset($query['name'])){
            $recruits->where('recruit.fullname' , 'like' , '%'.$query['name'].'%');
        }
        
        //verify tab route parameter to set recruits filters
        if($query['tab'] == "postulant"){
            $recruits->distinct()
                ->leftJoin('recruit_positions' , 'recruit_positions.recruit_id' , '=' , 'recruit.id')
                ->leftJoin('positions' , 'positions.id' , '=' , 'recruit_positions.position_id')
                ->leftJoin('users' , 'users.id' , '=' , 'recruit_positions.user_id')
                ->whereNull('recruit_positions.outstanding_report')
                ->whereNull('recruit_positions.call_report')
                ->whereNotNull('recruit_positions.recruit_id')
                ->whereNotNull('recruit_positions.position_id')
                ->select('recruit.*',
                    'positions.name AS position_name',
                    'users.name AS user_name',
                    'positions.id AS pos_id',
                    'recruit_positions.audio_report AS audio_report',
                    'recruit_positions.recruit_id AS recruit_id',
                    'recruit_positions.id AS rp_id');
        }elseif($query['tab'] == "outstanding"){
            $recruits->distinct()
                ->leftJoin('recruit_positions' , 'recruit_positions.recruit_id' , '=' , 'recruit.id')
                ->leftJoin('positions' , 'positions.id' , '=' , 'recruit_positions.position_id')
                ->leftJoin('users' , 'users.id' , '=' , 'recruit_positions.user_id')
                ->where('recruit_positions.outstanding_report', '=' , 'approve')
                ->whereNull('recruit_positions.call_report')
                ->whereNotNull('recruit_positions.recruit_id')
                ->whereNotNull('recruit_positions.position_id')
                ->select('recruit.*',
                    'positions.name AS position_name',
                    'users.name AS user_name',
                    'positions.id AS pos_id',
                    'recruit_positions.audio_report AS audio_report',
                    'recruit_positions.recruit_id AS recruit_id',
                    'recruit_positions.id AS rp_id');
        }elseif($query['tab'] == "preselected"){
            $recruits->distinct()
                ->leftJoin('recruit_positions' , 'recruit_positions.recruit_id' , '=' , 'recruit.id')
                ->leftJoin('positions' , 'positions.id' , '=' , 'recruit_positions.position_id')
                ->leftJoin('users' , 'users.id' , '=' , 'recruit_positions.user_id')
                ->where('recruit_positions.outstanding_report', '=' , 'approve')
                ->where('recruit_positions.call_report', '=' , 'approve')
                ->whereNotNull('recruit_positions.recruit_id')
                ->whereNotNull('recruit_positions.position_id')
                ->select('recruit.*',
                    'positions.name AS position_name',
                    'users.name AS user_name',
                    'positions.id AS pos_id',
                    'recruit_positions.audio_report AS audio_report',
                    'recruit_positions.recruit_id AS recruit_id',
                    'recruit_positions.id AS rp_id');
        }

        //set rows value
        $recruit =  $recruits->paginate( $query['rows'] );
        $rows = $recruit->items();

        //return view with values (name search, recruits menu option)
        return json_encode(array(
            "total" => count($rows),
            "totalNotFiltered" => $recruit->total(),
            "rows" => $rows
        ));
    }

    public function recruitsSearchBootstrap(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call route parameters
        $query = $request->query();

        //create query to call recruits
        $recruits = Recruit::whereNotNull('recruit.id');
        
        //check if $name route parameter is setted
        if(isset($query['name'])){
            $recruits->where('recruit.fullname' , 'like' , '%'.$query['name'].'%');
        }

        $recruits->distinct()
                ->leftJoin('recruit_positions' , 'recruit_positions.recruit_id' , '=' , 'recruit.id')
                ->leftJoin('positions' , 'positions.id' , '=' , 'recruit_positions.position_id')
                ->leftJoin('users' , 'users.id' , '=' , 'recruit_positions.user_id')
                ->whereNotNull('recruit_positions.recruit_id')
                ->whereNotNull('recruit_positions.position_id')
                ->where(function ($query) {
                    $query->where('recruit_positions.outstanding_report', '=', 'disapprove')
                          ->orWhere('recruit_positions.call_report', '=', 'disapprove');
                })
                ->select('recruit.*',
                    'positions.name AS position_name',
                    'users.name AS user_name',
                    'positions.id AS pos_id',
                    'recruit_positions.recruit_id AS recruit_id',
                    'recruit_positions.outstanding_report AS outstanding_report',
                    'recruit_positions.call_report AS call_report',
                    'recruit_positions.audio_report AS audio_report',
                    'recruit_positions.id AS rp_id');

        $rows = $recruits->get();

        //return view with values (name search, recruits menu option)
        return json_encode(array(
            "total" => count($rows),
            "rows" => $rows
        ));
    }

    public function getRecruit(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call route parameters
        $input = $request->all();

        //set values in variables
        $recruit_id = $input["recruit_id"];
        $rp_id      = $input["rp_id"];

        //call recruit values by id
        $recruit = Recruit::whereNotNull('recruit.id')
                ->leftJoin('recruit_positions' , 'recruit_positions.recruit_id' , '=' , 'recruit.id')
                ->leftJoin('positions' , 'positions.id' , '=' , 'recruit_positions.position_id')
                ->leftJoin('users' , 'users.id' , '=' , 'recruit_positions.user_id')
                ->where('recruit.id' , $recruit_id)
                ->where('recruit_positions.id' , $rp_id)
                ->select(
                    'recruit.id AS recruit_id',
                    'recruit.fullname AS fullname',
                    'recruit.platform AS platform',
                    'recruit.phone_number AS phone_number',
                    'recruit.email_address AS email_address',
                    'recruit.profile_link AS profile_link',
                    'recruit_positions.id AS rp_id',
                    'recruit_positions.position_id AS position_id')
                ->get();

        return json_encode(array(
            "recruit" => $recruit
        ));
    }

    public function changeRecruit(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

    
        $input = $request->all();

        //set values in variables
        $fullname = $input["fullname"];
        $position_id = $input["position_id"];
        $platform = $input["platform"];
        $phone_number = $input["phone_number"];
        $email_address = $input["email_address"];
        $profile_link = $input["profile_link"];
        $recruit_id = $input["recruit_id"];
        $rp_id = $input["rp_id"];

        if($fullname==null || $position_id==null || $platform==null || $phone_number==null || $email_address==null){ 
            return json_encode(array(
                "state" => false
            ));
        }else{
            unset( $input["position_id"] );
            unset( $input["recruit_id"] );
            unset( $input["rp_id"] );

            Recruit::where('id' , $recruit_id)->update($input);
            RecruitPosition::where('id' , $rp_id)->update(
                array( "position_id" => $position_id )
            );

            return json_encode(array(
                "state" => true
            ));
        }
               
    }

    public function editRecruit($id){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call recruit values by id
        $recruit = Recruit::where('id' , $id)->get();

        //call platforms
        $platforms = $this->platforms();

        //return view with values ( recruit values, platforms)
        return view('recruit.postulant_edit')
            ->with([
                'recruit' => $recruit,
                'platforms' => $platforms,
            ]);
    }

    public function deleteRecruit(Request $request){
        //call route parameters
        $input = $request->all();

        //set values in variables
        $recruit_id = $input["recruit_id"];
        $position_id = $input["position_id"];

        //delete recruit_positions row by: recruit_id and position_id
        RecruitPosition::where('recruit_id' , $recruit_id)->where('position_id' , $position_id)->delete();
    }

    public function updateRecruit(Request $request, $id){
        //set form validators
        $validator = $request->validate( [
            'fullname'              => 'required',
            'identification_number' => 'required',
            'platform'              => 'required',
            'phone_number'          => 'required|numeric',
            'email_address'         => 'required',
            'file_path_update'      => 'mimes:pdf,doc,docx|max:2048',
        ]);

        //verify if validators failed
        if( !is_array($validator) ){
            if ($validator->fails()) {
                return back()
                            ->withErrors($validator)
                            ->withInput();
            }
        }

        try {
            //set all file variables we need
            $file = $request->file("file_path_update");
            $destinationPath = 'uploads/cv';
            $newNameFile = '';

            ////call form parameters
            $input = $request->all();

            //check if file exists in order to storage on the server
            if( $file ){
                $_fileName = "cv-".date("Y-m-d")."-".time().".".$file->getClientOriginalExtension();
                $newNameFile = $destinationPath."/" . $_fileName;
                $path = $request->file("file_path_update")->store("cv" , "s3");
                $path_s3 = Storage::disk("s3")->url($path);
                Storage::disk("s3")->put($path , file_get_contents( $request->file("file_path_update") ) , 'public' );
                Storage::delete( $path );
                $input["file_path"] = $path_s3;
            }

            //unset values _token and file_path_update from input
            unset( $input["_token"] );
            unset( $input["file_path_update"] );
            
            //check if exists another recruit with same identification number
            if( Recruit::where("identification_number" , $input['identification_number'])->count() >=1 ){
                //call recruit with that identification number
                $recruit = Recruit::where("identification_number" , $input['identification_number'] )->get();

                //verify if it´s the one we are editing
                if($recruit[0]->id == $id){
                    //edit the existing recruit
                    Recruit::where('id' , $id)->update($input);
                }else{
                    //return with error message, that identification number already exists in other recruit
                    return redirect()->route('recruit.menu')
                            ->with('error', 'Identification number already exists.');
                }
            }else{
                //if not, then we update the recruit
                Recruit::where('id' , $id)->update($input);
            }
            

            if(Auth::check()){
                //return with success message
                return redirect()->route('recruit.menu')
                            ->with('success', 'Postulant updated successfully.');
            }else{
                //return with error message
                return redirect()->route('recruit.menu')
                            ->with('error', 'Nedd to Log In.');
            }

        } catch (Exception $exception) {
            //return with error
            return back()->withErrors($exception->getMessage())->withInput();
        }
    }

    public function isSlug($slug){
        //call positions
        $positions = Position::all();

        //set variables we need
        $is = false;
        $p = array();

        //go through the array
        foreach ($positions as $key => $p) {
            if($slug === $p->slug){
                $position = $p;$is = true;break;
            }
        }

        //check if position exists
        if($is){
            //if exists, return with values (position, expert, technology)
            $expert = $this->getModelFormat();
            return view('recruit.form' )->with('position', $position )->with('expert', $expert )->with('technologies',Expert::getTechnologies());
        }else{
            //return with error message
            abort(404);
        }
    }

    public function save(Request $request){
        //set form validators
        $validator = $request->validate( [
            'fullname'              => 'required',
            'identification_number' => 'required',
            'position_id'           => 'required',
            'platform'              => 'required',
            'phone_number'          => 'required|numeric',
            'email_address'         => 'required',
            'file_path'             => 'mimes:pdf,doc,docx|max:2048',
        ]);
        
        //verify if validators failed
        if( !is_array($validator) ){
            if ($validator->fails()) {
                return back()
                            ->withErrors($validator)
                            ->withInput();
            }
        }    
        
        try {
            //set all file variables we need
            $file = $request->file("file_path");
            $destinationPath = 'uploads/cv';
            $input = $request->all();
            $recruit;
            $newNameFile = '';
            $input["file_path"] = '';
            $isCreated = true;

            //check if file exists in order to storage on the server
            if( $file ){
                $_fileName = "cv-".date("Y-m-d")."-".time().".".$file->getClientOriginalExtension();
                $newNameFile = $destinationPath."/" . $_fileName;
                $path = $request->file("file_path")->store("cv" , "s3");
                $path_s3 = Storage::disk("s3")->url($path);
                Storage::disk("s3")->put($path , file_get_contents( $request->file("file_path") ) , 'public' );
                Storage::delete( $path );
                $input["file_path"] = $path_s3;
            }

            //create hashid for new user
            $input['id'] = Hashids::encode(time());

            //unset values _token and file_path_update from input
            unset( $input["_token"] );
            unset( $input["position_id"] );

            //check if the recruit already exists
            if( Recruit::where("identification_number" , $input['identification_number'])->count() > 0 ){
                //if exists, we call the recruit and set isCreated true
                $recruit = Recruit::where("identification_number" , $input['identification_number'] )->first();
                $isCreated = true;
            }else{
                //if not, we create the recruit and set isCreated false
                Recruit::create($input);
                $isCreated = false;
            }

            //check isCreated value
            if($isCreated){
                //check if recruit already applied for that position
                if(RecruitPosition::where("recruit_id" , $recruit['id'])->where('position_id', $request->input('position_id'))->count() > 0){
                    //if already applied, return with error
                    return redirect()->route('home')
                            ->with('error', 'Already applied for that position.');
                }else{
                    //if not, create recruit_positions row
                    RecruitPosition::create(
                        array(
                            "recruit_id"         =>  $recruit['id'],
                            "position_id"        =>  $request->input('position_id'),
                            "user_id"            =>  Auth::id(),
                        )
                    );
                }
            }else{
                //if not, we create the applyment directly
                RecruitPosition::create(
                    array(
                        "recruit_id"         =>  $input['id'],
                        "position_id"        =>  $request->input('position_id'),
                        "user_id"            =>  Auth::id(),
                    )
                );
            }
            
            if(Auth::check()){
                //return with success message
                return redirect()->route('recruit.menu')
                            ->with('success', 'Postulant applied successfully.');
            }else{
                //return with error message
                return redirect()->route('experts.confirmation');
            }

        } catch (Exception $exception) {
            //return with error
            return back()->withErrors($exception->getMessage())->withInput();
        }

    }

    public function saveRecruit(Request $request){

        $validator = $request->validate( [
            'fullname'              => 'required',
            'position_id'           => 'required',
            'platform'              => 'required',
            'phone_number'          => 'required|numeric',
            'email_address'         => 'required',
        ]);
        
        if( !is_array($validator) ){
            if ($validator->fails()) {
                return back()
                            ->withErrors($validator)
                            ->withInput();
            }
        }    
        
        try {
            $input = $request->all();
            $recruit;
            $isCreated = true;

            $input['id'] = Hashids::encode(time());
            unset( $input["_token"] );
            unset( $input["position_id"] );

            Recruit::create($input);

            RecruitPosition::create(
                array(
                    "recruit_id"         =>  $input['id'],
                    "position_id"        =>  $request->input('position_id'),
                    "user_id"            =>  Auth::id(),
                )
            );
            
            if(Auth::check()){
                return redirect()->route('recruit.menu')
                            ->with('success', 'Postulant applied successfully.');
            }else{
                return redirect()->route('recruit.menu')
                            ->with('error', 'Need to Log In.');
            }

        } catch (Exception $exception) {
            return back()->withErrors($exception->getMessage())->withInput();
        }
    }

    public function uploadAudio( Request $request ){

        $input = $request->all();

        $recruit_id = $input['recruit_id'];
        $position_id = $input['position_id'];

        $file = $request->file("file");
        
        $destinationPath = 'uploads/audio';
        
        $input = $request->all();
        
        $newNameFile = '';

        if( $file ){
            $_fileName = "audio-".date("Y-m-d")."-".time().".".$file->getClientOriginalExtension();
            $newNameFile = $destinationPath."/" . $_fileName;
            
            
            // $file->move( $destinationPath, $newNameFile );
            $path = $request->file("file")->store("audio" , "s3");
            
            $path_s3 = Storage::disk("s3")->url($path);

            Storage::disk("s3")->put($path , file_get_contents( $request->file("file") ) , 'public' );

            Storage::delete( $path );

            Recruit::where('id' , $recruit_id)->update(
                array( "audio_path" => $path_s3 )
            );

            return array(
                "file" => $path_s3,
            );
        }
        
    }

    public function deleteAudio( Request $request ){
        $input = $request->all();
        $recruit_id = $input['recruit_id'];
        $position_id = $input['position_id'];
        Recruit::where('id' , $recruit_id)->update(
            array( "audio_path" => null )
        );
    }

    public function uploadCV( Request $request ){

        $input = $request->all();

        $rp_id = $input['rp_id'];
        $position_id = $input['position_id'];

        $file = $request->file("file");
        
        $destinationPath = 'uploads/cv';
        
        $input = $request->all();
        
        $newNameFile = '';

        if( $file ){
            $_fileName = "cv-".date("Y-m-d")."-".time().".".$file->getClientOriginalExtension();
            $newNameFile = $destinationPath."/" . $_fileName;
            
            
            // $file->move( $destinationPath, $newNameFile );
            $path = $request->file("file")->store("cv" , "s3");
            
            $path_s3 = Storage::disk("s3")->url($path);

            Storage::disk("s3")->put($path , file_get_contents( $request->file("file") ) , 'public' );

            Storage::delete( $path );

            Recruit::where('id' , $position_id)->update(
                array( "file_path" => $path_s3 )
            );

            return array(
                "file" => $path_s3,
            );
        }
        
    }

    public function deleteCV( Request $request ){
        $input = $request->all();
        $rp_id = $input['rp_id'];
        $position_id = $input['position_id'];
        Recruit::where('id' , $position_id)->update(
            array( "file_path" => null )
        );
    }

    public function recruitsEvaluateOutstanding(Request $request){
        //call route parameters
        $input = $request->all();

        //set all parameters in variables
        $id          = $input['id'];
        $positionid  = $input['positionid'];
        $outstanding = $input['outstanding'];

        //call recruit by id
        $recruit = Recruit::where('id' , $id)->first();
        
        //verify it recuir have CV FILE or PROFILE LINK (1 at least)
        if($recruit['file_path'] == null && $recruit['profile_link'] == null){
            //if not, return with error message
            redirect()->route('recruit.menu')
                            ->with('error', 'Need to have "PROFILE LINK" or "CV FILE".');
        }else{
            //if exists 1 at least, approve call evaluation
            RecruitPosition::where('recruit_id' , $id)->where('position_id' , $positionid)->update(
                array("outstanding_report"=>$outstanding)
            );
        }
    }

    public function recruitsEvaluateCall(Request $request){
        //call route parameters
        $input = $request->all();

        //set all parameters in variables
        $id          = $input['id'];
        $positionid  = $input['positionid'];
        $phonecall   = $input['phonecall'];

        //call recruit by id
        $recruit = Recruit::where('id' , $id)->first();
        
        //verify it recuir have CV FILE or PROFILE LINK (1 at least)
        if($recruit['file_path'] == null && $recruit['profile_link'] == null){
            //if not, return with error message
            redirect()->route('recruit.outstanding')
                            ->with('error', 'Need to have "PROFILE LINK" or "CV FILE".');
        }else{
            //if exists 1 at least, approve call evaluation
            RecruitPosition::where('recruit_id' , $id)->where('position_id' , $positionid)->update(
                array("call_report"=>$phonecall)
            );
        }
    }

    public function bulkActions(Request $request){
        //call route parameters
        $input = $request->all();

        $action           = $input['action'];
        $rp_id_array      = $input['rp_id_array'];
        $recruit_id_array = $input['recruit_id_array'];
        $tab              = $input['tab'];

        $count_reject = 0;
        $count_accept = 0;

        $name_array = '';
        $accepted_array = [];

        foreach ($recruit_id_array as $key => $recruit_id) {
            if($action == 'trash'){
                array_push($accepted_array, $rp_id_array[$key]);
                $count_accept++;
            }else{
                if(Recruit::where('id' , $recruit_id)->whereNull('profile_link')->whereNull('file_path')->count() > 0){
                    $recruit = Recruit::where('id' , $recruit_id)->first();
                    $name_array .= "<br> &nbsp; &#8226; " . $recruit['fullname'];
                    $count_reject++;
                }else{
                    array_push($accepted_array, $rp_id_array[$key]);
                    $count_accept++;
                }
            }
        }
        
        if($count_accept>0){
            switch ($action) {
                case 'approve':
                    $this->bulkActionApprove($accepted_array, $tab);
                    break;
                
                case 'disapprove':
                    $this->bulkActionDisapprove($accepted_array, $tab);
                    break;

                case 'trash':
                    $this->bulkActionTrash($accepted_array);
                    break;
            }
        }

        if($count_reject>0){
            redirect()->back()
                    ->with('error', 'There ' . (($count_reject > 1)?'are '.$count_reject.' postulants':'is 1 postulant') . ' without "PROFILE LINK" and "CV FILE":' . "\r\n" . $name_array);
        }else{
            redirect()->back()
                    ->with('success', 'Great!!! ' . (($count_accept > 1)?$count_accept.' postulants were':'1 postulant was') . ' modified successfully.');
        }
    }

    public function bulkActionApprove($rp_id_array, $tab){
        foreach ($rp_id_array as $rp_id) {
            switch ($tab) {
                case 'postulant':
                    RecruitPosition::where('id' , $rp_id)->update(array("outstanding_report"=>"approve"));
                    break;
                case 'outstanding':
                    RecruitPosition::where('id' , $rp_id)->update(array("call_report"=>"approve"));
                    break;
                case 'preselected':
                    # code...
                    break;
            }   
        }
    }

    public function bulkActionDisapprove($rp_id_array, $tab){
        foreach ($rp_id_array as $rp_id) {
            switch ($tab) {
                case 'postulant':
                    RecruitPosition::where('id' , $rp_id)->update(array("outstanding_report"=>"disapprove"));
                    break;
                case 'outstanding':
                    RecruitPosition::where('id' , $rp_id)->update(array("call_report"=>"disapprove"));
                    break;
                case 'preselected':
                    # code...
                    break;
            }   
        }
    }

    public function bulkActionTrash($rp_id_array){
        foreach ($rp_id_array as $rp_id) {
            RecruitPosition::where('id' , $rp_id)->delete();
        }
    }

    public function recruitTechSigned($recruitId) {
        $query = array(
            'recruitId' => $recruitId 
        );
        if( Recruit::where('id' , $recruitId)->count() > 0 ){
            $query['position'] = time();
        }

        return URL::temporarySignedRoute(
            'recruit.tech', now()->addDays(7), $query
        );
    }

    public function recruitTech(Request $request , $recruitId){
        if(!Auth::check() && !$request->hasValidSignature()) return redirect('login');

        $recruit = Recruit::find($recruitId);
        $position = 0;
        if( !is_null( $request->query('position') ) ){
            $position = 1;
        }

        return view('recruit.tech_qtn')
            ->with('recruit', $recruit)
            ->with('position', $position)
            ->with('technologies',Recruit::getTechnologies());
    }

    public function saveRecruitTechQtn(Request $request){
        $validator = $request->validate( [
            'identification_number' => 'required',
        ]);
        
        if( !is_array($validator) ){
            if ($validator->fails()) {
                return back()
                            ->withErrors($validator)
                            ->withInput();
            }
        }

        try{
            //call route parameters
            $input = $request->all();
            
            $recruit_id = $input["recruit_id"];

            unset( $input["_token"] );
            unset( $input["recruit_id"] );
            $input['tech_qtn'] = 'filled';

            $recruit = Recruit::where('id' , $recruit_id)->first();

            if($recruit['tech_qtn'] == 'filled'){
                return redirect()->back()
                            ->with('error', 'Postulant already filled the Tech Questionary.');
            }

            Recruit::where('id' , $recruit_id)->update($input);

            if(Auth::check()){
                //return with success message
                return redirect()->route('recruit.menu')
                            ->with('success', 'Postulant filled the Tech Questionary successfully.');
            }else{
                //return with error message
                return redirect()->route('experts.confirmation');
            }

        } catch (Exception $exception) {
            //return with error
            return back()->withErrors($exception->getMessage())->withInput();
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
