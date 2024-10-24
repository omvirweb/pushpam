@extends('layouts.app')
@section('main-content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Create Item</h3>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid py-2">
                            <div id="alert-container" class="position-fixed top-0 end-0 p-3"></div>
                        </div>
                        <form id="create-item-form">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="item_name">Item Name:<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <input type="text" name="item_name" id="item_name" class="form-control" />
                                    <div class="text-danger error-item_name"></div>
                                </div>
                            </div>
                            <button type="button" id="submit-btn" class="btn btn-success">Create Item</button>
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
                                    <table class="table align-items-center mb-0 table-bordered table-hover" id="item-table">
                                        <thead class="table-active">
                                            <tr>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                    Action
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                    Item Name
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
@endsection
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            function formatDate(dateString) {
                let date = new Date(dateString);
                let day = ('0' + date.getDate()).slice(-2);
                let month = ('0' + (date.getMonth() + 1)).slice(-2); // Months are zero-based
                let year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }

            $('#submit-btn').on('click', function() {
                const isUpdate = $(this).data('id') !== undefined;
                if (isUpdate) {
                    updateItem($(this).data('id'));
                } else {
                    submitForm();
                }
            });

            fetchItems();
            initializeDataTable();

            function initializeDataTable() {
                // Check if DataTable is already initialized
                if ($.fn.DataTable.isDataTable('#item-table')) {
                    // Destroy existing DataTable instance
                    $('#item-table').DataTable().clear().destroy();
                }

                // Initialize DataTable
                $('#item-table').DataTable({
                    "paging": false, // Disable pagination
                    "searching": false, // Disable search box
                    "ordering": true, // Enable sorting
                    "info": false, // Disable table info (like showing 'Showing 1 to 10 of 50 entries')
                    "order": [
                        [1, 'asc']
                    ], // Default sorting on the second column (index 1) in ascending order
                    "language": {
                        "emptyTable": "No data available" // Message when no data is available
                    },
                    "columnDefs": [{
                            "orderable": false, // Disable sorting on the first column (buttons)
                            "targets": 0
                        },
                        {
                            "orderable": true, // Enable sorting on other columns
                            "targets": [1, 2]
                        }
                    ]
                });
            }

            function fetchItems() {
                $.ajax({
                    url: "{{ route('items.data') }}",
                    method: "GET",
                    success: function(response) {
                        let table = $('#item-table').DataTable();
                        table.clear().draw(); // Clear existing data

                        if (response.length > 0) {
                            response.forEach(item => {
                                let formattedDate = formatDate(item.created_at);
                                let newRow = `
                            <tr data-id="${item.id}">
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm edit-btn"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash-alt"></i></button>
                                </td>
                                <td class="text-left">${item.item_name}</td>
                                <td class="text-center">${formattedDate}</td>
                            </tr>
                        `;

                                // Add new row to DataTable
                                table.row.add($(newRow)).draw();
                            });

                            // Reattach event listeners after adding rows
                            $('.delete-btn').on('click', function() {
                                let row = $(this).closest('tr');
                                let id = row.data('id');
                                deleteItem(id, row);
                            });

                            $('.edit-btn').on('click', function() {
                                let row = $(this).closest('tr');
                                let id = row.data('id');
                                editItem(id);
                            });

                        } else {
                            // Add a single row indicating no data
                            table.row.add(`
                        <tr>
                            <td colspan="3" class="text-center">No data available</td>
                        </tr>
                    `).draw();
                        }
                    },
                    error: function() {
                        showAlert('An error occurred while fetching data.', 'danger');
                    }
                });
            }

            function deleteItem(id, row) {
                if (confirm('Are you sure you want to delete this item?')) {
                    $.ajax({
                        url: "{{ route('items.destroy', '') }}/" + id,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                showAlert('Item deleted successfully!', 'success');
                                row.remove();
                            } else {
                                showAlert('An error occurred while deleting the item.', 'danger');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred while deleting the item.', 'danger');
                        }
                    });
                }
            }

            function editItem(id) {
                $.ajax({
                    url: "{{ route('items.edit', '') }}/" + id,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (!response.error) {
                            $('#item_name').val(response.item_name);
                            $('#submit-btn').text('Update Item').data('id', id);
                        } else {
                            showAlert('Item not found', 'danger');
                        }
                    },
                    error: function() {
                        showAlert('An error occurred while fetching item details.', 'danger');
                    }
                });
            }

            function submitForm() {
                const formData = {
                    item_name: $('#item_name').val(),
                };

                $.ajax({
                    url: "{{ route('item.store') }}",
                    method: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert(response.message, 'success');
                            $('#create-item-form')[0].reset(); // Reset form fields
                            fetchItems(); // Optionally refresh the item list
                        } else {
                            showAlert(response.message, 'danger');
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

            function updateItem(id) {
                const formData = {
                    item_name: $('#item_name').val(),
                };

                $.ajax({
                    url: "{{ route('items.update', '') }}/" + id, // Adjust route for update
                    method: "PUT",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('Item updated successfully!', 'success');
                            $('#create-item-form')[0].reset(); // Reset form fields
                            $('#submit-btn').text('Create Item').removeData(
                                'id'); // Reset button text and ID
                            fetchItems(); // Refresh item list
                        } else {
                            showAlert(response.message, 'danger');
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
        });
    </script>
@endpush
