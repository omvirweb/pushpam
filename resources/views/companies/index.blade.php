@extends('layouts.app')
@section('main-content')

<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <form id="companyForm" action="{{ route('companies.store')}}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3>Add/Update Company Master
                            <button type="submit" class="btn btn-primary float-end">Save</button>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" id="companyId">
                            <div class="col-md-3 mb-3">
                                <label for="code" class="form-label">Company Code</label>
                                <input type="text" class="form-control" id="code" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="name" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Company Master
                        <a data-bs-toggle="modal" data-bs-target="#companyModal" class="btn btn-sm btn-icon btn-3 btn-primary mb-0 float-right">
                            <i class="fa fa-plus me-2"></i> Add
                        </a>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="container-fluid py-2">
                        <div id="alert-container" class="position-fixed top-0 end-0 p-3">
                        </div>
                    </div>
                    <div class="row">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script>
    function refreshTable() {
        $('#companyTable').DataTable().ajax.reload();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const companyForm = document.getElementById('companyForm');
        const companyId = document.getElementById('companyId');
        const code = document.getElementById('code');
        const name = document.getElementById('name');
        const alertContainer = document.getElementById('alert-container');
        // Submit form via AJAX
        companyForm.addEventListener('submit', function(e) {
            e.preventDefault();

            fetch(companyForm.action, {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    , }
                    , body: JSON.stringify({
                        id: companyId.value
                        , code: code.value
                        , name: name.value
                    , })
                , })
                .then((response) => response.json())
                .then((data) => {
                    showAlert(data.message, 'success');
                    companyId.value = '';
                    code.value = '';
                    name.value = '';
                    refreshTable();
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        let validationErrors = error.response.data.errors;
                        let errorMessages = '';
                        for (let field in validationErrors) {
                            validationErrors[field].forEach((message) => {
                                errorMessages += `<p>${message}</p>`;
                            });
                        }
                        showAlert(errorMessages, 'danger');
                    } else {
                        showAlert('Failed to save company!', 'danger');
                    }
                });
        });

        // Edit functionality
        window.editCompany = function(id) {
            let getCompanyRouteUrl = "{{route('companies.edit', ':id')}}".replace(':id', id);
            fetch(getCompanyRouteUrl)
                .then((response) => response.json())
                .then((data) => {
                    companyId.value = data.id;
                    code.value = data.code;
                    name.value = data.name;
                })
                .catch((error) => {
                    showAlert('Failed to fetch company details!', 'danger');
                });
        };

        // Show alert
        function showAlert(message, type) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.role = 'alert';
            alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
            alertContainer.appendChild(alert);
            setTimeout(() => alert.remove(), 3000);
        }

        $(document).on('click', '.delete-btn', function() {
            const companyId = $(this).data('id');
            const confirmDelete = confirm('Are you sure you want to delete this company?');

            if (confirmDelete) {
                let deleteCmpanyRouteUrl = "{{route('companies.destroy', ':id')}}".replace(':id', companyId);
                $.ajax({
                    url: deleteCmpanyRouteUrl
                    , type: 'DELETE'
                    , data: {
                        _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    }
                    , success: function(response) {
                        showAlert(response.message, 'success');
                        refreshTable();
                    }
                    , error: function(xhr, status, error) {
                        showAlert('Failed to delete the company!', 'danger');
                    }
                });
            }
        });
    });

</script>
@endpush
