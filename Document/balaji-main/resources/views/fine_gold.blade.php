@extends('layouts.app')
@section('main-content')
    <div class="container-fluid py-2">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col">
                <h2 class="fw-500">Fine</h2>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form id="fine-form">
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
                            <label for="amount" class="form-label">Fine<span
                                    class="text-danger required-asterisk">*</span></label>
                            <input type="number" name="fine" id="fine" class="form-control" min="0"
                                placeholder="Enter Fine" />
                            <div class="text-danger error-fine"></div>
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
                    <input type="text" class="form-control" name="method" id="method" value="3" hidden />
                    <input type="hidden" id="transaction-id" value="">
                </form>
            </div>
            <div class="row">
                <div class="modal-footer col-md-2 d-flex justify-content-center">
                    <button type="button" id="credit-btn" class="btn btn-primary" value="credit">Credit</button>
                    <button type="button" id="debit-btn" class="btn btn-danger" value="debit">Debit</button>
                </div>
                <div class="modal-footer col-md-10 flex justify-content-between flex-wrap">

                    {{-- <div class="modal-footer col-md-8 ">
                <div class="row"> --}}
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

                    {{-- </div>
            </div> --}}
                </div>
            </div>
        </div>
        <!-- Date Range Picker, Account Name Dropdown, and Search Button -->
        <div class="row mb-4">
            <div class="col-4">
                <div class="input-group">
                    <input type="text" id="date-range" class="form-control" placeholder="Select date range">
                </div>
            </div>
            <div class="col-4">
                <div class="input-group">
                    <select id="account-name" class="form-control">
                        <option value="">Select Account</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->account_name }}">{{ $account->account_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-4">
                <button class="btn btn-primary" id="search-button">Search</button>
            </div>
        </div>
        <div class="row">
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
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Fine
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
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Fine
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
                    $('td:eq(4)', row).addClass('text-right text-wrap');
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
                    $('td:eq(4)', row).addClass('text-right text-wrap');
                }
            });

            loadAmounts();
            // Load amounts on search button click
            $('#search-button').on('click', function() {
                loadAmounts();
            });

            // Load amounts when the date range or account name changes
            $('#date-range, #account-name').on('change', function() {
                loadAmounts();
            });

            function formatDate(dateStr) {
                var date = new Date(dateStr);
                var day = ("0" + date.getDate()).slice(-2);
                var month = ("0" + (date.getMonth() + 1)).slice(-2);
                var year = date.getFullYear();
                return `${day}/${month}/${year}`; // Display date in dd/mm/yyyy format
            }

            // function loadAmounts() {
            //     var dateRange = $('#date-range').val();
            //     var dates = dateRange.split(' - ');
            //     // Convert dd-mm-yyyy to yyyy-mm-dd for the AJAX request
            //     var startDate = dates[0].split('-').reverse().join('-');
            //     var endDate = dates[1].split('-').reverse().join('-');
            //     var accountName = $('#account-name').val();

            //     $.ajax({
            //         url: "{{ route('fine.data') }}",
            //         method: "GET",
            //         data: {
            //             start_date: startDate,
            //             end_date: endDate,
            //             account_name: accountName,
            //             _token: '{{ csrf_token() }}' // Include CSRF token
            //         },
            //         success: function(data) {
            //             let creditTableBody = $('#credit-table tbody');
            //             let debitTableBody = $('#debit-table tbody');
            //             creditTableBody.empty(); // Clear existing data
            //             debitTableBody.empty(); // Clear existing data

            //             let creditTotal = 0;
            //             let debitTotal = 0;

            //             if (data.credits.length > 0) {
            //                 data.credits.forEach(transaction => {
            //                     creditTableBody.append(`
        //                         <tr data-id="${transaction.id}">
        //                             <td class="text-center">
        //                                 <button class="btn btn-warning btn-sm edit-btn"data-id="${transaction.id}"><i class="fas fa-edit"></i></button>
        //                                 <button class="btn btn-danger btn-sm delete-btn" data-id="${transaction.id}"><i class="fas fa-trash-alt"></i></button>
        //                             </td>
        //                             <td class="text-left">${transaction.account ? transaction.account.account_name : ''}</td>
        //                             <td class="text-right">${transaction.fine || ''}</td>
        //                             <td class="text-left">${transaction.notes || ''}</td>
        //                             <td class="text-center">${formatDate(transaction.date)}</td>
        //                         </tr>
        //                     `);
            //                     creditTotal += parseFloat(transaction.fine);
            //                 });
            //             } else {
            //                 creditTableBody.append(`
        //                     <tr>
        //                         <td colspan="5" class="text-center">No credit data available</td>
        //                     </tr>
        //                 `);
            //             }

            //             if (data.debits.length > 0) {
            //                 data.debits.forEach(transaction => {
            //                     debitTableBody.append(`
        //                         <tr data-id="${transaction.id}">
        //                             <td class="text-center">
        //                                 <button class="btn btn-warning btn-sm edit-btn" data-id="${transaction.id}"><i class="fas fa-edit"></i></button>
        //                                 <button class="btn btn-danger btn-sm delete-btn" data-id="${transaction.id}"><i class="fas fa-trash-alt"></i></button>
        //                             </td>
        //                             <td class="text-left">${transaction.account ? transaction.account.account_name : ''}</td>
        //                             <td class="text-right">${transaction.fine || ''}</td>
        //                             <td class="text-left">${transaction.notes || ''}</td>
        //                             <td class="text-center">${formatDate(transaction.date)}</td>
        //                         </tr>
        //                     `);
            //                     debitTotal += parseFloat(transaction.fine);
            //                 });
            //             } else {
            //                 debitTableBody.append(`
        //                     <tr>
        //                         <td colspan="5" class="text-center">No debit data available</td>
        //                     </tr>
        //                 `);
            //             }

            //             // Update the totals in the UI
            //             $('#credit_total').text(creditTotal.toFixed(3));
            //             $('#debit_total').text(debitTotal.toFixed(3));
            //             $('#difference').text((creditTotal - debitTotal).toFixed(3));

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

            function loadAmounts() {
                var dateRange = $('#date-range').val();
                var dates = dateRange.split(' - ');
                // Convert dd-mm-yyyy to yyyy-mm-dd for the AJAX request
                var startDate = dates[0].split('-').reverse().join('-');
                var endDate = dates[1].split('-').reverse().join('-');
                var accountName = $('#account-name').val();

                $.ajax({
                    url: "{{ route('fine.data') }}",
                    method: "GET",
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        account_name: accountName,
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(data) {
                        // Clear existing data
                        creditTable.clear().draw();
                        debitTable.clear().draw();

                        let creditTotal = 0;
                        let debitTotal = 0;

                        // if (data.credits.length > 0) {
                        data.credits.forEach(transaction => {
                            creditTable.row.add([
                                `<td class="text-center">
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="${transaction.id}"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="${transaction.id}"><i class="fas fa-trash-alt"></i></button>
                                    </td>`,
                                `<td class="text-left">${transaction.account ? transaction.account.account_name : ''}</td>`,
                                `<td class="text-right">${transaction.fine || ''}</td>`,
                                `<td class="text-left">${transaction.notes || ''}</td>`,
                                `<td class="text-center">${formatDate(transaction.date)}</td>`,

                            ]).draw();
                            creditTotal += parseFloat(transaction.fine);
                        });
                        // } else {
                        //     creditTable.append(`
                    //         <tr>
                    //             <td colspan="5" class="text-center">No credit data available</td>
                    //         </tr>
                    //     `);
                        // }

                        // if (data.debits.length > 0) {
                        data.debits.forEach(transaction => {
                            debitTable.row.add([
                                `<td class="text-center">
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="${transaction.id}"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="${transaction.id}"><i class="fas fa-trash-alt"></i></button>
                                    </td>`,
                                `<td class="text-left">${transaction.account ? transaction.account.account_name : ''}</td>`,
                                `<td class="text-right">${transaction.fine || ''}</td>`,
                                `<td class="text-left">${transaction.notes || ''}</td>`,
                                `<td class="text-center">${formatDate(transaction.date)}</td>`,

                            ]).draw();
                            debitTotal += parseFloat(transaction.fine);
                        });
                        // } else {
                        //     debitTable.append(`
                    //         <tr>
                    //             <td colspan="5" class="text-center">No debit data available</td>
                    //         </tr>
                    //     `);
                        // }

                        // Update the totals in the UI
                        $('#credit_total').text(creditTotal.toFixed(3));
                        $('#debit_total').text(debitTotal.toFixed(3));
                        $('#difference').text((creditTotal - debitTotal).toFixed(3));

                        // Attach delete handler
                        $('.delete-btn').on('click', function() {
                            let id = $(this).data('id');
                            if (confirm('Are you sure you want to delete this transaction?')) {
                                deleteFine(id);
                            }
                        });

                        $('.edit-btn').on('click', function() {
                            let id = $(this).data('id');
                            editFine(id);
                        });
                    },
                    error: function(xhr) {
                        showAlert('An error occurred while loading data.', 'danger');
                    }
                });
            }

            function deleteFine(id) {
                $.ajax({
                    url: "{{ route('fine.destroy', '') }}/" + id,
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

            function editFine(id) {
                $.ajax({
                    url: "{{ route('fine.edit', '') }}/" + id, // Adjust URL if necessary
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (!response.error) {
                            $('#date').val(response.date);
                            $('#fine').val(response.fine); // Ensure this value is correctly set
                            $('#notes').val(response.notes);
                            $('#method').val(response.method);

                            // Set select2 values
                            $('#account_id').val(response.account_id).trigger(
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

            // $(document).on('click', '.edit-btn', function() {
            //     let id = $(this).closest('tr').data('id'); // Get transaction ID from data-id attribute

            //     $.ajax({
            //         url: "{{ route('fine.edit', '') }}/" + id, // Adjust URL if necessary
            //         method: 'GET',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             if (!response.error) {
            //                 $('#date').val(response.date);
            //                 $('#fine').val(response.fine); // Ensure this value is correctly set
            //                 $('#notes').val(response.notes);
            //                 $('#method').val(response.method);

            //                 // Set select2 values
            //                 $('#account_id').val(response.account_id).trigger(
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


            $('#credit-btn').on('click', function() {
                submitForm('credit');
            });

            $('#debit-btn').on('click', function() {
                submitForm('debit');
            });

            function submitForm(type) {
                // Gather form data
                const formData = {
                    date: $('input[name="date"]').val(),
                    account_id: $('#account_id').val(),
                    fine: $('input[name="fine"]').val(),
                    notes: $('input[name="notes"]').val(),
                    type: type,
                    method: 3,
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                };

                // Determine which button was clicked and disable it
                const button = type === 'credit' ? $('#credit-btn') : $('#debit-btn');
                button.prop('disabled', true);

                // Get the transaction ID for updating
                const transactionId = $('#transaction-id').val();

                // Determine URL and HTTP method based on whether it's an update or create
                const ajaxOptions = {
                    url: transactionId ? `{{ route('fine.update', '') }}/${transactionId}` :
                        "{{ route('fine.store') }}",
                    method: transactionId ? "PUT" : "POST", // Use PUT for update, POST for create
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showAlert(`${type.charAt(0).toUpperCase() + type.slice(1)} Data successful!`,
                                'success');
                            loadAmounts(); // Reload amounts to reflect changes
                            $('#fine-form')[0].reset(); // Reset the form
                            $('#account_id').val(null).trigger('change'); // Reset select2
                            $('#transaction-id').val(''); // Clear transaction ID
                            $('#credit-btn').show();
                            $('#debit-btn').show();
                        } else {
                            showAlert('Failed to process transaction', 'danger');
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        showErrorMessages(errors); // Display validation errors
                    },
                    complete: function() {
                        button.prop('disabled', false); // Re-enable the button
                    }
                };

                // Send AJAX request
                $.ajax(ajaxOptions);
            }

            function showAlert(message, type) {
                $('#alert-container').empty().html(`
                    <div class="alert alert-${type} alert-dismissible fade show alert-custom" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `).show().delay(2000).fadeOut(400, function() {
                    $(this).empty();
                });
            }
            function clearErrorMessages() {
                // $('.text-danger').text('');
                $('.text-danger').not('.required-asterisk').text('');
            }
        });
    </script>
@endpush
