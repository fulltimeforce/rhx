<?php

namespace App\Http\Controllers;

use App\Recruit;
use App\User;
use App\Especificpositions;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

use Vinkla\Hashids\Facades\Hashids;

class EspecificpositionsController extends Controller
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

        //check if parameter (name) exists
        $name = isset($query['name']) ? $query['name'] : '';

        //return view with values ( name search, positions, platforms, recruits menu option)
        return view('specific.index')
            ->with('s', $name );
    }

    //==============================================================================
    //==========================TABLE BOOTSTRAP METHODS=============================
    //==============================================================================
    public function specificBootstrap(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call route parameters
        $query = $request->query();

        //create query to call recruits
        $positions = Especificpositions::whereNotNull('id');
        
        //check if $name route parameter is setted
        if(isset($query['name'])){
            $positions->where('name' , 'like' , '%'.$query['name'].'%');
        }
        
        //set recruits filters
        $positions->distinct()
            ->where('status', '=', 'enabled')
            ->select('*');


        //set rows value
        $position =  $positions->paginate( $query['rows'] );
        $rows = $position->items();

        foreach ($rows as $key => $value) {
            
            $a_basic = !is_null($value->technology_basic)? explode("," , $value->technology_basic) : array();
            $a_inter = !is_null($value->technology_inter)? explode("," , $value->technology_inter) : array();
            $a_advan = !is_null($value->technology_advan)? explode("," , $value->technology_advan) : array();

            $a_basic_new = [];
            $a_inter_new = [];
            $a_advan_new = [];

            foreach ($a_basic as $value2) {
                array_push($a_basic_new, Recruit::getTechnologyName($value2));
            }

            foreach ($a_inter as $value2) {
                array_push($a_inter_new, Recruit::getTechnologyName($value2));
            }

            foreach ($a_advan as $value2) {
                array_push($a_advan_new, Recruit::getTechnologyName($value2));
            }

            $rows[$key]['technology_basic'] = $a_basic_new;
            $rows[$key]['technology_inter'] = $a_inter_new;
            $rows[$key]['technology_advan'] = $a_advan_new;
        }

        //return view with values (name search, positions menu option)
        return json_encode(array(
            "total" => count($rows),
            "totalNotFiltered" => $position->total(),
            "rows" => $rows
        ));
    }

    public function specificShowBootstrap(Request $request){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //call route parameters
        $query = $request->query();

        //create query to call recruits
        $specific_positionId = $query['spcposId'];

        //get specificposition by id
        $spec_pos = Especificpositions::where('id' , $specific_positionId)->first();
        
        $a_basic = !is_null($spec_pos->technology_basic)? explode("," , $spec_pos->technology_basic) : array();
        $a_inter = !is_null($spec_pos->technology_inter)? explode("," , $spec_pos->technology_inter) : array();
        $a_advan = !is_null($spec_pos->technology_advan)? explode("," , $spec_pos->technology_advan) : array();

        $recruits = Recruit::whereNotNull('id')
            ->where('tech_qtn', '=', 'filled');

        foreach ($a_basic as $basic) {
            $recruits->whereIn($basic, ['basic','intermediate','advanced']);
        }

        foreach ($a_inter as $inter) {
            $recruits->whereIn($inter, ['intermediate','advanced']);
        }

        foreach ($a_advan as $advan) {
            $recruits->whereIn($advan, ['advanced']);
        }
        
        $recruits->distinct()
            ->select('*');

        //set rows value
        $recruit =  $recruits->paginate( $query['rows'] );
        $rows = $recruit->items();

        //return view with values (name search, positions menu option)
        return json_encode(array(
            "total" => count($rows),
            "totalNotFiltered" => $recruit->total(),
            "rows" => $rows
        ));
    }

    //==============================================================================
    //=====FUNCTIONALITY METHDOS: CREATE, EDIT, DELETE, EVALUATE CRITERIA===========
    //==============================================================================
    public function editEspecificPosition(Request $request, $id){
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');
        
        $specific_position = Especificpositions::where('id' , $id)->first();

        $a_basic = !is_null($specific_position->technology_basic)? explode("," , $specific_position->technology_basic) : array();
        $a_inter = !is_null($specific_position->technology_inter)? explode("," , $specific_position->technology_inter) : array();
        $a_advan = !is_null($specific_position->technology_advan)? explode("," , $specific_position->technology_advan) : array();
        
        $a_basic_new = [];
        $a_inter_new = [];
        $a_advan_new = [];

        foreach ($a_basic as $value) {
            $a_basic_new[$value] = Recruit::getTechnologyName($value);
        }

        foreach ($a_inter as $value) {
            $a_inter_new[$value] = Recruit::getTechnologyName($value);
        }

        foreach ($a_advan as $value) {
            $a_advan_new[$value] = Recruit::getTechnologyName($value);
        }

        // return $a_tech_advan;

        return view('specific.edit')
            ->with('a_basic_new', $a_basic_new)
            ->with('a_inter_new', $a_inter_new)
            ->with('a_advan_new', $a_advan_new)
            ->with('position',$specific_position);
    }

    public function updateEspecificPosition(Request $request, $id){
        $request->validate([]);
        $input = $request->all();

        $input['technology_basic'] = isset( $input['technology_basic'] )? implode("," , $input['technology_basic'] ) : '' ;
        $input['technology_inter'] = isset( $input['technology_inter'] )? implode("," , $input['technology_inter'] ) : '' ;
        $input['technology_advan'] = isset( $input['technology_advan'] )? implode("," , $input['technology_advan'] ) : '' ;

        $input['technology_basic'] = empty( $input['technology_basic'] )? null : $input['technology_basic'] ;
        $input['technology_inter'] = empty( $input['technology_inter'] )? null : $input['technology_inter'] ;
        $input['technology_advan'] = empty( $input['technology_advan'] )? null : $input['technology_advan'] ;
        
        $input['slug'] = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($input['name']));
        $input['icon'] = preg_replace('/[^A-Za-z0-9-]+/', '_', strtolower($input['name']));

        $input['private'] = isset($input['private'])? 1 : 0;

        unset( $input["_token"] );

        Especificpositions::where('id' , $id)->update($input);

        return redirect()->route('specific.menu')
                        ->with('success','Specific Position EDITED successfully.');
    }

    public function addEspecificPosition(Request $request){
        $request->validate([]);
        $input = $request->all();
        
        $input['id'] = Hashids::encode( time() );

        $input['technology_basic'] = isset( $input['technology_basic'] )? implode("," , $input['technology_basic'] ) : '' ;
        $input['technology_inter'] = isset( $input['technology_inter'] )? implode("," , $input['technology_inter'] ) : '' ;
        $input['technology_advan'] = isset( $input['technology_advan'] )? implode("," , $input['technology_advan'] ) : '' ;

        $input['technology_basic'] = empty( $input['technology_basic'] )? null : $input['technology_basic'] ;
        $input['technology_inter'] = empty( $input['technology_inter'] )? null : $input['technology_inter'] ;
        $input['technology_advan'] = empty( $input['technology_advan'] )? null : $input['technology_advan'] ;
        
        $input['slug'] = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($input['name']));
        $input['icon'] = preg_replace('/[^A-Za-z0-9-]+/', '_', strtolower($input['name']));

        $input['private'] = isset($input['private'])? 1 : 0;

        $esp_position = Especificpositions::create($input);
   
        return redirect()->route('specific.menu')
                        ->with('success','Specific Position CREATED successfully.');
    }

    public function deleteEspecificPosition(Request $request){
        //call route parameters
        $input = $request->all();

        //set values in variables
        $specific_position_id = $input["specific_position_id"];

        //delete recruit_positions row by: recruit_id and position_id
        Especificpositions::where('id' , $specific_position_id)->delete();

        redirect()->back()
                ->with('success', 'Specific Position DELETED successfully');
    }

    public function createEspecificPosition(Request $request){
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        return view('specific.create');
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

    //==============================================================================
    //==========================SHOW APPLICANTS METHODS=============================
    //==============================================================================
    public function showApplicants(Request $request, $id){
        //verify logged user and user level
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id >= 3) return redirect('login');

        //get specific position information by id
        $specific_position = Especificpositions::where('id' , $id)->first();

        $a_basic = !is_null($specific_position->technology_basic)? explode("," , $specific_position->technology_basic) : array();
        $a_inter = !is_null($specific_position->technology_inter)? explode("," , $specific_position->technology_inter) : array();
        $a_advan = !is_null($specific_position->technology_advan)? explode("," , $specific_position->technology_advan) : array();

        $a_basic_new = [];
        $a_inter_new = [];
        $a_advan_new = [];

        foreach ($a_basic as $value) {
            $a_basic_new[$value] = Recruit::getTechnologyName($value);
        }

        foreach ($a_inter as $value) {
            $a_inter_new[$value] = Recruit::getTechnologyName($value);
        }

        foreach ($a_advan as $value) {
            $a_advan_new[$value] = Recruit::getTechnologyName($value);
        }

        //return view with values ( name search, positions, platforms, recruits menu option)
        return view('specific.show')
            ->with('specific_position', $specific_position )
            ->with('a_basic', $a_basic_new )
            ->with('a_inter', $a_inter_new )
            ->with('a_advan', $a_advan_new );
    }
}
