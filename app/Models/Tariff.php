<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    protected $fillable = [
        'name', 
        'tariff_code', 
        'power_va', 
        'price_per_kwh'
    ];

    public function simulations()
    {
        return $this->hasMany(Simulation::class);
    }
}
