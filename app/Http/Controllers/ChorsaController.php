<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Chorsa;
use App\Models\Transactions;
use App\Models\Datewise;
use App\Models\Delivered;

use Illuminate\Http\Request;

class ChorsaController extends Controller
{
    public function index()
    {
        $account = Account::where('default_opp_acc', 1)->first();
        $data = array();
        if ($account) {
            $data['account_name'] = $account->account_name;
            $data['account_id'] = $account->account_id;
        }
        return view('chorsa_leva_deva', compact('data'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'date' => 'required|date',
            // 'name' => 'required|string|max:255',
            'account_id' => 'required|string',
            'weight' => 'required|numeric|min:0',
            // 'rate' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'type' => 'required|string|in:credit,debit',

        ]);

        // Handle account_id
        $accountId = $this->resolveAccountId($request->account_id);
        $oppAccountId = $this->resolveAccountId($request->opp_account_id);

        $data = $request->merge([
            'account_id' => $accountId,
            'opp_account_id' => $oppAccountId,
        ])->all();
        $data['method'] = '1';
        // Create a new chorsa record
        Transactions::create($data);

        // Return success response
        return response()->json(['success' => true, 'message' => 'Chorsa created successfully.']);
    }

    protected function resolveAccountId($identifier)
    {
        if (is_numeric($identifier)) {
            return (int) $identifier; // Existing account ID
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

    public function changestatus(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'status' => 'required'
        ]);

        $status = NULL;
        if ($request->status) {
            $status = 1;
        }

        Transactions::where('id', $request->id)
            ->update([
                'is_delivered' => $status
            ]);
        return true;
    }

    // public function getData(Request $request)
    // {
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $accountName = $request->input('account_name');
    //     $search = $request->input('search');
    //     // $fromAmount = $request->input('from_amount');
    //     // $toAmount = $request->input('to_amount');
    //     $columns = ['date', 'weight', 'rate', 'total', 'notes', 'type', 'created_at'];
    //     $query = Transactions::query()->with('account');
    //     $query1 = Transactions::query()->with('account');

    //     $query->where('method', '1');
    //     $query1->where('method', '1');

    //     if ($startDate && $endDate) {
    //         $query->whereBetween('date', [$startDate, $endDate]);
    //         $query1->whereBetween('date', [$startDate, $endDate]);
    //     }

    //     if ($accountName) {
    //         $query->where('account_id', $accountName);
    //         $query1->where('account_id', $accountName);
    //     }

    //     if ($search) {
    //         $query->where(function ($subQuery) use ($columns, $search) {
    //             foreach ($columns as $column) {
    //                 $subQuery = $subQuery->orWhere($column, 'LIKE', "%{$search}%");
    //             }
    //             return $subQuery;
    //         });
    //         $query1->where(function ($subQuery) use ($columns, $search) {
    //             foreach ($columns as $column) {
    //                 $subQuery = $subQuery->orWhere($column, 'LIKE', "%{$search}%");
    //             }
    //             return $subQuery;
    //         });
    //     }

    //     // if ($fromAmount) {
    //     //     $query->where('total', '>=', $fromAmount);
    //     // }

    //     // if ($toAmount) {
    //     //     $query->where('total', '<=', $toAmount);
    //     // }

    //     $data = [
    //         'levana' => $query->where('type', 'credit')->get(),
    //         'devana' => $query1->where('type', 'debit')->get(),
    //     ];

    //     // $data = [
    //     //     'levana' => Transactions::where('type', 'levana')->get(),
    //     //     'devana' => Transactions::where('type', 'devana')->get(),
    //     // ];

    //     // $data = [
    //     //     'levana' => Transactions::with('account')->where('type', 'levana')->get(),
    //     //     'devana' => Transactions::with('account')->where('type', 'devana')->get(),
    //     // ];

    //     return response()->json($data);
    // }



    public function getData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $accountName = $request->input('account_name');
        $search = $request->input('search');
        $notConsiderDateRange = $request->input('notConsiderDateRange');
        $delivered = $request->input('delivered');

