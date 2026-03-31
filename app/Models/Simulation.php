<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simulation extends Model
{
    protected $fillable = [
        'user_id',
        'tariff_id',
        'location_name',
        'latitude',
        'longitude',
        'roof_length',
        'roof_width',
        'average_monthly_bill',
        'estimated_budget',
        'ghi_value'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tariff()
    {
        return $this->belongsTo(Tariff::class);
    }
}
