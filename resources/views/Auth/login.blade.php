@extends('layouts.guest')

@section('title', 'Iniciar Sesión')

@section('content')
<main class="main-content mt-0">
    <section class="min-vh-100 d-flex align-items-center login-background-section">
        <div class="animated-background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>
        <span class="mask bg-gradient-dark opacity-6"></span>

        <div class="container" style="z-index: 1;">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-7">
                    <div class="card shadow-lg">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">

                                <div class="mb-3">
                                    <img src="{{ asset('img/logo.png') }}" alt="Logo de la Farmacia 701" style="max-height: 80px;">
                                </div>

                                <h4 class="font-weight-bolder">¡Bienvenido!</h4>
                                <p class="mb-0">Ingresa tus credenciales para continuar.</p>
                            </div>

                            <form role="form" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="input-wrapper mb-3">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required autocomplete="email">
                                    </div>
                                </div>
                                @error('email')
                                <div class="text-danger text-xs mt-n2 mb-2">{{ $message }}</div>
                                @enderror

                                <div class="input-wrapper mb-3">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Contraseña" required autocomplete="current-password">
                                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Recuérdame</label>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-lg bg-gradient-dark w-100 mt-4 mb-0">Iniciar Sesión</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection