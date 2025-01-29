<?php

namespace App\Http\Controllers;

use App\Models\FleetJson;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Models\FleetFile;
use App\Models\FleetData;
use App\Exports\FleetReportExport; // Custom export class for Excel
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class FleetReportController extends Controller
{
    public function index()
    {
        // Fetch all files to populate the dropdown
        $files = FleetFile::all();
        return view('fleet.report', compact('files'));
    }

    public function generateReport(Request $request)
    {

        // Validate the selected file
        /*$request->validate([
            'file_id' => 'required|exists:files,id',
        ]);*/

        // Fetch file data
        $fileId = $request->input('file_id');
        $file = FleetFile::find($fileId);



        // Generate the Excel report
        return Excel::download(new FleetReportExport($fileId), 'fleet_report_' . $file->file_name . '.xlsx');
    }

    // public function displayAllFleetData(Request $request)
    // {

    //     $allData = '';
    //     $listdata ='';
    //     if($request->has('listdata'))
    //     {
    //         $listdata =$request->listdata;
    //         $allData = FleetData::where('file_id',$request->listdata)->get();
    //     }
    //     $filesList = FleetFile::get();
    //     $header_arr = array('High Speed Diesel','Lubricants & Oils','Other Items','Spare Parts','Tools & Kits','Tyre-Tube-Flaps','Welding');
    //     return view('all_fleetdata',['alldata'=>$allData,'header_arr'=>$header_arr,'filesList'=>$filesList,'listdata'=>$listdata]);
    // }
    public function displayAllFleetData(Request $request)
    {
        $user = auth()->user();
        $selectedType = $request->get('type');
        $selectedCompany = $request->get('company');

        if ($user->id == 1) {
            $companies = Company::all();
        } else {
            $companies = $user->companies;
        }

        // Only return the view with initial data
        return view('all_fleetdata', [
            'fileTypes' => Type::get(),
            'companies' => $companies,
            'selectedType' => $selectedType,
            'selectedCompany' => $selectedCompany,
        ]);
    }

    public function getFleetData(Request $request)
    {
        $user = auth()->user();
        $selectedType = $request->get('type');
        $selectedCompany = $request->get('company');
        $start = (int) $request->get('start', 0);
        $length = (int) $request->get('length', 50);

        if ($user->id == 1) {
            $filesList = FleetFile::where('type', $selectedType)
                ->when($selectedCompany, function ($query) use ($selectedCompany) {
                    $query->where('company_id', $selectedCompany);
                })
                ->latest()
                ->first();
        } else {
            $companyIds = $user->companies()->pluck('companies.id');
            $filesList = FleetFile::where('type', $selectedType)
                ->whereIn('company_id', $companyIds)
                ->when($selectedCompany, function ($query) use ($selectedCompany) {
                    $query->where('company_id', $selectedCompany);
                })
                ->latest()
                ->first();
        }

        if (!$filesList) {
            return response()->json([
                'data' => [],
                'headers' => [],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
            ]);
        }

        $filePath = storage_path('app/public/uploads/' . $filesList->file_name);
        // dd($filePath);
        if (!file_exists($filePath)) {
            return response()->json([
                'data' => [],
                'headers' => [],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
            ]);
        }

        $fileContent = file_get_contents($filePath);
        $jsonData = json_decode($fileContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'data' => [],
                'headers' => [],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'error' => 'Invalid JSON structure',
            ]);
        }

        $arrays = array_values($jsonData);

        if (count($arrays) < 2) {
            return response()->json([
                'data' => [],
                'headers' => [],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'error' => 'Insufficient data in the JSON file',
            ]);
        }

        $data = $arrays[1] ?? [];
        $totalRecords = count($data);
        $fileKey = array_keys($jsonData)[1]; // Get keys from the first row as headers
        // Extract headers dynamically based on conditions
        $headers = [];
        $formattedData = [];
        // dd($data);
        if ($fileKey === 'Stock Item Wise Vendor List') {
            // Define headers
            $headers = [
                '#',
                'Name of Item',
                'Part No',
                'Stock Group',
                'Stock Category',
                'Vendor Name',
                'Supplied Quantity',
                'Last Supplied Price',
                'Average Price',
            ];

            $formattedData = [];
            $rowNumber = 1;  // Initialize row counter

            foreach ($data as $item) {
                // If there are vendors in the list
                if (!empty($item['Vendor List']) && is_array($item['Vendor List'])) {
                    foreach ($item['Vendor List'] as $vendor) {
                        $formattedData[] = [
                            '#' => $rowNumber,
                            'Name of Item' => $item['Name of Item'] ?? '-',
                            'Part No' => $item['Part No.'] ?? '-',
                            'Stock Group' => $item['Stock Group'] ?? '-',
                            'Stock Category' => $item['Stock Category'] ?? '-',
                            'Vendor Name' => $vendor['Vendor Name'] ?? '-',
                            'Supplied Quantity' => $vendor['Supplied Quantity'] ?? '-',
                            'Last Supplied Price' => $vendor['Last Supplied Price'] ?? '-',
                            'Average Price' => $vendor['Average Price'] ?? '-',
                        ];
                        $rowNumber++;  // Increment row number for each vendor entry
                    }
                } else {
                    // If no vendors, create a row with empty vendor details
                    $formattedData[] = [
                        '#' => $rowNumber,
                        'Name of Item' => $item['Name of Item'] ?? '-',
                        'Part No' => $item['Part No.'] ?? '-',
                        'Stock Group' => $item['Stock Group'] ?? '-',
                        'Stock Category' => $item['Stock Category'] ?? '-',
                        'Vendor Name' => '-',
                        'Supplied Quantity' => '-',
                        'Last Supplied Price' => '-',
                        'Average Price' => '-',
                    ];
                    $rowNumber++;
                }
            }

        } elseif ($fileKey === 'Fleet Wise Diesel Parts Oil Tyre Details') {
            // Initialize arrays
            $dynamicHeaders = [];
            $formattedData = [];

            // First pass: Extract all possible category names from all entries
            $allCategories = [];
            foreach ($data as $entry) {
                if (!empty($entry['Category']) && is_array($entry['Category'])) {
                    foreach ($entry['Category'] as $category) {
                        if (isset($category['Category Name'])) {
                            $allCategories[$category['Category Name']] = true;
                        }
                    }
                }
            }
            // Convert to array and maintain unique categories
            $dynamicHeaders = array_keys($allCategories);

            // Define prefix and suffix headers in desired order
            $prefixHeaders = ['#', 'Location', 'Door No'];
            $suffixHeaders = [
                'Total Cost',
                'Monthly KMS',
                'Monthly Hour',
                'Cost per KMS',
                'Cost per Hour',
            ];

            // Combine headers in desired order
            $headers = array_merge($prefixHeaders, $dynamicHeaders, $suffixHeaders);

            // Format data rows
            foreach ($data as $key => $entry) {
                // Initialize all category amounts with defaults
                $categoryData = array_fill_keys($dynamicHeaders, '-');

                // Fill in actual category amounts where they exist
                if (!empty($entry['Category']) && is_array($entry['Category'])) {
                    foreach ($entry['Category'] as $category) {
                        if (isset($category['Category Name'])) {
                            $categoryName = $category['Category Name'];
                            $categoryData[$categoryName] = $category['Category Amount'] ?? '-';
                        }
                    }
                }

                // Prepare prefix and suffix data
                $prefixData = [
                    '#' => $key + 1,
                    'Location' => $entry['Location'] ?? '-',
                    'Door No' => $entry['Door No.'] ?? '-'
                ];

                $suffixData = [
                    'Total Cost' => $entry['Total Cost'] ?? '-',
                    'Monthly KMS' => $entry['Monthly KMS'] ?? '-',
                    'Monthly Hour' => $entry['Monthly Hour'] ?? '-',
                    'Cost per KMS' => $entry['Cost per KMS'] ?? '-',
                    'Cost per Hour' => $entry['Cost per Hour'] ?? '-',
                ];

                // Combine data in the same order as headers
                $formattedData[] = array_merge($prefixData, $categoryData, $suffixData);
            }
        } elseif ($fileKey === 'TOP Consumable Report') {
            // Static columns
            $staticHeaders = [
                '#',
                'Name of Item',
                'Part No',
                'Stock Group',
                'Stock Category',
                'Total Consumed Qty',
            ];

            // Extract unique Godown names
            $uniqueGodowns = [];
            foreach ($data as $entry) {
                if (isset($entry['Godown']) && is_array($entry['Godown'])) {
                    foreach ($entry['Godown'] as $godown) {
                        if (!in_array($godown['Godown Name'], $uniqueGodowns)) {
                            $uniqueGodowns[] = $godown['Godown Name'];
                        }
                    }
                }
            }

            // Dynamic godown headers
            $dynamicHeaders = $uniqueGodowns;

            // Combine static and dynamic headers
            $headers = array_merge($staticHeaders, $dynamicHeaders);

            // Format data rows
            foreach ($data as $index => $entry) {
                $rowData = [
                    '#' => $entry['S.No.'],
                    'Name of Item' => $entry['Name of Item'] ?? '-',
                    'Part No' => $entry['Part No.'] ?? '-',
                    'Stock Group' => $entry['Stock Group'] ?? '-',
                    'Stock Category' => $entry['Stock Category'] ?? '-',
                    'Total Consumed Qty' => $entry['Total Consumed Qty'] ?? '-',
                ];

                // Add godown data
                foreach ($uniqueGodowns as $godownName) {
                    $godown = collect($entry['Godown'] ?? [])->firstWhere('Godown Name', $godownName);
                    $rowData[$godownName] = $godown['Qunatity'] ?? '-';
                }

                $formattedData[] = $rowData;
            }
        } elseif ($fileKey === 'Fleet Details') {
            $headers = [
                '#',
                'Door No',
                'Status(Active/Inactive)',
                'Invoice No',
                'Name of Owner',
                'Cost Center(Location)',
                'Section',
                'Date of Delivery',
                'Capacity',
                'Regd Date',
                'Regd State',
                'Regd RTO',
                'Regd No',
                'Engine No',
                'Chasis No',
                'Road Tax From',
                'Road Tax To',
                'Fitness From',
                'Fitness To',
                'Permit for State',
                'Permit From',
                'Permit To',
                'PESO From',
                'PESO To',
                'Calibration From',
                'Calibration To',
                'Remarks',
                'Name of Financer',
                'Agreement Number',
                'Loan Amount',
                'Tenure',
                'EMI Start Date',
                'EMI End Date',
                'EMI Amount',
                'Insured By',
                'Insurance Policy No',
                'Insurance IDV',
                'Insurance From',
                'Insurance To',
                'PUC From',
                'PUC To'
            ];


            foreach ($data as $index => $entry) {
                $formattedData[] = [
                    '#' => $entry['S.No.'],
                    'Door No' => $entry['Door No.'] ?? '',
                    'Status(Active/Inactive)' => $entry['Vehicle Status'][0] ?? '',
                    'Invoice No' => $entry['Invoice No.'] ?? '',
                    'Name of Owner' => $entry['Name of Owner'] ?? '',
                    'Cost Center(Location)' => $entry['Cost Center'] ?? '',
                    'Section' => $entry['Section'] ?? '',
                    'Date of Delivery' => $entry['Date of Delivery'] ?? '',
                    'Capacity' => $entry['Loading Capacity'] ?? '',
                    'Regd Date' => $entry['Regd. Date'] ?? '',
                    'Regd State' => $entry['Regd. State'] ?? '',
                    'Regd RTO' => $entry['Regd. RTO'] ?? '',
                    'Regd No' => $entry['Regd.No.'] ?? '',
                    'Engine No' => $entry['Engine No.'] ?? '',
                    'Chasis No' => $entry['Chasis No.'] ?? '',
                    'Road Tax From' => $entry['Road Tax From'] ?? '',
                    'Road Tax To' => $entry['Road Tax To'] ?? '',
                    'Fitness From' => $entry['Fitness From'] ?? '',
                    'Fitness To' => $entry['Fitness To'] ?? '',
                    'Permit for State' => $entry['Permit for State'] ?? '',
                    'Permit From' => $entry['Permit From'] ?? '',
                    'Permit To' => $entry['Permit To'] ?? '',
                    'PESO From' => $entry['PESO From'] ?? '',
                    'PESO To' => $entry['PESO To'] ?? '',
                    'Calibration From' => $entry['Calibration From'] ?? '',
                    'Calibration To' => $entry['Calibration To'] ?? '',
                    'Remarks' => $entry['Remarks'] ?? '',
                    'Name of Financer' => $entry['Name of Financer'] ?? '',
                    'Agreement Number' => $entry['Agreement Number'] ?? '',
                    'Loan Amount' => $entry['Loan Amount'] ?? '',
                    'Tenure' => $entry['Tenure'] ?? '',
                    'EMI Start Date' => $entry['EMI Start Date'] ?? '',
                    'EMI End Date' => $entry['EMI End Date'] ?? '',
                    'EMI Amount' => $entry['EMI Amount'] ?? '',
                    'Insured By' => $entry['Insured By'] ?? '',
                    'Insurance Policy No' => $entry['Insurance Policy No.'] ?? '',
                    'Insurance IDV' => $entry['Insurance IDV'] ?? '',
                    'Insurance From' => $entry['Insurance From'] ?? '',
                    'Insurance To' => $entry['Insurance To'] ?? '',
                    'PUC From' => $entry['PUC From'] ?? '',
                    'PUC To' => $entry['PUC To'] ?? '',
                ];
            }

        } elseif ($fileKey === 'Godown Wise Item Summary') {
            $staticHeaders = ['#', 'Name of Item', 'Part No', 'Stock Group', 'Stock Category'];
            $uniqueGodowns = [];

            // Collect unique Godown names
            foreach ($data as $row) {
                if (isset($row['Godowns']) && is_array($row['Godowns'])) {
                    foreach ($row['Godowns'] as $godown) {
                        if (isset($godown['Godown Name']) && !in_array($godown['Godown Name'], $uniqueGodowns)) {
                            $uniqueGodowns[] = $godown['Godown Name'];
                        }
                    }
                }
            }

            // Create dynamic Godown headers
            $dynamicHeaders = [];
            foreach ($uniqueGodowns as $godownName) {
                $dynamicHeaders[] = "$godownName: Opening";
                $dynamicHeaders[] = "$godownName: Inward";
                $dynamicHeaders[] = "$godownName: Outward";
                $dynamicHeaders[] = "$godownName: Closing";
            }

            $headers = array_merge($staticHeaders, $dynamicHeaders);

            // Format data rows
            $formattedData = [];
            foreach ($data as $index => $row) {
                $rowData = [
                    '#' => $row['S.No.'] ?? '--',
                    'Name of Item' => $row['Name of Item'] ?? '--',
                    'Part No' => $row['Part No.'] ?? '--',
                    'Stock Group' => $row['Stock Group'] ?? '--',
                    'Stock Category' => $row['Stock Category'] ?? '--',
                ];

                foreach ($uniqueGodowns as $godownName) {
                    $godownData = collect($row['Godowns'])->firstWhere('Godown Name', $godownName);
                    $rowData["$godownName: Opening"] = $godownData['Opening Balance'] ?? '--';
                    $rowData["$godownName: Inward"] = $godownData['Inward Quantity'] ?? '--';
                    $rowData["$godownName: Outward"] = $godownData['Outward Quantity'] ?? '--';
                    $rowData["$godownName: Closing"] = $godownData['Closing Balance'] ?? '--';
                }
                $formattedData[] = $rowData;
            }
        } else {
            $headers = array_keys($data[0] ?? []);
            $formattedData = $data;
        }

        // Paginate data
        $paginatedData = array_slice($formattedData, $start, $length);
        // dd($paginatedData);

        // Return JSON response
        return response()->json([
            'data' => $paginatedData,
            'key' => $fileKey,
            'headers' => $headers,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
        ]);

    }


    public function loadFleetData(Request $request)
    {
        $selectedFileId = $request->get('fileId');
        $page = $request->get('page', 1);  // Default to 1st page if not set
        $perPage = 500;  // Number of records to load per request

        // Find the selected file
        $selectedFile = FleetFile::find($selectedFileId);
        $fileData = [];

        if ($selectedFile) {
            $filePath = storage_path('app/public/uploads/' . $selectedFile->file_name);

            if (file_exists($filePath)) {
                $fileContent = file_get_contents($filePath);
                $allData = json_decode($fileContent, true);
                $allData = $allData['Godown Wise Item Summary'];

                if (json_last_error() === JSON_ERROR_NONE) {
                    // Paginate the data based on the requested page and perPage
                    $fileData = array_slice($allData, ($page - 1) * $perPage, $perPage);
                }
            }
        }

        // Return the paginated data as JSON
        return response()->json($fileData);
    }


    public function getFiles(Request $request)
    {
        $user = auth()->user();
        $type = $request->get('type');
        $company = $request->get('company');
        $query = FleetFile::query();

        if ($type) {
            $query->where('type', $type);
        }
        if ($company) {
            $query->where('company_id', $company);
        }
        if ($user->id != 1) {
            $companyIds = $user->companies()->pluck('companies.id');
            $query->whereIn('company_id', $companyIds);
        }

        $latestFile = $query->latest()->first(['id', 'file_name']); // Get only the latest file

        return response()->json($latestFile); // Return only the latest file
    }



    public function deleteFleetFile($id)
    {
        try {
            $fleetFile = FleetFile::find($id);
            if ($fleetFile) {
                $filePath = storage_path('app/public/uploads/' . $fleetFile->file_name);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                $fleetFile->delete();
            }
            return response()->json(['message' => 'File deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete file!'], 500);
        }
    }
}
