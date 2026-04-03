<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'simulation_id', 'user_id', 'vendor_id', 'status', 'total_amount', 'vendor_notes'
    ];

    public function simulation()
    {
        return $this->belongsTo(Simulation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
