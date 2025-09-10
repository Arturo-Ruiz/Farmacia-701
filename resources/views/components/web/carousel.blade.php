@props(['carousels'])

<!-- Carousel Component -->
<section class="container py-3">
    @if($carousels->count() > 0)
    <div id="carousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($carousels as $index => $carousel)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/carousels/' . $carousel->img) }}"
                    class="d-flex w-100 img-fluid"
                    alt="Imagen de carrusel {{ $carousel->id }}">
            </div>
            @endforeach
        </div>

        @if($carousels->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        @endif
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-images fa-3x text-muted mb-3"></i>
        <p class="text-muted">No hay imágenes de carrusel disponibles</p>
    </div>
    @endif
</section>