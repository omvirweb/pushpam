@extends('layouts.app')
@section('main-content')
    <style>
        .table tbody td {
            white-space: nowrap !important;
        }

        .table tbody td span {
            white-space: nowrap !important;
        }

        table.dataTable td.word-wrap {
            white-space: normal !important;
            word-wrap: break-word;
            word-break: break-all;
        }
    </style>
    <div class="container-fluid py-2">


        <div class="row align-items-center justify-content-between mb-4">
            <div class="col">
                {{--  <h2 class="fw-500">Dhal</h2>  --}}
            </div>
            {{--  <div class="col-auto">
                <a data-bs-toggle="modal" data-bs-target="#addstaff" class="btn btn-icon btn-3 btn-primary mb-0">
                    <i class="fa fa-plus me-2"></i> Add
                </a>
            </div>  --}}
        </div>
        <div class="card ">
            <div class="card-body ">
                <div class="container-fluid py-2">
                    <div id="alert-container" class="position-fixed top-0 end-0 p-3">
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-3">
                        <label for="new_account_id" class="form-label">Name</label>
                        <select id="new_account_id" name="new_account_id" class="form-control">
                            <!-- Options will be loaded dynamically -->
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="new_account_id" class="form-label">Date Range</label>
                        {{-- <div id="reportrange"
                            style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div> --}}
                        {{--  <input type="text" id="daterange" class="form-control" name="daterange"
                            value=" {{ date('m/d/Y') }} - {{ date('m/d/Y') }}" />  --}}
                        <input type="text" id="daterange" class="form-control" name="daterange" />
                    </div>
                    {{-- <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="search" id="newsearch" name="#newsearch" class="form-control" placeholder="Search" />

                    </div> --}}
                    <div class="col-md-3">
                        <label for="account_id" class="form-label">Opposite Name</label>
                        <select id="account_id" name="account_id" class="form-control">
                            <!-- Options will be loaded dynamically -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="delivered" class="form-label">Delivered</label>
                        <select id="delivered" class="form-control">
                            <option value="not_delivered" selected>Not Delivered</option>
                            <option value="delivered">Delivered</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                </div>

                <div class="row pt-4">
                    <div class="col-md-3">
                        <input type="checkbox" class="form-label" id="chorsa" name="chorsa" value="1" checked>
                        <label for="chorsa" class="form-label">Chorsa</label>
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" class="form-label" id="amount" name="amount" value="1" checked>
                        <label for="amount" class="form-label">Amount</label>
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" class="form-label" id="fine" name="fine" value="1" checked>
                        <label for="fine" class="form-label">Fine</label>
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" class="form-label" id="dhal" name="dhal" value="1" checked>
                        <label for="dhal" class="form-label">Dhal</label>
                    </div>
                </div>

                <!-- <div class="row mt-4">
                    <div class="col-md-4">
                        <label class="form-label">Total Of Above</label> : <span id="totalCase">{{ $totalCase }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Current Cash</label> : 
                        <input type="number" id="currentCash" required value="0" class="" placeholder="Current Cash" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Profit Loss</label> : <span id="profitLoss">0.00</span>
                    </div>
                </div> -->

                <div class="row mt-4">
                    <div class="col-md-3">
                        <label class="form-label">Close Amount</label> : 
                        <input type="number" class="profitLoss" id="closeAmount" required value="0" class="" placeholder="Close Amount" min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Real Stock</label> : 
                        <input type="number" id="realStock" required value="0" class="" placeholder="Real Stock" min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Close Rate</label> : 
                        <input type="number" class="profitLoss" id="closeRate" required value="0" class="" placeholder="Close Rate" min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Profit Loss</label> : <span id="profitLoss">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Credit Amount</label> : <span id="amountCredit">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Debit Amount</label> : <span id="amountDebit">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Amount Calculated</label> : <span id="amountCalculated">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Amount Diff</label> : <span id="amountDiff">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Final Amount</label> : <span id="finalAmount">0.00</span>
                    </div>
                    <!-- <div class="col-md-3"><br>
                        <label class="form-label">Chorsa Delivered</label> : <span id="chorsaDeliveredLabel">0.00</span>
                    </div> -->
                    <div class="col-md-3"><br>
                        <label class="form-label">Chorsa Delivered</label> : <span id="chorsaDelivered">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Chorsa Buy</label> : <span id="chorsaBuy">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Chorsa Sell</label> : <span id="chorsaSell">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Chorsa Stock</label> : <span id="chorsaStock">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Chorsa Buy Amount</label> : <span id="chorsaAmountCredit">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Chorsa Sell Amount</label> : <span id="chorsaAmountDebit">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Chorsa Amount</label> : <span id="chorsaAmount">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Chorsa Diff</label> : <span id="chorsaDiff">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Chorsa Buy Avg.</label> : <span id="chorsaBuyAvg">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Chorsa Sell Avg.</label> : <span id="chorsaSellAvg">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Chorsa Close Amount</label> : <span id="chorsaCloseAmount">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Fine Buy Qty</label> : <span id="fineChorsaCredit">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Fine Sell Qty</label> : <span id="fineChorsaDebit">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Software Fine</label> : <span id="softwareFine">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Fine Diff</label> : <span id="fineDiff">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Dhal Fine</label> : <span id="dhalFine">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Fine + Dhal Diff</label> : <span id="fineDhalDiff">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Fine + Dhal Amount</label> : <span id="fineDhalAmount">0.00</span>
                    </div>
                    <div class="col-md-3"><br>
                        <label class="form-label">Delivered Amount</label> : <span id="deliveredAmount">0.00</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="d-flex justify-content-center">Report</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0" style="box-shadow: 0px 5px 10px lightblue;">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 table-bordered table-hover">
                                <thead class="table-active">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width: 105px;">Type</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Amount</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Chorsa Amount</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Amount + Chorsa Amount</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width: 85px;">Fine</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width: 85px;">Dhal Weight</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width: 85px;">Dhal Fine</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width: 85px;">Chorsa Delivered</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width: 115px;">Chorsa Not Delivered</th>
                                    </tr>

                                    <tr>
                                        <td>Opening</td>
                                        <td class="text-right"><span id="op_amount_only">0.00</span></td>
                                        <td class="text-right"><span id="op_chor_amount">0.00</span></td>
                                        <td class="text-right"><span id="op_amount">0.00</span></td>
                                        <td class="text-right"><span id="op_fine">0.00</span>
                                        <td class="text-right"><span id="op_dhal_waight">0.00</span></td>
                                        <td class="text-right"><span id="op_dhal_fine">0.00</span></td>
                                        <td class="text-right"><span id="op_chor_del">0.00</span></td>
                                        <td class="text-right"><span id="op_chor_no_del">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td>Closing</td>
                                        <td class="text-right"><span id="co_amount_only">0.00</span></td>
                                        <td class="text-right"><span id="co_chor_amount">0.00</span></td>
                                        <td class="text-right"><span id="co_amount">0.00</span></td>
                                        <td class="text-right"><span id="co_fine">0.00</span>
                                        <td class="text-right"><span id="co_dhal_waight">0.00</span></td>
                                        <td class="text-right"><span id="co_dhal_fine">0.00</span></td>
                                        <td class="text-right"><span id="co_chor_del">0.00</span></td>
                                        <td class="text-right"><span id="co_chor_no_del">0.00</span></td>
                                    </tr>

                                </thead>
                                <tbody>
                                </tbody>

                            </table>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-12">
                                <h3 class="d-flex justify-content-center">Report</h3>
                            </div>
                        </div> --}}
                    </div>
                    <div class="card-body p-0" style="box-shadow: 0px 5px 10px lightblue;">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 table-bordered table-hover" id="datatable">
                                <thead class="table-active">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Id#</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Action</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Date</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Particular</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Name</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Opposite Name</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Delivered</th>


                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2 text-center">
                                            Type</th>
                                        {{-- <th class="text-uppercase text-secondary text-xs opacity-7 ps-2 text-center "
                                            colspan="5">
                                            Debit</th> --}}
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Weight</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Rate</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Touch</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Fine</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Amount</th>
                                        {{-- <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Notes</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Date</th> --}}
                                    </tr>
                                    {{-- <tr>
                                        <th colspan="7"></th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Weight</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Rate</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Touch</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Fine</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Amount</th>

                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Weight</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Rate</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Touch</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Fine</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Amount</th>
                                    </tr> --}}
                                </thead>
                                <tbody>
                                </tbody>
                                {{-- <tfoot>
                                    <tr>
                                        <th colspan="9" class="text-center">Opening</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">
                                            <label for="">
                                                Amount :
                                            </label> <span id="op_amount">0.00</span>

                                            <label for="">
                                                , Fine :
                                            </label> <span id="op_fine">0.00</span>


                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">
                                            <label for="">
                                                Dhal Waight :
                                            </label> <span id="op_dhal_waight">0.00</span>

                                            <label for="">
                                                ,Dhal Fine :
                                            </label> <span id="op_dhal_fine">0.00</span>


                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">
                                            <label for="">
                                                Chorsa Delivered :
                                            </label> <span id="op_chor_del">0.00</span>

                                            <label for="">
                                                , Chorsa Not Delivered :
                                            </label> <span id="op_chor_no_del">0.00</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="9" class="text-center">Closing</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">
                                            <label for="">
                                                Amount :
                                            </label> <span id="co_amount">0.00</span>

                                            <label for="">
                                                , Fine :
                                            </label> <span id="co_fine">0.00</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">
                                            <label for="">
                                                Dhal Waight :
                                            </label> <span id="co_dhal_waight">0.00</span>

                                            <label for="">
                                                ,Dhal Fine :
                                            </label> <span id="co_dhal_fine">0.00</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">
                                            <label for="">
                                                Chorsa Delivered :
                                            </label> <span id="co_chor_del">0.00</span>

                                            <label for="">
                                                , Chorsa Not Delivered :
                                            </label> <span id="co_chor_no_del">0.00</span>
                                        </th>
                                    </tr>
                                </tfoot> --}}
                            </table>
                        </div>

                    </div>
                </div>
            </div>



        </div>

    </div>
