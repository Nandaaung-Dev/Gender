<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function males()
    {
        return $this->hasMany(Male::class, 'location_id', 'id');
    }

    public function females()
    {
        return $this->hasMany(Female::class, 'location_id', 'id');
    }

    public function others()
    {
        return $this->hasMany(Other::class, 'location_id', 'id');
    }
}
