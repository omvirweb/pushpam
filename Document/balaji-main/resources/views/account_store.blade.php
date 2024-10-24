@extends('layouts.app')
@section('main-content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Create Account</h3>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid py-2">
                            <div id="alert-container" class="position-fixed top-0 end-0 p-3"></div>
                        </div>
                        <form id="create-account-form">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="account_name">Account Name:<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <input type="text" name="account_name" id="account_name" class="form-control" />
                                    <div class="text-danger error-account_name"></div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_mobile">Mobile:</label>
                                    <input type="number" class="form-control form-control-sm" id="account_mobile"
                                        name="account_mobile" value="{{ old('account_mobile') }}">
                                    <span class="text-danger error-message" id="error-account_mobile"></span>
                                </div>
                                <!-- Opp. Account Checkbox -->

                                <div class="col-md-4 mb-3">
                                    <label for="opp_account" class="form-label">Opp. Account:</label>
                                    <div class="form-check form-switch">
                                        &emsp;&emsp;&emsp;<input class="form-check-input" type="checkbox" id="opp_account"
                                            name="opp_account">
                                    </div>
                                    <div class="text-danger error-opp_account"></div>
                                </div>
                                {{-- <div class="col-md-4 mb-3">
                                    <label for="account_phone">Phone:</label>
                                    <input type="number" class="form-control form-control-sm" id="account_phone"
                                        name="account_phone" value="{{ old('account_phone') }}">
                                </div> --}}
                                {{-- <div class="col-md-4 mb-3">
                                    <label for="account_email_ids">Email IDs:</label>
                                    <input type="email" class="form-control form-control-sm" id="account_email_ids"
                                        name="account_email_ids" value="{{ old('account_email_ids') }}">
                                    <span class="text-danger error-message" id="error-account_email_ids"></span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_address">Address:</label>
                                    <textarea class="form-control form-control-sm" id="account_address" name="account_address" rows="2"></textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_state">State:</label>
                                    <input type="text" class="form-control form-control-sm" id="account_state"
                                        name="account_state">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_city">City:</label>
                                    <input type="text" class="form-control form-control-sm" id="account_city"
                                        name="account_city">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_postal_code">Postal Code:</label>
                                    <input type="text" class="form-control form-control-sm" id="account_postal_code"
                                        name="account_postal_code">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_gst_no">GST No:</label>
                                    <input type="text" class="form-control form-control-sm" id="account_gst_no"
                                        name="account_gst_no">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_pan">PAN:</label>
                                    <input type="text" class="form-control form-control-sm" id="account_pan"
                                        name="account_pan">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_aadhaar">Aadhaar:</label>
                                    <input type="text" class="form-control form-control-sm" id="account_aadhaar"
                                        name="account_aadhaar">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_contect_person_name">Contact Person Name:</label>
                                    <input type="text" class="form-control form-control-sm"
                                        id="account_contect_person_name" name="account_contect_person_name">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_group_id">Group ID:</label>
                                    <input type="text" class="form-control form-control-sm" id="account_group_id"
                                        name="account_group_id">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account_remarks">Remarks:</label>
                                    <textarea class="form-control form-control-sm" id="account_remarks" name="account_remarks" rows="2"></textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="opening_balance">Opening Balance:</label>
                                    <input type="text" class="form-control form-control-sm" id="opening_balance"
                                        name="opening_balance">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="interest">Interest:</label>
                                    <input type="text" class="form-control form-control-sm" id="interest"
                                        name="interest">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="credit_debit">Credit/Debit:</label>
                                    <input type="text" class="form-control form-control-sm" id="credit_debit"
                                        name="credit_debit">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="opening_balance_in_gold">Opening Balance in Gold:</label>
                                    <input type="text" class="form-control form-control-sm"
                                        id="opening_balance_in_gold" name="opening_balance_in_gold">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="gold_ob_credit_debit">Gold OB Credit/Debit:</label>
                                    <input type="text" class="form-control form-control-sm" id="gold_ob_credit_debit"
                                        name="gold_ob_credit_debit">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="opening_balance_in_silver">Opening Balance in Silver:</label>
                                    <input type="text" class="form-control form-control-sm"
                                        id="opening_balance_in_silver" name="opening_balance_in_silver">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="silver_ob_credit_debit">Silver OB Credit/Debit:</label>
                                    <input type="text" class="form-control form-control-sm"
                                        id="silver_ob_credit_debit" name="silver_ob_credit_debit">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="opening_balance_in_rupees">Opening Balance in Rupees:</label>
                                    <input type="text" class="form-control form-control-sm"
                                        id="opening_balance_in_rupees" name="opening_balance_in_rupees">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="rupees_ob_credit_debit">Rupees OB Credit/Debit:</label>
                                    <input type="text" class="form-control form-control-sm"
                                        id="rupees_ob_credit_debit" name="rupees_ob_credit_debit">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="opening_balance_in_c_amount">Opening Balance in C Amount:</label>
                                    <input type="text" class="form-control form-control-sm"
                                        id="opening_balance_in_c_amount" name="opening_balance_in_c_amount">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="c_amount_ob_credit_debit">C Amount OB Credit/Debit:</label>
                                    <input type="text" class="form-control form-control-sm"
                                        id="c_amount_ob_credit_debit" name="c_amount_ob_credit_debit">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="opening_balance_in_r_amount">Opening Balance in R Amount:</label>
                                    <input type="text" class="form-control form-control-sm"
                                        id="opening_balance_in_r_amount" name="opening_balance_in_r_amount">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="r_amount_ob_credit_debit">R Amount OB Credit/Debit:</label>
                                    <input type="text" class="form-control form-control-sm"
                                        id="r_amount_ob_credit_debit" name="r_amount_ob_credit_debit">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="bank_name">Bank Name:</label>
                                    <input type="text" class="form-control form-control-sm" id="bank_name"
                                        name="bank_name">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="bank_account_no">Bank Account No:</label>
                                    <input type="text" class="form-control form-control-sm" id="bank_account_no"
                                        name="bank_account_no">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ifsc_code">IFSC Code:</label>
                                    <input type="text" class="form-control form-control-sm" id="ifsc_code"
                                        name="ifsc_code">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="bank_interest">Bank Interest:</label>
                                    <input type="text" class="form-control form-control-sm" id="bank_interest"
                                        name="bank_interest">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="gold_fine">Gold Fine:</label>
                                    <input type="text" class="form-control form-control-sm" id="gold_fine"
                                        name="gold_fine">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="silver_fine">Silver Fine:</label>
                                    <input type="text" class="form-control form-control-sm" id="silver_fine"
                                        name="silver_fine">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="amount">Amount:</label>
                                    <input type="text" class="form-control form-control-sm" id="amount"
                                        name="amount">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="c_amount">C Amount:</label>
                                    <input type="text" class="form-control form-control-sm" id="c_amount"
                                        name="c_amount">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="r_amount">R Amount:</label>
                                    <input type="text" class="form-control form-control-sm" id="r_amount"
                                        name="r_amount">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="credit_limit">Credit Limit:</label>
                                    <input type="text" class="form-control form-control-sm" id="credit_limit"
                                        name="credit_limit">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="balance_date">Balance Date:</label>
                                    <input type="date" class="form-control form-control-sm" id="balance_date"
                                        name="balance_date" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="status">Status:</label>
                                    <input type="text" class="form-control form-control-sm" id="status"
                                        name="status">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="user_id">User ID:</label>
                                    <input type="text" class="form-control form-control-sm" id="user_id"
                                        name="user_id">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="user_name">User Name:</label>
                                    <input type="text" class="form-control form-control-sm" id="user_name"
                                        name="user_name">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="is_supplier">Is Supplier:</label>
                                    <input type="text" class="form-control form-control-sm" id="is_supplier"
                                        name="is_supplier">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control form-control-sm" id="password"
                                        name="password">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="min_price">Min Price:</label>
                                    <input type="text" class="form-control form-control-sm" id="min_price"
                                        name="min_price">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="chhijjat_per_100_ad">Chhijjat per 100 AD:</label>
                                    <input type="text" class="form-control form-control-sm" id="chhijjat_per_100_ad"
                                        name="chhijjat_per_100_ad">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="meena_charges">Meena Charges:</label>
                                    <input type="text" class="form-control form-control-sm" id="meena_charges"
                                        name="meena_charges">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="price_per_pcs">Price per Pcs:</label>
                                    <input type="text" class="form-control form-control-sm" id="price_per_pcs"
                                        name="price_per_pcs">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="is_active">Is Active:</label>
                                    <input type="text" class="form-control form-control-sm" id="is_active"
                                        name="is_active">
                                </div> --}}
                            </div>
                            <button type="button" id="submit-btn" class="btn btn-success">Create Account</button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card mb-4">
                            <div class="card-header p-4">
                                <h3>Accounts</h3>
                            </div>
                            <div class="card-body p-0" style="box-shadow: 0px 5px 10px lightblue;">
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0 table-bordered table-hover"
                                        id="credit-table">
                                        <thead class="table-active">
                                            <tr>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                    Action
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                    Name
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                    Mobile No.
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                    Opp. Account:
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                    Date
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
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#submit-btn').on('click', function() {
                const isUpdate = $(this).data('id') !== undefined;
                if (isUpdate) {
                    updateAccount($(this).data('id'));
                } else {
                    submitForm();
                }
            });

            function formatDate(dateString) {
                let date = new Date(dateString);
                let day = ('0' + date.getDate()).slice(-2);
                let month = ('0' + (date.getMonth() + 1)).slice(-2); // Months are zero-based
                let year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }

            let accountTable = $("#credit-table").DataTable({
                paging: false,
                searching: false,
                ordering: true,
                info: false,
                order: [
                    [1, "asc"]
                ], // Set default sorting on the second column (index 1) in ascending order
                columnDefs: [{
                        orderable: false,
                        targets: 0,
                    }, // Disable sorting on the first column (buttons)
                    {
                        orderable: true,
                        targets: [1, 2, 3, 4],
                    }, // Enable sorting on other columns
                ],
                language: {
                    emptyTable: "No data available", // This message will show when there is no data
                },
                createdRow: function(row, data, dataIndex) {
                    // Apply the 'text-right' class to the "Amount" cell (index 3)
                    $("td:eq(0)", row).addClass("text-center text-wrap");
                    $("td:eq(1)", row).addClass("text-left text-wrap");
                    $("td:eq(2)", row).addClass("text-right text-wrap");
                    $("td:eq(3)", row).addClass("text-center text-wrap");
                    $("td:eq(4)", row).addClass("text-center text-wrap");
                },
            });

            function fetchAccounts() {
                $.ajax({
                    url: "{{ route('accounts.data') }}",
                    method: "GET",
                    success: function(response) {
                        // Clear existing data
                        accountTable.clear();
                        if (response.length > 0) {
                            response.forEach(function(account) {
                                let formattedDate = formatDate(account.created_at);
                                let oppAccount = account.opp_account ? "Yes" : "No";

                                accountTable.row
                                    .add([
                                        `<td class="text-center">
                                            <button class="btn btn-warning btn-sm edit-btn" data-id="${account.account_id}"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-danger btn-sm delete-btn" data-id="${account.account_id}"><i class="fas fa-trash-alt"></i></button>
                                        </td>`,
                                        `<td class="text-left">${account.account_name}</td>`,
                                        `<td class="text-right">${account.account_mobile || ""}</td>`,
                                        `<td class="text-center">${oppAccount}</td>`,
                                        `<td class="text-center">${formattedDate}</td>`,
                                    ])
                                    .draw();
                            });
                            // Reattach event listeners after adding rows
                            $('.delete-btn').on('click', function() {
                                // let row = $(this).closest('tr');
                                // let id = row.data('id');
                                let id = $(this).data("id");
                                deleteAccount(id);
                            });

                            $('.edit-btn').on('click', function() {
                                let id = $(this).data("id");
                                editAccount(id);
                            });

                        } else {
                            // If no data found, display a message
                            // accountTable.clear().draw();
                            accountTable.draw();
                            // showAlert(response.message, "warning");
                        }
                    },
                    error: function(xhr) {
                        showAlert("An error occurred while loading data.", "danger");
                    },
                });
            }


            function deleteAccount(id) {
                if (confirm('Are you sure you want to delete this Account?')) {
                    $.ajax({
                        url: "{{ route('account.destroy', '') }}/" + id,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                showAlert('Account deleted successfully!', 'success');
                                // let table = $('#credit-table').DataTable();
                                // table.row(row).remove().draw(); // Remove the row from DataTable
                                fetchAccounts();

                            } else {
                                showAlert('An error occurred while deleting the account.', 'danger');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred while deleting the account.', 'danger');
                        }
                    });
                }
            }

            function editAccount(id) {
                $.ajax({
                    url: "{{ route('accounts.edit', '') }}/" + id,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (!response.error) {
                            $('#account_name').val(response.account_name);
                            $('#account_mobile').val(response.account_mobile);
                            $('#opp_account').prop('checked', response.opp_account);

                            $('#submit-btn').text('Update Account').data('id', id); // Set ID for update
                        } else {
                            showAlert('Account not found', 'danger');
                        }
                    },
                    error: function() {
                        showAlert('An error occurred while fetching account details.', 'danger');
                    }
                });
            }

            fetchAccounts();

            $('#account_name').on('input', function() {
                clearErrorMessages(); // Clear error messages on field change
            });

            function updateAccount(id) {
                const formData = {
                    account_name: $('#account_name').val(),
                    account_mobile: $('#account_mobile').val(),
                    opp_account: $('#opp_account').is(':checked') ? 1 : 0,
                };

                $.ajax({
                    url: "{{ route('accounts.update', '') }}/" + id,
                    method: 'PUT',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('Account updated successfully!', 'success');
                            $('#create-account-form')[0].reset();
                            $('#submit-btn').text('Create Account').removeData('id');
                            fetchAccounts(); // Refresh the accounts table
                        } else {
                            showAlert(`Unexpected response: ${response.message}`, 'danger');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
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

            function submitForm() {
                const formData = {
                    account_name: $('#account_name').val(),
                    account_mobile: $('#account_mobile').val(),
                    opp_account: $('#opp_account').is(':checked') ? 1 : 0,
                };

                $.ajax({
                    url: "{{ route('account.store') }}",
                    method: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('Data submitted successfully!', 'success');
                            $('#create-account-form')[0].reset();
                            clearErrorMessages();
                            fetchAccounts(); // Refresh the accounts table
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
                $('.text-danger').text('');
            }
        });


                    // function initializeDataTable() {
            //     // Check if DataTable is already initialized
            //     if ($.fn.DataTable.isDataTable('#credit-table')) {
            //         // Destroy existing DataTable instance
            //         $('#credit-table').DataTable().clear().destroy();
            //     }

            //     // Initialize DataTable
            //     $('#credit-table').DataTable({
            //         "paging": false,
            //         "searching": false,
            //         "ordering": true,
            //         "info": false,
            //         "order": [
            //             [1, 'asc']
            //         ], // Set default sorting on the second column (index 1) in ascending order
            //         "columnDefs": [{
            //                 "orderable": false,
            //                 "targets": 0
            //             }, // Disable sorting on the first column (buttons)
            //             {
            //                 "orderable": true,
            //                 "targets": [1, 2, 3, 4]
            //             } // Enable sorting on other columns
            //         ],
            //         "language": {
            //             "emptyTable": "No data available" // This message will show when there is no data
            //         }
            //     });
            // }

            // initializeDataTable();

            // function fetchAccounts() {
            //     $.ajax({
            //         url: "{{ route('accounts.data') }}",
            //         method: "GET",
            //         success: function(response) {
            //             // Ensure DataTable is initialized
            //             let table = $('#credit-table').DataTable();
            //             table.clear().draw(); // Clear existing data

            //             if (response.length > 0) {
            //                 response.forEach(account => {
            //                     let formattedDate = formatDate(account.created_at);
            //                     let oppAccount = account.opp_account ? 'Yes' : 'No';

            //                     // Append the row with necessary buttons and attributes
            //                     let newRow = $(`
        //                         <tr data-id="${account.account_id}">
        //                             <td class="text-center">
        //                                 <button class="btn btn-warning btn-sm edit-btn"><i class="fas fa-edit"></i></button>
        //                                 <button class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash-alt"></i></button>
        //                             </td>
        //                             <td class="text-left">${account.account_name}</td>
        //                             <td class="text-right">${account.account_mobile || ''}</td>
        //                             <td class="text-center">${oppAccount}</td>
        //                             <td class="text-center">${formattedDate}</td>
        //                         </tr>
        //                     `);

            //                     // Add the new row to the DataTable
            //                     table.row.add(newRow).draw();
            //                 });

            //                 // Reattach event listeners after adding rows
            //                 $('.delete-btn').on('click', function() {
            //                     let row = $(this).closest('tr');
            //                     let id = row.data('id');
            //                     deleteAccount(id, row);
            //                 });

            //                 $('.edit-btn').on('click', function() {
            //                     let row = $(this).closest('tr');
            //                     let id = row.data('id');
            //                     editAccount(id);
            //                 });

            //             } else {
            //                 // If no data, add a single row indicating no data
            //                 // table.row.add(`
        //             // <tr>
        //             //     <td colspan="5" class="text-center">No data available</td>
        //             // </tr>
        //             // `).draw();
            //                 table.draw();
            //             }
            //         },
            //         error: function() {
            //             showAlert('An error occurred while fetching data.', 'danger');
            //         }
            //     });
            // }

    </script>
@endpush
