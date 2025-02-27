<?php

namespace App\Http\Controllers;

use App\Models\County_list;
use Illuminate\Http\Request;

class CountyListController extends Controller{

    public function getDataFromAddress($data){
        $datas = json_decode($data,true);
        $list = County_list::where('postal_code','LIKE','%'.$data.'%')
        ->where('postal_code','LIKE','%'.$data.'%')->get()->toArray();
        dd($datas);
    }
}

?>