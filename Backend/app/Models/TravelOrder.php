<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination', 'departure_date', 'return_date', 'status_id', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(TravelOrderStatus::class);
    }

    public function scopeAccessibleBy($query, $user)
    {
        if ($user->permissions->name !== 'admin') {
            $query->where('user_id', $user->id);
        }
        return $query;
    }
}
