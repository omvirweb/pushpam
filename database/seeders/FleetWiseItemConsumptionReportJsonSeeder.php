<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FleetWiseItemConsumptionReportJsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
         // Truncate existing records if needed
         DB::table('fleet_item_consumptions')->truncate();

         $jsonPath = database_path('data/FleetWiseItemConsumption20250507114053.json');

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

        foreach ($data['FleetWiseItemConsumption'] as $item) {
            [$quantityVal, $unitType] = $this->parseQuantityWithUnit($item['Quantity'] ?? null);

            DB::table('fleet_item_consumptions')->insert([
                'company_id' => $companyId,
                'sr_no'=>$item['S.No.']??null,
                'date' => isset($item['Date']) ? Carbon::createFromFormat('j-M-y', $item['Date'])->format('Y-m-d') : null,
                'vch_no' => $item['Vch No.'] ?? null,
                'door_no' => $item['Door No.'] ?? null,
                'godown_name' => $item['Godown Name'] ?? null,
                'kms' => $item['KMS'] ?? null,
                'hmr' => $item['HMR'] ?? null,
                'item_name' => $item['Name of Item'] ?? '',
                'stock_group' => $item['Stock Group'] ?? null,
                'stock_category' => $item['Stock Category'] ?? null,
                'unit' => $item['Unit'] ?? null,
                'quantity' => $quantityVal,
                'unit_type' => $unitType,
                'rate' => $item['Rate'] ?? null,
                'amount' => $item['Amount'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function parseQuantityWithUnit(?string $quantityRaw): array
    {
        if (!$quantityRaw) return [null, null];

        $parts = explode(' ', trim($quantityRaw));
        $quantity = isset($parts[0]) ? floatval(str_replace(',', '', $parts[0])) : null;
        $unitType = $parts[1] ?? null;

        return [$quantity, $unitType];
    }
}
