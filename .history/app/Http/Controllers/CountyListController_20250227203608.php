<?php

namespace App\Http\Controllers;

use App\Models\County_list;
use Illuminate\Http\Request;

class CountyListController extends Controller{

    public function getDataFromAddress(Request $request){
        $zip = $request->data();
        $list = County_list::all();
    }
}

?>