<?php

namespace App\Http\Controllers;

use App\Position;
use Illuminate\Http\Request;
use App\Expert;
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
        $positions = Position::latest()->get();
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
        
        $input['id'] = Hashids::encode(time());

        $input['slug'] = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($input['name']));

        $position = Position::create($input);
   
        return redirect()->route('positions.index')
                        ->with('success','Expert created successfully.');
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
        return view('positions.edit')->with('position',$position);
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

        $n_experts = array();
        foreach ($experts as $k => $expert) {
            
            $date = new DateTime($expert->birthday);
            $now = new DateTime();
            $interval = $now->diff($date);
            $expert->birthday = $interval->y;
            $n_experts[] = $expert;
        }
        return view('positions.experts')->with('experts' , $n_experts)->with('technologies',Expert::getTechnologies());
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

        $input['slug'] = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($input['name']));
        $position->update($input);
  
        return redirect()->route('positions.index')
                        ->with('success','Expert updated successfully');
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

    public function enabled($expertId){
        $new_a_positions = array();
        $expert_enableds = DB::table('expert_position')
            ->leftJoin('experts', 'expert_position.expert_id', '=', 'experts.id')
            ->select('expert_position.position_id')
            ->get();

        $positions = Position::where('status' , 'enabled')->get();


        // $positions = DB::table('positions')
        //     ->leftJoin('expert_position' , )
        //     ->select('positions.*' , DB::table('') )


        foreach ($positions as $key => $position) {
            
        }
        
        return response()->json( [] );
    }
}
