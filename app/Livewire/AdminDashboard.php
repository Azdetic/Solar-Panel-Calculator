<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tariff;
use App\Models\Simulation;

class AdminDashboard extends Component
{
    public function render()
    {
        return view('livewire.admin-dashboard', [
            'tariffs' => Tariff::all(),
            'simulations' => Simulation::with(['user', 'tariff'])->latest()->get()
        ])->layout('layouts.calculator'); // reuse calculator layout for now
    }
}
