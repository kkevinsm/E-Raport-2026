<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Raport Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-square.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
    /* Merapikan elemen DataTables */
    .dataTables_wrapper .dataTables_filter {
        float: right;
        margin-bottom: 15px;
    }
    .dataTables_wrapper .dataTables_length {
        float: left;
        margin-bottom: 15px;
    }
    .dataTables_wrapper .dataTables_info {
        float: left;
        margin-top: 15px;
    }
    .dataTables_wrapper .dataTables_paginate {
        float: right;
        margin-top: 15px;
    }
    /* Memperbaiki garis tabel yang hilang di Bootstrap 5 */
    table.dataTable.no-footer {
        border-bottom: 1px solid #dee2e6 !important;
    }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand ms-4" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo E-Raport" height="42" class="d-inline-block align-text-top">
        </a>
        <div class="d-flex align-items-center">
            <span class="navbar-text me-3 text-white">
                Halo, {{ Auth::user()->name }}
            </span>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">
    <div class="row">
        
        @if(Auth::user()->role !== 'guru')
            <div class="col-md-2">
                <div class="list-group">
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users') ? 'active' : '' }}">Users (Guru)</a>
                        <a href="{{ route('admin.students') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.students*') ? 'active' : '' }}">Students</a>
                        <a href="{{ route('admin.courses') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.courses') ? 'active' : '' }}">Courses</a>
                        
                    @elseif(Auth::user()->role === 'student')
                        <a href="{{ route('student.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">Dashboard Nilai</a>
                        <a href="{{ route('student.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('student.profile') ? 'active' : '' }}">Profil Saya</a>
                    @endif
                </div>
            </div>
        @endif

        <div class="{{ Auth::user()->role === 'guru' ? 'col-md-12' : 'col-md-10' }}">
            @yield('content')
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

@stack('scripts')

</body>
</html>