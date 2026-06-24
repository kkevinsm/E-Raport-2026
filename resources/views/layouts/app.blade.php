<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Raport Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-square.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

    /* Membuat semua Card melengkung (curved) bergaya modern */
    .card {
        border-radius: 16px !important; 
        overflow: hidden; 
        border: none !important;
    }
    .card-header {
        border-bottom: 1px solid #f0f0f0; 
    }

    /* Sidebar Modern Styles (myITS style) */
    .sidebar-menu {
        padding: 0;
        margin: 0;
    }
    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        color: #495057;
        font-weight: 500;
        font-size: 0.95rem;
        text-decoration: none;
        border-radius: 10px;
        margin-bottom: 6px;
        transition: all 0.2s ease-in-out;
    }
    .sidebar-link i {
        font-size: 1.15rem;
        color: #6c757d;
        transition: all 0.2s ease-in-out;
    }
    .sidebar-link:hover {
        background-color: rgba(0, 0, 0, 0.04);
        color: #212529;
    }
    .sidebar-link:hover i {
        color: #212529;
    }
    .sidebar-link.active {
        background-color: rgba(27, 104, 207, 0.12) !important;
        color: #1b68cf !important;
        font-weight: 600;
    }
    .sidebar-link.active i {
        color: #1b68cf !important;
    }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgba(255, 255, 255, 0.75); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0, 0, 0, 0.08); position: relative; z-index: 1050;">
    <div class="container-fluid">
        @if(Auth::user()->role !== 'guru')
            <button class="btn btn-link text-dark d-lg-none me-2 p-0 border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                <i class="bi bi-list fs-2" style="line-height: 1;"></i>
            </button>
        @endif
        <a class="navbar-brand ms-1 ms-lg-4" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo E-Raport" height="42" class="d-inline-block align-text-top">
        </a>
        <div class="dropdown ms-auto me-2 me-lg-4">
            <button class="btn btn-link text-decoration-none d-flex align-items-center text-dark fw-semibold p-0 border-0" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="me-2 d-none d-sm-inline">Halo, {{ Auth::user()->name }}</span>
                <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 1.1rem; background-color: #1b68cf !important;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-3" aria-labelledby="profileDropdown" style="width: 260px; border-radius: 12px; margin-top: 10px;">
                <li class="d-flex align-items-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 44px; height: 44px; font-size: 1.25rem; color: #1b68cf !important; background-color: rgba(27, 104, 207, 0.12) !important;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="mb-0 fw-bold text-dark text-truncate">{{ Auth::user()->name }}</h6>
                        <small class="text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            {{ Auth::user()->role }} - {{ Auth::user()->username }}
                        </small>
                    </div>
                </li>
                <li><hr class="dropdown-divider my-2"></li>
                <li>
                    <button class="dropdown-item d-flex align-items-center text-dark py-2 gap-2 mb-1" style="border-radius: 6px;" data-bs-toggle="modal" data-bs-target="#accountSettingsModal">
                        <i class="bi bi-gear fs-5 text-secondary"></i>
                        <span class="fw-medium">Account Settings</span>
                    </button>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center text-danger py-2 gap-2" style="border-radius: 6px;">
                            <i class="bi bi-box-arrow-right fs-5"></i>
                            <span class="fw-medium">Sign Out</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">
    <div class="row">
        
        @if(Auth::user()->role !== 'guru')
            <div class="col-lg-2 d-none d-lg-block">
                <div class="sidebar-menu">
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-door-fill"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <i class="bi bi-person-badge-fill"></i>
                            <span>Users (Guru)</span>
                        </a>
                        <a href="{{ route('admin.students') }}" class="sidebar-link {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
                            <i class="bi bi-mortarboard-fill"></i>
                            <span>Students</span>
                        </a>
                        <a href="{{ route('admin.courses') }}" class="sidebar-link {{ request()->routeIs('admin.courses') ? 'active' : '' }}">
                            <i class="bi bi-journal-bookmark-fill"></i>
                            <span>Courses</span>
                        </a>
                        
                    @elseif(Auth::user()->role === 'student')
                        <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-door-fill"></i>
                            <span>Dashboard Nilai</span>
                        </a>
                        <a href="{{ route('student.profile') }}" class="sidebar-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                            <i class="bi bi-person-fill"></i>
                            <span>Profil Saya</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <div class="{{ Auth::user()->role === 'guru' ? 'col-12' : 'col-lg-10 col-12' }}">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if($errors->has('name') || $errors->has('password'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @yield('content')
        </div>
        
</div>
</div>

@if(Auth::user()->role !== 'guru')
<!-- Sidebar Offcanvas for Mobile -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel" style="width: 280px;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">
            <img src="{{ asset('images/logo.png') }}" alt="Logo E-Raport" height="32">
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="sidebar-menu">
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Users (Guru)</span>
                </a>
                <a href="{{ route('admin.students') }}" class="sidebar-link {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Students</span>
                </a>
                <a href="{{ route('admin.courses') }}" class="sidebar-link {{ request()->routeIs('admin.courses') ? 'active' : '' }}">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span>Courses</span>
                </a>
                
            @elseif(Auth::user()->role === 'student')
                <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Dashboard Nilai</span>
                </a>
                <a href="{{ route('student.profile') }}" class="sidebar-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                    <i class="bi bi-person-fill"></i>
                    <span>Profil Saya</span>
                </a>
            @endif
        </div>
    </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Modal Account Settings (Global) -->
<div class="modal fade" id="accountSettingsModal" tabindex="-1" aria-labelledby="accountSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="accountSettingsModalLabel">Pengaturan Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Nama Lengkap (Nickname)</label>
                        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required placeholder="Nama Lengkap Baru">
                    </div>
                    
                    <hr class="my-4 text-muted">
                    <h6 class="fw-bold text-dark mb-3">Ubah Password</h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin diubah" minlength="6">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-secondary">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-info-circle-fill me-1 text-primary"></i> Lupa password? Hubungi Admin Utama jika Anda bukan pengelola server utama.
                    </small>
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-4 px-4 d-flex gap-2">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" style="background-color: #1b68cf; border-color: #1b68cf; border-radius: 8px;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stack('scripts')

</body>
</html>