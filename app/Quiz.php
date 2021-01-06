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

    public $matrix_answers = [
        ['serie'=>'A','answers'=>[4,5,1,2,6,3,6,2,1,3,5,4]],
        ['serie'=>'B','answers'=>[2,6,1,2,1,3,5,6,4,3,4,5]],
        ['serie'=>'C','answers'=>[8,2,3,8,7,4,5,1,7,6,1,2]],
        ['serie'=>'D','answers'=>[3,4,3,7,8,6,5,4,1,2,5,6]],
        ['serie'=>'E','answers'=>[7,6,8,2,1,5,2,4,1,6,3,5]],
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
        foreach($quiz as $k => $quiz_answer){
            if($i >= 12){
                $serie++;
                $i = 0;
            }
            if($quiz_answer != null){
                $matrix_ans = $this->matrix_answers[$serie]['answers'][$i];
                if(intval($quiz_answer) == $matrix_ans){
                    $series_result[$this->matrix_answers[$serie]['serie']]++;
                }
            }
            $i++;
        }
        return $series_result;
    }

    function getImgFromQuestion($question_index){
        foreach($this->quiz_questions as $k => $val){
            if($question_index == $k){
                return $val;
            }
        }
        return $this->quiz_questions[0];
    }
}