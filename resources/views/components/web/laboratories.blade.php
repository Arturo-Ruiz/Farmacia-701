@props(['laboratories'])

<!-- Laboratories Component -->
<section id="categories-carousel-section" class="container">
    <div class="py-2">
        <p class="h3 head__title text-start">Laboratorios Recomendados</p>
    </div>

    @if($laboratories->count() > 0)
    <div class="owl-carousel categories-owl-carousel">
        @foreach($laboratories as $laboratory)
        <div class="category-item text-center">
            <a href="{{ route('web.laboratory', $laboratory->keyword) }}">
                @if($laboratory->logo)
                <img src="{{ asset('storage/' . $laboratory->logo) }}"
                    alt="{{ $laboratory->name }}"
                    class="img-fluid">
                @endif
            </a>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-flask fa-3x text-muted mb-3"></i>
        <p class="text-muted">No hay laboratorios disponibles</p>
    </div>
    @endif
</section>