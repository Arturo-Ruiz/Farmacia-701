@props(['products', 'title' => 'Catálogo', 'dayRate' => null, 'searchQuery' => null, 'totalResults' => null, 'priceOrder' => null, 'isSearchPage' => false, 'showLoadMore' => true])

<!-- Products Component -->
<section id="products" class="bg-light pb-5">
    <div class="container">
        @if($title)
        <div class="py-4">
            <p class="h3 title-section-products">{{ $title }}</p>
        </div>
        @endif

        @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 col-6 mb-4">
                <div class="product-card">

                    <div class="product-image">
                        @if($product->medical_prescription)
                        <div class="prescription-badge ">
                            Requiere Recipe
                        </div>
                        @endif
                        <a href="#producto-{{ $product->id }}">
                            <img src="{{ $product->img_url }}" alt="{{ $product->name }}">
                        </a>
                    </div>
                    <!-- <div class="product-image">
                        <a href="#producto-{{ $product->id }}">
                            <img src="{{ $product->img_url }}" alt="{{ $product->name }}">
                        </a>
                    </div> -->

                    <div class="product-details">
                        <div class="product-info">
                            <h3 class="product-title">{{ ucwords(strtolower($product->name)) }}</h3>
                            <p class="product-subtitle">{{ $product->laboratory ?? 'Sin laboratorio' }}</p>

                            @if($product->stock > 3)
                            <div class="product-availability high-stock">¡Disponible!</div>
                            @elseif($product->stock > 0)
                            <div class="product-availability low-stock">{{ $product->stock }} unidades disponibles</div>
                            @endif

                            <div class="price-stack">
                                @if($dayRate)
                                <span class="product-price-bs">Bs. {{ number_format($product->price * $dayRate->value, 2) }}</span>
                                <span class="price-separator">|</span>
                                <span class="product-price-usd">$ {{ number_format($product->price, 2) }}</span>
                                @else
                                <span class="product-price-bs">Bs. {{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                        </div>

                        @if($product->stock > 0)
                        <div class="product-button-container">
                            <a href="#" class="btn btn-dark w-100 product-button add-to-cart"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}"
                                data-product-price="{{ $product->price }}"
                                data-product-laboratory="{{ $product->laboratory }}"
                                data-product-image="{{ $product->img_url }}"
                                data-product-stock="{{ $product->stock }}">
                                <i class="fas fa-shopping-cart me-2"></i>Agregar al carrito
                            </a>

                            <div class="quantity-controls d-none" data-product-id="{{ $product->id }}">
                                <button class="btn decrease-quantity" type="button">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="quantity-display mx-3">1</span>
                                <button class="btn increase-quantity" type="button">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        @else
                        <button class="btn btn-secondary w-100 product-button" disabled>Agotado</button>
                        @endif

                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($showLoadMore && (!isset($searchQuery) || $totalResults > 12))
        <div class="row">
            <div class="col-12 text-center mt-4">
                <button id="load-more-btn" class="btn bg-primary-color text-white btn-lg font-weight-lighter">
                    <i class="fa-solid fa-arrow-down me-2"></i>Cargar más productos
                </button>
            </div>
        </div>
        @endif

        @else
        <section class="empty-search-section py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <div class="empty-search-content">
                            <div class="empty-search-icon mb-4">
                                <i class="fas fa-search-minus"></i>
                            </div>
                            <h3 class="empty-search-title">No encontramos resultados</h3>
                            <p class="empty-search-text">
                                No pudimos encontrar productos que coincidan con tu búsqueda
                                <strong>"{{ $searchQuery }}"</strong>
                            </p>
                            <div class="empty-search-suggestions">
                                <h5>Sugerencias:</h5>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Verifica la ortografía</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Usa términos más generales</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Prueba con sinónimos</li>
                                </ul>
                            </div>
                            <div class="empty-search-actions mt-4">
                                <a href="{{ route('web.home') }}" class="btn bg-primary-color text-white btn-lg">
                                    <i class="fas fa-home me-2"></i>Ver todos los productos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

    </div>
</section>


@push('scripts')
<script>
    let currentOffset = 12;

    $('#load-more-btn').click(function() {
        const button = $(this);
        
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Cargando...');

        // Variables pasadas desde el componente  
        const searchQuery = "{{ $searchQuery ?? '' }}";
        const priceOrder = "{{ $priceOrder ?? '' }}";
        const isSearchPage = searchQuery !== '';

        // Determinar URL y datos según el contexto  
        let url = "{{ route('web.products.load-more') }}";
        let data = {
            offset: currentOffset
        };

        if (isSearchPage) {
            url = "{{ route('web.search.load-more') }}";
            data.q = searchQuery;
            if (priceOrder) {
                data.price_order = priceOrder;
            }
        }

        $.ajax({
            url: url,
            method: 'GET',
            data: data,
            success: function(response) {
                if (response.products.length > 0) {
                    response.products.forEach(function(product, index) {
                        const productHtml = createProductCard(product, response.dayRate);
                        const $newProduct = $(productHtml);

                        // Aplicar animación inicial  
                        $newProduct.css({
                            'opacity': '0',
                            'transform': 'translateY(30px)',
                            'transition': 'all 0.6s ease-out'
                        });

                        // Agregar al grid  
                        $('#products .row').first().append($newProduct);

                        // Animar entrada con delay escalonado  
                        setTimeout(function() {
                            $newProduct.css({
                                'opacity': '1',
                                'transform': 'translateY(0)'
                            });
                        }, index * 150);
                    });

                    // Verificar si algún producto recién cargado ya está en el carrito  
                    const cart = JSON.parse(sessionStorage.getItem('cart')) || [];
                    response.products.forEach(function(product) {
                        const cartItem = cart.find(item => item.id === product.id);
                        if (cartItem) {
                            updateProductButton(product.id, cartItem.quantity);
                        }
                    });

                    currentOffset += response.products.length;

                    if (!response.hasMore) {
                        button.hide();
                    }
                }

                button.prop('disabled', false).html('<i class="fa-solid fa-arrow-down me-2"></i>Cargar más productos');
            },
            error: function() {
                button.prop('disabled', false).html('<i class="fa-solid fa-arrow-down me-2"></i>Cargar más productos');
                alert('Error al cargar más productos');
            }
        });
    });

    function createProductCard(product, dayRate) {
        const template = $('#products .product-card').first().closest('.col-lg-3').clone();

        const productImage = template.find('.product-image');
        productImage.find('.prescription-badge').remove();

        if (product.medical_prescription) {
            productImage.prepend('<div class="prescription-badge">REQUIERE RECIPE</div>');
        }

        template.find('.product-title').text(
            product.name.toLowerCase().split(' ').map(word =>
                word.charAt(0).toUpperCase() + word.slice(1)
            ).join(' ')
        );
        template.find('.product-subtitle').text(product.laboratory || 'Sin laboratorio');

        const priceStack = template.find('.price-stack');
        priceStack.empty();

        if (dayRate && dayRate.value) {
            const bsPrice = (product.price * dayRate.value).toFixed(2);
            const dollarPrice = parseFloat(product.price).toFixed(2);
            priceStack.html(`        
        <span class="product-price-bs">Bs. ${bsPrice}</span>        
        <span class="price-separator">|</span>        
        <span class="product-price-usd">$ ${dollarPrice}</span>        
    `);
        } else {
            const bsPrice = parseFloat(product.price).toFixed(2);
            priceStack.html(`<span class="product-price-bs">Bs. ${bsPrice}</span>`);
        }

        template.find('img').attr('src', `/storage/products/${product.img}`);
        template.find('img').attr('alt', product.name);
        template.find('img').attr('onerror', "this.src='/img/logo.png'");

        const availabilityDiv = template.find('.product-availability');
        if (product.stock > 3) {
            availabilityDiv.removeClass().addClass('product-availability high-stock').text('¡Disponible!');
        } else if (product.stock > 0) {
            availabilityDiv.removeClass().addClass('product-availability low-stock').text(`${product.stock} unidades disponibles`);
        } else {
            availabilityDiv.removeClass().addClass('product-availability out-of-stock').text('Agotado');
        }

        const productDetails = template.find('.product-details');

        if (product.stock > 0) {
            const buttonHtml = `  
            <div class="product-button-container">  
                 <a href="#" class="btn btn-dark w-100 product-button add-to-cart"    
                    data-product-id="${product.id}"    
                    data-product-name="${product.name}"    
                    data-product-price="${product.price}"    
                    data-product-laboratory="${product.laboratory || 'Sin laboratorio'}"    
                    data-product-image="/storage/products/${product.img}"    
                    data-product-stock="${product.stock}">    
                    <i class="fas fa-shopping-cart me-2"></i>Agregar al carrito    
                </a>    
                <div class="quantity-controls d-none" data-product-id="${product.id}">  
                    <button class="btn decrease-quantity" type="button">  
                        <i class="fas fa-minus"></i>  
                    </button>  
                    <span class="quantity-display mx-3">1</span>  
                    <button class="btn increase-quantity" type="button">  
                        <i class="fas fa-plus"></i>  
                    </button>  
                </div>  
            </div>  
        `;

            productDetails.find('.product-button, .product-button-container').remove();
            productDetails.append(buttonHtml);
        } else {
            const buttonHtml = `<button class="btn btn-secondary w-100 product-button" disabled>Agotado</button>`;
            productDetails.find('.product-button, .product-button-container').remove();
            productDetails.append(buttonHtml);
        }

        template.find('a[href^="#producto-"]').attr('href', `#producto-${product.id}`);

        return template[0].outerHTML;
    }

    function addToCart(productData) {
        let cart = JSON.parse(sessionStorage.getItem('cart')) || [];

        const existingItem = cart.find(item => item.id === productData.id);
        const currentQuantityInCart = existingItem ? existingItem.quantity : 0;
        const availableStock = parseInt(productData.stock);

        if (currentQuantityInCart >= availableStock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock insuficiente',
                text: `Solo hay ${availableStock} unidades disponibles de este producto`,
                confirmButtonColor: '#212529',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: productData.id,
                name: productData.name,
                price: productData.price,
                laboratory: productData.laboratory,
                image: productData.image,
                quantity: 1,
                stock: availableStock
            });
        }

        sessionStorage.setItem('cart', JSON.stringify(cart));
        updateCartCounter();
        updateProductButton(productData.id, existingItem ? existingItem.quantity : 1);
    }

    function updateCartCounter() {
        const cart = JSON.parse(sessionStorage.getItem('cart')) || [];
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

        const cartCounter = document.querySelector('.cart-counter');
        if (cartCounter) {
            cartCounter.textContent = totalItems;
            cartCounter.style.display = totalItems > 0 ? 'inline' : 'none';
        }

        // Update floating cart counter with animation
        const cartCounterFloat = document.querySelector('.cart-counter-float');
        if (cartCounterFloat) {
            cartCounterFloat.textContent = totalItems;
            cartCounterFloat.style.display = totalItems > 0 ? 'flex' : 'none';
            
            // Trigger bounce animation
            if (totalItems > 0) {
                cartCounterFloat.classList.remove('animate-add');
                void cartCounterFloat.offsetWidth; // Trigger reflow
                cartCounterFloat.classList.add('animate-add');
                
                // Remove animation class after it completes
                setTimeout(() => {
                    cartCounterFloat.classList.remove('animate-add');
                }, 600);
            }
        }
    }


    function updateProductButton(productId, quantity) {
        const productCard = $(`.add-to-cart[data-product-id="${productId}"]`).closest('.product-button-container');
        const addButton = productCard.find('.add-to-cart');
        const quantityControls = productCard.find('.quantity-controls');
        const quantityDisplay = quantityControls.find('.quantity-display');

        addButton.addClass('d-none');
        quantityControls.removeClass('d-none');
        quantityDisplay.text(quantity);
    }

    function updateQuantity(productId, newQuantity) {
        let cart = JSON.parse(sessionStorage.getItem('cart')) || [];
        const item = cart.find(item => item.id === productId);

        if (newQuantity <= 0) {
            cart = cart.filter(item => item.id !== productId);
            restoreAddButton(productId);
        } else {
            if (item) {
                if (newQuantity > item.stock) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stock insuficiente',
                        text: `Solo hay ${item.stock} unidades disponibles de este producto`,
                        confirmButtonColor: '#212529',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                item.quantity = newQuantity;
                updateProductButton(productId, newQuantity);
            }
        }

        sessionStorage.setItem('cart', JSON.stringify(cart));
        updateCartCounter();
    }

    function restoreAddButton(productId) {
        const productCard = $(`.quantity-controls[data-product-id="${productId}"]`).closest('.product-button-container');
        const addButton = productCard.find('.add-to-cart');
        const quantityControls = productCard.find('.quantity-controls');

        addButton.removeClass('d-none');
        quantityControls.addClass('d-none');
    }

    function initializeCartButtons() {
        const cart = JSON.parse(sessionStorage.getItem('cart')) || [];

        cart.forEach(item => {
            const existingProduct = $(`.add-to-cart[data-product-id="${item.id}"]`);
            if (existingProduct.length > 0) {
                updateProductButton(item.id, item.quantity);
            }
        });
    }

    // Event Listeners  
    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();

        const productData = {
            id: $(this).data('product-id'),
            name: $(this).data('product-name'),
            price: $(this).data('product-price'),
            laboratory: $(this).data('product-laboratory'),
            image: $(this).data('product-image'),
            stock: $(this).data('product-stock')
        };

        addToCart(productData);
    });

    $(document).on('click', '.increase-quantity', function(e) {
        e.preventDefault();
        const productId = $(this).closest('.quantity-controls').data('product-id');
        const currentQuantity = parseInt($(this).siblings('.quantity-display').text());
        updateQuantity(productId, currentQuantity + 1);
    });

    $(document).on('click', '.decrease-quantity', function(e) {
        e.preventDefault();
        const productId = $(this).closest('.quantity-controls').data('product-id');
        const currentQuantity = parseInt($(this).siblings('.quantity-display').text());
        updateQuantity(productId, currentQuantity - 1);
    });

    $(document).on('change', '#price-filter', function() {
        const priceOrder = $(this).val();
        const searchQuery = "{{ $searchQuery ?? '' }}";

        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('q', searchQuery);

        if (priceOrder && priceOrder !== '') {
            currentUrl.searchParams.set('price_order', priceOrder);
        } else {
            currentUrl.searchParams.delete('price_order');
        }

        window.location.href = currentUrl.toString();
    });

    $(document).ready(function() {
        updateCartCounter();
        initializeCartButtons();


        // Animación de entrada para tarjetas de productos existentes 
        $('.product-card').each(function(index) {
            $(this).css({
                'opacity': '0',
                'transform': 'translateY(30px)'
            }).delay(index * 100).animate({
                'opacity': '1'
            }, 600).css('transform', 'translateY(0)');
        });

        const urlParams = new URLSearchParams(window.location.search);

        
        const priceOrder = urlParams.get('price_order');
        if (priceOrder) {
            $('#price-filter').val(priceOrder);
        }

        $('#apply-price-filter').click(function() {
            const priceOrder = $('#price-filter').val();
            const currentUrl = new URL(window.location);

            if (priceOrder) {
                currentUrl.searchParams.set('price_order', priceOrder);
            } else {
                currentUrl.searchParams.delete('price_order');
            }

            currentUrl.searchParams.delete('offset');
            window.location.href = currentUrl.toString();
        });

        $('#clear-price-filter').click(function() {
            $('#price-filter').val('');
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.delete('price_order');
            currentUrl.searchParams.delete('offset');
            window.location.href = currentUrl.toString();
        });


    });
</script>
@endpush