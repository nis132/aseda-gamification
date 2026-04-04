@extends('layouts.app')

@section('title', 'Login')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #4f46e5, #7c3aed, #a855f7);
        min-height: 100vh;
    }

    .card {
        border-radius: 20px;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        transition: 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px) scale(1.01);
    }

    .text-primary {
        color: #6d28d9 !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6d28d9, #a855f7);
        border: none;
        border-radius: 30px;
        transition: 0.3s;
    }

    .btn-primary:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(124, 58, 237, 0.4);
    }

    .input-group-text {
        background: #ede9fe;
        border: none;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #ddd;
        transition: 0.3s;
    }

    .form-control:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 10px rgba(124, 58, 237, 0.3);
    }

    /* animasi masuk */
    .card {
        animation: fadeInUp 0.8s ease;
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

<div style="width: 100%; max-width: 400px; margin: auto;">
    <div class="card shadow-lg border-0">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="fas fa-sign-in-alt fa-4x text-primary mb-3"></i>
                <h2 class="h4 fw-bold text-primary">Masuk ke Sistem</h2>
                <p class="text-muted">Web Pembelajaran Berbasis Gamifikasi</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                @error('username')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="mb-4">
                    <label class="form-label fw-bold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                               name="username" value="{{ old('username') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                </button>
            </form>
            <hr class="my-4">
        </div>
    </div>
</div>

@endsection