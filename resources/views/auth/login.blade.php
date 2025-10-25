<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Deep Learning</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Custom CSS untuk tampilan login --}}
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.25);
            overflow: hidden;
            width: 100%;
            margin: 0 auto;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .login-header h3 {
            margin: 0 0 10px 0;
            font-weight: 700;
            font-size: 1.75rem;
        }

        .login-header p {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .login-body {
            padding: 35px 30px 40px;
        }

        .info-box {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            border-left: 4px solid #667eea;
            padding: 16px 18px;
            margin-bottom: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
        }

        .info-box strong {
            color: #667eea;
            font-size: 0.9rem;
            display: block;
            margin-bottom: 8px;
        }

        .info-box small {
            font-size: 0.85rem;
            line-height: 1.8;
        }

        .info-box code {
            background: #667eea;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
            transform: translateY(-1px);
        }

        .form-control::placeholder {
            color: #999;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #5568d3 0%, #6a3f93 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: white;
            font-size: 0.85rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            body {
                padding: 15px;
            }

            .login-header {
                padding: 30px 20px;
            }

            .login-header h3 {
                font-size: 1.5rem;
            }

            .login-header p {
                font-size: 0.9rem;
            }

            .login-body {
                padding: 25px 20px 30px;
            }

            .info-box {
                padding: 12px 14px;
                font-size: 0.8rem;
            }

            .info-box small {
                font-size: 0.75rem;
                line-height: 1.7;
            }

            .info-box code {
                font-size: 0.7rem;
                padding: 2px 6px;
            }

            .form-control {
                padding: 10px 14px;
                font-size: 0.9rem;
            }

            .btn-login {
                padding: 12px;
                font-size: 0.95rem;
            }

            .footer-text {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 375px) {
            .login-header h3 {
                font-size: 1.35rem;
            }

            .info-box small {
                font-size: 0.7rem;
            }
        }

        /* Animation */
        .login-card {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            {{-- Header Login --}}
            <div class="login-header">
                <h3>Sistem Deep Learning</h3>
                <p class="mb-0">Silakan login untuk melanjutkan</p>
            </div>

            {{-- Body Login --}}
            <div class="login-body">
                {{-- Tampilkan pesan error jika login gagal --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Login Gagal!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Informasi akun untuk testing --}}
                <div class="info-box">
                    <strong>Akun untuk testing:</strong>
                    <small>
                        Admin: <code>admin</code> / <code>admin123</code><br>
                        Guru: <code>guru</code> / <code>guru123</code><br>
                        Siswa: <code>siswa</code> / <code>siswa123</code>
                    </small>
                </div>

                {{-- Form Login --}}
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    {{-- Input Username --}}
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input
                            type="text"
                            class="form-control @error('username') is-invalid @enderror"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Masukkan username"
                            required
                            autofocus
                        >
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Input Password --}}
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tombol Login --}}
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-login">
                            Masuk ke Sistem
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer-text">
            &copy; {{ date('Y') }} Sistem Deep Learning - Laravel {{ app()->version() }}
        </div>
    </div>

    {{-- Bootstrap 5 JS Bundle (termasuk Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
