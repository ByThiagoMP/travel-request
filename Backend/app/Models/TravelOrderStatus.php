<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrderStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false;

    public function travelOrders()
    {
        return $this->hasMany(TravelOrder::class);
    }
}
