<?php

namespace App\Http\Controllers;

use App\Expert;
use App\Position;
use App\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;

class ExpertController extends Controller
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
        $_experts = $this->visibleExpert( Expert::with(['log'])->latest()->get() );
        $experts = count($_experts);
        return view('experts.index',compact('experts'))
            ->with('technologies',Expert::getTechnologies());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // if(!Auth::check()) return redirect('login');
        // return $request->query('expertId');
        $expert = (object) array();
        if( $request->query('expertId') != "" ){
            
            $expert = Expert::where("id" , $request->query('expertId') )->first();
        }else{
            
            $expert = $this->getModelFormat();
        }

        $positionId = !empty($request->query('positionId')) ? $request->query('positionId') : "";

        $expert->email_address = $request->query('e') !== "" ? base64_decode( $request->query('e') ) : "";
        return view('experts.create' )->with('positionId', $positionId )->with('expert', $expert )->with('technologies',Expert::getTechnologies());
    }

    private function getModelFormat(){
        $expert = new Expert();
        $a = [];
        foreach ($expert->getFillable() as $k => $f) {
            $a[$f] = "";
        }
        return (object) $a;
    }

    public function apply($positionId = null){
        if(empty($positionId)) return redirect('login');
        $position = Position::find($positionId);
        if(is_null($position)) return redirect('login');
        return view('experts.create',compact('position'))->with('technologies',Expert::getTechnologies());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function validateEmail(Request $request ){
        $email = $request->input('email');
        $positionId = $request->input('positionId'); 
        
        if( Expert::where("email_address" , $email)->count() > 0 ){
            
            $expert = Expert::where("email_address" , $email)->first();

            return route( 'experts.create' , [ 'expertId' => $expert->id , "positionId" => $positionId ,"e" => base64_encode($email)] );
        }else{

            return route( 'experts.create' , [ 'expertId' => "" , "positionId" => $positionId , "e" => base64_encode($email)] );
            
        }
    }

    public function store(Request $request)
    {
        //
        try {
            //code...

            $request->validate( [
                'file_cv'           => 'mimes:pdf,doc,docx|max:2048',
                'phone'             => 'required|numeric',
                'birthday'          => 'date_format:'.config('app.date_format_php'),
                'last_info_update'  => 'date_format:'.config('app.date_format_php'),
                'availability'      => 'date_format:'.config('app.date_format_php'),
                // 'email_address' => 'required|email:rfc,dns'
            ]);

            $file = $request->file("file_cv");

            $destinationPath = 'uploads/cv';
        
            $input = $request->all();
            
            $newNameFile = '';

            $input["file_path"] = '';
            $isCreated = true;
            if( $file ){

                $newNameFile = $destinationPath."/" . "cv-".date("Y-m-d")."-".time().".".$file->getClientOriginalExtension();
                $input["file_path"] = $newNameFile;
            }

            if( Auth::check() ){
                $input["user_id"] = Auth::id();
                $input["user_name"] = Auth::user()->name;
            }
            $signed = null;
            $logId = null;
            if( isset( $input["signed"] ) && isset( $input["log"] ) ){
                
                $signed = $input["signed"];
                $logId = $input["log"];

                unset( $input["signed"] );
                unset( $input["log"] );
            }
            

            $input['fullname'] = ucwords(substr( $input['fullname'] , 0 , 244));
            $input['email_address'] = substr( $input['email_address'] , 0 , 244);
            $input['education'] = substr( $input['education'] , 0 , 244);
            $input['address'] = substr( $input['address'] , 0 , 244);
            $input['identification_number'] = substr( $input['identification_number'] , 0 , 244);
            $input['phone'] = substr( $input['phone'] , 0 , 244);

            if(isset($input['result1']) ) $input['result1'] = substr( $input['result1'] , 0 , 244);
            if(isset($input['result2']) ) $input['result2'] = substr( $input['result2'] , 0 , 244);
            if(isset($input['result3']) ) $input['result3'] = substr( $input['result3'] , 0 , 244);
    
            if( Expert::where("email_address" , $input['email_address'])->count() > 0 ){
                unset( $input["_token"] );
                unset( $input["position"] );
                unset( $input["file_cv"] );
                
                if( isset($input['last_info_update']) ) $input['last_info_update'] = date("Y-m-d H:i:s" , strtotime($input['last_info_update']));
                if( isset($input['availability']) ) $input['availability'] = date("Y-m-d H:i:s" , strtotime($input['availability']));
                if( isset($input['birthday']) ) $input['birthday'] = date("Y-m-d H:i:s" , strtotime($input['birthday']));
               
                Expert::where("email_address" , $input['email_address'])->update($input);
                $isCreated = false;
            }else{
                $input['id'] = Hashids::encode(time());
                $expert = Expert::create($input);
                $isCreated = true;
            }

            if( $file ){
                $file->move( $destinationPath, $newNameFile );
            }
            
            $positionId = $request->input('position','');
            if(!empty($positionId) && $isCreated){
                $position = Position::find($positionId);
                // $expert->positions()->attach($position);

                Log::create(
                    array(
                        "expert_id"     =>  $input["id"],
                        "position_id"   =>  $positionId,
                        "form"          =>  1
                    )
                );
            }
    
            if(Auth::check()){
                return redirect()->route('experts.home')
                            ->with('success', $isCreated ? 'Expert created successfully.' : 'Expert updated successfully.');
            }else{
                return redirect()->route('positions.index')
                            ->with('success', $isCreated ? 'Expert created successfully.' : 'Expert updated successfully.');
            }

        } catch (Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expert  $expert
     * @return \Illuminate\Http\Response
     */
    public function show(Expert $expert)
    {
        //
        if(!Auth::check()) return redirect('login');
        return view('experts.show',compact('expert'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expert  $expert
     * @return \Illuminate\Http\Response
     */
    public function edit(Expert $expert)
    {
        //
        if(!Auth::check()) return redirect('login');
        return view('experts.edit')->with('expert',$expert)->with('technologies',Expert::getTechnologies());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expert  $expert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expert $expert)
    {
        //
        // if(!Auth::check() && !$request->hasValidSignature()) return redirect('login');
        try {
            $request->validate([
                'file_cv' => 'mimes:pdf,doc,docx|max:2048',
                'phone' => 'required|numeric',
                'birthday'          => 'date_format:'.config('app.date_format_php'),
                'last_info_update'  => 'date_format:'.config('app.date_format_php'),
                'availability'      => 'date_format:'.config('app.date_format_php'),
                // 'email_address' => 'required|email:rfc,dns'
            ]);

            $file = $request->file("file_cv");

            $destinationPath = 'uploads/cv';
        
            $input = $request->all();
            
            $newNameFile = '';

            $input["file_path"] = '';
            if( $file ){

                $newNameFile = $destinationPath."/" . "cv-".date("Y-m-d")."-".time().".".$file->getClientOriginalExtension();
                $input["file_path"] = $newNameFile;

            }

            unset( $input["_token"] );
            unset( $input["file_cv"] );

            $input['fullname'] = ucwords(substr( $input['fullname'] , 0 , 244));
            $input['email_address'] = substr( $input['email_address'] , 0 , 244);
            $input['education'] = substr( $input['education'] , 0 , 244);
            $input['address'] = substr( $input['address'] , 0 , 244);
            $input['identification_number'] = substr( $input['identification_number'] , 0 , 244);
            $input['phone'] = substr( $input['phone'] , 0 , 244);
            if(isset($input['result1']) ) $input['result1'] = substr( $input['result1'] , 0 , 244);
            if(isset($input['result2']) ) $input['result2'] = substr( $input['result2'] , 0 , 244);
            if(isset($input['result3']) ) $input['result3'] = substr( $input['result3'] , 0 , 244);
      
            $expert->update( $input );

            if( $file ){
                $file->move( $destinationPath, $newNameFile );
            }

            if( isset($input['position']) ){
                Log::where('expert_id' , $expert->id)->update(
                    array(
                        'form' => 1
                    )
                );
            }
      
            return redirect()->route('experts.home')
                            ->with('success','Expert updated successfully');

        } catch (Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expert  $expert
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expert $expert)
    {
        //
        $expert->positions()->detach();
        $expert->delete();
  
        return redirect()->route('experts.home')
                        ->with('success','Expert deleted successfully');
    }

    public function filter(Request $request)
    {
        if(!Auth::check()) return redirect('login');

        $basic = $request->input('basic_level',array());
        $intermediate = $request->input('intermediate_level',array());
        $advanced = $request->input('advanced_level',array());

        // return array($basic,$intermediate,$advanced);

        $basic_array = array();
        $intermediate_array = array();
        $advanced_array = array();

        //BASIC
        $thewholequery = null; 
        foreach($basic as $techid){
            if(empty($thewholequery)) {
                $thewholequery = Expert::with(['log'])->where(function($query) use($techid){
                    $query->where($techid,'basic')->orWhere($techid,'intermediate')->orWhere($techid,'advanced');  
                });
            }else{
                $thewholequery = $thewholequery->where(function($query) use($techid){
                    $query->where($techid,'basic')->orWhere($techid,'intermediate')->orWhere($techid,'advanced');  
                });
            }
            $basic_array[$techid] = self::getTechLabel($techid);
        }   
        foreach($intermediate as $techid){
            if(empty($thewholequery)) {
                $thewholequery = Expert::with(['log'])->where(function($query) use($techid){
                    $query->where($techid,'intermediate')->orWhere($techid,'advanced');  
                });
            }else{
                $thewholequery = $thewholequery->where(function($query) use($techid){
                    $query->where($techid,'intermediate')->orWhere($techid,'advanced');  
                });
            }
            $intermediate_array[$techid] = self::getTechLabel($techid);
        }
        foreach($advanced as $techid){
            if(empty($thewholequery)) {
                $thewholequery = Expert::with(['log'])->where(function($query) use($techid){
                    $query->where($techid,'advanced');  
                });
            }else{
                $thewholequery = $thewholequery->where(function($query) use($techid){
                    $query->where($techid,'advanced');  
                });
            }
            $advanced_array[$techid] = self::getTechLabel($techid);
        }

        $experts = Expert::with(['log'])->latest()->get();
        if(!empty($thewholequery)) $experts = $thewholequery->get();

        // return view('experts.index')->with('experts',$experts)
        //     ->with('i', (request()->input('page', 1) - 1) * 10)
        //     ->with('technologies',Expert::getTechnologies())
        //     ->with('basic',$basic_array)
        //     ->with('intermediate',$intermediate_array)
        //     ->with('advanced',$advanced_array);

        return response()->json($this->visibleExpert($experts));
    }

    private function visibleExpert($_experts){
        $_n = array();

        foreach ($_experts as $key => $_expert) {
            if( is_null($_expert->log) || $_expert->log->form == 1 ){
                $_n[] = $_expert;
            }
        }
        return $_n;
    }

    public function techs(Request $request){

        $search = $request->query('search','');
        $start = $request->query('start','0');
        $techs = array();
        foreach(Expert::getTechnologies() as $catid => $cat){
            foreach($cat[1] as $techid => $techlabel){
                if(preg_match('/' . ($start ? '^' : '') . $search . '/i', $techlabel) || preg_match('/' . ($start ? '^' : '') . $search . '/i', $techid)){
                    $techs[] = array ('value'=>$techid,'text'=>$techlabel);
                }                
            }
        }

        return response()->json($techs);
    }

    public function technologies(Request $request){
        $search = $request->query('search','');
        $start = $request->query('start','0');
        $techs = array();
        foreach(Expert::getTechnologies() as $catid => $cat){
            foreach($cat[1] as $techid => $techlabel){
                if(preg_match('/' . ($start ? '^' : '') . $search . '/i', $techlabel) || preg_match('/' . ($start ? '^' : '') . $search . '/i', $techid)){
                    $techs[] = array ('id'=>$techid,'text'=>$techlabel);
                }                  
            }
        }

        return response()->json($techs);
    }

    public static function getTechLabel($id){
        foreach(Expert::getTechnologies() as $catid => $cat){
            foreach($cat[1] as $techid => $techlabel){
                if($id==$techid) return $techlabel;
            }
        }
        return '';
    }

    public function developerEditSigned($expertId) {
        $query = array(
            'expertId' => $expertId 
        );
        if( Log::where('expert_id' , $expertId)->count() > 0 ){
            $query['position'] = time();
        }

        return URL::temporarySignedRoute(
            'developer.edit', now()->addDays(7), $query
        );
    }

    public function developerEdit(Request $request , $expertId){

        if(!Auth::check() && !$request->hasValidSignature()) return redirect('login');

        $expert = Expert::find($expertId);
        $position = 0;
        if( !is_null( $request->query('position') ) ){
            $position = 1;
        } 

        $disabledInputs = array(
            ''
        );

        return view('experts.edit')->with('expert', $expert)->with('position', $position)->with('technologies',Expert::getTechnologies());
    }

    public function applicantRegisterSigned() {
        return URL::temporarySignedRoute(
            'applicant.register', now()->addDays(7)
        );
        
    }

    public function applicantRegister(){
        $expert = $this->getModelFormat();
        return view('experts.create')->with('positionId', '' )->with('expert', $expert)->with('technologies',Expert::getTechnologies());
    }

    public function positions($expertId){
        return response()->json($expert);
    }

    public function isSlug($slug){

        $positions = Position::all();
        $is = false;
        $p = array();
        foreach ($positions as $key => $p) {
            if($slug === $p->slug){
                $position = $p;$is = true;break;
            }
        }

        if($is){

            $expert = $this->getModelFormat();
        
            return view('experts.create' )->with('positionId', $position->id )->with('positionName' , $position->name )->with('expert', $expert )->with('technologies',Expert::getTechnologies());
        }else{
            abort(404);
        }
    }

    public function log( Request $request ){

        $input = $request->all();

        $expertId = $input['id'];
        $expert = Expert::where('id' , $expertId)->first();
        $logId = Hashids::encode(time());

        if( !empty( $expert ) ){
            $log = array(
                'id'        => $logId,
                'name'      => $expert->fullname,
                'phone'     => $expert->phone,
                // 'positions' => '',
                'form'      => 1,
                'user_id'   => Auth::id(),
            );
    
            $created = Log::create( $log );
    
            Expert::where('id' , $expertId)->update( array('log_id' => $logId) );

            return $created;
        }

        return 0;
    }

    public function searchbyname( Request $request ){

        $input = $request->all();
        $fullname = $input['name'];
        $experts = Expert::with(['log'])->where('fullname' , 'like' , '%'.$fullname.'%')->orderBy('created_at')->get();

        return response()->json( $this->visibleExpert($experts));

    }

}
