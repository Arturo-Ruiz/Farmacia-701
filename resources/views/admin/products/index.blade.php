@extends('layouts.admin.app')

@section('title', 'Productos')

@section('content')

<div class="card shadow-lg border-0">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <div>
                <h5 class="m-0 font-weight-bold">
                    <i class="fa-solid fa-box me-2"></i>Productos Registrados
                </h5>
                <p class="mb-0 small">Gestiona los productos del sistema</p>
            </div>
            <div class="d-flex gap-2">
                <button id="uploadImagesBtn" class="btn btn-success btn-md m-0">
                    <i class="fa-solid fa-images"></i> Cargar Imágenes
                </button>
                <button id="importExcelBtn" class="btn btn-primary btn-md m-0">
                    <i class="fa-solid fa-file-excel"></i> Importar Excel
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive p-0 mt-2">
            {!! $dataTable->table() !!}
        </div>
    </div>
</div>

<!-- Modal de Importación -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-bold">Importar Productos desde Excel</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="importForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="excel_file" class="form-label text-modal">Archivo Excel</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-file-excel"></i></span>
                                <input type="file" class="form-control" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="excel_file_error"></div>
                        <small class="text-muted">Formatos permitidos: .xlsx, .xls, .csv</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="uploadBtn">Importar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}

<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        const importModal = new bootstrap.Modal(document.getElementById('importModal'));
        const importForm = document.getElementById('importForm');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        const resetForm = () => {
            importForm.reset();
            document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
        };

        const uploadExcel = async () => {
            const formData = new FormData(importForm);

            try {
                const response = await fetch('/admin/products/import', {
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
                            title: result.error || 'Error al importar'
                        });
                    }
                    return;
                }

                importModal.hide();
                resetForm();
                window.LaravelDataTables['products-table'].ajax.reload();
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });

            } catch (error) {
                console.error('Error:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error al procesar el archivo'
                });
            }
        };

        // Event Listeners  
        document.getElementById('importExcelBtn').addEventListener('click', () => {
            resetForm();
            importModal.show();
        });

        document.getElementById('uploadImagesBtn').addEventListener('click', () => {
            window.location.href = "{{ route('admin.products.upload-images') }}";
        });

        document.getElementById('uploadBtn').addEventListener('click', uploadExcel);
    });
</script>
@endpush