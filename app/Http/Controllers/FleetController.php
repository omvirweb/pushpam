<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FleetFile;
use App\Models\FleetData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FleetController extends Controller
{
    public function index()
    {
        return view('fleet.upload');
    }

    public function upload(Request $request)
    {
        $input = $request->all();
       
        // Validate the request
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:json|max:2048',
        ]);

        if ($validator->fails()) {
            // Redirect back with validation errors
            return redirect()->back()
                ->withErrors($validator) // Attach the errors to the session
                ->withInput(); // Preserve the input fields (optional)
        }

        // Handle the file upload
        if ($request->hasFile('file')) {
              // Correct file input key 'json_file'
              
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');

            // Save the file info to the 'files' table
            $fileModel = FleetFile::create([
                'file_name' => $fileName
            ]);

            // Parse the JSON file
            $jsonData = json_decode(file_get_contents($file), true);

            // Handle dynamic root key
            $rootKey = array_key_first($jsonData);  // Dynamically fetch the root key
            $fleetDetails = $jsonData[$rootKey] ?? [];

            DB::beginTransaction();

            try {
                // Insert data into 'fleet_data' table
                foreach ($fleetDetails as $fleetDetail) {
                    // Extract details, use null if key is missing
                    $location = $fleetDetail['Location'] ?? null;
                    $doorNo = $fleetDetail['Door No.'] ?? null;
                    $totalCost = $fleetDetail['Total Cost'] ?? null;
                    $totalKMSReading = $fleetDetail['KMS Reading'] ?? null;
                    $totalHourReading = $fleetDetail['Hour Reading'] ?? null;
                    $totalCostperKMS = $fleetDetail['Cost per KMS'] ?? null;
                    $totalCostperHour = $fleetDetail['Cost per Hour'] ?? null;

                    // Process categories within each fleet detail
                    if (isset($fleetDetail['Category']) && is_array($fleetDetail['Category'])) {
                        foreach ($fleetDetail['Category'] as $category) {
                            $categoryName = $category['Category Name'] ?? null;
                            $categoryAmount = $category['Category Amount'] ?? null;

                            // Save each category into the 'fleet_data' table
                            FleetData::create([
                                'file_id' => $fileModel->id,
                                'location' => $location,
                                'door_no' => $doorNo,
                                'total_cost' => $totalCost,
                                'kms_reading' => $totalKMSReading,
                                'hour_reading' => $totalHourReading,
                                'cost_per_kms' => $totalCostperKMS,
                                'cost_per_hour' => $totalCostperHour,
                                'category_name' => $categoryName,
                                'category_amount' => $categoryAmount
                            ]);
                        }
                    }
                }

                DB::commit();
                return back()->with('success', 'File uploaded and data saved successfully!');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Failed to process the file: ' . $e->getMessage());
            }
        }
        
        // If upload failed, return an error response
        return response()->json([
            'success' => false,
            'message' => 'File upload failed. Please try again.'
        ]);
    }
}
