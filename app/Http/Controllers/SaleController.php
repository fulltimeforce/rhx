<?php

namespace App\Http\Controllers;

use App\Sale;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    //
    public function __construct(Request $request)
    {
       
    }

    public function index(Request $request){
        if(!Auth::check()) return redirect('login');
        if(Auth::user()->role_id != 1) return redirect('login');
        $query = $request->query();
        $sales = Sale::all();
        return view('sale.index',['sales'=> $sales]);
        //return view('user.index')->with('name',$name);
    }

    public function salesBootstrap(Request $request){
        if(!Auth::check()) return redirect('login');
        $query = $request->query();

        $sales = Sale::where('status', 'enabled');

        $sale =  $sales->paginate( $query['rows'] );
        $rows = $sale->items();
        return json_encode(array(
            "total" => count($rows),
            "totalNotFiltered" => $sale->total(),
            "rows" => $rows
        ));
    }

    public function switchStatus(Request $request){
        $input = $request->all();
        $id = $input['id'];
        
        Sale::where('id' , $id)->update(
            array("status"=>'disabled')
        );

    }
}
