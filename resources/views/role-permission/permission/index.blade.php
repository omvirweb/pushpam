{{--  <x-app-layout>  --}}
@extends('layouts.app')
@section('main-content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h4>Permissions
                        <a href="{{ url('permissions/create') }}" class="btn btn-primary float-end">Add Permission</a>
                    </h4>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script type="text/javascript"></script>
@endpush

{{--  </x-app-layout>  --}}
