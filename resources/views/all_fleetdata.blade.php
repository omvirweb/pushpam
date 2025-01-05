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

    <link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.4.3/css/scroller.dataTables.min.css">
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
                                <div class="col-md-6 mb-3">
                                    <label for="company">Select Company:<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <select name="company" id="company" class="form-control" required>
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ old('company', $selectedCompany ?? '') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="file_type">Select File Type:<span
                                            class="text-danger required-asterisk">*</span></label>
                                    <select name="type" id="file_type" class="form-control" required>
                                        <option value="">Select File Type</option>
                                        @foreach ($fileTypes as $type)
                                            <option value="{{ $type->id }}"
                                                {{ old('type', $selectedType ?? '') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" id="latestFileId" name="listdata">
                            </div>
                            <button type="submit" id="submit-btn" class="btn btn-success">Generate Report</button>
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
                                            $keys = array_keys($fileData);
                                            $tableName = isset($keys[1]) ? $keys[1] : null;
                                            $entries = $fileData[$tableName];
                                        @endphp
                                        <h4 class="text-center mt-4">{{ $tableName }}</h4>
                                        @if (!empty($entries))
                                            @if ($selectedType == 'Godown Wise Item Summary' || $selectedType == 4)
                                                <table
                                                    class="custom-datatable align-items-center mb-0 table-bordered table-hover  dt-responsive">
                                                    <thead class="table-active">
                                                        <tr>
                                                            <th class="text-center">S.No.</th>
                                                            <th class="text-center">Name of Item</th>
                                                            <th class="text-center">Part No.</th>
                                                            <th class="text-center">Stock Group</th>
                                                            <th class="text-center">Stock Category</th>

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
                                                            @foreach ($entries[0]['Godowns'] as $godown)
                                                                <th class="text-center">Opening</th>
                                                                <th class="text-center">Inward</th>
                                                                <th class="text-center">Outward</th>
                                                                <th class="text-center">Closing</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody id="data-table-body">
                                                        <!-- Initial rows will be injected here via AJAX -->
                                                    </tbody>
                                                </table>
                                            @elseif ($selectedType == 'Fleet Wise Diesel Parts Oil Tyre Details' || $selectedType == 2)
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
                                            @elseif ($selectedType == 'Stock Item Wise Vendor List' || $selectedType == 1)
                                                @php
                                                    $keys = array_keys($entries[0] ?? []);
                                                @endphp
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
                                                        @foreach ($entries as $index => $entry)
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
                                            @elseif ($selectedType == 'TOP Consumable Report' || $selectedType == 7)
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
                                            @elseif ($selectedType == 'Fleet Details' || $selectedType == 5)
                                                <table
                                                    class=" align-items-center mb-0 table-bordered table-hover custom-datatable dt-responsive">
                                                    <thead class="table-active">
                                                        <tr>
                                                            <!-- Static Columns -->
                                                            <th class="">S.No.</th>
                                                            <th class="">Door No</th>
                                                            <th class="">Status(Active/Inactive)</th>
                                                            <th class="">Invoice No.</th>
                                                            <th class="">Name of Owner</th>
                                                            <th class="">Cost Center(Location)</th>
                                                            <th class="">Section</th>
                                                            <th class="">Date of Delivery</th>
                                                            <th class="">Capacity</th>
                                                            <th class="">Regd. Date</th>
                                                            <th class="">Regd. State</th>
                                                            <th class="">Regd. RTO</th>
                                                            <th class="">Regd.No.</th>
                                                            <th class="">Engine No.</th>
                                                            <th class="">Chasis No.</th>
                                                            <th class="">Road Tax From</th>
                                                            <th class="">Road Tax To</th>
                                                            <th class="">Fitness From</th>
                                                            <th class="">Fitness To</th>
                                                            <th class="">Permit for State</th>
                                                            <th class="">Permit From</th>
                                                            <th class="">Permit To</th>
                                                            <th class="">PESO From</th>
                                                            <th class="">PESO To</th>
                                                            <th class="">Calibration From</th>
                                                            <th class="">Calibration To</th>
                                                            <th class="">Remarks</th>
                                                            <th class="">Name of Financer</th>
                                                            <th class="">Agreement Number</th>
                                                            <th class="">Loan Amount</th>
                                                            <th class="">Tenure</th>
                                                            <th class="">EMI Start Date</th>
                                                            <th class="">EMI End Date</th>
                                                            <th class="">EMI Amount</th>
                                                            <th class="">Insured By</th>
                                                            <th class="">Insurance Policy No.</th>
                                                            <th class="">Insurance IDV</th>
                                                            <th class="">Insurance From</th>
                                                            <th class="">Insurance To</th>
                                                            <th class="">PUC From</th>
                                                            <th class="">PUC To</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($entries as $entry)
                                                            <tr>
                                                                <td class="text-wrap">{{ $entry['S.No.'] ?? '' }}</td>
                                                                <td class="text-wrap">{{ $entry['Door No'] ?? '' }}</td>
                                                                <td class="text-wrap">
                                                                    {{ $entry['Vehicle Status'][0] ?? '' }}</td>
                                                                <td class="text-wrap">{{ $entry['Invoice No.'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Name of Owner'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Cost Center'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Section'] ?? '' }}</td>
                                                                <td class="text-wrap">
                                                                    {{ $entry['Date of Delivery'] ?? '' }}</td>
                                                                <td class="text-wrap">
                                                                    {{ $entry['Loading Capacity'] ?? '' }}</td>
                                                                <td class="text-wrap">{{ $entry['Regd. Date'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Regd. State'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Regd. RTO'] ?? '' }}</td>
                                                                <td class="text-wrap">{{ $entry['Regd.No.'] ?? '' }}</td>
                                                                <td class="text-wrap">{{ $entry['Engine No.'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Chasis No.'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Road Tax From'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Road Tax To'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Fitness From'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Fitness To'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">
                                                                    {{ $entry['Permit for State'] ?? '' }}</td>
                                                                <td class="text-wrap">{{ $entry['Permit From'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Permit To'] ?? '' }}</td>
                                                                <td class="text-wrap">{{ $entry['PESO From'] ?? '' }}</td>
                                                                <td class="text-wrap">{{ $entry['PESO To'] ?? '' }}</td>
                                                                <td class="text-wrap">
                                                                    {{ $entry['Calibration From'] ?? '' }}</td>
                                                                <td class="text-wrap">{{ $entry['Calibration To'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Remarks'] ?? '' }}</td>
                                                                <td class="text-wrap">
                                                                    {{ $entry['Name of Financer'] ?? '' }}</td>
                                                                <td class="text-wrap">
                                                                    {{ $entry['Agreement Number'] ?? '' }}</td>
                                                                <td class="text-wrap">{{ $entry['Loan Amount'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Tenure'] ?? '' }}</td>
                                                                <td class="text-wrap">{{ $entry['EMI Start Date'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['EMI End Date'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['EMI Amount'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Insured By'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">
                                                                    {{ $entry['Insurance Policy No.'] ?? '' }}</td>
                                                                <td class="text-wrap">{{ $entry['Insurance IDV'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Insurance From'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['Insurance To'] ?? '' }}
                                                                </td>
                                                                <td class="text-wrap">{{ $entry['PUC From'] ?? '' }}</td>
                                                                <td class="text-wrap">{{ $entry['PUC To'] ?? '' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
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
                                            @endif
                                        @else
                                            <p class=" p-4">No data available for the selected file.</p>
                                        @endif
                                    @else
                                        <p class=" p-4">No data available for the selected file.</p>
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
    <script src="https://cdn.datatables.net/scroller/2.4.3/js/dataTables.scroller.min.js"></script>

    <script>
        let page = 1; // Current page
        const fileId = "{{ $listdata }}";
        let table;

        $(document).ready(function() {
            // Initialize the DataTable once
            table = $('.custom-datatable').DataTable({
                scroller: true,
                deferRender: true,
                scrollY: 600,
                autoWidth: false,
                responsive: false, // Disable responsive
            });

            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                    page++; // Increment page number
                    loadMoreData();
                }
            });

            loadMoreData(); // Load initial data
        });

        function loadMoreData() {
            $.ajax({
                url: "{{ route('loadFleetData') }}",
                method: 'GET',
                data: {
                    fileId: fileId,
                    page: page,
                },
                success: function(data) {
                    console.log(data);
                    if (data.length > 0) {
                        // Clear existing rows before adding new ones
                        let rows = [];
                        data.forEach(function(item) {
                            let row = `<tr>
                        <td class="">${item['S.No.'] ?? '-'}</td>
                        <td class="">${item['Name of Item'] ?? '-'}</td>
                        <td class="text-end">${item['Part No.'] ?? '-'}</td>
                        <td class="">${item['Stock Group'] ?? '-'}</td>
                        <td class="">${item['Stock Category'] ?? '-'}</td>`;

                            item['Godowns'].forEach(function(godown) {
                                row += `
                        <td class="text-end">${godown['Opening Balance'] ?? '-'}</td>
                        <td class="text-end">${godown['Inward Quantity'] ?? '-'}</td>
                        <td class="text-end">${godown['Outward Quantity'] ?? '-'}</td>
                        <td class="text-end">${godown['Closing Balance'] ?? '-'}</td>
                        `;
                            });

                            row += `</tr>`;
                            rows.push($(row)[0]); // Push the row element to an array
                        });

                        // Add the new rows to the DataTable
                        table.rows.add(rows); // Add rows to the table
                        table.draw(); // Redraw the table to reflect changes
                    }
                },
                error: function() {
                    console.log('Error loading data');
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            const fileTypeDropdown = document.getElementById('file_type');
            const companyDropdown = document.getElementById('company');
            // const fileDropdown = document.getElementById('listdata');
            const submitButton = document.getElementById('submit-btn');
            let selectedFileId = "{{ $listdata }}";
            submitButton.disabled = true;

            const fetchFiles = () => {
                const selectedType = fileTypeDropdown.value;
                const selectedCompany = companyDropdown.value;

                // fileDropdown.innerHTML = '<option value="">Select File</option>';
                // fileDropdown.disabled = true;

                if (selectedType || selectedCompany) {
                    submitButton.disabled = false;
                    fetch('{{ route('fleet.getFiles') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                type: selectedType,
                                company: selectedCompany
                            }),
                        })
                        .then(response => response.json())
                        .then(latestFile => {
                            if (latestFile && latestFile.id) {
                                document.getElementById('latestFileId').value = latestFile.id;

                            } else {
                                console.error('No latest file found');
                            }
                        })
                        .catch(error => console.error('Error fetching latest file:', error));
                }


            };
            fileTypeDropdown.addEventListener('change', fetchFiles);
            companyDropdown.addEventListener('change', fetchFiles);
            fetchFiles();
        });
    </script>
@endpush
