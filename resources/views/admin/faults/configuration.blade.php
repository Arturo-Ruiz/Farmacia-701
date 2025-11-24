@extends('layouts.admin.app')

@section('title', 'Configuración de Stock')

@section('content')

    <div class="card shadow-lg border-0">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div>
                    <h5 class="m-0 font-weight-bold">
                        <i class="fas fa-cogs me-2"></i>Configuración de Stock
                    </h5>
                    <p class="mb-0 small">Establece los límites de stock para tus productos</p>
                </div>
                <div>
                    <a href="{{ route('admin.faults.index') }}" class="btn btn-secondary btn-md m-0">
                        <i class="fas fa-arrow-left me-2"></i>Volver a Fallas
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        // Use event delegation for the save button since DataTable rows are dynamic
        document.body.addEventListener('click', async function(e) {
            if (e.target.closest('.save-btn')) {
                const button = e.target.closest('.save-btn');
                const id = button.getAttribute('data-id');
                const row = button.closest('tr');
                const minStock = row.querySelector('.min-stock-input').value;
                const maxStock = row.querySelector('.max-stock-input').value;

                try {
                    const response = await fetch(`/admin/faults/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            min_stock: minStock,
                            max_stock: maxStock
                        })
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || 'Error al actualizar');
                    }

                    Toast.fire({
                        icon: 'success',
                        title: result.message
                    });

                } catch (error) {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al actualizar los límites'
                    });
                }
            }
        });
    });
</script>
@endpush
