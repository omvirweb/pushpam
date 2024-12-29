<?php

use App\Http\Controllers\AmountController;
use App\Http\Controllers\ChorsaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DanaController;
use App\Http\Controllers\DhalController;
use App\Http\Controllers\FineGoldController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Report2Controller;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\FleetReportController;
use App\Http\Controllers\FleetDataController;
use App\Http\Controllers\UserController as AdminUserController;
use Illuminate\Support\Facades\Artisan;

Route::resource('permissions', App\Http\Controllers\PermissionController::class);

// Route::resource('permissions', App\Http\Controllers\PermissionController::class);


Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('change.password');
    Route::put('/change-password', [UserController::class, 'update'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    ///////////////account/////////////////////
    Route::get('/account/create', [AccountController::class, 'create'])->name('account.create');
    Route::post('/account', [AccountController::class, 'store'])->name('account.store');
    Route::get('/accounts/data', [AccountController::class, 'dataDisplay'])->name('accounts.data');
    Route::delete('/account/{id}', [AccountController::class, 'destroy'])->name('account.destroy');
    Route::get('accounts/{id}', [AccountController::class, 'edit'])->name('accounts.edit');
    Route::put('accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');

    Route::get('/fleetdata/create', [FleetDataController::class, 'create'])->name('fleetdata.create');
    Route::post('/fleetdata', [FleetDataController::class, 'store'])->name('fleetdata.store');
    Route::get('/fleetdata/data', [FleetDataController::class, 'dataDisplay'])->name('fleetdata.data');
    Route::delete('/fleetdata/{id}', [FleetDataController::class, 'destroy'])->name('fleetdata.destroy');
    Route::get('fleetdata/{id}', [FleetDataController::class, 'edit'])->name('fleetdata.edit');
    Route::put('fleetdata/{id}', [FleetDataController::class, 'update'])->name('fleetdata.update');

    ////////item pagess////////////
    Route::get('/item/create', [ItemController::class, 'create'])->name('item.create');
    Route::post('/item', [ItemController::class, 'store'])->name('item.store');
    Route::get('/items', [ItemController::class, 'dataDisplay'])->name('items.data');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::get('item/{id}', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('item/{id}', [ItemController::class, 'update'])->name('items.update');


    ////////amount pagess////////////
    Route::get('/amounts', [AmountController::class, 'index'])->name('amounts.index');
    Route::post('/amounts', [AmountController::class, 'store'])->name('amounts.store');
    Route::get('/amounts/data', [AmountController::class, 'getAmounts'])->name('amounts.data');
    // Route::get('/amounts/data', [AmountController::class, 'getData'])->name('amounts.data');
    Route::get('/amounts/last-transaction', [AmountController::class, 'getLastTransaction'])->name('amounts.last-transaction');
    Route::delete('/amounts/{id}', [AmountController::class, 'destroy'])->name('amounts.destroy');
    Route::get('amounts/{id}', [AmountController::class, 'edit'])->name('amounts.edit');
    Route::put('/amounts/{id}', [AmountController::class, 'update'])->name('amounts.update');

    ////////////transactions////////////////
    Route::get('/transactions/create', [TransactionsController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionsController::class, 'store'])->name('transactions.store');
    // Route::get('/transactions/data', [TransactionsController::class, 'getData'])->name('transactions.data');
    Route::get('transactions/account/{id}', [TransactionsController::class, 'getAccount'])->name('transactions.getAccount');
    // Route to display the transactions page
    Route::get('/transactions/show', [TransactionsController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/fetch', [TransactionsController::class, 'fetchTransactions'])->name('transactions.fetch');
    // Route to fetch the transactions data
    Route::get('/transactions/data', [TransactionsController::class, 'getData'])->name('transactions.data');
    // In web.php or api.php
    Route::get('accounts/getOppAccount/{id}', [TransactionsController::class, 'getOppAccount'])->name('accounts.getOppAccount');
    Route::post('transactions/accountStore', [TransactionsController::class, 'accountStore'])->name('accounts.accountStore');
    Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/oppAccountFetch', [TransactionsController::class, 'oppAccountFetch'])->name('transactions.oppAccountFetch');

    //////////////fine///////////////////
    Route::get('/fine', [FineGoldController::class, 'index'])->name('fine.index');
    Route::post('/fine/store', [FineGoldController::class, 'store'])->name('fine.store');
    Route::get('/fine/data', [FineGoldController::class, 'getFine'])->name('fine.data');
    Route::delete('/fine/{id}', [FineGoldController::class, 'destroy'])->name('fine.destroy');
    // Route::get('/fine/data', [FineGoldController::class, 'getData'])->name('fine.data');
    Route::delete('/fine/{id}', [FineGoldController::class, 'destroy'])->name('fine.destroy');
    Route::get('fine/{id}', [FineGoldController::class, 'edit'])->name('fine.edit');
    Route::put('/fine/{id}', [FineGoldController::class, 'update'])->name('fine.update');


    // Route::get('/report', [ReportController::class, 'index'])->name('report.index');

    Route::group(['as' => 'report.', 'prefix' => 'report'], function () {
        Route::controller(ReportController::class)->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::delete('/destroy/{id}', 'destroy')->name('destroy'); // Update to DELETE method
            Route::post('/change_status', 'change_status')->name('change.status');
            Route::post('/status', 'changestatus')->name('status');
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('datatable');
            Route::post('/amountUpdate', 'amountUpdate')->name('amountUpdate');
            Route::post('/rateUpdate', 'rateUpdate')->name('rateUpdate');
            Route::post('/profitLossCalculation', 'profitLossCalculation')->name('profitLossCalculation');
            Route::post('/loadProfitLoass', 'loadProfitLoass')->name('loadProfitLoass');
        });
    });

    Route::group(['as' => 'report2.', 'prefix' => 'report2'], function () {
        Route::controller(Report2Controller::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/datatable', 'datatable')->name('datatable');
        });
    });

    ///////////chorsa////////////////////
    Route::get('/chorsa', [ChorsaController::class, 'index'])->name('chorsa.index');
    Route::post('/chorsa', [ChorsaController::class, 'store'])->name('chorsa.store');
    Route::get('/chorsa/data', [ChorsaController::class, 'getData'])->name('chorsa.data');
    Route::post('/chorsa/status', [ChorsaController::class, 'changestatus'])->name('chorsa.status');
    Route::delete('/chorsa/{id}', [ChorsaController::class, 'destroy'])->name('chorsa.destroy');
    Route::get('chorsa/{id}', [ChorsaController::class, 'edit'])->name('chorsa.edit');
    Route::put('chorsa/{id}', [ChorsaController::class, 'update'])->name('chorsa.update');
    Route::post('/chorsa/datewise', [ChorsaController::class, 'datewise'])->name('chorsa.datewise');
    Route::post('chorsa/qytUpdate', [ChorsaController::class, 'qytUpdate'])->name('chorsa.qytupdate');
    Route::post('/chorsa/isChecked', [ChorsaController::class, 'isChecked'])->name('chorsa.isChecked');
    Route::get('/chorsa/deliveredRecords/{id}', [ChorsaController::class, 'getDeliveredRecords'])->name('chorsa.deliveredRecords');
    Route::delete('/delivered/delete/{id}', [ChorsaController::class, 'deleteDeliveredRecord'])->name('chorsa.deleteDelivered');






    //////////dhal///////////////
    Route::get('/dhal', [DhalController::class, 'index'])->name('dhal.index');
    Route::post('/dhal/store', [DhalController::class, 'store'])->name('dhal.store');
    Route::get('/dhal/data', [DhalController::class, 'getDhal'])->name('dhal.data');
    Route::get('/dhal/create', [DhalController::class, 'create'])->name('dhal.create');
    Route::post('dhal/itemStore', [DhalController::class, 'itemStore'])->name('item.itemStore');
    Route::delete('/dhal/{id}', [DhalController::class, 'destroy'])->name('dhal.destroy');
    Route::get('/dhal/{id}', [DhalController::class, 'edit'])->name('dhal.edit');
    Route::put('/dhal/{id}', [DhalController::class, 'update'])->name('dhal.update');
    Route::post('/dhal/status', [DhalController::class, 'changestatus'])->name('dhal.status');


    /////////dana//////////
    Route::get('/dana', [DanaController::class, 'index'])->name('dana');
    Route::post('/dana/store', [DanaController::class, 'store'])->name('dana.store');
    Route::get('/dana/data', [DanaController::class, 'getData'])->name('dana.data');


    //Fleet Report
    Route::get('/fleet/upload', [FleetController::class, 'index'])->name('fleet.uploadForm');
    Route::post('/fleet/upload', [FleetController::class, 'upload'])->name('fleet.upload');

    Route::get('/fleet/report', [FleetReportController::class, 'index'])->name('fleet.reportForm');
    Route::post('/fleet/generate-report', [FleetReportController::class, 'generateReport'])->name('fleet.generateReport');
    Route::get('/fleet/alldata', [FleetReportController::class, 'displayAllFleetData'])->name('fleet.reportScreen');
    Route::post('/fleet/alldata', [FleetReportController::class, 'displayAllFleetData'])->name('fleet.reportScreen_post');
    Route::get('loadFleetData', [FleetReportController::class, 'loadFleetData'])->name('loadFleetData');
    Route::post('/get-files', [FleetReportController::class, 'getFiles'])->name('fleet.getFiles');
    Route::delete('/delete-fleet-file/{id}', [FleetReportController::class, 'deleteFleetFile'])->name('fleetfile.destroy');

    Route::resource('companies', CompanyController::class);
    Route::resource('users', AdminUserController::class);
});

Route::get('/fleet/upload-cron', function () {
    try {
        $exitCode = Artisan::call('json:upload');
        $output = Artisan::output();
        return response()->json([
            'message' => 'JSON upload cron executed successfully',
            'exit_code' => $exitCode,
            'output' => $output,
        ]);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'An error occurred during execution',
            'details' => $e->getMessage(),
        ], 500);
    }
});


// Route::post('/create-customer', [CustomerController::class, 'createCustomer'])->name('createCustomer');
// Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
// Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
// Route::post('/submit-form', [CustomerController::class, 'submitForm'])->name('customer');
// Route::get('/customers/autocomplete', [CustomerController::class, 'autocomplete'])->name('customers.autocomplete');
// Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');

require __DIR__ . '/auth.php';
