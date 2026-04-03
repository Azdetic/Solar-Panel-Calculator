<?php

namespace App\Livewire;

use Livewire\Component;

class Calculator extends Component
{
    // Form Inputs
    public $length = 10;
    public $width = 8;
    public $tariff_id;
    public $bill = 1500000;
    public $budget = 25000000;

    // Error Reporting
    public $errorMessage = null;

    // Map Coordinates (Bandung as default)
    public $latitude = -6.914744;
    public $longitude = 107.609810;
    public $locationName = 'Bandung, Jawa Barat';

    // Results from NASA & Math
    public $simulationResult = null;

    // Saved Simulation ID for RFQ
    public $savedSimulationId = null;

    // Vendor Selection
    public $isVendorModalOpen = false;
    public $selectedVendorId = null;
    public $vendorsList = [];

    // Computed
    public $area = 80;

    public function mount()
    {
        $defaultTariff = \App\Models\Tariff::first();
        if ($defaultTariff) {
            $this->tariff_id = $defaultTariff->id;
        }
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'length' || $propertyName === 'width') {
            $this->area = (float)($this->length ?: 0) * (float)($this->width ?: 0);
        }
    }

    // Triggered when user clicks 'Hitung Sekarang'
    public function calculateResult()
    {
        $this->errorMessage = null; // reset
        
        // Clean inputs from dots/commas before validation
        $this->bill = str_replace(['.', ','], '', $this->bill);
        $this->budget = str_replace(['.', ','], '', $this->budget);

        $this->validate([
            'length' => 'required|numeric|min:1',
            'width' => 'required|numeric|min:1',
            'tariff_id' => 'required|exists:tariffs,id',
            'bill' => 'required|numeric|min:0',
            'budget' => 'required|numeric|min:1000000',
        ]);

        try {
            // Fetch NASA POWER Global Horizontal Irradiance (GHI)
            // ALLSKY_SFC_SW_DWN is the average solar radiation arriving at the surface (kWh/m^2/day)
            $response = \Illuminate\Support\Facades\Http::timeout(10)->get('https://power.larc.nasa.gov/api/temporal/climatology/point', [
                'parameters' => 'ALLSKY_SFC_SW_DWN',
                'community' => 'RE',
                'longitude' => $this->longitude,
                'latitude' => $this->latitude,
                'format' => 'JSON',
            ]);

            $ghi = 4.5; // Fallback default (Indonesian average) if API fails
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['properties']['parameter']['ALLSKY_SFC_SW_DWN']['ANN'])) {
                    $ghi = (float) $data['properties']['parameter']['ALLSKY_SFC_SW_DWN']['ANN'];
                }
            }

            // --- Solar Math Calculation (As per README Spec) ---
            
            // MAP PLN Tariff to Price Per kWh
            $selectedTariff = \App\Models\Tariff::find($this->tariff_id);
            $pricePerKwh = $selectedTariff ? $selectedTariff->price_per_kwh : 1444;

            // Constants from spec
            $systemEfficiency  = 0.75;
            $roofUsableFactor  = 0.75;
            $m2PerPanel        = 6.5;
            $kwpPerPanel       = 0.4;
            $pricePerKwp       = 11000000;
            $panelLifespan     = 25;
            $co2Factor         = 0.785;

            // STEP 1 - Area
            $roofArea   = $this->area;
            $usableArea = $roofArea * $roofUsableFactor;
            
            // STEP 2 - Capacity from roof
            $capacityFromRoof = ($usableArea / $m2PerPanel) * $kwpPerPanel;
            
            // STEP 3 - Capacity from budget
            $capacityFromBudget = $this->budget / $pricePerKwp;
            
            // STEP 4 - Installed capacity (bottleneck)
            $installedCapacity = min($capacityFromRoof, $capacityFromBudget);
            $bottleneck = $capacityFromRoof < $capacityFromBudget ? 'roof' : 'budget';
            
            // STEP 5 - Energy production
            $productionPerDay   = $installedCapacity * $ghi * $systemEfficiency;
            $productionPerMonth = $productionPerDay * 30;
            $productionPerYear  = $productionPerMonth * 12;
            
            // STEP 6 - Savings
            $savingsPerMonth = $productionPerMonth * $pricePerKwh;
            $savingsPerYear  = $savingsPerMonth * 12;
            
            // STEP 7 - Investment & Payback
            $investmentCost = $installedCapacity * $pricePerKwp;
            $paybackYears   = $savingsPerYear > 0 ? $investmentCost / $savingsPerYear : 0;
            $remainingLifespan = max(0, $panelLifespan - $paybackYears);
            
            // STEP 8 - CO2 reduction
            $co2PerYear = $productionPerYear * $co2Factor;
            
            // STEP 9 - Energy Independence
            $estimatedUsageKwh = max(1, $this->bill / $pricePerKwh); // Prevent division by zero
            $independencePercent = min(100, ($productionPerMonth / $estimatedUsageKwh) * 100);
            
            // Save results to display
            $this->simulationResult = [
                // Source
                'ghi'                  => round($ghi, 2),
                'bottleneck'           => $bottleneck,
                
                // Capacity Breakdown
                'capacity_from_roof'   => round($capacityFromRoof, 2),
                'capacity_from_budget' => round($capacityFromBudget, 2),
                'installed_capacity'   => round($installedCapacity, 2),
                'investment_cost'      => round($investmentCost, 0),
                
                // Production & Savings
                'production_per_month' => round($productionPerMonth, 1),
                'production_per_year'  => round($productionPerYear, 1),
                'savings_per_month'    => round($savingsPerMonth, 0),
                'savings_per_year'     => round($savingsPerYear, 0),
                
                // Stats
                'payback_years'        => round($paybackYears, 1),
                'remaining_lifespan'   => round($remainingLifespan, 1),
                'co2_per_year'         => round($co2PerYear, 0),
                'independence_percent' => round($independencePercent, 0),
                'estimated_usage_kwh'  => round($estimatedUsageKwh, 1)
            ];

            // Project Rubric: Transaction Recording if logged in
            if (auth()->check()) {
                $savedSim = \App\Models\Simulation::create([
                    'user_id' => auth()->id(),
                    'tariff_id' => $this->tariff_id,
                    'location_name' => $this->locationName,
                    'latitude' => $this->latitude,
                    'longitude' => $this->longitude,
                    'roof_length' => $this->length,
                    'roof_width' => $this->width,
                    'average_monthly_bill' => $this->bill,
                    'estimated_budget' => $this->budget,
                    'ghi_value' => round($ghi, 2),
                ]);
                $this->savedSimulationId = $savedSim->id;
            }

        } catch (\Exception $e) {
            // Error handling
            $this->errorMessage = 'Calculation Error: ' . $e->getMessage();
        }
    }

    public function requestQuotation()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!$this->savedSimulationId) {
            $this->errorMessage = 'Terjadi kesalahan: Data simulasi belum tersimpan. Silakan hitung ulang.';
            return;
        }

        // Cek apakah sudah pernah request untuk simulasi ini
        $existing = \App\Models\Quotation::where('simulation_id', $this->savedSimulationId)->first();
        if ($existing) {
            $this->errorMessage = 'Anda sudah mengajukan penawaran untuk simulasi ini.';
            return;
        }

        // Prepare vendor list and open modal
        $this->vendorsList = \App\Models\User::where('role', 'vendor')->get();
        if ($this->vendorsList->isEmpty()) {
            $this->errorMessage = 'Saat ini belum ada vendor yang tersedia.';
            return;
        }
        
        $this->isVendorModalOpen = true;
    }

    public function closeVendorModal()
    {
        $this->isVendorModalOpen = false;
        $this->selectedVendorId = null;
    }

    public function submitQuotationRequest()
    {
        $this->validate([
            'selectedVendorId' => 'required|exists:users,id',
        ]);

        \App\Models\Quotation::create([
            'user_id' => auth()->id(),
            'simulation_id' => $this->savedSimulationId,
            'vendor_id' => $this->selectedVendorId,
            'status' => 'requested',
        ]);

        $this->closeVendorModal();
        session()->flash('message', 'Quote request sent! Look out for vendor messages in your dashboard.');
    }

    // Method to handle map clicks/drags from JS
    public function updateLocation($lat, $lng, $name = null)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;

        if ($name) {
            $this->locationName = $name;
        } else {
            // Reverse Geocoding via OpenStreetMap Nominatim API
            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'User-Agent' => 'SolarSmartApp/1.0 (Laravel)'
                ])->get('https://nominatim.openstreetmap.org/reverse', [
                    'format' => 'jsonv2',
                    'lat' => $lat,
                    'lon' => $lng,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['address'])) {
                        $addr = $data['address'];
                        $city = $addr['city'] ?? $addr['town'] ?? $addr['village'] ?? $addr['county'] ?? $addr['suburb'] ?? '';
                        $state = $addr['state'] ?? $addr['region'] ?? '';

                        $resolved = trim("$city, $state", ", ");
                        if (empty($resolved)) {
                            $resolved = $data['name'] ?? $data['display_name'] ?? 'Lokasi tidak diketahui';
                        }
                        
                        // Limit string length for UI
                        $parts = explode(',', $resolved);
                        if (count($parts) > 2) {
                            $resolved = trim($parts[0] . ', ' . $parts[1]);
                        }

                        $this->locationName = $resolved;
                    }
                }
            } catch (\Exception $e) {
                // Ignore failure silently
            }
        }
    }

    public function render()
    {
        return view('livewire.calculator', [
            'tariffs' => \App\Models\Tariff::all()
        ])->layout('layouts.calculator');
    }
}

