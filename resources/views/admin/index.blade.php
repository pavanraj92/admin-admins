@extends('admin::admin.layouts.master')

@section('title', 'Admins Management')
@section('page-title', 'Admin Manager')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Admin Manager</li>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Filter Start-->
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title">Filter</h4>
                <form action="{{ route('admin.admins.index') }}" method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Keyword</label>
                                <input type="text" name="keyword" id="keyword" class="form-control"
                                    value="{{ app('request')->query('keyword') }}" placeholder="Enter keyword">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control select2">
                                    <option value="">All</option>
                                    <option value="0" {{ app('request')->query('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                    <option value="1" {{ app('request')->query('status') == '1' ? 'selected' : '' }}>Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto mt-1 text-right">
                            <div class="form-group">
                                <label for="created_at">&nbsp;</label>
                                <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary mt-4">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Filter End-->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Create Button -->
                    @admincan('admin_manager_create')
                    <div class="text-right">
                        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary mb-3">Create New Admin</a>
                    </div>
                    @endadmincan

                    <!-- Admins Table -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>S. No.</th>
                                    <th>@sortablelink('name', 'Name', [], ['class' => 'text-dark']) </th>
                                    <th>@sortablelink('email', 'Email', [], ['class' => 'text-dark'])</th>
                                    <th>Role</th>
                                    <th>@sortablelink('status', 'Status', [], ['class' => 'text-dark'])</th>
                                    <th>@sortablelink('created_at', 'Created At', [], ['class' => 'text-dark'])</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($admins as $admin)
                                <tr>
                                    <td scope="row">{{ ($admins->currentPage() - 1) * $admins->perPage() + $loop->iteration }}</td>
                                    <td>{{ $admin?->full_name ?? 'N/A' }}</td>
                                    <td>{{ $admin?->email ?? 'N/A' }}</td>
                                    <td>
                                        @forelse($admin?->roles as $role)
                                        <span class="badge bg-secondary text-white">{{ ucwords($role->name) }}</span>
                                        @empty
                                        <span class="badge bg-secondary">N/A</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Click to change status to {{ $admin->status ? 'inactive' : 'active' }}"
                                            data-url="{{ route('admin.admins.updateStatus') }}"
                                            data-method="POST"
                                            data-status="{{ $admin->status ? 0 : 1 }}"
                                            data-id="{{ $admin->id }}"
                                            class="btn btn-sm update-status {{ $admin->status ? 'btn-success' : 'btn-warning' }}">
                                            {{ $admin?->status ? 'Active' : 'Inactive' }}
                                        </a>
                                    </td>
                                    <td>{{ $admin?->created_at ? $admin->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : '—' }}</td>
                                    <td style="width: 10%;">
                                        @admincan('admin_manager_view')
                                        <a href="{{ route('admin.admins.show', $admin) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="View this record"
                                            class="btn btn-warning btn-sm">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        @endadmincan

                                        @admincan('admin_manager_edit')
                                        <a href="{{ route('admin.admins.edit', $admin) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit this record"
                                            class="btn btn-success btn-sm">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        @endadmincan

                                        @admincan('admin_manager_delete')
                                        <a href="javascript:void(0)"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Delete this record"
                                            data-url="{{ route('admin.admins.destroy', $admin) }}"
                                            data-text="Are you sure you want to delete this record?"
                                            data-method="DELETE"
                                            class="btn btn-danger btn-sm delete-record">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                        @endadmincan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination Start -->
                        @if ($admins->count() > 0)
                        {{ $admins->links('admin::pagination.custom-admin-pagination') }}
                        @endif
                        <!-- Pagination End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection