@extends('layouts.admin.app')

@section('title', 'Publicidades')

@section('content')

<div class="card shadow-lg border-0">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <div>
                <h5 class="m-0 font-weight-bold">
                    <i class="fa-solid fa-rectangle-ad me-2"></i>Publicidades Registradas
                </h5>
                <p class="mb-0 small">Gestiona las publicidades del sistema</p>
            </div> <!-- <button id="uploadImagesBtn" class="btn btn-success btn-md m-0">
                <i class="fa-solid fa-images"></i> Cargar Anuncios
            </button> -->
        </div>
    </div>
    <div class="card-body">
        @if($ads->count() > 0)
        <div class="row" id="ads-grid">
            @foreach($ads as $index => $ad)
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4" data-ad-id="{{ $ad->id }}">
                <div class="card shadow-sm h-100">
                    <div class="position-relative">
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-primary fs-6">#{{ $ad->id }}</span>
                        </div>

                        <!-- <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 delete-ad"
                            data-id="{{ $ad->id }}" title="Eliminar anuncio">
                            <i class="fas fa-trash"></i>
                        </button> -->

                        <div class="card-img-top-container" style="aspect-ratio: 1/1; overflow: hidden;">
                            <img src="{{ $ad->img_url }}"
                                alt="Anuncio #{{ $index + 1 }}"
                                class="card-img-top w-100 h-100"
                                style="object-fit: contain;">
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="card-title mb-0 text-primary">Anuncio #{{ $ad->id }}</h6>
                            <small class="text-muted">ID: {{ $ad->id }}</small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">
                                <i class="fas fa-folder me-1"></i>
                                {{ $ad->img }}
                            </small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $ad->created_at->format('d/m/Y') }}
                            </small>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm edit-ad"
                                    data-id="{{ $ad->id }}" title="Editar anuncio"
                                    style="background: linear-gradient(45deg, #28a745, #20c997); border: none; color: white;">
                                    <i class="fas fa-pencil me-1"></i>
                                </button>
                                <button type="button" class="btn btn-sm view-full"
                                    data-bs-toggle="modal" data-bs-target="#imageModal"
                                    data-src="{{ $ad->img_url }}"
                                    data-title="Anuncio #{{ $index + 1 }}"
                                    style="background: linear-gradient(45deg, #007bff, #6f42c1); border: none; color: white;">
                                    <i class="fas fa-eye me-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-rectangle-ad fa-4x text-muted"></i>
            </div>
            <h5 class="text-muted">No hay anuncios registrados</h5>
            <p class="text-muted">Comienza cargando tu primer anuncio</p>
            <button class="btn btn-success" onclick="document.getElementById('uploadImagesBtn').click()">
                <i class="fa-solid fa-images me-2"></i>Cargar Primer Anuncio
            </button>
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-bold" id="modalTitle">
                    <i class="fas fa-images me-2"></i>Cargar Anuncios
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    <input type="hidden" id="adId">

                    <div class="mb-3 text-center d-none" id="currentImageContainer">
                        <img id="currentImage" src="" alt="Imagen actual"
                            class="img-fluid rounded" style="max-height: 200px;">
                        <p class="text-muted mt-2">Imagen actual</p>
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label text-modal" id="imageLabel">Seleccionar Imágenes</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-images"></i></span>
                                <input type="file" class="form-control" name="images[]" id="images"
                                    accept="image/*">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="images_error"></div>
                        <small class="text-muted" id="imageHelp">
                            Formatos permitidos: JPG, PNG, GIF (máximo 10MB por imagen)<br>
                            <strong>Las imágenes se redimensionarán a 500x500 píxeles</strong>
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="uploadBtn">
                    <i class="fas fa-upload me-1"></i>Cargar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="imageModalTitle">Ver Anuncio</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imageModalImg" src="" alt="" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        const uploadForm = document.getElementById('uploadForm');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let isEditing = false;
        let currentAdId = null;

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        const resetForm = () => {
            uploadForm.reset();
            document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
            currentAdId = null;
        };

        const openModalForCreate = () => {
            resetForm();
            isEditing = false;
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-images me-2"></i>Cargar Anuncios';
            document.getElementById('imageLabel').textContent = 'Seleccionar Imágenes';
            document.getElementById('images').setAttribute('multiple', 'multiple');
            document.getElementById('images').setAttribute('name', 'images[]');
            document.getElementById('currentImageContainer').classList.add('d-none');
            document.getElementById('uploadBtn').innerHTML = '<i class="fas fa-upload me-1"></i>Cargar';
            document.getElementById('imageHelp').innerHTML = 'Formatos permitidos: JPG, PNG, GIF (máximo 10MB por imagen)<br><strong>Las imágenes se redimensionarán a 500x500 píxeles</strong>';
            uploadModal.show();
        };

        const openModalForEdit = async (adId) => {
            resetForm();
            isEditing = true;
            currentAdId = adId;

            try {
                const response = await fetch(`/admin/ads/${adId}`);
                if (!response.ok) throw new Error('Anuncio no encontrado');
                const ad = await response.json();

                document.getElementById('modalTitle').innerHTML = '<i class="fas fa-pencil me-2"></i>Editar Anuncio';
                document.getElementById('imageLabel').textContent = 'Nueva Imagen (opcional)';
                document.getElementById('images').removeAttribute('multiple');
                document.getElementById('images').setAttribute('name', 'image');
                document.getElementById('currentImage').src = `${window.location.origin}/storage/ads/${ad.img}`;
                document.getElementById('currentImageContainer').classList.remove('d-none');
                document.getElementById('uploadBtn').innerHTML = '<i class="fas fa-save me-1"></i>Actualizar';
                document.getElementById('imageHelp').innerHTML = 'Si no seleccionas una imagen, se mantendrá la actual<br><strong>La nueva imagen se redimensionará a 500x500 píxeles</strong>';

                uploadModal.show();
            } catch (error) {
                console.error('Error al cargar anuncio:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error al cargar el anuncio'
                });
            }
        };

        const uploadImages = async () => {
            const formData = new FormData(uploadForm);
            const fileInput = document.getElementById('images');

            if (!isEditing && !fileInput.files.length) {
                document.getElementById('images_error').textContent = 'Por favor selecciona al menos una imagen';
                return;
            }

            try {
                let url, method;
                if (isEditing) {
                    url = `/admin/ads/${currentAdId}`;
                    method = 'POST';
                    formData.append('_method', 'PUT');

                    if (fileInput.files.length > 0) {
                        formData.delete('images[]');
                        formData.append('image', fileInput.files[0]);
                    } else {

                        Toast.fire({
                            icon: 'info',
                            title: 'No se seleccionó ninguna imagen nueva'
                        });
                        uploadModal.hide();
                        return;
                    }
                } else {
                    url = '/admin/ads/upload-images';
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
                    if (result.errors) {
                        Object.keys(result.errors).forEach(key => {
                            const errorElement = document.getElementById(`${key}_error`) || document.getElementById('images_error');
                            if (errorElement) {
                                errorElement.textContent = result.errors[key][0];
                            }
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: result.error || 'Error al procesar las imágenes'
                        });
                    }
                    return;
                }

                uploadModal.hide();
                resetForm();
                window.location.reload();

            } catch (error) {
                console.error('Error:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error al procesar las imágenes'
                });
            }
        };

        const deleteAd = async (e) => {
            const adId = e.target.closest('.delete-ad').dataset.id;

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
                    const response = await fetch(`/admin/ads/${adId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        window.location.reload();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.error || 'Error al eliminar el anuncio'
                        });
                    }
                } catch (error) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al eliminar el anuncio'
                    });
                }
            }
        };

        // document.getElementById('uploadImagesBtn').addEventListener('click', openModalForCreate);
        document.getElementById('uploadBtn').addEventListener('click', uploadImages);

        // document.querySelectorAll('.delete-ad').forEach(btn => {
        //     btn.addEventListener('click', deleteAd);
        // });

        document.querySelectorAll('.edit-ad').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const adId = e.target.closest('.edit-ad').dataset.id;
                openModalForEdit(adId);
            });
        });

        document.querySelectorAll('.view-full').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const src = e.target.closest('.view-full').dataset.src;
                const title = e.target.closest('.view-full').dataset.title;
                document.getElementById('imageModalImg').src = src;
                document.getElementById('imageModalTitle').textContent = title;
            });
        });
    });
</script>
@endpush