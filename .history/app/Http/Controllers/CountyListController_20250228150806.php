<?php

namespace App\Http\Controllers;

use App\Models\County_list;
use Illuminate\Http\Request;

class CountyListController extends Controller{
    public function getDataFromAddress($data){
        //[0] => ZIP | [1] => Megye | [2] => Város <- EZ FIX
        dd($data);

        $datas = implode($data);
        $zip = $datas[0];
        //$county = $datas[1];
        $city = $datas[1];
        $query = County_list::query();
        if($zip){
            $query = County_list::where('postal_code','=',$zip);
        }
        /*if($county){
            $query->where('county','LIKE','%'.$county.'%')->where('county_seat','=',1);
        }*/
        if($city){
            $query->where('place_name','LIKE','%'.$city.'%');
        }
        $list = $query->get()->toArray();


        return response()->json(['status' => 'success', 'list'=> $list]);
    }
}

?>