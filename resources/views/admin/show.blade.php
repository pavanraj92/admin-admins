@extends('admin::admin.layouts.master')

@section('title', 'Admin Details - ' . ($admin?->full_name ?? 'N/A'))
@section('page-title', 'Admin Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Admin Manager</a></li>
<li class="breadcrumb-item active" aria-current="page">Admin Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="card-title mb-0">
                            {{ $admin?->full_name ?? 'N/A' }}
                            <span class="badge {{ $admin?->status == 1 ? 'badge-success' : 'badge-secondary' }}">
                                {{ $admin?->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </h4>
                        <div>
                            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary ml-2">Back</a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Admin Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Name:</label>
                                                <p>{{ $admin?->full_name ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Email:</label>
                                                <p>{{ $admin?->email ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Mobile:</label>
                                                <p>{{ $admin?->mobile ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Role(s):</label>
                                                <p>
                                                    @forelse($admin?->roles as $role)
                                                        <span class="badge badge-secondary">{{ ucwords($role->name) }}</span>
                                                    @empty
                                                        <span class="badge badge-secondary">N/A</span>
                                                    @endforelse
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Created At:</label>
                                                <p>{{ $admin?->created_at ? $admin->created_at->format(config('GET.admin_date_time_format') ?? 'M d, Y h:i A') : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Quick Actions</h5>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    @admincan('admin_manager_edit')
                                    <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-warning mb-2">
                                        <i class="mdi mdi-pencil"></i> Edit Admin
                                    </a>
                                    @endadmincan

                                    @admincan('admin_manager_delete')
                                    <button type="button" class="btn btn-danger delete-btn delete-record"
                                        title="Delete this record"
                                        data-url="{{ route('admin.admins.destroy', $admin) }}"
                                        data-redirect="{{ route('admin.admins.index') }}"
                                        data-text="Are you sure you want to delete this admin?"
                                        data-method="DELETE">
                                        <i class="mdi mdi-delete"></i> Delete Admin
                                    </button>
                                    @endadmincan
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- card-body -->
            </div><!-- card -->
        </div><!-- col -->
    </div><!-- row -->
</div>
@endsection
