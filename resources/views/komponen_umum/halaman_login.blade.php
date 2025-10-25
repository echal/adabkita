@extends('layouts.template_utama')

@section('judul', 'Login - Sistem Deep Learning')

@section('konten')
{{--
    =====================================================
    HALAMAN LOGIN
    =====================================================
    Halaman ini untuk login pengguna dengan 3 role:
    - Admin (username: admin, password: admin123)
    - Guru (username: guru, password: guru123)
    - Siswa (username: siswa, password: siswa123)
    =====================================================
--}}

<style>
    /* Style khusus untuk halaman login */
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-container {
        width: 100%;
        max-width: 450px;
    }

    .kartu-login {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 50px rgba(0,0,0,0.25);
        overflow: hidden;
        width: 100%;
        margin: 0 auto;
    }

    .header-login {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 30px;
        text-align: center;
    }

    .header-login h3 {
        margin: 0 0 10px 0;
        font-weight: 700;
        font-size: 1.75rem;
    }

    .header-login p {
        margin: 0;
        font-size: 0.95rem;
        opacity: 0.95;
    }

    .isi-login {
        padding: 35px 30px 40px;
    }

    .kotak-info {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
        border-left: 4px solid #667eea;
        padding: 16px 18px;
        margin-bottom: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
    }

    .kotak-info strong {
        color: #667eea;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 8px;
    }

    .kotak-info small {
        font-size: 0.85rem;
        line-height: 1.8;
    }

    .kotak-info code {
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

    .tombol-login {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 14px;
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 1rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .tombol-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        background: linear-gradient(135deg, #5568d3 0%, #6a3f93 100%);
    }

    .tombol-login:active {
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

        .header-login {
            padding: 30px 20px;
        }

        .header-login h3 {
            font-size: 1.5rem;
        }

        .header-login p {
            font-size: 0.9rem;
        }

        .isi-login {
            padding: 25px 20px 30px;
        }

        .kotak-info {
            padding: 12px 14px;
            font-size: 0.8rem;
        }

        .kotak-info small {
            font-size: 0.75rem;
            line-height: 1.7;
        }

        .kotak-info code {
            font-size: 0.7rem;
            padding: 2px 6px;
        }

        .form-control {
            padding: 10px 14px;
            font-size: 0.9rem;
        }

        .tombol-login {
            padding: 12px;
            font-size: 0.95rem;
        }

        .footer-text {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 375px) {
        .header-login h3 {
            font-size: 1.35rem;
        }

        .kotak-info small {
            font-size: 0.7rem;
        }
    }

    /* Animation */
    .kartu-login {
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

<div class="login-container">
    <div class="kartu-login">
        {{-- Header/Judul Login --}}
        <div class="header-login">
            <h3>Sistem Deep Learning</h3>
            <p class="mb-0">Silakan login untuk melanjutkan</p>
        </div>

        {{-- Isi Form Login --}}
        <div class="isi-login">
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
            <div class="kotak-info">
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
                    <button type="submit" class="btn btn-primary tombol-login">
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
@endsection
