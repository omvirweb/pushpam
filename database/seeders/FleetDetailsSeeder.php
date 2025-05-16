<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FleetDetailsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fleet_details')->truncate();
        
        $jsonPath = database_path('data/FleetWiseMasterRecord20250507113518.json');
        $data = json_decode(file_get_contents($jsonPath), true);

        $fleetData = $data['Fleet Details'] ?? [];

        foreach ($fleetData as $entry) {
            if (!is_array($entry) || empty($entry['Door No.'])) {
                continue;
            }

            DB::table('fleet_details')->insert([
                's_no' => $entry['S.No.'] ?? null,
                'door_no' => $entry['Door No.'],
                'vehicle_status' => $entry['Vehicle Status'][0] ?? true,
                'cost_center' => $entry['Cost Center'] ?? null,
                'regd_state' => $entry['Regd. State'] ?? null,
                'invoice_no' => $entry['Invoice No.'] ?? null,
                'name_of_owner' => $entry['Name of Owner'] ?? null,
                'seaction' => $entry['Seaction'] ?? null,
                'date_of_delivery' => $this->parseDate($entry['Date of Delivery'] ?? null),
                'regd_date' => $this->parseDate($entry['Regd. Date'] ?? null),
                'regd_rto' => $entry['Regd. RTO'] ?? null,
                'regd_no' => $entry['Regd.No.'] ?? null,
                'engine_no' => $entry['Engine No.'] ?? null,
                'chasis_no' => $entry['Chasis No.'] ?? null,
                'road_tax_from' => $this->parseDate($entry['Road Tax From'] ?? null),
                'road_tax_to' => $this->parseDate($entry['Road Tax To'] ?? null),
                'fitness_from' => $this->parseDate($entry['Fitness From'] ?? null),
                'fitness_to' => $this->parseDate($entry['Fitness To'] ?? null),
                'permit_for_state' => $entry['Permit for State'] ?? null,
                'permit_from' => $this->parseDate($entry['Permit From'] ?? null),
                'permit_to' => $this->parseDate($entry['Permit To'] ?? null),
                'insured_by' => $entry['Insured By'] ?? null,
                'insurance_policy_no' => $entry['Insurance Policy No.'] ?? null,
                'insurance_idv' => $entry['Insurance IDV'] ?? null,
                'insurance_from' => $this->parseDate($entry['Insurance From'] ?? null),
                'insurance_to' => $this->parseDate($entry['Insurance To'] ?? null),
                'puc_from' => $this->parseDate($entry['PUC From'] ?? null),
                'puc_to' => $this->parseDate($entry['PUC To'] ?? null),

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function parseDate(?string $date): ?string
    {
        if (!$date) return null;
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
