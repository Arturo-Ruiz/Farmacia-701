@extends('layouts.web.app')

@section('content')
<section class="search-hero-section bg-gradient-primary py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="search-hero-content">
                    <i class="fas fa-search search-hero-icon mb-3"></i>
                    <h1 class="search-hero-title">Resultados de búsqueda</h1>
                    <div class="search-query-display">
                        <span class="search-query-text">"{{ $query }}"</span>
                    </div>
                    <div class="search-results-summary">
                        <span class="results-count">{{ $totalResults }}</span>
                        <span class="results-text">{{ $totalResults == 1 ? 'producto encontrado' : 'productos encontrados' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Filtros Mejorada -->
<!-- Sección de Filtros Mejorada -->
<section class="search-filters-section py-4 bg-light">
    <div class="container">
        <!-- Breadcrumbs y botón volver (mantener igual) -->
        <div class="row align-items-center mb-3">
            <div class="col-md-6">
                <div class="search-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('web.home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active">Búsqueda</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="search-actions">
                    <a href="{{ route('web.home') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Volver al catálogo
                    </a>
                </div>
            </div>
        </div>

        <!-- Botón de filtros para móvil -->
        <div class="row d-md-none mb-3">
            <div class="col-12">
                <button class="btn btn-filter-toggle w-100" type="button" data-bs-toggle="collapse" data-bs-target="#mobileFilters" aria-expanded="false">
                    <i class="fas fa-sliders-h me-2"></i>
                    <span>Filtros de precio</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </button>
            </div>
        </div>

        <!-- Filtros - Visible en desktop, colapsable en móvil -->
        <div class="row">
            <div class="col-12">
                <div class="collapse d-md-block" id="mobileFilters">
                    <!-- Help text solo para móvil -->
                    <div class="d-md-none mb-2">
                        <small class="text-muted fw-semibold">
                            <i class="fas fa-info-circle me-1"></i>
                            Filtrar por precio
                        </small>
                    </div>

                    <div class="price-filter-container d-flex align-items-center justify-content-end gap-3 p-3">
                        <div class="filter-label">
                            <i class="fas fa-sort-amount-down text-primary me-2"></i>
                            <span class="fw-semibold">Ordenar por precio:</span>
                        </div>

                        <div class="filter-select-wrapper">
                            <select class="form-select form-select-sm" id="price-filter" name="price_order">
                                <option value="">Sin filtro</option>
                                <option value="asc" {{ request('price_order') == 'asc' ? 'selected' : '' }}>Menor a mayor</option>
                                <option value="desc" {{ request('price_order') == 'desc' ? 'selected' : '' }}>Mayor a menor</option>
                            </select>
                        </div>

                        <!-- Solo mantener el botón limpiar -->
                        <div class="filter-actions">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-price-filter">
                                Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<x-web.products
    :products="$products"
    :title="'Resultados de búsqueda'"
    :dayRate="$dayRate"
    :searchQuery="$query"
    :totalResults="$totalResults"
    :priceOrder="$priceOrder"
    :isSearchPage="true" />

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const searchText = $('.search-query-text');
        const originalText = searchText.text();
        searchText.text('');

        let i = 0;
        const typeWriter = setInterval(function() {
            if (i < originalText.length) {
                searchText.text(searchText.text() + originalText.charAt(i));
                i++;
            } else {
                clearInterval(typeWriter);
            }
        }, 50);

        $('#mobileFilters').on('show.bs.collapse', function() {
            $('[data-bs-target="#mobileFilters"]').html('<i class="fas fa-filter me-2"></i>Ocultar filtros <i class="fas fa-chevron-up ms-auto"></i>');
        });

        $('#mobileFilters').on('hide.bs.collapse', function() {
            $('[data-bs-target="#mobileFilters"]').html('<i class="fas fa-filter me-2"></i>Filtros <i class="fas fa-chevron-down ms-auto"></i>');
        });

    });
</script>
@endpush