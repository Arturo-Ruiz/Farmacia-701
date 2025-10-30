@extends('layouts.admin.app')

@section('title', 'Carousel')

@section('content')
<div class="card shadow-lg border-0">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <div>
                <h5 class="m-0 font-weight-bold">
                    <i class="fas fa-images me-2"></i> Imágenes del Carousel
                </h5>
                <p class="mb-0 small">Gestiona las imágenes del Carousel</p>
            </div>
            <button id="createCarouselBtn" class="btn btn-primary btn-md m-0 shadow-sm">
                <i class="fa-solid fa-plus me-1"></i>
                <span class="font-weight-bold">Nueva Imagen del Carousel</span>
            </button>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive p-0 mt-2">
            {!! $dataTable->table(['class' => 'table align-items-center']) !!}
        </div>
    </div>
</div>

<div class="modal fade" id="carouselModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl">
            <div class="modal-header bg-gradient-primary border-0">
                <h6 class="modal-title text-white font-weight-bold" id="modalTitle">
                    <i class="fas fa-images me-2"></i>Gestionar Imagen
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="carouselForm" enctype="multipart/form-data">
                    <input type="hidden" id="carouselId">

                    <!-- Preview de imagen actual -->
                    <div class="mb-4 text-center d-none" id="currentImageContainer">
                        <div class="position-relative d-inline-block">
                            <img id="currentImage" src="" alt="Imagen actual"
                                class="img-fluid rounded-3 shadow-lg"
                                style="max-height: 200px; border: 3px solid #e9ecef;">
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-primary">Actual</span>
                            </div>
                        </div>
                        <p class="text-muted mt-2 mb-0">
                            <i class="fas fa-info-circle me-1"></i>Imagen actual del carousel
                        </p>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label font-weight-bold text-dark">
                            <i class="fas fa-cloud-upload-alt me-2 text-primary"></i>Seleccionar Nueva Imagen
                        </label>
                        <div class="upload-area border-2 border-dashed border-primary rounded-3 p-4 text-center position-relative">
                            <input type="file" class="form-control position-absolute w-100 h-100 opacity-0"
                                name="image" id="image" accept="image/*" style="cursor: pointer;">
                            <div class="upload-content">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                <h6 class="text-primary font-weight-bold">Arrastra tu imagen aquí</h6>
                                <p class="text-muted mb-2">o haz clic para seleccionar</p>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    JPG, PNG, GIF (máx. 10MB) • Se redimensionará a 1280x350px
                                </small>
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-2" id="image_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary shadow-sm" id="saveCarouselBtn">
                    <i class="fas fa-save me-1"></i>Guardar Imagen
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}

<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        const carouselModal = new bootstrap.Modal(document.getElementById('carouselModal'));
        const carouselForm = document.getElementById('carouselForm');
        const carouselModalEl = document.getElementById('carouselModal');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const modalTitle = document.getElementById('modalTitle');

        let isEditing = false;
        let currentCarouselId = null;

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
            carouselForm.reset();
            document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
            currentCarouselId = null;
        };

        const openModalForCreate = () => {
            resetForm();
            isEditing = false;
            modalTitle.textContent = 'Crear Nueva Imagen';
            document.getElementById('currentImageContainer').classList.add('d-none');
            carouselModal.show();
        };

        const openModalForEdit = async (carouselId) => {
            resetForm();
            isEditing = true;
            currentCarouselId = carouselId;

            try {
                const response = await fetch(`/admin/carousels/${carouselId}`);
                if (!response.ok) throw new Error('Imagen no encontrada');
                const carousel = await response.json();

                modalTitle.textContent = 'Editar Imagen';
                document.getElementById('currentImage').src = `${window.location.origin}/storage/carousels/${carousel.img}`;
                document.getElementById('currentImageContainer').classList.remove('d-none');
                carouselModal.show();
            } catch (error) {
                console.error('Error al cargar datos:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error al cargar la imagen'
                });
            }
        };

        const saveCarousel = async () => {
            const formData = new FormData(carouselForm);

            try {
                let url, method;
                if (isEditing) {
                    url = `/admin/carousels/${currentCarouselId}`;
                    method = 'POST';
                    formData.append('_method', 'PUT');

                    const fileInput = document.getElementById('image');
                    if (fileInput.files.length === 0) {
                        Toast.fire({
                            icon: 'info',
                            title: 'Selecciona una imagen para actualizar'
                        });
                        return;
                    }
                } else {
                    url = '/admin/carousels';
                    method = 'POST';
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
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

                carouselModal.hide();
                window.LaravelDataTables['carousels-table'].ajax.reload();
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });

            } catch (error) {
                console.error('Error al guardar:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error al procesar la solicitud'
                });
            }
        };

        const deleteCarousel = async (carouselId) => {
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/carousels/${carouselId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    });

                    const data = await response.json();
                    window.LaravelDataTables['carousels-table'].ajax.reload();
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                } catch (error) {
                    console.error('Error al eliminar:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al eliminar la imagen'
                    });
                }
            }
        };

        // Event listeners  
        // document.getElementById('createCarouselBtn').addEventListener('click', openModalForCreate);
        document.getElementById('saveCarouselBtn').addEventListener('click', saveCarousel);

        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const uploadContent = document.querySelector('.upload-content');

            if (file) {
                uploadContent.innerHTML = `  
            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>  
            <h6 class="text-success font-weight-bold">¡Imagen seleccionada!</h6>  
            <p class="text-muted mb-0">${file.name}</p>  
            <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>  
        `;
            }
        });

        carouselModalEl.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                saveCarousel();
            }
        });

        carouselModalEl.addEventListener('hidden.bs.modal', event => {
            document.body.focus();
        });

        $('#carousels-table').on('click', '.edit-btn', function() {
            openModalForEdit($(this).data('id'));
        });

        $('#carousels-table').on('click', '.delete-btn', function() {
            deleteCarousel($(this).data('id'));
        });


    });
</script>
@endpush