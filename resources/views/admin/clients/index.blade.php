@extends('layouts.admin.app')

@section('title', 'Clientes')

@section('content')

<div class="card shadow-lg border-0">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <div>
                <h5 class="m-0 font-weight-bold">
                    <i class="fa-solid fa-users me-2"></i>Clientes Registrados
                </h5>
                <p class="mb-0 small">Gestiona los clientes del sistema</p>
            </div>
            <!-- <button id="createClientBtn" class="btn btn-primary btn-md m-0 shadow-sm">
                <i class="fa-solid fa-plus"></i>
                Crear Nuevo Cliente
            </button> -->
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive p-0 mt-2">
            {!! $dataTable->table() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="clientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-bold" id="modalTitle"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="clientForm">
                    <input type="hidden" id="clientId">
                    <div class="mb-3">
                        <label for="name" class="form-label text-modal">Nombre</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="name" id="name" autocomplete="name">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="name_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="id_card" class="form-label text-modal">Cédula o RIF</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" class="form-control" name="id_card" id="id_card" autocomplete="off">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="id_card_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label text-modal">Email</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" name="email" id="email" autocomplete="email">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="email_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label text-modal">Teléfono</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control" name="phone" id="phone" autocomplete="tel">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="phone_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label text-modal">Dirección</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <textarea class="form-control" name="address" id="address" rows="3" autocomplete="address-line1"></textarea>
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="address_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="saveClientBtn" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}

<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        const clientModal = new bootstrap.Modal(document.getElementById('clientModal'))
        const clientForm = document.getElementById('clientForm');
        const clientModalEl = document.getElementById('clientModal');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const modalTitle = document.getElementById('modalTitle');

        let isEditing = false;
        let currentClientId = null;

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
            clientForm.reset();
            document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
            currentClientId = null;
        };

        const openModalForCreate = () => {
            resetForm();
            isEditing = false;
            modalTitle.textContent = 'Crear Nuevo Cliente';
            clientModal.show();
        };

        const openModalForEdit = async (clientId) => {
            resetForm();
            isEditing = true;
            currentClientId = clientId;

            try {
                const response = await fetch(`/admin/clients/${clientId}`);
                if (!response.ok) throw new Error('Cliente no encontrado');
                const client = await response.json();

                modalTitle.textContent = 'Editar Cliente';
                document.getElementById('name').value = client.name;
                document.getElementById('id_card').value = client.id_card;
                document.getElementById('email').value = client.email || '';
                document.getElementById('phone').value = client.phone || '';
                document.getElementById('address').value = client.address || '';

                clientModal.show();
            } catch (error) {
                console.error('Error al cargar datos:', error);
            }
        };

        const saveClient = async () => {
            const url = isEditing ? `/admin/clients/${currentClientId}` : '/admin/clients';
            const method = isEditing ? 'PUT' : 'POST';

            const formData = {
                name: document.getElementById('name').value,
                id_card: document.getElementById('id_card').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                address: document.getElementById('address').value,
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

                clientModal.hide();
                window.LaravelDataTables['clients-table'].ajax.reload();
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });

            } catch (error) {
                console.error('Error al guardar:', error);
            }
        };

        const deleteClient = async (clientId) => {
            const result = await Swal.fire({
                title: '¿Estás seguro de eliminar este cliente?',
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
                    const response = await fetch(`/admin/clients/${clientId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Error al eliminar.');

                    window.LaravelDataTables['clients-table'].ajax.reload();
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                } catch (error) {
                    console.error('Error al eliminar:', error);
                    Swal.fire('Error', 'No se pudo eliminar el cliente.', 'error');
                }
            }
        };



        // Formateo automático para Cédula/RIF  
        document.getElementById('id_card').addEventListener('input', function() {
            let value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');

            if (value.length > 0) {
                // Asegurar que empiece con una letra válida  
                if (!/^[VEJPG]/.test(value)) {
                    value = 'V' + value.replace(/[A-Z]/g, '');
                }

                // Formatear con guión después de la primera letra  
                if (value.length > 1) {
                    value = value.charAt(0) + '-' + value.slice(1);
                }

                // Limitar longitud  
                if (value.length > 10) {
                    value = value.substring(0, 10);
                }
            }

            this.value = value;
        });

        // document.getElementById('createClientBtn').addEventListener('click', openModalForCreate);
        document.getElementById('saveClientBtn').addEventListener('click', saveClient);

        clientModalEl.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                saveClient();
            }
        });

        clientModalEl.addEventListener('hidden.bs.modal', event => {
            document.body.focus();
        });

        $('#clients-table').on('click', '.edit-btn', function() {
            openModalForEdit($(this).data('id'));
        });

    });
</script>
@endpush