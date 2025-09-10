@extends('layouts.web.app')

@section('content')

<section class="search-hero-section pt-5 pb-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Presentación de la alianza -->
                <div class="alliance-presentation text-center mb-4 fade-in-up alliance-section">
                    <div class="alliance-logos d-flex align-items-center justify-content-center flex-row gap-4 mb-3">
                        <!-- Logo Farmacia 701 -->
                        <div class="laboratory-logo fade-in-up logo-farmacia">
                            <img src="{{ asset('img/logo.png') }}"
                                alt="Farmacia 701"
                                class="alliance-logo">
                        </div>

                        <!-- Símbolo de suma -->
                        <div class="alliance-plus fade-in-up plus-symbol">
                            <i class="fas fa-plus text-white"></i>
                        </div>

                        <!-- Logo del laboratorio -->
                        <div class="laboratory-logo fade-in-up logo-laboratory">
                            <img src="{{ asset('storage/' . $laboratory->logo) }}"
                                alt="{{ $laboratory->name }}"
                                class="alliance-logo">
                        </div>
                    </div>

                    <!-- Texto de la alianza -->
                    <h2 class="alliance-title text-white mb-2 fade-in-up alliance-title-section">
                        Farmacia 701 + {{ $laboratory->name }}
                    </h2>
                    <p class="alliance-subtitle text-white-500 fade-in-up alliance-subtitle-section">
                        Alianza Estratégica en Salud
                    </p>
                </div>

                <!-- Información existente -->
                <div class="text-center fade-in-up hero-content-section">
                    <div class="search-query-text">
                        Laboratorio Recomendado
                    </div>
                    <div class="results-count">
                        <h6></h6> {{ $products->count() }}
                    </div>
                    <p class="results-text">Productos disponibles</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Productos -->
@if($products->count() > 0)
<div class="fade-in-up products-laboratory-section">
    <x-web.products
        :products="$products"
        :title="'Productos de ' . $laboratory->name"
        :dayRate="$dayRate"
        :showLoadMore="false" />
</div>
@else
<section class="py-5 bg-light fade-in-up empty-products-section">
    <div class="container">
        <div class="text-center">
            <i class="fas fa-box-open mb-4" style="font-size: 4rem; color: var(--color__primary); opacity: 0.6;"></i>
            <h3>No hay productos disponibles</h3>
            <p class="text-muted">Actualmente no tenemos productos de {{ $laboratory->name }} en stock.</p>
            <a href="{{ route('web.home') }}" class="btn btn-primary mt-3">
                <i class="fas fa-arrow-left me-2"></i>Volver al catálogo
            </a>
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Función para carga suave de imágenes  
        function loadImagesGracefully() {
            $('.alliance-logo').each(function() {
                const $img = $(this);
                const src = $img.attr('src');

                if (src) {
                    $img.addClass('image-skeleton');

                    const newImg = new Image();
                    newImg.onload = function() {
                        $img.removeClass('image-skeleton').addClass('loaded');
                    };
                    newImg.onerror = function() {
                        $img.attr('src', '/img/logo.png').addClass('loaded');
                    };
                    newImg.src = src;
                }
            });
        }

        setTimeout(function() {
            $('.alliance-section').addClass('animate');
            loadImagesGracefully();
        }, 200);

        setTimeout(function() {
            $('.logo-farmacia').addClass('animate');
        }, 400);

        setTimeout(function() {
            $('.plus-symbol').addClass('animate');
        }, 600);

        setTimeout(function() {
            $('.logo-laboratory').addClass('animate');
        }, 800);

        setTimeout(function() {
            $('.alliance-title-section').addClass('animate');
        }, 1000);

        setTimeout(function() {
            $('.alliance-subtitle-section').addClass('animate');
        }, 1200);

        setTimeout(function() {
            $('.hero-content-section').addClass('animate');
        }, 1400);

        setTimeout(function() {
            $('.products-laboratory-section, .empty-products-section').addClass('animate');
        }, 1600);
    });
</script>
@endpush