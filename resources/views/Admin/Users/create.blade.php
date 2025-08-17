@extends('layouts.admin.app')

@section('title', 'Crear Nuevo Usuario')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Formulario de Creación de Usuario</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            {{-- Muestra de errores de validación --}}
            @if ($errors->any())
            <div class="alert alert-danger text-white">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Usuario</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection