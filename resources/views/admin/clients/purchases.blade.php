@extends('layouts.admin.app')

@section('title', 'Historial de Compras - ' . $client->name)

@section('content')

<div class="card shadow-lg border-0">
    <div class="card-header pb-0">
        <div class="container-fluid d-flex justify-content-between align-items-center mt-2">
            <div>
                <h5 class="m-0 font-weight-bold">
                    <i class="fas fa-shopping-cart me-2"></i>Historial de Compras
                </h5>
                <p class="mb-0 small">Cliente: {{ $client->name }}</p>
            </div>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary btn-md m-0">
                <i class="fas fa-arrow-left me-2"></i>Volver a Clientes
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <!-- Información del cliente -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 bg-gradient-primary">
                    <div class="card-body p-3 text-white">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-user fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-sm opacity-8">Cliente</p>
                                <h6 class="mb-0">{{ $client->name }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-gradient-info">
                    <div class="card-body p-3 text-white">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-id-card fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-sm opacity-8">Documento</p>
                                <h6 class="mb-0">{{ $client->id_card }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-gradient-success">
                    <div class="card-body p-3 text-white">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-shopping-bag fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-sm opacity-8">Total Compras</p>
                                <h6 class="mb-0">{{ $client->number_of_purchases }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-gradient-warning">
                    <div class="card-body p-3 text-white">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-envelope fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-sm opacity-8">Email</p>
                                <h6 class="mb-0 text-truncate" style="max-width: 120px;">{{ $client->email ?: 'No registrado' }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de compras -->
        @if($sales->count() > 0)
        <div class="row">
            @foreach($sales as $sale)
            <div class="col-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 font-weight-bold">
                                    <i class="fas fa-receipt me-2 text-primary"></i>
                                    Pedido #{{ $sale->id }}
                                </h6>
                                <p class="mb-0 text-sm text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $sale->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-gradient-primary fs-6 px-3 py-2">
                                    @if($dayRate)
                                    Bs. {{ number_format($sale->total_amount * $dayRate->value, 2) }} |
                                    ${{ number_format($sale->total_amount, 2) }}
                                    @else
                                    ${{ number_format($sale->total_amount, 2) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">
                                        <i class="fas fa-truck me-1"></i>Método de entrega
                                    </small>
                                    <span class="badge bg-info">
                                        {{ $sale->delivery_type == 'pickup' ? 'Retiro en tienda' : 'Delivery' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">
                                        <i class="fas fa-credit-card me-1"></i>Método de pago
                                    </small>
                                    <span class="badge bg-success">
                                        @switch($sale->payment_method)
                                        @case('debit') Tarjeta de débito @break
                                        @case('credit') Tarjeta de crédito @break
                                        @case('mobile') Pago móvil @break
                                        @case('zelle') Zelle @break
                                        @case('binance') Binance @break
                                        @case('paypal') PayPal @break
                                        @default {{ $sale->payment_method }}
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Productos -->
                        <div class="mb-3">
                            <small class="text-muted d-block mb-2">
                                <i class="fas fa-box me-1"></i>Productos ({{ count($sale->products) }})
                            </small>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-xs font-weight-bold">Producto</th>
                                            <th class="text-xs font-weight-bold text-center">Cantidad</th>
                                            <th class="text-xs font-weight-bold text-end">Precio Unit.</th>
                                            <th class="text-xs font-weight-bold text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sale->products as $product)
                                        <tr>
                                            <td class="text-sm">{{ ucwords(strtolower($product['name'])) }}</td>
                                            <td class="text-sm text-center">{{ $product['quantity'] }}</td>
                                            <td class="text-sm text-end">
                                                @if($dayRate)
                                                ${{ number_format($product['price'], 2) }}
                                                @else
                                                ${{ number_format($product['price'], 2) }}
                                                @endif
                                            </td>
                                            <td class="text-sm text-end font-weight-bold">
                                                @if($dayRate)
                                                ${{ number_format($product['total'], 2) }}
                                                @else
                                                ${{ number_format($product['total'], 2) }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($sale->product_request)
                        <div class="alert alert-info mb-0">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-comment me-1"></i>Productos solicitados
                            </small>
                            <p class="mb-0 text-sm">{{ $sale->product_request }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $sales->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart mb-3" style="font-size: 4rem; color: var(--bs-gray-400);"></i>
            <h5 class="text-muted">No hay compras registradas</h5>
            <p class="text-muted">Este cliente aún no ha realizado ninguna compra.</p>
        </div>
        @endif
    </div>
</div>

@endsection