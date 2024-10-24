<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Chorsa;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use DataTables;
use Carbon\Carbon;

class ReportController extends Controller
{   
    const OPEN_AMOUNT = 42;
    const CLOSE_AMOUNT = 43;

    public function index()
    {
        try {
            $data = [];
            $data['page_title'] = 'Report List';

            $totalCaseCredit    = Transactions::whereNotNull('opp_account_id')->where('type', 'credit')->where('method', '2')->sum('amount');
            $totalCaseDebit     = Transactions::whereNotNull('opp_account_id')->where('type', 'debit')->where('method', '2')->sum('amount');

            $totalChorsaCredit  = Transactions::whereNotNull('opp_account_id')->where('type', 'credit')->where('method', '1')->sum('amount');
            $totalChorsaDebit   = Transactions::whereNotNull('opp_account_id')->where('type', 'debit')->where('method', '1')->sum('amount');

            $data['totalCase'] = $totalCaseCredit - $totalCaseDebit - $totalChorsaCredit + $totalChorsaDebit;

            return view('Report.index', $data);
        } catch (\Exception $e) {
            dd($e);
            return abort('404');
        }
    }

    public function datatable(Request $request)
    {
        // dd('test');
        if ($request->ajax()) {
            $op_amount = 0.00;
            $op_fine = 0.00;
            $op_dhal_waight = 0.00;
            $op_dhal_fine = 0.00;
            $op_del_chors = 0.00;
            $op_no_del_chors = 0.00;

            $co_amount = 0.00;
            $co_fine = 0.00;
            $co_dhal_waight = 0.00;
            $co_dhal_fine = 0.00;
            $co_del_chors = 0.00;
            $co_no_del_chors = 0.00;

            $report = Transactions::select('*')->with(['account', 'oppositeAccount']);
            if ($request->start_date != '' && $request->end_date != '') {
                $op_chorsa_credit = Transactions::where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'credit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('amount');
                $op_chorsa_debit = Transactions::where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'debit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('amount');
                
                $op_amount_credit = Transactions::where('method', '2')
                    ->where('type', 'credit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('amount');
                $op_amount_debit = Transactions::where('method', '2')
                    ->where('type', 'debit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('amount');

                $op_amount_only = $op_amount_credit - $op_amount_debit;
                $op_chor_amount = $op_chorsa_debit - $op_chorsa_credit;
                $op_amount = $op_chorsa_debit + $op_amount_credit - $op_chorsa_credit - $op_amount_debit;

                $op_fine_credit = Transactions::where('method', '1')
                    ->whereNull('rate')
                    ->where('type', 'credit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('weight');
                $op_fine_debit = Transactions::where('method', '1')
                    ->whereNull('rate')
                    ->where('type', 'debit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('weight');

                $op_fine = $op_fine_credit - $op_fine_debit;

                $op_dhal_waight = Transactions::where('method', '4')
                    // ->whereNull('is_delivered')
                    ->where('type', 'credit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('dhal');

                $op_chorsa_fine_credit = Transactions::where('method', '1')
                    ->whereNull('rate')
                    ->where('type', 'credit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('fine');
                $op_chorsa_fine_debit = Transactions::where('method', '1')
                    ->whereNull('rate')
                    ->where('type', 'debit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('fine');
                $op_dhal_fine_credit = Transactions::where('method', '4')
                    // ->whereNull('is_delivered')
                    ->where('type', 'credit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('fine');

                $op_dhal_fine = $op_chorsa_fine_credit - $op_chorsa_fine_debit + $op_dhal_fine_credit;

                $op_no_del_chors_credit = Transactions::where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'credit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('weight');

                $op_no_del_chors_credit_delivered = Transactions::withSum(['delivered' => function ($query) use ($request) {
                        return $query->where('delivered_date', '<', $request->start_date); 
                    }],'delivered_qty')
                    ->where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'credit')
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->get();

                $op_no_del_chors_credit_delivered_sum = 0;
            
                foreach ($op_no_del_chors_credit_delivered as $key => $value) {
                    $op_no_del_chors_credit_delivered_sum += $value->delivered_sum_delivered_qty;
                }

                $op_no_del_chors_debit = Transactions::where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'debit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('weight');

                $op_no_del_chors_debit_delivered = Transactions::withSum(['delivered' => function ($query) use ($request) {
                        return $query->where('delivered_date', '<', $request->start_date); 
                    }],'delivered_qty')
                    ->where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'debit')
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->get();

                $op_no_del_chors_debit_delivered_sum = 0;
            
                foreach ($op_no_del_chors_debit_delivered as $key => $value) {
                    $op_no_del_chors_debit_delivered_sum += $value->delivered_sum_delivered_qty;
                }

                $op_no_del_chors = ($op_no_del_chors_credit - $op_no_del_chors_credit_delivered_sum) - ($op_no_del_chors_debit - $op_no_del_chors_debit_delivered_sum);

                $op_del_chors = $op_no_del_chors_credit_delivered_sum - $op_no_del_chors_debit_delivered_sum;
                /*$op_del_chors_credit = Transactions::where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'credit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('weight');
                $op_del_chors_debit = Transactions::where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'debit')
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->where('date', '<', $request->start_date)
                    ->sum('weight');

                $op_del_chors_credit_delivered = Transactions::withSum(['delivered' => function ($query) use ($request) {
                        return $query->where('delivered_date', '<', $request->start_date); 
                    }],'delivered_qty')
                    ->where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'credit')
                    ->where('date', '<', $request->start_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->get();

                $op_del_chors_credit_delivered_sum = 0;
            
                foreach ($op_del_chors_credit_delivered as $key => $value) {
                    $op_del_chors_credit_delivered_sum += $value->delivered_sum_delivered_qty;
                }

                $op_del_chors_debit_delivered = Transactions::withSum(['delivered' => function ($query) use ($request) {
                        return $query->where('delivered_date', '<', $request->start_date); 
                    }],'delivered_qty')
                    ->where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'debit')
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->where('date', '<', $request->start_date)
                    ->get();

                $op_del_chors_debit_delivered_sum = 0;
                foreach ($op_del_chors_debit_delivered as $key => $value) {
                    $op_del_chors_debit_delivered_sum += $value->delivered_sum_delivered_qty;
                }

                $op_del_chors = ($op_del_chors_credit - $op_del_chors_credit_delivered_sum) - ($op_del_chors_debit - $op_del_chors_debit_delivered_sum);*/


                $report->whereBetween('date', [$request->start_date, $request->end_date]);
            }
            if ($request->start_date != '' && $request->end_date != '') {
                $co_chorsa_credit = Transactions::where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'credit')
                    // ->whereBetween('date', [$request->start_date, $request->end_date])
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('amount');
                $co_chorsa_debit = Transactions::where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'debit')
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('amount');
                
                $co_amount_credit = Transactions::where('method', '2')
                    ->where('type', 'credit')
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('amount');
                $co_amount_debit = Transactions::where('method', '2')
                    ->where('type', 'debit')
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('amount');

                $co_amount_only = $co_amount_credit - $co_amount_debit;
                $co_chor_amount = $co_chorsa_debit - $co_chorsa_credit;
                $co_amount = $co_chorsa_debit + $co_amount_credit - $co_chorsa_credit - $co_amount_debit;


                $co_fine_credit = Transactions::where('method', '1')
                    ->whereNull('rate')
                    ->where('type', 'credit')
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('weight');
                $co_fine_debit = Transactions::where('method', '1')
                    ->whereNull('rate')
                    ->where('type', 'debit')
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('weight');

                $co_fine = $co_fine_credit - $co_fine_debit;

                $co_dhal_waight = Transactions::where('method', '4')
                    // ->whereNull('is_delivered')
                    ->where('type', 'credit')
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('dhal');

                $co_chorsa_fine_credit = Transactions::where('method', '1')
                    ->whereNull('rate')
                    ->where('type', 'credit')
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('fine');
                $co_chorsa_fine_debit = Transactions::where('method', '1')
                    ->whereNull('rate')
                    ->where('type', 'debit')
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('fine');
                $co_dhal_fine_credit = Transactions::where('method', '4')
                    // ->whereNull('is_delivered')
                    ->where('type', 'credit')
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('fine');

                $co_dhal_fine = $co_chorsa_fine_credit - $co_chorsa_fine_debit + $co_dhal_fine_credit;

                $co_no_del_chors_credit = Transactions::where('method', '1')
                    ->whereNotNull('rate')
                    // ->whereNotNull('is_delivered')
                    ->where('type', 'credit')
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('weight');

                $co_no_del_chors_credit_delivered = Transactions::withSum(['delivered' => function ($query) use ($request) {
                        return $query->where('delivered_date', '<=', $request->end_date); 
                    }],'delivered_qty')
                    ->where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'credit')
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->get();

                $co_no_del_chors_credit_delivered_sum = 0;
            
                foreach ($co_no_del_chors_credit_delivered as $key => $value) {
                    $co_no_del_chors_credit_delivered_sum += $value->delivered_sum_delivered_qty;
                }

                $co_no_del_chors_debit = Transactions::where('method', '1')
                    ->whereNotNull('rate')
                    // ->whereNotNull('is_delivered')
                    ->where('type', 'debit')
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('weight');

                $co_no_del_chors_debit_delivered = Transactions::withSum(['delivered' => function ($query) use ($request) {
                        return $query->where('delivered_date', '<=', $request->end_date); 
                    }],'delivered_qty')
                    ->where('method', '1')
                    ->whereNotNull('rate')
                    ->where('type', 'debit')
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->get();

                $co_no_del_chors_debit_delivered_sum = 0;
            
                foreach ($co_no_del_chors_debit_delivered as $key => $value) {
                    $co_no_del_chors_debit_delivered_sum += $value->delivered_sum_delivered_qty;
                }

                $co_no_del_chors = ($co_no_del_chors_credit - $co_no_del_chors_credit_delivered_sum) - ($co_no_del_chors_debit - $co_no_del_chors_debit_delivered_sum);

                $co_del_chors = $co_no_del_chors_credit_delivered_sum - $co_no_del_chors_debit_delivered_sum;
                
                /*$co_del_chors_credit = Transactions::where('method', '1')
                    ->whereNotNull('rate')
                    ->whereNull('is_delivered')
                    ->where('type', 'credit')
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('weight');
                $co_del_chors_debit = Transactions::where('method', '1')
                    ->whereNotNull('rate')
                    ->whereNull('is_delivered')
                    ->where('type', 'debit')
                    ->where('date', '<=', $request->end_date)
                    ->when($request->name, function($query) use ($request){
                        return $query->where('account_id', $request->name);
                    })
                    ->sum('weight');

                $co_del_chors = $co_del_chors_credit - $co_del_chors_debit;*/

                $report->whereBetween('date', [$request->start_date, $request->end_date]);
            }
            if ($request->name != '') {
                $report->where('account_id', $request->name);
            }
            if ($request->opp_name != '') {
                $report->where('opp_account_id', $request->opp_name);
            }
            // Delivered filter
            if ($request->delivered == 'not_delivered') {
                // Filter where `is_delivered` is either `0` or `null`
                $report->where(function ($query) {
                    $query->where('is_delivered', '=', 0)
                        ->orWhereNull('is_delivered');
                });
            } elseif ($request->delivered == 'delivered') {
                // Filter where `is_delivered` is `1`
                $report->where('is_delivered', '=', 1);
            }

            $method = [];
            // dd($request->chorsa);
            if ($request->chorsa == 'true') {
                // $report->orWhere('method', '1');
                $method[] = '1';
            }
            if ($request->amount == 'true') {
                // $report->orWhere('method', '2');
                $method[] = '2';
            }
            if ($request->fine == 'true') {
                // $report->orWhere('method', '3');
                $method[] = '3';
            }
            if ($request->dhal == 'true') {
                // $report->orWhere('method', '4');
                $method[] = '4';
            }
            // dd($method);
            if (!empty($method)) {
                # code...
                $report->WhereIn('method', $method);
            }
            $report->get();
            // dd($report->toSql());
            // dd($report->toArray());
            return datatables::of($report)
                ->addColumn('action', function ($report) {
                    $action = '<a href="#" class="btn btn-outline-secondary btn-sm btnDelete" data-url="' . route('report.destroy', $report->id) . '" data-id="' . $report->id . '" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                    // $action .= '<a href="' . route('report.edit', $report->id) . '" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp';
                    return $action;
                })
                ->addColumn('is_deleverd', function ($report) {
                    $action = '-';
                    if ($report->method == 1) {
                        $ischeck = '';
                        $isstatus = '1';
                        if ($report->is_delivered == 1) {
                            $ischeck = 'checked';
                            $isstatus = '0';
                        }
                        $action = '<input type="checkbox" id=""' . $ischeck . ' onclick="changestatus(' . $isstatus . ',' . $report->id . ')" />';
                        # code...
                    }
                    return $action;
                })

                ->addColumn('weight', function ($report) {
                    $data = '-';
                    $data = $report->weight;
                    if ($report->weight == '' || $report->weight == null || $report->weight == 0) {
                        return '<span class="text-primary">0</span>';
                    }
                    if ($report->type == 'credit') {
                        $data = '<span class="text-primary">+' . $data . '</span>';
                    } else {
                        $data = '<span class="text-danger">-' . $data . '</span>';
                    }
                    return $data;
                })

                ->addColumn('rate', function ($report) {
                    $data = '-';
                    $data = $report->rate;
                    if ($report->rate == '' || $report->rate == null || $report->rate == 0) {
                        return '<span class="text-primary">0</span>';
                    }
                    if ($report->type == 'credit') {
                        $data = '<span class="text-primary">+' . $data . '</span>';
                    } else {
                        $data = '<span class="text-danger">-' . $data . '</span>';
                    }
                    return $data;
                })

                ->addColumn('touch', function ($report) {
                    $data = '-';
                    $data = $report->touch;
                    if ($report->touch == '' || $report->touch == null || $report->touch == 0) {
                        return '<span class="text-primary">0</span>';
                    }
                    if ($report->type == 'credit') {
                        $data = '<span class="text-primary">+' . $data . '</span>';
                    } else {
                        $data = '<span class="text-danger">-' . $data . '</span>';
                    }
                    return $data;
                })
                ->addColumn('fine', function ($report) {
                    $data = '-';
                    $data = $report->fine;
                    if ($report->fine == '' || $report->fine == null || $report->fine == 0) {
                        return '<span class="text-primary">0</span>';
                    }
                    if ($report->type == 'credit') {
                        $data = '<span class="text-primary">+' . $data . '</span>';
                    } else {
                        $data = '<span class="text-danger">-' . $data . '</span>';
                    }
                    return $data;
                })
                ->addColumn('amount', function ($report) {
                    $data = '-';

                    $data = $report->amount;
                    if ($report->amount == '' || $report->amount == null || $report->amount == 0) {
                        return '<span class="text-primary">0</span>';
                    }
                    if ($report->type == 'credit' && $report->method != 1) {
                        $data = '<span class="text-primary">+' . $data . '</span>';
                    } elseif ($report->type == 'debit' && $report->method == 1) {
                        $data = '<span class="text-primary">+' . $data . '</span>';
                    } else {
                        $data = '<span class="text-danger">-' . $data . '</span>';
                    }
                    return $data;
                })



                // // debit

                // ->addColumn('debit_weight', function ($report) {
                //     $data = '-';
                //     if ($report->type == 'debit') {
                //         $data = $report->weight;
                //     }
                //     return $data;
                // })

                // ->addColumn('debit_rate', function ($report) {
                //     $data = '-';
                //     if ($report->type == 'debit') {
                //         $data = $report->rate;
                //     }
                //     return $data;
                // })

                // ->addColumn('debit_touch', function ($report) {
                //     $data = '-';
                //     if ($report->type == 'debit') {
                //         $data = $report->touch;
                //     }
                //     return $data;
                // })
                // ->addColumn('debit_fine', function ($report) {
                //     $data = '-';
                //     if ($report->type == 'debit') {
                //         $data = $report->fine;
                //     }
                //     return $data;
                // })
                // ->addColumn('debit_amount', function ($report) {
                //     $data = '-';
                //     if ($report->type == 'credit' && $report->method == 1) {
                //         $data = $report->amount;
                //     } elseif ($report->type == 'debit' && $report->method != 1) {
                //         $data = $report->amount;
                //     }
                //     return $data;
                // })

                ->with('op_amount_only', $op_amount_only)
                ->with('op_chor_amount', $op_chor_amount)
                ->with('op_amount', $op_amount)
                ->with('op_fine', $op_fine)
                ->with('op_dhal_waight', $op_dhal_waight)
                ->with('op_dhal_fine', $op_dhal_fine)
                ->with('op_del_chors', $op_del_chors)
                ->with('op_no_del_chors', $op_no_del_chors)

                ->with('co_amount_only', $co_amount_only)
                ->with('co_chor_amount', $co_chor_amount)
                ->with('co_amount', $co_amount)
                ->with('co_fine', $co_fine)
                ->with('co_dhal_waight', $co_dhal_waight)
                ->with('co_dhal_fine', $co_dhal_fine)
                ->with('co_del_chors', $co_del_chors)
                ->with('co_no_del_chors', $co_no_del_chors)


                ->rawColumns(['action', 'weight', 'amount', 'fine', 'touch', 'rate', 'is_deleverd', 'credit_weight', 'credit_rate', 'credit_touch', 'credit_fine', 'credit_amount'])
                ->make(true);
        }
    }
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'date' => 'required|date',
            // 'name' => 'required|string|max:255',
            'account_id' => 'required|string',
            'weight' => 'required|numeric|min:0',
            'rate' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'type' => 'required|string|in:levana,devana',

        ]);

        // Handle account_id
        $accountId = $this->resolveAccountId($request->account_id);

        $data = $request->merge([
            'account_id' => $accountId,
        ])->all();

        // Create a new chorsa record
        Chorsa::create($data);

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

        Transactions::where('id', $request->id)
            ->update([
                'is_delivered' => $request->status
            ]);
        return true;
    }
    public function getData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $accountName = $request->input('account_name');
        $search = $request->input('search');
        // $fromAmount = $request->input('from_amount');
        // $toAmount = $request->input('to_amount');
        $columns = ['date', 'weight', 'rate', 'total', 'notes', 'type', 'created_at'];
        $query = Chorsa::query()->with('account');
        $query1 = Chorsa::query()->with('account');

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
            $query1->whereBetween('date', [$startDate, $endDate]);
        }

        if ($accountName) {
            $query->where('account_id', $accountName);
            $query1->where('account_id', $accountName);
        }

        if ($search) {
            $query->where(function ($subQuery) use ($columns, $search) {
                foreach ($columns as $column) {
                    $subQuery = $subQuery->orWhere($column, 'LIKE', "%{$search}%");
                }
                return $subQuery;
            });
            $query1->where(function ($subQuery) use ($columns, $search) {
                foreach ($columns as $column) {
                    $subQuery = $subQuery->orWhere($column, 'LIKE', "%{$search}%");
                }
                return $subQuery;
            });
        }

        // if ($fromAmount) {
        //     $query->where('total', '>=', $fromAmount);
        // }

        // if ($toAmount) {
        //     $query->where('total', '<=', $toAmount);
        // }

        $data = [
            'levana' => $query->where('type', 'levana')->get(),
            'devana' => $query1->where('type', 'devana')->get(),
        ];

        // $data = [
        //     'levana' => Chorsa::where('type', 'levana')->get(),
        //     'devana' => Chorsa::where('type', 'devana')->get(),
        // ];

        // $data = [
        //     'levana' => Chorsa::with('account')->where('type', 'levana')->get(),
        //     'devana' => Chorsa::with('account')->where('type', 'devana')->get(),
        // ];

        return response()->json($data);
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

    public function amountUpdate(Request $request) {
        if ($request->ajax()) {
            $account = Account::where('default_opp_acc', 1)->first();
            
            $data = [
                'date'          => $request->endDate,
                'account_id'    => self::CLOSE_AMOUNT,
                'amount'        => $request->closeAmount,
                'opp_account_id' => $account->account_id,
                'notes'         => '',
                'type'          => 'debit',
                'method'        => 2,
            ];

            Transactions::create($data);

            $nextDayDate = Carbon::parse($request->endDate)->addDays(1)->format('Y-m-d');

            $data = [
                'date'          => $nextDayDate,
                'account_id'    => self::OPEN_AMOUNT,
                'amount'        => $request->closeAmount,
                'opp_account_id' => $account->account_id,
                'notes'         => '',
                'type'          => 'credit',
                'method'        => 2,
            ];

            Transactions::create($data);

            return response()->json(['success' => true]);      
        }
    }

    public function rateUpdate(Request $request) {
        if ($request->ajax()) {
            $account = Account::where('default_opp_acc', 1)->first();

            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $accountName = $request->accountName;
            $search = $request->search;
            $delivered = $request->delivered;

            // Adjust the columns to match your database table structure
            $columns = ['date', 'weight', 'rate', 'amount', 'notes', 'type', 'created_at'];
            $query = Transactions::query()->where('method', '1');

            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }

            if ($delivered != 'all') {
                if ($delivered == 'delivered') {
                    $query->where('is_delivered', '1');
                }
                if ($delivered == 'not_delivered') {
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

            $totalBuyWeight = 0;
            $totalSellWeight = 0;
            $fine = 0;

            foreach ($data as $key => $value) {
                if ($value->is_delivered !== '1') {
                    if ($value->type === 'credit') {
                        $totalBuyWeight += $value->weight;
                    } else {
                        $totalSellWeight += $value->weight;
                    }
                }
            }

            $fine = Transactions::where('method', '4')->whereNull('is_delivered')->sum('fine');

            $weight = $fine + $totalBuyWeight - $totalSellWeight; //difference

            if ($weight > 0) {
                $type = 'debit';
            } else {
                $type = 'credit';
            }
            
            $data = [
                'date'          => $request->endDate,
                'account_id'    => self::CLOSE_AMOUNT,
                'amount'        => abs($weight) * $request->closeRate,
                'opp_account_id' => $account->account_id,
                'notes'         => '',
                'type'          => $type,
                'method'        => 1,
                'weight'        => abs($weight),
                'rate'          => $request->closeRate,
            ];

            Transactions::create($data);

            $nextDayDate = Carbon::parse($request->endDate)->addDays(1)->format('Y-m-d');

            if ($weight > 0) {
                $type = 'credit';
            } else {
                $type = 'debit';
            }

            $data = [
                'date'          => $nextDayDate,
                'account_id'    => self::OPEN_AMOUNT,
                'amount'        => abs($weight) * $request->closeRate,
                'opp_account_id' => $account->account_id,
                'notes'         => '',
                'type'          => $type,
                'method'        => 1,
                'weight'        => abs($weight),
                'rate'          => $request->closeRate,
            ];

            Transactions::create($data);

            return response()->json(['success' => true]);      
        }
    }

    public function profitLossCalculation(Request $request) {
        if ($request->ajax()) {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $accountName = $request->accountName;
            $search = $request->search;
            $delivered = $request->delivered;
            $closeRate = $request->closeRate;

            // Adjust the columns to match your database table structure
            $columns = ['date', 'weight', 'rate', 'amount', 'notes', 'type', 'created_at'];
            $amountCredit   = Transactions::query()->where('method', '2')->where('type', 'credit');
            $amountDebit    = Transactions::query()->where('method', '2')->where('type', 'debit');
            $chorsaCredit   = Transactions::query()->where('method', '1')->where('type', 'credit')->whereNotNull('rate');
            $chorsaDebit    = Transactions::query()->where('method', '1')->where('type', 'debit')->whereNotNull('rate');

            $chorsaRateCredit   = Transactions::query()->where('method', '1')->where('type', 'credit')->whereNull('rate');
            $chorsaRateDebit    = Transactions::query()->where('method', '1')->where('type', 'debit')->whereNull('rate');

            $dhalCredit    = Transactions::query()->where('method', '4')->where('type', 'credit')->whereNull('is_delivered');

            if ($startDate && $endDate) {
                $amountCredit->whereBetween('date', [$startDate, $endDate]);
                $amountDebit->whereBetween('date', [$startDate, $endDate]);
                $chorsaCredit->whereBetween('date', [$startDate, $endDate]);
                $chorsaDebit->whereBetween('date', [$startDate, $endDate]);

                $chorsaRateCredit->whereBetween('date', [$startDate, $endDate]);
                $chorsaRateDebit->whereBetween('date', [$startDate, $endDate]);

                $dhalCredit->whereBetween('date', [$startDate, $endDate]);
            }

            if ($delivered != 'all') {
                if ($delivered == 'delivered') {
                    $amountCredit->where('is_delivered', '1');
                    $amountDebit->where('is_delivered', '1');
                    $chorsaCredit->where('is_delivered', '1');
                    $chorsaDebit->where('is_delivered', '1');

                    $chorsaRateCredit->where('is_delivered', '1');
                    $chorsaRateDebit->where('is_delivered', '1');
                }
                if ($delivered == 'not_delivered') {
                    $amountCredit->whereNull('is_delivered');
                    $amountDebit->whereNull('is_delivered');
                    $chorsaCredit->whereNull('is_delivered');
                    $chorsaDebit->whereNull('is_delivered');

                    $chorsaRateCredit->whereNull('is_delivered');
                    $chorsaRateDebit->whereNull('is_delivered');
                }
            }

            if ($accountName) {
                $amountCredit->where('account_id', $accountName);
                $amountDebit->where('account_id', $accountName);
                $chorsaCredit->where('account_id', $accountName);
                $chorsaDebit->where('account_id', $accountName);

                $chorsaRateCredit->where('account_id', $accountName);
                $chorsaRateDebit->where('account_id', $accountName);

                $dhalCredit->where('account_id', $accountName);
            }

            if ($search) {
                $amountCredit->where(function ($subQuery) use ($columns, $search) {
                    foreach ($columns as $column) {
                        $subQuery->orWhere($column, 'LIKE', "%{$search}%");
                    }
                });
                $amountDebit->where(function ($subQuery) use ($columns, $search) {
                    foreach ($columns as $column) {
                        $subQuery->orWhere($column, 'LIKE', "%{$search}%");
                    }
                });
                $chorsaCredit->where(function ($subQuery) use ($columns, $search) {
                    foreach ($columns as $column) {
                        $subQuery->orWhere($column, 'LIKE', "%{$search}%");
                    }
                });
                $chorsaDebit->where(function ($subQuery) use ($columns, $search) {
                    foreach ($columns as $column) {
                        $subQuery->orWhere($column, 'LIKE', "%{$search}%");
                    }
                });

                $chorsaRateCredit->where(function ($subQuery) use ($columns, $search) {
                    foreach ($columns as $column) {
                        $subQuery->orWhere($column, 'LIKE', "%{$search}%");
                    }
                });
                $chorsaRateDebit->where(function ($subQuery) use ($columns, $search) {
                    foreach ($columns as $column) {
                        $subQuery->orWhere($column, 'LIKE', "%{$search}%");
                    }
                });

                $dhalCredit->where(function ($subQuery) use ($columns, $search) {
                    foreach ($columns as $column) {
                        $subQuery->orWhere($column, 'LIKE', "%{$search}%");
                    }
                });
            }

            $data['amountCredit'] = $amountCredit->sum('amount');
            $data['amountDebit'] = $amountDebit->sum('amount');
            $data['chorsaCredit'] = $chorsaCredit->sum('amount');
            $data['chorsaDebit'] = $chorsaDebit->sum('amount');
            $data['chorsaRateCredit'] = $amountCredit->sum('weight') * $closeRate;
            $data['chorsaRateDebit'] = $amountDebit->sum('weight') * $closeRate;
            $data['dhalCredit'] = $amountDebit->sum('fine') * $closeRate;

            $data['result'] = $data['amountCredit'] - $data['amountDebit'] - $data['chorsaCredit'] + $data['chorsaDebit'] - $data['dhalCredit'] - $data['chorsaRateCredit'] + $data['chorsaRateDebit'];

            return response()->json(['success' => true, 'data' => $data]);
        }
    }

    public function loadProfitLoass(Request $request) {
        if ($request->ajax()) {
            $data = array();
            // amount
            $data['amountCredit'] = Transactions::where('method', '2')
                ->whereNull('is_delivered')
                ->where('type', 'credit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->sum('amount');
            $data['amountDebit'] = Transactions::where('method', '2')
                ->whereNull('is_delivered')
                ->where('type', 'debit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->sum('amount');
            $data['amountCalculated'] = round($data['amountCredit'] - $data['amountDebit']);

            // chorsa fine (without rate)
            $data['fineChorsaCredit'] = Transactions::where('method', '1')
                ->whereNull('is_delivered')
                ->whereNull('rate')
                ->where('type', 'credit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->sum('weight');
            $data['fineChorsaDebit'] = Transactions::where('method', '1')
                ->whereNull('is_delivered')
                // ->whereNull('rate')
                ->where(function (Builder $query) {
                    $query->whereNull('rate')
                        ->orWhere('rate', '=', '0');
                })
                ->where('type', 'debit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->sum('weight');
            $data['softwareFine'] = $data['fineChorsaCredit'] - $data['fineChorsaDebit'];

            // dhal 
            $data['dhalFine'] = Transactions::where('method', '4')
                ->whereNull('is_delivered')
                ->where('type', 'credit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->sum('fine');

            // chorsa weight with rate
            $data['chorsaCredit'] = Transactions::where('method', '1')
                ->whereNull('is_delivered')
                ->whereNotNull('rate')
                ->where('type', 'credit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->sum('weight');
            $data['chorsaDebit'] = Transactions::where('method', '1')
                ->whereNull('is_delivered')
                ->whereNotNull('rate')
                ->where('type', 'debit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->sum('weight');
            $data['chorsaStock'] = $data['chorsaCredit'] - $data['chorsaDebit'];

            // chorsa amount with rate
            $data['chorsaAmountCredit'] = Transactions::where('method', '1')
                ->whereNull('is_delivered')
                ->whereNotNull('rate')
                ->where('type', 'credit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->sum('amount');
            $data['chorsaAmountDebit'] = Transactions::where('method', '1')
                ->whereNull('is_delivered')
                ->whereNotNull('rate')
                ->where('type', 'debit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->sum('amount');

            // chorsa amount with rate
            $data['amountChorsaCredit'] = Transactions::where('method', '1')
                ->whereNull('is_delivered')
                ->whereNotNull('rate')
                ->where('type', 'credit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->sum('amount');
            $data['amountChorsaDebit'] = Transactions::where('method', '1')
                ->whereNull('is_delivered')
                ->whereNotNull('rate')
                ->where('type', 'debit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->sum('amount');
            $data['chorsaAmount'] = round($data['amountChorsaDebit'] - $data['amountChorsaCredit']);

            // Chorsa Partly Delivered
            /*$data['chorsaDeliveredCredit'] = Transactions::where('method', '1')
                ->whereNull('is_delivered')
                ->whereNotNull('rate')
                ->where('type', 'credit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->sum('delivered_qty');
            $data['chorsaDeliveredDebit'] = Transactions::where('method', '1')
                ->whereNull('is_delivered')
                ->whereNotNull('rate')
                ->where('type', 'debit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->sum('delivered_qty');
            $data['chorsaDelivered'] = $data['chorsaDeliveredCredit'] - $data['chorsaDeliveredDebit'];*/

            $chorsaDeliveredCredit = Transactions::withSum('delivered', 'delivered_qty')
                ->where('method', '1')
                ->where('type', 'credit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->get();

            $chorsaDeliveredCreditSum = 0;
            
            foreach ($chorsaDeliveredCredit as $key => $value) {
                $chorsaDeliveredCreditSum += $value->delivered_sum_delivered_qty;
            }

            $chorsaDeliveredDebit = Transactions::withSum('delivered', 'delivered_qty')
                ->where('method', '1')
                ->where('type', 'debit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->get();

            $chorsaDeliveredDebitSum = 0;
            
            foreach ($chorsaDeliveredDebit as $key => $value) {
                $chorsaDeliveredDebitSum += $value->delivered_sum_delivered_qty;
            }

            // $data['chorsaDeliveredLabel'] = $chorsaDeliveredCreditSum - $chorsaDeliveredDebitSum;
            $data['chorsaDelivered'] = $chorsaDeliveredCreditSum - $chorsaDeliveredDebitSum;

            // Delivered Amount
            $deliveredCreditAmount = Transactions::withSum('delivered', 'delivered_qty')
                ->where('method', '1')
                ->whereNull('is_delivered')
                ->whereNotNull('rate')
                ->where('type', 'credit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->get();

            $deliveredCreditAmountSum = 0;
            foreach ($deliveredCreditAmount as $key => $value) {
                $deliveredCreditAmountSum += $value->delivered_sum_delivered_qty * $value->rate;
            }

            $deliveredDebitAmount = Transactions::withSum('delivered', 'delivered_qty')
                ->where('method', '1')
                ->whereNull('is_delivered')
                ->whereNotNull('rate')
                ->where('type', 'debit')
                ->whereBetween('date', [$request->startDate, $request->endDate])
                ->when($request->name, function($query) use ($request){
                    return $query->where('account_id', $request->name);
                })
                ->when($request->oppName, function($query) use ($request){
                    return $query->where('opp_account_id', $request->oppName);
                })
                ->get();

            $deliveredDebitAmountSum = 0;
            foreach ($deliveredDebitAmount as $key => $value) {
                $deliveredDebitAmountSum += $value->delivered_sum_delivered_qty * $value->rate;
            };

            $data['deliveredAmount'] = $deliveredDebitAmountSum - $deliveredCreditAmountSum;
                
            return response()->json(['success' => true, 'data' => $data]);
        }
    }
}
