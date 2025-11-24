@extends('layouts.admin.app')

@section('title', 'Módulo de Fallas')

@push('styles')
<style>
    /* Choices.js custom styling */
    .choices {
        margin-bottom: 0;
    }
    
    .choices__inner {
        background-color: #fff;
        border: 1px solid #d2d6da;
        border-radius: 0.375rem;
        padding: 0.625rem 0.75rem;
        font-size: 0.875rem;
        min-height: 42px;
        transition: all 0.15s ease-in-out;
    }
    
    .choices__inner:hover {
        border-color: #b8bfc6;
    }
    
    .choices.is-focused .choices__inner,
    .choices.is-open .choices__inner {
        border-color: #5e72e4;
        box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.15);
    }
    
    .choices__list--dropdown {
        border: 1px solid #e9ecef;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        margin-top: 4px;
        z-index: 1060;
        background-color: #fff;
    }
    
    .choices__list--dropdown .choices__item {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        color: #525f7f;
        transition: all 0.15s ease;
    }
    
    .choices__list--dropdown .choices__item--selectable {
        padding-right: 1rem;
    }
    
    .choices__list--dropdown .choices__item--selectable.is-highlighted {
        background-color: #f7fafc;
        color: #5e72e4;
    }
    
    .choices__list--dropdown .choices__item--selectable:hover {
        background-color: #f7fafc;
    }
    
    .choices__input {
        background-color: transparent;
        font-size: 0.875rem;
        color: #525f7f;
        padding: 0;
        margin-bottom: 0;
    }
    
    .choices__input:focus {
        outline: none;
    }
    
    .choices__placeholder {
        opacity: 0.7;
        color: #8898aa;
    }
    
    /* Hide the dropdown placeholder that appears as first item */
    .choices__list--dropdown .choices__item:first-child.choices__placeholder {
        display: none;
    }
    
    /* Remove button (X) styling */
    .choices[data-type*='select-one'] .choices__button {
        background-image: url("data:image/svg+xml,%3Csvg width='21' height='21' viewBox='0 0 21 21' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23667085' fill-rule='evenodd'%3E%3Cpath d='M2.592.044l18.364 18.364-2.548 2.548L.044 2.592z'/%3E%3Cpath d='M0 18.364L18.364 0l2.548 2.548L2.548 20.912z'/%3E%3C/g%3E%3C/svg%3E");
        background-size: 8px;
        border-left: 1px solid #e3e8ee;
        opacity: 0.5;
        padding: 0 10px;
        margin-left: 10px;
    }
    
    .choices[data-type*='select-one'] .choices__button:hover,
    .choices[data-type*='select-one'] .choices__button:focus {
        opacity: 1;
    }
    
    /* Dropdown arrow */
    .choices[data-type*='select-one']::after {
        border-color: #8898aa transparent transparent;
        border-style: solid;
        border-width: 5px 5px 0;
        content: '';
        height: 0;
        pointer-events: none;
        position: absolute;
        right: 11.5px;
        top: 50%;
        margin-top: -2.5px;
        width: 0;
    }
    
    .choices[data-type*='select-one'].is-open::after {
        border-color: transparent transparent #8898aa;
        border-width: 0 5px 5px;
        margin-top: -7.5px;
    }
    
    /* No results / disabled items */
    .choices__item--disabled {
        color: #adb5bd;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }
</style>
@endpush

@section('content')

<!-- Tabla de fallas -->
<div class="row ">
    <div class="col-12">
        <div class="card shadow-lg border-0">
            <div class="card-header pb-0">
                <div class="row mt-2">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <h5 class="m-0 font-weight-bold">
                            <i class="fas fa-exclamation-triangle me-2"></i>Alertas de Stock
                        </h5>
                        <p class="mb-0 small">Productos con stock fuera de los límites configurados</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="d-grid d-md-flex gap-2 justify-content-md-end">
                            <a href="{{ route('admin.faults.history') }}" class="btn btn-secondary btn-md">
                                <i class="fas fa-history me-2"></i>Ver Historial
                            </a>
                            <a href="{{ route('admin.faults.configuration') }}" class="btn btn-primary btn-md">
                                <i class="fas fa-cogs me-2"></i>Configurar Límites
                            </a>
                            <button type="button" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#createFaultModal">
                                <i class="fas fa-plus me-2"></i>Crear Falla Manual
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive p-0 mt-2">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear falla manual -->
<div class="modal fade" id="createFaultModal" tabindex="-1" aria-labelledby="createFaultModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFaultModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Crear Falla Manual
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createFaultForm">
                    @csrf
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Producto *</label>
                        <select class="form-select" id="product_id" name="product_id" required>
                            <option value="">Seleccione un producto...</option>
                            @foreach(\App\Models\Product::orderBy('name')->get() as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->laboratory }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fault_type" class="form-label">Tipo de Falla *</label>
                        <select class="form-select" id="fault_type" name="fault_type" required>
                            <option value="">Seleccione un tipo...</option>
                            <option value="low_stock">Stock Bajo</option>
                            <option value="over_stock">Exceso de Stock</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="saveFaultBtn">
                    <i class="fas fa-save me-1"></i>Crear Falla
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
    
    <script>
       document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });

    // Initialize Choices.js on product select
    const productSelect = document.getElementById('product_id');
    const choices = new Choices(productSelect, {
        searchEnabled: true,
        searchPlaceholderValue: 'Buscar producto...',
        noResultsText: 'No se encontraron resultados',
        noChoicesText: 'No hay opciones disponibles',
        itemSelectText: 'Click para seleccionar',
        placeholder: true,
        placeholderValue: 'Seleccione un producto...',
        removeItemButton: true,
        shouldSort: false,
    });

    // Reset Choices when modal is hidden
    document.getElementById('createFaultModal').addEventListener('hidden.bs.modal', function() {
        choices.setChoiceByValue('');
        document.getElementById('fault_type').value = '';
    });

    // Handle manual fault creation
    document.getElementById('saveFaultBtn').addEventListener('click', async function () {
        const form = document.getElementById('createFaultForm');
        const formData = new FormData(form);

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const data = {
            product_id: formData.get('product_id'),
            fault_type: formData.get('fault_type'),
        };

        try {
            const response = await fetch('/admin/faults/manual', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Error al crear la falla');
            }

            Toast.fire({
                icon: 'success',
                title: result.message
            });

            // Close modal and reset form
            const modal = bootstrap.Modal.getInstance(document.getElementById('createFaultModal'));
            modal.hide();
            form.reset();
            choices.setChoiceByValue('');

            // Reload DataTable
            if (window.LaravelDataTables && window.LaravelDataTables['faults-alerts-table']) {
                window.LaravelDataTables['faults-alerts-table'].ajax.reload();
            }

        } catch (error) {
            console.error('Error:', error);
            Toast.fire({
                icon: 'error',
                title: error.message || 'Error al crear la falla'
            });
        }
    });

    // Use event delegation for dynamically loaded DataTable rows
    document.body.addEventListener('click', async function (e) {
        if (e.target.closest('.mark-reviewed-btn')) {
            const button = e.target.closest('.mark-reviewed-btn');
            const faultId = button.getAttribute('data-id');

            // Confirm before marking
            const result = await Swal.fire({
                title: '¿Marcar como revisada?',
                text: "Esta falla se quitará de la lista de pendientes",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, marcar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/faults/${faultId}/mark-reviewed`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Error al marcar como revisada');
                    }

                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });

                    // Reload the DataTable
                    if (window.LaravelDataTables && window.LaravelDataTables['faults-alerts-table']) {
                        window.LaravelDataTables['faults-alerts-table'].ajax.reload();
                    }

                } catch (error) {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al marcar la falla'
                    });
                }
            }
        }
    });
});

    </script>
@endpush

