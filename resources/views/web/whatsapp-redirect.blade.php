<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="es">

    <title>@yield('title', 'Farmacia 701 - ¡Somos tus Aliados en Salud!')</title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{ asset('/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href=" {{ asset('/apple-touch-icon.png') }} " />
    <meta name="apple-mobile-web-app-title" content="Farmacia 701" />
    <link rel="manifest" href="{{ asset('/site.webmanifest') }}" />

    <meta name="robots" content="noindex, nofollow">

<body>
    <script>
        let order_products = ``;
        @foreach($products as $product)
        order_products += `*{{ $product['name'] }}*\nPrecio unitario: {{ $product['unit_price_formatted'] }}\nCantidad: *{{ $product['quantity'] }}*\nSubtotal: {{ $product['total_formatted'] }}\n\n`;
        @endforeach

        let msg = `*💊 Farmacia 701 - Nuevo Pedido*\n\n`;
        msg += `👤 *Cliente:* {{ $name }}\n`;
        msg += `📄 *Documento:* {{ $id_card }}\n`;

        @if($email)
        msg += `📧 *Email:* {{ $email }}\n`;
        @endif

        @if($phone)
        msg += `📱 *Teléfono:* {{ $phone }}\n`;
        @endif

        @if($address)
        msg += `📍 *Dirección:* {{ $address }}\n`;
        @endif

        msg += `🚚 *Entrega:* {{ $deliveryMethod }}\n`;
        msg += `💳 *Método Pago:* {{ $paymentMethod }}\n\n`;

        msg += `🛒 *Productos Solicitados:*\n\n`;
        msg += order_products;

        msg += `💰 *Total: {{ $total }}*\n\n`;

        @if($requestProducts)
        msg += `📝 *Productos adicionales solicitados:*\n{{ $requestProducts }}\n\n`;
        @endif

        msg += `¡Gracias por confiar en Farmacia 701! 💙`;

        let url = `https://api.whatsapp.com/send?phone=584141850671&text=${encodeURIComponent(msg)}`;

        window.location.href = url;
    </script>
</body>

</html>