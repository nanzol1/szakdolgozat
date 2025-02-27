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
        $query = County_list::query();
        if($zip){
            $query = County_list::orWhere('postal_code','LIKE','%'.$zip.'%');
        }
        if($county){
            $query->orWhere('county','LIKE','%'.$county.'%');
        }
        if($city){
            $query->orWhere('place_name','LIKE','%'.$city.'%');
        }
        $list = $query->get()->toArray();


        return response()->json(['status' => 'success', 'list'=> $list]);
    }
}

?>