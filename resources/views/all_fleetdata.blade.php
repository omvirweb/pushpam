@extends('layouts.app')
<style>
    .text-wrap {
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 300px;
    }

    .sr-no-column {
        width: 50px;
    }
</style>
@section('main-content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('fleet.reportScreen_post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="file">Select File:<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <select name="listdata" id="listdata" class="form-control">
                                        @if ($filesList)
                                            <option value="">Select File</option>
                                            @foreach ($filesList as $file)
                                                <option value="{{ $file->id }}"
                                                    @if ($listdata == $file->id) selected @endif>{{ $file->file_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <button type="submit" id="submit-btn" class="btn btn-success">Display Fleet Data</button>
                        </form>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <div class="card mb-4">
                            <div class="card-header p-4">
                                <h3>Fleet Data</h3>
                            </div>
                            <div class="card-body p-0" style="box-shadow: 0px 5px 10px lightblue;">
                                <div class="table-responsive">
                                    @if ($fileData)
                                        @php
                                            // Access the data inside the "Material Out Register" key
                                            $materialOutRegister = $fileData['Material Out Register'] ?? [];
                                            $keys = array_keys($materialOutRegister[0] ?? []); // Get the keys for the first element
                                        @endphp

                                        @if (count($materialOutRegister) > 0)
                                            <table class="table align-items-center mb-0 table-bordered table-hover">
                                                <thead class="table-active">
                                                    <tr>
                                                        <th
                                                            class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2 sr-no-column">
                                                            Sr. No.</th> <!-- Apply class here -->
                                                        @foreach ($keys as $key)
                                                            <th
                                                                class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                                {{ $key }}
                                                            </th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($materialOutRegister as $index => $entry)
                                                        <tr>
                                                            <td class="text-center sr-no-column">{{ $index + 1 }}</td>
                                                            <!-- Display Serial Number (Sr. No.) -->
                                                            @foreach ($keys as $key)
                                                                <td class="text-center text-wrap">
                                                                    {{-- If the value is an array, JSON encode it, otherwise show the value --}}
                                                                    {{ is_array($entry[$key] ?? null) ? json_encode($entry[$key]) : \Illuminate\Support\Str::limit($entry[$key] ?? '-', 50) }}
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="text-center p-4">No data available for the selected file.</p>
                                        @endif
                                    @else
                                        <p class="text-center p-4">No data available for the selected file.</p>
                                    @endif
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
