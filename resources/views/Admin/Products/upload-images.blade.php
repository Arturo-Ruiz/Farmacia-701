@extends('layouts.admin.app')

@section('title', 'Cargar Imágenes de Productos')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <h5 class="text-header m-0">Cargar Imágenes de Productos</h5>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-md">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    <div class="card-body">
        <div id="dropzone" class="dropzone">
            <div class="dz-message">
                <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                <h4>Arrastra las imágenes aquí o haz clic para seleccionar</h4>
                <p>Formatos permitidos: JPG, PNG, GIF (máximo 10MB por imagen)</p>
            </div>
        </div>

        <div id="uploaded-images" class="mt-4">
            <h6>Imágenes Cargadas:</h6>
            <div class="row" id="images-grid"></div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        Dropzone.autoDiscover = false;

        const dropzoneElement = document.getElementById('dropzone');
        if (!dropzoneElement || dropzoneElement.dropzone) {
            return;
        }

        let uploadedImagesCount = 0;
        let totalImagesToUpload = 0;

        const dropzone = new Dropzone("#dropzone", {
            url: "/admin/products/upload-images",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            paramName: "images",
            maxFilesize: 10,
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            dictDefaultMessage: "",
            parallelUploads: 5,
            uploadMultiple: true,

            success: function(file, response) {
                if (response.images) {
                    response.images.forEach(image => {
                        addImageToGrid(image);
                    });
                    uploadedImagesCount += response.images.length;
                }
            },

            queuecomplete: function() {
                if (uploadedImagesCount > 0) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: `${uploadedImagesCount} imagen(es) cargada(s) exitosamente`,
                        timer: 3000,
                        showConfirmButton: false
                    });

                    uploadedImagesCount = 0;
                }
            },

            error: function(file, response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al cargar la imagen'
                });
            }
        });

        function addImageToGrid(image) {
            const grid = document.getElementById('images-grid');
            if (!grid) return;

            const imageDiv = document.createElement('div');
            imageDiv.className = 'col-md-3 col-sm-6 mb-3';
            imageDiv.innerHTML = `  
            <div class="image-preview">  
                <img src="${image.url}" alt="${image.filename}" class="img-fluid">  
                <div class="image-info">  
                    <strong>${image.filename}</strong><br>  
                    <small>Ruta: ${image.path}</small>  
                </div>  
            </div>  
        `;
            grid.appendChild(imageDiv);
        }
    });
</script>
@endpush