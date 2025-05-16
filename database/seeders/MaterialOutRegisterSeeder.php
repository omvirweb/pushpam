<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MaterialOutRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('material_out_registers')->truncate();

        $jsonPath = database_path('data/MaterialOutRegister20250507113743.json');

        if (!file_exists($jsonPath)) {
            echo "File not found: $jsonPath\n";
            return;
        }

        $data = json_decode(file_get_contents($jsonPath), true);

        // Insert company
        $companyData = $data['Company Details'][0];
        $companyId = DB::table('companies')->insertGetId([
            'name' => $companyData['Company Name'],
            'code' => Str::slug($companyData['Company Name']),
            'address' => $companyData['Address'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

         // Insert material out register data
         foreach ($data['Material Out Register'] as $entry) {
            [$amount, $transactionType] = $this->parseAmount($entry['Amount']);

            DB::table('material_out_registers')->insert([
                'company_id'       => $companyId,
                'date'             => Carbon::createFromFormat('d-m-Y', $entry['Date']),
                'party_name'       => $entry['Party Name'],
                'voucher_type'     => $entry['Voucher Type'] ?? null,
                'voucher_group'    => $entry['Voucher Group'] ?? null,
                'godown'           => $entry['Godown'] ?? null,
                'ref_no'           => $entry['Ref.No.'] ?? null,
                'voucher_no'       => $entry['Voucher No.'] ?? null,
                'amount'           => $amount ?? null,
                'transaction_type' => $transactionType ?? null,
                'kms'             => $entry['KMS'] ?? null,
                'kms_life'        => $entry['KMS Life'] ?? null,
                'hmr'             => $entry['HMR'] ?? null,
                'hmr_life'        => $entry['HMR Life'] ?? null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }

    private function parseAmount(string $rawAmount): array
    {
        preg_match('/([\d,\.]+)\s*(Dr|Cr)?/i', $rawAmount, $matches);
        $amount = isset($matches[1]) ? floatval(str_replace(',', '', $matches[1])) : 0;
        $type   = isset($matches[2]) ? strtoupper($matches[2]) : null;
        return [$amount, $type];
    }
}
