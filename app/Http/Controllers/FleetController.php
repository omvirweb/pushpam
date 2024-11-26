<?php

namespace App\Http\Controllers;

use App\Models\FileColumn;
use App\Models\FileRow;
use App\Models\File;
use App\Models\FleetJson;
use Config;
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
        // Validate the request
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:json|max:2048',
            'type' => 'required|string|in:' . implode(',', Config::get('fleet.types')),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $type = $request->input('type');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');

            // Parse the JSON file
            $jsonData = json_decode(file_get_contents($file), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'Invalid JSON format in the uploaded file.');
            }

            try {
                // Store JSON data in the fleet_json table
                FleetJson::create([

                    'type' => $type,
                    'file_name' => $fileName,
                    'data' => $jsonData, // Store entire JSON content
                ]);

                return back()->with('success', 'File uploaded and data saved successfully!');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to save data: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'No file was uploaded.');
    }


}
