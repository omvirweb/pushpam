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
                                <!-- Dropdown for file types -->
                                <div class="col-md-4 mb-3">
                                    <label for="file_type">Select File Type:<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <select name="type" id="file_type" class="form-control">
                                        <option value="">Select File Type</option>
                                        @foreach ($fileTypes as $type)
                                            <option value="{{ $type->name }}">
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Dropdown for files, filtered by selected file type -->
                                <div class="col-md-4 mb-3">
                                    <label for="listdata">Select File:<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <select name="listdata" id="listdata" class="form-control">
                                        @if ($filesList->isNotEmpty())
                                            <option value="">Select File</option>
                                            @foreach ($filesList as $file)
                                                <option value="{{ $file->id }}"
                                                    @if (old('listdata') == $file->id) selected @endif>
                                                    {{ $file->file_name }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="">No files available for this type</option>
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

                                    <!-- Condition based on file type -->
                                    @if ($selectedType == 'Stock Item Wise Vendor 123')
                                        @if ($fileData)
                                            @php
                                                $rootKey = array_key_first($fileData);
                                                $dynamicData = $fileData[$rootKey] ?? [];
                                                $keys = array_keys($dynamicData[0] ?? []);
                                            @endphp
                                            @if (count($dynamicData) > 0)
                                                <table class="table align-items-center mb-0 table-bordered table-hover">
                                                    <thead class="table-active">
                                                        <tr>
                                                            <th
                                                                class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2 sr-no-column">
                                                                Sr. No.</th>
                                                            @foreach ($keys as $key)
                                                                <th
                                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                                    {{ $key }}</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($dynamicData as $index => $entry)
                                                            <tr>
                                                                <td class="text-center sr-no-column">{{ $index + 1 }}
                                                                </td>
                                                                @foreach ($keys as $key)
                                                                    <td class="text-center text-wrap">
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
                                    @elseif ($selectedType == 'Fleet Wise Diesel Parts Oil Tyre')
                                        <table style="width: 100%;"
                                            class="table align-items-center mb-0 table-bordered table-hover"
                                            id="credit-table">
                                            <thead class="table-active">
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                        Id
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                        Location
                                                    </th>
                                                    <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2"
                                                        style="width:350px!important;">
                                                        Door No.
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                        Total Cost
                                                    </th>
                                                    @foreach ($header_arr as $val)
                                                        <th
                                                            class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                            {{ $val }}
                                                        </th>
                                                    @endforeach

                                                </tr>
                                            </thead>
                                            <tbody class="scrollable-tbody">
                                                @if ($alldata)
                                                    @foreach ($alldata as $detail)
                                                        <tr>
                                                            <td>{{ $detail->id }}</td>
                                                            <td>{{ $detail->location }}</td>

                                                            <td style="width:350px!important;">
                                                                <div style="word-wrap: break-word;">
                                                                    {{ $detail->door_no }}</div>
                                                            </td>

                                                            <td>{{ $detail->total_cost }}</td>
                                                            @foreach ($header_arr as $val)
                                                                <td>
                                                                    @if ($val == $detail->category_name)
                                                                        {{ $detail->category_amount }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                            @endforeach

                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    @else
                                        @if ($fileData)
                                            @php
                                                $rootKey = array_key_first($fileData);
                                                $dynamicData = $fileData[$rootKey] ?? [];
                                                $keys = array_keys($dynamicData[0] ?? []);
                                            @endphp

                                            @if (count($dynamicData) > 0)
                                                <table class="table align-items-center mb-0 table-bordered table-hover">
                                                    <thead class="table-active">
                                                        <tr>
                                                            {{-- Optionally, you can add a "Sr. No." column if needed --}}
                                                            {{-- <th class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2 sr-no-column">
                                                            Sr. No.
                                                        </th> --}}

                                                            @foreach ($keys as $key)
                                                                <th
                                                                    class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                                    {{ $key }}
                                                                </th>
                                                            @endforeach

                                                            <th
                                                                class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                                Vendor Name
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                                Supplied Quantity
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                                Last Supplied Price
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-secondary text-xs text-center opacity-7 ps-2">
                                                                Average Price
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($dynamicData as $index => $entry)
                                                            <tr>
                                                                {{-- Optionally, you can add the Sr. No. here --}}
                                                                {{-- <td class="text-center sr-no-column">{{ $index + 1 }}</td> --}}

                                                                @foreach ($keys as $key)
                                                                    <td class="text-center text-wrap">
                                                                        @if ($key == 'Vendor List')
                                                                            <!-- Handle Vendor List directly within the same column -->
                                                                            @if (isset($entry['Vendor List']) && count($entry['Vendor List']) > 0)
                                                                                <ul>
                                                                                    @foreach ($entry['Vendor List'] as $vendor)
                                                                                        <li><strong>Vendor Name:</strong>
                                                                                            {{ $vendor['Vendor Name'] ?? '-' }}
                                                                                        </li>
                                                                                        <li><strong>Supplied
                                                                                                Quantity:</strong>
                                                                                            {{ $vendor['Supplied Quantity'] ?? '-' }}
                                                                                        </li>
                                                                                        <li><strong>Last Supplied
                                                                                                Price:</strong>
                                                                                            {{ $vendor['Last Supplied Price'] ?? '-' }}
                                                                                        </li>
                                                                                        <li><strong>Average Price:</strong>
                                                                                            {{ $vendor['Average Price'] ?? '-' }}
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @else
                                                                                <span>-</span>
                                                                            @endif
                                                                        @else
                                                                            <!-- Display other data fields or a dash if data is missing -->
                                                                            {{ $entry[$key] ?? '-' }}
                                                                        @endif
                                                                    </td>
                                                                @endforeach

                                                                <!-- Vendor-specific columns (these should be displayed for each entry) -->
                                                                <td class="text-wrap">
                                                                    @if (isset($entry['Vendor List']) && count($entry['Vendor List']) > 0)
                                                                        @foreach ($entry['Vendor List'] as $vendor)
                                                                            {{ $vendor['Vendor Name'] ?? '-' }}
                                                                        @endforeach
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if (isset($entry['Vendor List']) && count($entry['Vendor List']) > 0)
                                                                        @foreach ($entry['Vendor List'] as $vendor)
                                                                            {{ $vendor['Supplied Quantity'] ?? '-' }}
                                                                        @endforeach
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if (isset($entry['Vendor List']) && count($entry['Vendor List']) > 0)
                                                                        @foreach ($entry['Vendor List'] as $vendor)
                                                                            {{ $vendor['Last Supplied Price'] ?? '-' }}
                                                                        @endforeach
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if (isset($entry['Vendor List']) && count($entry['Vendor List']) > 0)
                                                                        @foreach ($entry['Vendor List'] as $vendor)
                                                                            {{ $vendor['Average Price'] ?? '-' }}
                                                                        @endforeach
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
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