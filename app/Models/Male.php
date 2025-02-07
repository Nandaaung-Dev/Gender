<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Male extends Model
{
    use HasFactory;
    protected $fillable = ['name','age','location_id'];

    public function location() {
        return $this->belongsTo(Location::class);
    }
}
