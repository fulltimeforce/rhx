<?php

namespace App\Http\Controllers;

use App\Recruiterlog;

use App\Recruit;
use App\RecruitPosition;
use App\Expert;
use App\Position;
use App\Portfolio;
use App\SearchHistory;
use App\Notification;
use App\User;
use App\Config;

use App\Quiz;
use Mail;
use MultiMail;
use Exception;
use Google_Client;
use Google_Service_Drive;

use Carbon\Carbon;
use App\Mail\ravenEmail;

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

    //==============================================================================
    //========================RECRUITMENT MENU METHODS=============================
    //==============================================================================
    public function index(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call route parameters
        $query = $request->query();

        //check if parameters (name) exists
        $name = isset($query['name']) ? $query['name'] : '';
        $_user = isset($query['user']) ? $query['user'] : '';
        $_hand = isset($query['hand']) ? $query['hand'] : "true";
        $_auto = isset($query['auto']) ? $query['auto'] : "true";

        //call positions, users and platforms
        $positions = Position::whereNull('position_type')->where('status' , 'enabled')->latest()->get();
        $users = User::whereIn('role_id' , [1, 2])->where('status', 'ENABLED')->get();
        $platforms = $this->platforms();

        //badge new auto postulants
        $badge_qty = RecruitPosition::whereNull('user_id')->get();
                            
        //return view with values ( name search, positions, platforms, recruits menu option)
        return view('recruit.index')
            ->with('search_name', $name )
            ->with([
                'positions' => $positions, 
                'platforms' => $platforms,
                'users' => $users,
                'tab' => "postulant",
                'badge_qty' => count($badge_qty),
                '_user' => $_user,
                '_hand' => $_hand,
                '_auto' => $_auto,
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

        //badge new auto postulants
        $badge_qty = RecruitPosition::whereNull('user_id')->get();

        //return view with values ( name search, recruits menu option)
        return view('recruit.outstanding')
            ->with('search_name', $name )
            ->with([
                'tab' => "outstanding",
                'badge_qty' => count($badge_qty),
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

        //badge new auto postulants
        $badge_qty = RecruitPosition::whereNull('user_id')->get();

        //return view with values ( name search, recruits menu option)
        return view('recruit.preselected')
            ->with('search_name', $name )
            ->with([
                'tab' => "preselected",
                'badge_qty' => count($badge_qty),
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

        //badge new auto postulants
        $badge_qty = RecruitPosition::whereNull('user_id')->get();

        //return view with values ( name search, recruits menu option)
        return view('recruit.softskills')
            ->with('search_name', $name )
            ->with([
                'tab' => "softskills",
                'badge_qty' => count($badge_qty),
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

        //badge new auto postulants
        $badge_qty = RecruitPosition::whereNull('user_id')->get();

        //return view with values ( name search, recruits menu option)
        return view('recruit.selected')
            ->with('search_name', $name )
            ->with([
                'tab' => "selected",
                'badge_qty' => count($badge_qty),
            ]);
    }

    //==============================================================================
    //======================== TECH MENU METHODS=============================
    //==============================================================================

    public function listTech(Request $request){
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id != 1) return redirect('login');
        $query = $request->query();
        $name = isset($query['name']) ? $query['name'] : '';

        return view('tech.index')->with('name',$name);
    }

    //==============================================================================
    //==========================TABLE BOOTSTRAP METHODS=============================
    //==============================================================================
    public function recruitsBootstrap(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call route parameters
        $query = $request->query();

        //get FCE lower grade array
        $fce_lower = Config::first()->fce_lower_overall;
        
        if($fce_lower){
            $fces = Config::getListFceSuperior($fce_lower);
        }else{
            $fces = [''];
        }

        //get filter values
        $filters['user'] = isset($query['user'])?$query['user']:null;
        $filters['auto'] = isset($query['auto'])?$query['auto']:null;
        $filters['hand'] = isset($query['hand'])?$query['hand']:null;
        
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
                //====================================================================================
                ->where(function ($query) use ($filters) {
                    if($filters['user']){
                        $query->where('recruit_positions.user_id', $filters['user']);
                    }else{
                        if($filters['auto']=="false" && $filters['hand']=="true"){
                            $query->whereNotNull('recruit_positions.user_id');
                        }else if($filters['auto']=="true" && $filters['hand']=="false"){
                            $query->whereNull('recruit_positions.user_id');
                        }
                    }
                })
                //====================================================================================
                ->whereNull('recruit_positions.outstanding_report')
                ->whereNull('recruit_positions.call_report')
                ->whereNull('recruit_positions.audio_report')
                ->whereNull('recruit_positions.soft_report')
                ->whereNotNull('recruit_positions.recruit_id')
                ->whereNotNull('recruit_positions.position_id')
                ->orderByDesc('recruit_positions.created_at')
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
                ->whereNull('recruit_positions.audio_report')
                ->whereNull('recruit_positions.soft_report')
                ->whereNotNull('recruit_positions.recruit_id')
                ->whereNotNull('recruit_positions.position_id')
                ->orderByDesc('recruit_positions.outstanding_ev_date')
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
                ->whereNull('recruit_positions.audio_report')
                ->whereNull('recruit_positions.soft_report')
                ->whereNotNull('recruit_positions.recruit_id')
                ->whereNotNull('recruit_positions.position_id')
                ->orderByDesc('recruit_positions.call_ev_date')
                ->select('recruit.*',
                    'positions.name AS position_name',
                    'users.name AS user_name',
                    'positions.id AS pos_id',
                    'recruit_positions.audio_report AS audio_report',
                    'recruit_positions.recruit_id AS recruit_id',
                    'recruit_positions.id AS rp_id');
        }elseif($query['tab'] == "softskills"){
            $recruits->distinct()
                ->leftJoin('recruit_positions' , 'recruit_positions.recruit_id' , '=' , 'recruit.id')
                ->leftJoin('positions' , 'positions.id' , '=' , 'recruit_positions.position_id')
                ->leftJoin('users' , 'users.id' , '=' , 'recruit_positions.user_id')
                ->where('recruit_positions.outstanding_report', '=' , 'approve')
                ->where('recruit_positions.call_report', '=' , 'approve')
                ->where('recruit_positions.audio_report', '=' , 'approve')
                //====================================================================================
                //====================================================================================
                ->whereNull('recruit_positions.soft_report')
                ->whereNotNull('recruit_positions.recruit_id')
                ->whereNotNull('recruit_positions.position_id')
                ->orderByDesc('recruit_positions.audio_ev_date')
                ->select('recruit.*',
                    'positions.name AS position_name',
                    'users.name AS user_name',
                    'positions.id AS pos_id',
                    'recruit_positions.audio_report AS audio_report',
                    'recruit_positions.recruit_id AS recruit_id',
                    'recruit_positions.id AS rp_id');
        }elseif($query['tab'] == "selected"){
            $recruits->distinct()
                ->leftJoin('recruit_positions' , 'recruit_positions.recruit_id' , '=' , 'recruit.id')
                ->leftJoin('positions' , 'positions.id' , '=' , 'recruit_positions.position_id')
                ->leftJoin('users' , 'users.id' , '=' , 'recruit_positions.user_id')
                ->where('recruit_positions.outstanding_report', '=' , 'approve')
                ->where('recruit_positions.call_report', '=' , 'approve')
                ->where('recruit_positions.audio_report', '=' , 'approve')
                ->where('recruit_positions.soft_report', '=' , 'approve')
                //====================================================================================
                //====================================================================================
                ->whereNotNull('recruit_positions.recruit_id')
                ->whereNotNull('recruit_positions.position_id')
                ->orderByDesc('recruit_positions.soft_ev_date')
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

        //return view with values (total returned, total, rows values)
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

        //get FCE lower grade array
        $fce_lower = Config::first()->fce_lower_overall;
        
        if($fce_lower){
            $fces = Config::getListFceInferior($fce_lower);
        }else{
            $fces = [''];
        }

        //create query to call recruits
        $recruits = Recruit::whereNotNull('recruit.id');
        
        //check if $name route parameter is setted
        if(isset($query['name'])){
            $recruits->where('recruit.fullname' , 'like' , '%'.$query['name'].'%');
        }

        //set query conditions
        $recruits->distinct()
                ->leftJoin('recruit_positions' , 'recruit_positions.recruit_id' , '=' , 'recruit.id')
                ->leftJoin('positions' , 'positions.id' , '=' , 'recruit_positions.position_id')
                ->leftJoin('users' , 'users.id' , '=' , 'recruit_positions.user_id')
                ->whereNotNull('recruit_positions.recruit_id')
                ->whereNotNull('recruit_positions.position_id')
                ->select('recruit.*',
                    'positions.name AS position_name',
                    'users.name AS user_name',
                    'positions.id AS pos_id',
                    'recruit_positions.recruit_id AS recruit_id',
                    'recruit_positions.outstanding_report AS outstanding_report',
                    'recruit_positions.call_report AS call_report',
                    'recruit_positions.audio_report AS audio_report',
                    'recruit_positions.soft_report AS soft_report',
                    'recruit_positions.id AS rp_id')
                ->orderByDesc('recruit.created_at');

        $rows = $recruits->get();

        foreach ($rows as $key => $value) {
            $reached_value = '';
            $status = 'disapprove';
            if($value['outstanding_report'] == $status || $value['call_report'] == $status ||
            $value['audio_report'] == $status || $value['soft_report'] == $status){
                $rows[$key]['status'] = $status;
            }else{
                $status = 'approve';
                $rows[$key]['status'] = $status;
            }
            
            if($value['outstanding_report'] == $status){
                switch ($status){
                    case 'disapprove':
                        $reached_value = 'Postulantes';
                        break;
                    case 'approve':
                        $reached_value = 'Perfiles Destacados';
                        break;
                    default:
                        $reached_value = 'Postulantes';
                        break;
                }
                $rows[$key]['reached'] = $reached_value;
            }else if($value['outstanding_report'] == null){
                $rows[$key]['reached'] = 'Postulantes';
            }

            if($value['call_report'] == $status){
                switch ($status){
                    case 'disapprove':
                        $reached_value = 'Perfiles Destacados';
                        break;
                    case 'approve':
                        $reached_value = 'Pre-Seleccionados';
                        break;
                }
                $rows[$key]['reached'] = $reached_value;
            }

            if($value['audio_report'] == $status){
                switch ($status){
                    case 'disapprove':
                        $reached_value = 'Pre-Seleccionados';
                        break;
                    case 'approve':
                        $reached_value = 'Para Evaluar';
                        break;
                }
                $rows[$key]['reached'] = $reached_value;
            }

            if($value['soft_report'] == $status){
                switch ($status){
                    case 'disapprove':
                        $reached_value = 'Para Evaluar';
                        break;
                    case 'approve':
                        $reached_value = 'Seleccionados';
                        break;
                }
                $rows[$key]['reached'] = $reached_value;
            }
        }

        //return view with values (name search, recruits menu option)
        return json_encode(array(
            "total" => count($rows),
            "rows" => $rows
        ));
    }

    public function recruitsRegisteredBootstrap(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //create query to call recruits
        $recruits = Recruit::whereNotNull('recruit.id')
                           ->whereNotNull('recruit.identification_number');

        $rows = $recruits->get();

        //call positions and platforms
        $positions = Position::whereNull('position_type')->where('status' , 'enabled')->latest()->get();

        //return view with values (name search, recruits menu option)
        return json_encode(array(
            "total" => count($rows),
            "rows" => $rows,
            "positions" => $positions,
        ));
    }

    public function recruitsTechBootstrap(Request $request){
        $query = $request->query();
        $fce_lower = Config::first()->fce_lower_overall;
        
        if($fce_lower){
            $fces = Config::getListFceSuperior($fce_lower);
        }else{
            $fces = [''];
        }
        $recruits = Recruit::where('fullname' , 'like' , '%'.$query['name'].'%');

        $recruits->whereIn('fce_overall' , $fces);
        $recruits->orderBy("fce_total","DESC");
         
        $expert =  $recruits->paginate( $query['rows'] );
        $rows = $expert->items();

        return json_encode(array(
            "total" => count($rows),
            "totalNotFiltered" => $expert->total(),
            "rows" => $rows
        ));
    }

    //==============================================================================
    //==TABLE FUNCTIONALITY METHDOS: EDIT, DELETE, SAVE, EVALUATE CRITERIA, APPLY===
    //==============================================================================
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

        //call route parameters
        $input = $request->all();

        //set values in variables
        $fullname = $input["fullname"];
        $position_id = $input["position_id"];
        $platform = $input["platform"];
        $phone_number = ($input["phone_number"])?$input["phone_number"]:null;
        $email_address = ($input["email_address"])?$input["email_address"]:null;
        $profile_link = ($input["profile_link"])?$input["profile_link"]:null;
        $recruit_id = $input["recruit_id"];
        $rp_id = $input["rp_id"];

        //check if there is a null value in required values
        if($fullname==null || $position_id==null || $platform==null){ 
            return json_encode(array(
                "state" => false
            ));
        }else{
            //unset useless values
            unset( $input["position_id"] );
            unset( $input["recruit_id"] );
            unset( $input["rp_id"] );

            //update recruit and and postulant position
            Recruit::where('id' , $recruit_id)->update($input);
            RecruitPosition::where('id' , $rp_id)->update(
                array( "position_id" => $position_id )
            );

            //if everything is ok return true state
            return json_encode(array(
                "state" => true
            ));
        }
               
    }

    public function deleteRecruit(Request $request){
        //call route parameters
        $input = $request->all();

        //set values in variables
        $recruit_id = $input["recruit_id"];
        $position_id = $input["position_id"];
        $rp_id = $input["rp_id"];

        //delete recruit_positions row by: recruit_id and position_id
        RecruitPosition::where('recruit_id' , $recruit_id)->where('position_id' , $position_id)->where('id' , $rp_id)->delete();
    }

    public function saveRecruit(Request $request){

        //set form validators
        $validator = $request->validate( [
            'fullname'              => 'required',
            'position_id'           => 'required',
            'platform'              => 'required',
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
            $recruit;
            $isCreated = true;
            
            //call route parameters
            $input = $request->all();

            //unset useless values && set hashid
            $input['id'] = Hashids::encode(time());
            $input['phone_number'] = ($input['phone_number'])?preg_replace('/[^0-9.]+/', '', $input['phone_number']):null;
            unset( $input["_token"] );
            unset( $input["position_id"] );

            //create recruit with input values
            Recruit::create($input);

            //create recruit position with: recruit id, position id, user id
            RecruitPosition::create(
                array(
                    "recruit_id"         =>  $input['id'],
                    "position_id"        =>  $request->input('position_id'),
                    "user_id"            =>  Auth::id(),
                )
            );
            
            if(Auth::check()){
                //return view with message (success)
                return redirect()->route('recruit.menu')
                            ->with('success', 'Postulant applied successfully.');
            }else{
                //if not logged, return view with message (error)
                return redirect()->route('recruit.menu')
                            ->with('error', 'Need to Log In.');
            }

        } catch (Exception $exception) {
            return back()->withErrors($exception->getMessage())->withInput();
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

    public function updateRecruit(Request $request, $id){
        //set form validators
        $validator = $request->validate( [
            'fullname'              => 'required',
            'identification_number' => 'required',
            'platform'              => 'required',
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

            //call form parameters
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

    public function evaluateCriteria( Request $request ){
        $input = $request->all();

        $recruit_id = $input['recruit_id'];
        $positionid = $input['positionid'];
        $crit = $input['crit'];
        $option = $input['option'];
        if($option == ''){$option = null;}

        Recruit::where('id' , $recruit_id)->update(
            array( "crit_".$crit => $option )
        );
    }

    public function applyRecruit(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call route parameters
        $input = $request->all();

        //set values in variables
        $recruit_id  = $input["id"];
        $position_id = $input["positionId"];
        $user_id     = Auth::id();

        RecruitPosition::create(
            array(
                "recruit_id"         =>  $recruit_id,
                "position_id"        =>  $position_id,
                "user_id"            =>  $user_id
            )
        );

        //return view with message (success)
        redirect()->back()
                ->with('success', 'Postulant APPLIED successfully');
    }

    //==============================================================================
    //==================RECLUTE A POSTULANT VIA WORKAT LINK METHODS=================
    //==============================================================================
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
            return view('recruit.form' )->with('position', $position )->with('technologies',Recruit::getTechnologies());
        }else{
            //return with error message
            abort(404);
        }
    }

    public function save(Request $request){
        //set form validators
        $validator = $request->validate( [
            'fullname'              => 'required',
            'position_id'           => 'required',
            'platform'              => 'required',
            'phone_number'          => 'required|numeric',
            'email_address'         => 'required',
            'identification_number' => 'required',
            'file_path'             => 'mimes:pdf,doc,docx|max:2048',
        ]);
        
        //verify if validators failed
        if( !is_array($validator) ){
            if ($validator->fails()) {
                return back()
                            ->withErrors($validator)
                            ->withInput($request->input());
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

            $input['file_path'] = empty( $input['file_path'] )? null : $input['file_path'] ;

            //create hashid for new user
            $input['id'] = Hashids::encode(time());

            //unset values _token and file_path_update from input
            unset( $input["_token"] );
            unset( $input["position_id"] );

            //set variable is created false
            $isCreated = false;
            $recruit = [];

            //verify is Postulant with that IDENTIFICATION NUMBER already exists
            if(Recruit::where('identification_number' , $input['identification_number'])->count() > 0){
                //If does, set variable is created true, and get Postulant information
                $isCreated = true;
                $recruit = Recruit::where("identification_number" , $input['identification_number'] )->first();
            }

            //If is created, we only create the Postulation to that position
            if($isCreated){
                RecruitPosition::create(
                    array(
                        "recruit_id"         =>  $recruit['id'],
                        "position_id"        =>  $request->input('position_id'),
                        "user_id"            =>  Auth::id(),
                    )
                );
            //If dont, we create the Postulant and the Postulation
            }else{
                $recruitInput = [
                    "id"                    => $input['id'],
                    "fullname"              => $input["fullname"],
                    "email_address"         => $input["email_address"],
                    "phone_number"          => $input["phone_number"],
                    "identification_number" => $input["identification_number"],
                    "file_path"             => $input['file_path'],
                    "profile_link"          => $input['profile_link']
                ];
                $recruit = Recruit::create($recruitInput);

                $recruitPos = RecruitPosition::create(
                    array(
                        "recruit_id"         =>  $input['id'],
                        "position_id"        =>  $request->input('position_id'),
                        "user_id"            =>  Auth::id(),
                    )
                );

                // $expertInput = [
                //     "id"                    => $input['id'],
                //     "fullname"              => $input["fullname"],
                //     "email_address"         => $input["email_address"],
                //     "phone"                 => $input["phone_number"],
                //     "identification_number" => $input["identification_number"],
                //     "file_path"             => $input['file_path'],
                //     "user_id"            =>  Auth::id(),
                // ];

                // // we also create a new Expert instance
                // $expert = Expert::create($expertInput);
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

    //==============================================================================
    //=========================FCE2 FUNCTIONALITY METHODS===========================
    //==============================================================================
    public function fce( Request $request ){
        if(!Auth::check()) return redirect('login');
        
        $query = $request->query();

        $name = isset( $query['name'] )? $query['name'] : '';
        
        return view('fce_evaluation.index')
            ->with('name', $name );
    }

    public function listFCEBootstrap( Request $request ){
        //call route parameters
        $query = $request->query();

        //create query to call recruits
        $recruits = Recruit::whereNotNull('recruit.id');
        
        //check if $name route parameter is setted
        if(isset($query['name'])){
            $recruits->where('recruit.fullname' , 'like' , '%'.$query['name'].'%');
        }
        
        //verify tab route parameter to set recruits filters
        $recruits->distinct()
            ->where('recruit.fce_overall', 'like', '-')
            ->whereNotNull('recruit.audio_path')
            ->select('recruit.*');

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

    public function getRecruitForFce( Request $request ){
        //call route parameters
        $input = $request->all();

        //set values in variables
        $recruit_id = $input["recruit_id"];
        
        $recruit = Recruit::where("id" , $recruit_id )->first();

        return json_encode(array(
            "recruit" => $recruit
        ));
    }

    public function saveRecruitFce( Request $request ){
        $input = $request->all();

        $input['grammar_vocabulary'] = ( Recruit::getFceValue($input['grammatical_forms']) + Recruit::getFceValue($input['vocabulary']) ) / 2;
        $input['discourse_management'] = ( Recruit::getFceValue($input['stretch_language']) + Recruit::getFceValue($input['cohesive_devices']) + Recruit::getFceValue($input['hesitation']) + Recruit::getFceValue($input['organizations_ideas']) ) / 4;
        $input['pronunciation'] = ( Recruit::getFceValue($input['intonation']) + Recruit::getFceValue($input['phonological_features']) + Recruit::getFceValue($input['intelligible']) ) / 3;
        $input['interactive_communication'] = Recruit::getFceValue($input['interaction']);
        
        $input['fce_total'] = ($input['grammar_vocabulary'] + $input['discourse_management'] + $input['pronunciation'] + $input['interactive_communication']) / 4;
        $input['fce_overall'] = Recruit::calculateOveral($input['fce_total']);
        
        $recruit_id = $input['recruit_id'];
        unset( $input['recruit_id'] );

        Recruit::where('id' , $recruit_id)->update($input);
    
        //return with success message
        redirect()->route('recruit.fce.menu')
                    ->with('success', 'FCE evaluated successfully.');
    }

    //==============================================================================
    //=====================UPLOAD AND DELETE: AUDIO, CV FILE========================
    //==============================================================================
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

    //==============================================================================
    //=====================POSTULANTS PROCESS EVALUATION METHODS====================
    //==============================================================================
    public function recruitsEvaluateOutstanding(Request $request){
        //call route parameters
        $input = $request->all();

        //set all parameters in variables
        $id          = $input['id'];
        $rpid        = $input['rpid'];
        $fullname    = $input['fullname'];
        $positionid  = $input['positionid'];
        $outstanding = $input['outstanding'];

        //call recruit by id
        $recruit = Recruit::where('id' , $id)->first();

        $current_date_time = Carbon::now()->toDateTimeString();
                
        //verify it recuir have CV FILE or PROFILE LINK (1 at least)
        if($recruit['file_path'] == null && $recruit['profile_link'] == null){
            //if not, return with error message
            // redirect()->route('recruit.menu')
            //                 ->with('error', 'Need to have "PROFILE LINK" or "CV FILE".');
        }else{
            //if exists 1 at least, approve call evaluation
            
        }
        RecruitPosition::where('recruit_id' , $id)->where('position_id' , $positionid)->where('id' , $rpid)->update(
            array("outstanding_report"=>$outstanding,
                  "user_id"=>Auth::id(),
                  "outstanding_ev_date"=>$current_date_time)
        );

        if($outstanding == 'approve'){
            //return view with success message
            redirect()->route('recruit.menu')
                ->with('success', '&#8226; "'. $fullname . '" move onto the next stage.');
        }else{
            //return view with warning message
            redirect()->route('recruit.menu')
                ->with('warning', '&#8226; "'. $fullname . '" finished his/her career.');
        }
    }

    public function recruitsEvaluateCall(Request $request){
        //call route parameters
        $input = $request->all();

        //set all parameters in variables
        $id          = $input['id'];
        $rpid        = $input['rpid'];
        $fullname    = $input['fullname'];
        $positionid  = $input['positionid'];
        $phonecall   = $input['phonecall'];

        //call recruit by id
        $recruit = Recruit::where('id' , $id)->first();

        $current_date_time = Carbon::now()->toDateTimeString();
        
        //verify it recuir have CV FILE or PROFILE LINK (1 at least)
        if($recruit['file_path'] == null && $recruit['profile_link'] == null){
            //if not, return with error message
            redirect()->route('recruit.outstanding')
                            ->with('error', 'Need to have "PROFILE LINK" or "CV FILE".');
        }else{
            //if exists 1 at least, approve call evaluation
            RecruitPosition::where('recruit_id' , $id)->where('position_id' , $positionid)->where('id' , $rpid)->update(
                array("call_report"=>$phonecall,
                      "call_ev_date"=>$current_date_time)
            );

            if($phonecall == 'approve'){
                //return view with success message
                redirect()->route('recruit.outstanding')
                    ->with('success', '&#8226; "'. $fullname . '" move onto the next stage.');
            }else{
                //return view with warning message
                redirect()->route('recruit.outstanding')
                    ->with('warning', '&#8226; "'. $fullname . '" finished his/her career.');
            }
        }
    }

    public function recruitsEvaluateAudio(Request $request){
        //call route parameters
        $input = $request->all();

        //set all parameters in variables
        $id          = $input['id'];
        $rpid        = $input['rpid'];
        $fullname    = $input['fullname'];
        $positionid  = $input['positionid'];
        $audio       = $input['audio'];

        //call recruit by id
        $recruit = Recruit::where('id' , $id)->first();

        $current_date_time = Carbon::now()->toDateTimeString();

        //dont verify, just approve/disapprove evaluation
        RecruitPosition::where('recruit_id' , $id)->where('position_id' , $positionid)->where('id' , $rpid)->update(
            array("audio_report"=>$audio,
                  "audio_ev_date"=>$current_date_time)
        );

        if($audio == 'approve'){            
            //return view with success message
            redirect()->route('recruit.preselected')
                ->with('success', '&#8226; "'. $fullname . '" move onto the next stage.');
        }else{
            //return view with warning message
            redirect()->route('recruit.preselected')
                ->with('warning', '&#8226; "'. $fullname . '" finished his/her career.');
        }
        return;
        
        //verify it recuir have CV FILE or PROFILE LINK (1 at least)
        // if($recruit['file_path'] == null && $recruit['profile_link'] == null){
        //     //return with error message
        //     redirect()->route('recruit.preselected')
        //                     ->with('error', 'Need to have "PROFILE LINK" or "CV FILE".');
        // }else{
        //     if($recruit['audio_path'] == null){
        //         //return with error message
        //         redirect()->route('recruit.preselected')
        //                     ->with('error', 'Need to upload "AUDIO FILE".');
        //     }else{
        //         if($recruit['crit_1'] == null || $recruit['crit_2'] == null){
        //             //return with error message
        //             redirect()->route('recruit.preselected')
        //                         ->with('error', 'Need to complete "PERSONA AMBIENTE" and "AUTO CONFIANZA" evaluations.');
        //         }else{
        //             //if exists 1 at least, approve call evaluation
        //             RecruitPosition::where('recruit_id' , $id)->where('position_id' , $positionid)->where('id' , $rpid)->update(
        //                 array("audio_report"=>$audio,
        //                       "audio_ev_date"=>$current_date_time)
        //             );
        //
        //             if($audio == 'approve'){
        //                 //return view with success message
        //                 redirect()->route('recruit.preselected')
        //                     ->with('success', '&#8226; "'. $fullname . '" move onto the next stage.');
        //             }else{
        //                 //return view with warning message
        //                 redirect()->route('recruit.preselected')
        //                     ->with('warning', '&#8226; "'. $fullname . '" finished his/her career.');
        //             }
        //         }
        //     }
        // }
    }

    public function recruitsEvaluateEvaluation(Request $request){
        //call route parameters
        $input = $request->all();

        //set all parameters in variables
        $id          = $input['id'];
        $rpid        = $input['rpid'];
        $fullname    = $input['fullname'];
        $positionid  = $input['positionid'];
        $evaluation       = $input['evaluation'];

        //call recruit by id
        $recruit = Recruit::where('id' , $id)->first();

        $current_date_time = Carbon::now()->toDateTimeString();
        
        //verify it recuir have CV FILE or PROFILE LINK (1 at least)
        if($recruit['file_path'] == null && $recruit['profile_link'] == null){
            //if not, return with error message
            redirect()->route('recruit.softskills')
                            ->with('error', 'Need to have "PROFILE LINK" or "CV FILE".');
        }else{
            if($recruit['fce_overall'] == '-'){
                redirect()->route('recruit.softskills')
                            ->with('error', 'Need to evaluate "ZOOM AUDIO (FCE)".');
            }else{
                // RecruitPosition::where('recruit_id' , $id)->where('position_id' , $positionid)->where('id' , $rpid)->update(
                //     array("soft_report"=>$evaluation,
                //         "soft_ev_date"=>$current_date_time)
                // );
                // if($evaluation == 'approve'){
                //     //return view with success message
                //     redirect()->route('recruit.softskills')
                //         ->with('success', '&#8226; "'. $fullname . '" move onto the next stage.');
                // }else{
                //     //return view with warning message
                //     redirect()->route('recruit.softskills')
                //         ->with('warning', '&#8226; "'. $fullname . '" finished his/her career.');
                // }

                if($recruit['raven_status'] == null){
                    redirect()->route('recruit.softskills')
                            ->with('error', 'Need to take Raven Quiz.');
                }else if($recruit['raven_status'] == 'invalid'){
                    redirect()->route('recruit.softskills')
                            ->with('error', 'Raven result was not valid for consideration.');
                }else{
                    //if exists 1 at least, approve call evaluation
                    RecruitPosition::where('recruit_id' , $id)->where('position_id' , $positionid)->where('id' , $rpid)->update(
                        array("soft_report"=>$evaluation,
                            "soft_ev_date"=>$current_date_time)
                    );

                    if($evaluation == 'approve'){
                        //return view with success message
                        redirect()->route('recruit.softskills')
                            ->with('success', '&#8226; "'. $fullname . '" move onto the next stage.');
                    }else{
                        //return view with warning message
                        redirect()->route('recruit.softskills')
                            ->with('warning', '&#8226; "'. $fullname . '" finished his/her career.');
                    }
                }
            }
        }
    }

    function sendMail($view, $subject, $email, $name, $data){
        $to_name = $name;
        $to_email = $email;
        Mail::send($view, $data, function($message) use ($to_name, $to_email, $subject) {
            $message->to($to_email, $to_name)->subject($subject);
            $message->from('hr@fulltimeforce.com','FullTimeForce');
        });
        return 'success';
    }

    //==============================================================================
    //=============================BULK ACTIONS METHODS=============================
    //==============================================================================
    public function bulkActions(Request $request){
        //call route parameters
        $input = $request->all();

        $action           = $input['action'];
        $rp_id_array      = $input['rp_id_array'];
        $recruit_id_array = $input['recruit_id_array'];
        $tab              = $input['tab'];

        $count_reject = 0;
        $count_accept = 0;

        $name_array_reject = '';
        $name_array_accept = '';
        $accepted_array = [];

        foreach ($recruit_id_array as $key => $recruit_id) {
            //OBTENEMOS EL POSTULANTE POR ID
            $recruit = Recruit::where('id' , $recruit_id)->first();

            if($action == 'trash'){
                $name_array_accept .= "<br> &nbsp; &#8226; " . $recruit['fullname'];
                array_push($accepted_array, $rp_id_array[$key]);
                $count_accept++;
            }else{
 
                if($tab == 'softskills'){
                    if($recruit['fce_overall'] == '-'){
                        $name_array_reject .= "<br> &nbsp; &#8226; " . $recruit['fullname'];
                        $count_reject++;
                    }else{
                        $name_array_accept .= "<br> &nbsp; &#8226; " . $recruit['fullname'];
                        array_push($accepted_array, $rp_id_array[$key]);
                        $count_accept++;
                    }
                }else if($tab == 'preselected'){
                    if($recruit['audio_path'] == null || $recruit['crit_1'] == null || $recruit['crit_2'] == null){
                        $name_array_reject .= "<br> &nbsp; &#8226; " . $recruit['fullname'];
                        $count_reject++;
                    }else{
                        $name_array_accept .= "<br> &nbsp; &#8226; " . $recruit['fullname'];
                        array_push($accepted_array, $rp_id_array[$key]);
                        $count_accept++;
                    }
                }else{
                    if($recruit['profile_link'] == null && $recruit['file_path'] == null){
                        $name_array_reject .= "<br> &nbsp; &#8226; " . $recruit['fullname'];
                        $count_reject++;
                    }else{
                        $name_array_accept .= "<br> &nbsp; &#8226; " . $recruit['fullname'];
                        array_push($accepted_array, $rp_id_array[$key]);
                        $count_accept++;
                    }
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
            if($tab == 'softskills'){
                redirect()->back()
                    ->with('error', 'There ' . (($count_reject > 1)?'are '.$count_reject.' postulants':'is 1 postulant') . ' without "FCE" evaluation:' . "\r\n" . $name_array_reject);
            }else if($tab == 'preselected'){
                redirect()->back()
                    ->with('error', 'There ' . (($count_reject > 1)?'are '.$count_reject.' postulants':'is 1 postulant') . ' without "AUDIO FILE" or "CALIFICATIONS":' . "\r\n" . $name_array_reject);
            }else{
                redirect()->back()
                    ->with('error', 'There ' . (($count_reject > 1)?'are '.$count_reject.' postulants':'is 1 postulant') . ' without "PROFILE LINK" and "CV FILE":' . "\r\n" . $name_array_reject);
            }
        }else{
            switch ($action) {
                case 'approve':
                    redirect()->back()
                        ->with('success', (($count_accept > 1)?$count_accept.' postulants':'1 postulant') . ' move onto the next stage:' . "\r\n" . $name_array_accept);
                    break;
                
                case 'disapprove':
                    redirect()->back()
                        ->with('warning', (($count_accept > 1)?$count_accept.' postulants':'1 postulant') . ' finished their career:' . "\r\n" . $name_array_accept);
                    break;

                case 'trash':
                    redirect()->back()
                        ->with('error', (($count_accept > 1)?$count_accept.' postulants':'1 postulant') . ' were deleted:' . "\r\n" . $name_array_accept);
                    break;
            }
        }
    }

    public function bulkActionApprove($rp_id_array, $tab){
        $current_date_time = Carbon::now()->toDateTimeString();
        foreach ($rp_id_array as $rp_id) {
            switch ($tab) {
                case 'postulant':
                    RecruitPosition::where('id' , $rp_id)->update(array("outstanding_report"=>"approve", "user_id"=>Auth::id(), "outstanding_ev_date"=>$current_date_time));
                    break;
                case 'outstanding':
                    RecruitPosition::where('id' , $rp_id)->update(array("call_report"=>"approve", "call_ev_date"=>$current_date_time));
                    break;
                case 'preselected':
                    RecruitPosition::where('id' , $rp_id)->update(array("audio_report"=>"approve", "audio_ev_date"=>$current_date_time));
                    break;
                case 'softskills':
                    RecruitPosition::where('id' , $rp_id)->update(array("soft_report"=>"approve", "soft_ev_date"=>$current_date_time));
                    break;
            }   
        }
    }

    public function bulkActionDisapprove($rp_id_array, $tab){
        $current_date_time = Carbon::now()->toDateTimeString();
        foreach ($rp_id_array as $rp_id) {
            switch ($tab) {
                case 'postulant':
                    RecruitPosition::where('id' , $rp_id)->update(array("outstanding_report"=>"disapprove", "user_id"=>Auth::id(), "outstanding_ev_date"=>$current_date_time));
                    break;
                case 'outstanding':
                    RecruitPosition::where('id' , $rp_id)->update(array("call_report"=>"disapprove", "call_ev_date"=>$current_date_time));
                    break;
                case 'preselected':
                    RecruitPosition::where('id' , $rp_id)->update(array("audio_report"=>"disapprove", "audio_ev_date"=>$current_date_time));
                    break;
                case 'softskills':
                    RecruitPosition::where('id' , $rp_id)->update(array("soft_report"=>"disapprove", "soft_ev_date"=>$current_date_time));
                    break;
            }   
        }
    }

    public function bulkActionTrash($rp_id_array){
        foreach ($rp_id_array as $rp_id) {
            RecruitPosition::where('id' , $rp_id)->delete();
        }
    }
    
    public function quizIndex(Request $request, $recruitId){
        if(!Auth::check() && !$request->hasValidSignature()) return redirect('/');
        $recruit = Recruit::find($recruitId);
        if($recruit == null){
            return redirect('/');
        }
        return view('quiz.index')->with('recruit',$recruit);
    }

    public function quizStart(Request $request){
        $quiz = new Quiz;
        session([
            'endtime'=>strtotime("now") + 3900, // SET END TO 1H 5MIN
            'recruit_id'=> $request->rcn,
            'curr_question_number' => 1,
            'quiz' => [],
        ]);
        $recruit = Recruit::where('id',$request->rcn);
        if($recruit->count() > 0){
            // $recruit->update([
            //     'raven_status'=>'invalid',
            // ]);
            return [
                // 'code' => session('recruit_id'),
                // 'quiz' => session('quiz'),
                'status' => 'continue',
                'curr_question' => session('curr_question_number'),
                'img'=> $quiz->getImgFromQuestion(1)
            ];
        }

        return [
            'success' => false,
        ];
    }

    public function quizContinue(Request $request){
        if(session('recruit_id') != $request->rcn){
            return [
                'success' => false,
                'error' => 'the answer was register under a different recruit code'
            ];
        }
        $quiz = new Quiz;
        $curr_question = session('curr_question_number');
        $curr_quiz = session('quiz');
        $quiz_status = 'continue';

        // if time is already up
        if(session('endtime')<strtotime("now")){
            // END TEST - FILL EMPTY ANSWERS -  CALCULATE & SAVE RESULTS
            $size_quiz = sizeof($curr_quiz) + 1;
            for ($i=$size_quiz; $i <= 60; $i++) { 
                $curr_quiz['q'.$i] = null;
            }
            
            $quiz_status = 'ended';
            $quiz_result = $quiz->evaluateResults($curr_quiz);

            $recruit = Recruit::where('id',session('recruit_id'));
            if($quiz_result['valid']){    
                $recruit->update([
                    'raven_total'=>$quiz_result['total'],
                    'raven_overall'=>$quiz_result['raven_overall'],
                    'raven_perc'=>$quiz_result['raven_perc'],
                    'raven_status'=>'completed',
                ]);
            }else{
                $recruit->update([
                    'raven_status'=>'invalid',
                ]);
            }
            return [
                'status' => $quiz_status,
            ];
        }

        //SAVE ANSWER IN QUIZ ARRAY
        $curr_quiz['q'.$curr_question] = $request->answer;
        
        //Save current quiz
        session(['quiz' => $curr_quiz]);

        //SET NEXT QUESTION INDEX
        if($curr_question < 60){
            session(['curr_question_number' => $curr_question + 1]);
        }else{
            // END TEST AND SAVE RESULTS
            $quiz_status = 'ended';
            $quiz_result = $quiz->evaluateResults($curr_quiz);

            $recruit = Recruit::where('id',session('recruit_id'));
            if($quiz_result['valid']){    
                $recruit->update([
                    'raven_total'=>$quiz_result['total'],
                    'raven_overall'=>$quiz_result['raven_overall'],
                    'raven_perc'=>$quiz_result['raven_perc'],
                    'raven_status'=>'completed',
                ]);
            }else{
                $recruit->update([
                    'raven_status'=>'invalid',
                ]);
            }
            return [
                'status' => $quiz_status,
            ];
        }

        return [
            'status' => $quiz_status,
            'curr_question' => session('curr_question_number'),
            'img'=> $quiz->getImgFromQuestion($curr_question + 1)
        ];
    }

    public function quizEnd(Request $request){
        $recruit = Recruit::where('id',session('recruit_id'));

        if(session('recruit_id') != $request->rcn){
            $recruit->update([
                'raven_status'=>"invalid"
            ]);
            return [
                'status' => "ended",
                'recruit' => $recruit
            ];
        }
        $quiz = new Quiz;
        $curr_question = session('curr_question_number');
        $curr_quiz = session('quiz');
        $size_quiz = sizeof($curr_quiz) + 1;

        for ($i=$size_quiz; $i <= 60; $i++) { 
            $curr_quiz['q'.$i] = null;
        }

        $quiz_status = 'ended';
        $quiz_result = $quiz->evaluateResults($curr_quiz);
        

        if($quiz_result['valid']){    
            $recruit->update([
                'raven_total'=>$quiz_result['total'],
                'raven_overall'=>$quiz_result['raven_overall'],
                'raven_perc'=>$quiz_result['raven_perc'],
                'raven_status'=>'completed'
            ]);
        }else{
            $recruit->update([
                'raven_status'=>"invalid"
            ]);
        }
        
        return [
            'status' => $quiz_status,
            'recruit' => $recruit
            // 'quiz_result' => $quiz_result
        ];

    }

    public function testMail(){
        try{
            MultiMail::to('alejandro.daza@fulltimeforce.com')
                ->from('luisana.moncada@fulltimeforce.com')
                ->send(new ravenEmail('Alejandro Daza','this-is-link'));

            return 'all good';
        }catch(\Swift_TransportException $e){
            if($e->getMessage()) {
                dd($e->getMessage());
            }             
        }catch(Exception $ex){
            if($ex->getMessage()) {
                dd($ex->getMessage());
            }             
        }
    }

    public function quizSigned($recruitId){
        $query = array(
            'recruitId' => $recruitId 
        );
        $recruit = Recruit::find($recruitId);
        $query['position'] = time();

        $url = URL::temporarySignedRoute(
            'recruit.quiz', now()->addHours(2), $query
        );
        return $url;
    }

    public function quizRestore(Request $request){
        $recruit = Recruit::where('id',$request->id);
        if($recruit->count() > 0){
            $recruit->update([
                'raven_total'   =>null,
                'raven_overall' =>null,
                'raven_perc'    =>null,
                'raven_status'  =>null,
            ]);
        }
    }

    public function scheduleQuizView(Request $request){
        $recruit = Recruit::find($request->id);
        $date = date('Y-m-d');
        $time = date('H',strtotime('1 hour'));
        if($recruit->raven_date != null){
            $d = strtotime($recruit->raven_date);

            $date = date('Y-m-d',$d);
            $time = date('H',$d);
        }

        return view('quiz.schedule_modal',[
            'recruit'=>$recruit,
            'nowDate'=>$date,
            'nowTime'=>$time,
        ]);
    }

    public function scheduleSave(Request $request){
        $date = date($request->date.' '.$request->time.':00');
        $recruit = Recruit::where('id',$request->id);
        if($recruit->count() > 0){
            $recruit->update([
                'raven_date' => $date,
            ]);
        }
        return 'success';
    }

    public function scheduleCron(){
        $email_data = [];
        $recruits = Recruit::whereNotNull('raven_date')
                    ->select('id','fullname','email_address','raven_date');
        if($recruits->count() > 0){
            $recruits = $recruits->get();
            foreach($recruits as $recruit){

                $ravenTime = strtotime($recruit->raven_date);
                $date = date('Y-m-d',$ravenTime);
                $time = date('H', $ravenTime);

                // If there scheduled for today and this present hour
                if($date == date('Y-m-d') && $time == date('H')){
                    
                    $positions = RecruitPosition::join('users','recruit_positions.user_id','=','users.id')
                            ->where('recruit_positions.recruit_id',$recruit->id)
                            ->select('recruit_positions.id','users.email')
                            ->orderBy('recruit_positions.created_at','DESC');
                            
                    if($positions->count() > 0){
                        $position = $positions->first();
                        $email_data[] = [
                            'id'=>$recruit->id,
                            'mail'=>$recruit->email_address, 
                            'name'=>$recruit->fullname,
                            'recruit'=>$position->email,
                        ];
                    }
                }
            }
        }

        // Send mails to everyone who scheduled
        if(!empty($email_data)){
            foreach($email_data as $data){
                $query = [
                    'recruitId' => $data['id'],
                    'position' => time(),
                ];
                
                $url = URL::temporarySignedRoute(
                    'recruit.quiz', now()->addHours(2), $query
                );

                MultiMail::to('alejandro.daza@fulltimeforce.com') //$data['mail']
                    ->from($data['recruit'])
                    ->send(new ravenEmail($data['name'],$url));
            }
            return $email_data;
        }

        return 'no mails scheduled';
    }

    //==============================================================================
    //=====================POSTULANTS TECHNICAL QUESTIONARIE========================
    //==============================================================================
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
        $portfolios = Portfolio::where('expert_id' , $recruitId )->get();
        $position = 0;
        if( !is_null( $request->query('position') ) ){
            $position = 1;
        }

        return view('recruit.tech_qtn')
            ->with('recruit', $recruit)
            ->with('portfolios', $portfolios)
            ->with('technologies',Recruit::getTechnologies());
    }

    public function saveRecruitTechQtn(Request $request){
        $validator = $request->validate( [
            'fullname'              => 'required',
            'email_address'         => 'required',
            'identification_number' => 'required',
            'birthday'              => 'date_format:'.config('app.date_format_php'),
            'phone_number'          => 'required|numeric',
            'file_path'             => 'mimes:pdf,doc,docx|max:2048',
        ]);
        
        if( !is_array($validator) ){
            if ($validator->fails()) {
                return back()
                            ->withErrors($validator)
                            ->withInput($request->input());
            }
        }

        try{
            //call route parameters
            $input = $request->all();

            //set variables for uploading CV
            $file = $request->file("file_path");
            $destinationPath = 'uploads/cv';        
            $newNameFile = '';

            //upload CV to S3 server and string to DB
            if( $file ){
                $_fileName = "cv-".date("Y-m-d")."-".time().".".$file->getClientOriginalExtension();
                $newNameFile = $destinationPath."/" . $_fileName;
                $path = $request->file("file_path")->store("cv" , "s3");
                $path_s3 = Storage::disk("s3")->url($path);
                Storage::disk("s3")->put($path , file_get_contents( $request->file("file_path") ) , 'public' );
                Storage::delete( $path );
                $input["file_path"] = $path_s3;
            }
            
            //catch all variables we need
            $recruit_id = $input["recruit_id"];

            //set tech_qtn status and unset recruit_id and _token
            unset( $input["_token"] );
            unset( $input["recruit_id"] );
            $input['tech_qtn'] = 'filled';

            //get portfolio rows and unset them
            $portfolio_link = isset( $input['link'] )? $input['link'] : array();
            $portfolio_description = isset( $input['description'] )? $input['description'] : array();
            unset( $input['link'] );
            unset( $input['description'] );

            //config birthday
            if( isset($input['birthday']) ) $input['birthday'] = date("Y-m-d H:i:s" , strtotime($input['birthday']));

            //update recruit information
            Recruit::where('id' , $recruit_id)->update($input);

            //update recruit portfolio rows
            Portfolio::where('expert_id', $recruit_id)->delete();
            foreach ( $portfolio_link as $key => $p ) {
                Portfolio::create(array(
                    "expert_id" => $recruit_id,
                    "link" => $p,
                    "description" => $portfolio_description[$key]
                )); 
            }

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

    //==============================================================================
    //=====================POSTULANTS POSITION BULK ACTIONS=========================
    //==============================================================================
    public function positionBulkAction(Request $request){
        $input = $request->all();
        $experts_id   = $input['expert_id_array'];
        $experts_name = $input['expert_name_array'];
        $positionId   = $input['action'];
        $positionName = $input['position_name'];

        $user_id = Auth::id();
        $name_array = '';
        $count = 0;
        
        foreach ($experts_id as $key => $value) {

            RecruitPosition::create(
                array(
                    "recruit_id"         =>  $value,
                    "position_id"        =>  $positionId,
                    "user_id"            =>  $user_id
                )
            );

            $name_array .= "<br> &nbsp; &#8226; " . $experts_name[$key];
            $count++;
        }

        redirect()->back()
                ->with('success', 'Great!! ' . $count . (($count > 1)?' experts':' expert') . ' successfully applied to "' . $positionName . '":' . "\r\n" . $name_array);
    }

    //==============================================================================
    //=====================SEARCH PROFILE AND NOTIFICATIONS METHDOS=================
    //==============================================================================
    public function saveSearchProfile(Request $request){
        $input = $request->all();

        $save_search = $input['save_search'];
        unset($input['save_search']);

        if($save_search == 'true'){

            if($input['search_name'] == "" || $input['search_user_level'] == ""){
                redirect()->back()
                    ->with('error', 'Search Profile couldn´t be saved. Need to have "Search Profile Name" and "User Level"');
            }else{
                $input['user_id'] = Auth::id();
                $input['id'] = Hashids::encode(time());

                SearchHistory::create($input);

                redirect()->back()
                    ->with('success', 'Search Profile successfully saved.');
            }
        }
    }

    public function loadSearchProfile(Request $request){
        $input = $request->all();

        $search_profile = SearchHistory::where("id" , $input['selectId'] )->first();

        return json_encode(array(
            "search_profile" => $search_profile
        ));
    }

    public function loadToastNotifiers(){
        if(Auth::user()->role_id){
            $notifications = Notification::where('user_level', '=', Auth::user()->role_id)->where('state', '=', 'enabled')->get();

            return json_encode(array(
                "notifications" => $notifications
            ));
        }else{
            return json_encode(array(
                "notifications" => []
            ));
        }
    }

    public function deleteToastNotifiers(Request $request){
        $input = $request->all();

        Notification::where( 'id' , $input['notification_id'] )->update(
            array(
                "state" => "disabled",
            )
        );
    }

    //==============================================================================
    //========================TAKE POSITION NOTES METHDOS===========================
    //==============================================================================
    public function getRecruitPositionNotes(Request $request){
        $input = $request->all();
        $rp_id = $input['rpid'];
        $positionid = $input['positionid'];
        $row_name = ($input['tab']=='preselected' || $input['tab']=='postulant')?'evaluation_notes':'audio_notes';

        $recruit_position = RecruitPosition::where('id', $rp_id)->get();
        $snippet = Position::where('id', $positionid)->get();

        return json_encode(array(
            "notes" => $recruit_position[0][$row_name],
            "snippet" => $snippet[0]['snippet'],
        ));
    }

    public function updateRecruitPositionNotes(Request $request){
        $input = $request->all();
        
        $rp_id = $input['rpid'];
        $textarea = $input['textarea'];
        $row_name = ($input['tab']=='preselected'|| $input['tab']=='postulant')?'evaluation_notes':'audio_notes';

        RecruitPosition::where('id' , $rp_id)->update(array($row_name=> $textarea));

        return json_encode(array(
            "state" => true
        ));

        
    }

    public function deleteRecruitWithNotes(Request $request){
        //call route parameters
        $input = $request->all();

        //set values in variables
        $rp_id = $input["rp_id"];
        $textarea = $input['textarea'];
        $row_name = ($input['tab']=='preselected'|| $input['tab']=='postulant')?'evaluation_notes':'audio_notes';
        $recruit_id = $input["recruit_id"];
        $position_id = $input["position_id"];

        $current_date_time = Carbon::now()->toDateTimeString();
        
        RecruitPosition::where('recruit_id' , $recruit_id)
                        ->where('position_id' , $position_id)
                        ->where('id' , $rp_id)
                        ->update([
                            "outstanding_report"=>'disapprove',
                            "outstanding_ev_date"=>$current_date_time,
                            $row_name=> $textarea,
                            "user_id"=>Auth::id(),
                        ]);

        return json_encode(["state" => true]);
    }

    //==============================================================================
    //===============================AUXILIARY METHDOS==============================
    //==============================================================================
    public function pasarFilas(Request $request){

        //=========================================================================================
        $logs = Recruiterlog::leftJoin('expert_log', 'expert_log.log_id', '=', 'recruiter_logs.id')
                ->whereNull('expert_log.log_id')
                ->whereNotNull('recruiter_logs.position_id')
                ->whereNotNull('recruiter_logs.expert')
                ->select(
                    'recruiter_logs.expert AS expert',
                    'recruiter_logs.position_id AS position_id',
                    'recruiter_logs.user_id AS user_id',
                    'recruiter_logs.platform AS platform',
                    'recruiter_logs.link AS link',
                    'recruiter_logs.cv AS cv',
                    'recruiter_logs.filter_audio AS filter_audio',
                    'recruiter_logs.communication AS communication',
                    'recruiter_logs.created_at AS created_at',
                    'recruiter_logs.updated_at AS updated_at'
                    )
                ->get();

        foreach ($logs as $key => $value) {

            list($date, $time) = explode(' ', $value['created_at']);
            list($year, $month, $day) = explode('-', $date);
            list($hour, $minute, $second) = explode(':', $time);

            $timestamp = mktime($hour, $minute, $second, $month, $day, $year); 

            $id = Hashids::encode($timestamp);

            Recruit::create(
                array(
                    "id"            =>  $id,
                    "fullname"      =>  $value['expert'],
                    "platform"      =>  $value['platform'],
                    "profile_link"  =>  $value['link'],
                    "audio_path"    =>  $value['filter_audio'],
                    "phone_number"  =>  '-',
                    "email_address" =>  '-',
                    "created_at"    =>  $value['created_at'],
                    "update_at"     =>  $value['updated_at']
                )
            );

            RecruitPosition::create(
                array(
                    "recruit_id"          =>  $id,
                    "position_id"         =>  $value['position_id'],
                    "user_id"             =>  $value['user_id'],
                    "outstanding_report"  =>  "disapprove",
                    "outstanding_ev_date" =>  $value['updated_at'],
                    "created_at"          =>  $value['created_at'],
                    "update_at"           =>  $value['updated_at']
                )
            );
        }
        //=========================================================================================
        
        return json_encode(array(
            "logs" => $logs
        ));
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

    //==============================================================================
    //===============================EXPERT VIEW METHODS============================
    //==============================================================================
    public function expertIndex( Request $request )
    {
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role->id >= 3) return redirect('/expert/fce');
        $_recruits = Recruit::where(function ($query) {
            $query->where('recruit.phone_number', 'not like', '-')
                  ->orWhere('recruit.email_address', 'not like', '-');
        })->where('recruit.tech_qtn', 'filled')->get();
        $recruits = count($_recruits);
        $query = $request->query();

        $search = isset( $query['search'] )? true : false;
        $deep_search = isset($query['deep_search']) ? ($query['deep_search'] ? 1 : 0) : 0;
        $a_basic = isset( $query['basic'] )? explode(",", $query['basic']) : array();
        $a_inter = isset( $query['intermediate'] )? explode(",", $query['intermediate']) : array();
        $a_advan = isset( $query['advanced'] )? explode(",", $query['advanced']) : array();
        $name = isset( $query['name'] )? $query['name'] : '';
        $audio = isset( $query['audio'] )? filter_var($query['audio'] , FILTER_VALIDATE_BOOLEAN) : true;
        $selection = isset( $query['selection'] )? $query['selection'] : 1;
        $profile = isset( $query['profile'] )? $query['profile'] : '';
        
        $basic = array();
        $intermediate = array();
        $advanced = array();

        foreach(Recruit::getTechnologies() as $catid => $cat){
            foreach($cat[1] as $techid => $techlabel){
                if( in_array( $techid , $a_basic ) ) $basic = array_merge( $basic, array( $techid => $techlabel ));
                if( in_array( $techid , $a_inter ) ) $intermediate = array_merge( $intermediate, array( $techid => $techlabel ));
                if( in_array( $techid , $a_advan ) ) $advanced = array_merge( $advanced ,array( $techid => $techlabel ));
            }
        }

        $search_profiles = SearchHistory::where('search_user_level', '=', Auth::user()->role->id)->get();
        $positions = Position::whereNull('position_type')->get();
        
        return view('experts.index',compact('recruits'))
            ->with('search', $search )
            ->with('deep_search', $deep_search)
            ->with('audio', $audio )
            ->with('selection', $selection )
            ->with('name', $name )
            ->with('basic', $basic )
            ->with('intermediate', $intermediate )
            ->with('advanced', $advanced )
            ->with('search_profiles', $search_profiles )
            ->with('profile', $profile )
            ->with('positions', $positions )
            ->with('technologies', Recruit::getTechnologies() );
    }

    public function listExpertBootstrap(Request $request){
        $query = $request->query();

        $a_basic = array();
        $a_inter = array();
        $a_advan = array();
        
        //BASIC, INTERMEDIATE, ADVANCED filters
        if( isset( $query['basic'] ) ) $a_basic = ($query['basic'] != '')? explode("," , $query['basic']) : array();
        if( isset( $query['intermediate'] ) ) $a_inter = ($query['intermediate'] != '')? explode("," , $query['intermediate']) : array();
        if( isset( $query['advanced'] ) ) $a_advan = ($query['advanced'] != '')? explode("," , $query['advanced']) : array();
        
        if(isset($query['deep_search']) ? ($query['deep_search'] ? 1 : 0) : 0){
            $_recruits = Recruit::whereNotNull('id');
        }else{
            $_recruits = Recruit::where(function ($query) {
                $query->where('recruit.phone_number', 'not like', '-')
                      ->orWhere('recruit.email_address', 'not like', '-');
            });
            $_recruits->where('recruit.tech_qtn', 'filled');
        }

        foreach ($a_basic as $basic) {
            $_recruits->whereIn($basic, ['basic','intermediate','advanced']);
        }

        foreach ($a_inter as $inter) {
            $_recruits->whereIn($inter, ['intermediate','advanced']);
        }

        foreach ($a_advan as $advan) {
            $_recruits->whereIn($advan, ['advanced']);
        }

        //NAME filter
        $names = explode(" ", $query['name']);
        foreach($names as $name){
            $_recruits->where('recruit.fullname' , 'like' , '%'.$name.'%');
        }
        // $_recruits->where('recruit.fullname' , 'like' , '%'.$query['name'].'%');

        //SELECTION filter
        if( $query['selection'] != 1 ){
            $_recruits->where('recruit.selection' , intval( $query['selection']  ) );
        }

        //AUDIO filter

        // if( filter_var($query['audio'] , FILTER_VALIDATE_BOOLEAN) ){
        //     $_recruits->whereNotNull('recruit.audio_path');
        // }else{
        //     $_recruits->whereNull('recruit.audio_path');
        // }        

        $_recruits->orderByDesc('recruit.created_at');
        
        $_recruits->select('recruit.*');

        $recruit =  $_recruits->paginate( $query['rows'] );
        $rows = $recruit->items();

        return json_encode(array(
            "total" => count($rows),
            "totalNotFiltered" => $recruit->total(),
            "rows" => $rows,
            "query" => $query,
        ));
    }

    public function listExpertBetaBootstrap(Request $request){
        $query = $request->query();

        $a_basic = array();
        $a_inter = array();
        $a_advan = array();
        
        //BASIC, INTERMEDIATE, ADVANCED filters
        if( isset( $query['basic'] ) ) $a_basic = ($query['basic'] != '')? explode("," , $query['basic']) : array();
        if( isset( $query['intermediate'] ) ) $a_inter = ($query['intermediate'] != '')? explode("," , $query['intermediate']) : array();
        if( isset( $query['advanced'] ) ) $a_advan = ($query['advanced'] != '')? explode("," , $query['advanced']) : array();
        
        $_recruits = Recruit::where(function ($query) {
            $query->where('recruit.phone_number', 'not like', '-')
                  ->orWhere('recruit.email_address', 'not like', '-');
        });

        // $_recruits->where('recruit.tech_qtn', 'filled');
        
        foreach ($a_basic as $basic) {
            $_recruits->whereIn($basic, ['basic','intermediate','advanced']);
        }

        foreach ($a_inter as $inter) {
            $_recruits->whereIn($inter, ['intermediate','advanced']);
        }

        foreach ($a_advan as $advan) {
            $_recruits->whereIn($advan, ['advanced']);
        }

        //NAME filter
        $_recruits->where('recruit.fullname' , 'like' , '%'.$query['name'].'%');

        //SELECTION filter
        if( $query['selection'] != 1 ){
            $_recruits->where('recruit.selection' , intval( $query['selection']  ) );
        }

        //AUDIO filter
        
        // if( filter_var($query['audio'] , FILTER_VALIDATE_BOOLEAN) ){
        //     $_recruits->whereNotNull('recruit.audio_path');
        // }else{
        //     $_recruits->whereNull('recruit.audio_path');
        // }

        $_recruits->orderByDesc('recruit.created_at');
        
        $_recruits->select('recruit.*');

        $recruit =  $_recruits->paginate( $query['rows'] );
        $rows = $recruit->items();

        return json_encode(array(
            "total" => count($rows),
            "totalNotFiltered" => $recruit->total(),
            "rows" => $rows
        ));
    }

    public function getExpertNotes(Request $request){
        $position = RecruitPosition::where('recruit_id', $request->recruitId)->first();
        
        return [
            "evaluation_notes" => $position->evaluation_notes,
            "audio_notes" => $position->audio_notes,
        ];
    }

    public function getExpertAudio( Request $request ){
        $input = $request->all();

        $recruit = Recruit::where('id' , $input['recruitId'])->get();
        
        return array(
            "recruit" => $recruit
        );
    }

    public function updateExpertSelection( Request $request){
        $id = $request->input('recruitId');
        $selection = $request->input('selection');

        Recruit::where('id' , $id)->update(array(
            "selection" => intval($selection)
        ));

        return response()->json(array(
            "selection" => $selection
        ));
    }

    public function deleteExpert(Request $request){
        $id = $request->input('recruitId');

        Recruit::where('id' , $id)->delete();
    }

    public function showExpert(Request $request){
        $input = $request->all();
        
        $recruit = Recruit::find($input["id"]);

        $basic = [];
        $intermediate = [];
        $advanced = [];

        $technologies = Recruit::getTechnologies();
        foreach ($technologies as $catgId => $catg) {
            foreach ($catg[1] as $techId => $techLabel) {
                if($catgId == "english"){continue;}
                if($recruit[$techId] == "basic"){$basic[] = $techLabel;}
                if($recruit[$techId] == "intermediate"){$intermediate[] = $techLabel;}
                if($recruit[$techId] == "advanced"){$advanced[] = $techLabel;}
            }
        }
        return ["recruit"=>$recruit,"basic"=>$basic,"intermediate"=>$intermediate,"advanced"=>$advanced];
    }

    public function updateExpertPopup(Request $request){
        $id = $request->id;
        $recruit = Recruit::where('id', $id)->update([
            'crit_1'=>$request->crit_1,
            'crit_2'=>$request->crit_2,
        ]);
        return $recruit;
    }

    public function getTechnologies(Request $request){
        $search = $request->query('search','');
        $start = $request->query('start','0');
        $techs = array();
        foreach(Recruit::getTechnologies() as $catid => $cat){
            foreach($cat[1] as $techid => $techlabel){
                if(preg_match('/' . ($start ? '^' : '') . $search . '/i', $techlabel) || preg_match('/' . ($start ? '^' : '') . $search . '/i', $techid)){
                    $techs[] = array ('id'=>$techid,'text'=>$techlabel);
                }                  
            }
        }
        return response()->json($techs);
    }

    public function editExpert($recruitId)
    {
        if(!Auth::check()) return redirect('login');

        $recruit = Recruit::where('id', $recruitId)->get();
        $portfolios = Portfolio::where('expert_id' , $recruitId)->get();
        
        return view('experts.edit')
            ->with('recruit',$recruit[0])
            ->with('portfolios',$portfolios)
            ->with('technologies',Recruit::getTechnologies());
    }

    public function updateExpert(Request $request){
        $validator = $request->validate( [
            'fullname'              => 'required',
            'email_address'         => 'required',
            'identification_number' => 'required',
            'birthday'              => 'date_format:'.config('app.date_format_php'),
            'phone_number'          => 'required|numeric',
            'file_path'             => 'mimes:pdf,doc,docx|max:2048',
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

            //set variables for uploading CV
            $file = $request->file("file_path");
            $destinationPath = 'uploads/cv';        
            $newNameFile = '';

            //upload CV to S3 server and string to DB
            if( $file ){
                $_fileName = "cv-".date("Y-m-d")."-".time().".".$file->getClientOriginalExtension();
                $newNameFile = $destinationPath."/" . $_fileName;
                $path = $request->file("file_path")->store("cv" , "s3");
                $path_s3 = Storage::disk("s3")->url($path);
                Storage::disk("s3")->put($path , file_get_contents( $request->file("file_path") ) , 'public' );
                Storage::delete( $path );
                $input["file_path"] = $path_s3;
            }
            
            //catch all variables we need
            $recruit_id = $input["recruit_id"];

            //set tech_qtn status and unset recruit_id and _token
            unset( $input["_token"] );
            unset( $input["recruit_id"] );

            //get portfolio rows and unset them
            $portfolio_link = isset( $input['link'] )? $input['link'] : array();
            $portfolio_description = isset( $input['description'] )? $input['description'] : array();
            unset( $input['link'] );
            unset( $input['description'] );

            //config birthday
            if( isset($input['birthday']) ) $input['birthday'] = date("Y-m-d H:i:s" , strtotime($input['birthday']));

            //update recruit information
            Recruit::where('id' , $recruit_id)->update($input);

            //update recruit portfolio rows
            Portfolio::where('expert_id', $recruit_id)->delete();
            foreach ( $portfolio_link as $key => $p ) {
                Portfolio::create(array(
                    "expert_id" => $recruit_id,
                    "link" => $p,
                    "description" => $portfolio_description[$key]
                )); 
            }

            if(Auth::check()){
                //return with success message
                return redirect()->back()
                            ->with('success', 'Expert edited successfully.');
            }else{
                //return with error message
                return redirect()->route('experts.confirmation');
            }

        } catch (Exception $exception) {
            //return with error
            return back()->withErrors($exception->getMessage())->withInput();
        }
    }

    public function developerEditSigned($recruitId) {
        $query = array(
            'recruitId' => $recruitId 
        );

        if( Recruit::where('id' , $recruitId)->count() > 0 ){
            $query['position'] = time();
        }

        return URL::temporarySignedRoute(
            'experts.edit.form', now()->addDays(7), $query
        );
    }

    public function developerEdit(Request $request , $recruitId){

        if(!Auth::check() && !$request->hasValidSignature()) return redirect('login');

        $recruit = Recruit::find($recruitId);

        $portfolios = Portfolio::where('expert_id' , $recruit->id )->get();

        return view('experts.edit')
            ->with('recruit', $recruit)
            ->with('portfolios', $portfolios)
            ->with('technologies',Recruit::getTechnologies());
    }
}
