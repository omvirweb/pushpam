<?php

namespace App\Http\Controllers;

use App\DataTables\FleetFileDataTable;
use App\Models\FileColumn;
use App\Models\FileRow;
use App\Models\File;
use App\Models\FleetJson;
use App\Models\Type;
use Config;
use Illuminate\Http\Request;
use App\Models\FleetFile;
use App\Models\FleetData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Log;

class FleetController extends Controller
{
    public function index(FleetFileDataTable $dataTable)
    {
        $types = Type::get();
        // dd($types);
        return $dataTable->render('fleet.upload', compact('types'));
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:204800',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ini_set('max_execution_time', 300); // 5 minutes
        ini_set('memory_limit', '512M');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $fileModel = FleetFile::create([
                'file_name' => $fileName,
                'type' => $request->type,
            ]);

            return back()->with('success', 'File uploaded and data saved successfully!');
        }
        return back()->with('error', 'Upload failed!');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');

            // Parse the JSON file
            $jsonData = json_decode(file_get_contents($file), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'Invalid JSON format in the uploaded file.');
            }
            $fileModel = FleetFile::create([
                'file_name' => $fileName,
                'type' => $request->type,
            ]);
            return back()->with('success', 'File uploaded and data saved successfully!');
        }

        // If upload failed, return an error response
        return response()->json([
            'success' => false,
            'message' => 'File upload failed. Please try again.'
        ]);
    }
}
