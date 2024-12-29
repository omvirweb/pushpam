@extends('layouts.app')
@section('main-content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Upload your Json fill-rule</h3>
                </div>
                <div class="card-body">
                    <div class="container-fluid py-2">
                        <div id="alert-container" class="position-fixed top-0 end-0 p-3"></div>
                    </div>
                    <form action="{{ route('fleet.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="type">Select Type:<span class="text-danger required-asterisk">*</span></label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger error-type">
                                    @error('type')
                                    {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="file">Choose JSON file:<span class="text-danger required-asterisk">*</span></label>
                                <input type="file" name="file" id="file" class="form-control" accept=".json" required />
                                <div class="text-danger error-file">
                                    @error('file')
                                    {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" id="submit-btn" class="btn btn-success">Create Fleet Data</button>
                    </form>
                    @if(auth()->user()->id == 1)
                    <hr>
                    {{ $dataTable->table() }}
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
</div>
@endsection
@push('script')
@if(auth()->user()->id == 1)
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script>
    $(document).on('click', '.delete-btn', function() {
        const userId = $(this).data('id');
        const confirmDelete = confirm('Are you sure you want to delete this user?');

        if (confirmDelete) {
            let deleteUserRouteUrl = "{{ route('fleetfile.destroy', ':id') }}".replace(':id', userId);
            $.ajax({
                url: deleteUserRouteUrl
                , type: 'DELETE'
                , data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                , }
                , success: function(response) {
                    alert(response.message);
                    refreshTable();
                }
                , error: function(xhr, status, error) {
                    alert('Failed to delete the user!');
                }
            });
        }
    });

    function refreshTable() {
        $('#FleetFileTable').DataTable().ajax.reload();
    }

</script>
@endif
@endpush
