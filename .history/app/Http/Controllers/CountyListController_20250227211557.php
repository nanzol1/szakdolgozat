<?php

namespace App\Http\Controllers;

use App\Models\County_list;
use Illuminate\Http\Request;

class CountyListController extends Controller{

    public function getDataFromAddress($data){
        //[0] => ZIP | [1] => Megye | [2] => Város <- EZ FIX
        $datas = explode(',',$data);
        $zip = $datas[0];
        $county = $datas[1];
        $city = $datas[2];
        $list = County_list::where('postal_code','LIKE','%'.$zip.'%')
        ->where('county','LIKE','%'.$county.'%')
        ->whereRaw("LOWER(place_name) = LOWER(?)", [$city])->get()->toArray();
        dd($list);
    }
}

?>