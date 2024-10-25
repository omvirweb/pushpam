<?php

namespace App\Http\Controllers;

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

    public function displayAllFleetData(Request $request)
    {
    
        $allData = '';
        $listdata ='';
        if($request->has('listdata'))
        {
            $listdata =$request->listdata;
            $allData = FleetData::where('file_id',$request->listdata)->get();
        }
        $filesList = FleetFile::get();
        $header_arr = array('High Speed Diesel','Lubricants & Oils','Other Items','Spare Parts','Tools & Kits','Tyre-Tube-Flaps','Welding');
        return view('all_fleetdata',['alldata'=>$allData,'header_arr'=>$header_arr,'filesList'=>$filesList,'listdata'=>$listdata]);
    }
}
