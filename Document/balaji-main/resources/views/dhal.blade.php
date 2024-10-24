@extends('layouts.app')
@section('main-content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form id="transaction-form">
                        <div class="card-header">
                            <h3>Dhal</h3>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid py-2">
                                <div id="alert-container" class="position-fixed top-0 end-0 p-3" style="">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <label for="date">Date<span class="text-danger required-asterisk">*</span></label>
                                    <input type="date" class="form-control" id="date" name="date"
                                        value="{{ date('Y-m-d') }}">
                                    <div class="text-danger error-date"></div>
                                </div>
                                {{-- <div class="col-md-5">
                                    <label for="item_name" class="form-label">Item<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <select id="item_name" name="item_name" class="form-control">
                                        <!-- Options will be loaded dynamically -->
                                    </select>
                                    <div class="text-danger error-item_name"></div>
                                </div> --}}
                                <input type="text" class="form-control" name="item_name" id="item_name" value="1"
                                hidden />
                                <div class="col-md-5">
                                    <label for="dhal" class="form-label">Dhal<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <input type="number" class="form-control" id="dhal" name="dhal" min="0"
                                        placeholder="Enter Dhal">
                                    <div class="text-danger error-dhal"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <label for="touch" class="form-label">Touch<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <input type="number" class="form-control" id="touch" name="touch" min="0"
                                        placeholder="Enter Touch">
                                    <div class="text-danger error-touch"></div>
                                </div>
                                <div class="col-md-5">
                                    <label for="fine" class="form-label">Fine</label>
                                    <input type="number" class="form-control" name="fine" id="fine"
                                        placeholder="Enter Fine" readonly>
                                    <div class="text-danger error-fine"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <label class="form-label">Notes</label>
                                    <input type="text" name="notes" name="notes" class="form-control"
                                        placeholder="Enter Notes">
                                    <div class="text-danger error-notes"></div>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="method" id="method" value="4"
                                hidden />
                            <input type="hidden" id="transaction-id" value="">
                        </div>
                        <div class="modal-footer md-8 d-flex justify-content-center">
                            <button type="button" id="credit-btn" class="btn btn-success">Submit</button>
                            {{-- Credit --}}
                            {{-- <button type="button" id="debit-btn" class="btn btn-primary">Debit</button> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row align-items-center justify-content-between mb-4">
            {{-- <div class="col">
                <h2 class="fw-500">Dhal</h2>
            </div> --}}
        </div>
        <!-- Date Range Picker, Account Name Dropdown, and Search Button -->
        {{-- <div class="row mb-4">
            <div class="col-4">
                <div class="input-group">
                    <input type="text" id="date-range" class="form-control" placeholder="Select date range">
                </div>
            </div>
            {{-- <div class="col-4">
                <div class="input-group">
                    <select id="account-name" class="form-control">
                        <option value="">Select Account</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->account_name }}">{{ $account->account_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}
        {{-- <div class="col-4">
                <button class="btn btn-primary" id="search-button">Search</button>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    {{-- <div class="card-header p-4">
                        <h3>Credit</h3>
                    </div> --}}
                    <div class="card-body p-0" style="box-shadow: 0px 5px 10px lightblue;">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 table-bordered table-hover" id="credit-table">
                                <thead class="table-active">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Action
                                        </th>
                                        {{-- <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Name --}}
                                        </th>
                                        {{-- <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Item
                                        </th> --}}
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                            Delivered</th>

                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Dhal
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Touch
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
                        <div>
                            <table class="table align-items-center mb-0 table-bordered table-hover">
                                <tr>
                                    <td>Total Kachu</td>
                                    {{-- <td></td> --}}
                                    <td></td>
                                    <td class="text-right"><span id="total-dhal">0</span></td>
                                    {{-- Total Dhal: --}}
                                    <td class="text-right"><span id="total-touch">0</span></td>
                                    {{-- Total Touch:  --}}
                                    <td class="text-right"><span id="total-fine">0</span></td>
                                    {{-- Total Fine: --}}
                                    <td></td>
                                    <td></td>

                                    {{-- <td>Touch Percentage: <span id="touch-percentage" class="text-wrap">0%</span></td> --}}
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-6">
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
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Date
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">name
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Dhal
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Touch
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Fine
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Notes
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
            </div> --}}
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            // $('#item_name').select2({
            //     placeholder: 'Select an Item',
            //     ajax: {
            //         url: '{{ route('dhal.index') }}',
            //         dataType: 'json',
            //         delay: 250,
            //         data: function(params) {
            //             return {
            //                 search: params.term
            //             };
            //         },
            //         processResults: function(data) {
            //             return {
            //                 results: data.map(function(item) {
            //                     return {
            //                         id: item.id,
            //                         text: item.text
            //                     };
            //                 })
            //             };
            //         },
            //         cache: true
            //     },
            //     tags: true,
            //     createTag: function(params) {
            //         var term = $.trim(params.term);
            //         if (term === '') {
            //             return null;
            //         }
            //         return {
            //             id: term,
            //             text: term,
            //             newTag: true
            //         };
            //     }
            // }).on('select2:select', function(e) {
            //     var data = e.params.data;
            //     if (data.newTag) {
            //         saveNewTag(data.text, '#item_name');
            //     }
            //     clearErrorMessages();
            // });

            // function saveNewTag(text, selectElement) {
            //     $.ajax({
            //         type: 'POST',
            //         url: '{{ route('item.itemStore') }}',
            //         data: {
            //             item_name: text,
            //             _token: '{{ csrf_token() }}'
            //         },
            //         success: function(response) {
            //             var newOption = new Option(response.text, response.id, false, true);
            //             $(selectElement).append(newOption).trigger('change');
            //         },
            //         error: function(xhr) {
            //             console.log('Error saving new tag:', xhr);
            //         }
            //     });
            // }

            // // Function to open Select2 and perform click on the search input
            // function openSelect2AndClick() {
            //     // Open Select2 dropdown
            //     $('#item_name').select2('open');

            //     // Delay to ensure dropdown is open and rendered
            //     setTimeout(function() {
            //         // Find the search input field within the dropdown
            //         var $searchField = $('#item_name').data('select2').$dropdown.find(
            //             '.select2-search__field');
            //         if ($searchField.length) {
            //             // Use JavaScript to set focus and select the text inside the input
            //             $searchField.focus();
            //             $searchField[0].select(); // Optional: Select text in the input
            //         }
            //     }, 300); // Adjust timeout if needed
            // }

            // // Call the function to open and click on Select2 on page load
            // openSelect2AndClick();

            // Initialize Date Range Picker with dd-mm-yyyy format
            // $('#date-range').daterangepicker({
            //     locale: {
            //         format: 'DD-MM-YYYY'
            //     }
            // });

            loadDhal();

            $('#search-button').on('click', function() {
                loadDhal();
            });

            function formatDate(dateStr) {
                var date = new Date(dateStr);
                var day = ("0" + date.getDate()).slice(-2);
                var month = ("0" + (date.getMonth() + 1)).slice(-2);
                var year = date.getFullYear();
                return `${day}/${month}/${year}`; // Display date in dd/mm/yyyy format
            }

            function changestatus(status, id) {
                // console.log("Changing status for ID:", id, "to:", status);

                $.ajax({
                    url: "{{ route('dhal.status') }}",
                    method: "POST",
                    data: {
                        status: status,
                        id: id,
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(data) {
                        loadDhal();
                    },
                    error: function(xhr) {
                        showAlert("An error occurred while changing status.", "danger");
                    }
                });
            }

            let dhalTable = $('#credit-table').DataTable({
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
                        "targets": [1, 2, 3, 4, 5, 6] // Enable sorting on other columns
                    }
                ],
                "language": {
                    "emptyTable": "No data available" // Message for empty data
                },
                "createdRow": function(row, data, dataIndex) {
                    // Apply the 'text-right' class to the "Amount" cell (index 3)
                    // $('td:eq(1)', row).addClass('text-left text-wrap');
                    $('td:eq(1)', row).addClass('text-center');
                    $('td:eq(2)', row).addClass('text-right text-wrap');
                    $('td:eq(3)', row).addClass('text-right text-wrap');
                    $('td:eq(4)', row).addClass('text-right text-wrap'); // Ensure 'Notes' column aligns left
                    $('td:eq(5)', row).addClass('text-left text-wrap'); // Ensure 'Notes' column aligns left
                    $('td:eq(6)', row).addClass('text-right text-wrap'); // Ensure 'Notes' column aligns left
                }
            });

            function loadDhal() {
                // var dateRange = $('#date-range').val();
                // var dates = dateRange.split(' - ');
                // // Convert dd-mm-yyyy to yyyy-mm-dd for the AJAX request
                // var startDate = dates[0].split('-').reverse().join('-');
                // var endDate = dates[1].split('-').reverse().join('-');

                // Initialize totals
                let totalDhal = 0;
                let totalTouch = 0;
                let totalFine = 0;

                $.ajax({
                    url: "{{ route('dhal.data') }}",
                    method: "GET",
                    data: {
                        // start_date: startDate,
                        // end_date: endDate,
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(data) {
                        // // Clear existing data
                        // let creditTableBody = $('#credit-table tbody');
                        // creditTableBody.empty();
                        dhalTable.clear();

                        // Process data for the table
                        data.forEach(function(transaction) {
                            if (transaction.dhal !== null && transaction.dhal !== '') {
                                // Accumulate totals
                                totalDhal += parseFloat(transaction.dhal) || 0;
                                totalTouch += parseFloat(transaction.touch) || 0;
                                totalFine += parseFloat(transaction.fine) || 0;

                                dhalTable.row.add([


                                    `<td class="text-center">
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="${transaction.id}"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="${transaction.id}"><i class="fas fa-trash-alt"></i></button>
                                    </td>`,
                                    // `<td class="text-wrap">${transaction.item ? transaction.item.item_name : ''}</td>`,
                                    `<td class="text-center">
                                        <input type="checkbox" class="status-checkbox" data-id="${transaction.id}" ${transaction.is_delivered == 1 ? 'checked' : ''} />
                                    </td>`,
                                    `<td class="text-right">${transaction.dhal || ''}</td>`,
                                    `<td class="text-right">${transaction.touch || ''}</td>`,
                                    `<td class="text-right">${transaction.fine || ''}</td>`,
                                    `<td class="text-wrap text-left">${transaction.notes || ''}</td>`,
                                    `<td class="text-center">${formatDate(transaction.date)}</td>`
                                ])
                                .draw();
                            }
                        });

                        // Update totals and percentage
                        $('#total-dhal').text(totalDhal.toFixed(3));
                        $('#total-fine').text(totalFine.toFixed(3));

                        let touchPercentage = totalDhal > 0 ? ((totalFine / totalDhal) * 100)
                            .toFixed(2) : 0;
                        $('#total-touch').text(`${touchPercentage}%`);

                        // Attach delete handler
                        $('.delete-btn').on('click', function() {
                            let id = $(this).data('id');
                            if (confirm('Are you sure you want to delete this record?')) {
                                deleteDhal(id);
                            }
                        });

                        $('.edit-btn').on('click', function() {
                            let id = $(this).data('id');
                            editDhal(id);
                        });

                        $(".status-checkbox").on("change", function() {
                            let id = $(this).data("id");
                            let status = $(this).is(":checked") ? 1 : 0;
                            changestatus(status, id);
                        });
                    },
                    error: function(xhr) {
                        showAlert('An error occurred while loading data.', 'danger');
                    }
                });
            }
            function deleteDhal(id) {
                $.ajax({
                    url: "{{ route('dhal.destroy', '') }}/" + id,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('Record deleted successfully!', 'success');
                            loadDhal(); // Reload the data to reflect the deletion
                        } else {
                            showAlert('An error occurred while deleting the record.', 'danger');
                        }
                    },
                    error: function() {
                        showAlert('An error occurred while deleting the record.', 'danger');
                    }
                });
            }

            function editDhal(id){
                $.ajax({
                    url: "{{ route('dhal.edit', '') }}/" + id, // Adjust URL if necessary
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Populate the form with the data from the response
                            $('input[name="date"]').val(response.data.date);
                            $('input[name="dhal"]').val(response.data.dhal);
                            $('input[name="touch"]').val(response.data.touch);
                            $('input[name="fine"]').val(response.data.fine);
                            $('input[name="notes"]').val(response.data.notes);
                            $('#method').val(response.data.method);

                            // Update Select2 with the correct item
                            let itemId = response.data.item;
                            let itemName = response.data.item_name;

                            // Ensure item exists in Select2 options
                            let select2Element = $('#item_name');
                            let optionExists = select2Element.find(`option[value="${itemId}"]`)
                                .length > 0;

                            if (!optionExists) {
                                // If the item is not in the options, add it
                                select2Element.append(new Option(itemName, itemId, false, true))
                                    .trigger('change');
                            } else {
                                // If the item already exists, just set it as the selected value
                                select2Element.val(itemId).trigger('change');
                            }

                            // Show the appropriate button based on the transaction type
                            if (response.data.type === 'credit') {
                                $('#credit-btn').show();
                                $('#debit-btn').hide();
                            } else if (response.data.type === 'debit') {
                                $('#debit-btn').show();
                                $('#credit-btn').hide();
                            }
                            $('#transaction-id').val(response.data.id);
                            $('#credit-btn').text('Update Dhal');
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
            //         url: "{{ route('dhal.edit', '') }}/" + id, // Adjust URL if necessary
            //         method: 'GET',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             if (response.success) {
            //                 // Populate the form with the data from the response
            //                 $('input[name="date"]').val(response.data.date);
            //                 $('input[name="dhal"]').val(response.data.dhal);
            //                 $('input[name="touch"]').val(response.data.touch);
            //                 $('input[name="fine"]').val(response.data.fine);
            //                 $('input[name="notes"]').val(response.data.notes);
            //                 $('#method').val(response.data.method);

            //                 // Update Select2 with the correct item
            //                 let itemId = response.data.item;
            //                 let itemName = response.data.item_name;

            //                 // Ensure item exists in Select2 options
            //                 let select2Element = $('#item_name');
            //                 let optionExists = select2Element.find(`option[value="${itemId}"]`)
            //                     .length > 0;

            //                 if (!optionExists) {
            //                     // If the item is not in the options, add it
            //                     select2Element.append(new Option(itemName, itemId, false, true))
            //                         .trigger('change');
            //                 } else {
            //                     // If the item already exists, just set it as the selected value
            //                     select2Element.val(itemId).trigger('change');
            //                 }

            //                 // Show the appropriate button based on the transaction type
            //                 if (response.data.type === 'credit') {
            //                     $('#credit-btn').show();
            //                     $('#debit-btn').hide();
            //                 } else if (response.data.type === 'debit') {
            //                     $('#debit-btn').show();
            //                     $('#credit-btn').hide();
            //                 }
            //                 $('#transaction-id').val(response.data.id);
            //                 $('#credit-btn').text('Update Dhal');
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

            // function submitForm(type) {
            //     const formData = {
            //         date: $('input[name="date"]').val(),
            //         item_name: $('#item_name').val(),
            //         dhal: $('input[name="dhal"]').val(),
            //         touch: $('input[name="touch"]').val(),
            //         fine: $('input[name="fine"]').val(),
            //         notes: $('input[name="notes"]').val(),
            //         type: type,
            //         method: $('input[name="method"]').val()
            //     };

            //     const button = $('#credit-btn');
            //     button.prop('disabled', true);

            //     $.ajax({
            //         url: "{{ route('dhal.store') }}",
            //         method: "POST",
            //         data: formData,
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             if (response.success) {
            //                 showAlert(
            //                     `${type.charAt(0).toUpperCase() + type.slice(1)} Data successful!`,
            //                     'success');
            //                 loadDhal();
            //                 $('#transaction-form')[0].reset();
            //                 $('#item_name').val(null).trigger('change');
            //             }
            //         },
            //         error: function(xhr) {
            //             const errors = xhr.responseJSON.errors;
            //             showErrorMessages(errors);
            //             // showAlert(`An error occurred while processing the ${type}.`, 'danger');
            //         },
            //         complete: function() {
            //             button.prop('disabled', false);
            //         }
            //     });
            // }

            function submitForm(type) {
                const transactionId = $('#transaction-id').val(); // Get the transaction ID
                const formData = {
                    date: $('input[name="date"]').val(),
                    item_name: $('#item_name').val(),
                    dhal: $('input[name="dhal"]').val(),
                    touch: $('input[name="touch"]').val(),
                    fine: $('input[name="fine"]').val(),
                    notes: $('input[name="notes"]').val(),
                    type: type,
                    method: $('input[name="method"]').val()
                };

                const button = $('#credit-btn');
                button.prop('disabled', true);

                $.ajax({
                    url: transactionId ? `{{ route('dhal.update', '') }}/${transactionId}` :
                        "{{ route('dhal.store') }}",
                    method: transactionId ? "PUT" : "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert(
                                `${type.charAt(0).toUpperCase() + type.slice(1)} Data successful!`,
                                'success');
                            loadDhal();
                            $('#transaction-form')[0].reset();
                            // $('#item_name').val(null).trigger('change');
                            $('#transaction-id').val(
                                ''); // Clear the transaction ID after successful update
                            $('#credit-btn').text('Submit'); // Reset the button text
                            // openSelect2AndClick();
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        showErrorMessages(errors);
                        // showAlert(`An error occurred while processing the ${type}.`, 'danger');
                    },
                    complete: function() {
                        button.prop('disabled', false);
                    }
                });
            }


            function showErrorMessages(errors) {
                $.each(errors, function(key, message) {
                    $('.error-' + key).text(message);
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

            // Clear error messages on input
            $('input[name="date"],input[name="item_name"], input[name="dhal"], input[name="touch"], input[name="notes"]')
                .on('input',
                    function() {
                        clearErrorMessages();
                    });

            function clearErrorMessages() {
                // $('.text-danger').text('');
                $('.text-danger').not('.required-asterisk').text('');
            }

            $('input[name="dhal"], input[name="touch"]').on('input', function() {
                validateNonNegative($(this));
                calculateFine();
            });

            function validateNonNegative(input) {
                let value = parseFloat(input.val());
                if (value < 0) {
                    input.val(0);
                }
            }

            function calculateFine() {
                let dhal = parseFloat($('input[name="dhal"]').val()) || 0;
                let touch = parseFloat($('input[name="touch"]').val()) || 0;
                let fine = (dhal * touch / 100).toFixed(3);
                $('input[name="fine"]').val(fine);
            }
        });
    </script>
@endpush
