@extends('layouts.app')
@section('main-content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form id="transaction-form">
                    <div class="card-header">
                        <h3>Dana Leva Deva</h3>
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
                                <label class="form-label">Name<span class="text-danger required-asterisk">*</span></label>
                                <input type="text" name="name" required class="form-control" placeholder="Enter Name">
                                <div class="text-danger error-name"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Weight<span class="text-danger required-asterisk">*</span></label>
                                <input type="number" name="weight" required class="form-control" placeholder="Enter Weight" min="0">
                                <div class="text-danger error-weight"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Rate<span class="text-danger required-asterisk">*</span></label>
                                <input type="number" name="rate" required class="form-control" placeholder="Enter Rate" min="0">
                                <div class="text-danger error-rate"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total<span class="text-danger required-asterisk">*</span></label>
                                <input type="number" name="total" required class="form-control" placeholder="Enter Total" min="0">
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
                    <div class="modal-footer md-8 d-flex justify-content-center">
                        <button type="button" id="credit-btn" class="btn btn-success">Credit</button>
                        <button type="button" id="debit-btn" class="btn btn-primary">Debit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h2 class="fw-500">Dana Leva Deva</h2>
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
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Action</th>
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Name</th>
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Weight</th>
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Rate</th>
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Total</th>
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Notes</th>
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Date</th>
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
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Action</th>
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Name</th>
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Weight</th>
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Rate</th>
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Total</th>
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Notes</th>
                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">Date</th>
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
        loadAmounts();

        function formatDate(dateStr) {
            var date = new Date(dateStr);
            var day = ("0" + date.getDate()).slice(-2);
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        function loadAmounts() {
            $.ajax({
                url: "{{ route('dana.data') }}",
                method: "GET",
                success: function(data) {
                    $('#credit-table tbody').empty();
                    $('#debit-table tbody').empty();

                    data.credits.forEach(function(transaction) {
                        $('#credit-table tbody').append(`
                            <tr>
                                <td>
                                    <a data-bs-toggle="modal" data-bs-target="#editstaff" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                                </td>
                                <td class="align-middle text-wrap">${transaction.name || ''}</td>
                                <td class="text-wrap" align="right">${transaction.weight || ''}</td>
                                <td class="text-wrap" align="right">${transaction.rate || ''}</td>
                                <td class="text-wrap" align="right">${transaction.total || ''}</td>
                                <td class="text-wrap">${transaction.notes || ''}</td>
                                <td class="text-wrap">${formatDate(transaction.date)}</td>
                            </tr>
                        `);
                    });

                    data.debits.forEach(function(transaction) {
                        $('#debit-table tbody').append(`
                            <tr>
                                <td>
                                    <a data-bs-toggle="modal" data-bs-target="#editstaff" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                                </td>
                                <td class="align-middle text-wrap">${transaction.name || ''}</td>
                                <td class="text-wrap" align="right">${transaction.weight || ''}</td>
                                <td class="text-wrap" align="right">${transaction.rate || ''}</td>
                                <td class="text-wrap" align="right">${transaction.total || ''}</td>
                                <td class="text-wrap">${transaction.notes || ''}</td>
                                <td class="text-wrap">${formatDate(transaction.date)}</td>
                            </tr>
                        `);
                    });
                },
                error: function(xhr) {
                    alert('An error occurred while loading data.');
                }
            });
        }

        $('#credit-btn').on('click', function() {
            submitForm('credit');
        });

        $('#debit-btn').on('click', function() {
            submitForm('debit');
        });

        function clearErrorMessages() {
            // $('.text-danger').text('');
            $('.text-danger').not('.required-asterisk').text('');

        }

        $('input[name="date"], input[name="name"], input[name="weight"], input[name="rate"], input[name="total"], input[name="notes"]').on('input', function() {
            clearErrorMessages();
        });

        function submitForm(type) {
            const formData = {
                date: $('input[name="date"]').val(),
                name: $('input[name="name"]').val(),
                weight: $('input[name="weight"]').val(),
                rate: $('input[name="rate"]').val(),
                total: $('input[name="total"]').val(),
                notes: $('input[name="notes"]').val(),
                type: type
            };

            const button = type === 'credit' ? $('#credit-btn') : $('#debit-btn');
            button.prop('disabled', true);

            $.ajax({
                url: "{{ route('dana.store') }}",
                method: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert(`${type.charAt(0).toUpperCase() + type.slice(1)} Data successful!`, 'success');
                        loadAmounts();
                        $('#transaction-form')[0].reset();
                        clearErrorMessages();
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        Object.keys(errors).forEach(function(key) {
                            $(`.error-${key}`).text(errors[key][0]);
                        });
                    }
                    // showAlert(`An error occurred while processing the ${type}.`, 'danger');
                },
                complete: function() {
                    button.prop('disabled', false);
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
