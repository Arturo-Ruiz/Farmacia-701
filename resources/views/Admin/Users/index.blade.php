@extends('layouts.admin.app')

@section('title', 'Usuarios')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Usuarios Registrados</h6>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                Crear Nuevo Usuario
            </a>
        </div>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-sm font-weight-bold opacity-7">ID</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7 ps-2">Nombre</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7 ps-2">Email</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr class="align-middle">
                        <td>
                            <p class="text-sm font-weight-bold mb-0 px-3">{{ $user->id }}</p>
                        </td>
                        <td>
                            <p class="text-sm font-weight-normal mb-0">{{ $user->name }}</p>
                        </td>
                        <td>
                            <p class="text-sm text-secondary mb-0">{{ $user->email }}</p>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning mb-0 mr-2">Editar</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mb-0" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <p class="text-secondary">No hay usuarios registrados.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer px-4 d-flex justify-content-end">
        {{ $users->links() }}
    </div>
</div>
@endsection