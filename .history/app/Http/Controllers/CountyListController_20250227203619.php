<?php

namespace App\Http\Controllers;

use App\Models\County_list;
use Illuminate\Http\Request;

class CountyListController extends Controller{

    public function getDataFromAddress(Request $request){
        $data = $request->all();
        $list = County_list::all();
        dd($data);
    }
}

?>