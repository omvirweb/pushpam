<?php

namespace App\Exports;

use App\Models\FleetData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FleetReportExport implements FromCollection, WithHeadings
{
    protected $fileId;

    public function __construct($fileId)
    {
        $this->fileId = $fileId;
    }

    // Define the headings for the report
    public function headings(): array
    {
        return [
            'CMP Code', 'REP Code', 'Location', 'Door No.', 'High Speed Diesel', 'Lubricants & Oils', 
            'Other Items', 'Spare Parts', 'Tools & Kits', 'Tyre-Tube-Flaps', 'Welding', 
            'Total Cost', 'KMS Reading', 'Hour Reading', 'Cost per KMS', 'Cost per Hour'
        ];
    }

    // Generate the data for the Excel file
    public function collection()
    {
        $fleetData = FleetData::where('file_id', $this->fileId)->get();

        $reportData = $fleetData->map(function ($data) {
            return [
                'CMP Code' => 'CMP123', // Replace with actual data if needed
                'REP Code' => 'REP456', // Replace with actual data if needed
                'Location' => $data->location,
                'Door No.' => $data->door_no,
                'High Speed Diesel' => $data->category_name === 'High Speed Diesel' ? $data->category_amount : '',
                'Lubricants & Oils' => $data->category_name === 'Lubricants & Oils' ? $data->category_amount : '',
                'Other Items' => $data->category_name === 'Other Items' ? $data->category_amount : '',
                'Spare Parts' => $data->category_name === 'Spare Parts' ? $data->category_amount : '',
                'Tools & Kits' => $data->category_name === 'Tools & Kits' ? $data->category_amount : '',
                'Tyre-Tube-Flaps' => $data->category_name === 'Tyre-Tube-Flaps' ? $data->category_amount : '',
                'Welding' => $data->category_name === 'Welding' ? $data->category_amount : '',
                'Total Cost' => $data->total_cost,
                'KMS Reading' => $data->kms_reading,
                'Hour Reading' => $data->hour_reading,
                'Cost per KMS' => $data->cost_per_kms,
                'Cost per Hour' => $data->cost_per_hour,
            ];
        });

        return collect($reportData);
    }
}
