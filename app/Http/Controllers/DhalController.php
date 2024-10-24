<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Dhal;
use App\Models\Items;
use App\Models\Transactions;
use Illuminate\Http\Request;

class DhalController extends Controller
{
    public function create(Request $request)
    {
        return view('dhal');
    }
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dhal = Items::where('item_name', 'LIKE', "%{$search}%")
            ->get(['id', 'item_name as text']);

        return response()->json($dhal);
    }
    public function itemStore(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
        ]);

        $item = Items::create([
            'item_name' => $request->item_name,
        ]);

        return response()->json([
            'id' => $item->id,
            'text' => $item->item_name,
        ]);
    }


    public function store(Request $request)
    {

        $request->validate([
            'date' => 'required|date',
            'item_name' => 'required',
            'dhal' => 'required|numeric',
            'touch' => 'required|numeric',
            'fine' => 'required|numeric',
            'notes' => 'nullable|string|max:255',
            'type' => 'required|string|in:credit,debit',
        ]);

        $itemId = $this->resolveItemId($request->item_name);

        $data = $request->merge([
            'item' => $itemId,
            // 'account_id' => "",
            'method' => 4,
        ])->all();
        // dd($data);

        // $dhal =  Dhal::create($data);
        Transactions::create($data);

        return response()->json(['success' => true]);
    }
    protected function resolveItemId($identifier)
    {
        if (is_numeric($identifier)) {
            return (int)$identifier; // Existing item ID
        } else {
            // Check if item exists by name
            $item = Items::where('item_name', $identifier)->first();

            if ($item) {
                return $item->id; // Existing item
            } else {
                // Create new item and return its ID
                $newitem = Items::create(['item_name' => $identifier]);
                return $newitem->id;
            }
        }
    }

    public function changestatus(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'status' => 'required'
        ]);

        Transactions::where('id', $request->id)
            ->update([
                'is_delivered' => $request->status
            ]);
        return true;
    }

    public function getDhal(Request $request)
    {
        $transactions = Transactions::where('method', 4)  // Filter for records where method is 4
            ->whereNotNull('dhal')
            ->where('dhal', '!=', '')
            ->with('item')
            ->orderBy('is_delivered', 'asc') // Sort not delivered (0) first, then delivered (1)
            ->orderBy('date', 'asc') // Then sort by date within each group
            ->get();

        return response()->json($transactions);
    }
    public function destroy($id)
    {
        $transaction = Transactions::find($id);
        if ($transaction) {
            $transaction->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Transaction not found']);
        }
    }
    public function edit($id)
    {
        $transaction = Transactions::find($id);

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found.']);
        }

        // Fetch the Item using the item ID from the transaction
        $item = Items::find($transaction->item);

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $transaction->date,
                'dhal' => $transaction->dhal,
                'touch' => $transaction->touch,
                'fine' => $transaction->fine,
                'notes' => $transaction->notes,
                'method' => $transaction->method,
                'type' => $transaction->type,
                'id' => $transaction->id,
                'item' => $transaction->item, // ID of the item
                'item_name' => $item ? $item->item_name : '', // Name of the item if it exists
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'item_name' => 'required',
            'dhal' => 'required|numeric',
            'touch' => 'required|numeric',
            'fine' => 'required|numeric',
            'notes' => 'nullable|string|max:255',
            'type' => 'required|string|in:credit,debit',
        ]);

        $transaction = Transactions::find($id);

        if ($transaction) {
            $transaction->update([
                'date' => $request->date,
                'item' => $this->resolveItemId($request->item_name),
                'dhal' => $request->dhal,
                'touch' => $request->touch,
                'fine' => $request->fine,
                'notes' => $request->notes,
                'type' => $request->type,
                'method' => $request->method,
            ]);

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false], 404);
        }
    }
    // public function getDhal(Request $request)
    // {
    //     $start_date = $request->input('start_date');
    //     $end_date = $request->input('end_date');

    //     $transactions = Transactions::whereBetween('date', [$start_date, $end_date])
    //         ->where('method', 4)  // Filter for records where method is 4
    //         ->whereNotNull('dhal')
    //         ->where('dhal', '!=', '')
    //         ->with('item')
    //         ->get();

    //     return response()->json($transactions);
    // }

    // public function getDhal(Request $request)
    // {
    //     $start_date = $request->input('start_date');
    //     $end_date = $request->input('end_date');
    //     $account_name = $request->input('account_name');

    //     $credits = Transactions::where('type', 'credit')
    //         ->whereBetween('date', [$start_date, $end_date])
    //         ->whereNotNull('dhal')
    //         ->where('dhal', '!=', '')
    //         ->when($account_name, function ($query) use ($account_name) {
    //             return $query->whereHas('account', function ($query) use ($account_name) {
    //                 $query->where('account_name', $account_name);
    //             });
    //         })
    //         ->with('account')
    //         ->get();

    //     $debits = Transactions::where('type', 'debit')
    //         ->whereBetween('date', [$start_date, $end_date])
    //         ->whereNotNull('dhal')
    //         ->where('dhal', '!=', '')
    //         ->when($account_name, function ($query) use ($account_name) {
    //             return $query->whereHas('account', function ($query) use ($account_name) {
    //                 $query->where('account_name', $account_name);
    //             });
    //         })
    //         ->with('account')
    //         ->get();

    //     return response()->json([
    //         'credits' => $credits,
    //         'debits' => $debits
    //     ]);
    // }
}
