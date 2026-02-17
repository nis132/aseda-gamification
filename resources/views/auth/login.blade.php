@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5 col-xl-4">
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
                <div class="text-center">
                    <p class="text-muted mb-0">Demo Akun:</p>
                    <div class="small">
                        <div><strong>Admin:</strong> admin / password</div>
                        <div><strong>Guru:</strong> budi.guru / password</div>
                        <div><strong>Siswa:</strong> andi7a / password</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
