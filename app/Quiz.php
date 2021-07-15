<?php
namespace App;

Class Quiz{
    public $quiz_questions = [
        1=>'1441512.jpg',
        2=>'2224123.jpg',
        3=>'3412423.jpg',
        4=>'4444444.jpg',
        5=>'5555555.jpg',
        6=>'6666666.jpg',
        7=>'7777777.jpg',
        8=>'8888888.jpg',
        9=>'9999999.jpg',
        10=>'10040400.jpg',
        11=>'11040020.jpg',
        12=>'12995949.jpg',
        13=>'13995949.jpg',
        14=>'14995949.jpg',
        15=>'15995949.jpg',
        16=>'16995949.jpg',
        17=>'17995949.jpg',
        18=>'18995949.jpg',
        19=>'19995949.jpg',
        20=>'20995949.jpg',
        21=>'21995949.jpg',
        22=>'22995949.jpg',
        23=>'23995949.jpg',
        24=>'24995949.jpg',
        25=>'25995949.jpg',
        26=>'26995949.jpg',
        27=>'27995949.jpg',
        28=>'28995949.jpg',
        29=>'29995949.jpg',
        30=>'30995949.jpg',
        31=>'31995949.jpg',
        32=>'32995949.jpg',
        33=>'33995949.jpg',
        34=>'34995949.jpg',
        35=>'35995949.jpg',
        36=>'36995949.jpg',
        37=>'37995949.jpg',
        38=>'38995949.jpg',
        39=>'39995949.jpg',
        40=>'40995949.jpg',
        41=>'41995949.jpg',
        42=>'42995949.jpg',
        43=>'43995949.jpg',
        44=>'44995949.jpg',
        45=>'45995949.jpg',
        46=>'46995949.jpg',
        47=>'47995949.jpg',
        48=>'48995949.jpg',
        49=>'49995949.jpg',
        50=>'50995949.jpg',
        51=>'51995949.jpg',
        52=>'52995949.jpg',
        53=>'53995949.jpg',
        54=>'54995949.jpg',
        55=>'55995949.jpg',
        56=>'56995949.jpg',
        57=>'57995949.jpg',
        58=>'58995949.jpg',
        59=>'59995949.jpg',
        60=>'60995949.jpg'
    ];

    public $quiz_answers = [
        ['serie'=>'A','answers'=>[4,5,1,2,6,3,6,2,1,3,5,4]],
        ['serie'=>'B','answers'=>[2,6,1,2,1,3,5,6,4,3,4,5]],
        ['serie'=>'C','answers'=>[8,2,3,8,7,4,5,1,7,6,1,2]],
        ['serie'=>'D','answers'=>[3,4,3,7,8,6,5,4,1,2,5,6]],
        ['serie'=>'E','answers'=>[7,6,8,2,1,5,2,4,1,6,3,5]],
    ];

    public $expected_answers = [
        ["total" => 15, 'expected_results'=>[8,4,2,1,0]],
        ["total" => 16, 'expected_results'=>[8,4,3,1,0]],
        ["total" => 17, 'expected_results'=>[8,5,3,2,0]],
        ["total" => 18, 'expected_results'=>[8,5,3,2,0]],
        ["total" => 19, 'expected_results'=>[8,6,3,2,0]],
        ["total" => 20, 'expected_results'=>[8,6,3,2,1]],
        ["total" => 21, 'expected_results'=>[8,6,4,2,1]],
        ["total" => 22, 'expected_results'=>[9,6,4,2,1]],
        ["total" => 23, 'expected_results'=>[9,7,4,2,1]],
        ["total" => 24, 'expected_results'=>[9,7,4,3,1]],
        ["total" => 25, 'expected_results'=>[10,7,4,3,1]],
        ["total" => 26, 'expected_results'=>[10,7,5,3,1]],
        ["total" => 27, 'expected_results'=>[10,7,5,4,1]],
        ["total" => 28, 'expected_results'=>[10,7,6,4,1]],
        ["total" => 29, 'expected_results'=>[10,7,6,5,1]],
        ["total" => 30, 'expected_results'=>[10,7,6,5,2]],
        ["total" => 31, 'expected_results'=>[10,7,7,5,2]],
        ["total" => 32, 'expected_results'=>[10,8,7,5,2]],
        ["total" => 33, 'expected_results'=>[11,8,7,5,2]],
        ["total" => 34, 'expected_results'=>[11,8,7,6,2]],
        ["total" => 35, 'expected_results'=>[11,8,7,7,2]],
        ["total" => 36, 'expected_results'=>[11,8,8,7,2]],
        ["total" => 37, 'expected_results'=>[11,9,8,7,2]],
        ["total" => 38, 'expected_results'=>[11,9,8,8,2]],
        ["total" => 39, 'expected_results'=>[11,9,8,8,3]],
        ["total" => 40, 'expected_results'=>[11,10,8,8,3]],
        ["total" => 41, 'expected_results'=>[11,10,9,8,3]],
        ["total" => 42, 'expected_results'=>[11,10,9,9,3]],
        ["total" => 43, 'expected_results'=>[12,10,9,9,3]],
        ["total" => 44, 'expected_results'=>[12,10,9,9,4]],
        ["total" => 45, 'expected_results'=>[12,10,9,9,5]],
        ["total" => 46, 'expected_results'=>[12,10,10,9,5]],
        ["total" => 47, 'expected_results'=>[12,10,10,9,6]],
        ["total" => 48, 'expected_results'=>[12,11,10,9,6]],
        ["total" => 49, 'expected_results'=>[12,11,10,10,6]],
        ["total" => 50, 'expected_results'=>[12,11,10,10,7]],
        ["total" => 51, 'expected_results'=>[12,11,11,10,7]],
        ["total" => 52, 'expected_results'=>[12,11,11,10,8]],
        ["total" => 53, 'expected_results'=>[12,11,11,11,8]],
        ["total" => 54, 'expected_results'=>[12,12,11,11,8]],
        ["total" => 55, 'expected_results'=>[12,12,11,11,9]],
        ["total" => 56, 'expected_results'=>[12,12,12,11,9]],
        ["total" => 57, 'expected_results'=>[12,12,12,11,10]],
        ["total" => 58, 'expected_results'=>[12,12,12,12,10]],
        ["total" => 59, 'expected_results'=>[12,12,12,12,11]],
        ["total" => 60, 'expected_results'=>[12,12,12,12,12]],
    ];

    public $baremos = [
        ['percentile' => 99, 'total' => 53, 'overall'=>'S'],
        ['percentile' => 95, 'total' => 52, 'overall'=>'S'],
        ['percentile' => 90, 'total' => 51, 'overall'=>'S'],
        ['percentile' => 80, 'total' => 50, 'overall'=>'PS'],
        ['percentile' => 70, 'total' => 49, 'overall'=>'PS'],
        ['percentile' => 60, 'total' => 48, 'overall'=>'P'],
        ['percentile' => 50, 'total' => 47, 'overall'=>'P'],
        ['percentile' => 45, 'total' => 46, 'overall'=>'P'],
        ['percentile' => 35, 'total' => 45, 'overall'=>'PI'],
        ['percentile' => 30, 'total' => 44, 'overall'=>'PI'],
        ['percentile' => 30, 'total' => 43, 'overall'=>'PI'],
        ['percentile' => 25, 'total' => 42, 'overall'=>'PI'],
        ['percentile' => 20, 'total' => 41, 'overall'=>'I'],
        ['percentile' => 15, 'total' => 40, 'overall'=>'I'],
        ['percentile' => 15, 'total' => 39, 'overall'=>'I'],
        ['percentile' => 10, 'total' => 38, 'overall'=>'I'],
        ['percentile' => 10, 'total' => 37, 'overall'=>'I'],
        ['percentile' => 10, 'total' => 36, 'overall'=>'I'],
        ['percentile' => 10, 'total' => 35, 'overall'=>'I'],
        ['percentile' => 10, 'total' => 34, 'overall'=>'I'],
        ['percentile' => 10, 'total' => 33, 'overall'=>'I'],
        ['percentile' => 05, 'total' => 32, 'overall'=>'I'],
        ['percentile' => 05, 'total' => 31, 'overall'=>'I'],
        ['percentile' => 05, 'total' => 30, 'overall'=>'I'],
        ['percentile' => 05, 'total' => 29, 'overall'=>'I'],
        ['percentile' => 05, 'total' => 28, 'overall'=>'I'],
        ['percentile' => 01, 'total' => 27, 'overall'=>'I'],
    ];

    function evaluateResults($quiz){
        $i = 0;
        $serie = 0;
        $series_result=[
            'A'=>0,
            'B'=>0,
            'C'=>0,
            'D'=>0,
            'E'=>0,
        ];
        $total_score = 0;

        foreach($quiz as $k => $quiz_answer){
            if($i >= 12){
                $serie++;
                $i = 0;
            }
            if($quiz_answer != null){
                $matrix_ans = $this->quiz_answers[$serie]['answers'][$i];
                if(intval($quiz_answer) == $matrix_ans){
                    $series_result[$this->quiz_answers[$serie]['serie']]++;
                    $total_score++;
                }
            }
            $i++;
        }

        $valid = $this->validateQuiz($total_score, $series_result);

        return [
            "series"=>$series_result,
            "total"=>$total_score,
            "valid"=>$valid,
            "raven_overall" => $this->getRavenOverallScore($total_score),
            "raven_perc" => $this->getRavenPercentile($total_score)
        ];
    }

    function validateQuiz($score, $series_result){
        
        if($score < 15){
            return false;
        }
        $valid = true;
        $expected_results = [];
        foreach($this->expected_answers as $case){
            if($case['total'] == $score){
                $expected_results = $case['expected_results'];
            }
        }

        $i = 0;
        $series_discrepancy = 0;
        foreach($series_result as $result){
            $series_discrepancy += $result - $expected_results[$i];
            /*$temp = $result - $expected_results[$i];
            if($temp < -2 || $temp > 2){
                $valid = false;
            }*/
            $i++;
        }
        if($series_discrepancy < -2 || $series_discrepancy > 2){
            $valid = false;
        }

        return $valid;
        
    }

    function getRavenOverallScore($score){
        $overall = "";
        if($score >= 53){
            return 'S';
        }

        if($score <= 27){
            return 'I';
        }
        foreach($this->baremos as $bar){
            if($bar['total'] == $score){
                $overall = $bar['overall'];
            }
        }
        return $overall;
    }

    function getRavenPercentile($score){
        $percentile = "";
        if($score >= 53){
            return 99;
        }

        if($score <= 27){
            return 01;
        }
        foreach($this->baremos as $bar){
            if($bar['total'] == $score){
                $percentile = $bar['percentile'];
            }
        }
        return $percentile;
    }

    function getImgFromQuestion($question_index){
        foreach($this->quiz_questions as $k => $val){
            if($question_index == $k){
                return $val;
            }
        }
        return $this->quiz_questions[0];
    }

    function mapStoredAnswers($raven_test){
        $groups = ["A","B","C","D","E"];
        $answers = [];
        $q = 0;
        for ($i=1; $i <= 60; $i++) { 
            $f = ($i-1)/12;
            $intStr = substr("$f",0,1);
            $index = intval($intStr);
            $group = $groups[$index];
            $class = $this->answerInGroup($group, $q, $raven_test["q$i"]);

            $q++;

            $ans = [
                "q" => $q,
                "answer" => $raven_test["q$i"] ? intval($raven_test["q$i"]) : "?",
                "class" => $class
            ];
            $answers[$group][] = $ans;
            if($q == 12){
                $q = 0;
            }
        }
        return $answers;
    }
    function answerInGroup($group, $question, $answer){
        if($answer){
            $ans = intval($answer);
            for ($g=0; $g < sizeof($this->quiz_answers); $g++) { 
                $serie = $this->quiz_answers[$g];
                if($serie['serie'] == $group){
                    if($serie['answers'][$question] == $ans){
                        return "correct";
                    }else{
                        return "wrong";
                    }
                }
            }
        }else{
            return "empty";
        }
    }
}