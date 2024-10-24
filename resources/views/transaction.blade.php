@extends('layouts.app')
@section('main-content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form id="transaction-form">
                        <div class="card-header">
                            <span>Transaction</span>
                            <a href="{{ route('account.create') }}" class="btn btn-primary float-right">Account</a>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid py-2">
                                <div id="alert-container" class="position-fixed top-0 end-0 p-3">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="date">Date<span class="text-danger required-asterisk">*</span></label>
                                    <input type="date" class="form-control" id="date" name="date"
                                        value="{{ date('Y-m-d') }}">
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
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="text" name="amount" id="amount" class="form-control" min="0"
                                        placeholder="Enter Amount">
                                    <div class="text-danger error-amount"></div>
                                </div>
                                <div class="col-md-3">
                                    <label for="opp_account_id" class="form-label">Opp. Account<span
                                            class="text-danger required-asterisk">*</span></label>
                                    {{-- <input type="text" name="opp_account_id" id="opp_account_id" class="form-control" placeholder="Enter account"> --}}
                                    <select id="opp_account_id" name="opp_account_id" class="form-control">
                                        <option value="self" selected>Self</option>
                                    </select>
                                    <div class="text-danger error-opp_account_id"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="fine" class="form-label">Fine</label>
                                    <input type="number" class="form-control" id="fine" name="fine" min="0"
                                        placeholder="Enter fine">
                                    <div class="text-danger error-fine"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="item" class="form-label">Item</label>
                                    <input type="text" class="form-control" id="item" name="item">
                                    <div class="text-danger error-item"></div>
                                </div>
                                <div class="col-md-2">
                                    <label for="dhal" class="form-label">Dhal</label>
                                    <input type="number" class="form-control" id="dhal" name="dhal" min="0"
                                        placeholder="Enter Dhal">
                                    <div class="text-danger error-dhal"></div>
                                </div>
                                <div class="col-md-2">
                                    <label for="touch" class="form-label">Touch</label>
                                    <input type="number" class="form-control" id="touch" name="touch" min="0"
                                        placeholder="Enter Touch">
                                    <div class="text-danger error-touch"></div>
                                </div>
                                <div class="col-md-2">
                                    <label for="fineCalc" class="form-label">Fine</label>
                                    <input type="number" class="form-control" name="fineCalc" id="fineCalc" readonly>
                                    <div class="text-danger error-fineCalc"></div>
                                </div>
                                <div class="col-md-8">
                                    <label for="notes" class="form-label">Notes</label>
                                    <input type="text" class="form-control" name="notes" id="notes"
                                        placeholder="Enter Notes">
                                    <div class="text-danger error-notes"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" id="credit-btn" class="btn btn-success">Credit</button>
                            <button type="button" id="debit-btn" class="btn btn-primary">Debit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
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

            $('#credit-btn').on('click', function() {
                submitForm('credit');
            });

            $('#debit-btn').on('click', function() {
                submitForm('debit');
            });

            function submitForm(type) {

                if (areAllFieldsEmpty()) {
                    showAlert('At least one field (Amount, Fine, Item, Dhal) must be filled', 'danger');
                    return;
                }

                const formData = {
                    date: $('input[name="date"]').val(),
                    account_id: $('#account_id').val(),
                    amount: $('input[name="amount"]').val(),
                    opp_account_id: $('#opp_account_id').val(),
                    fine: $('input[name="fine"]').val(),
                    item: $('input[name="item"]').val(),
                    dhal: $('input[name="dhal"]').val(),
                    touch: $('input[name="touch"]').val(),
                    fineCalc: $('input[name="fineCalc"]').val(),
                    notes: $('input[name="notes"]').val(),
                    type: type
                };

                const button = type === 'credit' ? $('#credit-btn') : $('#debit-btn');
                button.prop('disabled', true);

                $.ajax({
                    url: "{{ route('transactions.store') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showAlert('Transaction created successfully', 'success');
                            $('#transaction-form')[0].reset();
                            $('#account_id').val(null).trigger('change');
                            $('#opp_account_id').val(null).trigger('change');
                        } else {
                            showAlert('Failed to create transaction', 'danger');
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        showErrorMessages(errors);
                        // showAlert('Failed to create transaction', 'danger');
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

            function areAllFieldsEmpty() {
                return !$('input[name="amount"]').val() &&
                    !$('input[name="fine"]').val() &&
                    !$('input[name="item"]').val() &&
                    !$('input[name="dhal"]').val() &&
                    !$('input[name="touch"]').val();
            }

            function clearErrorMessages() {
                // $('.text-danger').text('');
                $('.text-danger').not('.required-asterisk').text('');
            }

            function calculateFine() {
                let dhal = parseFloat($('input[name="dhal"]').val()) || 0;
                let touch = parseFloat($('input[name="touch"]').val()) || 0;
                let fineCalc = (dhal * touch / 100).toFixed(3);
                $('input[name="fineCalc"]').val(fineCalc);
            }
        });
    </script>

        {{-- <script type="text/javascript">
        $(document).ready(function() {
            let accountIdCache = {};
            let oppAccountIdCache = {};

            function initializeSelect2(selector, cache) {
                $(selector).select2({
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
                        if (!cache[data.text]) {
                            $.ajax({
                                type: 'POST',
                                url: '{{ route('accounts.accountStore') }}',
                                data: {
                                    account_name: data.text,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    cache[data.text] = response.id;
                                    var newOption = new Option(response.text, response.id,
                                        false, true);
                                    $(selector).append(newOption).trigger('change');
                                },
                                error: function(xhr) {
                                    console.log('Error saving new tag:', xhr);
                                }
                            });
                        }
                    }
                    clearErrorMessages();
                });
            }

            initializeSelect2('#account_id', accountIdCache);
            initializeSelect2('#opp_account_id', oppAccountIdCache);

            $('#credit-btn').on('click', function() {
                submitForm('credit');
            });

            $('#debit-btn').on('click', function() {
                submitForm('debit');
            });

            function submitForm(type) {
                const formData = {
                    date: $('input[name="date"]').val(),
                    account_id: $('#account_id').val(),
                    amount: $('input[name="amount"]').val(),
                    opp_account_id: $('#opp_account_id').val(),
                    fine: $('input[name="fine"]').val(),
                    item: $('input[name="item"]').val(),
                    dhal: $('input[name="dhal"]').val(),
                    touch: $('input[name="touch"]').val(),
                    fineCalc: $('input[name="fineCalc"]').val(),
                    notes: $('input[name="notes"]').val(),
                    type: type
                };

                const button = type === 'credit' ? $('#credit-btn') : $('#debit-btn');
                button.prop('disabled', true);

                $.ajax({
                    url: "{{ route('transactions.store') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showAlert('Transaction created successfully', 'success');
                            $('#transaction-form')[0].reset();
                            $('#account_id').val(null).trigger('change');
                            $('#opp_account_id').val(null).trigger('change');
                        } else {
                            showAlert('Failed to create transaction', 'danger');
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        showErrorMessages(errors);
                        showAlert('Failed to create transaction', 'danger');
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

            function clearErrorMessages() {
                $('.text-danger').text('');
            }

            function showAlert(message, type) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                $('#alert-container').html(alertHtml);
            }

            // Prevent default Enter key behavior in Select2
            $(document).on('keypress', '.select2-search__field', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    return false;
                }
            });
        });
</script> --}}
@endpush
