{{-- @extends('layouts.app')

@section('main-content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            Change Password
        </div>
        <div class="card-body">
            <form id="change-password-form" method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input
                        type="password"
                        name="current_password"
                        id="current_password"
                        class="form-control"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        required
                        autocomplete="new-password"
                    >
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-control"
                        required
                        autocomplete="new-password"
                    >
                </div>

                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
            <div id="response-message" class="mt-3"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#change-password-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Clear previous response messages
        $('#response-message').empty();
        $('#current_password_error').text('');
        $('#password_error').text('');
        $('#password_confirmation_error').text('');

        // Get form data
        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'), // Use the form's action attribute
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#response-message').html('<div class="alert alert-success">' +
                    response.message + '</div>');
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                if (errors.current_password) {
                    $('#current_password_error').text(errors.current_password[0]);
                }
                if (errors.password) {
                    $('#password_error').text(errors.password[0]);
                }
                if (errors.password_confirmation) {
                    $('#password_confirmation_error').text(errors.password_confirmation[0]);
                }
            }
        });
    });
});

</script>
@endpush --}}

@extends('layouts.app')
@section('main-content')
<style>
    /* Ensure the message starts fully visible */

    .auto-hide {
        opacity: 1;
        transition: opacity 0.5s ease-out;
        animation: fadeOut 5s forwards;
    }

    /* Define the keyframes for the fade-out effect */
    @keyframes fadeOut {
        0% {
            opacity: 1; /* Start fully visible */
        }
        90% {
            opacity: 1; /* Remain visible for most of the time */
        }
        100% {
            opacity: 0; /* Fade out to invisible */
            display: none; /* Hide the element completely */
        }
    }
</style>
<div class="container">

    <!-- Success Message -->
    @if (session('status'))
        <div class="alert alert-success auto-hide" id="success-alert">
            {{ session('status') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            Change Password
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <!-- Current Password Field -->
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input
                        type="password"
                        name="current_password"
                        id="current_password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        required
                        autocomplete="current-password"
                    >
                    <!-- Error for Current Password -->
                    @error('current_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- New Password Field -->
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                        autocomplete="new-password"
                    >
                    <!-- Error for New Password -->
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    {{-- @if ($errors->has('password'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif --}}
                </div>

                <!-- Confirm New Password Field -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-control"
                        required
                        autocomplete="new-password"
                    >
                    @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
                </div>

                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
</script>
@endpush
