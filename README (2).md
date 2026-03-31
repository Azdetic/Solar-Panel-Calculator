git push# SolarSmart — IDE Prompt / Build Spec

## Project Overview

Build a **Solar Panel Feasibility Calculator** called **SolarSmart** as a single-page Laravel application.
The page is a split-pane dashboard: interactive map on the left, input form + results on the right.
No multi-step wizard. Everything is on one page.

---

## Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade + Livewire 3 + Tailwind CSS v3
- **Map**: Leaflet.js + OpenStreetMap (loaded via CDN, no API key needed)
- **Geocoding**: Nominatim API (OpenStreetMap, free, no API key)
- **Solar Data**: NASA POWER API (free, no API key)
- **PDF Export**: barryvdh/laravel-dompdf
- **Database**: MySQL 8+ (for caching NASA API results)

---

## Page Layout — Single Page (`/`)

```
┌─────────────────────┬──────────────────────────────────────┐
│                     │  FORM + RESULTS PANEL                │
│   MAP PANEL         │                                      │
│   (Leaflet.js)      │  [Input fields when no result yet]   │
│                     │  [Result cards after calculate]      │
│   Click to drop     │                                      │
│   a marker          │                                      │
└─────────────────────┴──────────────────────────────────────┘
```

The right panel has two states:
- **State A (default)**: Shows the input form
- **State B (after calculate)**: Hides form, shows result cards + a "Hitung Ulang" button to go back to State A

---

## File Structure to Create

```
app/
├── Http/
│   └── Livewire/
│       └── SolarCalculator.php        ← main Livewire component
├── Services/
│   └── CalculationService.php         ← all formula logic
│   └── NasaPowerService.php           ← NASA API call + caching

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php              ← main layout (includes Leaflet CDN)
│   ├── livewire/
│   │   └── solar-calculator.blade.php ← the single page view
│   └── welcome.blade.php              ← redirects to /calculator

database/
└── migrations/
    └── create_solar_cache_table.php   ← cache NASA results

routes/
└── web.php                            ← one route: GET /
```

---

## Route

```php
// routes/web.php
Route::get('/', function () {
    return view('welcome');
});

Route::get('/calculator', SolarCalculator::class)->name('calculator');
```

---

## Database Migration — `solar_cache`

```php
Schema::create('solar_cache', function (Blueprint $table) {
    $table->id();
    $table->decimal('lat', 8, 4);
    $table->decimal('lng', 8, 4);
    $table->float('ghi'); // Global Horizontal Irradiance, kWh/m²/day
    $table->timestamp('cached_at');
    $table->timestamps();
});
```

Cache lookup: find a row where `abs(lat - input_lat) < 0.1 AND abs(lng - input_lng) < 0.1`
and `cached_at > now() - 30 days`. If found, use cached GHI. If not, call NASA API and insert.

---

