<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('types')->insert([
            ['name' => 'Stock Item Wise Vendor'],
            ['name' => 'Fleet Wise Diesel Parts Oil Tyre'],
            ['name' => 'Material Out Register'],
            ['name' => 'Godown Wise Item Summary'],
            ['name' => 'Fleet Details'],
            ['name' => 'Fleet Wise Trip - Diesel - KMS - Hours'],
            ['name' => 'TOP Consumable Report'],
        ]);
    }
}
