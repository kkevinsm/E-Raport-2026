<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login E-Raport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS Khusus untuk Halaman Login */
        .login-left {
            /* Ganti URL di bawah dengan gambar gedung sekolah/abstrak pilihan Anda */
            /* Contoh menggunakan asset Laravel: background-image: url('{{ asset('images/bg-login.jpg') }}'); */
            background-image: url("{{ asset('images/sekolah.jpg') }}");
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }
        
        .login-right {
            min-height: 100vh;
            background-color: #f8f9fa; /* Warna abu-abu sangat muda */
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0">
            
            <div class="col-lg-6 d-none d-lg-block login-left">
                </div>

            <div class="col-lg-6 d-flex align-items-center justify-content-center login-right">
                
                <div class="card shadow-lg border-0" style="width: 100%; max-width: 450px; border-radius: 15px;">
                    <div class="card-body p-5">
                        
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-dark mb-2">Login E-Raport</h2>
                            <p class="text-muted">Selamat datang, silakan masuk ke akun Anda.</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger p-2 mb-4 text-center">
                                <small>{{ $errors->first() }}</small>
                            </div>
                        @endif

                        <form action="/login" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">NBM / NIS</label>
                                <input type="text" name="username" class="form-control form-control-lg bg-light" placeholder="Masukkan NBM / NIS" required autofocus>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary">Password</label>
                                <input type="password" name="password" class="form-control form-control-lg bg-light" placeholder="Masukkan Password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm" style="border-radius: 10px;">
                                Login
                            </button>
                            
                        </form>
                        
                        <div class="text-center mt-4">
                            <small class="text-muted">&copy; {{ date('Y') }} Sistem Informasi Akademik Sekolah</small>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>