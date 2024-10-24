<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class AccountController extends Controller
{
    public function create()
    {
        return view('account_store');
    }
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'account_name' => 'required|string|max:255',
            // 'account_phone' => 'nullable|string|max:12',
            'account_mobile' => 'nullable|string|max:12',
            'opp_account' => 'nullable|boolean',
            // 'account_email_ids' => 'nullable|string|max:50',
            // 'account_address' => 'nullable|string',
            // 'account_state' => 'nullable|integer',
            // 'account_city' => 'nullable|integer',
            // 'account_postal_code' => 'nullable|string|max:50',
            // 'account_gst_no' => 'nullable|string|max:255',
            // 'account_pan' => 'nullable|string|max:22',
            // 'account_aadhaar' => 'nullable|string|max:22',
            // 'account_contect_person_name' => 'nullable|string|max:255',
            // 'account_group_id' => 'nullable|integer',
            // 'account_remarks' => 'nullable|string',
            // 'opening_balance' => 'nullable|numeric',
            // 'interest' => 'nullable|numeric',
            // 'credit_debit' => 'nullable|integer',
            // 'opening_balance_in_gold' => 'nullable|numeric',
            // 'gold_ob_credit_debit' => 'nullable|integer',
            // 'opening_balance_in_silver' => 'nullable|numeric',
            // 'silver_ob_credit_debit' => 'nullable|integer',
            // 'opening_balance_in_rupees' => 'nullable|numeric',
            // 'rupees_ob_credit_debit' => 'nullable|integer',
            // 'opening_balance_in_c_amount' => 'nullable|numeric',
            // 'c_amount_ob_credit_debit' => 'nullable|integer',
            // 'opening_balance_in_r_amount' => 'nullable|integer',
            // 'r_amount_ob_credit_debit' => 'nullable|integer',
            // 'bank_name' => 'nullable|string|max:255',
            // 'bank_account_no' => 'nullable|string|max:255',
            // 'ifsc_code' => 'nullable|string|max:255',
            // 'bank_interest' => 'nullable|numeric',
            // 'gold_fine' => 'nullable|numeric',
            // 'silver_fine' => 'nullable|numeric',
            // 'amount' => 'nullable|numeric',
            // 'c_amount' => 'nullable|numeric',
            // 'r_amount' => 'nullable|numeric',
            // 'credit_limit' => 'nullable|numeric',
            // 'balance_date' => 'nullable|date',
            // 'status' => 'nullable|integer',
            // 'user_id' => 'nullable|integer',
            // 'user_name' => 'nullable|string|max:255',
            // 'is_supplier' => 'nullable|integer',
            // 'password' => 'nullable|string|max:255',
            // 'min_price' => 'nullable|numeric',
            // 'chhijjat_per_100_ad' => 'nullable|numeric',
            // 'meena_charges' => 'nullable|numeric',
            // 'price_per_pcs' => 'nullable|numeric',
            // 'is_active' => 'nullable|integer'
        ]);

        // Create a new account record
        $account = new Account();
        $account->account_name = $request->input('account_name');
        // $account->account_phone = $request->input('account_phone');
        $account->account_mobile = $request->input('account_mobile');
        $account->opp_account = $request->input('opp_account', false); // Default to false if not checked
        // $account->account_email_ids = $request->input('account_email_ids');
        // $account->account_address = $request->input('account_address');
        // $account->account_state = $request->input('account_state');
        // $account->account_city = $request->input('account_city');
        // $account->account_postal_code = $request->input('account_postal_code');
        // $account->account_gst_no = $request->input('account_gst_no');
        // $account->account_pan = $request->input('account_pan');
        // $account->account_aadhaar = $request->input('account_aadhaar');
        // $account->account_contect_person_name = $request->input('account_contect_person_name');
        // $account->account_group_id = $request->input('account_group_id');
        // $account->account_remarks = $request->input('account_remarks');
        // $account->opening_balance = $request->input('opening_balance');
        // $account->interest = $request->input('interest');
        // $account->credit_debit = $request->input('credit_debit');
        // $account->opening_balance_in_gold = $request->input('opening_balance_in_gold');
        // $account->gold_ob_credit_debit = $request->input('gold_ob_credit_debit');
        // $account->opening_balance_in_silver = $request->input('opening_balance_in_silver');
        // $account->silver_ob_credit_debit = $request->input('silver_ob_credit_debit');
        // $account->opening_balance_in_rupees = $request->input('opening_balance_in_rupees');
        // $account->rupees_ob_credit_debit = $request->input('rupees_ob_credit_debit');
        // $account->opening_balance_in_c_amount = $request->input('opening_balance_in_c_amount');
        // $account->c_amount_ob_credit_debit = $request->input('c_amount_ob_credit_debit');
        // $account->opening_balance_in_r_amount = $request->input('opening_balance_in_r_amount');
        // $account->r_amount_ob_credit_debit = $request->input('r_amount_ob_credit_debit');
        // $account->bank_name = $request->input('bank_name');
        // $account->bank_account_no = $request->input('bank_account_no');
        // $account->ifsc_code = $request->input('ifsc_code');
        // $account->bank_interest = $request->input('bank_interest');
        // $account->gold_fine = $request->input('gold_fine');
        // $account->silver_fine = $request->input('silver_fine');
        // $account->amount = $request->input('amount');
        // $account->c_amount = $request->input('c_amount');
        // $account->r_amount = $request->input('r_amount');
        // $account->credit_limit = $request->input('credit_limit');
        // $account->balance_date = $request->input('balance_date');
        // $account->status = $request->input('status');
        // $account->user_id = $request->input('user_id');
        // $account->user_name = $request->input('user_name');
        // $account->is_supplier = $request->input('is_supplier');
        // $account->password = bcrypt($request->input('password'));
        // $account->min_price = $request->input('min_price');
        // $account->chhijjat_per_100_ad = $request->input('chhijjat_per_100_ad');
        // $account->meena_charges = $request->input('meena_charges');
        // $account->price_per_pcs = $request->input('price_per_pcs');
        // $account->is_active = $request->input('is_active');

        // Save the account record to the database
        $account->save();

        // Redirect back with success message
        // return redirect()->route('account.create')->with('success', 'Account created successfully.');
        return response()->json(['success' => true, 'message' => 'Account created successfully.']);
    }

    public function dataDisplay(Request $request)
    {
        $accounts = Account::all(); // Fetch all accounts or use pagination
        return response()->json($accounts);
    }
    public function destroy($id)
    {
        $account = Account::find($id);
        if ($account) {
            $account->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Account not found']);
        }
    }
    public function edit($id)
    {
        $account = Account::find($id);
        if ($account) {
            return response()->json($account);
        } else {
            return response()->json(['error' => 'Account not found'], 404);
        }
    }
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_mobile' => 'nullable|string|max:12',
            'opp_account' => 'nullable|boolean',
        ]);

        // Find the account
        $account = Account::find($id);
        if ($account) {
            // Update the account
            $account->account_name = $request->input('account_name');
            $account->account_mobile = $request->input('account_mobile');
            $account->opp_account = $request->input('opp_account', false);
            $account->save(); // Use save() to update the record

            return response()->json(['success' => true, 'message' => 'Account updated successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Account not found']);
        }
    }
}
