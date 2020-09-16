<?php

namespace App\Http\Controllers;
use App\Config;

use Illuminate\Http\Request;

class ConfigController extends Controller
{
   public function changeFceLevel(Request $request){
        $fce_level = $request->input("fce");
        Config::first()
            ->update(
                array(
                    "fce_lower_overall" => $fce_level
                )
            );
    }
}
