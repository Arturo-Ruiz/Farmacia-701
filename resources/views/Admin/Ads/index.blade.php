@extends('layouts.admin.app')

@section('title', 'Anuncios')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <h5 class="text-header m-0">Anuncios Registrados</h5>
            <button id="uploadImagesBtn" class="btn btn-success btn-md m-0">
                <i class="fa-solid fa-images"></i> Cargar Anuncios
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row" id="ads-grid">
            @foreach($ads as $ad)
            <div class="col-md-3 col-sm-6 mb-3" data-ad-id="{{ $ad->id }}">
                <div class="image-preview position-relative">
                    <img src="{{ $ad->img_url }}" alt="Ad {{ $ad->id }}" class="img-fluid">
                    <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 delete-ad" data-id="{{ $ad->id }}">
                        <i class="fas fa-trash"></i>
                    </button>
                    <div class="image-info">
                        <strong>ID: {{ $ad->id }}</strong><br>
                        <small>{{ $ad->img }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-bold">Cargar Anuncios</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="images" class="form-label text-modal">Seleccionar Imágenes</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-images"></i></span>
                                <input type="file" class="form-control" name="images[]" id="images"
                                    accept="image/*" multiple>
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="images_error"></div>
                        <small class="text-muted">
                            Formatos permitidos: JPG, PNG, GIF (máximo 10MB por imagen)<br>
                            <strong>Las imágenes se redimensionarán a 500x500 píxeles</strong>
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="uploadBtn">Cargar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
        const uploadForm = document.getElementById('uploadForm');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
        };

        const uploadImages = async () => {
            const formData = new FormData(uploadForm);
            const fileInput = document.getElementById('images');

            if (!fileInput.files.length) {
                document.getElementById('images_error').textContent = 'Por favor selecciona al menos una imagen';
                return;
            }

            try {
                const response = await fetch('/admin/ads/upload-images', {
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
                            const errorElement = document.getElementById(`${key}_error`);
                            if (errorElement) {
                                errorElement.textContent = result.errors[key][0];
                            }
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: result.error || 'Error al cargar las imágenes'
                        });
                    }
                    return;
                }

                uploadModal.hide();
                resetForm();

                if (result.images) {
                    result.images.forEach(image => {
                        addImageToGrid(image);
                    });
                }

                Toast.fire({
                    icon: 'success',
                    title: result.message
                });

            } catch (error) {
                console.error('Error:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error al procesar las imágenes'
                });
            }
        };

        const addImageToGrid = (image) => {
            const grid = document.getElementById('ads-grid');
            const imageDiv = document.createElement('div');
            imageDiv.className = 'col-md-3 col-sm-6 mb-3';
            imageDiv.setAttribute('data-ad-id', image.id);
            imageDiv.innerHTML = `  
                <div class="image-preview position-relative">  
                    <img src="${image.url}" alt="Ad ${image.id}" class="img-fluid">  
                    <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 delete-ad" data-id="${image.id}">  
                        <i class="fas fa-trash"></i>  
                    </button>  
                    <div class="image-info">  
                        <strong>ID: ${image.id}</strong><br>  
                        <small>${image.path}</small>  
                    </div>  
                </div>  
            `;
            grid.appendChild(imageDiv);

            // Agregar event listener al botón de eliminar  
            imageDiv.querySelector('.delete-ad').addEventListener('click', deleteAd);
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
                        document.querySelector(`[data-ad-id="${adId}"]`).remove();
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
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

        document.getElementById('uploadImagesBtn').addEventListener('click', () => {
            resetForm();
            uploadModal.show();
        });

        document.getElementById('uploadBtn').addEventListener('click', uploadImages);

        document.querySelectorAll('.delete-ad').forEach(btn => {
            btn.addEventListener('click', deleteAd);
        });
    });
</script>
@endpush