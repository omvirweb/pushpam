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
 /* Style for both header and body cells */
 .custom-datatable  thead th,
#fleetDataTable tbody td,
.dataTables_scrollBody tbody td {
    white-space: normal !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
    max-width: 150px !important; /* Fixed width for all cells */
    min-width: 150px !important; /* Ensure minimum width */
    width: 150px !important; /* Force exact width */
}

/* Specific styles for header cells */
.custom-datatable thead th {
    position: sticky !important;
    top: 0;
    background-color: #fff; /* or your preferred background color */
    z-index: 1;
}

/* Handle specific columns if needed */
.custom-datatable thead th:first-child,
#fleetDataTable tbody td:first-child {
    min-width: 100px !important; /* Adjust for smaller columns */
    width: 100px !important;
}

/* Ensure scroll container doesn't override widths */
.dataTables_scrollBody {
    overflow-x: auto !important;
}

/* Force table layout to respect column widths */
#fleetDataTable {
    table-layout: fixed !important;
    width: 100% !important;
}

/* Handle text overflow */
.custom-datatable thead th,
#fleetDataTable tbody td {
    padding: 8px 25px !important;
    text-overflow: ellipsis !important;
    overflow: hidden !important;
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
                                        <option value="{{ $type->id }}" {{ old('type', $selectedType ?? '') == $type->id ? 'selected' : '' }}>
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
                            <div class="table-responsive" id="tableContainer">

                                <table id="fleetDataTable"
                                    class="custom-datatable align-items-center mb-0 table-bordered table-hover">
                                    <thead class="table-active">
                                        <tr>
                                            {{-- Columns will be generated dynamically via JavaScript --}}
                                        </tr>
                                    </thead>
                                </table>

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
   $('#fleetDataTable').DataTable({
        serverSide: true,
        processing: true,
        autoWidth: false,
        ordering: false,
        ajax: {
            url: "{{ route('fleet.data') }}",
            type: 'POST',
            data: function (d) {
                d.type = "{{ $selectedType }}";
                d.company = "{{ $selectedCompany }}";
            },
            dataSrc: function (json) {
                const headers = json.headers || [];
                const dynamicColumns = headers.map((header) => ({
                    title: header,
                    data: header,
                    defaultContent: '--', // Ensure a default value for undefined/null data
                    render: function (data) {
                        return data !== null && data !== undefined && data !== '' ? data : '--';
                    },
                    autoWidth: false 
                }));


                // Reinitialize DataTable if already initialized
                if ($.fn.DataTable.isDataTable('#fleetDataTable')) {
                    $('#fleetDataTable').DataTable().clear().destroy();
                    $('#fleetDataTable thead').empty(); // Clear the header to avoid mismatches
                }


                // Build custom multi-row headers dynamically if the key matches
                if (json.key === 'Godown Wise Item Summary') {
                    const staticHeaders = ['S.No.', 'Name of Item', 'Part No.', 'Stock Group', 'Stock Category'];
                    const godownHeaders = headers.slice(staticHeaders.length); // Extract dynamic Godown headers
                    const groupedHeaders = godownHeaders.reduce((acc, curr) => {
                        const [godownName, headerType] = curr.split(':'); // Split header by `:`
                        if (!acc[godownName]) acc[godownName] = [];
                        acc[godownName].push(headerType.trim());
                        return acc;
                    }, {});
                    
                    // Generate HTML for multi-row header
                    let firstRow = '<tr>';
                    let secondRow = '<tr>';
                    staticHeaders.forEach(header => {
                        firstRow += `<th rowspan="2">${header}</th>`;
                    });
                    
                    Object.entries(groupedHeaders).forEach(([godown, columns]) => {     
                        firstRow += `<th colspan="${columns.length}" class="text-center">${godown}</th>`;
                        columns.forEach(col => {
                            secondRow += `<th>${col}</th>`;
                        });
                    });
                    
                    firstRow += '</tr>';
                    secondRow += '</tr>';

                    // Replace the DataTable's header
                    $('#fleetDataTable thead').html(firstRow + secondRow);
                }


                // Initialize DataTable with new headers and columns
                $('#fleetDataTable').DataTable({
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: "{{ route('fleet.data') }}",
                        type: 'POST',
                        data: function (d) {
                            d.type = "{{ $selectedType }}";
                            d.company = "{{ $selectedCompany }}";
                        },
                    },
                    columns: dynamicColumns,
                    scrollY: '50vh',
                    scroller: true,
                    deferRender: true,
                    autoWidth: false,
                    ordering: false,
                    language: {
                        processing: "Loading data...",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    },
                });
                
                
                return json.data;
            },
            initComplete: function () {
                $('#fleetDataTable thead th').each(function () {
                    const originalText = $(this).text();
                    const newText = originalText.replace(/.*:\s?/, ''); // Remove text before ":" and the space
                    $(this).text(newText);
                });
            },
        
        },
    });

const thElements = document.querySelectorAll('#fleetDataTable th');

// Iterate over each <th> element and extract the part after the colon
$(document).ready(function() {
    thElements.forEach(th => {
        const fullText = th.getAttribute('aria-label') || th.innerText; // Get text from aria-label or innerText
        if (fullText.includes(':')) { // Check if the text contains a colon
            const extractedText = fullText.split(':').pop().trim(); // Get the part after the last colon and trim whitespace
            console.log(extractedText); // Logs "Opening", "Closing", etc.
        }
    });
});
    </script>
@endpush