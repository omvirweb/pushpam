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
            'fileTypes' => Type::orderBy('name', 'ASC')->get(),
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
        $searchColumns = $request->get('columns', []);
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
                            '#' => $item['Sr.No.'] ?? $rowNumber,
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
                        '#' => $item['Sr.No.'] ?? $rowNumber,
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
        } elseif($fileKey === 'Voucher' || $fileKey === 'Daybook') {
            // Define headers
            $headers = [
                '#',
                'Date',
                'Voucher Type',
                'Voucher Number',
                'GUID',
                'MASTERID',
                'ALTERID',
                'VOUCHERKEY',
                'ENTEREDBY',
                'Ref. No.',
                'Ref. Date',
                'CostCentre Name',
                'Narration',
                'Party Name',
                'Address',
                'Place of Supply',
                'GST Reg. Type',
                'GSTIN No.',
                'KMS Reading',
                'Hours Reading',
                'Diesel(Ltr.)',
                'No. of Trips',
                'Trip Factor',
                'Quantity',
                'Inventory Entries',
                'Ledger Entries',
                'Ledger Name',
                'Amount',
                'Name of Item',
                'Accounting Head',
                'Batch Name',
                'Godown Name',
                'HSN',
                'GST Rate',
                'Rate',
            ];

            $formattedData = [];
            $rowNumber = 1;  // Initialize row counter

            foreach ($data as $index => $entry) {
                // If there are vendors in the list
                @$formattedData[] = [
                    '#' => $rowNumber,
                    'Date' => $entry['Date'] ?? '-',
                    'Voucher Type' => $entry['Voucher Type'] ?? '-',
                    'Voucher Number' => $entry['Voucher Number'] ?? '-',
                    'GUID' => $entry['GUID'] ?? '-',
                    'MASTERID' => $entry['MASTERID'] ?? '-',
                    'ALTERID' => $entry['ALTERID'] ?? '-',
                    'VOUCHERKEY' => $entry['VOUCHERKEY'] ?? '-',
                    'ENTEREDBY' => $entry['ENTEREDBY'] ?? '-',
                    'Ref. No.' => $entry['Ref. No.'] ?? '-',
                    'Ref. Date' => $entry['Ref. Date'] ?? '-',
                    'CostCentre Name' => $entry['GUCostCentre NameID'] ?? '-',
                    'Narration' => $entry['Narration'] ?? '-',
                    'Party Name' => $entry['Party Name'] ?? '-',
                    'Address' => $entry['Address'] ?? '-',
                    'Place of Supply' => $entry['Place of Supply'] ?? '-',
                    'GST Reg. Type' => $entry['GST Reg. Type'] ?? '-',
                    'GSTIN No.' => $entry['GSTIN No.'] ?? '-',
                    'KMS Reading' => $entry['KMS Reading'] ?? '-',
                    'Hours Reading' => $entry['Hours Reading'] ?? '-',
                    'Diesel(Ltr.)' => $entry['Diesel(Ltr.)'] ?? '-',
                    'No. of Trips' => $entry['No. of Trips'] ?? '-',
                    'Trip Factor' => $entry['Trip Factor'] ?? '-',
                    'Quantity' => $entry['Quantity'] ?? '-',
                    'Inventory Entries' => $entry['Inventory Entries'] ?? '-',
                    'Ledger Entries' => $entry['Ledger Entries'] ?? '-',
                    'Ledger Name' => $entry['Ledger Name'] ?? '-',
                    'Amount' => $entry['Amount'] ?? '-',
                    'Name of Item' => $entry['Name of Item'] ?? '-',
                    'Accounting Head' => $entry['Accounting Head'] ?? '-',
                    'Batch Name' => $entry['Batch Name'] ?? '-',
                    'Godown Name' => $entry['Godown Name'] ?? '-',
                    'HSN' => $entry['HSN'] ?? '-',
                    'GST Rate' => $entry['GST Rate'] ?? '-',
                    'Rate' => $entry['Rate'] ?? '-',
                ];

                $rowNumber++;
            }

        } elseif ($fileKey === 'Fleet Wise Diesel Parts Oil Tyre Details') {
            $headers = [
                '#',
                'Location',
                'Door No.',
                'Type of Outward',
                'Total Cost',
                'Monthly KMS',
                'Monthly Hour',
                'Cost per KMS',
                'Cost per Hour',
            ];

            $formattedData = [];
            $rowNumber = 1;  // Initialize row counter

            foreach ($data as $item) {
                $formattedData[] = [
                    '#' => $rowNumber,
                    'Date' => $item['Date'] ?? '-',
                    'Location' => $item['Location'] ?? '-',
                    'Door No.' => $item['Door No.'] ?? '-',
                    'Type of Outward' => $item['Type of Outward'] ?? '-',
                    'Total Cost' => $item['Total Cost'] ?? '-',
                    'Monthly KMS' => $item['Monthly KMS'] ?? '-',
                    'Monthly Hour' => $item['Monthly Hour'] ?? '-',
                    'Cost per KMS' => $item['Cost per KMS'] ?? '-',
                    'Cost per Hour' => $item['Cost per Hour'] ?? '-',
                ];

                $rowNumber++;
            }
        } elseif ($fileKey === 'Fleet Wise Diesel Report') {
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
                'Vehicle Status',
                'Invoice No',
                'Name of Owner',
                'Cost Center',
                'Seaction',
                'Date of Delivery',
                'Loading Capacity',
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
                    'Vehicle Status' => $entry['Vehicle Status'][0] ?? '',
                    'Invoice No' => $entry['Invoice No.'] ?? '',
                    'Name of Owner' => $entry['Name of Owner'] ?? '',
                    'Cost Center' => $entry['Cost Center'] ?? '',
                    'Seaction' => $entry['Seaction'] ?? '',
                    'Date of Delivery' => $entry['Date of Delivery'] ?? '',
                    'Loading Capacity' => $entry['Loading Capacity'] ?? '',
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
        } elseif ($fileKey === 'Fleet Wise Trip - Diesel - KMS - Hours') {
            $headers = [
                'Location',
                'Door No',
                'No of Trips',
                'Quantity',
                'Diesel(Ltr)',
                'Monthly KMS',
                'Monthly Hours',
                'Lead in KMS',
                'HSD per KM',
                'HSD per HOUR',
                'Diesel per Quantity'
            ];

            // Transform the data
            foreach ($data as $index => $entry) {
                $formattedData[] = [
                    'Location' => $entry['Location'] ?? '--',
                    'Door No' => $entry['Door No.'] ?? '--',  // Note the period after "No"
                    'No of Trips' => $entry['No. of Trips'] ?? '--',  // Note the period after "No"
                    'Quantity' => $entry['Quantity'] ?? '--',  // Note the period after "No"
                    'Diesel(Ltr)' => $entry['Diesel(Ltr.)'] ?? '--',  // Added period
                    'Monthly KMS' => $entry["Monthly\nKMS"] ?? '--',
                    'Monthly Hours' => $entry["Monthly\nHours"] ?? '--',
                    'Lead in KMS' => $entry['Lead in KMS'] ?? '--',
                    'HSD per KM' => $entry['HSD per KM'] ?? '--',
                    'HSD per HOUR' => $entry['HSD per HOUR'] ?? '--',
                    'Capacity' => $entry['Loading Capacity'] ?? '--',
                    'Diesel per Quantity' => $entry['Diesel per Quantity'] ?? '--',
                ];
            }
        } elseif ($fileKey === 'Fleet Wise Item Consumption') { // pending
            $headers = [
                'S.No.',
                'Date',
                'Vch No.',
                'Door No.',
                'Godown Name',
                'KMS',
                'HMR',
                'Name of Item',
                'Stock Group',
                'Stock Category',
                'Unit',
                'Quantity',
                'Rate',
                'Amount',
            ];

            $formattedData = [];
            $rowNumber = 1;  // Initialize row counter

            foreach ($data as $item) {
                $formattedData[] = [
                    'S.No.' => $rowNumber,
                    'Date' => $item['Date'] ?? '-',
                    'Vch No.' => $item['Vch No.'] ?? '-',
                    'Door No.' => $item['Door No.'] ?? '-',
                    'Godown Name' => $item['Godown Name'] ?? '-',
                    'KMS' => $item['KMS'] ?? '-',
                    'HMR' => $item['HMR'] ?? '-',
                    'Name of Item' => $item['Name of Item'] ?? '-',
                    'Stock Group' => $item['Stock Group'] ?? '-',
                    'Stock Category' => $item['Stock Category'] ?? '-',
                    'Unit' => $item['Unit'] ?? '-',
                    'Quantity' => $item['Quantity'] ?? '-',
                    'Rate' => $item['Rate'] ?? '-',
                    'Amount' => $item['Amount'] ?? '-',
                ];

                $rowNumber++;
            }
        } elseif ($fileKey === 'Material Out Register') {
            $headers = [
                'Date',
                'Party Name',
                'KMS',
                'HMR',
                'KMS Life',
                'HMR Life',
                'Voucher Type',
                'Godown',
                'Ref.No.',
                'Voucher No.',
                'Amount',
            ];

            $formattedData = [];
            $rowNumber = 1;  // Initialize row counter

            foreach ($data as $item) {
                $formattedData[] = [
                    'Date' => $item['Date'] ?? '-',
                    'Party Name' => $item['Party Name'] ?? '-',
                    'KMS' => $item['KMS'] ?? '-',
                    'HMR' => $item['HMR'] ?? '-',
                    'KMS Life' => $item['KMS Life'] ?? '-',
                    'HMR Life' => $item['HMR Life'] ?? '-',
                    'Voucher Type' => $item['Voucher Type'] ?? '-',
                    'Godown' => $item['Godown'] ?? '-',
                    'Ref.No.' => $item['Ref.No.'] ?? '-',
                    'Voucher No.' => $item['Voucher No.'] ?? '-',
                    'Amount' => $item['Amount'] ?? '-',
                ];

                $rowNumber++;
            }
        } else {
            $formattedData = array_map(function ($row) {
                $newRow = [];
                foreach ($row as $key => $value) {
                    $safeKey = str_replace('.', '_', $key);
                    $newRow[$safeKey] = $value;
                }
                return $newRow;
            }, $data);

            $headers = array_map(function ($header) {
                return str_replace('.', '_', $header);
            }, array_keys($data[0] ?? []));
        }

        $filteredData = array_filter($formattedData, function ($row) use ($searchColumns) {
            foreach ($searchColumns as $column) {
                $columnName = str_replace('.', '_', $column['data']); // Convert dots to underscores
                $searchValue = trim($column['search']['value'] ?? '');
                if (!empty($searchValue) && stripos($row[$columnName] ?? '', $searchValue) === false) {
                    return false;
                }
            }
            return true;
        });

        foreach ($filteredData as &$value) { // Use reference to update $filteredData
            foreach ($value as $k => $val) {
                if (is_array($val)) {
                    $arrayValues = '';

                    foreach ($val as $k2 => $val2) {
                        if (is_array($val2)) {
                            foreach ($val2 as $k3 => $val3) {
                                $keyCount = $k2 + 1;
                                $arrayValues .= "<li><strong>$k3 $keyCount:</strong> $val3</li>";
                            }
                        }
                    }

                    if (!empty($arrayValues)) {
                        $value[$k] = '<ul>' . $arrayValues . '</ul>';
                    }
                }
            }
        }

        $recordsFiltered = count($filteredData);

        // Paginate data
        $paginatedData = array_slice(array_values($filteredData), $start, $length);

        // Return JSON response
        return response()->json([
            'data' => $paginatedData,
            'key' => $fileKey,
            'headers' => $headers,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
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
