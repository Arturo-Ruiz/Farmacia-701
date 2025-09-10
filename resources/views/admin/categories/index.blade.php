@extends('layouts.admin.app')

@section('title', 'Categorías')

@section('content')

<div class="card shadow-lg border-0">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <div>
                <h5 class="m-0 font-weight-bold">
                    <i class="fa-solid fa-tags me-2"></i>Categorías Registradas
                </h5>
                <p class="mb-0 small">Gestiona las categorías de los productos</p>
            </div>
            <button id="createCategoryBtn" class="btn btn-primary btn-md m-0">
                <i class="fa-solid fa-plus"></i> Crear Nueva Categoría
            </button>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive p-0 mt-2">
            {!! $dataTable->table() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-bold" id="modalTitle"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <input type="hidden" id="categoryId">
                    <div class="mb-3">
                        <label for="id" class="form-label text-modal">ID</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                <input type="number" class="form-control" name="id" id="id" autocomplete="off">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="id_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label text-modal">Nombre</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control" name="name" id="name" autocomplete="off">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="name_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveCategoryBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}

<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'))
        const categoryForm = document.getElementById('categoryForm');
        const categoryModalEl = document.getElementById('categoryModal');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const modalTitle = document.getElementById('modalTitle');

        let isEditing = false;
        let currentCategoryId = null;

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        const resetForm = () => {
            categoryForm.reset();
            document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
            currentCategoryId = null;
        };

        const openModalForCreate = () => {
            resetForm();
            isEditing = false;
            modalTitle.textContent = 'Crear Nueva Categoría';
            document.getElementById('id').disabled = false;
            categoryModal.show();
        };

        const openModalForEdit = async (categoryId) => {
            resetForm();
            isEditing = true;
            currentCategoryId = categoryId;

            try {
                const response = await fetch(`/admin/categories/${categoryId}`);
                if (!response.ok) throw new Error('Categoría no encontrada');
                const category = await response.json();

                modalTitle.textContent = 'Editar Categoría';
                document.getElementById('id').value = category.id;
                document.getElementById('id').disabled = true;
                document.getElementById('name').value = category.name;

                categoryModal.show();
            } catch (error) {
                console.error('Error al cargar datos:', error);
            }
        };

        const saveCategory = async () => {
            const url = isEditing ? `/admin/categories/${currentCategoryId}` : '/admin/categories';
            const method = isEditing ? 'PUT' : 'POST';

            const formData = {
                id: document.getElementById('id').value,
                name: document.getElementById('name').value,
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

                categoryModal.hide();
                window.LaravelDataTables['categories-table'].ajax.reload();
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });

            } catch (error) {
                console.error('Error al guardar:', error);
            }
        };

        const deleteCategory = async (categoryId) => {
            const result = await Swal.fire({
                title: '¿Estás seguro de eliminar esta categoría?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#5e72e4',
                cancelButtonColor: '#f5365c',
                confirmButtonText: 'Sí, ¡eliminar!',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/categories/${categoryId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Error al eliminar.');

                    window.LaravelDataTables['categories-table'].ajax.reload();
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                } catch (error) {
                    console.error('Error al eliminar:', error);
                    Swal.fire('Error', 'No se pudo eliminar la categoría.', 'error');
                }
            }
        };

        document.getElementById('createCategoryBtn').addEventListener('click', openModalForCreate);
        document.getElementById('saveCategoryBtn').addEventListener('click', saveCategory);

        categoryModalEl.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                saveCategory();
            }
        });

        categoryModalEl.addEventListener('hidden.bs.modal', event => {
            document.body.focus();
        });

        $('#categories-table').on('click', '.edit-btn', function() {
            openModalForEdit($(this).data('id'));
        });

        $('#categories-table').on('click', '.delete-btn', function() {
            deleteCategory($(this).data('id'));
        });
    });
</script>
@endpush