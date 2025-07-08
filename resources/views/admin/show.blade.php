@extends('admin::admin.layouts.master')

@section('title', 'Admins Management')

@section('page-title', 'Admin Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.admins.index') }}">Manage Admins</a></li>
    <li class="breadcrumb-item active" aria-current="page">Admin Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Email Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">                    
                    <div class="table-responsive">
                         <div class="card-body">      
                            <table class="table table-responsive-lg table-no-border">
                                <tbody>
                                    <tr>
                                        <th scope="row">Name</th>
                                        <td scope="col">{{ $admin->full_name ?? 'N/A' }}</td>
                                    </tr>         
                                    <tr>
                                        <th scope="row">Email</th>
                                        <td scope="col">{{ $admin->email ?? 'N/A' }}</td>
                                    </tr>                                
                                    <tr>
                                        <th scope="row">Mobile</th>
                                        <td scope="col">{{ $admin->mobile }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Status</th>
                                        <td scope="col"> {!! config('admin.constants.aryStatusLabel.' . $admin->status, 'N/A') !!}</td>
                                    </tr>    
                                    <tr>
                                        <th scope="row">Created At</th>
                                        <td scope="col"> {{ $admin->created_at
                                            ? $admin->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                            : '—' }}</td>
                                    </tr>                                
                                </tbody>
                            </table>   
                                             
                            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Back</a> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End category Content -->
    </div>
    <!-- End Container fluid  -->
@endsection
