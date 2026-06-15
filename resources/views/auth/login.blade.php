@extends('layouts.app')

@section('title', 'Masuk Petualangan - ASEDA')

@section('content')

<style>
    .auth-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7ff 0%, #e0e7ff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
    }

    .auth-page::after {
        content: "";
        position: absolute;
        width: 400px;
        height: 400px;
        background: rgba(99, 102, 241, 0.1);
        border-radius: 50%;
        top: -100px;
        right: -100px;
        z-index: 0;
    }

    .auth-card {
        width: 100%;
        max-width: 400px;
        border-radius: 24px;
        background: #ffffff;
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease;
        animation: fadeInUp 0.8s ease;
    }

    .auth-card:hover {
        transform: translateY(-5px);
    }

    .auth-title {
        color: #4338ca;
        font-weight: 800;
    }

    .auth-subtitle {
        color: #64748b;
        font-size: 0.9rem;
    }

    .input-group-text {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-right: none;
        color: #6366f1;
        border-radius: 12px 0 0 12px;
    }

    .form-control {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0 12px 12px 0;
        padding: 12px;
    }

    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background: #fff;
    }

    .btn-adventure {
        background: #007bff;
        color: white;
        border: none;
        border-radius: 14px;
        padding: 14px;
        font-weight: 700;
        width: 100%;
        transition: all 0.3s;
        box-shadow: 0 8px 15px rgba(0, 123, 255, 0.2);
    }

    .btn-adventure:hover {
        background: #0069d9;
        transform: scale(1.02);
    }

    .alert-gamified {
        background: #fff1f2;
        border: 1px solid #fecaca;
        color: #e11d48;
        border-radius: 12px;
        font-size: 0.85rem;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="auth-page">
    <div class="card auth-card">
        <div class="card-body p-4 p-md-5">

            {{-- HEADER --}}
            <div class="text-center mb-4">
                <img src="{{ asset('storage/logo_aseda.webp') }}" 
                     alt="Logo" 
                     class="mb-3"
                     style="width: 75px; height:auto;">
                
                <h2 class="auth-title h4 mb-1">Masuk Petualangan</h2>
                <p class="auth-subtitle">Akses portal belajar ASEDA</p>
            </div>

            {{-- SESSION ERROR (misalnya login gagal) --}}
            @if (session('error'))
                <div class="alert alert-gamified p-3 mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- VALIDATION ERROR --}}
            @if ($errors->any())
                <div class="alert alert-gamified p-3 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">USERNAME</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" 
                               name="username" 
                               class="form-control" 
                               placeholder="Masukkan username"
                               value="{{ old('username') }}"
                               required autofocus>
                    </div>
                    @error('username')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-secondary">PASSWORD</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" 
                               name="password" 
                               class="form-control" 
                               placeholder="Masukkan password"
                               required>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-adventure">
                    Mulai Petualangan
                </button>
            </form>

        </div>
    </div>
</div>

@endsection