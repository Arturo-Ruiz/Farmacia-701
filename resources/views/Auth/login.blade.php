<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    {{-- Aquí puedes añadir CSS --}}
</head>
<body>
    <h2>Acceso al Panel</h2>

    {{-- Bloque para mostrar errores de validación --}}
    @error('email')
        <div style="color: red;">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label for="email">Correo Electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <br>
        <div>
            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" required>
        </div>
        <br>
        <div>
            <button type="submit">Entrar</button>
        </div>
    </form>
</body>
</html>