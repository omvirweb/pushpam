<?php

namespace App\Http\Controllers;

use App\Models\FleetJson;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Models\FleetFile;
use App\Models\FleetData;
use App\Exports\FleetReportExport; // Custom export class for Excel
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
        $selectedFileId = $request->get('listdata');
        $selectedType = $request->get('type');
        $filesList = FleetFile::where('type', $selectedType)->get();

        // Initialize file data as null initially
        $fileData = null;

        // If a file is selected, read its contents
        if ($selectedFileId) {
            $selectedFile = FleetFile::find($selectedFileId);

            if ($selectedFile) {
                $filePath = storage_path('app/public/uploads/' . $selectedFile->file_name);
                if (file_exists($filePath)) {
                    $fileContent = file_get_contents($filePath);
                    $fileData = json_decode($fileContent, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return back()->with('error', 'Invalid JSON format in the selected file.');
                    }
                }
            }
        }

        // Return the basic view
        return view('all_fleetdata', [
            'filesList' => $filesList,
            'fileData' => $fileData,
            'listdata' => $selectedFileId,
            'fileTypes' => Type::get(),
            'selectedType' => $selectedType,
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


    public function getFilesByType(Request $request)
    {
        $type = $request->get('type');
        if ($type) {
            $files = FleetFile::where('type', $type)->get(['id', 'file_name']);
            return response()->json($files);
        }
        return response()->json([]);
    }

}
