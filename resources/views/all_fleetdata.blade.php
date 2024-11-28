@extends('layouts.app')

@push('style')
    <style>
        .text-wrap {
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 300px;
        }

        .sr-no-column {
            width: 50px;
        }

        .table-responsive {
            padding: 0 10px;
        }

        div.dt-container select.dt-input {
            padding: 0 18px !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
@endpush

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
                                    <label for="file_type">Select File Type:<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <select name="type" id="file_type" class="form-control">
                                        <option value="">Select File Type</option>
                                        @foreach ($fileTypes as $type)
                                            <option value="{{ $type->name }}"
                                                {{ old('type', $selectedType ?? '') == $type->name ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="listdata">Select File:<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <select name="listdata" id="listdata" class="form-control"
                                        {{ empty(old('type', $selectedType ?? '')) ? 'disabled' : '' }}>
                                        <option value="">Select File</option>
                                        @if ($filesList->isNotEmpty())
                                            @foreach ($filesList as $file)
                                                <option value="{{ $file->id }}"
                                                    {{ old('listdata', $listdata ?? '') == $file->id ? 'selected' : '' }}>
                                                    {{ $file->file_name }}
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
                                    @if ($selectedType == 'Godown Wise Item Summary')
                                        @if ($fileData)
                                            @php
                                                // Extract the first key (e.g., "Godown Wise Item Summary") and retrieve the data
                                                $tableName = array_key_first($fileData);
                                                $entries = $fileData[$tableName]; // Array of item entries
                                            @endphp

                                            <h4 class="text-center mt-4">{{ $tableName }}</h4>
                                            <!-- Display the table name -->

                                            @if (!empty($entries))
                                                <table
                                                    class="custom-datatable align-items-center mb-0 table-bordered table-hover custom-datatable dt-responsive">
                                                    <thead class="table-active">
                                                        <tr>
                                                            <!-- Static Columns -->
                                                            <th class="text-center">S.No.</th>
                                                            <th class="text-center">Name of Item</th>
                                                            <th class="text-center">Part No.</th>
                                                            <th class="text-center">Stock Group</th>
                                                            <th class="text-center">Stock Category</th>

                                                            <!-- Dynamic Columns for Godowns -->
                                                            @if (!empty($entries[0]['Godowns']))
                                                                @foreach ($entries[0]['Godowns'] as $godown)
                                                                    <th colspan="4" class="text-center">
                                                                        {{ $godown['Godown Name'] ?? '-' }}</th>
                                                                @endforeach
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <!-- Sub-headers for Godowns -->
                                                            @foreach ($entries[0]['Godowns'] as $godown)
                                                                <th class="text-center">Opening</th>
                                                                <th class="text-center">Inward</th>
                                                                <th class="text-center">Outward</th>
                                                                <th class="text-center">Closing</th>
                                                            @endforeach
                                                        </tr>

                                                    </thead>
                                                    <tbody>
                                                        @foreach ($entries as $entry)
                                                            <tr>
                                                                <!-- Static Columns -->
                                                                <td class="text-center">{{ $entry['S.No.'] ?? '-' }}</td>
                                                                <td class="text-center">{{ $entry['Name of Item'] ?? '-' }}
                                                                </td>
                                                                <td class="text-center">{{ $entry['Part No.'] ?? '-' }}
                                                                </td>
                                                                <td class="text-center">{{ $entry['Stock Group'] ?? '-' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $entry['Stock Category'] ?? '-' }}</td>

                                                                <!-- Dynamic Columns for Godowns -->
                                                                @foreach ($entry['Godowns'] as $godown)
                                                                    <td class="text-center">
                                                                        {{ $godown['Opening Balance'] ?? '-' }}</td>
                                                                    <td class="text-center">
                                                                        {{ $godown['Inward Quantity'] ?? '-' }}</td>
                                                                    <td class="text-center">
                                                                        {{ $godown['Outward Quantity'] ?? '-' }}</td>
                                                                    <td class="text-center">
                                                                        {{ $godown['Closing Balance'] ?? '-' }}</td>
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
                                        @if ($fileData)

                                            @php
                                                $tableName = array_key_first($fileData);
                                                $entries = $fileData[$tableName];
                                            @endphp
                                            <h4 class="text-center mt-4">{{ $tableName }}</h4>

                                            @if (!empty($entries))
                                                <table
                                                    class=" align-items-center mb-0 table-bordered table-hover custom-datatable dt-responsive">
                                                    <thead class="table-active">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>
                                                                Location
                                                            </th>
                                                            <th style="width:350px!important;">
                                                                Door No.
                                                            </th>
                                                            <th>
                                                                Total Cost
                                                            </th>
                                                            @if (!empty($entries[0]['Category']))
                                                                @foreach ($entries[0]['Category'] as $category)
                                                                    <th class="">
                                                                        {{ $category['Category Name'] }}</th>
                                                                @endforeach
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($entries as $key => $entry)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td class="">{{ $entry['Location'] ?? '-' }}</td>
                                                                <td class="">{{ $entry['Door No.'] ?? '-' }}
                                                                </td>
                                                                <td class=" text-wrap">{{ $entry['Total Cost'] ?? '-' }}
                                                                </td>
                                                                <!-- Dynamic Columns for Godowns -->
                                                                @foreach ($entry['Category'] as $category)
                                                                    <td class="text-wrap">
                                                                        {{ $category['Category Amount'] ?? '-' }}</td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <p class=" p-4">No data available for the selected file.</p>
                                            @endif
                                        @else
                                            <p class=" p-4">No data available for the selected file.</p>
                                        @endif
                                    @elseif ($selectedType == 'Stock Item Wise Vendor')
                                        @if ($fileData)
                                            @php
                                                $rootKey = array_key_first($fileData);
                                                $dynamicData = $fileData[$rootKey] ?? [];
                                                $keys = array_keys($dynamicData[0] ?? []);
                                            @endphp

                                            <h4 class="text-center mt-4">{{ $rootKey }}</h4>

                                            @if (count($dynamicData) > 0)
                                                <table
                                                    class="custom-datatable align-items-center mb-0 table-bordered table-hover">
                                                    <thead class="table-active">
                                                        <tr>
                                                            {{-- Optionally, you can add a "Sr. No." column if needed --}}
                                                            {{-- <th class="text-uppercase text-secondary text-xs  opacity-7 ps-2 sr-no-column">
                                                            Sr. No.
                                                        </th> --}}

                                                            @foreach ($keys as $key)
                                                                <th
                                                                    class="text-uppercase text-secondary text-xs  opacity-7 ps-2">
                                                                    {{ $key }}
                                                                </th>
                                                            @endforeach

                                                            <th
                                                                class="text-uppercase text-secondary text-xs  opacity-7 ps-2">
                                                                Vendor Name
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-secondary text-xs  opacity-7 ps-2">
                                                                Supplied Quantity
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-secondary text-xs  opacity-7 ps-2">
                                                                Last Supplied Price
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-secondary text-xs  opacity-7 ps-2">
                                                                Average Price
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($dynamicData as $index => $entry)
                                                            <tr>
                                                                @foreach ($keys as $key)
                                                                    <td class=" text-wrap">
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
                                                <p class=" p-4">No data available for the selected file.</p>
                                            @endif
                                        @else
                                            <p class=" p-4">No data available for the selected file.</p>
                                        @endif
                                    @elseif ($selectedType == 'TOP Consumable Report')
                                        @if ($fileData)
                                            @php
                                                // Extract the first key (e.g., "Godown Wise Item Summary") and retrieve the data
                                                $tableName = array_key_first($fileData);
                                                $entries = $fileData[$tableName]; // Array of item entries
                                            @endphp

                                            <h4 class="text-center mt-4">{{ $tableName }}</h4>
                                            <!-- Display the table name -->

                                            @if (!empty($entries))
                                                <table
                                                    class=" align-items-center mb-0 table-bordered table-hover custom-datatable dt-responsive">
                                                    <thead class="table-active">
                                                        <tr>
                                                            <!-- Static Columns -->
                                                            <th class="">S.No.</th>
                                                            <th class="">Name of Item</th>
                                                            <th class="">Part No.</th>
                                                            <th class="">Stock Group</th>
                                                            <th class="">Stock Category</th>
                                                            <th class="">Total Consumed Qty</th>

                                                            <!-- Dynamic Columns for Godowns -->
                                                            @if (!empty($entries[0]['Godown']))
                                                                @foreach ($entries[0]['Godown'] as $godown)
                                                                    <th class="">
                                                                        {{ $godown['Godown Name'] }}</th>
                                                                @endforeach
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($entries as $entry)
                                                            <tr>
                                                                <!-- Static Columns -->
                                                                <td class="">{{ $entry['S.No.'] ?? '-' }}</td>
                                                                <td class=" text-wrap">{{ $entry['Name of Item'] ?? '-' }}
                                                                </td>
                                                                <td class="">{{ $entry['Part No.'] ?? '-' }}
                                                                </td>
                                                                <td class=" text-wrap">{{ $entry['Stock Group'] ?? '-' }}
                                                                </td>
                                                                <td class=" text-wrap">
                                                                    {{ $entry['Stock Category'] ?? '-' }}</td>
                                                                <td class="text-wrap">
                                                                    {{ $entry['Total Consumed Qty'] ?? '-' }}</td>

                                                                <!-- Dynamic Columns for Godowns -->
                                                                @foreach ($entry['Godown'] as $godown)
                                                                    <td class="text-wrap">
                                                                        {{ $godown['Qunatity'] ?? '-' }}</td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <p class=" p-4">No data available for the selected file.</p>
                                            @endif
                                        @else
                                            <p class=" p-4">No data available for the selected file.</p>
                                        @endif
                                    @else
                                        @if ($fileData)
                                            @php
                                                // Extract the first key from $fileData (e.g., "Material Out Register")
                                                $tableName = array_key_first($fileData);
                                                $entries = $fileData[$tableName]; // The data entries
                                            @endphp

                                            <h4 class="text-center mt-4">{{ $tableName }}</h4>
                                            <!-- Display the table name -->

                                            @if (!empty($entries))
                                                <table
                                                    class="custom-datatable align-items-center mb-0 table-bordered table-hover ">
                                                    <thead class="table-active">
                                                        <tr>
                                                            @php
                                                                // Get keys for the first entry to use as column headers
                                                                $keys = array_keys($entries[0] ?? []);
                                                            @endphp
                                                            @foreach ($keys as $key)
                                                                <th
                                                                    class="text-uppercase text-secondary text-xs  opacity-7 ps-2">
                                                                    {{ $key }}
                                                                </th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($entries as $entry)
                                                            <tr>
                                                                @foreach ($keys as $key)
                                                                    <td class=" text-wrap">
                                                                        @php
                                                                            $value = $entry[$key] ?? '-';
                                                                            if (is_array($value)) {
                                                                                $value = array_map(function ($v) {
                                                                                    if (is_bool($v)) {
                                                                                        return $v ? 'true' : 'false';
                                                                                    }
                                                                                    return $v;
                                                                                }, $value);
                                                                                $value = implode(', ', $value);
                                                                            }
                                                                        @endphp
                                                                        {{ $value }}
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    </tbody>

                                                </table>
                                            @else
                                                <p class=" p-4">No data available for the selected file.</p>
                                            @endif
                                        @else
                                            <p class=" p-4">No data available for the selected file.</p>
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
@push('script')
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTables once the table is populated
            $('.custom-datatable').DataTable({
                responsive: true, // For responsive layout
                // You can add any additional DataTable options here
            });
        });

        document.addEventListener("DOMContentLoaded", function() {

            const fileTypeDropdown = document.getElementById('file_type');
            const fileDropdown = document.getElementById('listdata');

            fileTypeDropdown.addEventListener('change', function() {
                const selectedType = this.value;
                fileDropdown.innerHTML = '<option value="">Select File</option>';
                fileDropdown.disabled = true;

                if (selectedType) {
                    fetch('{{ route('fleet.getFilesByType') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                type: selectedType
                            }),
                        })
                        .then(response => response.json())
                        .then(files => {
                            if (files.length > 0) {
                                files.forEach(file => {
                                    const option = document.createElement('option');
                                    option.value = file.id;
                                    option.textContent = file.file_name;
                                    fileDropdown.appendChild(option);
                                });
                                fileDropdown.disabled = false;
                            }
                        })
                        .catch(error => console.error('Error fetching files:', error));
                }
            });
        });
    </script>
@endpush
