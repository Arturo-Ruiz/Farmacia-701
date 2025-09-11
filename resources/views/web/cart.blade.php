@extends('layouts.web.app')

@section('title', 'Farmacia 701 - Carrito de compras')

@section('content')

<section class="cart-section py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="search-hero-content">
                    <img src="{{ asset('img/logo.png') }}"
                        alt="Farmacia 701"
                        class="cart-logo mb-3"
                        style="width: 80px; height: 80px;">
                    <h1 class="search-hero-title">Carrito de compras</h1>
                    <p class="search-hero-subtitle">Finaliza los detalles de tu compra</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="cart-form-section fade-in-up">
                    <h3 class="mb-4">
                        <i class="fas fa-user-edit me-2"></i>Datos personales y envío
                    </h3>
                    <form id="cart-form">
                        <div class="mb-3">
                            <label class="form-label">Cédula o Rif <small class="text-muted">(Empieza con V, E o J)</small>
                            </label>
                            <input type="text" class="form-control" name="customer_id"
                                placeholder="V-12345678 o J-12345678" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nombre y Apellido o Nombre de empresa</label>
                            <input type="text" class="form-control" name="customer_name"
                                placeholder="Nombre y Apellido o Nombre de empresa" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email (Opcional)</label>
                            <input type="email" class="form-control" name="email"
                                placeholder="Con tu email podrás participar de nuestras promociones">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teléfono (Opcional)</label>
                            <input type="tel" class="form-control" name="phone"
                                placeholder="Coloca el Teléfono de la persona que retira o recibira el producto">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Dirección de envío</label>
                            <textarea class="form-control" name="address" rows="3"
                                placeholder="En caso de solicitar delivery"></textarea>
                        </div>

                        <div class="mb-4">
                            <h5>Elija el tipo de entrega 🚚</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery_type"
                                    id="pickup" value="pickup" checked>
                                <label class="form-check-label" for="pickup">
                                    Retiro en tienda
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery_type"
                                    id="delivery" value="delivery">
                                <label class="form-check-label" for="delivery">
                                    Delivery
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Método de pago 💳</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method"
                                    id="debit" value="debit" checked>
                                <label class="form-check-label" for="debit">Tarjeta de débito</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method"
                                    id="credit" value="credit">
                                <label class="form-check-label" for="credit">Tarjeta de crédito</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method"
                                    id="mobile" value="mobile">
                                <label class="form-check-label" for="mobile">Pago móvil</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method"
                                    id="zelle" value="zelle">
                                <label class="form-check-label" for="zelle">Zelle</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method"
                                    id="binance" value="binance">
                                <label class="form-check-label" for="binance">Binance</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method"
                                    id="paypal" value="paypal">
                                <label class="form-check-label" for="paypal">Paypal</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Solicitud de productos ✈️</h5>
                            <textarea class="form-control" name="special_requests" rows="3"
                                placeholder="Ingrese productos requeridos que no aparecen en el catálogo"></textarea>
                        </div>


                        <button type="submit" class="btn bg-primary-color text-white btn-lg w-100">
                            <i class="fas fa-paper-plane me-2"></i>Enviar pedido
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6 order-1 order-lg-2 ">
                <div class="cart-summary-section fade-in-up">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">
                            <i class="fas fa-shopping-bag me-2"></i>Su pedido
                        </h3>
                        @if($dayRate)
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-dollar-sign text-primary me-2"></i>
                            <span class="me-2">Tasa del día:</span>
                            <span class="fw-bold text-primary">Bs. {{ number_format($dayRate->value, 2) }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="cart-items-container">
                        {{-- Tabla para dispositivos de escritorio --}}
                        <div class="table-responsive d-none d-lg-block">
                            <table class="table cart-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cart-items-tbody-desktop">
                                    {{-- Los productos se inyectarán aquí --}}
                                </tbody>
                            </table>
                        </div>

                        {{-- Contenedor para la vista móvil --}}
                        <div class="cart-items-mobile d-lg-none">
                            <div id="cart-items-tbody-mobile">
                                {{-- Los productos se inyectarán aquí --}}
                            </div>
                        </div>
                    </div>

                    <div class="cart-total">
                        <div class="row">
                            <div class="col-6 d-flex align-content-center align-items-center">
                                <span class="fw-bold total-price-size">Total del pedido:</span>
                            </div>
                            <div class="col-6 text-end">
                                <span class="fw-bold price-size" id="cart-total-amount-bs">Bs. 0.00</span>
                                <span class="text-muted d-block price-size" id="cart-total-amount-dollars">$. 0.00</span>

                            </div>
                        </div>
                    </div>

                    <div class="cart-empty-state text-center py-5" id="cart-empty" style="display: none;">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5>Tu carrito está vacío</h5>
                        <p class="text-muted">Agrega productos desde nuestro catálogo</p>
                        <a href="{{ route('web.home') }}" class="btn bg-primary-color text-white">
                            <i class="fas fa-arrow-left me-2"></i>Volver al catálogo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@push('scripts')
<script>
    $(document).ready(function() {
        const dayRate = @json($dayRate);
        setTimeout(function() {
            $('.cart-form-section').addClass('animate');
        }, 200);

        setTimeout(function() {
            $('.cart-summary-section').addClass('animate');
        }, 400);

        // Animar items del carrito cuando se cargan  
        function animateCartItems() {
            $('.cart-item-row').each(function(index) {
                const $item = $(this);
                setTimeout(function() {
                    $item.addClass('animate');
                }, index * 100);
            });
        }

        // Llamar después de cargar items  
        setTimeout(animateCartItems, 600);

        loadCartItems();
        updateCartCounter();

        function loadCartItems() {
            const cart = JSON.parse(sessionStorage.getItem('cart') || '[]');
            const cartTbodyDesktop = $('#cart-items-tbody-desktop');
            const cartItemsMobile = $('#cart-items-tbody-mobile');

            if (cart.length === 0) {
                $('#cart-empty').show();
                $('.cart-items-container').hide();

                $('#cart-total-amount-bs').text('Bs. 0.00');
                $('#cart-total-amount-dollars').text('$ 0.00');

                return;
            }

            $('#cart-empty').hide();
            $('.cart-items-container').show();

            let htmlDesktop = '';
            let htmlMobile = '';
            let total = 0;

            cart.forEach(function(item, index) {
                const price = parseFloat(item.price);
                const quantity = parseInt(item.quantity);
                const itemTotal = price * quantity;
                total += itemTotal;

                const bsPrice = dayRate ? (price * dayRate.value).toFixed(2) : price.toFixed(2);
                const bsTotal = dayRate ? (itemTotal * dayRate.value).toFixed(2) : itemTotal.toFixed(2);

                // HTML para la tabla de escritorio
                htmlDesktop += `
            <tr data-index="${index}">
                <td data-label="Producto">
                    <div class="d-flex align-items-center">
                        <img src="${item.image}" alt="${item.name}" class="cart-product-image me-3">
                        <div>
                            <h6 class="mb-0">${item.name.toLowerCase().split(' ').map(word =>   
                                word.charAt(0).toUpperCase() + word.slice(1)  
                            ).join(' ')}</h6>
                            <small class="text-muted">${item.laboratory || 'Sin laboratorio'}</small>
                            <small class="text-primary-color">
                        <span class="fw-bold d-block">Precio Unitario:</span> <div> <span class="fw-bold"> Bs. ${bsPrice}</span> | <small class="text-muted"> $${price.toFixed(2)} </small></div>  
                    </small>
                        </div>
                    </div>
                </td>
                <td class="quantity-container" data-label="Cantidad">
                    <div class="cart-quantity-controls d-flex align-items-center">
                        <button class="btn bg-primary-color text-white btn-sm decrease-qty" data-index="${index}">-</button>
                        <span class="quantity-display">${quantity}</span>
                        <button class="btn btn-sm bg-primary-color text-white increase-qty" data-index="${index}">+</button>
                    </div>
                </td>
                <td class="total-container" data-label="Total">
                <span class="d-block fw-semibold">${dayRate ? `Bs. ${bsTotal}` : `Bs. ${bsTotal}`}</span>
                <small class="text-muted d-block">${dayRate ? `$${itemTotal.toFixed(2)}` : ''}</small>
                </td>
                <td>
                    <button class="btn btn-sm btn-danger remove-item" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

                htmlMobile += `
    <div class="cart-item-card" data-index="${index}">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="d-flex align-items-center">
                <img src="${item.image}" alt="${item.name}" class="cart-product-image me-3">
                <div class="product-info-mobile">
                    <h6 class="mb-0 fw-bold">${item.name.toLowerCase().split(' ').map(word =>   
                        word.charAt(0).toUpperCase() + word.slice(1)  
                    ).join(' ')}</h6>
                    <small class="text-muted d-block">${item.laboratory || 'Sin laboratorio'}</small>

                    <small class="text-primary-color">
                        <span class="fw-bold">Precio Unitario:</span> <div> <span class="fw-bold"> Bs. ${bsPrice}</span> | <small class="text-muted"> $${price.toFixed(2)} </small></div>  
                    </small>
                </div>
            </div>
            <button class="btn btn-sm btn-danger remove-item-mobile" data-index="${index}">
                <i class="fas fa-trash"></i>
            </button>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div class="cart-quantity-controls-mobile d-flex align-items-center">
                <button class="btn btn-sm bg-primary-color text-white decrease-qty" data-index="${index}">-</button>
                <span class="quantity-display">${quantity}</span>
                <button class="btn btn-sm bg-primary-color text-white increase-qty" data-index="${index}">+</button>
            </div>
            <div class="text-end">
                <span>Total:</span>
                <span class="d-block fw-bold">${dayRate ? `Bs. ${bsTotal}` : `Bs. ${bsTotal}`}</span>
                <small class="text-muted d-block">${dayRate ? `$${itemTotal.toFixed(2)}` : ''}</small>
            </div>
        </div>

    </div>
`;


                $('.cart-item-row').removeClass('animate');
                setTimeout(function() {
                    $('.cart-item-row').each(function(index) {
                        const $item = $(this);
                        setTimeout(function() {
                            $item.addClass('animate');
                        }, index * 100);
                    });
                }, 50);
            });

            cartTbodyDesktop.html(htmlDesktop);
            cartItemsMobile.html(htmlMobile);

            const totalBs = dayRate ? (total * dayRate.value).toFixed(2) : total.toFixed(2);

            $('#cart-total-amount-bs').text(dayRate ? `Bs. ${totalBs}` : `Bs. ${totalBs}`);
            $('#cart-total-amount-dollars').text(dayRate ? `$ ${total.toFixed(2)}` : `Bs. ${totalBs}`);

            // $('#cart-total-amount-b').text(dayRate ? `Bs. ${totalBs}  $${total.toFixed(2)}` : `Bs. ${totalBs}`);

        }

        function updateCartCounter() {
            const cart = JSON.parse(sessionStorage.getItem('cart') || '[]');
            const totalItems = cart.reduce((sum, item) => sum + parseInt(item.quantity), 0);
            $('.cart-counter').text(totalItems);
        }

        $(document).on('click', '.increase-qty', function() {
            const index = $(this).data('index');
            let cart = JSON.parse(sessionStorage.getItem('cart') || '[]');

            if (cart[index]) {
                const currentQuantity = parseInt(cart[index].quantity);
                const availableStock = parseInt(cart[index].stock);

                if (currentQuantity >= availableStock) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stock insuficiente',
                        text: `Solo hay ${availableStock} unidades disponibles de este producto`,
                        confirmButtonColor: '#212529',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                cart[index].quantity = currentQuantity + 1;
                sessionStorage.setItem('cart', JSON.stringify(cart));
                loadCartItems();
                updateCartCounter();
            }
        });

        $(document).on('click', '.decrease-qty', function() {
            const index = $(this).data('index');
            let cart = JSON.parse(sessionStorage.getItem('cart') || '[]');

            if (cart[index]) {
                const newQuantity = parseInt(cart[index].quantity) - 1;

                if (newQuantity <= 0) {
                    cart.splice(index, 1);
                    sessionStorage.setItem('cart', JSON.stringify(cart));
                    loadCartItems();
                    updateCartCounter();

                    Swal.fire({
                        title: 'Producto eliminado',
                        text: 'El producto ha sido eliminado del carrito',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    // Disminuir cantidad normalmente  
                    cart[index].quantity = newQuantity;
                    sessionStorage.setItem('cart', JSON.stringify(cart));
                    loadCartItems();
                    updateCartCounter();
                }
            }
        });

        $(document).on('click', '.remove-item, .remove-item-mobile', function() {
            const index = $(this).data('index');
            let cart = JSON.parse(sessionStorage.getItem('cart') || '[]');

            cart.splice(index, 1);
            sessionStorage.setItem('cart', JSON.stringify(cart));
            loadCartItems();
            updateCartCounter();

            Swal.fire({
                title: 'Producto eliminado',
                text: 'El producto ha sido eliminado del carrito',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        });

        $('#cart-form').on('submit', function(e) {
            e.preventDefault();

            const cart = JSON.parse(sessionStorage.getItem('cart') || '[]');
            if (cart.length === 0) {
                Swal.fire('Error', 'Tu carrito está vacío', 'error');
                return;
            }

            if (!this.querySelector('input[name="_token"]')) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                this.appendChild(csrfInput);
            }

            cart.forEach((item, index) => {
                const fields = [{
                        name: `cart_products[${index}][id]`,
                        value: item.id
                    },
                    {
                        name: `cart_products[${index}][name]`,
                        value: item.name
                    },
                    {
                        name: `cart_products[${index}][price]`,
                        value: item.price
                    },
                    {
                        name: `cart_products[${index}][quantity]`,
                        value: item.quantity
                    },
                    {
                        name: `cart_products[${index}][stock]`,
                        value: item.stock
                    },
                    {
                        name: `cart_products[${index}][laboratory]`,
                        value: item.laboratory || ''
                    }
                ];

                fields.forEach(field => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = field.name;
                    input.value = field.value;
                    this.appendChild(input);
                });
            });

            this.action = '{{ route("web.cart.process-order") }}';
            this.method = 'POST';

            sessionStorage.removeItem('cart');

            this.submit();
        });


        $('input[name="customer_id"]').on('input', function() {
            let value = $(this).val().toUpperCase().replace(/[^A-Z0-9]/g, '');

            if (value.length > 0) {
                if (!/^[VEJPG]/.test(value)) {
                    value = 'V' + value.replace(/[A-Z]/g, '');
                }

                if (value.length > 1) {
                    value = value.charAt(0) + '-' + value.slice(1);
                }

                if (value.length > 15) {
                    value = value.substring(0, 15);
                }
            }

            $(this).val(value);
        });

        $('input[name="customer_id"]').on('keypress', function(e) {
            const char = String.fromCharCode(e.which).toUpperCase();
            const currentValue = $(this).val();

            if (currentValue.length === 0) {
                if (!/[VEJPG]/.test(char)) {
                    e.preventDefault();
                }
            }
        });

        $('input[name="customer_id"]').on('blur', async function() {
            const idCard = $(this).val().trim();

            if (idCard.length >= 5) { // Mínimo V-123  
                try {
                    const response = await fetch('{{ route("web.cart.search-client") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            id_card: idCard
                        })
                    });

                    const result = await response.json();

                    if (result.found) {
                        // Rellenar campos automáticamente  
                        $('input[name="customer_name"]').val(result.client.name);
                        $('input[name="email"]').val(result.client.email || '');
                        $('input[name="phone"]').val(result.client.phone || '');
                        $('textarea[name="address"]').val(result.client.address || '');

                        // Mostrar notificación de éxito  
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Cliente encontrado - Datos cargados automáticamente',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                } catch (error) {
                    console.error('Error al buscar cliente:', error);
                }
            }
        });

        function loadImagesGracefully() {
            $('.cart-item-image img').each(function() {
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
    });
</script>
@endpush