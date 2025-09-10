@props(['ads'])  
  
<!-- Ads Component -->  
<section id="ads" class="container">  
    <div class="py-4">  
        <p class="h3 title-section">Productos recomendados</p>  
    </div>  
      
    @if($ads->count() > 0)  
        <div class="owl-carousel carousel-ads">  
            @foreach($ads as $ad)  
                <div class="item-ad-carousel">  
                    <img src="{{ asset('storage/ads/' . $ad->img) }}"   
                         alt="Publicidad {{ $ad->id }}"  
                         class="img-fluid">  
                </div>  
            @endforeach  
        </div>  
    @else  
        <div class="text-center py-5">  
            <i class="fas fa-rectangle-ad fa-3x text-muted mb-3"></i>  
            <p class="text-muted">No hay publicidades disponibles</p>  
        </div>  
    @endif  
</section>