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
                <p class="mb-0 small">Cliente: <span class="font-weight-bolder">{{ $client->name }}</span></p>
            </div>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-primary btn-md m-0">
                <i class="fas fa-arrow-left me-2"></i>Volver a Clientes
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                <div class="card border-0" style="background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 16px; box-shadow: 0 4px 16px 0 rgba(31, 38, 135, 0.2);">
                    <div class="card-body p-3 text-dark">
                        <div class="d-flex align-items-center">
                            <div class="me-3 flex-shrink-0" style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user text-white fs-6"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <p class="mb-0 text-xs text-muted fw-bold">CLIENTE</p>
                                <h6 class="mb-0 fw-bold text-truncate">{{ $client->name }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                <div class="card border-0" style="background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 16px; box-shadow: 0 4px 16px 0 rgba(31, 38, 135, 0.2);">
                    <div class="card-body p-3 text-dark">
                        <div class="d-flex align-items-center">
                            <div class="me-3 flex-shrink-0" style="background: linear-gradient(135deg, #f093fb, #f5576c); border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-id-card text-white fs-6"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <p class="mb-0 text-xs text-muted fw-bold">DOCUMENTO</p>
                                <h6 class="mb-0 fw-bold text-truncate">{{ $client->id_card }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                <div class="card border-0" style="background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 16px; box-shadow: 0 4px 16px 0 rgba(31, 38, 135, 0.2);">
                    <div class="card-body p-3 text-dark">
                        <div class="d-flex align-items-center">
                            <div class="me-3 flex-shrink-0" style="background: linear-gradient(135deg, #4facfe, #00f2fe); border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-shopping-bag text-white fs-6"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <p class="mb-0 text-xs text-muted fw-bold">TOTAL COMPRAS</p>
                                <h6 class="mb-0 fw-bold">{{ $client->number_of_purchases }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                <div class="card border-0" style="background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 16px; box-shadow: 0 4px 16px 0 rgba(31, 38, 135, 0.2);">
                    <div class="card-body p-3 text-dark">
                        <div class="d-flex align-items-center">
                            <div class="me-3 flex-shrink-0" style="background: linear-gradient(135deg, #43e97b, #38f9d7); border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-envelope text-white fs-6"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <p class="mb-0 text-xs text-muted fw-bold">EMAIL</p>
                                <h6 class="mb-0 fw-bold text-truncate">{{ $client->email ?: 'No registrado' }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del cliente -->

        <!-- Lista de compras -->
        @if($sales->count() > 0)
        <div class="row">
            @foreach($sales as $sale)
            <div class="col-12 mb-3">
                <div class="card border-0" style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 2px solid rgba(102, 126, 234, 0.3); border-radius: 12px; box-shadow: 0 8px 30px 0 rgba(0, 0, 0, 0.15);">
                    <!-- Header responsive -->
                    <div class="card-header border-0 py-2" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1)); border-radius: 12px 12px 0 0;">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <div class="d-flex align-items-center mb-2 mb-md-0">
                                <div class="me-2" style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 8px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-receipt text-white" style="font-size: 0.8rem;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Pedido #{{ $sale->id }}</h6>
                                    <small class="text-muted">{{ $sale->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                            <div class="text-white px-3 py-2" style="background: linear-gradient(135deg, #4facfe, #00f2fe); border-radius: 15px; font-size: 0.9rem; font-weight: bold;">
                                Bs. {{ number_format($sale->total_amount * $sale->day_rate_value, 2) }} | ${{ number_format($sale->total_amount, 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="card-body py-2">
                        <!-- Layout responsive para métodos y tasa -->
                        <div class="row mb-2">
                            <!-- En móvil: 2 columnas por fila, en desktop: 3 columnas -->
                            <div class="col-6 col-lg-4 mb-2 mb-lg-0">
                                <div class="d-flex align-items-center p-2" style="background: rgba(248, 249, 250, 0.8); border-radius: 8px; border: 1px solid rgba(0, 0, 0, 0.05);">
                                    <div class="me-2 flex-shrink-0" style="background: linear-gradient(135deg, #f093fb, #f5576c); border-radius: 6px; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-truck text-white" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <div class="min-width-0">
                                        <p class="mb-0" style="font-size: 0.65rem; color: #6c757d;">ENTREGA</p>
                                        <small class="fw-bold text-dark d-block text-truncate">{{ $sale->delivery_type == 'pickup' ? 'Retiro' : 'Delivery' }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-4 mb-2 mb-lg-0">
                                <div class="d-flex align-items-center p-2" style="background: rgba(248, 249, 250, 0.8); border-radius: 8px; border: 1px solid rgba(0, 0, 0, 0.05);">
                                    <div class="me-2 flex-shrink-0" style="background: linear-gradient(135deg, #43e97b, #38f9d7); border-radius: 6px; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-credit-card text-white" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <div class="min-width-0">
                                        <p class="mb-0" style="font-size: 0.65rem; color: #6c757d;">PAGO</p>
                                        <small class="fw-bold text-dark d-block text-truncate">
                                            @switch($sale->payment_method)
                                            @case('debit') Débito @break
                                            @case('credit') Crédito @break
                                            @case('mobile') Móvil @break
                                            @case('zelle') Zelle @break
                                            @case('binance') Binance @break
                                            @case('paypal') PayPal @break
                                            @default {{ $sale->payment_method }}
                                            @endswitch
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <!-- Tasa del día en nueva fila en móvil -->
                            <div class="col-12 col-lg-4">
                                <div class="d-flex align-items-center p-2" style="background: rgba(248, 249, 250, 0.8); border-radius: 8px; border: 1px solid rgba(0, 0, 0, 0.05);">
                                    <div class="me-2 flex-shrink-0" style="background: linear-gradient(135deg, #ff9a9e, #fecfef); border-radius: 6px; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-exchange-alt text-white" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <div class="min-width-0">
                                        <p class="mb-0" style="font-size: 0.65rem; color: #6c757d;">TASA DEL DÍA</p>
                                        <small class="fw-bold text-dark">Bs. {{ number_format($sale->day_rate_value, 2) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Productos con vista responsive -->
                        <div class="mb-2">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2" style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 6px; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-box text-white" style="font-size: 0.75rem;"></i>
                                </div>
                                <small class="fw-bold text-dark">Productos ({{ count($sale->products) }})</small>
                            </div>

                            <div class="d-none d-md-block">
                                <div class="table-responsive" style="background: rgba(248, 249, 250, 0.8); border-radius: 8px; border: 1px solid rgba(0, 0, 0, 0.08);">
                                    <table class="table table-sm mb-0" style="font-size: 0.8rem;">
                                        <thead style="background: rgba(248, 249, 250, 0.9);">
                                            <tr>
                                                <th class="border-0 py-2 fw-bold text-dark text-center">Producto</th>
                                                <th class="border-0 py-2 fw-bold text-dark text-center">Cant.</th>
                                                <th class="border-0 py-2 fw-bold text-dark text-center">Precio Unit.</th>
                                                <th class="border-0 py-2 fw-bold text-dark text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sale->products as $product)
                                            <tr>
                                                <td class="border-0 py-2 text-dark text-center">{{ ucwords(strtolower($product['name'])) }}</td>
                                                <td class="border-0 py-2 text-center">
                                                    <span class="badge text-white px-2" style="background: linear-gradient(135deg, #4facfe, #00f2fe); border-radius: 10px; font-size: 0.7rem;">
                                                        {{ $product['quantity'] }}
                                                    </span>
                                                </td>
                                                <td class="border-0 py-2 text-dark text-center">
                                                    <div style="font-size: 0.75rem;">
                                                        <div class="text-bolder">Bs. {{ number_format($product['price'] * $sale->day_rate_value, 2)  }} </div>
                                                        <div class="text-muted">${{ number_format($product['price'], 2) }}</div>
                                                    </div>
                                                </td>
                                                <td class="border-0 py-2 text-dark text-center fw-bold">
                                                    <div style="font-size: 0.75rem;">
                                                        <div class="text-bolder">Bs. {{ number_format($product['total'] * $sale->day_rate_value, 2) }}</div>
                                                        <div class="text-muted">${{ number_format($product['total'], 2) }}</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Vista de tarjetas para móvil -->
                            <div class="d-md-none">
                                @foreach($sale->products as $product)
                                <div class="mb-2 p-2" style="background: rgba(248, 249, 250, 0.8); border-radius: 8px; border: 1px solid rgba(0, 0, 0, 0.08);">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div class="flex-grow-1 me-2">
                                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.85rem;">{{ ucwords(strtolower($product['name'])) }}</h6>
                                        </div>
                                        <span class="badge text-white px-2" style="background: linear-gradient(135deg, #4facfe, #00f2fe); border-radius: 10px; font-size: 0.7rem;">
                                            {{ $product['quantity'] }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted d-block">Precio:</small>
                                            <small class="fw-bold text-dark">Bs. {{ number_format($product['price'] * $sale->day_rate_value, 2) }}</small>
                                            <small class="text-muted"> | ${{ number_format($product['price'], 2) }}</small>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted d-block">Total:</small>
                                            <small class="fw-bold text-dark">Bs. {{ number_format($product['total'] * $sale->day_rate_value, 2) }}</small>
                                            <small class="text-muted"> | ${{ number_format($product['total'], 2) }}</small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        @if($sale->product_request)
                        <div class="p-2" style="background: rgba(52, 144, 220, 0.1); border-radius: 8px; border: 1px solid rgba(52, 144, 220, 0.2);">
                            <div class="d-flex align-items-start">
                                <div class="me-2 flex-shrink-0" style="background: linear-gradient(135deg, #3498db, #2980b9); border-radius: 6px; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-comment text-white" style="font-size: 0.6rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0" style="font-size: 0.7rem; color: #6c757d;">SOLICITUD DE PRODUCTOS</p>
                                    <small class="text-dark">{{ $sale->product_request }}</small>
                                </div>
                            </div>
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