<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Items;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function create()
    {
        return view('item_store');
    }
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'item_name' => 'required|string|max:255',
        ]);

        // Create a new item
        Items::create([
            'item_name' => $request->item_name,
        ]);

        // Return a JSON response
        return response()->json(['success' => true, 'message' => 'Item created successfully.']);
    }
    public function dataDisplay(Request $request)
    {
        $items = Items::all(); // Fetch all itemss or use pagination
        return response()->json($items);
    }
    public function destroy($id)
    {
        $item = Items::find($id); // Fetch item by its primary key 'id'
        if ($item) {
            $item->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }
    }
    public function edit($id)
    {
        $items = Items::find($id);
        if ($items) {
            return response()->json($items);
        } else {
            return response()->json(['error' => 'items not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
        ]);

        $item = Items::find($id);
        if ($item) {
            $item->update([
                'item_name' => $request->item_name,
            ]);
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }
    }
}
