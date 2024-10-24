<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Account;
use App\Models\Chorsa;
use DataTables;
use DB;

class Report2Controller extends Controller
{
    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Report List';

            return view('Report2.index', $data);
        } catch (\Exception $e) {
            return abort('404');
        }
    }

    public function datatable(Request $request) {
        try {
            if ($request->ajax()) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;

                $account = Account::with('transactions')->whereHas('transactions')
                    // amount
                    ->withSum([
                        'transactions as credit_amount' => function ($q) use ($end_date) {
                            $q->select(DB::raw('SUM(amount)'))->where('method', '2')->where('type', 'credit')->where('date', '<=', $end_date);
                        },
                    ], 'amount')
                    ->withSum([
                        'transactions as debit_amount' => function ($q) use ($end_date) {
                            $q->select(DB::raw('SUM(amount)'))->where('method', '2')->where('type', 'debit')->where('date', '<=', $end_date);
                        },
                    ], 'amount')
                    ->withSum([
                        'transactions as credit_chorsa' => function ($q) use ($end_date) {
                            $q->select(DB::raw('SUM(amount)'))->where('method', '1')->where('type', 'credit')->whereNotNull('rate')->where('date', '<=', $end_date);
                        },
                    ], 'amount')
                    ->withSum([
                        'transactions as debit_chorsa' => function ($q) use ($end_date) {
                            $q->select(DB::raw('SUM(amount)'))->where('method', '1')->where('type', 'debit')->whereNotNull('rate')->where('date', '<=', $end_date);
                        },
                    ], 'amount')
                    // amount
                    // fine (without rate)
                    ->withSum([
                        'transactions as credit_fine' => function ($q) use ($end_date) {
                            $q->select(DB::raw('SUM(weight)'))->where('method', '1')->where('type', 'credit')->whereNull('rate')->where('date', '<=', $end_date);
                        },
                    ], 'weight')
                    ->withSum([
                        'transactions as debit_fine' => function ($q) use ($end_date) {
                            $q->select(DB::raw('SUM(weight)'))->where('method', '1')->where('type', 'debit')->whereNull('rate')->where('date', '<=', $end_date);
                        },
                    ], 'weight')
                    // fine (without rate)
                    // Chorsa Delivered
                    ->withSum([
                        'transactions as credit_chorsa_del' => function ($q) use ($end_date) {
                            $q->select(DB::raw('SUM(weight)'))->where('method', '1')->where('type', 'credit')->whereNotNull('rate')->where('date', '<=', $end_date);
                        },
                    ], 'weight')
                    ->withSum([
                        'transactions as debit_chorsa_del' => function ($q) use ($end_date) {
                            $q->select(DB::raw('SUM(weight)'))->where('method', '1')->where('type', 'debit')->whereNotNull('rate')->where('date', '<=', $end_date);
                        },
                    ], 'amount')
                    // Chorsa Delivered
                    ->withSum([
                        'transactions as delivered_sum' => function ($q) use ($end_date) {
                            $q->select(DB::table('transactions'))
                                ->join('delivered', 'transactions.id', '=', 'delivered.transactions_id')
                                ->select(DB::raw('SUM(delivered.delivered_qty) as delivered_qty'))
                                ->where('transactions.method', '1')
                                ->whereNotNull('transactions.rate')
                                ->where('delivered.delivered_date', '<=', $end_date);
                        },
                    ], 'weight');
                    // Chorsa not Delivered
                    // Chorsa not Delivered


                /*if ($start_date != '' && $end_date != '') {
                    $account = $account->whereHas('transactions', function($q) use ($start_date, $end_date) {
                        $q->whereBetween('date', [$start_date, $end_date]);
                    });
                }*/

                if ($request->name != '') {
                    $account = $account->where('account_id', $request->name);
                }

                $account = $account->get();

                return Datatables::of($account)
                    ->addColumn('amount', function($account) {
                        $co_amount = $account->credit_amount + $account->debit_chorsa - $account->credit_chorsa - $account->debit_amount;
                        return $co_amount;
                    })
                    ->addColumn('fine_amount', function($account) {
                        $co_fine = $account->credit_fine - $account->debit_fine;
                        return $co_fine;
                    })
                    ->addColumn('chorsa_del', function($account) {
                        $co_del_chors = $account->delivered_sum;
                        return $co_del_chors;
                    })
                    ->addColumn('chorsa_not_del', function($account) use ($end_date) {
                        $co_no_del_chors = $account->credit_chorsa_del + $account->debit_chorsa_del - $account->delivered_sum;
                        return $co_no_del_chors;
                    })
                    ->make(true);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return abort('404');
        }
    }
}
