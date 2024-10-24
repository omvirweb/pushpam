@extends('layouts.app')
@section('main-content')
    <div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
        @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
            <div style="color: red;">
                {{ session('error') }}
            </div>
        @endif
        </div>
    </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Generate Fleet Report</h3>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid py-2">
                            <div id="alert-container" class="position-fixed top-0 end-0 p-3"></div>
                        </div>
                        <form action="{{ route('fleet.generateReport') }}" method="POST" enctype="multipart/form-data">
                        @csrf    
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="file">Select File:<span
                                        class="text-danger required-asterisk">*</span></label>
                                <select name="file_id" id="file_id" class="form-control">
                                <option value="">-- Select File --</option>
                                    @foreach ($files as $file)
                                        <option value="{{ $file->id }}">{{ $file->file_name }}</option>
                                    @endforeach
                                </select>    
                                
                            </div>
                        </div>
                        <button type="submit" id="submit-btn" class="btn btn-success">Generate Report</button>
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






