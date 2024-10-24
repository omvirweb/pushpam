<?php

namespace App\Http\Controllers;
use App\Models\FleetData;

use Illuminate\Http\Request;

class FleetDataController extends Controller
{
    public function create()
    {
        
        return view('fleetdata_store');
    }

    public function store(Request $request)
    {
        $fleetData = new FleetData();
        $fleetData->location = $request->location;
        $fleetData->door_no = $request->door_no;
        $fleetData->total_cost = $request->total_cost;
        $fleetData->category_name = $request->category_name;
        $fleetData->save();
        return response()->json(['success' => true, 'message' => 'Fleet data created successfully.']);
    }

    public function dataDisplay(Request $request)
    {
        //$accounts = FleetData::select('id','location','door_no','category_name','category_amount')->get(); // Fetch all accounts or use pagination
        //return response()->json($accounts);
 
        $limit = $request->input('length'); // Records per page
        $start = $request->input('start');  // Start point of the data
        $search = $request->input('search.value'); // Search query if any

        $query = FleetData::query();

        if (!empty($search)) {
            $query->where('location', 'like', "%{$search}%")
                ->orWhere('door_no', 'like', "%{$search}%")
                ->orWhere('total_cost', 'like', "%{$search}%")
                ->orWhere('category_name', 'like', "%{$search}%");
        }

        $totalRecords = $query->count();
        $fleetData = $query->skip($start)
                        ->take($limit)
                        ->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $fleetData
        ]);


    }

    public function destroy($id)
    {
        $fleetData = FleetData::find($id);
        if ($fleetData) {
            $fleetData->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Fleet data not found']);
        }
    }

    public function edit($id)
    {
        $fleetData = FleetData::find($id);
        if ($fleetData) {
            return response()->json($fleetData);
        } else {
            return response()->json(['error' => 'Fleet data not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        /*$validatedData = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_mobile' => 'nullable|string|max:12',
            'opp_account' => 'nullable|boolean',
        ]);*/

        // Find the account
        $fleetData = FleetData::find($id);
        if ($fleetData) {
            // Update the account
            $fleetData->location = $request->location;
            $fleetData->door_no = $request->door_no;
            $fleetData->total_cost = $request->total_cost;
            $fleetData->category_name = $request->category_name;
            $fleetData->save();

            return response()->json(['success' => true, 'message' => 'Fleet data updated successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Fleet data not found']);
        }
    }
}
