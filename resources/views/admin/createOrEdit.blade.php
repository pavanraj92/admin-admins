@extends('admin::admin.layouts.master')

@section('title', 'Admins Management')
@section('page-title', isset($admin) ? 'Edit Admin' : 'Create Admin')

@push('styles')
@include('admin::admin.partials.style')
@endpush

@section('breadcrumb')
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.admins.index') }}">Admin Manager</a></li>
<li class="breadcrumb-item active" aria-current="page">{{isset($admin) ? 'Edit Admin' : 'Create Admin'}}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Start Admin Content -->
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <form action="{{ isset($admin) ? route('admin.admins.update', $admin->id) : route('admin.admins.store') }}"
                    method="POST" id="adminForm">
                    @isset($admin)
                    @method('PUT')
                    @endisset
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name<span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control alphabets-only" placeholder="Enter First Name" value="{{ $admin?->first_name ?? old('first_name') }}" required>
                                @error('first_name')
                                <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name<span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control alphabets-only" placeholder="Enter Last Name"
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
                                <input type="text" name="email" class="form-control" placeholder="Enter Email"
                                    value="{{ $admin?->email ?? old('email') }}" required>
                                @error('email')
                                <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mobile<span class="text-danger">*</span></label>
                                <input type="text" name="mobile" class="form-control numbers-only" placeholder="Enter Mobile"
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
                                <label>Role<span class="text-danger">*</span></label>
                                <select multiple name="role_ids[]" class="form-control select2 @error('role_ids') is-invalid @enderror" required>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ in_array($role->id, $assignedRoleIds ?? []) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('role_ids')
                                <div id="role-error" class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status<span class="text-danger">*</span></label>
                                <select name="status" class="form-control select2" required>
                                    <option value="1" {{ (($admin?->status ?? old('status')) == '1') ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ (($admin?->status ?? old('status')) == '0') ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="saveBtn"> {{isset($admin) ? 'Update' : 'Save'}}</button>
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End admin Content -->
</div>
@endsection

@push('scripts')
@include('admin::admin.partials.script')
@endpush