## NasaPowerService.php

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NasaPowerService
{
    public function getGHI(float $lat, float $lng): float
    {
        // 1. Check cache
        $cached = DB::table('solar_cache')
            ->whereRaw('ABS(lat - ?) < 0.1', [$lat])
            ->whereRaw('ABS(lng - ?) < 0.1', [$lng])
            ->where('cached_at', '>=', Carbon::now()->subDays(30))
            ->first();

        if ($cached) {
            return $cached->ghi;
        }

        // 2. Call NASA POWER API
        $response = Http::timeout(10)->get('https://power.larc.nasa.gov/api/temporal/climatology/point', [
            'parameters' => 'ALLSKY_SFC_SW_DWN',
            'community'  => 'RE',
            'longitude'  => $lng,
            'latitude'   => $lat,
            'format'     => 'JSON',
        ]);

        // ALLSKY_SFC_SW_DWN returns monthly averages + annual mean
        // The annual mean key is "ANN"
        $ghi = $response->json('properties.parameter.ALLSKY_SFC_SW_DWN.ANN');

        // 3. Store in cache
        DB::table('solar_cache')->insert([
            'lat'        => $lat,
            'lng'        => $lng,
            'ghi'        => $ghi,
            'cached_at'  => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return (float) $ghi;
    }
}
```

---

## CalculationService.php

All formulas live here. No calculation logic in the Livewire component.

```php
<?php

namespace App\Services;

class CalculationService
{
    // Constants
    const SYSTEM_EFFICIENCY    = 0.75;  // 75% system efficiency
    const ROOF_USABLE_FACTOR   = 0.75;  // 75% of roof area is usable
    const M2_PER_PANEL         = 6.5;   // m² needed per 400Wp panel
    const PANEL_CAPACITY_KWP   = 0.4;   // kWp per panel (400Wp)
    const PRICE_PER_KWP        = 11_000_000; // IDR per kWp (market estimate 2024)
    const DAYS_PER_MONTH       = 30;
    const PANEL_LIFESPAN_YEARS = 25;
    const CO2_FACTOR           = 0.785; // kg CO2 per kWh (PLN grid, IPCC AR6)

    // PLN tariff map (golongan => Rp/kWh)
    const PLN_TARIFFS = [
        'R1_900'   => 1352,
        'R1_1300'  => 1444,
        'R1_2200'  => 1444,
        'R2_3500'  => 1699,
        'R3_6600'  => 1699,
    ];

    public function calculate(
        float  $lat,
        float  $lng,
        float  $roofLength,   // meters
        float  $roofWidth,    // meters
        string $plnGolongan,  // key from PLN_TARIFFS
        float  $monthlyBill,  // IDR
        float  $budget,       // IDR
        float  $ghi           // kWh/m²/day from NASA
    ): array {

        // --- Step 1: Roof area ---
        $roofArea        = $roofLength * $roofWidth;                         // m²
        $usableArea      = $roofArea * self::ROOF_USABLE_FACTOR;             // m²

        // --- Step 2: Capacity from roof ---
        $capacityFromRoof = ($usableArea / self::M2_PER_PANEL) * self::PANEL_CAPACITY_KWP; // kWp

        // --- Step 3: Capacity from budget ---
        $capacityFromBudget = $budget / self::PRICE_PER_KWP;                // kWp

        // --- Step 4: Installed capacity (bottleneck) ---
        $installedCapacity  = min($capacityFromRoof, $capacityFromBudget);  // kWp
        $bottleneck         = $capacityFromRoof < $capacityFromBudget ? 'roof' : 'budget';

        // --- Step 5: Energy production ---
        $productionPerDay   = $installedCapacity * $ghi * self::SYSTEM_EFFICIENCY;         // kWh/day
        $productionPerMonth = $productionPerDay * self::DAYS_PER_MONTH;                    // kWh/month
        $productionPerYear  = $productionPerMonth * 12;                                    // kWh/year

        // --- Step 6: Savings ---
        $tariffPerKwh       = self::PLN_TARIFFS[$plnGolongan];
        $savingsPerMonth    = $productionPerMonth * $tariffPerKwh;                         // IDR/month
        $savingsPerYear     = $savingsPerMonth * 12;                                       // IDR/year

        // --- Step 7: Investment & payback ---
        $investmentCost     = $installedCapacity * self::PRICE_PER_KWP;                   // IDR
        $paybackYears       = $savingsPerYear > 0 ? $investmentCost / $savingsPerYear : 0; // years
        $remainingLifespan  = self::PANEL_LIFESPAN_YEARS - $paybackYears;

        // --- Step 8: CO2 reduction ---
        $co2PerYear         = $productionPerYear * self::CO2_FACTOR;                       // kg/year

        // --- Step 9: Energy independence % ---
        $estimatedUsageKwh      = $monthlyBill / $tariffPerKwh;                            // kWh/month
        $independencePercent    = $estimatedUsageKwh > 0
            ? min(100, round(($productionPerMonth / $estimatedUsageKwh) * 100))
            : 0;

        return [
            // Inputs (echo back)
            'ghi'                   => round($ghi, 2),
            'roof_area'             => round($roofArea, 1),
            'usable_area'           => round($usableArea, 1),

            // Capacity
            'capacity_from_roof'    => round($capacityFromRoof, 2),
            'capacity_from_budget'  => round($capacityFromBudget, 2),
            'installed_capacity'    => round($installedCapacity, 2),
            'bottleneck'            => $bottleneck,  // 'roof' or 'budget'

            // Production
            'production_per_day'    => round($productionPerDay, 2),
            'production_per_month'  => round($productionPerMonth, 1),
            'production_per_year'   => round($productionPerYear, 1),

            // Financial
            'tariff_per_kwh'        => $tariffPerKwh,
            'savings_per_month'     => round($savingsPerMonth),
            'savings_per_year'      => round($savingsPerYear),
            'investment_cost'       => round($investmentCost),
            'payback_years'         => round($paybackYears, 1),
            'remaining_lifespan'    => round(max(0, $remainingLifespan), 1),

            // Environment
            'co2_per_year'          => round($co2PerYear),

            // Independence
            'independence_percent'  => $independencePercent,
            'estimated_usage_kwh'   => round($estimatedUsageKwh, 1),
        ];
    }
}
```

---

## SolarCalculator.php (Livewire Component)

```php
<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\NasaPowerService;
use App\Services\CalculationService;

class SolarCalculator extends Component
{
    // Map inputs (set from JS via dispatch)
    public float  $lat           = -6.2088;   // default: Jakarta
    public float  $lng           = 106.8456;
    public string $locationName  = '';

    // Form inputs
    public float  $roofLength    = 0;
    public float  $roofWidth     = 0;
    public string $plnGolongan   = '';
    public float  $monthlyBill   = 0;
    public float  $budget        = 0;

    // State
    public bool   $isLoading     = false;
    public bool   $showResult    = false;
    public ?array $result        = null;
    public ?string $errorMessage = null;

    // Computed (reactive)
    public float $roofArea    = 0;
    public float $usableArea  = 0;
    public float $estimatedKwh = 0;

    protected array $rules = [
        'lat'          => 'required|numeric|between:-11,6',
        'lng'          => 'required|numeric|between:95,141',
        'roofLength'   => 'required|numeric|min:5|max:200',
        'roofWidth'    => 'required|numeric|min:5|max:200',
        'plnGolongan'  => 'required|in:R1_900,R1_1300,R1_2200,R2_3500,R3_6600',
        'monthlyBill'  => 'required|numeric|min:50000',
        'budget'       => 'required|numeric|min:5000000',
    ];

    // Called from Blade JS when user clicks map
    public function updateLocation(float $lat, float $lng, string $name): void
    {
        $this->lat          = $lat;
        $this->lng          = $lng;
        $this->locationName = $name;
    }

    // Reactive: recalculate roof preview on input change
    public function updatedRoofLength(): void { $this->recalcRoof(); }
    public function updatedRoofWidth(): void  { $this->recalcRoof(); }

    private function recalcRoof(): void
    {
        $this->roofArea   = round($this->roofLength * $this->roofWidth, 1);
        $this->usableArea = round($this->roofArea * 0.75, 1);
    }

    // Reactive: estimate kWh from bill input
    public function updatedMonthlyBill(): void
    {
        $tariffs = \App\Services\CalculationService::PLN_TARIFFS;
        if ($this->plnGolongan && $this->monthlyBill > 0) {
            $this->estimatedKwh = round($this->monthlyBill / $tariffs[$this->plnGolongan], 1);
        }
    }

    public function updatedPlnGolongan(): void
    {
        $this->updatedMonthlyBill();
    }

    public function calculate(): void
    {
        $this->validate();

        $this->isLoading     = true;
        $this->errorMessage  = null;

        try {
            $nasaService  = app(NasaPowerService::class);
            $calcService  = app(CalculationService::class);

            $ghi          = $nasaService->getGHI($this->lat, $this->lng);
            $this->result = $calcService->calculate(
                $this->lat,
                $this->lng,
                $this->roofLength,
                $this->roofWidth,
                $this->plnGolongan,
                $this->monthlyBill,
                $this->budget,
                $ghi
            );

            $this->showResult = true;

        } catch (\Exception $e) {
            $this->errorMessage = 'Gagal mengambil data. Pastikan koneksi internet tersedia dan coba lagi.';
        }

        $this->isLoading = false;
    }

    public function reset(): void
    {
        $this->showResult   = false;
        $this->result       = null;
        $this->errorMessage = null;
    }

    public function render()
    {
        return view('livewire.solar-calculator')->layout('layouts.app');
    }
}
```

---

## layouts/app.blade.php

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SolarSmart — Kalkulator Panel Surya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    {{-- Navbar --}}
    <nav class="h-14 bg-white border-b border-gray-200 flex items-center px-6 gap-3">
        <div class="w-7 h-7 bg-amber-400 rounded-lg flex items-center justify-center text-white font-bold text-sm">S</div>
        <span class="font-semibold text-gray-800">SolarSmart</span>
        <span class="text-xs text-gray-400 ml-1">Kalkulator Panel Surya</span>
    </nav>

    {{-- Main content fills viewport below navbar --}}
    <main class="h-[calc(100vh-3.5rem)]">
        {{ $slot }}
    </main>

    @livewireScripts

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @stack('scripts')
</body>
</html>
```

---

## livewire/solar-calculator.blade.php

```html
<div class="flex h-full">

    {{-- ═══════════════════════════════════════ --}}
    {{-- LEFT: MAP PANEL                         --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="w-1/2 flex flex-col border-r border-gray-200">

        {{-- Search bar --}}
        <div class="p-3 border-b border-gray-200 bg-white">
            <input
                id="map-search"
                type="text"
                placeholder="Cari kota atau klik langsung di peta..."
                class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:border-amber-400"
            />
        </div>

        {{-- Map container --}}
        <div id="map" class="flex-1"></div>

        {{-- Selected location badge --}}
        @if($locationName)
        <div class="px-4 py-2 bg-white border-t border-gray-200 flex items-center gap-2">
            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
            <span class="text-sm text-gray-700">{{ $locationName }}</span>
            <span class="text-xs text-gray-400 ml-auto">{{ round($lat,4) }}, {{ round($lng,4) }}</span>
        </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- RIGHT: FORM / RESULT PANEL              --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="w-1/2 flex flex-col bg-white overflow-y-auto">

        {{-- Error message --}}
        @if($errorMessage)
        <div class="m-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
            {{ $errorMessage }}
        </div>
        @endif

        {{-- ─── STATE A: INPUT FORM ─── --}}
        @if(!$showResult)
        <div class="p-5 flex flex-col gap-5">

            <div>
                <h2 class="text-base font-semibold text-gray-800">Detail Properti</h2>
                <p class="text-xs text-gray-400 mt-0.5">Lengkapi semua field lalu klik Hitung</p>
            </div>

            {{-- Roof size --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Ukuran Atap</label>
                <div class="grid grid-cols-2 gap-3">
                    <div class="relative">
                        <input wire:model.live="roofLength" type="number" min="5" max="200" placeholder="Panjang"
                            class="w-full h-9 px-3 pr-8 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-amber-400" />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">m</span>
                    </div>
                    <div class="relative">
                        <input wire:model.live="roofWidth" type="number" min="5" max="200" placeholder="Lebar"
                            class="w-full h-9 px-3 pr-8 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-amber-400" />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">m</span>
                    </div>
                </div>
                @if($roofArea > 0)
                <div class="mt-2 flex justify-between text-xs bg-amber-50 rounded-lg px-3 py-2">
                    <span class="text-gray-500">Luas total: <strong class="text-gray-800">{{ $roofArea }} m²</strong></span>
                    <span class="text-gray-400">Area efektif: {{ $usableArea }} m²</span>
                </div>
                @endif
                @error('roofLength') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @error('roofWidth')  <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- PLN golongan --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Golongan Tarif PLN</label>
                <select wire:model.live="plnGolongan"
                    class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-amber-400 bg-white">
                    <option value="">Pilih golongan...</option>
                    <option value="R1_900">R-1 / 900 VA — Rp 1.352/kWh</option>
                    <option value="R1_1300">R-1 / 1.300 VA — Rp 1.444/kWh</option>
                    <option value="R1_2200">R-1 / 2.200 VA — Rp 1.444/kWh</option>
                    <option value="R2_3500">R-2 / 3.500–5.500 VA — Rp 1.699/kWh</option>
                    <option value="R3_6600">R-3 / 6.600 VA ke atas — Rp 1.699/kWh</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">Cek golongan di tagihan listrik bulanan Anda</p>
                @error('plnGolongan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Monthly bill --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Tagihan Listrik / Bulan</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">Rp</span>
                    <input wire:model.live="monthlyBill" type="number" min="50000" placeholder="0"
                        class="w-full h-9 pl-8 pr-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-amber-400" />
                </div>
                @if($estimatedKwh > 0)
                <div class="mt-1.5 inline-flex items-center gap-1 bg-amber-50 text-amber-800 text-xs px-2.5 py-1 rounded">
                    ≈ {{ $estimatedKwh }} kWh/bulan
                </div>
                @endif
                @error('monthlyBill') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Budget --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Anggaran Investasi</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">Rp</span>
                    <input wire:model.live="budget" type="number" min="5000000" placeholder="0"
                        class="w-full h-9 pl-8 pr-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-amber-400" />
                </div>
                <div class="mt-2 text-xs text-gray-400 bg-gray-50 rounded-lg px-3 py-2 leading-relaxed">
                    Referensi: Sistem 1 kWp ~Rp 11.000.000 · 2 kWp ~Rp 22.000.000 · 4 kWp ~Rp 44.000.000
                    <br>* Sudah termasuk panel, inverter, dan instalasi
                </div>
                @error('budget') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Validation status --}}
            <div class="flex flex-wrap gap-2 text-xs text-gray-400">
                <span class="{{ $locationName ? 'text-green-600' : '' }}">{{ $locationName ? '✓' : '○' }} Lokasi</span>
                <span class="{{ $roofArea > 0 ? 'text-green-600' : '' }}">{{ $roofArea > 0 ? '✓' : '○' }} Atap</span>
                <span class="{{ $plnGolongan ? 'text-green-600' : '' }}">{{ $plnGolongan ? '✓' : '○' }} Tarif PLN</span>
                <span class="{{ $monthlyBill > 0 ? 'text-green-600' : '' }}">{{ $monthlyBill > 0 ? '✓' : '○' }} Tagihan</span>
                <span class="{{ $budget > 0 ? 'text-green-600' : '' }}">{{ $budget > 0 ? '✓' : '○' }} Anggaran</span>
            </div>

            {{-- Submit --}}
            <button wire:click="calculate" wire:loading.attr="disabled"
                class="w-full h-11 bg-amber-400 hover:bg-amber-500 text-white font-semibold text-sm rounded-xl transition-colors disabled:opacity-60">
                <span wire:loading.remove>✨ Hitung Sekarang</span>
                <span wire:loading>Menghitung...</span>
            </button>

        </div>
        @endif

        {{-- ─── STATE B: RESULT ─── --}}
        @if($showResult && $result)
        <div class="p-5 flex flex-col gap-4">

            {{-- Header --}}
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-800">Hasil Kalkulasi</h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $locationName }}</p>
                </div>
                <button wire:click="reset" class="text-xs text-amber-600 hover:underline">← Hitung Ulang</button>
            </div>

            {{-- Highlight --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">
                <p class="text-xs text-amber-700">Kapasitas yang direkomendasikan</p>
                <p class="text-2xl font-semibold text-amber-800">{{ $result['installed_capacity'] }} kWp</p>
                <p class="text-sm text-amber-700">Estimasi biaya: Rp {{ number_format($result['investment_cost'], 0, ',', '.') }}</p>
                <p class="text-xs text-amber-600 mt-1">
                    Bottleneck: {{ $result['bottleneck'] === 'roof' ? 'ukuran atap jadi pembatas' : 'budget jadi pembatas' }}
                </p>
            </div>

            {{-- 4 stat cards --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400">Hemat / bulan</p>
                    <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($result['savings_per_month'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400">Payback period</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $result['payback_years'] }} tahun</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400">Produksi / bulan</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $result['production_per_month'] }} kWh</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400">Kurangi CO₂ / tahun</p>
                    <p class="text-lg font-semibold text-gray-800">{{ number_format($result['co2_per_year'], 0, ',', '.') }} kg</p>
                </div>
            </div>

            {{-- Energy independence bar --}}
            <div class="bg-gray-50 rounded-xl p-3">
                <div class="flex justify-between text-xs mb-2">
                    <span class="text-gray-500">Kemandirian energi</span>
                    <span class="font-medium text-gray-800">{{ $result['independence_percent'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-amber-400 h-2 rounded-full" style="width: {{ $result['independence_percent'] }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1.5">{{ $result['independence_percent'] }}% dari kebutuhan listrik dipenuhi panel surya</p>
            </div>

            {{-- Detail accordion (pure HTML, no JS needed) --}}
            <details class="border border-gray-200 rounded-xl overflow-hidden">
                <summary class="px-4 py-3 text-sm font-medium cursor-pointer bg-gray-50 hover:bg-gray-100 text-gray-700">
                    Lihat detail kalkulasi
                </summary>
                <div class="px-4 py-3 text-xs text-gray-600 space-y-1.5">
                    <p class="font-medium text-gray-700 mb-2">Data Input</p>
                    <p>Iradiasi matahari (GHI): <strong>{{ $result['ghi'] }} kWh/m²/hari</strong> — sumber: NASA POWER</p>
                    <p>Area efektif: <strong>{{ $result['usable_area'] }} m²</strong> (75% dari {{ $result['roof_area'] }} m²)</p>
                    <p>Kapasitas dari atap: <strong>{{ $result['capacity_from_roof'] }} kWp</strong></p>
                    <p>Kapasitas dari budget: <strong>{{ $result['capacity_from_budget'] }} kWp</strong></p>
                    <div class="border-t border-gray-200 my-2"></div>
                    <p class="font-medium text-gray-700 mb-2">Hasil Perhitungan</p>
                    <p>Produksi/hari: <strong>{{ $result['production_per_day'] }} kWh</strong></p>
                    <p>Produksi/bulan: <strong>{{ $result['production_per_month'] }} kWh</strong></p>
                    <p>Produksi/tahun: <strong>{{ $result['production_per_year'] }} kWh</strong></p>
                    <p>Tarif PLN: <strong>Rp {{ number_format($result['tariff_per_kwh'], 0, ',', '.') }}/kWh</strong></p>
                    <p>Penghematan/bulan: <strong>Rp {{ number_format($result['savings_per_month'], 0, ',', '.') }}</strong></p>
                    <p>Penghematan/tahun: <strong>Rp {{ number_format($result['savings_per_year'], 0, ',', '.') }}</strong></p>
                    <p>Sisa manfaat panel setelah BEP: <strong>{{ $result['remaining_lifespan'] }} tahun</strong></p>
                    <div class="border-t border-gray-200 my-2"></div>
                    <p class="text-gray-400 italic">
                        ⚠ Hasil ini adalah estimasi berdasarkan data iradiasi rata-rata tahunan NASA POWER
                        dan harga referensi pasar 2024. Hasil aktual dapat berbeda.
                    </p>
                </div>
            </details>

        </div>
        @endif

    </div>{{-- end right panel --}}

</div>{{-- end flex --}}

@push('scripts')
<script>
document.addEventListener('livewire:initialized', () => {

    // Init Leaflet map
    const map = L.map('map').setView([-2.5, 118], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker = null;

    function placeMarker(lat, lng) {
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);
        map.setView([lat, lng], 12);
    }

    // Reverse geocode using Nominatim
    async function reverseGeocode(lat, lng) {
        try {
            const res = await fetch(
                `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`
            );
            const data = await res.json();
            const city  = data.address.city || data.address.town || data.address.county || '';
            const state = data.address.state || '';
            return city && state ? `${city}, ${state}` : data.display_name.split(',').slice(0,2).join(',');
        } catch {
            return `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        }
    }

    // Click on map
    map.on('click', async (e) => {
        const { lat, lng } = e.latlng;
        placeMarker(lat, lng);
        const name = await reverseGeocode(lat, lng);
        @this.updateLocation(lat, lng, name);
    });

    // Search bar
    const searchInput = document.getElementById('map-search');
    let debounceTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(async () => {
            const query = searchInput.value.trim();
            if (query.length < 3) return;
            try {
                const res = await fetch(
                    `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=1&countrycodes=id`
                );
                const data = await res.json();
                if (data.length > 0) {
                    const { lat, lon, display_name } = data[0];
                    placeMarker(parseFloat(lat), parseFloat(lon));
                    const name = display_name.split(',').slice(0, 2).join(',').trim();
                    @this.updateLocation(parseFloat(lat), parseFloat(lon), name);
                }
            } catch (err) {
                console.error('Geocode error', err);
            }
        }, 600);
    });

});
</script>
@endpush
```

---

## Calculation Formulas Summary

```
INPUT
  panjang_atap     → meter
  lebar_atap       → meter
  GHI              → kWh/m²/hari  (dari NASA POWER API)
  tarif_pln        → Rp/kWh       (dari golongan PLN)
  tagihan_bulanan  → Rp
  anggaran         → Rp

