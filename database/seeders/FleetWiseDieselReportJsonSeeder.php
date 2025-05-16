<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FleetWiseDieselReportJsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('fleet_data')->truncate();

        $jsonPath = database_path('data/FleetWiseDieselReport20250507113536.json');
        
        if (!file_exists($jsonPath)) {
            console.log("File not found!");
        }

        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);

      

        $companyData = $data['Company Details'][0];

        // Insert company
        $companyId = DB::table('companies')->insertGetId([
            'name' => $companyData['Company Name'],
            'code' => Str::slug($companyData['Company Name']),
            'address' => $companyData['Address'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed fleet_data
        foreach ($data['Fleet Wise Diesel Parts Oil Tyre Details'] as $section) {
            $location = $section['Location'] ?? null;
            $doorNo = $section['Door No.'] ?? null;
            [$totalCost, $totalCostType] = $this->extractAmountAndType($section['Total Cost'] ?? null);
            $monthlyKms = $section['Monthly KMS'] ?? null;
            $monthlyHour = $section['Monthly Hour'] ?? null;
            $costPerKms = $section['Cost per KMS'] ?? null;
            $costPerHour = $section['Cost per Hour'] ?? null;
    
            foreach ($section['Category'] as $cat) {
                [$categoryAmount, $categoryAmountType] = $this->extractAmountAndType($cat['Category Amount'] ?? null);
    
                DB::table('fleet_data')->insert([
                    'file_id' => $companyId,
                    'location' => $location,
                    'door_no' => $doorNo,
                    'total_cost' => $totalCost,
                    'total_cost_type' => $totalCostType,
                    'kms_reading' => null,
                    'hour_reading' => null,
                    'cost_per_kms' => $costPerKms,
                    'cost_per_hour' => $costPerHour,
                    'monthly_hour' => $monthlyHour,
                    'monthly_kms' => $monthlyKms,
                    'category_name' => $cat['Category Name'],
                    'category_amount' => $categoryAmount,
                    'category_amount_type' => $categoryAmountType,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    protected function extractAmountAndType(?string $value): array
{
    if (!$value) return [null, null];

    $value = trim($value);

    // Match full number and optional Cr/Dr (e.g., "2,32,702.10 Cr")
    preg_match('/^([\d,]+(?:\.\d+)?)(?:\s*(Cr|Dr))?$/i', $value, $matches);

    $amount = isset($matches[1]) ? floatval(str_replace(',', '', $matches[1])) : null;
    $type = isset($matches[2]) ? strtoupper($matches[2]) : null;

    return [$amount, $type];
}
}
