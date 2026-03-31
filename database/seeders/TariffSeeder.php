<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TariffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tariffs = [
            ['name' => 'R-1 / 1.300 VA', 'tariff_code' => 'R-1', 'power_va' => '1.300', 'price_per_kwh' => 1444.70],
            ['name' => 'R-1 / 2.200 VA', 'tariff_code' => 'R-1', 'power_va' => '2.200', 'price_per_kwh' => 1444.70],
            ['name' => 'R-2 / 3.500 VA', 'tariff_code' => 'R-2', 'power_va' => '3.500', 'price_per_kwh' => 1699.53],
            ['name' => 'R-2 / 6.600 VA', 'tariff_code' => 'R-2', 'power_va' => '6.600', 'price_per_kwh' => 1699.53],
            ['name' => 'B-2 / 6.600 VA (Bisnis)', 'tariff_code' => 'B-2', 'power_va' => '6.600', 'price_per_kwh' => 1444.70],
        ];

        foreach ($tariffs as $tariff) {
            \App\Models\Tariff::create($tariff);
        }
    }
}
