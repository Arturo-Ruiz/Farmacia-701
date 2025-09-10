@extends('layouts.admin.app')

@section('title', 'Laboratorios')

@section('content')

<div class="card shadow-lg border-0">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <div>
                <h5 class="m-0 font-weight-bold">
                    <i class="fas fa-flask me-2"></i>Laboratorios Registrados
                </h5>
                <p class="mb-0 small">Gestiona los laboratorios farmacéuticos</p>
            </div>
            <button id="createLaboratoryBtn" class="btn btn-primary btn-md m-0 shadow-sm">
                <i class="fa-solid fa-plus me-2"></i>
                Crear Nuevo Laboratorio
            </button>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive p-0 mt-2">
            {!! $dataTable->table() !!}
        </div>
    </div>
</div>
<div class="modal fade" id="laboratoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-bold" id="modalTitle"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="laboratoryForm" enctype="multipart/form-data">
                    <input type="hidden" id="laboratoryId">

                    <!-- Preview de imagen actual (para edición) -->
                    <div class="mb-3 text-center d-none" id="currentImageContainer">
                        <img id="currentImage" src="" alt="Logo actual" class="img-fluid rounded" style="max-height: 100px;">
                        <p class="text-muted mt-2">Logo actual</p>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label text-modal">Nombre</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                <input type="text" class="form-control" name="name" id="name" autocomplete="name">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="name_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="keyword" class="form-label text-modal">Palabra Clave</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="text" class="form-control" name="keyword" id="keyword" autocomplete="keyword">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="keyword_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label text-modal" id="logoLabel">Logo</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-image"></i></span>
                                <input type="file" class="form-control" name="logo" id="logo" accept="image/*">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="logo_error"></div>
                        <small class="text-muted" id="logoHelp">Formatos permitidos: JPG, PNG, GIF (máximo 2MB)</small>

                        <!-- Preview de nueva imagen -->
                        <div class="mt-3 text-center d-none" id="logoPreview">
                            <img id="logoPreviewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 100px;">
                            <p class="text-muted mt-2 mb-0">Vista previa del nuevo logo</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveLaboratoryBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}

<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        const laboratoryModal = new bootstrap.Modal(document.getElementById('laboratoryModal'));
        const laboratoryForm = document.getElementById('laboratoryForm');
        const laboratoryModalEl = document.getElementById('laboratoryModal');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const modalTitle = document.getElementById('modalTitle');

        let isEditing = false;
        let currentLaboratoryId = null;

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Preview de imagen nueva  
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('logoPreview');
            const previewImg = document.getElementById('logoPreviewImg');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('d-none');
            }
        });

        const resetForm = () => {
            laboratoryForm.reset();
            document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
            document.getElementById('logoPreview').classList.add('d-none');
            document.getElementById('currentImageContainer').classList.add('d-none');
            currentLaboratoryId = null;
        };

        const openModalForCreate = () => {
            resetForm();
            isEditing = false;
            modalTitle.textContent = 'Crear Nuevo Laboratorio';
            document.getElementById('logoLabel').textContent = 'Logo';
            document.getElementById('logoHelp').textContent = 'Formatos permitidos: JPG, PNG, GIF (máximo 2MB)';
            laboratoryModal.show();
        };

        const openModalForEdit = async (laboratoryId) => {
            resetForm();
            isEditing = true;
            currentLaboratoryId = laboratoryId;

            try {
                const response = await fetch(`/admin/laboratories/${laboratoryId}`);
                if (!response.ok) throw new Error('Laboratorio no encontrado');
                const laboratory = await response.json();

                modalTitle.textContent = 'Editar Laboratorio';
                document.getElementById('name').value = laboratory.name;
                document.getElementById('keyword').value = laboratory.keyword;
                document.getElementById('logoLabel').textContent = 'Nuevo Logo (opcional)';
                document.getElementById('logoHelp').textContent = 'Si no seleccionas un logo, se mantendrá el actual';

                // Mostrar logo actual si existe  
                if (laboratory.logo) {
                    const currentImageContainer = document.getElementById('currentImageContainer');
                    const currentImage = document.getElementById('currentImage');
                    currentImage.src = `/storage/${laboratory.logo}`;
                    currentImageContainer.classList.remove('d-none');
                }

                laboratoryModal.show();
            } catch (error) {
                console.error('Error al cargar laboratorio:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error al cargar los datos del laboratorio'
                });
            }
        };

        const saveLaboratory = async () => {
            const formData = new FormData(laboratoryForm);
            const url = isEditing ? `/admin/laboratories/${currentLaboratoryId}` : '/admin/laboratories';

            // Para PUT requests con FormData, necesitamos usar POST con _method  
            if (isEditing) {
                formData.append('_method', 'PUT');
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    if (result.errors) {
                        Object.keys(result.errors).forEach(key => {
                            document.getElementById(`${key}_error`).textContent = result.errors[key][0];
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: result.message || 'Error al guardar'
                        });
                    }
                    return;
                }

                laboratoryModal.hide();
                resetForm();
                window.LaravelDataTables['laboratories-table'].ajax.reload();
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });

            } catch (error) {
                console.error('Error al guardar:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error de conexión'
                });
            }
        };

        const deleteLaboratory = async (laboratoryId) => {
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/laboratories/${laboratoryId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    });

                    const data = await response.json();
                    window.LaravelDataTables['laboratories-table'].ajax.reload();
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                } catch (error) {
                    console.error('Error al eliminar:', error);
                    Swal.fire('Error', 'No se pudo eliminar el laboratorio.', 'error');
                }
            }
        };

        // Event Listeners  
        document.getElementById('createLaboratoryBtn').addEventListener('click', openModalForCreate);
        document.getElementById('saveLaboratoryBtn').addEventListener('click', saveLaboratory);

        laboratoryModalEl.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                saveLaboratory();
            }
        });

        laboratoryModalEl.addEventListener('hidden.bs.modal', event => {
            document.body.focus();
        });

        $('#laboratories-table').on('click', '.edit-btn', function() {
            openModalForEdit($(this).data('id'));
        });

        $('#laboratories-table').on('click', '.delete-btn', function() {
            deleteLaboratory($(this).data('id'));
        });
    });
</script>
@endpush    