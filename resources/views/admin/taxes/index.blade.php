@extends('layouts.admin.app')

@section('title', 'Impuestos')

@section('content')

<div class="card shadow-lg border-0">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <div>
                <h5 class="m-0 font-weight-bold">
                    <i class="fa-solid fa-file-invoice-dollar me-2"></i>Impuestos Registrados
                </h5>
                <p class="mb-0 small">Gestiona los impuestos de los productos</p>
            </div>
            <button id="createTaxBtn" class="btn btn-primary btn-md m-0">
                <i class="fa-solid fa-plus"></i>
                Crear Nuevo Impuesto
            </button>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive p-0 mt-2">
            {!! $dataTable->table() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="taxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-bold" id="modalTitle"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="taxForm">
                    <input type="hidden" id="taxId">
                    <div class="mb-3">
                        <label for="name" class="form-label text-modal">Nombre</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control" name="name" id="name" autocomplete="name">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="name_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="value" class="form-label text-modal">Valor</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                <input type="text" step="0.01" class="form-control" name="value" id="value" autocomplete="value">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="value_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveTaxBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}


<script>
    document.addEventListener('DOMContentLoaded', function() {

        $('#value').mask('00.00', {
            reverse: true
        });

        const taxModal = new bootstrap.Modal(document.getElementById('taxModal'));
        const taxModalEl = document.getElementById('taxModal');
        const taxForm = document.getElementById('taxForm');
        const modalTitle = document.getElementById('modalTitle');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let isEditing = false;
        let currentTaxId = null;

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        const resetForm = () => {
            taxForm.reset();
            document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
            currentTaxId = null;
        };

        const openModalForCreate = () => {
            resetForm();
            isEditing = false;
            modalTitle.textContent = 'Crear Nuevo Impuesto';
            taxModal.show();
        };

        const openModalForEdit = async (taxId) => {
            resetForm();
            isEditing = true;
            currentTaxId = taxId;

            try {
                const response = await fetch(`/admin/taxes/${taxId}`);
                if (!response.ok) throw new Error('Impuesto no encontrado');
                const tax = await response.json();

                modalTitle.textContent = 'Editar Impuesto';
                document.getElementById('name').value = tax.name;
                document.getElementById('value').value = tax.value;

                taxModal.show();
            } catch (error) {
                console.error('Error al cargar datos:', error);
            }
        };

        const saveTax = async () => {
            const url = isEditing ? `/admin/taxes/${currentTaxId}` : '/admin/taxes';
            const method = isEditing ? 'PUT' : 'POST';

            const formData = {
                name: document.getElementById('name').value,
                value: document.getElementById('value').value,
            };

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (!response.ok) {
                    if (response.status === 422) {
                        document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
                        Object.keys(result.errors).forEach(key => {
                            document.getElementById(`${key}_error`).textContent = result.errors[key][0];
                        });
                    }
                    return;
                }

                taxModal.hide();
                window.LaravelDataTables['taxes-table'].ajax.reload();
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });

            } catch (error) {
                console.error('Error al guardar:', error);
            }
        };

        const deleteTax = async (taxId) => {
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/taxes/${taxId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    });

                    const data = await response.json();
                    window.LaravelDataTables['taxes-table'].ajax.reload();
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                } catch (error) {
                    console.error('Error al eliminar:', error);
                    Swal.fire('Error', 'No se pudo eliminar el impuesto.', 'error');
                }
            }
        };

        document.getElementById('createTaxBtn').addEventListener('click', openModalForCreate);
        document.getElementById('saveTaxBtn').addEventListener('click', saveTax);

        taxModalEl.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                saveTax();
            }
        });

        taxModalEl.addEventListener('hidden.bs.modal', event => {
            document.body.focus();
        });

        $('#taxes-table').on('click', '.edit-btn', function() {
            openModalForEdit($(this).data('id'));
        });

        $('#taxes-table').on('click', '.delete-btn', function() {
            deleteTax($(this).data('id'));
        });
    });
</script>
@endpush