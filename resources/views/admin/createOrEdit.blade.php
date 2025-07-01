@extends('admin::admin.layouts.master')

@section('title', 'Admins Management')

@section('page-title', 'Create Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.admins.index') }}">Manage Admins</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create Admin</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Start Admin Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <form action="{{ isset($admin) ? route('admin.admins.update', $admin->id) : route('admin.admins.store') }}"
                        method="POST" id="adminForm">
                        @if (isset($admin))
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-md-6">                                
                                <div class="form-group">
                                    <label>First Name<span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control"
                                        value="{{ $admin?->first_name ?? old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">                                
                                <div class="form-group">
                                    <label>Last Name<span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control"
                                        value="{{ $admin?->last_name ?? old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">                                
                                <div class="form-group">
                                    <label>Email<span class="text-danger">*</span></label>
                                    <input type="text" name="email" class="form-control"
                                        value="{{ $admin?->email ?? old('email') }}" required>
                                    @error('email')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">                                
                                <div class="form-group">
                                    <label>Mobile<span class="text-danger">*</span></label>
                                    <input type="text" name="mobile" class="form-control"
                                        value="{{ $admin?->mobile ?? old('mobile') }}" required>
                                    @error('mobile')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Role <span class="text-danger">*</span></label>
                                    <select name="role_id" class="form-control select2" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ (old('role_id', isset($admin) ? $admin->role_id : '') == $role->id) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status<span class="text-danger">*</span></label>
                                    <select name="status" class="form-control select2" required>
                                        <option value="0" {{ (($admin?->status ?? old('status')) == '0') ? 'selected' : '' }}>InActive</option>
                                        <option value="1" {{ (($admin?->status ?? old('status')) == '1') ? 'selected' : '' }}>Active</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End admin Content -->
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">
    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Custom CSS for the admin -->
    <link rel="stylesheet" href="{{ asset('backend/custom.css') }}">           
@endpush

@push('scripts')
    <!-- Then the jQuery Validation plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- Select2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for any select elements with the class 'select2'
            $('.select2').select2();

            //jquery validation for the form
            $('#adminForm').validate({
                ignore: [],
                rules: {
                    first_name: {
                        required: true,
                        minlength: 3
                    },
                    last_name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    mobile: {
                        required: true,
                        digits: true,
                        minlength: 3,
                        maxlength: 15,
                    }
                },
                messages: {
                    first_name: {
                        required: "Please enter a first name",
                        minlength: "First name must be at least 3 characters long"
                    },
                    last_name: {
                        required: "Please enter a last name",
                        minlength: "Last name must be at least 3 characters long"
                    },
                    email: {
                        required: "Please enter email",
                        email: "Please enter a valid email address"
                    },
                    mobile: {
                        required: "Please enter mobile no.",
                        digits: "Please enter a valid mobile number",
                        minlength: "Mobile number must be at least 3 digits long",
                        maxlength: "Mobile number must not exceed 15 digits"
                    }
                },
                errorElement: 'div',
                errorClass: 'text-danger custom-error',
                errorPlacement: function(error, element) {
                    $('.validation-error').hide(); // hide blade errors
                    error.insertAfter(element);
                }
            });
        });
    </script>
@endpush
