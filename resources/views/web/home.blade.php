@extends('layouts.web.app')

@section('title', 'Farmacia 701 - ¡Somos tus Aliados en Salud!')
@section('body-class', 'search-brand')

@section('content')
<div class="fade-in-up search-section">
    <x-web.layout.search-section />
</div>

<div class="fade-in-up carousel-section stagger-animation">
    <x-web.carousel :carousels="$carousels" />
</div>

<div class="fade-in-up laboratories-section stagger-animation">
    <x-web.laboratories :laboratories="$laboratories" />
</div>

<div class="fade-in-up ads-section stagger-animation">
    <x-web.ads :ads="$ads" />
</div>

<div class="fade-in-up products-section stagger-animation">
    <x-web.products :products="$products" :dayRate="$dayRate" />
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        document.getElementById('current-year').textContent = new Date().getFullYear();

        function loadImagesGracefully() {
            $('img').each(function() {
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

        // Animaciones escalonadas para secciones  
        setTimeout(function() {
            $('.carousel-section').addClass('animate');
            loadImagesGracefully();
        }, 200);

        setTimeout(function() {
            $('.laboratories-section').addClass('animate');
        }, 400);

        setTimeout(function() {
            $('.ads-section').addClass('animate');
        }, 600);

        setTimeout(function() {
            $('.products-section').addClass('animate');
        }, 800);

        const body = document.querySelector("body");
        body.addEventListener("click", function() {
            document.querySelector("body").classList.remove("search-brand");
        });

        // Configuración mejorada del carousel de laboratorios  
        $(".categories-owl-carousel").owlCarousel({
            loop: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            margin: 5,
            nav: false,
            dots: false,
            smartSpeed: 800,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            onInitialized: function() {
                // Animar items después de inicializar  
                setTimeout(function() {
                    $('.categories-owl-carousel .owl-item').addClass('active');
                }, 100);
            },
            responsive: {
                0: {
                    items: 3
                },
                768: {
                    items: 5
                },
                992: {
                    items: 7
                }
            }
        });

        // Configuración mejorada del carousel de anuncios  
        $(".carousel-ads").owlCarousel({
            loop: true,
            margin: 10,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            smartSpeed: 800,
            animateOut: 'slideOutDown',
            animateIn: 'flipInX',
            onInitialized: function() {
                setTimeout(function() {
                    $('.carousel-ads .owl-item').addClass('active');
                }, 200);
            },
            responsive: {
                0: {
                    items: 4
                },
                600: {
                    items: 5
                },
                1000: {
                    items: 6
                }
            }
        });
    });
</script>
@endpush