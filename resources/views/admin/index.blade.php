@extends('admin::admin.layouts.master')

@section('title', 'Admins Management')

@section('page-title', 'Admin Manager')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Admin Manager</li>
@endsection

@section('content')
<!-- Container fluid  -->
<div class="container-fluid">
    <!-- Start admin Content -->
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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @admincan('admin_manager_create')
                    <div class="text-right">
                        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary mb-3">Create New Admin</a>
                    </div>
                    @endadmincan

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">S. No.</th>
                                    <th scope="col">@sortablelink('name', 'Name', [], ['style' => 'color: #4F5467; text-decoration: none;']) </th>
                                    <th scope="col">@sortablelink('email', 'Email', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                    <th scope="col">@sortablelink('status', 'Status', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                    <th scope="col">@sortablelink('created_at', 'Created At', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($admins) && $admins->count() > 0)
                                @php
                                $i = ($admins->currentPage() - 1) * $admins->perPage() + 1;
                                @endphp
                                @foreach ($admins as $admin)
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $admin->full_name ?? '' }}</td>
                                    <td>{{ $admin->email ?? '' }}</td>
                                    <td>
                                        <!-- create update status functionality-->
                                        @if ($admin->status == '1')
                                        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top"
                                            title="Click to change status to inactive"
                                            data-url="{{ route('admin.admins.updateStatus') }}"
                                            data-method="POST" data-status="0" data-id="{{ $admin->id }}"
                                            class="btn btn-success btn-sm update-status">Active</a>
                                        @else
                                        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top"
                                            title="Click to change status to active"
                                            data-url="{{ route('admin.admins.updateStatus') }}"
                                            data-method="POST" data-status="1"
                                            data-id="{{ $admin->id }}"
                                            class="btn btn-warning btn-sm update-status">InActive</a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $admin->created_at
                                                        ? $admin->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                        : '—' }}
                                    </td>
                                    <td style="width: 10%;">
                                        @admincan('admin_manager_view')
                                        <a href="{{ route('admin.admins.show', $admin) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="View this record"
                                            class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                        @endadmincan
                                        @admincan('admin_manager_edit')
                                        <a href="{{ route('admin.admins.edit', $admin) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit this record"
                                            class="btn btn-success btn-sm"><i class="mdi mdi-pencil"></i></a>
                                        @endadmincan
                                        @admincan('admin_manager_delete')
                                        <a href="javascript:void(0)"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Delete this record"
                                            data-url="{{ route('admin.admins.destroy', $admin) }}"
                                            data-text="Are you sure you want to delete this record?"
                                            data-method="DELETE"
                                            class="btn btn-danger btn-sm delete-record"><i class="mdi mdi-delete"></i></a>
                                        @endadmincan
                                    </td>
                                </tr>
                                @php
                                $i++;
                                @endphp
                                @endforeach
                                @else

                                <tr>
                                    <td colspan="6" class="text-center">No records found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                        <!--pagination move the right side-->
                        @if ($admins->count() > 0)
                        {{ $admins->links('admin::pagination.custom-admin-pagination') }}
                        @endif

                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- End admin Content -->
</div>
<!-- End Container fluid  -->
@endsection