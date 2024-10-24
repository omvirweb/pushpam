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
                                <label for="file">Choose JSON file:<span
                                        class="text-danger required-asterisk">*</span></label>
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
                    
                </div>
            </div>

        </div>
    </div>
</div>
</div>
@endsection
@push('script')

@endpush