<?php

namespace App\Http\Controllers;

use App\Expert;
use App\Position;
use App\Portfolio;
use App\Log;
use App\Interview;
use App\Portfolioexpert;
use App\Recruiterlog;
use App\Expertlog;
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

class ExpertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index( Request $request )
    {
        
        if(!Auth::check()) return redirect('login');
        $_experts = $this->visibleExpert( Expert::with(['logs'])->latest()->get() );
        $experts = count($_experts);
        $query = $request->query();

        $search = isset( $query['search'] )? true : false;
        $a_basic = isset( $query['basic'] )? explode(",", $query['basic']) : array();
        $a_inter = isset( $query['intermediate'] )? explode(",", $query['intermediate']) : array();
        $a_advan = isset( $query['advanced'] )? explode(",", $query['advanced']) : array();
        $name = isset( $query['name'] )? $query['name'] : '';
        $audio = isset( $query['audio'] )? filter_var($query['audio'] , FILTER_VALIDATE_BOOLEAN) : true;
        $selection = isset( $query['selection'] )? $query['selection'] : 1;
        
        $basic = array();
        $intermediate = array();
        $advanced = array();

        foreach(Expert::getTechnologies() as $catid => $cat){
            foreach($cat[1] as $techid => $techlabel){
                if( in_array( $techid , $a_basic ) ) $basic = array_merge( $basic, array( $techid => $techlabel ));
                if( in_array( $techid , $a_inter ) ) $intermediate = array_merge( $intermediate, array( $techid => $techlabel ));
                if( in_array( $techid , $a_advan ) ) $advanced = array_merge( $advanced ,array( $techid => $techlabel ));
            }
        }
        
        return view('experts.index',compact('experts'))
            ->with('search', $search )
            ->with('audio', $audio )
            ->with('selection', $selection )
            ->with('name', $name )
            ->with('basic', $basic )
            ->with('intermediate', $intermediate )
            ->with('advanced', $advanced )
            ->with('technologies', Expert::getTechnologies() );
    }

    public function listjqgrid(Request $request){

        $query = $request->query();

        $basic = array();
        $intermediate = array();
        $advanced = array();

        if( isset( $query['basic'] ) ) $basic = $query['basic'] != ''? explode("," , $query['basic']) : array()  ;
        if( isset( $query['intermediate'] ) ) $intermediate = $query['intermediate'] != ''? explode("," , $query['intermediate']) : array()  ;
        if( isset( $query['advanced'] ) ) $advanced = $query['advanced'] != ''? explode("," , $query['advanced']) : array()  ;

        $experts = $this->filter($basic , $intermediate, $advanced);
        $experts->where('fullname' , 'like' , '%'.$query['name'].'%');
        $expert =  $experts->paginate( $query['rows'] );

        // return $expert;
        return json_encode(array(
            "page"      => $expert->currentPage(),
            "total"     => $expert->lastPage(),
            "records"   => $expert->total(),
            "rows"      => $expert->items()
        ));
    }

    public function listtbootstrap(Request $request){
        $query = $request->query();

        $basic = array();
        $intermediate = array();
        $advanced = array();

        if( isset( $query['basic'] ) ) $basic = $query['basic'] != ''? explode("," , $query['basic']) : array()  ;
        if( isset( $query['intermediate'] ) ) $intermediate = $query['intermediate'] != ''? explode("," , $query['intermediate']) : array()  ;
        if( isset( $query['advanced'] ) ) $advanced = $query['advanced'] != ''? explode("," , $query['advanced']) : array()  ;
        $experts = null;
        
        $experts = $this->filter($basic , $intermediate, $advanced);

        $experts->where('experts.fullname' , 'like' , '%'.$query['name'].'%');

        if( $query['selection'] != 1  ){
            $experts->where('experts.selection' , intval( $query['selection']  ) );
        }

        if( filter_var($query['audio'] , FILTER_VALIDATE_BOOLEAN)  ){
            $experts
                ->distinct()
                ->leftJoin('expert_log' , 'experts.id' , '=' , 'expert_log.expert_id')
                ->join('recruiter_logs' , 'recruiter_logs.id' , '=' , 'expert_log.log_id')
                ->whereNotNull( 'recruiter_logs.filter_audio' )
                ->orWhereNotNull( 'recruiter_logs.evaluate_audio' )
                ->select('experts.*');
        }
        
        
        $expert =  $experts->paginate( $query['rows'] );
        $rows = $expert->items();

        return json_encode(array(
            "total" => count($rows),
            "totalNotFiltered" => $expert->total(),
            "rows" => $rows
        ));
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
        $position = (object) array();
        if( !empty($request->query('positionId')) ){
            
            $position = Position::find( $request->query('positionId') );
        }
        
        $positionId = !empty($request->query('positionId')) ? $request->query('positionId') : "";

        $expert->email_address = $request->query('e') !== "" ? base64_decode( $request->query('e') ) : "";
        return view('experts.create' )->with('position', $position )->with('expert', $expert )->with('technologies',Expert::getTechnologies());
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
        
        if( Expert::where("email_address" , $email)->count() > 0 && $email != '' ){
            
            $expert = Expert::where("email_address" , $email)->first();

            return route( 'experts.create' , [ 'expertId' => $expert->id , "positionId" => $positionId ,"e" => base64_encode($email)] );
        }else{

            return route( 'experts.create' , [ 'expertId' => "" , "positionId" => $positionId , "e" => base64_encode($email)] );
            
        }
    }

    public function store(Request $request)
    {

        $validator = $request->validate( [
            'file_cv'           => 'mimes:pdf,doc,docx|max:2048',
            'phone'             => 'required|numeric',
            'birthday'          => 'date_format:'.config('app.date_format_php'),
            'last_info_update'  => 'date_format:'.config('app.date_format_php'),
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
            //code...

            $file = $request->file("file_cv");

            $destinationPath = 'uploads/cv';
        
            $input = $request->all();
            
            $newNameFile = '';

            $input["file_path"] = '';
            $isCreated = true;
            if( $file ){
                $_fileName = "cv-".date("Y-m-d")."-".time().".".$file->getClientOriginalExtension();
                $newNameFile = $destinationPath."/" . $_fileName;
                
                $path = $request->file("file_cv")->store("cv" , "s3");
            
                $path_s3 = Storage::disk("s3")->url($path);

                Storage::disk("s3")->put($path , file_get_contents( $request->file("file_cv") ) , 'public' );

                Storage::delete( $path );

                $input["file_path"] = $path_s3;
                
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

            $portfolio_link = isset( $input['link'] )? $input['link'] : array();
            $portfolio_description = isset( $input['description'] )? $input['description'] : array();
            
            unset( $input['link'] );
            unset( $input['description'] );

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
                // if( isset($input['availability']) ) $input['availability'] = date("Y-m-d H:i:s" , strtotime($input['availability']));
                if( isset($input['birthday']) ) $input['birthday'] = date("Y-m-d H:i:s" , strtotime($input['birthday']));
               
                Expert::where("email_address" , $input['email_address'])->update($input);
                $isCreated = false;
            }else{
                $input['id'] = Hashids::encode(time());
                $expert = Expert::create($input);
                $isCreated = true;
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

            if( count($portfolio_description) > 0 && $isCreated ){
                Portfolio::where('expert_id' , $input['id'] )->delete();
                foreach ( $portfolio_link as $key => $p ) {
                    Portfolio::create(array(
                        "expert_id" => $input['id'],
                        "link" => $p,
                        "description" => $portfolio_description[$key]
                    )); 
                }
            }
    
            if(Auth::check()){
                return redirect()->route('experts.home')
                            ->with('success', $isCreated ? 'Expert created successfully.' : 'Expert updated successfully.');
            }else{
                return redirect()->route('experts.confirmation');
                            
            }

        } catch (Exception $exception) {
            
            // return $exception->getMessage();
            return back()->withErrors($exception->getMessage())->withInput();
        }
        
        
    }

    public function confirmation(){
        return view('experts.confirmation');
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
        // return $expert;
        $portfolios = Portfolio::where('expert_id' , $expert->id )->get();
        return view('experts.edit')
            ->with('expert',$expert)
            ->with('portfolios',$portfolios)
            ->with('technologies',Expert::getTechnologies());
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

        $validator = $request->validate( [
            'file_cv'           => 'mimes:pdf,doc,docx|max:2048',
            'phone'             => 'required|numeric',
            'birthday'          => 'date_format:'.config('app.date_format_php'),
            'last_info_update'  => 'date_format:'.config('app.date_format_php'),
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
            
            $file = $request->file("file_cv_update");

            $destinationPath = 'uploads/cv';
        
            $input = $request->all();
            
            $newNameFile = '';

            if( $file ){

                $_fileName = "cv-".date("Y-m-d")."-".time().".".$file->getClientOriginalExtension();
                $newNameFile = $destinationPath."/" . $_fileName;
                    
                $path = $request->file("file_cv_update")->store("cv" , "s3");
        
                $path_s3 = Storage::disk("s3")->url($path);

                Storage::disk("s3")->put($path , file_get_contents( $request->file("file_cv_update") ) , 'public' );

                Storage::delete( $path );

                $input["file_path"] = $path_s3;
                
            
            }

            $portfolio_link = isset( $input['link'] )? $input['link'] : array();
            $portfolio_description = isset( $input['description'] )? $input['description'] : array();
            
            unset( $input['link'] );
            unset( $input['description'] );

            unset( $input["_token"] );
            unset( $input["file_cv_update"] );

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

            if( isset($input['position']) ){
                Log::where('expert_id' , $expert->id)->update(
                    array(
                        'form' => 1
                    )
                );
            }

            if( count($portfolio_description) > 0  ){
                Portfolio::where('expert_id' , $expert->id )->delete();
                foreach ( $portfolio_link as $key => $p ) {
                    Portfolio::create(array(
                        "expert_id" => $expert->id,
                        "link" => $p,
                        "description" => $portfolio_description[$key]
                    )); 
                }
            }
      
            return redirect()->route('experts.home')
                            ->with('success','Expert updated successfully');

        } catch (Exception $exception) {
            return back()->withErrors($exception->getMessage())->withInput();
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

    public function deleteExpert(Request $request){

        $id = $request->input('expertId');

        Interview::where('expert_id' , $id)->delete();

        Expert::where('id' , $id)->delete();

    }

    public function selectionExpert( Request $request){
        $id = $request->input('expertId');
        $selection = $request->input('selection');

        Expert::where('id' , $id)->update(array(
            "selection" => intval($selection)
        ));

        return response()->json(array(
            "selection" => $selection
        ));
    }

    private function filter($basic = array() , $intermediate = array() , $advanced = array() )
    {
        if(!Auth::check()) return redirect('login');

        // return array($basic,$intermediate,$advanced);

        //BASIC
        $thewholequery = null; 
        foreach($basic as $techid){
            if(empty($thewholequery)) {
                $thewholequery = Expert::with(['logs'])->where(function($query) use($techid){
                    $query->where($techid,'basic')->orWhere($techid,'intermediate')->orWhere($techid,'advanced');  
                });
            }else{
                $thewholequery = $thewholequery->where(function($query) use($techid){
                    $query->where($techid,'basic')->orWhere($techid,'intermediate')->orWhere($techid,'advanced');  
                });
            }

        }   
        foreach($intermediate as $techid){
            if(empty($thewholequery)) {
                $thewholequery = Expert::with(['logs'])->where(function($query) use($techid){
                    $query->where($techid,'intermediate')->orWhere($techid,'advanced');  
                });
            }else{
                $thewholequery = $thewholequery->where(function($query) use($techid){
                    $query->where($techid,'intermediate')->orWhere($techid,'advanced');  
                });
            }

        }
        foreach($advanced as $techid){
            if(empty($thewholequery)) {
                $thewholequery = Expert::with(['logs'])->where(function($query) use($techid){
                    $query->where($techid,'advanced');  
                });
            }else{
                $thewholequery = $thewholequery->where(function($query) use($techid){
                    $query->where($techid,'advanced');  
                });
            }

        }

        $experts = Expert::with(['logs'])->latest();
        if(!empty($thewholequery)) $experts = $thewholequery;

        // return view('experts.index')->with('experts',$experts)
        //     ->with('i', (request()->input('page', 1) - 1) * 10)
        //     ->with('technologies',Expert::getTechnologies())
        //     ->with('basic',$basic_array)
        //     ->with('intermediate',$intermediate_array)
        //     ->with('advanced',$advanced_array);

        return $experts;
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


        $portfolios = Portfolio::where('expert_id' , $expert->id )->get();

        return view('experts.edit')
            ->with('expert', $expert)
            ->with('position', $position)
            ->with('portfolios', $portfolios)
            ->with('technologies',Expert::getTechnologies());
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
        
            return view('experts.create' )->with('position', $position )->with('expert', $expert )->with('technologies',Expert::getTechnologies());
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

        $input = $request->query();
        $fullname = $input['name'];
        $experts = Expert::where('fullname' , 'like' , '%'.$fullname.'%')->orderBy('created_at')->get();

        return response()->json( $experts );

    }

    public function portfolioResume( Request $request ){
        
        if(!Auth::check()) return redirect('login');
        return view('portfolio.index' );

    }

    public function portfolioResumeList( Request $request ){
        $resumes = Portfolioexpert::with(['expert','user'])->latest()->get();

        return $resumes;
    }

    public function portfolioSave( Request $request ){

        $expertId = $request->input('expertId');

        $count = Portfolioexpert::where( 'expert_id' , $expertId )->count();

        $expert = array();

        if( $count == 0 ){

            $_expert = Expert::where( 'id' , $expertId )->first();

            if( empty( $_expert ) ) abort(404);

            $skills_ad = array();
            $skills_in = array();
            foreach(Expert::getTechnologies() as $catid => $cat){
                foreach($cat[1] as $techid => $techlabel){
                    if( !is_null($_expert[$techid]) 
                    && $_expert[$techid] != 'unknown' 
                    && $_expert[$techid] != 'basic' 
                    && !in_array( $techid , array('english_speaking','english_writing','english_reading') ) ){
                        if( $_expert[$techid] == 'advanced' ){
                            $skills_ad[] = array(
                                "skill" => $techlabel,
                                "id"    => $techid,
                                "value" => $_expert[$techid]
                            );
                        }else{
                            $skills_in[] = array(
                                "skill" => $techlabel,
                                "id"    => $techid,
                                "value" => $_expert[$techid]
                            );
                        }
                    }
                }
            }

            $skills = array_merge($skills_ad, $skills_in);

            Portfolioexpert::create(
                array(
                    "expert_id" => $expertId,
                    'fullname'  => $_expert->fullname,
                    'work'      => $_expert->focus,
                    'age'       => $_expert->age,
                    'email'     => $_expert->email_address,
                    'address'   => $_expert->address,
                    'github'    => $_expert->github,
                    'linkedin'  => $_expert->linkedin,
                    'facebook'  => $_expert->facebook,
                    'skills'    => serialize($skills),
                    'slug'      => preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower( $_expert->fullname )),
                    'user_id'   => Auth::id(),
                )
            );

        }

    }

    public function portfolioForm( $id ){

        $expert = Portfolioexpert::where( 'id' , $id )->first();

        return view('portfolio.form' )
            ->with('expert', $expert );
    }

    public function portfolioPreview( $slug ){

        $expert = Portfolioexpert::where( 'slug' , $slug )->first();

        return view('portfolio.template' )
            ->with('expert', $expert );

    }   

    public function saveportfolio( Request $request ){

        $input = $request->all();
        
        $input["projects"] = serialize($this->parsePorjects( $input ));

        $input["education"] = serialize($this->parseEducations( $input ));

        $input["employment"] = serialize($this->parseEmployments( $input ));

        $input["skills"] = serialize($this->parseSkills( $input ));

        // return $input;
        Portfolioexpert::where( 'id' , $input['id'] )->update(
            array(
                "fullname" => $input["fullname"],
                "work" => $input["work"],
                "age" => $input["age"],
                "slug" => $input["slug"],
                "email" => $input["email"],
                "address" => $input["address"],
                "availability" => $input["availability"],
                "github" => $input["github"],
                "linkedin" => $input["linkedin"],
                "photo" => $input["photo"],
                "description" => $input["description"],
                "education" => $input["education"],
                "employment" => $input["employment"],
                "skills" => $input["skills"],
                "projects" => $input["projects"],
                
            )
        );

        return redirect()->route('expert.portfolio.resume')
                            ->with('success', 'Expert updated successfully.');

    }

    public function imageproject( Request $request ){

        $file = $request->file("file");

        $destinationPath = 'uploads/projects';
        
        $newNameFile = '';

        if( $file ){

            $nameFile = time().".".$file->getClientOriginalExtension();

            $newNameFile = $destinationPath."/" . $nameFile;
            
            $file->move( $destinationPath, $newNameFile );

            echo $nameFile;
        }
        
        echo '';
    }

    public function deleteResume( Request $request ){
        $input = $request->all();
        Portfolioexpert::where( 'id' , $input['id'] )->delete();
    }

    public function listaudios( Request $request ){
        $input = $request->all();

        $logs = Expertlog::with('log' , 'expert')->where('expert_id' , $input['id'])->get();
        $array = array();
        foreach ($logs as $key => $log) {
            $position = null;
            if( isset($log->log->position_id) ){
                if( $log->log->position_id != null && $log->log->position_id != '' ){

                    $position = Position::find( $log->log->position_id );
                    
                }
            }
            
            if( $log->log->filter_audio != null ){
                $array[] = (object) array(
                    "expert_id" => $input['id'],
                    "position_name" => $position != null ? $position->name : "None",
                    "type" => "Filter",
                    "audio" => $log->log->filter_audio
                ); 
            }
            if( $log->log->evaluate_audio != null ){
                $array[] = (object) array(
                    "expert_id" => $input['id'],
                    "position_name" => $position != null ? $position->name : "None",
                    "type" => "Evaluate",
                    "audio" => $log->log->evaluate_audio
                ); 
            }
        }

        return $array;
    }

    public function listFce( Request $request ){

        if(!Auth::check()) return redirect('login');
        
        $query = $request->query();

        
        $name = isset( $query['name'] )? $query['name'] : '';

        return view('fce.index')
            ->with('name', $name );
    }

    public function listfcebootstratp( Request $request ){
        
        $query = $request->query();

        $experts = null;
        
        $experts = $this->filter(array() , array(), array());

        $experts->where('experts.fullname' , 'like' , '%'.$query['name'].'%');

        $experts->where('experts.fce_total' , '=' , 0);

        if( filter_var($query['audio'] , FILTER_VALIDATE_BOOLEAN)  ){
            $experts
                ->distinct()
                ->leftJoin('expert_log' , 'experts.id' , '=' , 'expert_log.expert_id')
                ->join('recruiter_logs' , 'recruiter_logs.id' , '=' , 'expert_log.log_id')
                ->whereNotNull( 'recruiter_logs.filter_audio' )
                ->orWhereNotNull( 'recruiter_logs.evaluate_audio' )
                ->select('experts.*');
        }
        
        $expert =  $experts->paginate( $query['rows'] );
        $rows = $expert->items();

        return json_encode(array(
            "total" => count($rows),
            "totalNotFiltered" => $expert->total(),
            "rows" => $rows
        ));

    }

    public function getFce( Request $request ){
        $input = $request->all();

        $logs = Expertlog::with('log' , 'expert')->where('expert_id' , $input['expertId'])->get();
        $array = array();
        foreach ($logs as $key => $log) {
            $position = null;
            if( isset($log->log->position_id) ){
                if( $log->log->position_id != null && $log->log->position_id != '' ){

                    $position = Position::find( $log->log->position_id );
                    
                }
            }
            
            if( $log->log->filter_audio != null ){
                $array[] = (object) array(
                    "expert_id" => $input['expertId'],
                    "position_name" => $position != null ? $position->name : "None",
                    "type" => "Filter",
                    "audio" => $log->log->filter_audio
                ); 
            }
            if( $log->log->evaluate_audio != null ){
                $array[] = (object) array(
                    "expert_id" => $input['expertId'],
                    "position_name" => $position != null ? $position->name : "None",
                    "type" => "Evaluate",
                    "audio" => $log->log->evaluate_audio
                ); 
            }
        }

        $fces = Expert::find($input['expertId']);
    
        return array(
            "audios" => $array,
            "fces"   => $fces
        );
    }

    public function saveFce( Request $request ){
        $input = $request->all();

        $input['grammar_vocabulary'] = ( Expert::getFceValue($input['grammatical_forms']) + Expert::getFceValue($input['vocabulary']) ) / 2;
        $input['discourse_management'] = ( Expert::getFceValue($input['stretch_language']) + Expert::getFceValue($input['cohesive_devices']) + Expert::getFceValue($input['hesitation']) + Expert::getFceValue($input['organizations_ideas']) ) / 4;
        $input['pronunciation'] = ( Expert::getFceValue($input['intonation']) + Expert::getFceValue($input['phonological_features']) + Expert::getFceValue($input['intelligible']) ) / 3;
        $input['interactive_communication'] = Expert::getFceValue($input['interaction']);
        
        $input['fce_total'] = ($input['grammar_vocabulary'] + $input['discourse_management'] + $input['pronunciation'] + $input['interactive_communication']) / 4;
        $input['fce_overall'] = Expert::calculateOveral($input['fce_total']);
        
        $id = $input['expert_id'];
        unset( $input['expert_id'] );
        $save = Expert::where( 'id' , $id )->update( 
            $input
        );
    
        return $input;
    }

    private function parsePorjects( $_array ){

        $len = count($_array['project_index']);
        $projects = array();
        for ($i=0; $i < $len; $i++) { 
            if( !is_null( $_array['project_title'][$i] ) ){
                $projects[] = array(
                    "index"         => isset( $_array['project_index'][$i] )? $_array['project_index'][$i] : null,
                    "title"         => isset( $_array['project_title'][$i] )? $_array['project_title'][$i] : null,
                    "videos"        => isset( $_array['project_video_'. $_array['project_index'][$i] ] )? $_array['project_video_'. $_array['project_index'][$i] ] : array(),
                    "images"        => isset( $_array['project_image_name_'. $_array['project_index'][$i] ] )? $_array['project_image_name_'. $_array['project_index'][$i] ] : array(),
                    "description"   => isset( $_array['project_description'][$i] )? $_array['project_description'][$i] : null,
                    "categories"    => isset( $_array['project_categories_'.$_array['project_index'][$i]  ] )? $_array['project_categories_'.$_array['project_index'][$i]  ] : null,
                    "stacks"        => isset( $_array['project_stacks_'.$_array['project_index'][$i] ] )? $_array['project_stacks_'.$_array['project_index'][$i] ] : null,
                    "url"           => isset( $_array['project_url'][$i] )? $_array['project_url'][$i] : null,
                );
            }
            
        }

        return $projects;

    }

    private function parseEducations( $_array ){

        $len = count($_array['education_university']);
        $educations = array();
        for ($i=0; $i < $len; $i++) { 
            if( !is_null($_array['education_university'][$i]) ){
                $educations[] = array(
                    "university"    => isset($_array['education_university'][$i])? $_array['education_university'][$i] : null,
                    "period"     => isset($_array['education_period'][$i])? $_array['education_period'][$i] : null,
                    "description"    => isset($_array['education_description'][$i])? $_array['education_description'][$i] : null,
                );
            }
            
        }

        return $educations;
    }

    private function parseEmployments( $_array ){

        $len = count($_array['employment_workplace']);
        $employments = array();
        for ($i=0; $i < $len; $i++) { 
            if( !is_null($_array['employment_workplace'][$i]) ){
                $employments[] = array(
                    "workplace"     => isset($_array['employment_workplace'][$i])? $_array['employment_workplace'][$i] : null,
                    "period"     => isset($_array['employment_period'][$i])? $_array['employment_period'][$i] : null,
                    "occupation"    => isset($_array['employment_occupation'][$i])? $_array['employment_occupation'][$i] : null,
                );
            }
            
        }

        return $employments;
    }

    private function parseSkills( $_array ){

        $len = count($_array['skills_skill']);
        $skills = array();
        for ($i=0; $i < $len; $i++) { 
            if( !is_null($_array['skills_skill'][$i]) ){
                $skills[] = array(
                    "skill" => $_array['skills_skill'][$i],
                    "value" => $_array['skills_value'][$i],
                );
            }
            
        }

        return $skills;
    }

    

}
