<?php

namespace App\Http\Controllers;

use App\Expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

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
        $experts = Expert::latest()->paginate(5);
  
        return view('experts.index',compact('experts'))
            ->with('i', (request()->input('page', 1) - 1) * 5)->with('technologies',Expert::getTechnologies());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mode = 'recruiter';
        if(!Auth::check()) $mode = 'new-expert';
        return view('experts.create',compact('mode'))->with('technologies',Expert::getTechnologies());
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
        $request->validate([]);
  
        Expert::create($request->all());
   
        return redirect()->route('experts.index')
                        ->with('success','Expert created successfully.');
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
        if(!Auth::check() && !$request->hasValidSignature()) return redirect('login');
        $request->validate([]);
  
        $expert->update($request->all());
  
        return redirect()->route('experts.index')
                        ->with('success','Expert updated successfully');
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
        $expert->delete();
  
        return redirect()->route('experts.index')
                        ->with('success','Expert deleted successfully');
    }

    public function filter(Request $request)
    {
        if(!Auth::check()) return redirect('login');
        $basic = $request->input('basic_level',array());
        $intermediate = $request->input('intermediate_level',array());
        $advanced = $request->input('advanced_level',array());

        $basic_array = array();
        $intermediate_array = array();
        $advanced_array = array();

        //BASIC
        $thewholequery = null; 
        foreach($basic as $techid){
            if(empty($thewholequery)) {
                $thewholequery = Expert::where(function($query) use($techid){
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
                $thewholequery = Expert::where(function($query) use($techid){
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
                $thewholequery = Expert::where(function($query) use($techid){
                    $query->where($techid,'advanced');  
                });
            }else{
                $thewholequery = $thewholequery->where(function($query) use($techid){
                    $query->where($techid,'advanced');  
                });
            }
            $advanced_array[$techid] = self::getTechLabel($techid);
        }

        $experts = array();
        if(!empty($thewholequery)) $experts = $thewholequery->paginate(10);

        return view('experts.index')->with('experts',$experts)
            ->with('i', (request()->input('page', 1) - 1) * 10)
            ->with('technologies',Expert::getTechnologies())
            ->with('basic',$basic_array)
            ->with('intermediate',$intermediate_array)
            ->with('advanced',$advanced_array);
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

    public static function getTechLabel($id){
        foreach(Expert::getTechnologies() as $catid => $cat){
            foreach($cat[1] as $techid => $techlabel){
                if($id==$techid) return $techlabel;
            }
        }
        return '';
    }

    public function developerEditSigned($expertId) {
        return URL::temporarySignedRoute(
            'developer.edit', now()->addMinutes(1), ['expertId' => $expertId]
        );
    }

    public function developerEdit($expertId){
        if(!Auth::check() && !$request->hasValidSignature()) return redirect('login');
        $expert = Expert::find($expertId);

        $disabledInputs = array(
            ''
        );

        return view('experts.edit')->with('expert',$expert)->with('technologies',Expert::getTechnologies());
    }
}
