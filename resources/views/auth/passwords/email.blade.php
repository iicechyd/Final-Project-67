@extends('layouts.layout')

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>เปลี่ยนรหัสผ่านบัญชีผู้ใช้งาน</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet"href="https://unpkg.com/bs-brain@2.0.4/components/password-resets/password-reset-5/assets/css/password-reset-5.css">
</head>

@section('content')
        <section class="p-3 p-md-4 p-xl-5">
            <div class="container">
                <div class="card border-light-subtle shadow-sm">
                    <div class="row g-0">
                        <div class="col-12 col-md-6" style="background-color: #ECECEC;">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="col-10 col-xl-8 py-3">
                                    <img class="img-fluid rounded mb-4" loading="lazy" src="/img/logo_mlc_sim1.png"
                                        width="500" height="80" alt="BootstrapBrain Logo">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card-body p-3 p-md-4 p-xl-5">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-5">
                                            <h2 class="h3">Password Reset</h2>
                                            <h3 class="fs-6 fw-normal text-secondary m-0">กรุณากรอกอีเมลที่เชื่อมโยงกับบัญชีผู้ใช้งานของคุณเพื่อกู้คืนรหัสผ่าน</h3>
                                        </div>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="row gy-3 gy-md-4 overflow-hidden">
                                        <div class="col-12">
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email" autofocus
                                                placeholder="name@example.com">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button class="btn bsb-btn-xl" style="background-color: #E6A732; color: #fff;" type="submit">เปลี่ยนรหัสผ่าน</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-12">
                                        <hr class="mt-5 mb-4 border-secondary-subtle">
                                        <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end">
                                            <a href="{{ route('login') }}" class="link-secondary text-decoration-none">Login</a>
                                            <a href="{{ route('register') }}" class="link-secondary text-decoration-none">Register</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection
