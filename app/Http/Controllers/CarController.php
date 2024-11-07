<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class CarController extends Controller
{
    



    public function getCarsList(Request $request)
    {

        $currentPage = $request->get('currentPage')? $request->get('currentPage') : 1;
        $perPage = $request->get('perPage');
        $data =Car::with('engine_type')->with('car_model', fn($a) => $a->with('brand'))->with('drive_type')->with('images');
        
        if ($request->get('filter_model')) $data=$data->where('car_model_id',$request->get('filter_model'));
        if ($request->get('filter_brand')) $data=$data->whereHas('car_model', function($q) use ($request) {
            $q->where('brand_id',$request->get('filter_brand'));
        });
        
       

        $data=$data->paginate($perPage, ['*'], null, $currentPage);
        

        return json_encode($data);
    }


    public function getGuides(Request $request)
    {

        $data['car_models'] =\App\Models\Car_model::all();
        $data['engine_types'] =\App\Models\Engine_type::all();
        $data['brands'] =\App\Models\Brand::all();
        $data['dive_types'] =\App\Models\Drive_type::all();
        

        return json_encode($data);
    }
    


}
