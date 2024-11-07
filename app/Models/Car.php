<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function engine_type()
    {
        return $this->hasOne(Engine_type::class, 'id', 'engine_type_id');
    }

    public function drive_type()
    {
        return $this->hasOne(Drive_type::class, 'id', 'drive_type_id');
    }

    public function car_model()
    {
        return $this->hasOne(Car_model::class, 'id', 'car_model_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'car_id', 'id');
    }

}
