<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engine_type extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cars()
    {
        return $this->belongsTo(Car::class, 'id', 'engine_type_id');
    }

}
