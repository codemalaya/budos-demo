@extends('layouts.app')

@section('content')
    <div class="auth-main v2">
        <div class="auth-wrapper">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center">
                            <h4 class="mb-3">Masuk</h4>
                            <p class="text-muted">Gunakan akun Anda untuk melanjutkan.</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Masuk</button>
                        </form>

                        <div class="text-center mt-3">
                            <span class="text-muted">Belum punya akun?</span>
                            <a href="{{ route('register') }}">Daftar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
