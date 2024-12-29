@extends('layouts.app')
@section('main-content')

<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <form id="userForm" action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3>Add/Update User Master
                            <button type="submit" class="btn btn-primary float-end">Save</button>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" id="userId">
                            <div class="col-md-3 mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="allowed_company" class="form-label">Allowed Companies</label>
                                <select id="allowed_company" class="form-select" multiple required>
                                    @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>User Master</h3>
                </div>
                <div class="card-body">
                    <div class="container-fluid py-2">
                        <div id="alert-container" class="position-fixed top-0 end-0 p-3"></div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const userForm = document.getElementById('userForm');
        const userId = document.getElementById('userId');
        const name = document.getElementById('name');
        const username = document.getElementById('username');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const allowedCompany = document.getElementById('allowed_company');
        const alertContainer = document.getElementById('alert-container');

        $('#allowed_company').select2({
            placeholder: 'Select Companies'
            , allowClear: true
        , });
        password.addEventListener('input', function() {
            if (password.value) {
                confirmPassword.setAttribute('required', 'required');
            } else {
                confirmPassword.removeAttribute('required');
            }
        });

        // Submit form via AJAX
        userForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const selectedCompanies = Array.from(allowedCompany.selectedOptions).map(option => option.value);

            fetch(userForm.action, {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    , }
                    , body: JSON.stringify({
                        id: userId.value
                        , username: username.value
                        , name: name.value
                        , password: password.value
                        , password_confirmation: confirmPassword.value
                        , allowed_company: selectedCompanies
                    , })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status == false) {
                        showAlert(data.message, 'danger');
                    } else {
                        console.log("Done");
                        showAlert(data.message, 'success');
                        userId.value = '';
                        name.value = '';
                        username.value = '';
                        password.value = '';
                        confirmPassword.value = '';
                        allowedCompany.value = '';
                        $('#allowed_company').val(data.allowed_company).trigger('change');
                        refreshTable();
                    }
                })
                .catch(error => {
                    console.log("error");
                    if (error.response && error.response.status === 422) {
                        let validationErrors = error.response.data.errors;
                        let errorMessages = '';
                        for (let field in validationErrors) {
                            validationErrors[field].forEach(message => {
                                errorMessages += `<p>${message}</p>`;
                            });
                        }
                        showAlert(errorMessages, 'danger');
                    } else {
                        showAlert('Failed to save user!', 'danger');
                    }
                });
        });

        // Edit functionality
        window.editUser = function(id) {
            let getUserRouteUrl = "{{ route('users.edit', ':id') }}".replace(':id', id);
            fetch(getUserRouteUrl)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    userId.value = data.id;
                    name.value = data.name;
                    username.value = data.username;
                    password.value = '';
                    confirmPassword.value = '';
                    password.removeAttribute('required');
                    confirmPassword.removeAttribute('required');
                    $('#allowed_company').val(data.allowed_company).trigger('change');
                })
                .catch(error => {
                    showAlert('Failed to fetch user details!', 'danger');
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
            const userId = $(this).data('id');
            const confirmDelete = confirm('Are you sure you want to delete this user?');

            if (confirmDelete) {
                let deleteUserRouteUrl = "{{ route('users.destroy', ':id') }}".replace(':id', userId);
                $.ajax({
                    url: deleteUserRouteUrl
                    , type: 'DELETE'
                    , data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    , }
                    , success: function(response) {
                        showAlert(response.message, 'success');
                        refreshTable();
                    }
                    , error: function(xhr, status, error) {
                        showAlert('Failed to delete the user!', 'danger');
                    }
                });
            }
        });

        function refreshTable() {
            $('#userTable').DataTable().ajax.reload();
        }
    });

</script>
@endpush
