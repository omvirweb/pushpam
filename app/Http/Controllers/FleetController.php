<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        $types = Type::get();
        // dd($types);
        return view('fleet.upload', compact('types'));
    }

    public function upload(Request $request)
    {
        $input = $request->all();



        // Validate the request
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:204800',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator) // Attach the errors to the session
                ->withInput(); // Preserve the input fields (optional)
        }

        // Handle the file upload

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
            // try {
            //     // Store JSON data in the fleet_json table
            //     FleetJson::create([

            //         'type' => $request->type,
            //         'file_name' => $fileName,
            //         'data' => $jsonData, // Store entire JSON content
            //     ]);

            // } catch (\Exception $e) {
            //     return back()->with('error', 'Failed to save data: ' . $e->getMessage());
            // }
        }

        // If upload failed, return an error response
        return response()->json([
            'success' => false,
            'message' => 'File upload failed. Please try again.'
        ]);
    }

}
