<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\FineGold;
use App\Models\Transactions;
use Illuminate\Http\Request;

class FineGoldController extends Controller
{
    public function index()
    {
        $accounts = Account::all();

        return view('fine_gold', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            // 'name' => 'required|string|max:255',
            'account_id' => 'required|string',
            'fine' => 'required|numeric',
            'notes' => 'nullable|string|max:255',
            'type' => 'required|string|in:credit,debit',
        ]);

        // Handle account_id
        $accountId = $this->resolveAccountId($request->account_id);

        // Merge the account_id and opp_account_id into the request data
        $data = $request->merge([
            'account_id' => $accountId,
            'method'  => 3
        ])->all();
        // dd($data);
        // Amount::create($data);
        Transactions::create($data);

        // FineGold::create($request->all());

        return response()->json(['success' => true]);
    }

    public function getData()
    {
        // $credits = Transactions::where('type', 'credit')->get();
        // $debits = Transactions::where('type', 'debit')->get();

        $credits = Transactions::where('type', 'credit')
            ->with('account')
            ->get();
        $debits = Transactions::where('type', 'debit')
            ->with('account')
            ->get();

        return response()->json(['credits' => $credits, 'debits' => $debits]);
    }

    protected function resolveAccountId($identifier)
    {
        if (is_numeric($identifier)) {
            return $identifier; // Existing account ID
        } else {
            // Check if account exists by name
            $account = Account::where('account_name', $identifier)->first();

            if ($account) {
                return $account->account_id; // Existing account
            } else {
                // Create new account and return its ID
                $newAccount = Account::create(['account_name' => $identifier]);
                return $newAccount->account_id;
            }
        }
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
    // public function getFine(Request $request)
    // {
    //     $start_date = $request->input('start_date');
    //     $end_date = $request->input('end_date');
    //     $account_name = $request->input('account_name');

    //     $credits = Transactions::whereBetween('date', [$start_date, $end_date])
    //         ->when($account_name, function ($query) use ($account_name) {
    //             return $query->whereHas('account', function ($query) use ($account_name) {
    //                 $query->where('account_name', $account_name);
    //             });
    //         })
    //         ->with('account')
    //         ->get();

    //     $debits = Transactions::whereBetween('date', [$start_date, $end_date])
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

    public function getFine(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $account_name = $request->input('account_name');
        // $from_fine = $request->input('from_fine');
        // $to_fine = $request->input('to_fine');

        $credits = Transactions::where('type', 'credit')
            ->whereBetween('date', [$start_date, $end_date])
            ->whereNotNull('fine')
            ->where('fine', '!=', '')
            // ->when($from_fine, function ($query) use ($from_fine) {
            //     return $query->where('fine', '>=', $from_fine);
            // })
            // ->when($to_fine, function ($query) use ($to_fine) {
            //     return $query->where('fine', '<=', $to_fine);
            // })
            ->when($account_name, function ($query) use ($account_name) {
                return $query->whereHas('account', function ($query) use ($account_name) {
                    $query->where('account_name', $account_name);
                });
            })
            ->with('account')
            ->get();

        $debits = Transactions::where('type', 'debit')
            ->whereBetween('date', [$start_date, $end_date])
            ->whereNotNull('fine')
            ->where('fine', '!=', '')
            // ->when($from_fine, function ($query) use ($from_fine) {
            //     return $query->where('fine', '>=', $from_fine);
            // })
            // ->when($to_fine, function ($query) use ($to_fine) {
            //     return $query->where('fine', '<=', $to_fine);
            // })
            ->when($account_name, function ($query) use ($account_name) {
                return $query->whereHas('account', function ($query) use ($account_name) {
                    $query->where('account_name', $account_name);
                });
            })
            ->with('account')
            ->get();

        return response()->json([
            'credits' => $credits,
            'debits' => $debits
        ]);
    }
    public function edit($id)
    {
        $transaction = Transactions::with(['account', 'oppAccount'])->find($id);

        if ($transaction) {
            return response()->json([
                'id' => $transaction->id,
                'date' => $transaction->date,
                'account_id' => $transaction->account_id,
                'account_name' => $transaction->account ? $transaction->account->account_name : '',
                'fine' => $transaction->fine,
                'notes' => $transaction->notes,
                'type' => $transaction->type,
            ]);
        } else {
            return response()->json(['error' => 'Fine not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|string',
            'fine' => 'required|numeric',
            'notes' => 'nullable|string|max:255',
            'type' => 'required|string|in:credit,debit',
        ]);

        // Find the transaction by ID
        $transaction = Transactions::find($id);

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
        }

        // Handle account_id
        $accountId = $this->resolveAccountId($request->account_id);

        // Update the transaction with the new data
        $transaction->update([
            'date' => $request->date,
            'account_id' => $accountId,
            'fine' => $request->fine,
            'notes' => $request->notes,
            'type' => $request->type,
        ]);

        return response()->json(['success' => true, 'message' => 'Transaction updated successfully']);
    }
}
