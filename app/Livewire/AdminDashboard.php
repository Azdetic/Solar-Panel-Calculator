<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tariff;
use App\Models\Simulation;
use App\Models\User;
use App\Models\Quotation;
use Illuminate\Support\Facades\Auth;

class AdminDashboard extends Component
{
    public $activeTab = 'users';

    public function mount()
    {
        if (Auth::check() && Auth::user()->role === 'vendor') {
            $this->activeTab = 'quotations';
        }
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    // Form properties (Users)
    public $isUserModalOpen = false;
    public $editingUserId = null;
    public $userName, $userEmail, $userPassword;
    public $userRole = 'user';

    // Form properties (Quotations)
    public $isQuoteModalOpen = false;
    public $editingQuoteId = null;
    public $quoteStatus = '';
    public $quoteAmount = null;
    public $quoteNotes = '';

    // Form properties (Tariffs)
    public $isModalOpen = false;
    public $editingId = null;
    public $name, $tariff_code, $power_va, $price_per_kwh;

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    }

    protected $rules = [
        'name' => 'required|string|max:100',
        'tariff_code' => 'required|string|max:10',
        'power_va' => 'required|string|max:20',
        'price_per_kwh' => 'required|numeric|min:0',
    ];

    // User Methods
    public function openUserModal($id = null)
    {
        $this->resetErrorBag();
        $this->editingUserId = $id;

        if ($id) {
            $user = User::find($id);
            if ($user) {
                $this->userName = $user->name;
                $this->userEmail = $user->email;
                $this->userRole = $user->role;
                $this->userPassword = '';
            }
        } else {
            $this->reset(['userName', 'userEmail', 'userPassword']);
            $this->userRole = 'user';
        }

        $this->isUserModalOpen = true;
    }

    public function closeUserModal()
    {
        $this->isUserModalOpen = false;
        $this->reset(['editingUserId', 'userName', 'userEmail', 'userPassword', 'userRole']);
    }

    public function saveUser()
    {
        $rules = [
            'userName' => 'required|string|max:255',
            'userEmail' => 'required|email|unique:users,email' . ($this->editingUserId ? ',' . $this->editingUserId : ''),
            'userRole' => 'required|in:admin,vendor,user',
            'userPassword' => $this->editingUserId ? 'nullable|min:6' : 'required|min:6',
        ];
        
        $this->validate($rules);

        $data = [
            'name' => $this->userName,
            'email' => $this->userEmail,
            'role' => $this->userRole,
        ];
        
        if ($this->userPassword) {
            $data['password'] = bcrypt($this->userPassword);
        }

        if ($this->editingUserId) {
            User::find($this->editingUserId)->update($data);
            session()->flash('message', 'User updated successfully');
        } else {
            User::create($data);
            session()->flash('message', 'User created successfully');
        }

        $this->closeUserModal();
        $this->activeTab = 'users';
    }

    public function deleteUser($id)
    {
        if (auth()->id() == $id) {
            session()->flash('message', 'Cannot delete your own account.');
            return;
        }
        User::find($id)->delete();
        session()->flash('message', 'User deleted successfully');
    }

    // Quotation Methods
    public function openQuoteModal($id)
    {
        $this->resetErrorBag();
        $quote = Quotation::find($id);
        if (!$quote) {
            return;
        }

        if (Auth::user()->role === 'vendor' && (int) $quote->vendor_id !== (int) Auth::id()) {
            session()->flash('message', 'You are not allowed to update this quotation.');
            return;
        }

        $this->editingQuoteId = $id;
        $this->quoteStatus = $quote->status;
        $this->quoteAmount = $quote->total_amount;
        $this->quoteNotes = $quote->vendor_notes;

        $this->isQuoteModalOpen = true;
    }

    public function closeQuoteModal()
    {
        $this->isQuoteModalOpen = false;
        $this->reset(['editingQuoteId', 'quoteStatus', 'quoteAmount', 'quoteNotes']);
    }

    public function saveQuote()
    {
        $this->validate([
            'quoteStatus' => 'required|in:requested,reviewed,quotation_sent,accepted,rejected,completed',
            'quoteAmount' => 'nullable|numeric|min:0',
            'quoteNotes' => 'nullable|string|max:5000',
        ]);
        
        if ($this->editingQuoteId) {
            $quote = Quotation::find($this->editingQuoteId);
            if (!$quote) {
                session()->flash('message', 'Quotation not found.');
                $this->closeQuoteModal();
                return;
            }

            if (Auth::user()->role === 'vendor' && (int) $quote->vendor_id !== (int) Auth::id()) {
                session()->flash('message', 'You are not allowed to update this quotation.');
                $this->closeQuoteModal();
                return;
            }

            $quote->update([
                'status' => $this->quoteStatus,
                'total_amount' => $this->quoteAmount,
                'vendor_notes' => $this->quoteNotes,
            ]);
            session()->flash('message', 'Quotation status updated successfully');
        }
        
        $this->closeQuoteModal();
    }

    public function quickUpdateQuoteStatus($id, $status)
    {
        $allowedStatuses = ['reviewed', 'quotation_sent', 'accepted', 'rejected', 'completed'];
        if (!in_array($status, $allowedStatuses, true)) {
            return;
        }

        $quote = Quotation::find($id);
        if (!$quote) {
            session()->flash('message', 'Quotation not found.');
            return;
        }

        if (Auth::user()->role === 'vendor' && (int) $quote->vendor_id !== (int) Auth::id()) {
            session()->flash('message', 'You are not allowed to update this quotation.');
            return;
        }

        $quote->update(['status' => $status]);
        session()->flash('message', 'Quotation status updated to ' . str_replace('_', ' ', $status) . '.');
    }

    // Tariff Methods
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
        $viewData = [
            'users' => [],
            'tariffs' => [],
            'simulations' => [],
            'quotations' => []
        ];

        if ($this->activeTab === 'users') {
            $viewData['users'] = User::latest()->get();
        } elseif ($this->activeTab === 'tariffs') {
            $viewData['tariffs'] = Tariff::all();
        } elseif ($this->activeTab === 'simulations') {
            $viewData['simulations'] = Simulation::with(['user', 'tariff'])->latest()->get();
        } elseif ($this->activeTab === 'quotations') {
            $query = Quotation::with(['user', 'vendor', 'simulation']);

            if (Auth::check() && Auth::user()->role === 'vendor') {
                $query->where('vendor_id', Auth::id());
            }

            $viewData['quotations'] = $query->latest()->get();
        }

        return view('livewire.admin-dashboard', $viewData)->layout('layouts.calculator'); // reuse calculator layout for now
    }
}
