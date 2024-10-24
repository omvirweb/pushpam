<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionsController extends Controller
{
    public function create(Request $request)
    {
        return view('transaction');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $transactions = Account::where('account_name', 'LIKE', "%{$search}%")
            ->get(['account_id', 'account_name as text']);

        return response()->json($transactions);
    }
    public function oppAccountFetch(Request $request)
    {
        $search = $request->input('search');

        // Fetch only accounts where opp_account is set to 1
        $transactions = Account::where('opp_account', 1)
            ->where('account_name', 'LIKE', "%{$search}%")
            ->get(['account_id', 'account_name as text']);

        return response()->json($transactions);
    }
    public function accountStore(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
        ]);

        $account = Account::create([
            'account_name' => $request->account_name,
        ]);

        return response()->json([
            'id' => $account->account_id,
            'text' => $account->account_name,
        ]);
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'date' => 'required|date',
    //         'account_id' => 'required|string',
    //         'amount' => 'nullable|numeric|min:0', // No negative values allowed
    //         'opp_account_id' => 'required|string',
    //         'fine' => 'nullable|numeric|min:0', // No negative values allowed
    //         'item' => 'nullable|string|max:255',
    //         'dhal' => 'nullable|numeric|min:0', // No negative values allowed
    //         'touch' => 'nullable|numeric|min:0', // No negative values allowed
    //         'fineCalc' => 'nullable|numeric|min:0', // No negative values allowed
    //         'notes' => 'nullable|string|max:255',
    //         'type' => 'required|string|in:credit,debit',
    //     ]);

    //     // Determine if account_id is an existing account ID or a new account name
    //     if (is_numeric($request->account_id)) {
    //         // If account_id is numeric, use it as an existing account ID
    //         $accountId = $request->account_id;
    //     } else {
    //         // If account_id is not numeric, it may be a new account name
    //         $account = Account::where('account_name', $request->account_id)->first();

    //         if ($account) {
    //             // Use the existing account ID
    //             $accountId = $account->account_id;
    //         } else {
    //             // Create a new account and get its ID
    //             $account = Account::create(['account_name' => $request->account_id]);
    //             $accountId = $account->account_id;
    //         }
    //     }

    //     // Merge the account_id into the request data
    //     $data = $request->merge(['account_id' => $accountId])->all();

    //     // Store the transaction
    //     Transactions::create($data);

    //     return response()->json(['success' => true]);
    // }


    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|string',
            'amount' => 'nullable|numeric|min:0',
            'opp_account_id' => 'required|string',
            'fine' => 'nullable|numeric|min:0',
            'item' => 'nullable|string|max:255',
            'dhal' => 'nullable|numeric|min:0',
            'touch' => 'nullable|numeric|min:0',
            'fineCalc' => 'nullable|numeric|min:0',
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
            'opp_account_id' => $oppAccountId
        ])->all();

        // Store the transaction
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

    public function getData()
    {
        $credits = Transactions::where('type', 'credit')->get();
        $debits = Transactions::where('type', 'debit')->get();

        return response()->json(['credits' => $credits, 'debits' => $debits]);
    }
    public function fetchTransactions()
    {
        try {
            $creditTransactions = Transactions::where('type', 'credit')->with('account')->get();
            $debitTransactions = Transactions::where('type', 'debit')->with('account')->get();

            // Debugging output
            logger()->info('Credit Transactions:', $creditTransactions->toArray());
            logger()->info('Debit Transactions:', $debitTransactions->toArray());

            return response()->json([
                'creditTransactions' => $creditTransactions,
                'debitTransactions' => $debitTransactions,
            ]);
        } catch (\Exception $e) {
            logger()->error('Error fetching transactions:', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function transation(Request $request)
    {

        if ($request->ajax()) {
            // $data = Journal::select('*')->with(['journal_to_account','journal_from_account'])->get();
            // dd($data);
            $data = Transactions::select('journals.*', 'from_accounts.account_name as account_from_name', 'to_accounts.account_name as account_to_name', 'user.name as user_name')
                // ->latest('journals.id')
                ->leftJoin('accounts as from_accounts', 'from_accounts.id', '=', 'journals.journal_from_account')
                ->leftJoin('accounts as to_accounts', 'to_accounts.id', '=', 'journals.journal_to_account')
                ->leftJoin('users as user', 'user.id', '=', 'journals.user_id')
                ->orderBy('journal_from_account')
                ->get();

            return DataTables::of($data)

                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd($row);
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('view');
    }
    public function getOppAccount($id)
    {
        // Assuming you are returning the account name or other relevant information
        $account = Account::find($id);

        if ($account) {
            return response()->json(['account_name' => $account->account_name]);
        } else {
            return response()->json(['account_name' => ''], 404);
        }
    }
}