@endsection
@push('script')
    <script type="text/javascript">
        // $(document).ready(function() {

        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            paging: false, // Disable regular pagination
            scrollY: '400px', // Set the height of the scrollable area
            scrollCollapse: true, // Collapse the table when there is no data
            ajax: {
                url: '{!! route('report.datatable') !!}',
                data: function(d) {
                    var dates = $('#daterange').val().split(' - ');
                    d.start_date = moment(dates[0], 'DD/MM/YYYY').format('YYYY/MM/DD');
                    d.end_date = moment(dates[1], 'DD/MM/YYYY').format('YYYY/MM/DD');
                    d.chorsa = $('#chorsa').is(":checked");
                    d.amount = $('#amount').is(":checked");
                    d.fine = $('#fine').is(":checked");
                    d.dhal = $('#dhal').is(":checked");
                    d.name = $('#new_account_id').val();
                    d.opp_name = $('#account_id').val();
                    d.delivered = $('#delivered').val(); // Include delivered filter value
                },
                type: 'post',
                dataType: 'json',
                dataSrc: function(json) {
                    // Update footer with totals from server response
                    $('#op_amount_only').html(json.op_amount_only.toFixed(2));
                    $('#op_chor_amount').html(json.op_chor_amount.toFixed(2));
                    $('#op_amount').html(json.op_amount.toFixed(2));
                    $('#op_fine').html(json.op_fine.toFixed(2));
                    $('#op_dhal_waight').html(parseFloat(json.op_dhal_waight).toFixed(2));
                    $('#op_dhal_fine').html(json.op_dhal_fine.toFixed(2));
                    $('#op_chor_del').html(json.op_del_chors.toFixed(2));
                    $('#op_chor_no_del').html(json.op_no_del_chors.toFixed(2));

                    // Update footer with closing totals from server response
                    $('#co_amount_only').html(json.co_amount_only.toFixed(2));
                    $('#co_chor_amount').html(json.co_chor_amount.toFixed(2));
                    $('#co_amount').html(json.co_amount.toFixed(2));
                    $('#co_fine').html(json.co_fine.toFixed(2));
                    $('#co_dhal_waight').html(parseFloat(json.co_dhal_waight).toFixed(2));
                    $('#co_dhal_fine').html(json.co_dhal_fine.toFixed(2));
                    $('#co_chor_del').html(json.co_del_chors.toFixed(2));
                    $('#co_chor_no_del').html(json.co_no_del_chors.toFixed(2));

                    return json.data;
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'action',
                    name: 'action'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'notes',
                    name: 'notes',
                    className: 'word-wrap'
                },
                {
                    data: 'account.account_name',
                    name: 'account.account_name',
                    defaultContent: '',
                    className: 'word-wrap'
                },
                {
                    data: 'opposite_account.account_name',
                    name: 'opposite_account.account_name',
                    defaultContent: ''
                },
                {
                    data: 'is_deleverd',
                    name: 'is_deleverd',
                    className: 'text-center'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'weight',
                    name: 'weight',
                    className: 'text-right'
                },
                {
                    data: 'rate',
                    name: 'rate',
                    className: 'text-right'
                },
                {
                    data: 'touch',
                    name: 'touch',
                    className: 'text-right'
                },
                {
                    data: 'fine',
                    name: 'fine',
                    className: 'text-right'
                },
                {
                    data: 'amount',
                    name: 'amount',
                    className: 'text-right'
                },
                // {
                //     data: 'debit_weight',
                //     name: 'debit_weight'
                // },
                // {
                //     data: 'debit_rate',
                //     name: 'debit_rate'
                // },
                // {
                //     data: 'debit_touch',
                //     name: 'debit_touch'
                // },
                // {
                //     data: 'debit_fine',
                //     name: 'debit_fine'
                // },
                // {
                //     data: 'debit_amount',
                //     name: 'debit_amount'
                // }
            ]
        });

        $('#daterange , #new_account_id ,#account_id,#amount,#fine,#dhal,#chorsa,#delivered').change(
            function() {

                table.draw();
            });
        // });

        $(document).on('click', '.btnDelete', function() {
            var url = $(this).data('url');
            var id = $(this).data('id');

            if (confirm('Are you sure you want to delete this record?')) {
                $.ajax({
                    url: "{{ route('report.destroy', '') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert('Record deleted successfully.');
                        $('#datatable').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        alert('Failed to delete the record.');
                    }
                });
            }
        });

        $('#daterange').daterangepicker({
            locale: {
                format: 'DD-MM-YYYY'
            }
        });

        function changestatus(status, id) {
            console.log('yyy');
            $.ajax({
                url: "{{ route('report.status') }}",
                method: "POST",
                data: {
                    status: status,
                    id: id,
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function(data) {
                    // loadChorsa();
                    showAlert('Status Changed successfully!', 'success');

                    clearErrorMessages();
                    table.draw();
                }
            })
        }
        // $(document).ready(function() {

        $('#new_account_id').select2({
            placeholder: 'Select a Name',
            ajax: {
                url: '{{ route('transactions.index') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.account_id,
                                text: item.text
                            };
                        })
                    };
                },
                cache: true
            },
            tags: true,
            allowClear: true,
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if (data.newTag) {
                saveNewTag(data.text, '#new_account_id');
            }
            clearErrorMessages();
        }).on('select2:open', function() {
            // Set focus on the Select2 input field
            $('.select2-search__field').focus();
        });
        $('#account_id').select2({
            placeholder: 'Select a Name',
            ajax: {
                url: '{{ route('transactions.index') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.account_id,
                                text: item.text
                            };
                        })
                    };
                },
                cache: true
            },
            tags: true,
            allowClear: true,
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if (data.newTag) {
                saveNewTag(data.text, '#account_id');
            }
            clearErrorMessages();
        }).on('select2:open', function() {
            // Set focus on the Select2 input field
            $('.select2-search__field').focus();
        });

        $(document).on("change", "#new_account_id , #daterange", function() {
            loadChorsa();
            loadProfitLoass();
        });
        $(document).on("keyup", "#newsearch", function() {
            loadChorsa();
        });

        $(document).on("keyup", "#newrate", function() {
            ratecalculation();
        });

        function ratecalculation() {
            var rate = $("#newrate").val();
            var proff_loss = 0
            // if (rate != null && rate != 0) {

            var difference = $("#difference").text();
            var toatldevana = $("#total-total_devana").text();
            var toatllevana = $("#total-total").text();

            proff_loss = (Number(toatldevana) - Number(toatllevana)) + (Number(difference) * Number(rate));

            // }
            $("#proffloss").text(proff_loss.toFixed(2));
        }

        function saveNewTag(text, selectElement) {
            $.ajax({
                type: 'POST',
                url: '{{ route('accounts.accountStore') }}',
                data: {
                    account_name: text,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var newOption = new Option(response.text, response.id, false, true);
                    $(selectElement).append(newOption).trigger('change');
                },
                error: function(xhr) {
                    console.log('Error saving new tag:', xhr);
                }
            });
        }

        function formatDate(dateStr) {
            var date = new Date(dateStr);
            var day = ("0" + date.getDate()).slice(-2);
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        // Initialize DataTables
        let levanaTable = $('#levana-table').DataTable({
            "paging": false,
            "info": false,
            "searching": false,
            "createdRow": function(row, data, dataIndex) {
                // Apply the 'text-right' class to the "Amount" cell (index 3)
                $('td:eq(2)', row).addClass('text-right');
                $('td:eq(3)', row).addClass('text-right');
                $('td:eq(4)', row).addClass('text-right');
            }
        });

        let devanaTable = $('#devana-table').DataTable({
            "paging": false,
            "info": false,
            "searching": false,
            "createdRow": function(row, data, dataIndex) {
                // Apply the 'text-right' class to the "Amount" cell (index 3)
                $('td:eq(2)', row).addClass('text-right');
                $('td:eq(3)', row).addClass('text-right');
                $('td:eq(4)', row).addClass('text-right');
            }
        });

        $('#levana-btn').on('click', function() {
            submitForm('levana');
        });

        $('#devana-btn').on('click', function() {
            submitForm('devana');
        });

        // Load chorsa data on page load
        loadChorsa();

        // Attach event listeners for form fields to clear error messages
        $('input[name="date"], #account_id, input[name="weight"], input[name="rate"], input[name="total"], input[name="notes"]')
            .on('input change', function() {
                clearErrorMessages();
            });

        function clearErrorMessages() {
            $('.text-danger').not('.required-asterisk').text('');

        }

        function submitForm(type) {
            const formData = {
                date: $('input[name="date"]').val(),
                // name: $('input[name="name"]').val(),
                account_id: $('#account_id').val(),
                weight: $('input[name="weight"]').val(),
                rate: $('input[name="rate"]').val(),
                total: $('input[name="total"]').val(),
                notes: $('input[name="notes"]').val(),
                type: type
            };

            // const button = type === 'levana' ? $('#levana-btn') : $('#devana-btn');
            // button.prop('disabled', true);

            $.ajax({
                url: "{{ route('chorsa.store') }}",
                method: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('Chorsa created successfully!', 'success');
                        $('#chorsa-form')[0].reset();
                        $('#account_id').val(null).trigger('change');
                        clearErrorMessages();
                        loadChorsa();
                    } else {
                        showAlert(`Unexpected response: ${response.message}`, 'danger');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) { // Validation error
                        let errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                $(`.error-${field}`).text(errors[field][0]);
                            }
                        }
                    } else {
                        showAlert('An error occurred while processing the request.', 'danger');
                    }
                }
            });
        }

        function loadChorsa() {}



        function showAlert(message, type) {
            $('#alert-container').empty().html(`
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `).show().delay(2000).fadeOut(400, function() {
                $(this).empty();
            });
        }


        // Attach event listeners for weight and rate fields
        $('input[name="weight"], input[name="rate"]').on('input', calculateTotal);

        // Calculate and update total field
        function calculateTotal() {
            let weight = parseFloat($('input[name="weight"]').val()) || 0;
            let rate = parseFloat($('input[name="rate"]').val()) || 0;
            let total = weight * rate;
            $('input[name="total"]').val(total.toFixed(2));
        }

        // });

        $("#currentCash").change(function(){
            $("#profitLoss").text( ( parseFloat($("#totalCase").text()) - parseFloat($(this).val()) ).toFixed(2) );
        });

        // profit loss 2024-09-16 amount update
        // $("#closeAmount").change(function(){
        //     console.log("called UVI");
        //     var dateRange = $("#daterange").val();
        //     var dates = dateRange.split(" - ");
        //     var endDate = dates[1].split("-").reverse().join("-");

        //     $.ajax({
        //         url: "<?php echo route('report.amountUpdate'); ?>",
        //         type: "POST",
        //         data: {
        //             'closeAmount': $(this).val(),
        //             'endDate': endDate,
        //         },
        //         success: function(response) {

        //         }
        //     });
        // });

        // profit loss 2024-09-16 close rate update
        // $("#closeRate").change(function(){
        //     console.log("called UVI");
        //     var dateRange = $("#daterange").val();
        //     var dates = dateRange.split(" - ");
        //     var startDate = dates[0].split("-").reverse().join("-");
        //     var endDate = dates[1].split("-").reverse().join("-");

        //     $.ajax({
        //         url: "<?php echo route('report.rateUpdate'); ?>",
        //         type: "POST",
        //         data: {
        //             'closeRate': $(this).val(),
        //             'startDate': startDate,
        //             'endDate': endDate,
        //             'accountName': $('#account_id').val(),
        //             'search': $('#new_account_id').val(),
        //             'delivered': $('#delivered').val()
        //         },
        //         success: function(response) {

        //         }
        //     });
        // });

        // profit loss calculation
        // $(".profitLoss").change(function(){
        //     console.log("called UVI");
        //     var search = $('#new_account_id').val();
        //     var dateRange = $("#daterange").val();
        //     var dates = dateRange.split(" - ");
        //     var startDate = dates[0].split("-").reverse().join("-");
        //     var endDate = dates[1].split("-").reverse().join("-");
        //     var account_id = $('#account_id').val();
        //     var delivered = $('#delivered').val();
        //     var closeRate = $('#closeRate').val();
        //     var closeAmount = $('#closeAmount').val();

        //     $.ajax({
        //         url: "<?php echo route('report.profitLossCalculation'); ?>",
        //         type: "POST",
        //         data: {
        //             'search': search,
        //             'startDate': startDate,
        //             'endDate': endDate,
        //             'accountName': account_id,
        //             'delivered': delivered,
        //             'closeRate': closeRate
        //         },
        //         success: function(response) {
        //             console.log( 'one => ' + parseFloat(response.data.result));
        //             console.log(' two => ' + parseFloat(closeRate));
        //             $("#profitLoss").text( parseFloat(response.data.result) - parseFloat(closeAmount) );
        //         }
        //     });
        // });

        // profit loass calculation 18-09-2024

        $(document).on("change", "#account_id", function() {
            loadProfitLoass();
        });

        loadProfitLoass();

        function loadProfitLoass() {
            // $("#closeAmount").val('365565');
            // $("#closeRate").val('86200');
            // $("#realStock").val('14.5');
            // alert("called UVI");
            var name = $('#new_account_id').val(); // acount id
            var dateRange = $("#daterange").val();
            var dates = dateRange.split(" - ");
            var startDate = dates[0].split("-").reverse().join("-");
            var endDate = dates[1].split("-").reverse().join("-");
            var oppName = $('#account_id').val(); // acount id
            var delivered = $('#delivered').val();
            var realStock = $("#realStock").val() || 0;

            $.ajax({
                url: "<?php echo route('report.loadProfitLoass'); ?>",
                type: "POST",
                data: {
                    'name': name, // acount id
                    'startDate': startDate,
                    'endDate': endDate,
                    'oppName': oppName, // acount id
                    'delivered': delivered,
                },
                success: function(response) {
                    $("#amountCredit").text(response.data.amountCredit);
                    $("#amountDebit").text(response.data.amountDebit);
                    $("#amountCalculated").text(response.data.amountCalculated);
                    $("#fineChorsaCredit").text(parseFloat(response.data.fineChorsaCredit).toFixed(3));
                    $("#fineChorsaDebit").text(parseFloat(response.data.fineChorsaDebit).toFixed(3));
                    $("#softwareFine").text(parseFloat(response.data.softwareFine).toFixed(3));
                    $("#dhalFine").text(parseFloat(response.data.dhalFine).toFixed(3));
                    $("#chorsaStock").text(parseFloat(response.data.chorsaStock).toFixed(3));
                    $("#chorsaAmountCredit").text(parseFloat(response.data.chorsaAmountCredit).toFixed(0));
                    $("#chorsaAmountDebit").text(parseFloat(response.data.chorsaAmountDebit).toFixed(0));
                    
                    var chorsaCreditValue =  (response.data.chorsaCredit == 0) ? 1 : response.data.chorsaCredit;
                    var chorsaDebitValue =  (response.data.chorsaDebit == 0) ? 1 : response.data.chorsaDebit;

                    $("#chorsaBuyAvg").text((parseFloat(response.data.chorsaAmountCredit) / parseFloat(chorsaCreditValue)).toFixed(0));
                    $("#chorsaSellAvg").text((parseFloat(response.data.chorsaAmountDebit) / parseFloat(chorsaDebitValue)).toFixed(0));
                    $("#chorsaBuy").text(parseFloat(response.data.chorsaCredit).toFixed(3));
                    $("#chorsaSell").text(parseFloat(response.data.chorsaDebit).toFixed(3));
                    // $("#chorsaDeliveredLabel").text(parseFloat(response.data.chorsaDeliveredLabel).toFixed(3));
                    $("#chorsaDelivered").text(parseFloat(response.data.chorsaDelivered).toFixed(3));
                    $("#chorsaAmount").text(response.data.chorsaAmount);
                    $("#fineDiff").text(parseFloat(parseFloat(realStock) - parseFloat(response.data.softwareFine)).toFixed(3));
                    $("#deliveredAmount").text(response.data.deliveredAmount);

                    $("#closeAmount").trigger("change");
                    $("#realStock").trigger("change");
                    $("#closeRate").trigger("change");
                }
            });
        }

        $("#closeAmount, #realStock, #closeRate").change(function() {
            var closeAmount = $("#closeAmount").val() || 0;
            var realStock = $("#realStock").val() || 0;
            var closeRate = $("#closeRate").val() || 0;

            var amountCalculated = $("#amountCalculated").text();
            var amountDiff = parseFloat(closeAmount) - parseFloat(amountCalculated);
            $("#amountDiff").text(parseFloat(amountDiff).toFixed(0));

            // realStock
            var softwareFine = $("#softwareFine").text();
            var fineDiff = parseFloat(realStock) - parseFloat(softwareFine);
            $("#fineDiff").text(parseFloat(fineDiff).toFixed(3));

            // closeRate
            var chorsaStock = $("#chorsaStock").text();
            var chorsaDelivered = $("#chorsaDelivered").text();
            var chorsaCloseAmount = parseFloat(closeRate) * ( parseFloat(chorsaStock) - parseFloat(chorsaDelivered) );
            $("#chorsaCloseAmount").text(parseFloat(chorsaCloseAmount).toFixed(0));

            var chorsaAmount = $("#chorsaAmount").text();
            var deliveredAmount = $("#deliveredAmount").text();
            var chorsaDiff = parseFloat(chorsaCloseAmount) + parseFloat(chorsaAmount) - parseFloat(deliveredAmount);
            $("#chorsaDiff").text(parseFloat(chorsaDiff).toFixed(0));

            var finalAmount = parseFloat(amountDiff) + parseFloat(chorsaDiff);
            $("#finalAmount").text(parseFloat(finalAmount).toFixed(0));

            var dhalFine = $("#dhalFine").text();
            var fineDhalDiff = parseFloat(fineDiff) + parseFloat(dhalFine);
            $("#fineDhalDiff").text(parseFloat(fineDhalDiff).toFixed(3));

            var fineDhalAmount = parseFloat(fineDhalDiff) * parseFloat(closeRate);
            $("#fineDhalAmount").text(parseFloat(fineDhalAmount).toFixed(0));

            // profit code
            var profitLoss = parseFloat(finalAmount) + parseFloat(fineDhalAmount);
            $("#profitLoss").text(parseFloat(profitLoss).toFixed(0));
        });
        
        // profit loass calculation 18-09-2024
    </script>
@endpush