STEP 1 — Luas
  luas_total   = panjang × lebar
  area_efektif = luas_total × 0.75

STEP 2 — Kapasitas dari atap
  kapasitas_atap = (area_efektif ÷ 6.5) × 0.4   [kWp]

STEP 3 — Kapasitas dari budget
  kapasitas_budget = anggaran ÷ 11.000.000        [kWp]

STEP 4 — Kapasitas terpasang (bottleneck)
  kapasitas = min(kapasitas_atap, kapasitas_budget)

STEP 5 — Produksi energi
  produksi_hari  = kapasitas × GHI × 0.75         [kWh/hari]
  produksi_bulan = produksi_hari × 30              [kWh/bulan]
  produksi_tahun = produksi_bulan × 12             [kWh/tahun]

STEP 6 — Penghematan
  hemat_bulan = produksi_bulan × tarif_pln         [Rp]
  hemat_tahun = hemat_bulan × 12                   [Rp]

STEP 7 — Investasi & Payback
  biaya_investasi = kapasitas × 11.000.000         [Rp]
  payback_period  = biaya_investasi ÷ hemat_tahun  [tahun]
  sisa_manfaat    = 25 - payback_period            [tahun]

STEP 8 — CO₂
  co2_tahun = produksi_tahun × 0.785              [kg/tahun]

