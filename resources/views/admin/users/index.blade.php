@extends('layouts.admin.app')

@section('title', 'Usuarios')

@section('content')

<div class="card shadow-lg border-0">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <div>
                <h5 class="m-0 font-weight-bold">
                    <i class="fa-solid fa-users me-2"></i>Usuarios Registrados
                </h5>
                <p class="mb-0 small">Gestiona los usuarios del sistema</p>
            </div>
            <button id="createUserBtn" class="btn btn-primary btn-md m-0 shadow-sm">
                <i class="fa-solid fa-plus"></i>
                Crear Nuevo Usuario
            </button>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive p-0 mt-2">
            {!! $dataTable->table() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-bold" id="modalTitle"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="userId">
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
                        <label for="password" class="form-label text-modal">Contraseña</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" name="password" id="password" autocomplete="new-password">
                                <span class="input-group-text password-toggle" style="cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <small class="form-text text-muted d-none" id="password_help">Solo si deseas cambiar la contraseña.</small>
                        <div class="text-danger text-sm mt-1" id="password_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label text-modal">Confirmar Contraseña</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" autocomplete="new-password">
                                <span class="input-group-text password-toggle" style="cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="saveUserBtn" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}

<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        const userModal = new bootstrap.Modal(document.getElementById('userModal'))
        const userForm = document.getElementById('userForm');
        const userModalEl = document.getElementById('userModal');;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const loggedInUserId = document.body.dataset.userId;
        const modalTitle = document.getElementById('modalTitle');

        const passwordToggles = document.querySelectorAll('.password-toggle');

        let isEditing = false;
        let currentUserId = null;

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
            userForm.reset();
            document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
            currentUserId = null;
        };

        const openModalForCreate = () => {
            resetForm();
            isEditing = false;
            modalTitle.textContent = 'Crear Nuevo Usuario';
            document.getElementById('password_help').classList.add('d-none');
            userModal.show();
        };



        const openModalForEdit = async (userId) => {
            resetForm();
            isEditing = true;
            currentUserId = userId;

            try {
                const response = await fetch(`/admin/users/${userId}`);
                if (!response.ok) throw new Error('Usuario no encontrado');
                const user = await response.json();

                modalTitle.textContent = 'Editar Usuario';
                document.getElementById('password_help').classList.remove('d-none');
                document.getElementById('name').value = user.name;
                document.getElementById('email').value = user.email;

                userModal.show();
            } catch (error) {
                console.error('Error al cargar datos:', error);
            }
        };

        const saveUser = async () => {
            const url = isEditing ? `/admin/users/${currentUserId}` : '/admin/users';
            const method = isEditing ? 'PUT' : 'POST';

            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
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

                userModal.hide();
                window.LaravelDataTables['users-table'].ajax.reload();
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });


                if (isEditing && currentUserId == loggedInUserId) {
                    document.getElementById('navbar-user-name').textContent = formData.name;
                }

            } catch (error) {
                console.error('Error al guardar:', error);
            }
        };


        const deleteUser = async (userId) => {

            if (userId === loggedInUserId) {
                Swal.fire('Acción no permitida', 'No puedes eliminar tu propio usuario.', 'error');
                return;
            }

            const result = await Swal.fire({
                title: '¿Estás seguro de eliminar este usuario?',
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
                    const response = await fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Error al eliminar.');

                    window.LaravelDataTables['users-table'].ajax.reload();
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                } catch (error) {
                    console.error('Error al eliminar:', error);
                    Swal.fire('Error', 'No se pudo eliminar el usuario.', 'error');
                }
            }
        };

        passwordToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const passwordInput = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });


        document.getElementById('createUserBtn').addEventListener('click', openModalForCreate);
        document.getElementById('saveUserBtn').addEventListener('click', saveUser);

        userModalEl.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                saveUser();
            }
        });

        userModalEl.addEventListener('hidden.bs.modal', event => {
            document.body.focus();
        });

        $('#users-table').on('click', '.edit-btn', function() {
            openModalForEdit($(this).data('id'));
        });

        $('#users-table').on('click', '.delete-btn', function() {
            deleteUser($(this).data('id'));
        });

        $('#users-table').on('draw.dt', function() {
            const deleteButton = document.querySelector(`.delete-btn[data-id="${loggedInUserId}"]`);
            if (deleteButton) {
                deleteButton.disabled = true;
                deleteButton.classList.add('disabled');
                deleteButton.classList.add('text-white');
            }
        });
    });
</script>
@endpush