        // Adjust the columns to match your database table structure
        $columns = ['date', 'weight', 'rate', 'amount', 'notes', 'type', 'created_at'];
        $query = Transactions::query()->with('account')->withSum('delivered', 'delivered_qty');

        $query->where('method', '1');

        if (($startDate && $endDate) && ($notConsiderDateRange == 'false')) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        if ($delivered != 3) {
            if ($delivered == 2) {
                $query->where('is_delivered', '1');
            }
            if ($delivered == 1) {
                $query->whereNull('is_delivered');
            }
        }

        if ($accountName) {
            $query->where('account_id', $accountName);
        }

        if ($search) {
            $query->where(function ($subQuery) use ($columns, $search) {
                foreach ($columns as $column) {
                    $subQuery->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
        }

        $data = $query->get();

        $fine = 0;
        $fine = Transactions::where('method', '4')->whereNull('is_delivered')->sum('fine');

        // Check if the data collection is empty
        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'No records found matching your criteria.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'fine' => $fine
        ]);
    }


    public function destroy($id)
    {
        $transaction = Transactions::find($id);
        if ($transaction) {
            $transaction->delivered()->delete();
            $transaction->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Transaction not found']);
        }
    }
    public function edit($id)
    {
        $chorsa = Transactions::with('account')->findOrFail($id);
        return response()->json([
            'id' => $chorsa->id,
            'date' => $chorsa->date,
            'account_id' => $chorsa->account_id,
            'account_name' => $chorsa->account->account_name, // Make sure this is returned
            'weight' => $chorsa->weight,
            'rate' => $chorsa->rate,
            'amount' => $chorsa->amount,
            'notes' => $chorsa->notes,
            'type' => $chorsa->type
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|string',
            'weight' => 'required|numeric|min:0',
            'rate' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'type' => 'required|string|in:credit,debit',
        ]);

        // Find the existing record
        $chorsa = Transactions::findOrFail($id);

        // Handle account_id
        $accountId = $this->resolveAccountId($request->account_id);

        // Update the record
        $chorsa->update([
            'date' => $request->date,
            'account_id' => $accountId,
            'weight' => $request->weight,
            'rate' => $request->rate,
            'amount' => $request->amount,
            'notes' => $request->notes,
            'type' => $request->type,
        ]);

        // Return success response
        return response()->json(['success' => true, 'message' => 'Chorsa updated successfully.']);
    }

    public function datewise(Request $request)
    {
        if ($request->ajax()) {
            // return $request->all();
            Datewise::updateOrCreate(
                ['for_date' => $request->endDate],
                ['close_rate' => $request->newRate]
            );
        }
    }

    public function qytUpdate(Request $request)
    {
        if ($request->ajax()) {
            $delivered = new Delivered;
            $delivered->transactions_id = $request->id;
            $delivered->delivered_qty = $request->qty;
            $delivered->delivered_date = $request->date;
            $delivered->save();
        }
    }

    public function isChecked(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'value' => 'required'
        ]);

        Transactions::where('id', $request->id)
            ->update([
                'is_checked' => $request->value
            ]);
        return true;
    }

    public function getDeliveredRecords($chorsaId)
    {
        // Find the delivered records where 'transactions_id' matches the given 'chorsaId'
        $deliveredRecords = Delivered::where('transactions_id', $chorsaId)->get();

        if ($deliveredRecords->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No delivered records found for this Chorsa ID.']);
        }

        // Return the delivered records in the response
        return response()->json(['success' => true, 'deliveredRecords' => $deliveredRecords]);
    }
    public function deleteDeliveredRecord($deliveredId)
    {
        // Find the delivered record by its ID
        $deliveredRecord = Delivered::find($deliveredId);

        if (!$deliveredRecord) {
            return response()->json(['success' => false, 'message' => 'Delivered record not found.']);
        }

        // Delete the record
        $deliveredRecord->delete();

        // Return a success response after deletion
        return response()->json(['success' => true, 'message' => 'Delivered record deleted successfully.']);
    }
}
