@extends('layouts.admin.app')

@section('title', 'Gestionar Usuarios')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Usuarios del Sistema</h5>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Nuevo Usuario
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-flush">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No hay usuarios registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $users->links() }}
    </div>
</div>
@endsection