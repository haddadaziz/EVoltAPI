<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stations = [
            ['name' => 'Borne Hôtel de Ville', 'latitude' => 48.8566, 'longitude' => 2.3522, 'connector_type' => 'Type 2', 'power_kw' => 22.0, 'status' => 'available'],
            ['name' => 'Borne Gare Centrale', 'latitude' => 48.8753, 'longitude' => 2.3561, 'connector_type' => 'CCS', 'power_kw' => 50.0, 'status' => 'available'],
            ['name' => 'Borne Centre Commercial', 'latitude' => 48.8924, 'longitude' => 2.2365, 'connector_type' => 'CHAdeMO', 'power_kw' => 100.0, 'status' => 'available'],
            ['name' => 'Borne Aéroport', 'latitude' => 49.0097, 'longitude' => 2.5479, 'connector_type' => 'Type 2', 'power_kw' => 22.0, 'status' => 'out_of_service'],
            ['name' => 'Borne Parc Municipal', 'latitude' => 48.8252, 'longitude' => 2.3831, 'connector_type' => 'CCS', 'power_kw' => 150.0, 'status' => 'available'],
        ];

        foreach ($stations as $station) {
            \App\Models\Station::create($station);
        }
    }
}
