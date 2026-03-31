<?php

namespace App\Livewire;

use Livewire\Component;

class Calculator extends Component
{
    // Form Inputs
    public $length = 10;
    public $width = 8;
    public $tariff = 'R-2 / 3.500 VA';
    public $bill = '1.500.000';
    public $budget = 25000000;

    // Map Coordinates (Bandung as default)
    public $latitude = -6.914744;
    public $longitude = 107.609810;
    public $locationName = 'Bandung, Jawa Barat';

    // Computed
    public $area = 80;

    public function updated($propertyName)
    {
        if ($propertyName === 'length' || $propertyName === 'width') {
            $this->area = (float)($this->length ?: 0) * (float)($this->width ?: 0);
        }
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
        return view('livewire.calculator')
            ->layout('layouts.calculator');
    }
}
