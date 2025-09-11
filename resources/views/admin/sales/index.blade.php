@extends('layouts.admin.app')

@section('title', 'Ventas')

@section('content')

<div class="card shadow-lg border-0">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <div>
                <h5 class="m-0 font-weight-bold">
                    <i class="fa-solid fa-shopping-cart me-2"></i>Ventas Registradas
                </h5>
                <p class="mb-0 small">Gestiona las ventas del sistema</p>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive p-0 mt-2">
            {!! $dataTable->table() !!}
        </div>
    </div>
</div>

<!-- Modal para Ver Detalles de Venta -->
<div class="modal fade" id="saleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-bold" id="modalTitle">Detalles de Venta</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="saleDetails">
                    <!-- Los detalles se cargarán aquí -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}

<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        const saleModal = new bootstrap.Modal(document.getElementById('saleModal'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

        const showSaleDetails = async (saleId) => {
            try {
                const response = await fetch(`/admin/sales/${saleId}`);
                if (!response.ok) throw new Error('Venta no encontrada');
                const sale = await response.json();

                // Verificar si products ya es un objeto o necesita parsing  
                const products = typeof sale.products === 'string' ? JSON.parse(sale.products) : sale.products;
                let productsHtml = '';

                products.forEach(product => {
                    const totalBs = parseFloat(product.total) * parseFloat(sale.day_rate_value);
                    const priceBs = parseFloat(product.price) * parseFloat(sale.day_rate_value);

                    productsHtml += `  
                        <tr>  
                            <td>${product.name}</td>  
                            <td>${product.quantity}</td>  
                            <td>$ ${parseFloat(product.price).toFixed(2)} / Bs. ${priceBs.toFixed(2)}</td>  
                            <td>$ ${parseFloat(product.total).toFixed(2)} / Bs. ${totalBs.toFixed(2)}</td>  
                        </tr>  
                    `;
                });

                const totalBs = parseFloat(sale.total_amount) * parseFloat(sale.day_rate_value);

                // Formatear métodos de pago y entrega  
                let paymentMethodText = '';
                switch (sale.payment_method) {
                    case 'debit':
                        paymentMethodText = 'Tarjeta de débito';
                        break;
                    case 'credit':
                        paymentMethodText = 'Tarjeta de crédito';
                        break;
                    case 'mobile':
                        paymentMethodText = 'Pago móvil';
                        break;
                    case 'zelle':
                        paymentMethodText = 'Zelle';
                        break;
                    case 'binance':
                        paymentMethodText = 'Binance';
                        break;
                    case 'paypal':
                        paymentMethodText = 'PayPal';
                        break;
                    default:
                        paymentMethodText = sale.payment_method;
                }

                const deliveryTypeText = sale.delivery_type === 'pickup' ? 'Retiro en tienda' : 'Delivery';
                const detailsHtml = `  
                    <div class="row">  
                        <div class="col-md-6">  
                            <h6><i class="fas fa-user text-primary me-2"></i>Información del Cliente</h6>  
                            <p><i class="fas fa-user-circle text-muted me-2"></i><strong>Nombre:</strong> ${sale.client ? sale.client.name : 'Cliente eliminado'}</p>  
                            <p><i class="fas fa-credit-card text-muted me-2"></i><strong>Método de Pago:</strong> ${paymentMethodText}</p>  
                            <p><i class="fas fa-truck text-muted me-2"></i><strong>Tipo de Entrega:</strong> ${deliveryTypeText}</p>  
                        </div>  
                        <div class="col-md-6">  
                            <h6><i class="fas fa-receipt text-primary me-2"></i>Información de la Venta</h6>  
                            <p><i class="fas fa-hashtag text-muted me-2"></i><strong>ID:</strong> #${sale.id}</p>  
                            <p><i class="fas fa-calendar text-muted me-2"></i><strong>Fecha:</strong> ${new Date(sale.created_at).toLocaleString()}</p>  
                            <p><i class="fas fa-exchange-alt text-muted me-2"></i><strong>Tasa del Día:</strong> Bs. ${parseFloat(sale.day_rate_value).toFixed(2)}</p>  
                            <p><i class="fas fa-dollar-sign text-success me-2"></i><strong>Total USD:</strong> $ ${parseFloat(sale.total_amount).toFixed(2)}</p>  
                            <p><i class="fas fa-coins text-warning me-2"></i><strong>Total Bs:</strong> Bs. ${totalBs.toFixed(2)}</p>  
                        </div>  
                    </div>  
                    
                    <h6 class="mt-3"><i class="fas fa-box text-primary me-2"></i>Productos</h6>  
                    <div class="table-responsive">  
                        <table class="table table-sm">  
                            <thead>  
                                <tr>  
                                    <th><i class="fas fa-pills me-1"></i>Producto</th>  
                                    <th><i class="fas fa-sort-numeric-up me-1"></i>Cantidad</th>  
                                    <th><i class="fas fa-tag me-1"></i>Precio Unit.</th>  
                                    <th><i class="fas fa-calculator me-1"></i>Total</th>  
                                </tr>  
                            </thead>  
                            <tbody>  
                                ${productsHtml}  
                            </tbody>  
                        </table>  
                    </div>  
                    
                    ${sale.product_request ? `              
                        <div class="p-2 mt-3" style="background: rgba(52, 144, 220, 0.1); border-radius: 8px; border: 1px solid rgba(52, 144, 220, 0.2);">
                            <div class="d-flex align-items-start">  
                                <div class="me-2 flex-shrink-0" style="background: linear-gradient(135deg, #3498db, #2980b9); border-radius: 6px; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-comment text-white" style="font-size: 0.6rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0" style="font-size: 0.7rem; color: #6c757d;">SOLICITUD DE PRODUCTOS</p>
                                    <small class="text-dark">${sale.product_request}  </small>
                                </div>
                            </div>
                        </div>
                    ` : ''}  
                `;

                document.getElementById('saleDetails').innerHTML = detailsHtml;
                saleModal.show();
            } catch (error) {
                console.error('Error al cargar detalles:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Error al cargar los detalles de la venta'
                });
            }
        };

        // Event Listeners usando el patrón estándar del sistema  
        $('#sales-table').on('click', '.view-btn', function() {
            showSaleDetails($(this).data('id'));
        });
    });
</script>
@endpush