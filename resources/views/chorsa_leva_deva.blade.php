@extends('layouts.app')
@section('main-content')
    <style>
        .actionWidth {
            width: 70px;
        }

        .rateWidth {
            width: 83px;
        }


        .buySellWidth {
            width: 66px;
        }

        .dateWidth {
            width: 90px;
        }

        .totalWidth {
            width: 120px;
        }

        .weightWidth,
        .deliveredWidth {
            width: 150px;
        }

        .nameWidth {
            width: 124px;
        }

        .bold-text {
            font-weight: bold;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin-bottom: 0px;
        }

        #chorsa-table td,
        #chorsa-table th {
            padding: 4px 8px;
            /* Adjust the padding to make the row height smaller */
            font-size: 14px;
            /* Optionally, reduce the font size */
        }

        #chorsa-table tbody tr {
            height: 20px;
            /* You can adjust the row height directly */
        }
    </style>
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form id="chorsa-form">
                        <div class="card-header">
                            <h3>Chorsa Leva Deva</h3>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid py-2">
                                <div id="alert-container" class="position-fixed top-0 end-0 p-3">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                                    <div class="text-danger error-date"></div>
                                </div>
                                <div class="col-md-4">
                                    <label for="account_id" class="form-label">Name<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <select id="account_id" name="account_id" class="form-control">
                                        <!-- Options will be loaded dynamically -->
                                    </select>
                                    <div class="text-danger error-account_id"></div>
                                </div>
                                <div class="col-md-4">
                                    <label for="opp_account_id" class="form-label">Opp. Account</label>
                                    <select id="opp_account_id" name="opp_account_id" class="form-control">
                                        {{-- <option value="self" selected>Self</option> --}}
                                    </select>
                                    <div class="text-danger error-opp_account_id"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Weight<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <input type="number" name="weight" required class="form-control"
                                        placeholder="Enter Weight" min="0">
                                    <div class="text-danger error-weight"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Rate</label>
                                    <input type="number" name="rate" required class="form-control"
                                        placeholder="Enter Rate" min="0">
                                    <div class="text-danger error-rate"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Total</label>
                                    <input type="number" name="total" required class="form-control"
                                        placeholder="Enter Total" min="0" readonly>
                                    <div class="text-danger error-total"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Notes</label>
                                    <input type="text" name="notes" class="form-control" placeholder="Enter Notes">
                                    <div class="text-danger error-notes"></div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" id="transaction-id">
                        <div class="row">

                            <div class="modal-footer col-md-3 d-flex justify-content-center">
                                <button type="button" id="levana-btn" class="btn btn-primary">Buy / Credit</button>
                                <button type="button" id="devana-btn" class="btn btn-danger">Sell / Debit</button>
                            </div>
                            <div class="modal-footer col-md-9 flex justify-content-between flex-wrap">
                                <div class="">
                                    <label class="form-label">Buy Total :</label> <span id="buy-total">0.00

                                    </span>
                                </div>
                                <div class="">
                                    <label class="form-label">Sell Total</label> : <span id="sell-total">
                                        0.00
                                    </span>
                                </div>
                                <div class="">
                                    <label class="form-label">Difference</label> : <span id="difference">
                                        0.00
                                    </span>
                                </div>
                                <div class="">
                                    <label class="form-label">Rate</label> <input type="number" id="newrate"required
                                        value="0" class="" placeholder="Enter Rate" min="0">
                                </div>
                                <div class="">
                                    <label class="form-label">Profit Loss</label> :
                                    <span id="proffloss">0.00</span>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
                <div class="row">

                    <div class="col-md-3">
                        <label for="new_account_id" class="form-label">Name</label>
                        <select id="new_account_id" name="new_account_id" class="form-control">
                            <!-- Options will be loaded dynamically -->
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="daterange" class="form-label">Date Range</label>
                        {{-- <div id="reportrange"
                            style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div> --}}
                        <input type="text" id="daterange" class="form-control" name="daterange" />
                    </div>
                    <div class="col-md-2 mt-4">
                        Do Not consider Date Range &nbsp; &nbsp;
                        <input type="checkbox" id="notConsiderDateRange" name="notConsiderDateRange" value="1"
                            class="form-control" checked>
                    </div>
                    <div class="col-md-2">
                        <label for="search" class="form-label">Search</label>
                        <input type="search" id="newsearch" name="#newsearch" class="form-control"
                            placeholder="Search" />

                    </div>
                    <div class="col-md-2">
                        <label for="delivered" class="form-label">Delivered</label>
                        <select id="delivered" name="delivered" class="form-control">
                            <option value="1">Not Delivered</option>
                            <option value="2">Delivered</option>
                            <option value="3">All</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-6">
                <div class="card mb-4">
                    <div class="card-header p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="d-flex justify-content-center">Chorsa Buy</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0" style="box-shadow: 0px 5px 10px lightblue;">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 table-bordered table-hover" id="levana-table">
                                <thead class="table-active">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Action</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Name</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Delivered</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Weight</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Rate</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Total</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Notes</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <table class="table align-items-center mb-0 table-bordered table-hover">
                                <tr>
                                    <td>Total</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><span id="total-weight">0</span></td>
                                    <td class="text-right"><span id="total-rate">0</span></td>
                                    <td class="text-right"><span id="total-total">0</span></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card mb-4">
                    <div class="card-header p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="d-flex justify-content-center">Chorsa Sell</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0" style="box-shadow: 0px 5px 10px lightblue;">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 table-bordered table-hover" id="devana-table">
                                <thead class="table-active">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Action</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Name</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Delivered</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Weight</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Rate</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Total</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Notes</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <table class="table align-items-center mb-0 table-bordered table-hover">
                                <tr>
                                    <td>Total</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><span id="total-weight_devana">0</span>
                                    </td>
                                    <td class="text-right"><span id="total-rate_devana">0</span>
                                    </td>
                                    <td class="text-right"><span id="total-total_devana">0</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body p-0" style="box-shadow: 0px 5px 10px lightblue;">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 table-bordered table-hover chorsa-table"
                                id="chorsa-table">
                                <thead class="table-active">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Action</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Date</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Delivered</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Buy/Sell</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Name</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Weight</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Rate</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Total</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="scrollable-tbody">
                                    <!-- Credit data will be appended here by jQuery -->
                                </tbody>
                            </table>
                        </div>
                        <div>
                            {{-- <table class="table align-items-center mb-0 table-bordered table-hover">
                                <tr>
                                    <td>Buy Total</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><span id="total-buy-weight">0</span></td>
                                    <td class="text-right"><span id="total-buy-rate">0</span></td>
                                    <td class="text-right"><span id="total-buy-total">0</span></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Sell Total</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><span id="total-sell-weight">0</span></td>
                                    <td class="text-right"><span id="total-sell-rate">0</span></td>
                                    <td class="text-right"><span id="total-sell-total">0</span></td>
                                    <td></td>
                                </tr>
                            </table> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Delivered Records Modal -->
    <div class="modal fade" id="deliveredRecordsModal" tabindex="-1" aria-labelledby="deliveredModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deliveredModalLabel">Delivered Records</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: scroll;">
                    <!-- Table for Delivered Records -->
                    <table id="deliveredRecordsTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Delivered Quantity</th>
                                <th>Delivered Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be dynamically populated -->
                        </tbody>
                    </table>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#opp_account_id').select2({
                placeholder: 'Select Opp. Account',
                ajax: {
                    url: '{{ route('transactions.oppAccountFetch') }}',
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
                    saveNewTag(data.text, '#opp_account_id');
                }
                clearErrorMessages();
            });

            // Preselect the value after Select2 is initialized
            var preselectedValue = '<?php echo $data['account_id'] ?? 0; ?>'; // Set your preselected value (e.g., account ID 1)
            if (preselectedValue != 0) {
                var preselectedText = '<?php echo $data['account_name'] ?? ''; ?>'; // Set the preselected text (e.g., the account name)

                // Manually add the preselected option
                var option = new Option(preselectedText, preselectedValue, true, true);
                $('#opp_account_id').append(option).trigger('change'); // Append the option and trigger change event

                // Optionally, remove the preselected option if you don't want it to persist after AJAX loads new options
                $('#opp_account_id').on('select2:select', function(e) {
                    $('#opp_account_id option[value="' + preselectedValue + '"]')
                        .remove(); // Remove the temporary option after selection
                });
            }

            // Initialize Date Range Picker with dd-mm-yyyy format
            $('#daterange').daterangepicker({
                locale: {
                    format: 'DD-MM-YYYY'
                }
            });

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
            // Function to open Select2 and perform click on the search input
            function openSelect2AndClick() {
                // Open Select2 dropdown
                $('#account_id').select2('open');

                // Delay to ensure dropdown is open and rendered
                setTimeout(function() {
                    // Find the search input field within the dropdown
                    var $searchField = $('#account_id').data('select2').$dropdown.find(
                        '.select2-search__field');
                    if ($searchField.length) {
                        // Use JavaScript to set focus and select the text inside the input
                        $searchField.focus();
                        $searchField[0].select(); // Optional: Select text in the input
                    }
                }, 300); // Adjust timeout if needed
            }

            // Call the function to open and click on Select2 on page load
            openSelect2AndClick();
            $(document).on("change", "#new_account_id , #daterange, #delivered, #notConsiderDateRange", function() {
                loadChorsa();
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
                var toatldevana = $("#total-sell-weight").text();
                var toatllevana = $("#total-buy-total").text();

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

            $('#levana-btn').on('click', function() {
                submitForm('credit');
            });

            $('#devana-btn').on('click', function() {
                submitForm('debit');
            });

            // Load chorsa data on page load
            loadChorsa();



            function submitForm(type) {
                const formData = {
                    date: $('input[name="date"]').val(),
                    // name: $('input[name="name"]').val(),
                    account_id: $("#account_id").val(),
                    opp_account_id: $('#opp_account_id').val(),
                    weight: $('input[name="weight"]').val(),
                    rate: $('input[name="rate"]').val(),
                    amount: $('input[name="total"]').val(),
                    notes: $('input[name="notes"]').val(),
                    type: type,
                    _token: "{{ csrf_token() }}",
                };

                const button = type === "credit" ? $("#credit-btn") : $("#debit-btn");
                button.prop("disabled", true);

                // Check if transaction ID is set for update
                const transactionId = $("#transaction-id").val();

                const ajaxOptions = {
                    url: transactionId ?
                        `{{ route('chorsa.update', '') }}/${transactionId}` : "{{ route('chorsa.store') }}",
                    method: transactionId ? "PUT" : "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showAlert('Chorsa created successfully!', 'success');
                            $('#chorsa-form')[0].reset();
                            $('#account_id').val(null).trigger('change');
                            clearErrorMessages();
                            loadChorsa();
                            $("#transaction-id").val(""); // Clear transaction ID
                            // Reset button visibility
                            $('#levana-btn').show();
                            $('#devana-btn').show();
                            openSelect2AndClick();
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
                    },
                    complete: function() {
                        button.prop("disabled", false);
                    },
                };

                $.ajax(ajaxOptions);
            }


            function changestatus(status, id) {
                // console.log("Changing status for ID:", id, "to:", status);

                $.ajax({
                    url: "{{ route('chorsa.status') }}",
                    method: "POST",
                    data: {
                        status: status,
                        id: id,
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(data) {
                        showAlert('Status Changed successfully!', 'success');
                        loadChorsa();
                    },
                    error: function(xhr) {
                        showAlert("An error occurred while changing status.", "danger");
                    }
                });
            }

            let chorsaTable = $('#chorsa-table').DataTable({
                "paging": false,
                "info": false,
                "searching": false,
                "ordering": true,
                "order": [
                    [1, 'asc'] // Default sorting on the second column (index 1)
                ],
                "columnDefs": [{
                        "orderable": false,
                        "targets": 0 // Disable sorting on the first column (buttons)
                    },
                    {
                        "orderable": true,
                        "targets": [1, 2, 3, 4, 5, 6, 7] // Enable sorting on other columns
                    },
                    {
                        "width": "78px",
                        "targets": 0,
                        "className": "actionWidth"
                    },
                    {
                        "width": "84px",
                        "targets": 1,
                        "className": "dateWidth"
                    },
                    {
                        "width": "145px",
                        "targets": 2,
                        "className": "deliveredWidth"
                    },
                    {
                        "width": "60px",
                        "targets": 3,
                        "className": "buySellWidth"
                    },
                    {
                        "width": "117px",
                        "targets": 4,
                        "className": "nameWidth"
                    },
                    {
                        "width": "150px",
                        "targets": 5,
                        "className": "weightWidth"
                    },
                    {
                        "width": "78px",
                        "targets": 6,
                        "className": "rateWidth"
                    },
                    {
                        "width": "120px",
                        "targets": 7,
                        "className": "totalWidth"
                    },
                ],
                "language": {
                    "emptyTable": "No data available" // Message for empty data
                },
                "createdRow": function(row, data, dataIndex) {
                    // Apply the 'text-right' class to the "Amount" cell (index 3)
                    $('td:eq(1)', row).addClass('text-left text-wrap bold-text');
                    $('td:eq(2)', row).addClass('text-center bold-text');
                    $('td:eq(3)', row).addClass('text-left text-wrap bold-text');
                    $('td:eq(4)', row).addClass('text-right text-wrap bold-text');
                    $('td:eq(5)', row).addClass('text-right text-wrap bold-text');
                    $('td:eq(6)', row).addClass('text-right text-wrap bold-text');
                    $('td:eq(7)', row).addClass('text-right text-wrap bold-text');
                    $('td:eq(8)', row).addClass('text-left text-wrap bold-text');

                    // Determine the color based on the row content
                    let type = $('td:eq(3)', row).text()
                        .trim(); // Assuming the type is in column index 3 (Buy/Sell)
                    let color = type === 'Buy' ? 'blue' :
                        'red'; // Adjust if your type values are different

                    // $('td', row).css('color', color);

                    // Apply color to all cells in the row
                    $('td', row).each(function(index) {
                        // Apply color to specific columns if needed
                        if (index >= 3 && index <= 7) { // Assuming amounts are in these columns
                            $(this).css('color', color);
                        }
                    });
                }
            });

            function loadChorsa() {
                var dateRange = $("#daterange").val();
                var dates = dateRange.split(" - ");
                var startDate = dates[0].split("-").reverse().join("-");
                var endDate = dates[1].split("-").reverse().join("-");
                var accountName = $("#new_account_id").val();
                var search = $("#newsearch").val();
                var notConsiderDateRange = $("#notConsiderDateRange").is(":checked");
                var delivered = $('#delivered').find(":selected").val();

                let totalBuyWeight = 0;
                let totalBuyRate = 0;
                let totalBuyTotal = 0;
                let totalSellWeight = 0;
                let totalSellRate = 0;
                let totalSellTotal = 0;
                let fine = 0;

                $.ajax({
                    url: "{{ route('chorsa.data') }}",
                    method: "GET",
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        account_name: accountName,
                        search: search,
                        notConsiderDateRange: notConsiderDateRange,
                        delivered: delivered,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        // Clear existing data
                        chorsaTable.clear();
                        if (response.success) {
                            // Process credits data
                            response.data.forEach(function(chorsa) {
                                if (chorsa.weight !== null && chorsa.weight !== "") {
                                    // Calculate Pending (Remaining) / Total Weight
                                    let deliveredQty = parseFloat(chorsa
                                        .delivered_sum_delivered_qty) || 0;
                                    let weight = parseFloat(chorsa.weight) || 0;
                                    let pendingWeight = weight - deliveredQty;
                                    let weightDisplay =
                                        `${pendingWeight.toFixed(3)} / ${weight.toFixed(3)}`; // Pending / Total format
                                    // Accumulate totals
                                    if (chorsa.is_delivered !== '1') {
                                        fine = parseFloat(response.fine);
                                        if (chorsa.type === 'credit') { // Buy
                                            totalBuyWeight += parseFloat(chorsa.weight) || 0;
                                            totalBuyRate += parseFloat(chorsa.rate) || 0;
                                            totalBuyTotal += parseFloat(chorsa.amount) || 0;
                                        } else { // Sell
                                            totalSellWeight += parseFloat(chorsa.weight) || 0;
                                            totalSellRate += parseFloat(chorsa.rate) || 0;
                                            totalSellTotal += parseFloat(chorsa.amount) || 0;
                                        }
                                    }

                                    chorsaTable.row
                                        .add([
                                            `<td class="text-center">
                                            <button class="btn btn-warning btn-sm edit-btn" data-id="${chorsa.id}"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-danger btn-sm delete-btn" data-id="${chorsa.id}"><i class="fas fa-trash-alt"></i></button>
                                        </td>`,
                                            `<td class="text-center">${formatDate(chorsa.date)}</td>`,
                                            `<td class="text-center">
                                            <!--<input type="checkbox" class="status-checkbox" data-id="${chorsa.id}" ${chorsa.is_delivered == 1 ? 'checked' : ''} /> -->
                                            <input type="text" class="delivered_qty delivered_qty_${chorsa.id}" name="delivered_qty" maxlength="7" size="6" data-id="${chorsa.id}" value="">
                                            <span class="delivered-qty" data-id="${chorsa.id}" style="cursor:pointer; text-decoration:underline;">
                                                ${chorsa.delivered_sum_delivered_qty === null ? '' : chorsa.delivered_sum_delivered_qty}
                                            </span>
                                        </td>`,
                                            `<td class="text-right">${chorsa.type === 'credit' ? 'Buy' : (chorsa.type === 'debit' ? 'Sell' : '')}</td>`,
                                            `<td class="text-left text-center">
                                                ${chorsa.account ? chorsa.account.account_name : ''}
                                                <input type="checkbox" class="isChecked" data-id="${chorsa.id}" ${chorsa.is_checked == 1 ? 'checked' : ''} />
                                            </td>`,
                                            `<td class="text-right text-wrap"><span class="d-none" id="weight_${chorsa.id}">${weight}</span> ${weightDisplay}</td>`,
                                            `<td class="text-right">${chorsa.rate || ''}</td>`,
                                            `<td class="text-right">${chorsa.amount || ''}</td>`,
                                            `<td class="text-wrap text-left">${chorsa.notes || ''}</td>`
                                        ])
                                        .draw();
                                }
                            });

                            $("#total-buy-weight").text(totalBuyWeight.toFixed(2));
                            $("#total-buy-rate").text(totalBuyRate.toFixed(2));
                            $("#total-buy-total").text(totalBuyTotal.toFixed(2));
                            $("#total-sell-weight").text(totalSellWeight.toFixed(2));
                            $("#total-sell-rate").text(totalSellRate.toFixed(2));
                            $("#total-sell-total").text(totalSellTotal.toFixed(2));

                            $("#buy-total").text(totalBuyTotal.toFixed(2));
                            $("#sell-total").text(totalSellTotal.toFixed(2));

                            let difference = fine + totalBuyWeight - totalSellWeight;
                            $('#difference').text(difference.toFixed(2));
                            ratecalculation();

                            // Reattach event handlers
                            $(".delete-btn").on("click", function() {
                                let id = $(this).data("id");
                                if (confirm(
                                        "Are you sure you want to delete this transaction?")) {
                                    deleteChorsa(id);
                                }
                            });

                            $(".edit-btn").on("click", function() {
                                let button = $(this); // Now button is defined
                                let id = $(this).data("id");
                                editChorsa(id);
                            });

                            $(".status-checkbox").on("change", function() {
                                let id = $(this).data("id");
                                let status = $(this).is(":checked") ? 1 : 0;
                                changestatus(status, id);
                            });
                            $(".isChecked").on("change", function() {
                                let id = $(this).data("id");
                                let value = $(this).is(":checked") ? 1 : 0;
                                isCheckedMethod(value, id);
                            });
                        } else {
                            // If no data found, display a message
                            chorsaTable.clear().draw();
                            // showAlert(response.message, "warning");
                        }
                    },
                    error: function(xhr) {
                        showAlert("An error occurred while loading data.", "danger");
                    },
                });
            }

            $(document).on("click", ".delivered-qty", function() {
                let chorsaId = $(this).data("id"); // Get the chorsa ID from the span
                console.log("Delivered quantity clicked. Chorsa ID:", chorsaId);
                // Fetch and display delivered records
                openDeliveredModal(chorsaId);
            });

            // Function to open the modal and fetch delivered records
            let deliveredTable = $('#deliveredRecordsTable').DataTable({
                "paging": false,
                "info": false,
                "searching": false,
                "ordering": true, // Enable sorting
                "columnDefs": [{
                        "orderable": false,
                        "targets": 0
                    } // Disable sorting on the action column (first column)
                ],
                "language": {
                    "emptyTable": "No data available" // Message for empty data
                },
                "createdRow": function(row, data, dataIndex) {
                    $('td:eq(1)', row).addClass('text-right text-wrap');
                    $('td:eq(2)', row).addClass('text-right text-wrap');
                }
            });

            function openDeliveredModal(chorsaId) {
                $.ajax({
                    url: "{{ route('chorsa.deliveredRecords', '') }}/" +
                        chorsaId, // Your API route for fetching delivered records
                    method: 'GET',
                    success: function(response) {
                        // Clear existing data
                        deliveredTable.clear();
                        if (response.success && response.deliveredRecords.length > 0) {
                            // Process credits data
                            response.deliveredRecords.forEach(function(record) {

                                deliveredTable.row
                                    .add([
                                        `<td>
                                                <button class="btn btn-danger btn-sm delete-delivered-btn" data-id="${record.id}" data-chorsa-id="${chorsaId}">Delete</button>
                                            </td>`,
                                        `<td class="text-right">${record.delivered_qty}</td>`,
                                        `<td class="text-right">${formatDate(record.delivered_date)}</td>`
                                    ])
                                    .draw();
                            });

                        } else {
                            // If no data found, display a message
                            deliveredTable.clear().draw();
                            // showAlert(response.message, "warning");
                        }
                        $('#deliveredRecordsModal').modal('show');
                    },
                    error: function(xhr) {
                        showAlert("An error occurred while loading data.", "danger");
                    },
                });
            }

            // Handle delete button click for delivered records
            $(document).on("click", ".delete-delivered-btn", function() {
                let deliveredId = $(this).data("id");
                let chorsaId = $(this).data("chorsa-id");

                if (confirm("Are you sure you want to delete this delivered record?")) {
                    $.ajax({
                        url: "{{ route('chorsa.deleteDelivered', '') }}/" +
                            deliveredId, // Your API route for deleting delivered record
                        method: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}", // Include CSRF token for security
                        },
                        success: function(response) {
                            if (response.success) {
                                // alert(response.message);
                                // Refresh the delivered records after deletion
                                openDeliveredModal(
                                    chorsaId
                                ); // Reload the delivered records for the same chorsa
                                loadChorsa();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function(xhr) {
                            alert("An error occurred while deleting the record.");
                        }
                    });
                }
            });

            function deleteChorsa(id) {
                $.ajax({
                    url: "{{ route('chorsa.destroy', '') }}/" + id,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('Record deleted successfully!', 'success');
                            loadChorsa(); // Reload the data to reflect the deletion
                        } else {
                            showAlert('An error occurred while deleting the record.', 'danger');
                        }
                    },
                    error: function() {
                        showAlert('An error occurred while deleting the record.', 'danger');
                    }
                });
            }

            function editChorsa(id) {

                $.ajax({
                    url: "{{ route('chorsa.edit', '') }}/" + id, // Adjust URL if necessary
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (!response.error) {
                            // Populate the form with the existing data
                            $('input[name="date"]').val(response.date);
                            $('#account_id').val(response.account_id).trigger('change');
                            $('input[name="weight"]').val(response.weight);
                            $('input[name="rate"]').val(response.rate);
                            $('input[name="total"]').val(response.amount);
                            $('input[name="notes"]').val(response.notes);
                            $('#method').val(response
                                .method); // Assuming method is used to determine type

                            // Set select2 values
                            $('#account_id').val(response.account_id).trigger(
                                'change'); // Update select2


                            // Show the appropriate button based on the transaction type
                            if (response.type === 'credit') {
                                $('#levana-btn').show();
                                $('#devana-btn').hide();
                            } else if (response.type === 'debit') {
                                $('#devana-btn').show();
                                $('#levana-btn').hide();
                            }

                            // Store the transaction ID in a hidden input field for updating
                            $('#transaction-id').val(response.id);
                            // Set the Opp. Account select2 with the new value if necessary
                            $('#account_id').append(new Option(response.account_name,
                                response.account_id, true, true)).trigger('change');

                        } else {
                            showAlert('Transaction not found', 'danger');
                        }
                    },
                    error: function() {
                        showAlert('An error occurred while fetching transaction details.',
                            'danger');
                    }
                });

            }

            function showAlert(message, type) {
                $('#alert-container').empty().html(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`).show().delay(2000).fadeOut(400, function() {
                    $(this).empty();
                });
            }

            // Attach event listeners for form fields to clear error messages
            $('input[name="date"], #account_id, input[name="weight"], input[name="rate"], input[name="total"], input[name="notes"]')
                .on('input change', function() {
                    clearErrorMessages();
                });

            function clearErrorMessages() {
                $('.text-danger').not('.required-asterisk').text('');

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

            $("#newrate").change(function() {
                var newRate = $('#newrate').val();
                var dateRange = $("#daterange").val();
                var dates = dateRange.split(" - ");
                var endDate = dates[1].split("-").reverse().join("-");

                $.ajax({
                    url: "<?php echo route('chorsa.datewise'); ?>",
                    type: "POST",
                    data: {
                        'newRate': newRate,
                        'endDate': endDate,
                    },
                    success: function(response) {

                    }
                });
            });

        });

        $(document).on('change', '.delivered_qty', function() {
            var dateRange = $("#daterange").val();
            var dates = dateRange.split(" - ");
            var endDate = dates[1].split("-").reverse().join("-");

            $.ajax({
                url: "<?php echo route('chorsa.qytupdate'); ?>",
                type: "POST",
                data: {
                    'qty': $(this).val(),
                    'id': $(this).data("id"),
                    'date': endDate,
                },
                success: function(response) {

                }
            });
        });

        $(document).on("change", ".status-checkbox", function() {
            if ($(this).is(':checked')) {
                let id = $(this).data("id");
                var weight = $("#weight_" + id).text();
                $(".delivered_qty_" + id).val(parseFloat(weight).toFixed(3));
                $(".delivered_qty_" + id).trigger("change");
            }
        });

        function isCheckedMethod(value, id) {
            $.ajax({
                url: "{{ route('chorsa.isChecked') }}",
                method: "POST",
                data: {
                    value: value,
                    id: id,
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function(data) {
                    showAlert('Update successfully!', 'success');
                    loadChorsa();
                },
                error: function(xhr) {
                    showAlert("An error occurred while changing status.", "danger");
                }
            });
        }
    </script>
@endpush
