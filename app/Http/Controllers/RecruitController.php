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
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        $query = $request->query();
        $name = isset($query['name']) ? $query['name'] : '';

        $positions = Position::where('status' , 'enabled')->latest()->get();
        $platforms = $this->platforms();

        //return view('recruit.index',['name'=>$name,'recruits'=> $recruits]);
        return view('recruit.index')
            ->with('s', $name )
            ->with([
                'positions' => $positions, 
                'platforms' => $platforms,
                'tab' => "postulant",
            ]);
        //return view('user.index')->with('name',$name);
    }

    public function outstanding(Request $request){
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        $query = $request->query();
        $name = isset($query['name']) ? $query['name'] : '';

        $recruits = Recruit::all();
        $positions = Position::where('status' , 'enabled')->latest()->get();
        $platforms = $this->platforms();

        //return view('recruit.index',['name'=>$name,'recruits'=> $recruits]);
        return view('recruit.outstanding' , compact('recruits') )
            ->with('s', $name )
            ->with([
                'positions' => $positions, 
                'platforms' => $platforms,
                'tab' => "outstanding",
            ]);
        //return view('user.index')->with('name',$name);
    }

    public function preselected(Request $request){
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        $query = $request->query();
        $name = isset($query['name']) ? $query['name'] : '';

        $recruits = Recruit::all();
        $positions = Position::where('status' , 'enabled')->latest()->get();
        $platforms = $this->platforms();

        //return view('recruit.index',['name'=>$name,'recruits'=> $recruits]);
        return view('recruit.preselected' , compact('recruits') )
            ->with('s', $name )
            ->with([
                'positions' => $positions, 
                'platforms' => $platforms,
                'tab' => "preselected",
            ]);
        //return view('user.index')->with('name',$name);
    }

    public function recruitsBootstrap(Request $request){
        if(!Auth::check()) return redirect('login');
        $query = $request->query();

        $recruits = Recruit::whereNotNull('recruit.id');
        
        if(isset($query['name'])){
            $recruits->where('recruit.fullname' , 'like' , '%'.$query['name'].'%');
        }
        
        if($query['tab'] == "postulant"){
            $recruits->distinct()
                ->leftJoin('recruit_positions' , 'recruit_positions.recruit_id' , '=' , 'recruit.id')
                ->leftJoin('positions' , 'positions.id' , '=' , 'recruit_positions.position_id')
                ->leftJoin('users' , 'users.id' , '=' , 'recruit_positions.user_id')
                ->whereNull('recruit_positions.outstanding_report')
                ->whereNull('recruit_positions.call_report')
                ->whereNotNull('recruit_positions.user_id')
                ->select('recruit.*',
                    'positions.name AS position_name',
                    'users.name AS user_name',
                    'positions.id AS position_id',
                    'recruit_positions.audio_report AS audio_report',
                    'recruit_positions.id AS rp_id');
        }elseif($query['tab'] == "outstanding"){
            $recruits->distinct()
                ->leftJoin('recruit_positions' , 'recruit_positions.recruit_id' , '=' , 'recruit.id')
                ->leftJoin('positions' , 'positions.id' , '=' , 'recruit_positions.position_id')
                ->leftJoin('users' , 'users.id' , '=' , 'recruit_positions.user_id')
                ->where('recruit_positions.outstanding_report', '=' , 'approve')
                ->whereNull('recruit_positions.call_report')
                ->whereNotNull('recruit_positions.user_id')
                ->select('recruit.*',
                    'positions.name AS position_name',
                    'users.name AS user_name',
                    'positions.id AS position_id',
                    'recruit_positions.audio_report AS audio_report',
                    'recruit_positions.id AS rp_id');
        }elseif($query['tab'] == "preselected"){
            $recruits->distinct()
                ->leftJoin('recruit_positions' , 'recruit_positions.recruit_id' , '=' , 'recruit.id')
                ->leftJoin('positions' , 'positions.id' , '=' , 'recruit_positions.position_id')
                ->leftJoin('users' , 'users.id' , '=' , 'recruit_positions.user_id')
                ->where('recruit_positions.outstanding_report', '=' , 'approve')
                ->where('recruit_positions.call_report', '=' , 'approve')
                ->whereNotNull('recruit_positions.user_id')
                ->select('recruit.*',
                    'positions.name AS position_name',
                    'users.name AS user_name',
                    'positions.id AS position_id',
                    'recruit_positions.audio_report AS audio_report',
                    'recruit_positions.id AS rp_id');
        }

        $recruit =  $recruits->paginate( $query['rows'] );
        $rows = $recruit->items();

        return json_encode(array(
            "total" => count($rows),
            "totalNotFiltered" => $recruit->total(),
            "rows" => $rows
        ));
    }

    public function editRecruit($id){
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        $recruit = Recruit::where('id' , $id)->get();
        $platforms = $this->platforms();

        //return view('recruit.index',['name'=>$name,'recruits'=> $recruits]);
        return view('recruit.postulant_edit')
            ->with([
                'recruit' => $recruit,
                'platforms' => $platforms,
            ]);
    }

    public function deleteRecruit(Request $request){
        $input = $request->all();
        $recruit_id = $input["recruit_id"];
        $position_id = $input["position_id"];

        RecruitPosition::where('recruit_id' , $recruit_id)->where('position_id' , $position_id)->delete();
    }

    public function updateRecruit(Request $request, $id){
        $validator = $request->validate( [
            'fullname'              => 'required',
            'identification_number' => 'required',
            'platform'              => 'required',
            'phone_number'          => 'required|numeric',
            'email_address'         => 'required',
            'file_path_update'      => 'mimes:pdf,doc,docx|max:2048',
            // 'availability'      => 'date_format:'.config('app.date_format_php'),
            // 'email_address' => 'required|email:rfc,dns'
        ]);

        if( !is_array($validator) ){
            if ($validator->fails()) {
                return back()
                            ->withErrors($validator)
                            ->withInput();
            }
        }

        try {

            $file = $request->file("file_path_update");
            $destinationPath = 'uploads/cv';
            $input = $request->all();
            $newNameFile = '';

            if( $file ){
                $_fileName = "cv-".date("Y-m-d")."-".time().".".$file->getClientOriginalExtension();
                $newNameFile = $destinationPath."/" . $_fileName;
                $path = $request->file("file_path_update")->store("cv" , "s3");
                $path_s3 = Storage::disk("s3")->url($path);
                Storage::disk("s3")->put($path , file_get_contents( $request->file("file_path_update") ) , 'public' );
                Storage::delete( $path );
                $input["file_path"] = $path_s3;
            }

            unset( $input["_token"] );
            
            $recruit = Recruit::where("identification_number" , $input['identification_number'] )->get();

            if( Recruit::where("identification_number" , $input['identification_number'])->count() >=1 ){
                if($recruit[0]->id == $id){
                    Recruit::where('id' , $id)->update($input);
                }else{
                    return redirect()->route('recruit.menu')
                            ->with('error', 'Identification number already exists.');
                }
            }else{
                Recruit::where('id' , $id)->update($input);
            }
            
            if(Auth::check()){
                return redirect()->route('recruit.menu')
                            ->with('success', 'Postulant updated successfully.');
            }else{
                return redirect()->route('recruit.menu')
                            ->with('error', 'Nedd to Log In.');
            }

        } catch (Exception $exception) {
                
            // return $exception->getMessage();
            return back()->withErrors($exception->getMessage())->withInput();
        }
    }

    public function saveRecruit(Request $request){

        $validator = $request->validate( [
            'fullname'              => 'required',
            'identification_number' => 'required',
            'position_id'           => 'required',
            'platform'              => 'required',
            'phone_number'          => 'required|numeric',
            'email_address'         => 'required',
            'file_path'             => 'mimes:pdf,doc,docx|max:2048',
        ]);
        
        if( !is_array($validator) ){
            if ($validator->fails()) {
                return back()
                            ->withErrors($validator)
                            ->withInput();
            }
        }    
        
        try {
            $file = $request->file("file_path");
            $destinationPath = 'uploads/cv';
            $input = $request->all();
            $recruit;
            $newNameFile = '';
            $input["file_path"] = '';
            $isCreated = true;

            if( $file ){
                $_fileName = "cv-".date("Y-m-d")."-".time().".".$file->getClientOriginalExtension();
                $newNameFile = $destinationPath."/" . $_fileName;
                $path = $request->file("file_path")->store("cv" , "s3");
                $path_s3 = Storage::disk("s3")->url($path);
                Storage::disk("s3")->put($path , file_get_contents( $request->file("file_path") ) , 'public' );
                Storage::delete( $path );
                $input["file_path"] = $path_s3;
            }

            $input['id'] = Hashids::encode(time());
            unset( $input["_token"] );
            unset( $input["position_id"] );

            if( Recruit::where("identification_number" , $input['identification_number'])->count() > 0 ){
                $recruit = Recruit::where("identification_number" , $input['identification_number'] )->first();
                $isCreated = true;
            }else{
                Recruit::create($input);
                $isCreated = false;
            }

            if($isCreated){
                if(RecruitPosition::where("recruit_id" , $recruit['id'])->where('position_id', $request->input('position_id'))->count() > 0){
                    return redirect()->route('recruit.menu')
                            ->with('error', 'Already applied for that position.');
                }else{
                    RecruitPosition::create(
                        array(
                            "recruit_id"         =>  $recruit['id'],
                            "position_id"        =>  $request->input('position_id'),
                            "user_id"            =>  Auth::id(),
                        )
                    );
                }
            }else{
                RecruitPosition::create(
                    array(
                        "recruit_id"         =>  $input['id'],
                        "position_id"        =>  $request->input('position_id'),
                        "user_id"            =>  Auth::id(),
                    )
                );
            }
            
            if(Auth::check()){
                return redirect()->route('recruit.menu')
                            ->with('success', 'Postulant applied successfully.');
            }else{
                return redirect()->route('recruit.menu')
                            ->with('error', 'Nedd to Log In.');
            }

        } catch (Exception $exception) {
            return back()->withErrors($exception->getMessage())->withInput();
        }
    }

    public function uploadAudio( Request $request ){

        $input = $request->all();

        $rp_id = $input['rp_id'];
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

            RecruitPosition::where('id' , $rp_id)->update(
                array( "audio_report" => $path_s3 )
            );

            return array(
                "file" => $path_s3,
            );
        }
        
    }

    public function deleteAudio( Request $request ){
        $input = $request->all();
        $rp_id = $input['rp_id'];
        $position_id = $input['position_id'];
        RecruitPosition::where('id' , $rp_id)->update(
            array( "audio_report" => null )
        );
    }

    public function recruitsEvaluateOutstanding(Request $request){
        $input = $request->all();

        $id          = $input['id'];
        $positionid  = $input['positionid'];
        $outstanding = $input['outstanding'];

        RecruitPosition::where('recruit_id' , $id)->where('position_id' , $positionid)->update(
            array("outstanding_report"=>$outstanding)
        );
    }

    public function recruitsEvaluateCall(Request $request){
        $input = $request->all();

        $id          = $input['id'];
        $positionid  = $input['positionid'];
        $phonecall   = $input['phonecall'];

        RecruitPosition::where('recruit_id' , $id)->where('position_id' , $positionid)->update(
            array("call_report"=>$phonecall)
        );
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
