<?php

namespace App\Http\Controllers;

use App\Position;
use Illuminate\Http\Request;
use App\Expert;
use App\Requirement;
use App\Log;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\DB;
use DateTime;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $positions = Position::where( function($q){
            if( !Auth::check() ){
                $q->where('status' , 'enabled');
            }
        } );

        if(!Auth::check()) $positions = $positions->where('private' , 1);

        $positions = $positions->latest()->get();
        return view('positions.index',compact('positions'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if(!Auth::check()) return redirect('login');
        return view('positions.create');
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
        $input = $request->all();
        
        $input['id'] = Hashids::encode( time() );

        $input['technology_basic'] = isset( $input['technology_basic'] )? implode("," , $input['technology_basic'] ) : '' ;
        $input['technology_inter'] = isset( $input['technology_inter'] )? implode("," , $input['technology_inter'] ) : '' ;
        $input['technology_advan'] = isset( $input['technology_advan'] )? implode("," , $input['technology_advan'] ) : '' ;

        if(isset( $input['req'] )){
            foreach ($input['req'] as $key => $req) {
                Requirement::create(
                    array(
                        'name' => $req,
                        'position_id' => $input['id'],
                        'user_id' => Auth::id()
                    )
                );
            }
        }
        
        $input['slug'] = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($input['name']));

        $input['private'] = isset($input['private'])? 1 : 0;

        $position = Position::create($input);
   
        return redirect()->route('positions.index')
                        ->with('success','Position created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show(Position $position)
    {
        //
        if(!Auth::check()) return redirect('login');
        return view('positions.show',compact('position'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(Position $position)
    {
        //
        if(!Auth::check()) return redirect('login');
        
        $a_basic = !is_null($position->technology_basic)? explode("," , $position->technology_basic) : array();
        $a_inter = !is_null($position->technology_inter)? explode("," , $position->technology_inter) : array();
        $a_advan = !is_null($position->technology_advan)? explode("," , $position->technology_advan) : array();
        
        $a_tech_basic = array();
        $a_tech_inter = array();
        $a_tech_advan = array();

        foreach(Expert::getTechnologies() as $catid => $cat){
            foreach($cat[1] as $techid => $techlabel){
                if( in_array( $techid , $a_basic ) ) $a_tech_basic = array_merge( $a_tech_basic, array( $techid => $techlabel ));
                if( in_array( $techid , $a_inter ) ) $a_tech_inter = array_merge( $a_tech_inter, array( $techid => $techlabel ));
                if( in_array( $techid , $a_advan ) ) $a_tech_advan = array_merge( $a_tech_advan ,array( $techid => $techlabel ));
            }
        }

        // return $a_tech_advan;

        return view('positions.edit')
            ->with('a_tech_basic', $a_tech_basic)
            ->with('a_tech_inter', $a_tech_inter)
            ->with('a_tech_advan', $a_tech_advan)
            ->with('position',$position);
    }
    

    /**
     * 
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function relations($positionId)
    {
        //
        if(!Auth::check()) return redirect('login');

        $experts = DB::table('experts')->whereIn('id', function($query) use ($positionId){
            $query->select('expert_id')
            ->from('expert_position')
            ->where('position_id' , $positionId);
        })->get();
        $position = Position::find($positionId);
        $a_basic = !is_null( $position->technology_basic )? explode(",", $position->technology_basic) : array();
        $a_inter = !is_null( $position->technology_inter )? explode(",", $position->technology_inter) : array();
        $a_advan = !is_null( $position->technology_advan )? explode(",", $position->technology_advan) : array();
        $a_technologies = array_merge($a_basic,$a_inter,$a_advan);
        $n_experts = array();
        foreach ($experts as $k => $expert) {
            
            $date = new DateTime($expert->birthday);
            $now = new DateTime();
            $interval = $now->diff($date);
            $expert->birthday = $interval->y;
            $n_experts[] = $expert;
        }

        $current_tech = array();
        $after_tech = array();
        foreach(Expert::getTechnologies() as $catid => $cat){
            foreach($cat[1] as $techid => $techlabel){
                if( in_array( $techid , $a_technologies) ){
                    $current_tech[] = array($techid => $techlabel ); 
                }else{
                    $after_tech[] = array($techid => $techlabel ); 
                }                
            }
        }
        return view('positions.experts')
            ->with('experts' , $n_experts)
            ->with('current_tech' , $current_tech)
            ->with('after_tech' , $after_tech)
            ->with('technologies',Expert::getTechnologies());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Position $position)
    {
        //
        if(!Auth::check()) return redirect('login');
        $request->validate([]);
        $input = $request->all();
        $input["status"] = isset( $input["status"] ) ? 'enabled' : 'disabled';

        $requirements = Requirement::where('position_id' , $position->id)->get();
        $a_requirements = array();
        foreach ($requirements as $key => $requirement) {
            $a_requirements[] = strtolower($requirement->name);
        }

        $input['technology_basic'] = isset( $input['technology_basic'] )? implode("," , $input['technology_basic'] ) : '' ;
        $input['technology_inter'] = isset( $input['technology_inter'] )? implode("," , $input['technology_inter'] ) : '' ;
        $input['technology_advan'] = isset( $input['technology_advan'] )? implode("," , $input['technology_advan'] ) : '' ;
        
        if(isset( $input['req'] )){
            
            foreach ($input['req'] as $key => $req) {
                if( count( array_filter($a_requirements, function($val) use($req){ return (strtolower($val) == strtolower($req)); }  ) ) == 0  ){
                    Requirement::create(
                        array(
                            'name' => $req,
                            'position_id' => $position->id,
                            'user_id' => Auth::id()
                        )
                    );
                }
                
            }
        }
        unset($input['req']);

        $input['slug'] = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($input['name']));

        $input['private'] = isset($input['private'])? 1 : 0;

        $position->update($input);
  
        return redirect()->route('positions.index')
                        ->with('success','Position updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        //
        $position->experts()->detach();
        
    }

    public function enabled(Request $request){

        $expertId = $request->input('expertId');

        $positions = Position::where('status' , 'enabled')->get();
        $a_positions = array();
        foreach ($positions as $key => $position) {
            $em = DB::table('expert_position')->where(['expert_id' => $expertId , "position_id" => $position->id ])->count();
            $a_positions[] = (object) array(
                "id" => $position->id,
                "name" => $position->name,
                "active" => $em > 0? 1 : 0 
            );
        }
        
        return response()->json( $a_positions );
    }

    public function experts(Request $request){

        $expertId = $request->input('expertId');

        $positions = $request->input('positions');

        $expert = Expert::where('id',$expertId)->first();

        Log::where('expert_id' , $expertId)->delete();

        foreach ($positions as $key => $position) {
            Log::create(
                array(
                    "expert_id" => $expertId,
                    "position_id" => $position,
                    "user_id"   => Auth::id(),
                    "form" => 1
                )
            );
        }

        return 'success';

    }

}
