<?php

namespace App\Http\Controllers;

use App\Log;
use App\Position;
use App\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;


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
