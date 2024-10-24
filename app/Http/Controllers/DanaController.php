<?php

namespace App\Http\Controllers;

use App\Models\Dana;
use Illuminate\Http\Request;

class DanaController extends Controller
{
    public function index()
    {
        return view('dana_leva_deva');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0',
            'rate' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
            'type' => 'required|string|in:credit,debit',
        ]);

        Dana::create($request->all());

        return response()->json(['success' => true]);
    }

    public function getData()
    {
        $credits = Dana::where('type', 'credit')->get();
        $debits = Dana::where('type', 'debit')->get();

        return response()->json(['credits' => $credits, 'debits' => $debits]);
    }
}
