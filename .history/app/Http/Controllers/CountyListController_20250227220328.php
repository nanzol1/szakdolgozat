<?php

namespace App\Http\Controllers;

use App\Models\County_list;
use Illuminate\Http\Request;

class CountyListController extends Controller{

    public function getDataFromAddress($data){
        $datas = explode('-',$data);
        $zip = $datas[0] === 'zip' ? $datas[1] : [];
        $query = County_list::query();
        if($zip){
            $query = County_list::where('postal_code','=',$zip);
        }
        $list = $query->get()->toArray();


        return response()->json(['status' => 'success', 'list'=> $list]);
    }
}

?>