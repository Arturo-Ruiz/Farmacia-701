@extends('layouts.admin.app')

@section('title', 'Historial de Fallas')

@section('content')

<div class="card shadow-lg border-0">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <div>
                <h5 class="m-0 font-weight-bold">
                    <i class="fas fa-history me-2"></i>Historial de Fallas
                </h5>
                <p class="mb-0 small">Registro completo de todas las fallas detectadas y revisadas</p>
            </div>
            <div>
                <a href="{{ route('admin.faults.index') }}" class="btn btn-secondary btn-md m-0">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Alertas
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive p-0 mt-2">
            {!! $dataTable->table() !!}
        </div>
    </div>
</div>

@endsection

@push('scripts')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
@endpush
