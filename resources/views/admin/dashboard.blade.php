@extends('layouts.admin.app')

@section('title', 'Panel de Control')

@section('content')

<!-- Métricas principales -->
<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Ventas de Hoy</p>
                            <h5 class="font-weight-bolder">
                                $ {{ number_format($todaySales, 2) }}
                            </h5>
                            <p class="mb-0">
                                <span class="text-success text-sm font-weight-bolder">{{ $todaySalesCount }}</span>
                                transacciones
                            </p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                            <i class="fa-solid fa-dollar-sign text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Ventas del Mes</p>
                            <h5 class="font-weight-bolder">
                                $ {{ number_format($monthlySales, 2) }}
                            </h5>
                            <p class="mb-0">
                                <span class="text-{{ $salesGrowth >= 0 ? 'success' : 'danger' }} text-sm font-weight-bolder">
                                    {{ $salesGrowth >= 0 ? '+' : '' }}{{ number_format($salesGrowth, 1) }}%
                                </span>
                                vs mes anterior
                            </p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                            <i class="fa-solid fa-chart-line text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Clientes</p>
                            <h5 class="font-weight-bolder">
                                {{ number_format($totalClients) }}
                            </h5>
                            <p class="mb-0">
                                <span class="text-success text-sm font-weight-bolder">Registrados</span>
                                en el sistema
                            </p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                            <i class="fa-solid fa-users text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Productos</p>
                            <h5 class="font-weight-bolder">
                                {{ number_format($totalProducts) }}
                            </h5>
                            <p class="mb-0">
                                <span class="text-info text-sm font-weight-bolder">Disponibles</span>
                                en inventario
                            </p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                            <i class="fa-solid fa-pills text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráfico de ventas y tasa actual -->
<div class="row mt-4">
    <div class="col-lg-8 mb-lg-0 mb-4">
        <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
                <h6 class="text-capitalize">
                    <i class="fas fa-chart-line text-primary me-2"></i>Tendencia de Ventas (Últimos 30 días)
                </h6>
                <p class="text-sm mb-0">
                    <i class="fa fa-arrow-up text-success"></i>
                    <span class="font-weight-bold">Ventas diarias en USD</span>
                </p>
            </div>
            <div class="card-body p-3">
                <div class="chart">
                    <canvas id="chart-sales" class="chart-canvas" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">
                    <i class="fas fa-exchange-alt text-primary me-2"></i>Tasa del Día
                </h6>
            </div>
            <div class="card-body p-3 text-center">
                @if($currentRate)
                <h2 class="text-primary mb-2">Bs. {{ number_format($currentRate->value, 2) }}</h2>
                <p class="text-muted mb-0">por cada dólar</p>
                <small class="text-muted">Actualizado: {{ $currentRate->updated_at->format('d/m/Y H:i') }}</small>
                @else
                <p class="text-muted">No hay tasa configurada</p>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">
                    <i class="fas fa-exchange-alt text-primary me-2"></i>Resumen del Día
                </h6>
            </div>
            <div class="card-body p-3 text-center">
                <h6><i class="fas fa-chart-line text-primary me-2"></i>Resumen del Día</h6>
                <div class="row mt-3">
                    <div class="col-6">
                        <small class="text-muted d-block">Ventas Hoy</small>
                        <h6 class="text-primary mb-0">{{ $todaySalesCount }}</h6>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Total Hoy</small>
                        <h6 class="text-success mb-0">${{ number_format($todaySales, 2) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos de análisis -->
<div class="row mt-4">
    <!-- Tipos de Entrega -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">
                    <i class="fas fa-truck text-primary me-2"></i>Tipos de Entrega
                </h6>
            </div>
            <div class="card-body p-3">
                <canvas id="chart-delivery-types" class="chart-canvas" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Ventas por Laboratorio (Barras) -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">
                    <i class="fas fa-flask text-primary me-2"></i>Ventas por Laboratorio
                </h6>
            </div>
            <div class="card-body p-3">
                <canvas id="chart-laboratory-sales" class="chart-canvas" height="200"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- Productos más vendidos y clientes activos -->
<div class="row mt-4">
    <div class="col-lg-7 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">
                    <i class="fas fa-star text-primary me-2"></i>Productos Más Vendidos
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Producto</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Cantidad</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $product)
                        <tr>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{ $product['name'] }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-sm bg-gradient-success">{{ $product['quantity'] }}</span>
                            </td>
                            <td class="align-middle text-center text-sm">
                                <span class="text-xs font-weight-bold">${{ number_format($product['total'], 2) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">
                    <i class="fa-solid fa-users me-2"></i>Clientes Más Activos
                </h6>
            </div>
            <div class="card-body p-3">
                <ul class="list-group">
                    @foreach($topClients as $client)
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-primary shadow text-center">
                                <i class="fa-solid fa-user text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">{{ $client->name }}</h6>
                                <span class="text-xs">{{ $client->sales_count }} compras</span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Pasar datos a JavaScript de forma global  
    window.dashboardData = {
        dailySales: @json($dailySales),
        deliveryTypes: @json($deliveryTypes),
        salesByLaboratory: @json($salesByLaboratory)
    };
</script>
@vite(['resources/assets/admin/js/dashboard.js'])
@endpush