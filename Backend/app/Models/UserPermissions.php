<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermissions extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false;

    public function travelOrders()
    {
        return $this->hasMany(User::class, 'permission_id');
    }
}
