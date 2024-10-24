<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Amount;
use App\Models\Transactions;
use Illuminate\Http\Request;

class AmountController extends Controller
{
    public function index()
    {
        $account = Account::where('default_opp_acc', 1)->first();
        $data = array();
        if ($account) {
            $data['account_name'] = $account->account_name;
            $data['account_id'] = $account->account_id;
        }

        // Return the view with the accounts data
        return view('amount', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            // 'name' => 'required|string|max:255',
            'account_id' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'opp_account_id' => 'required|string',
            'notes' => 'nullable|string|max:255',
            'type' => 'required|string|in:credit,debit',
        ]);

        // Handle account_id
        $accountId = $this->resolveAccountId($request->account_id);

        // Handle opp_account_id
        $oppAccountId = $this->resolveAccountId($request->opp_account_id);

        // Merge the account_id and opp_account_id into the request data
        $data = $request->merge([
            'account_id' => $accountId,
            'opp_account_id' => $oppAccountId,
            'method'  => 2
        ])->all();
        // dd($data);
        // Amount::create($data);
        Transactions::create($data);

        return response()->json(['success' => true]);
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
    public function getAmounts(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $account_name = $request->input('account_name');
        $from_amount = $request->input('from_amount');
        $to_amount = $request->input('to_amount');

        $credits = Transactions::where('type', 'credit')
            ->whereBetween('date', [$start_date, $end_date])
            ->when($account_name, function ($query) use ($account_name) {
                $query->whereHas('account', function ($query) use ($account_name) {
                    $query->where('account_id', $account_name);
                });
            })
            ->when($from_amount, function ($query) use ($from_amount) {
                $query->where('amount', '>=', $from_amount);
            })
            ->when($to_amount, function ($query) use ($to_amount) {
                $query->where('amount', '<=', $to_amount);
            })
            ->whereNotNull('amount') // Check if amount is not null
            ->where('amount', '!=', '') // Check if amount is not empty
            ->with('account')
            ->where('method', '2')
            ->get();

        $debits = Transactions::where('type', 'debit')
            ->whereBetween('date', [$start_date, $end_date])
            ->when($account_name, function ($query) use ($account_name) {
                $query->whereHas('account', function ($query) use ($account_name) {
                    $query->where('account_id', $account_name);
                });
            })
            ->when($from_amount, function ($query) use ($from_amount) {
                $query->where('amount', '>=', $from_amount);
            })
            ->when($to_amount, function ($query) use ($to_amount) {
                $query->where('amount', '<=', $to_amount);
            })
            ->whereNotNull('amount') // Check if amount is not null
            ->where('amount', '!=', '') // Check if amount is not empty
            ->with('account')
            ->where('method', '2')
            ->get();

        return response()->json([
            'credits' => $credits,
            'debits' => $debits
        ]);
    }
    public function getLastTransaction()
    {
        $lastTransaction = Transactions::latest('created_at')->first();

        if ($lastTransaction) {
            $oppAccount = $lastTransaction->oppAccount; // Assuming oppAccount is the relationship name
            return response()->json([
                'opp_account_name' => $oppAccount ? $oppAccount->account_name : null
            ]);
        } else {
            return response()->json([
                'opp_account_name' => null
            ]);
        }
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
                'amount' => $transaction->amount,
                'opp_account_id' => $transaction->opp_account_id,
                'opp_account_name' => $transaction->oppAccount ? $transaction->oppAccount->account_name : '',
                'notes' => $transaction->notes,
                'type' => $transaction->type,
            ]);
        } else {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|integer',
            'amount' => 'required|numeric',
            'opp_account_id' => 'required|integer',
            'notes' => 'nullable|string',
            'type' => 'required|string',
            'method' => 'required|integer',
        ]);

        $transaction = Transactions::find($id);
        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
        }

        $transaction->update($validatedData);

        return response()->json(['success' => true]);
    }


    // public function getData()
    // {
    //     // $credits = Transactions::where('type', 'credit')->get();
    //     // $debits = Transactions::where('type', 'debit')->get();

    //     $credits = Transactions::where('type', 'credit')
    //         ->with('account')
    //         ->get();
    //     $debits = Transactions::where('type', 'debit')
    //         ->with('account')
    //         ->get();

    //     return response()->json(['credits' => $credits, 'debits' => $debits]);
    // }

    // Controller method
    // public function getAmounts(Request $request)
    // {
    //     $start_date = $request->input('start_date');
    //     $end_date = $request->input('end_date');

    //     $credits = Transactions::whereBetween('date', [$start_date, $end_date])->with('account')->get();
    //     $debits = Transactions::whereBetween('date', [$start_date, $end_date])->with('account')->get();

    //     return response()->json([
    //         'credits' => $credits,
    //         'debits' => $debits
    //     ]);
    // }


}
