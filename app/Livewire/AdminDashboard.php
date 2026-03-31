<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tariff;
use App\Models\Simulation;

class AdminDashboard extends Component
{
    // Form properties
    public $isModalOpen = false;
    public $editingId = null;
    public $name, $tariff_code, $power_va, $price_per_kwh;

    protected $rules = [
        'name' => 'required|string|max:100',
        'tariff_code' => 'required|string|max:10',
        'power_va' => 'required|string|max:20',
        'price_per_kwh' => 'required|numeric|min:0',
    ];

    public function openModal($id = null)
    {
        $this->resetErrorBag();
        $this->editingId = $id;

        if ($id) {
            $tariff = Tariff::find($id);
            if ($tariff) {
                $this->name = $tariff->name;
                $this->tariff_code = $tariff->tariff_code;
                $this->power_va = $tariff->power_va;
                $this->price_per_kwh = $tariff->price_per_kwh;
            }
        } else {
            $this->reset(['name', 'tariff_code', 'power_va', 'price_per_kwh']);
        }

        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset(['editingId', 'name', 'tariff_code', 'power_va', 'price_per_kwh']);
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->editingId) {
            Tariff::find($this->editingId)->update($validatedData);
            session()->flash('message', 'tariff updated successfully');
        } else {
            Tariff::create($validatedData);
            session()->flash('message', 'new tariff added successfully');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        Tariff::find($id)->delete();
        session()->flash('message', 'tariff deleted successfully');
    }

    public function render()
    {
        return view('livewire.admin-dashboard', [
            'tariffs' => Tariff::all(),
            'simulations' => Simulation::with(['user', 'tariff'])->latest()->get()
        ])->layout('layouts.calculator'); // reuse calculator layout for now
    }
}
