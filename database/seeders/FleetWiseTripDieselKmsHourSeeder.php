<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FleetWiseTripDieselKmsHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Truncate existing records if needed
        DB::table('fleet_wise_trip_diesel_kms_hours')->truncate();
        
        $jsonPath = database_path('data/FleetWiseKM-HoursReport20250507113725.json');
        if (!file_exists($jsonPath)) {
            console.log("File not found!");
        }
        $json = file_get_contents($jsonPath);

        $data = json_decode($json, true);

        $tripData = $data['Fleet Wise Trip - Diesel - KMS - Hours'] ?? [];

        foreach ($tripData as $entry) {
            DB::table('fleet_wise_trip_diesel_kms_hours')->insert([
                'location' => $entry['Location']??null,
                'door_no' => $entry['Door No.'],
                'monthly_kms' => $entry["Monthly\nKMS"] ?? 0,
                'monthly_hours' => $entry["Monthly\nHours"] ?? 0,
                'lead_in_kms' => $entry['Lead in KMS'] ?? 0,
                'hsd_per_km' => $entry['HSD per KM'] ?? 0,
                'hsd_per_hour' => $entry['HSD per HOUR'] ?? 0,
                'diesel_per_quantity' => $entry['Diesel per Quantity'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
