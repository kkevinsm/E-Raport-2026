<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login E-Raport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-left {
            background-image: url("{{ asset('images/sekolah.jpg') }}");
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }
        
        .login-right {
            min-height: 100vh;
            background-color: #f8f9fa; 
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0">
            
            <div class="col-lg-8 d-none d-lg-block login-left">
            </div>

            <div class="col-lg-4 d-flex align-items-center justify-content-center login-right px-4">
                
                <div class="card shadow-lg border-0" style="width: 100%; max-width: 400px; border-radius: 15px;">
                    <div class="card-body p-4">
                        
                        <div class="text-center mb-4">
                            <h4 class="fw-bold text-dark mb-1">Login E-Raport</h4>
                            <p class="text-muted small mb-0">Selamat datang, silakan masuk ke akun Anda.</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger p-2 mb-4 text-center">
                                <small>{{ $errors->first() }}</small>
                            </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary small mb-1">NBM / NIS</label>
                                <input type="text" name="username" class="form-control bg-light" placeholder="Masukkan NBM / NIS" required autofocus>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary small mb-1">Password</label>
                                <input type="password" name="password" class="form-control bg-light" placeholder="Masukkan Password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2" style="border-radius: 8px;">
                                Login
                            </button>
                            
                        </form>
                        
                        <div class="text-center mt-4">
                            <small class="text-muted" style="font-size: 0.75rem;">&copy; {{ date('Y') }} Sistem Informasi Akademik Sekolah</small>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>