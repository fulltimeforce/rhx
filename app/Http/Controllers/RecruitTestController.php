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
            ->where(function($q){
                $q->where('recruit_positions.outstanding_report','!=','disapprove')
                ->orWhere('recruit_positions.outstanding_report',null);
            })
            ->where(function($q){
                $q->where('recruit_positions.call_report','!=','disapprove')
                ->orWhere('recruit_positions.call_report',null);
            })
            ->where(function($q){
                $q->where('recruit_positions.audio_report','!=','disapprove')
                ->orWhere('recruit_positions.audio_report',null);
            })
            ->where(function($q){
                $q->where('recruit_positions.soft_report','!=','disapprove')
                ->orWhere('recruit_positions.soft_report',null);
            })
            // ->where('recruit_positions.outstanding_report','not like','disapprove')
            // ->where('recruit_positions.call_report','not like','disapprove')
            // ->where('recruit_positions.audio_repor','not like','disapprove')
            // ->where('recruit_positions.soft_report','not like','disapprove')
            ->where('recruit_test.test_status',0)
            ->where('recruit_test.mail_sent',1)
            ->select('recruit.*','recruit_test.sent_at')
            ->orderBy('recruit_test.sent_at','desc')
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

        // ANALIZE CLEAN CODE SCORE ---------------------------------------------------
        //-------------------------------------------------------------------------
        $input['code_single_resp']      = isset($input['code_single_resp']);
        $input['code_open_closed']      = isset($input['code_open_closed']);
        $input['code_liskov_subs']      = isset($input['code_liskov_subs']);
        $input['code_int_segr']         = isset($input['code_int_segr']);
        $input['code_depend_invers']    = isset($input['code_depend_invers']);
        $input['code_all_solid']        = isset($input['code_all_solid']);
        $input['code_unreadable']       = isset($input['code_unreadable']);

        $code_criterias = [
            $input['code_single_resp'],
            $input['code_open_closed'],
            $input['code_liskov_subs'],
            $input['code_int_segr'],
            $input['code_depend_invers']
        ];
        $c_counter = 0;
        $input['code_score'] = 0;
        
        // count criterias
        foreach($code_criterias as $criteria){
            if($criteria) $c_counter ++;
        }

        // Asign code score
        if($c_counter == 5){
            $input['code_score'] = $input['code_all_solid'] ? 5 : 4;
            $input['code_unreadable'] = false;
        }
        if($c_counter < 5){
            $input['code_score'] = 3;
        }
        if($c_counter < 3){
            $input['code_score'] = 2;
        }
        if($c_counter == 0){
            $input['code_score'] = $input['code_unreadable'] ? 0 : 1;
            $input['code_all_solid'] = false;
        }

        // ANALIZE DESIGN QUALITY SCORE ---------------------------------------------------
        //-------------------------------------------------------------------------
        $input['design_adaptability']   = isset($input['design_adaptability']);
        $input['design_changability']   = isset($input['design_changability']);
        $input['design_modularity']     = isset($input['design_modularity']);
        $input['design_simplicity']     = isset($input['design_simplicity']);
        $input['design_robustness']     = isset($input['design_robustness']);

        $design_criterias = [
            $input['design_adaptability'],
            $input['design_changability'],
            $input['design_modularity'],
            $input['design_simplicity'],
            $input['design_robustness']
        ];
        $d_counter = 0;
        $input['design_score'] = 0;
        
        // count criterias
        foreach($design_criterias as $criteria){
            if($criteria) $d_counter ++;
        }

        // Asign design score
        if($d_counter == 5){
            $input['design_score'] = 5;
        }
        if($d_counter == 4){
            $input['design_score'] = !$input['design_simplicity'] ? 4: $input['design_robustness'] ? 3 : 2;
        }
        if($d_counter == 3){
            $input['design_score'] = $input['design_robustness'] ? 3 : 2;
        }
        if($d_counter < 3){
            $input['design_score'] = $d_counter;
        }

        unset($input['design_none']);

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
        MultiMail::to($recruit->email_address)//$recruit->email_address
            ->from($recruiter->email)
            ->send(new techTestEmail($url));

        // set mail flag to true
        $test = RecruitTest::where('recruit_id',$recruit->id)->update([
            'mail_sent' => 1,
            'sent_at'=> now(),
        ]);
        
        return ["status"=>"success","message"=>"mail sent!"];
    }

    public function failTest(Request $request){
        RecruitTest::where('recruit_id',$request->id)->update([
            'test_status' => 2
        ]);

        //return with success message
        redirect()->route('recruit.test.menu')
                    ->with('success', 'TEST disapproved successfully.');
    }
}
