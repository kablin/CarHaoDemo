<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    



    public function getCarsList(Request $request)
    {

        $currentPage = $request->get('currentPage')? $request->get('currentPage') : 1;
        $perPage = $request->get('perPage');
        $data =Car::with('engine_type')->with('car_model', fn($a) => $a->with('brand'))->with('drive_type')->with('images');
        
        if ($request->get('filter_model')) $data=$data->where('car_model_id',$request->get('filter_model'));
        if ($request->get('filter_drive')) $data=$data->where('drive_type_id',$request->get('filter_drive'));
        if ($request->get('filter_engine')) $data=$data->where('engine_type_id',$request->get('filter_engine'));

        if ($request->get('filter_brand')) $data=$data->whereHas('car_model', function($q) use ($request) {
            $q->where('brand_id',$request->get('filter_brand'));
        });
        
        if ($request->get('filter_price_min') && $request->get('filter_price_max')) $data=$data->whereBetween('price',[$request->get('filter_price_min'),$request->get('filter_price_max')]);

        if ($request->get('filter_year_min') && $request->get('filter_year_max')) $data=$data->whereBetween('manufactured_year',[ $request->get('filter_year_min').'-01-01',$request->get('filter_year_max').'-12-31']);
       
       
//          Log::info( $data->toSql());

        $data=$data->paginate($perPage, ['*'], null, $currentPage);
        

        return json_encode($data);
    }


    public function getGuides(Request $request)
    {

        $data['car_models'] =\App\Models\Car_model::all();
        $data['engine_types'] =\App\Models\Engine_type::all();
        $data['brands'] =\App\Models\Brand::all();
        $data['dive_types'] =\App\Models\Drive_type::all();

        $data['limits']=[];
        $data['limits']['max_price'] =Car::max('price');
        $data['limits']['min_price'] =Car::min('price');
        $data['limits']['max_date'] =Car::max('manufactured_year');
        $data['limits']['min_date'] =Car::min('manufactured_year');        

        
        
        

        return json_encode($data);
    }
    


}