STEP 9 — Kemandirian energi
  estimasi_pakai = tagihan_bulanan ÷ tarif_pln    [kWh/bulan]
  independensi   = (produksi_bulan ÷ estimasi_pakai) × 100  [%]

CONSTANTS
  system_efficiency    = 0.75      (75% efisiensi sistem)
  roof_usable_factor   = 0.75      (75% area atap efektif)
  m2_per_panel         = 6.5       (m² untuk 1 panel 400Wp)
  kwp_per_panel        = 0.4       (kWp per panel)
  price_per_kwp        = 11000000  (Rp, harga referensi 2024)
  panel_lifespan       = 25        (tahun)
  co2_factor           = 0.785     (kg CO₂/kWh, faktor emisi PLN)
```

---

## Install Commands

```bash
composer require livewire/livewire barryvdh/laravel-dompdf

php artisan make:livewire SolarCalculator
php artisan make:migration create_solar_cache_table
php artisan migrate

npm install
npm run dev
```

---

## Notes for IDE

- The Livewire component is a **full-page component** (uses `->layout('layouts.app')`)
- Map JS lives inside `@push('scripts')` in the Blade view, loaded after Leaflet CDN
- `@this.updateLocation()` is the Livewire JS bridge to call PHP from map click events
- All calculation logic is isolated in `CalculationService.php` — do not put math in the component
- NASA API is called only in `NasaPowerService.php`, always check DB cache first
- Use `wire:model.live` for real-time preview (roof area, kWh estimate)
- Use `wire:loading` on the submit button to show loading state during API call
