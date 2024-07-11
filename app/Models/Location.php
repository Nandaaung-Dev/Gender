<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function male()
    {
        return $this->hasMany(Male::class, 'location_id', 'id');
    }

    public function female()
    {
        return $this->hasMany(Female::class, 'location_id', 'id');
    }

    public function other()
    {
        return $this->hasMany(Other::class, 'location_id', 'id');
    }
}
