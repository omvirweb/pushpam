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
        $(document).ready(function() {
            let firstLoad = true;
            let columns = [];

            const table = $('#fleetDataTable').DataTable({
                serverSide: true,
                processing: true,
                searching: false,
                ordering: false,
                autoWidth: false,
                responsive: true,
                scrollCollapse: true,
                scroller: {
                    loadingIndicator: true,
                    displayBuffer: 10
                },
                ajax: {
                    url: "{{ route('fleet.data') }}",
                    data: function(d) {
                        d.type = "{{ $selectedType }}";
                        d.company = "{{ $selectedCompany }}";
                    },
                    dataFilter: function(data) {
                        let json = JSON.parse(data);
                        
                        let tableData = json.data["Material Out Register"] ||
                            json.data["Fleet Wise Trip - Diesel - KMS - Hours"] || json.data[
                                "Stock Item Wise Vendor List"] || json.data[
                                "Fleet Details"] || json.data[
                                "TOP Consumable Report"] || json.data[
                                "Fleet Wise Diesel Parts Oil Tyre Details"] ||
                            json.data["Godown Wise Item Summary"] || [];
                        console.log(tableData);
                        if (tableData && tableData.length > 0) {
                            let columns = [];

                            if (json.data["Godown Wise Item Summary"]) {
    let allGodowns = new Set();
    tableData.forEach(row => {
        if (row.Godowns) {
            row.Godowns.forEach(godown => {
                if (godown['Godown Name']) {
                    allGodowns.add(godown['Godown Name']);
                }
            });
        }
    });

    let uniqueGodowns = Array.from(allGodowns);
    
    // Create table HTML structure
    let tableHTML = `
        <table id="dataTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th rowspan="2">S.No.</th>
                    <th rowspan="2">Name of Item</th>
                    <th rowspan="2">Part No.</th>
                    <th rowspan="2">Stock Group</th>
                    <th rowspan="2">Stock Category</th>
    `;
    
    // Add godown header groups
    uniqueGodowns.forEach(godownName => {
        tableHTML += `<th colspan="4" class="text-center">${godownName}</th>`;
    });
    
    tableHTML += `</tr><tr>`;
    
    // Add subheaders for each godown
    uniqueGodowns.forEach(() => {
        tableHTML += `
            <th class="text-center">Opening</th>
            <th class="text-center">Inward</th>
            <th class="text-center">Outward</th>
            <th class="text-center">Closing</th>
        `;
    });
    
    tableHTML += `</tr></thead><tbody>`;
    
    // Add table data
    tableData.forEach((row, index) => {
        tableHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${row['Name of Item'] || '--'}</td>
                <td>${row['Part No.'] || '--'}</td>
                <td>${row['Stock Group'] || '--'}</td>
                <td>${row['Stock Category'] || '--'}</td>
        `;
        
        // Add data for each godown
        uniqueGodowns.forEach(godownName => {
            const godownData = row.Godowns ? 
                row.Godowns.find(g => g['Godown Name'] === godownName) : 
                null;
                
            tableHTML += `
                <td class="text-center">${godownData && godownData['Opening'] || '--'}</td>
                <td class="text-center">${godownData && godownData['Inward'] || '--'}</td>
                <td class="text-center">${godownData && godownData['Outward'] || '--'}</td>
                <td class="text-center">${godownData && godownData['Closing'] || '--'}</td>
            `;
        });
        
        tableHTML += `</tr>`;
    });
    
    tableHTML += `</tbody></table>`;
    
    // Add styles for the table
    const styles = `
        <style>
            #dataTable th {
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                padding: 8px;
                text-align: center;
            }
            #dataTable td {
                border: 1px solid #dee2e6;
                padding: 8px;
            }
            .text-center {
                text-align: center;
            }
        </style>
    `;
    
    // Append the table to the container
    document.getElementById('tableContainer').innerHTML = styles + tableHTML;
    
    
} else if (json.data["TOP Consumable Report"]) {
                                columns = [{
                                        title: 'S.No.',
                                        data: null,
                                        render: function(data, type, row, meta) {
                                            return meta.row + 1;
                                        }
                                    },
                                    {
                                        title: 'Name of Item',
                                        data: 'Name of Item',
                                        render: function(data) {
                                            return data || '--';
                                        }
                                    },
                                    {
                                        title: 'Part No.',
                                        data: 'Part No.',
                                        render: function(data, type, row) {
                                            return row['Part No.'] ||
                                                '--'; // Using bracket notation for the property with a dot
                                        }
                                    },
                                    {
                                        title: 'Stock Group',
                                        data: 'Stock Group',
                                        render: function(data) {
                                            return data || '--';
                                        }
                                    },
                                    {
                                        title: 'Stock Category',
                                        data: 'Stock Category',
                                        render: function(data) {
                                            return data || '--';
                                        }
                                    },
                                    {
                                        title: 'Total Consumed Qty',
                                        data: 'Total Consumed Qty',
                                        render: function(data) {
                                            return data || '--';
                                        }
                                    }
                                ];

                                // Dynamically add columns for Godowns
                                let allGodowns = new Set();
                                tableData.forEach(row => {
                                    if (row.Godown && Array.isArray(row.Godown)) {
                                        row.Godown.forEach(godown => {
                                            if (godown['Godown Name']) {
                                                allGodowns.add(godown['Godown Name']);
                                            }
                                        });
                                    }
                                });

                                let uniqueGodowns = Array.from(allGodowns);

                                // Add dynamic columns for Godowns
                                uniqueGodowns.forEach(godownName => {
                                    columns.push({
                                        title: godownName,
                                        data: 'Godown',
                                        render: function(data, type, row) {
                                            const godownData = row.Godown ? row
                                                .Godown.find(g => g[
                                                        'Godown Name'] ===
                                                    godownName) :
                                                null;
                                            return godownData && godownData[
                                                'Qunatity'] ? godownData[
                                                'Qunatity'] : '--';
                                        }
                                    });
                                });
                            } if (json.data["Godown Wise Item Summary"]) {
    let allGodowns = new Set();

    // Extract all unique Godown names
    tableData.forEach(row => {
        if (row.Godowns) {
            row.Godowns.forEach(godown => {
                if (godown['Godown Name']) {
                    allGodowns.add(godown['Godown Name']);
                }
            });
        }
    });

    let uniqueGodowns = Array.from(allGodowns);

    // Build the table structure dynamically
    let tableHTML = `
        <thead>
            <tr>
                <th rowspan="2">S.No.</th>
                <th rowspan="2">Name of Item</th>
                <th rowspan="2">Part No.</th>
                <th rowspan="2">Stock Group</th>
                <th rowspan="2">Stock Category</th>
    `;

    // Add Godown headers
    uniqueGodowns.forEach(godownName => {
        tableHTML += `<th colspan="4" class="text-center">${godownName}</th>`;
    });

    tableHTML += `
            </tr>
            <tr>
    `;

    // Add subheaders for each Godown
    uniqueGodowns.forEach(() => {
        tableHTML += `
            <th class="text-center">Opening</th>
            <th class="text-center">Inward</th>
            <th class="text-center">Outward</th>
            <th class="text-center">Closing</th>
        `;
    });

    tableHTML += `
            </tr>
        </thead>
        <tbody>
    `;

    // Add rows dynamically
    tableData.forEach((row, index) => {
        tableHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${row['Name of Item'] || '--'}</td>
                <td>${row['Part No.'] || '--'}</td>
                <td>${row['Stock Group'] || '--'}</td>
                <td>${row['Stock Category'] || '--'}</td>
        `;

        uniqueGodowns.forEach(godownName => {
            const godownData = row.Godowns
                ? row.Godowns.find(g => g['Godown Name'] === godownName)
                : null;

            tableHTML += `
                <td class="text-center">${godownData?.['Opening'] || '--'}</td>
                <td class="text-center">${godownData?.['Inward'] || '--'}</td>
                <td class="text-center">${godownData?.['Outward'] || '--'}</td>
                <td class="text-center">${godownData?.['Closing'] || '--'}</td>
            `;
        });

        tableHTML += `</tr>`;
    });

    tableHTML += `</tbody>`;

    // Insert table structure into the DOM
    $('#dataTable').html(tableHTML);

    // Destroy existing DataTable instance if it exists
    if ($.fn.DataTable.isDataTable('#dataTable')) {
        $('#dataTable').DataTable().clear().destroy();
    }

    // Initialize DataTable
    $('#dataTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 50,
        lengthChange: true,
        searching: true,
        ordering: true,
        scroller: true,
        scrollY: '600px',
        scrollCollapse: true,
        scrollX: true,
        deferRender: true,
        processing: true,
        language: {
            processing: "Loading data...",
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)"
        },
        drawCallback: function(settings) {
            $('#fleetDataTable thead th').css('vertical-align', 'middle');
            $('#fleetDataTable tbody td').css('vertical-align', 'middle');
        }
    });
}
else if (json.data["Stock Item Wise Vendor List"]) {
                                columns = [{
                                        title: 'Sr.No.',
                                        data: null,
                                        render: function(data, type, row, meta) {
                                            return meta.row + 1;
                                        }
                                    },
                                    {
                                        title: 'Name of Item',
                                        data: 'Name of Item',
                                        render: function(data) {
                                            return data || '--';
                                        }
                                    },
                                    {
                                        title: 'Part No.',
                                        data: 'Part No.',
                                        render: function(data, type, row) {
                                            return row['Part No.'] || '--';
                                        }
                                    },
                                    {
                                        title: 'Stock Group',
                                        data: 'Stock Group',
                                        render: function(data) {
                                            return data || '--';
                                        }
                                    },
                                    {
                                        title: 'Stock Category',
                                        data: 'Stock Category',
                                        render: function(data) {
                                            return data || '--';
                                        }
                                    }
                                ];

                                // Check if 'Vendor List' exists in any of the rows
                                var hasVendorList = json.data["Stock Item Wise Vendor List"].some(
                                    function(row) {
                                        return row["Vendor List"] && Array.isArray(row[
                                            "Vendor List"]) && row["Vendor List"].length > 0;
                                    });

                                // Helper function to safely get vendor data
                                function getVendorData(data, field) {
                                    if (data && Array.isArray(data) && data.length > 0 && data[0] &&
                                        data[0][field] !== undefined) {
                                        return data[0][field];
                                    }
                                    return '--';
                                }

                                // Vendor-related columns with improved error handling
                                const vendorColumns = [{
                                        title: 'Vendor Name',
                                        data: 'Vendor List',
                                        render: function(data, type, row) {
                                            return getVendorData(row["Vendor List"],
                                                "Vendor Name");
                                        }
                                    },
                                    {
                                        title: 'Supplied Quantity',
                                        data: 'Vendor List',
                                        render: function(data, type, row) {
                                            return getVendorData(row["Vendor List"],
                                                "Supplied Quantity");
                                        }
                                    },
                                    {
                                        title: 'Last Supplied Price',
                                        data: 'Vendor List',
                                        render: function(data, type, row) {
                                            return getVendorData(row["Vendor List"],
                                                "Last Supplied Price");
                                        }
                                    },
                                    {
                                        title: 'Average Price',
                                        data: 'Vendor List',
                                        render: function(data, type, row) {
                                            return getVendorData(row["Vendor List"],
                                                "Average Price");
                                        }
                                    }
                                ];

                                // Add vendor columns regardless of hasVendorList
                                columns = columns.concat(vendorColumns);
                            } else if (json.data["Fleet Wise Diesel Parts Oil Tyre Details"]) {
                                columns = [{
                                        title: 'S.No.',
                                        data: null,
                                        render: function(data, type, row, meta) {
                                            return meta.row + 1;
                                        }
                                    },
                                    {
                                        title: 'Location',
                                        data: 'Location',
                                        render: function(data) {
                                            return data || '--';
                                        }
                                    },
                                    {
                                        title: 'Door No.',
                                        data: 'Door No.',
                                        render: function(data, type, row) {
                                            return row['Door No.'] ||
                                                '--'; // Using bracket notation for the property with a dot
                                        }
                                    },
                                    {
                                        title: 'Total Cost',
                                        data: 'Total Cost',
                                        render: function(data) {
                                            return data || '--';
                                        }
                                    }
                                ];

                                // Dynamically add columns for each category
                                let allCategories = [];
                                tableData.forEach(row => {
                                    if (row.Category && Array.isArray(row.Category)) {
                                        row.Category.forEach(category => {
                                            if (category['Category Name']) {
                                                allCategories.push(category[
                                                    'Category Name']);
                                            }
                                        });
                                    }
                                });

                                // Remove duplicates using Set
                                let uniqueCategories = Array.from(new Set(allCategories));

                                // Add dynamic columns for categories
                                uniqueCategories.forEach(categoryName => {
                                    columns.push({
                                        title: categoryName,
                                        data: 'Category',
                                        render: function(data, type, row) {
                                            // Find the matching category for the row
                                            const categoryData = row.Category ? row
                                                .Category.find(category => category[
                                                        'Category Name'] ===
                                                    categoryName) : null;
                                            return categoryData ? categoryData[
                                                    'Category Amount'] || '--' :
                                                '--';
                                        }
                                    });
                                });

                            } else {
                                // Logic for other types of tables
                                const allKeys = new Set();
                                tableData.forEach(row => {
                                    if (typeof row === 'object' && row !== null) {
                                        Object.keys(row).forEach(key => allKeys.add(key));
                                    }
                                });

                                columns = Array.from(allKeys).map(key => ({
                                    title: key,
                                    data: key,
                                    render: function(data, type, row) {
                                        // return row[key] || '--';
                                        return (row[key]  === null || row[key]  === undefined || row[key]  === '') ? '--' : row[key] ;
                                    }
                                }));
                            }

                            // Check if DataTable is already initialized and destroy it if needed
                            if ($.fn.DataTable.isDataTable('#fleetDataTable')) {
                                $('#fleetDataTable').DataTable().clear().destroy();
                            }

                            // Empty the table before reinitializing
                            $('#fleetDataTable').empty();

                            // Create the custom header with godown names (if present)
                            let headerHtml = '<tr>';
                            headerHtml += columns
                                .map(col => `<th class="text-center">${col.title}</th>`)
                                .join('');
                            headerHtml += '</tr>';

                            // // Set custom header
                            // $('#fleetDataTable thead').html(headerHtml);

                            // Initialize the DataTable with the new data and columns
                            let table = $('#fleetDataTable').DataTable({
                                data: tableData,
                                columns: columns,
                                responsive: true,
                                autoWidth: false,
                                pageLength: 50,
                                lengthChange: true,
                                searching: true,
                                ordering: true,
                                scroller: true,
                                scrollY: '600px',
                                scrollCollapse: true,
                                scrollX: true,
                                deferRender: true,
                                processing: true,
                                language: {
                                    processing: "Loading data...",
                                    search: "Search:",
                                    lengthMenu: "Show _MENU_ entries",
                                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                                    infoEmpty: "Showing 0 to 0 of 0 entries",
                                    infoFiltered: "(filtered from _MAX_ total entries)"
                                },
                               
                                drawCallback: function(settings) {
                                    $('#fleetDataTable thead th').css('vertical-align',
                                        'middle');
                                    $('#fleetDataTable tbody td').css('vertical-align',
                                        'middle');
                                }
                            });
                        }

                        return JSON.stringify(json);
                    }
                },
                columnDefs: [{
                    targets: '_all',
                    render: function(data, type, row) {
                        if (Array.isArray(data)) {
                            return data.map(item => {
                                if (typeof item === 'boolean') {
                                    return item ? 'true' : 'false';
                                }
                                return item;
                            }).join(', ');
                        }
                        return data ?? '-';
                    }
                }]
            });

        });
    </script>
@endpush
