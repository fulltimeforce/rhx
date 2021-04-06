<?php

namespace App\Http\Controllers;

use App\Recruit;
use App\RecruitTest;
use App\RecruitPosition;

use Mail;
use MultiMail;
use App\Mail\techTestEmail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class RecruitTestController extends Controller
{
    public function test( Request $request ){
        if(!Auth::check()) return redirect('login');
        
        $query = $request->query();

        $name = isset( $query['name'] )? $query['name'] : '';
        
        return view('test_tech.index')
            ->with('name', $name );
    }

    public function listTestBootstrap( Request $request ){
        //call route parameters
        $query = $request->query();

        //create query to call recruits
        $recruits = Recruit::whereNotNull('recruit.id');
        
        //check if $name route parameter is setted
        if(isset($query['name'])){
            $recruits->where('recruit.fullname' , 'like' , '%'.$query['name'].'%');
        }
        
        //verify tab route parameter to set recruits filters
        $recruits->join('recruit_test','recruit.id','=','recruit_test.recruit_id')
            ->join('recruit_positions','recruit.id','=','recruit_positions.recruit_id')
            ->where('recruit_positions.outstanding_report','approve')
            ->where('recruit_positions.call_report','approve')
            ->where('recruit_positions.audio_report','approve')
            ->where('recruit_test.test_status',0)
            ->where('recruit_test.mail_sent',1)
            ->select('recruit.*')
            ->orderBy('recruit.fullname','asc')
            ->distinct();

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

    public function getRecruitForTest( Request $request ){
        //call route parameters
        $input = $request->all();

        //set values in variables
        $recruit_id = $input["recruit_id"];
        
        $recruit = Recruit::join('recruit_test','recruit_test.recruit_id','=','recruit.id')
        ->where("recruit.id" , $recruit_id )
        ->select(
            "recruit.id",
            "recruit.fullname",
            "recruit_test.completeness_score AS completeness_score",
            "recruit_test.code_score AS code_score",
            "recruit_test.design_score AS design_score",
            "recruit_test.technologies_score AS technologies_score",
            "recruit_test.readme_score AS readme_score")
        ->first();

        return json_encode(array(
            "recruit" => $recruit
        ));
    }

    public function saveRecruitTest( Request $request ){
        $input = $request->all();
        $input['test_status'] = 1;
        
        $recruit_id = $input['recruit_id'];
        unset( $input['recruit_id'] );

        RecruitTest::where('recruit_id', $recruit_id)->update($input);
    
        //return with success message
        redirect()->route('recruit.test.menu')
                    ->with('success', 'TEST evaluated successfully.');
    }

    public function sendMail(Request $request){
        if(!isset($request->id)){
            return ["status"=> "error", "message"=>"can´t search recruit"];
        }

        // find recruit
        $recruit = Recruit::find($request->id);

        if(!$recruit){
            return ["status"=> "error", "message"=>"recruit doesn't exist"];
        }

        // get info on recruiter mail 
        $recruiters = RecruitPosition::join('users','recruit_positions.user_id','=','users.id')
                            ->where('recruit_positions.recruit_id',$recruit->id)
                            ->select('users.email')
                            ->orderBy('recruit_positions.created_at','DESC');

        if($recruiters->count() == 0){
            return ["status"=> "error", "message"=>"couldn't find a recruiter connection"];
        }

        $recruiter = $recruiters->first();

        $url = "https://docs.google.com/document/d/1EtOeiVmGH2W3sYwe7YKKw8AhiLC_TxIDiG83wNKPmqk/edit?usp=sharing";
        
        // send mail
        MultiMail::to("ceo@fulltimeforce.com")//$recruit->email_address
            ->from($recruiter->email)
            ->send(new techTestEmail($url));

        // set mail flag to true
        $test = RecruitTest::where('recruit_id',$recruit->id)->update(['mail_sent' => 1]);
        
        return ["status"=>"success","message"=>"mail sent!"];
    }
}
