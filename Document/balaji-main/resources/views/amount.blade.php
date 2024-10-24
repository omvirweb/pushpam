@extends('layouts.app')
@section('main-content')
    <div class="container-fluid py-2">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col">
                <h2 class="fw-500">Amount</h2>
            </div>
        </div>

        <!-- Alert Container -->
        <div class="container-fluid py-2">
            <div id="alert-container" class="position-fixed top-0 end-0 p-3">
            </div>
        </div>
        <!-- Amount Form Card -->
        <div class="card mb-4">
            {{-- <div class="card-header">
                <h3>Transaction Form</h3>
            </div> --}}
            <div class="card-body">
                <form id="amoun-form">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="date" class="form-label">Date<span
                                    class="text-danger required-asterisk">*</span></label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="{{ date('Y-m-d') }}" />
                            <div class="text-danger error-date"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="account_id" class="form-label">Name<span
                                    class="text-danger required-asterisk">*</span></label>
                            <select id="account_id" name="account_id" class="form-control">
                                <!-- Options will be loaded dynamically -->
                            </select>
                            <div class="text-danger error-account_id"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="amount" class="form-label">Amount<span
                                    class="text-danger required-asterisk">*</span></label>
                            <input type="number" name="amount" id="amount" class="form-control" min="0"
                                placeholder="Enter Amount" />
                            <div class="text-danger error-amount"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="opp_account_id" class="form-label">Opp. Account<span
                                    class="text-danger required-asterisk">*</span></label>
                            <select id="opp_account_id" name="opp_account_id" class="form-control">
                                {{-- <option value="self" selected>Self</option> --}}
                            </select>
                            <div class="text-danger error-opp_account_id"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="notes" class="form-label">Notes</label>
                            <input type="text" class="form-control" name="notes" id="notes"
                                placeholder="Enter Notes" />
                            <div class="text-danger error-notes"></div>
                        </div>
                    </div>
                    <input type="text" class="form-control" name="method" id="method" value="2" hidden />
                    <input type="hidden" id="transaction-id" value="">
                    <!-- Hidden input for storing transaction ID -->
                </form>
            </div>
            <div class="row">
                <div class="modal-footer col-md-2 d-flex justify-content-center">

                    <button type="button" id="credit-btn" class="btn btn-primary" value="credit">Credit</button>
                    <button type="button" id="debit-btn" class="btn btn-danger" value="debit">Debit</button>
                </div>
                <div class="modal-footer col-md-10 flex justify-content-between flex-wrap">

                    <div class="">
                        <label class="form-label">Credit Total :</label> <span id="credit_total">0.00

                        </span>
                    </div>
                    <div class="">
                        <label class="form-label">Debit Total</label> : <span id="debit_total">
                            0.00
                        </span>
                    </div>
                    <div class="">
                        <label class="form-label">Difference</label> : <span id="difference">
                            0.00
                        </span>
                    </div>

                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <!-- Tables for Credit and Debit -->
        <div class="row">
            <div class="card-body">
                <div class="row mb">
                    <div class="col-3">
                        <div class="input-group">
                            <input type="text" id="date-range" class="form-control" placeholder="Select date range">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <select id="account-name" id="account-name" class="form-control">
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="input-group">
                            <input type="number" id="from-amount" class="form-control" min="0"
                                placeholder="From Amount">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="input-group">
                            <input type="number" id="to-amount" class="form-control" min="0"
                                placeholder="To Amount">
                        </div>
                    </div>
                    <div class="col-2">
                        <button class="btn btn-primary" id="search-button">Search</button>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card mb-4">
                    <div class="card-header p-4">
                        <h3>Credit</h3>
                    </div>
                    <div class="card-body p-0" style="box-shadow: 0px 5px 10px lightblue;">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 table-bordered table-hover" id="credit-table">
                                <thead class="table-active">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Action
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Name
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Amount
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Notes
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="scrollable-tbody">
                                    <!-- Credit data will be appended here by jQuery -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card mb-4">
                    <div class="card-header p-4">
                        <h3>Debit</h3>
                    </div>
                    <div class="card-body p-0" style="box-shadow: 0px 5px 10px lightblue;">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 table-bordered table-hover" id="debit-table">
                                <thead class="table-active">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Action
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Name
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Amount
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Notes
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="scrollable-tbody">
                                    <!-- Debit data will be appended here by jQuery -->
                                </tbody>
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
        $(document).ready(function() {
            // Initialize Date Range Picker
            $('#date-range').daterangepicker({
                locale: {
                    format: 'DD-MM-YYYY'
                }
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

            $('#account-name').select2({
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
                    saveNewTag(data.text, '#account-name');
                }
                clearErrorMessages();
            }).on('select2:open', function() {
                // Set focus on the Select2 input field
                $('.select2-search__field').focus();
            });


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

            function setOppAccount() {
                $.ajax({
                    url: '{{ route('amounts.last-transaction') }}',
                    method: 'GET',
                    success: function(response) {
                        if (response.opp_account_name) {
                            // Set the "Opp. Account" field value
                            let option = new Option(response.opp_account_name, response
                                .opp_account_name, true, true);
                            $('#opp_account_id').append(option).trigger('change');
                        }
                    },
                    error: function(xhr) {
                        console.log('Error fetching last transaction:', xhr);
                    }
                });
            }
            // Call setOppAccount function on page load
            setOppAccount();

            $('#credit-btn').on('click', function() {
                submitForm('credit');
            });

            $('#debit-btn').on('click', function() {
                submitForm('debit');
            });

            // Load amounts on page load
            loadAmounts();

            // Load amounts on search button click
            $('#search-button').on('click', function() {
                loadAmounts();
            });

            function formatDate(dateStr) {
                var date = new Date(dateStr);
                var day = ("0" + date.getDate()).slice(-2);
                var month = ("0" + (date.getMonth() + 1)).slice(-2);
                var year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }

            function submitForm(type) {
                const formData = {
                    date: $('input[name="date"]').val(),
                    account_id: $('#account_id').val(),
                    amount: $('input[name="amount"]').val(),
                    opp_account_id: $('#opp_account_id').val(),
                    notes: $('input[name="notes"]').val(),
                    type: type,
                    method: 2,
                    _token: '{{ csrf_token() }}'
                };

                const button = type === 'credit' ? $('#credit-btn') : $('#debit-btn');
                button.prop('disabled', true);

                // Check if transaction ID is set for update
                const transactionId = $('#transaction-id').val();

                const ajaxOptions = {
                    url: transactionId ? `{{ route('amounts.update', '') }}/${transactionId}` :
                        "{{ route('amounts.store') }}",
                    method: transactionId ? "PUT" : "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showAlert('Transaction processed successfully', 'success');
                            loadAmounts(); // Reload amounts to show the newly added/updated data
                            $('#amoun-form')[0].reset();
                            $('#account_id').val(null).trigger('change');
                            $('#opp_account_id').val(null).trigger('change');
                            $('#account_id').select2('open').focus();
                            setOppAccount();
                            $('#transaction-id').val(''); // Clear transaction ID
                            // Reset button visibility
                            $('#credit-btn').show();
                            $('#debit-btn').show();
                            openSelect2AndClick();
                        } else {
                            showAlert('Failed to process transaction', 'danger');
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        showErrorMessages(errors);
                    },
                    complete: function() {
                        button.prop('disabled', false);
                    }
                };

                $.ajax(ajaxOptions);
            }

            // Initialize DataTables
            let creditTable = $('#credit-table').DataTable({
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
                        "targets": [1, 2, 3, 4] // Enable sorting on other columns
                    }
                ],
                "language": {
                    "emptyTable": "No data available" // Message for empty data
                },
                "createdRow": function(row, data, dataIndex) {
                    // Apply the 'text-right' class to the "Amount" cell (index 3)
                    $('td:eq(1)', row).addClass('text-left text-wrap');
                    $('td:eq(2)', row).addClass('text-right text-wrap');
                    $('td:eq(3)', row).addClass('text-left text-wrap');
                    $('td:eq(4)', row).addClass('text-left text-wrap');

                }
            });

            let debitTable = $('#debit-table').DataTable({
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
                        "targets": [1, 2, 3, 4] // Enable sorting on other columns
                    }
                ],
                "language": {
                    "emptyTable": "No data available" // Message for empty data
                },
                "createdRow": function(row, data, dataIndex) {
                    // Apply the 'text-right' class to the "Amount" cell (index 3)
                    $('td:eq(1)', row).addClass('text-left text-wrap');
                    $('td:eq(2)', row).addClass('text-right text-wrap');
                    $('td:eq(3)', row).addClass('text-left text-wrap');
                    $('td:eq(4)', row).addClass('text-left text-wrap');

                }
            });

            function loadAmounts() {
                var dateRange = $('#date-range').val();
                var dates = dateRange.split(' - ');
                var startDate = dates[0].split('-').reverse().join('-');
                var endDate = dates[1].split('-').reverse().join('-');
                var accountName = $('#account-name').val();
                var fromAmount = $('#from-amount').val();
                var toAmount = $('#to-amount').val();

                let creditTotal = 0;
                let debitTotal = 0;

                $.ajax({
                    url: "{{ route('amounts.data') }}",
                    method: "GET",
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        account_name: accountName,
                        from_amount: fromAmount,
                        to_amount: toAmount,
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(data) {
                        // Clear existing data
                        creditTable.clear().draw();
                        debitTable.clear().draw();

                        // Process credits data
                        data.credits.forEach(function(transaction) {
                            if (transaction.amount !== null && transaction.amount !== '') {
                                creditTotal += parseFloat(transaction.amount) || 0;

                                creditTable.row.add([
                                    `<td class="text-center">
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${transaction.id}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${transaction.id}"><i class="fas fa-trash-alt"></i></button>
                                </td>`,
                                    `<td class="text-left">${transaction.account ? transaction.account.account_name : ''}</td>`,
                                    `<td class="text-right">${transaction.amount || ''}</td>`,
                                    `<td class="text-wrap text-left">${transaction.notes || ''}</td>`,
                                    `<td class="text-center">${formatDate(transaction.date)}</td>`
                                ]).draw();
                            }
                        });

                        $('#credit_total').text(creditTotal.toFixed(2));

                        // Process debits data
                        data.debits.forEach(function(transaction) {
                            if (transaction.amount !== null && transaction.amount !== '') {
                                debitTotal += parseFloat(transaction.amount) || 0;

                                debitTable.row.add([
                                    `<td class="text-center">
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="${transaction.id}"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="${transaction.id}"><i class="fas fa-trash-alt"></i></button>
                                    </td>`,
                                    `<td class="text-left">${transaction.account ? transaction.account.account_name : ''}</td>`,
                                    `<td class="text-right">${transaction.amount || ''}</td>`,
                                    `<td class="text-wrap text-left">${transaction.notes || ''}</td>`,
                                    `<td class="text-center">${formatDate(transaction.date)}</td>`
                                ]).draw();
                            }
                        });

                        $('#debit_total').text(debitTotal.toFixed(2));
                        $('#difference').text((creditTotal - debitTotal).toFixed(2));

                        // Reattach event handlers
                        $('.delete-btn').on('click', function() {
                            let id = $(this).data('id');
                            if (confirm('Are you sure you want to delete this transaction?')) {
                                deleteTransaction(id);
                            }
                        });

                        $('.edit-btn').on('click', function() {
                            let id = $(this).data('id');
                            editTransaction(id);
                        });
                    },
                    error: function(xhr) {
                        showAlert('An error occurred while loading data.', 'danger');
                    }
                });
            }

            function editTransaction(id) {
                $.ajax({
                    url: "{{ route('amounts.edit', '') }}/" + id, // Adjust URL if necessary
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (!response.error) {
                            $('#date').val(response.date);
                            $('#amount').val(response.amount);
                            $('#notes').val(response.notes);
                            $('#method').val(response
                                .method); // Assuming method is used to determine type

                            // Set select2 values
                            $('#account_id').val(response.account_id).trigger(
                                'change'); // Update select2
                            $('#opp_account_id').val(response.opp_account_id).trigger(
                                'change'); // Update select2

                            // Show the appropriate button based on the transaction type
                            if (response.type === 'credit') {
                                $('#credit-btn').show();
                                $('#debit-btn').hide();
                            } else if (response.type === 'debit') {
                                $('#debit-btn').show();
                                $('#credit-btn').hide();
                            }

                            // Store the transaction ID in a hidden input field for updating
                            $('#transaction-id').val(response.id);
                            // Set the Opp. Account select2 with the new value if necessary
                            $('#account_id').append(new Option(response.account_name,
                                response.account_id, true, true)).trigger('change');
                            $('#opp_account_id').append(new Option(response.opp_account_name,
                                response.opp_account_id, true, true)).trigger('change');

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

            function deleteTransaction(id) {
                $.ajax({
                    url: "{{ route('amounts.destroy', '') }}/" + id,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('Transaction deleted successfully!', 'success');
                            loadAmounts(); // Reload the data to reflect the deletion
                        } else {
                            showAlert('An error occurred while deleting the transaction.', 'danger');
                        }
                    },
                    error: function() {
                        showAlert('An error occurred while deleting the transaction.', 'danger');
                    }
                });
            }

            function showErrorMessages(errors) {
                $.each(errors, function(key, message) {
                    $('.error-' + key).text(message);
                });
            }

            function showAlert(message, type) {
                const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
                $('#alert-container').html(alertHtml);

                // Auto-hide the alert after 5 seconds (5000 milliseconds)
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 3000);
            }

            function clearErrorMessages() {
                // $('.text-danger').text('');
                $('.text-danger').not('.required-asterisk').text('');
            }


            // Clear error messages when input fields are changed
            $('#amoun-form input, #amoun-form select').on('input change', function() {
                clearErrorMessages();
            });


            // Initialize DataTables
            // let creditTable = $('#credit-table').DataTable({
            //     "paging": false,
            //     "info": false,
            //     "searching": false,
            //     "createdRow": function(row, data, dataIndex) {
            //         // Apply the 'text-right' class to the "Amount" cell (index 3)
            //         $('td:eq(2)', row).addClass('text-right');
            //     }
            // });

            // let debitTable = $('#debit-table').DataTable({
            //     "paging": false,
            //     "info": false,
            //     "searching": false,
            //     "createdRow": function(row, data, dataIndex) {
            //         // Apply the 'text-right' class to the "Amount" cell (index 3)
            //         $('td:eq(2)', row).addClass('text-right');
            //     }
            // });

            // function loadAmounts() {
            //     var dateRange = $('#date-range').val();
            //     var dates = dateRange.split(' - ');
            //     // Convert dd-mm-yyyy to yyyy-mm-dd for the AJAX request
            //     var startDate = dates[0].split('-').reverse().join('-');
            //     var endDate = dates[1].split('-').reverse().join('-');
            //     var accountName = $('#account-name').val();
            //     var fromAmount = $('#from-amount').val();
            //     var toAmount = $('#to-amount').val();

            //     // Initialize totals
            //     let creditTotal = 0;
            //     let debitTotal = 0;

            //     $.ajax({
            //         url: "{{ route('amounts.data') }}",
            //         method: "GET",
            //         data: {
            //             start_date: startDate,
            //             end_date: endDate,
            //             account_name: accountName,
            //             from_amount: fromAmount,
            //             to_amount: toAmount,
            //             _token: '{{ csrf_token() }}' // Include CSRF token
            //         },
            //         success: function(data) {
            //             // Clear existing data
            //             let creditTableBody = $('#credit-table tbody');
            //             let debitTableBody = $('#debit-table tbody');
            //             creditTableBody.empty();
            //             debitTableBody.empty();

            //             // Process credits data
            //             data.credits.forEach(function(transaction) {
            //                 if (transaction.amount !== null && transaction.amount !== '') {
            //                     creditTotal += parseFloat(transaction.amount) || 0;

            //                     creditTableBody.append(`
        //                         <tr data-id="${transaction.id}">
        //                             <td class="text-center">
        //                                 <button class="btn btn-warning btn-sm edit-btn" data-id="${transaction.id}"><i class="fas fa-edit"></i></button>
        //                                 <button class="btn btn-danger btn-sm delete-btn" data-id="${transaction.id}"><i class="fas fa-trash-alt"></i></button>
        //                             </td>
        //                             <td class="text-left">${transaction.account ? transaction.account.account_name : ''}</td>
        //                             <td class="text-right">${transaction.amount || ''}</td>
        //                             <td class="text-wrap text-left">${transaction.notes || ''}</td>
        //                             <td class="text-center">${formatDate(transaction.date)}</td>
        //                         </tr>
        //                     `);
            //                 }
            //             });

            //             $('#credit_total').text(creditTotal.toFixed(2));

            //             // Process debits data
            //             data.debits.forEach(function(transaction) {
            //                 if (transaction.amount !== null && transaction.amount !== '') {
            //                     debitTotal += parseFloat(transaction.amount) || 0;

            //                     debitTableBody.append(`
        //                         <tr data-id="${transaction.id}">
        //                             <td class="text-center">
        //                                 <button class="btn btn-warning btn-sm edit-btn" data-id="${transaction.id}"><i class="fas fa-edit"></i></button>
        //                                 <button class="btn btn-danger btn-sm delete-btn" data-id="${transaction.id}"><i class="fas fa-trash-alt"></i></button>
        //                             </td>
        //                             <td class="text-left">${transaction.account ? transaction.account.account_name : ''}</td>
        //                             <td class="text-right">${transaction.amount || ''}</td>
        //                             <td class="text-wrap text-left">${transaction.notes || ''}</td>
        //                             <td class="text-center">${formatDate(transaction.date)}</td>
        //                         </tr>
        //                     `);
            //                 }
            //             });

            //             $('#debit_total').text(debitTotal.toFixed(2));
            //             $('#difference').text((creditTotal - debitTotal).toFixed(2));

            //             // Attach delete handler
            //             $('.delete-btn').on('click', function() {
            //                 let id = $(this).data('id');
            //                 if (confirm('Are you sure you want to delete this transaction?')) {
            //                     deleteTransaction(id);
            //                 }
            //             });
            //         },
            //         error: function(xhr) {
            //             showAlert('An error occurred while loading data.', 'danger');
            //         }
            //     });
            // }

            // $(document).on('click', '.edit-btn', function() {
            //     let id = $(this).closest('tr').data('id'); // Get transaction ID from data-id attribute

            //     $.ajax({
            //         url: "{{ route('amounts.edit', '') }}/" + id, // Adjust URL if necessary
            //         method: 'GET',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             if (!response.error) {
            //                 $('#date').val(response.date);
            //                 $('#amount').val(response.amount);
            //                 $('#notes').val(response.notes);
            //                 $('#method').val(response
            //                     .method); // Assuming method is used to determine type

            //                 // Set select2 values
            //                 $('#account_id').val(response.account_id).trigger(
            //                     'change'); // Update select2
            //                 $('#opp_account_id').val(response.opp_account_id).trigger(
            //                     'change'); // Update select2

            //                 // Show the appropriate button based on the transaction type
            //                 if (response.type === 'credit') {
            //                     $('#credit-btn').show();
            //                     $('#debit-btn').hide();
            //                 } else if (response.type === 'debit') {
            //                     $('#debit-btn').show();
            //                     $('#credit-btn').hide();
            //                 }

            //                 // Store the transaction ID in a hidden input field for updating
            //                 $('#transaction-id').val(response.id);
            //                 // Set the Opp. Account select2 with the new value if necessary
            //                 $('#account_id').append(new Option(response.account_name,
            //                     response.account_id, true, true)).trigger('change');
            //                 $('#opp_account_id').append(new Option(response.opp_account_name,
            //                     response.opp_account_id, true, true)).trigger('change');

            //             } else {
            //                 showAlert('Transaction not found', 'danger');
            //             }
            //         },
            //         error: function() {
            //             showAlert('An error occurred while fetching transaction details.',
            //                 'danger');
            //         }
            //     });
            // });

        });
    </script>
@endpush
