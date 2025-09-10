@extends('layouts.admin.app')

@section('title', 'Tasa del Día')

@section('content')

<div class="card shadow-lg border-0">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <div>
                <h5 class="m-0 font-weight-bold">
                    <i class="fa-solid fa-chart-line me-2"></i>Tasa del Día
                </h5>
                <p class="mb-0 small">Gestiona la tasa del día</p>
            </div>
            <!-- <button id="createDayRateBtn" class="btn btn-primary btn-md m-0 shadow-sm" style="display: none;">
                <i class="fa-solid fa-plus me-2"></i>
                Crear Nueva Tasa
            </button> -->
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive p-0 mt-2">
            {!! $dataTable->table() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="dayRateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-bold" id="modalTitle">Editar Tasa del Día</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="dayRateForm">
                    <input type="hidden" id="dayRateId">

                    <div class="mb-3">
                        <label for="value" class="form-label text-modal">Valor</label>
                        <div class="input-wrapper">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input type="number" step="0.01" class="form-control" name="value" id="value" autocomplete="off">
                            </div>
                        </div>
                        <div class="text-danger text-sm mt-1" id="value_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveDayRateBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}

<script type="module">
    document.addEventListener('DOMContentLoaded', function() {

        $('#value').mask('000.00', {
            reverse: true
        });


        const dayRateModal = new bootstrap.Modal(document.getElementById('dayRateModal'))
        const dayRateForm = document.getElementById('dayRateForm');
        const dayRateModalEl = document.getElementById('dayRateModal');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let currentDayRateId = null;

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
            dayRateForm.reset();
            document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
            currentDayRateId = null;
        };

        const openModalForEdit = async (dayRateId) => {
            resetForm();
            currentDayRateId = dayRateId;

            try {
                const response = await fetch(`/admin/day-rates/${dayRateId}`);
                if (!response.ok) throw new Error('Tasa del día no encontrada');
                const dayRate = await response.json();

                document.getElementById('dayRateId').value = dayRate.id;
                document.getElementById('value').value = dayRate.value;

                dayRateModal.show();
            } catch (error) {
                console.error('Error al cargar la tasa del día:', error);
                Swal.fire('Error', 'No se pudo cargar la tasa del día.', 'error');
            }
        };

        const saveDayRate = async () => {
            const formData = new FormData(dayRateForm);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch(`/admin/day-rates/${currentDayRateId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (!response.ok) {
                    if (result.errors) {
                        document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
                        Object.keys(result.errors).forEach(key => {
                            document.getElementById(`${key}_error`).textContent = result.errors[key][0];
                        });
                    }
                    return;
                }

                dayRateModal.hide();
                window.LaravelDataTables['day-rates-table'].ajax.reload();
                Toast.fire({
                    icon: 'success',
                    title: result.message
                });

            } catch (error) {
                console.error('Error al guardar:', error);
            }
        };

        document.getElementById('saveDayRateBtn').addEventListener('click', saveDayRate);

        dayRateModalEl.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                saveDayRate();
            }
        });

        dayRateModalEl.addEventListener('hidden.bs.modal', event => {
            document.body.focus();
        });

        $('#day-rates-table').on('click', '.edit-btn', function() {
            openModalForEdit($(this).data('id'));
        });
    });
</script>
@endpush