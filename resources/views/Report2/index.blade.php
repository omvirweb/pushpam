@extends('layouts.app')
@section('main-content')
    <style>
        .table tbody td {
            white-space: break-spaces !important;
        }
    </style>

    <div class="container-fluid py-2">
        <div class="card ">
            <div class="card-body ">
                <div class="container-fluid py-2">
                    <div id="alert-container" class="position-fixed top-0 end-0 p-3">
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-4">
                        <label for="new_account_id" class="form-label">Name</label>
                        <select id="new_account_id" name="new_account_id" class="form-control">
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="daterange" class="form-label">Date Range</label>
                        <input type="text" id="daterange" class="form-control" name="daterange" />
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
                                <h3 class="d-flex justify-content-center">Report2</h3>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0" style="box-shadow: 0px 5px 10px lightblue;">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 table-bordered table-hover" id="datatable">
                                <thead class="table-active">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">SrNo.</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Party</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Amount</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Fine</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Chorsa Delivered</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Chorsa Not Delivered</th>
                                    </tr>
                                </thead>

                                <tbody>
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
        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route('report2.datatable') !!}',
                data: function(d) {
                    var dates = $('#daterange').val().split(' - ');

                    d.start_date = moment(dates[0], 'DD/MM/YYYY').format('YYYY/MM/DD');
                    d.end_date = moment(dates[1], 'DD/MM/YYYY').format('YYYY/MM/DD');
                    d.name = $('#new_account_id').val();
                },
                type: 'post',
                dataType: 'json',
            },
            columns: [{
                    data: 'account_id',
                    name: 'account_id'
                },
                {
                    data: 'account_name',
                    name: 'account_name'
                },
                {
                    data: 'amount',
                    name: 'amount',
                    className: 'text-right'
                },
                {
                    data: 'fine_amount',
                    name: 'fine_amount',
                    className: 'text-right'
                },
                {
                    data: 'chorsa_del',
                    name: 'chorsa_del',
                    className: 'text-right'
                },
                {
                    data: 'chorsa_not_del',
                    name: 'chorsa_not_del',
                    className: 'text-right'
                },
            ]
        });

        $('#daterange, #new_account_id').change(function() {
            table.draw();
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

        $('#daterange').daterangepicker({
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
    </script>
@endpush
