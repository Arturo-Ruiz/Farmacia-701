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

    <!-- SEO Meta Tags -->
    <meta name="description" content="Farmacia 701 - ¡Somos tus Aliados en Salud!. Ubicada en Ciudad Bolívar con servicio de entrega a domicilio. Encuentra medicamentos, productos de salud, cuidado personal y suplementos deportivos.">

    <meta name="keywords" content="Farmacia, Medicina, Médico, Medicamentos, Salud, Remedio, Farmacia en Ciudad Bolívar, medicamentos Ciudad Bolívar, farmacia cerca de mí, servicio de farmacia a domicilio, entrega de medicamentos Ciudad Bolívar, venta de medicamentos, medicinas Ciudad Bolívar, consultas farmacéuticas, asesoría farmacéutica, vitaminas, productos de cuidado personal, productos de salud, farmacias abiertas Ciudad Bolívar, remedios Ciudad Bolívar, productos naturales, salud Ciudad Bolívar, higiene personal, productos de bebé, suplementos alimenticios">

    <meta name="author" content="Arturo Ruiz">

    <meta name="robots" content="index, follow">

    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="Farmacia en Ciudad Bolívar - Medicamentos y Entrega a Domicilio">
    <meta property="og:description" content="Farmacia en Ciudad Bolívar con servicio de entrega a domicilio. Encuentra medicamentos y productos de salud.">
    <meta property="og:image" content="https://farmacia701.com/img/banner.jpg">
    <meta property="og:url" content="https://farmacia701.com">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Farmacia en Ciudad Bolívar">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Farmacia en Ciudad Bolívar - Medicamentos y Entrega a Domicilio">
    <meta name="twitter:description" content="Farmacia en Ciudad Bolívar con servicio de entrega a domicilio. Encuentra medicamentos y productos de salud.">
    <meta name="twitter:image" content="https://farmacia701.com/img/banner.jpg">


    <meta name="geo.region" content="VE-B">
    <meta name="geo.placename" content="Ciudad Bolívar">
    <meta name="geo.position" content="8.1292;-63.5400">
    <meta name="ICBM" content="8.1292, -63.5400">

    <meta name="DC.title" content="Farmacia en Ciudad Bolívar">
    <meta name="DC.creator" content="Farmacia Ciudad Bolívar">
    <meta name="DC.subject" content="medicamentos, salud, farmacia en Ciudad Bolívar">
    <meta name="DC.description" content="Farmacia en Ciudad Bolívar con servicio de entrega a domicilio para medicamentos y productos de salud.">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/assets/web/css/app.css'])

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Owl Carousel -->
    @vite(['resources/assets/web/css/owl.carousel.min.css'])
    @vite(['resources/assets/web/css/owl.theme.default.min.css'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.23.0/sweetalert2.css" integrity="sha512-/j+6zx45kh/MDjnlYQL0wjxn+aPaSkaoTczyOGfw64OB2CHR7Uh5v1AML7VUybUnUTscY5ck/gbGygWYcpCA7w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @stack('styles')


    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-6BYM46L25R"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-6BYM46L25R');
    </script>
</head>

<body class="@yield('body-class', '')">
    <x-web.layout.navbar />

    <div class="main-content">
        @yield('content')
    </div>

    <!-- <x-web.layout.whatsapp-float /> -->
    <x-web.layout.footer />


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    @vite(['resources/assets/web/js/owl.carousel.min.js'])

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.23.0/sweetalert2.min.js" integrity="sha512-pnPZhx5S+z5FSVwy62gcyG2Mun8h6R+PG01MidzU+NGF06/ytcm2r6+AaWMBXAnDHsdHWtsxS0dH8FBKA84FlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>
        $(document).ready(function() {
            if (typeof updateCartCounter === 'function') {
                updateCartCounter();
            }

            const getGreeting = () => {
                const hour = new Date().getHours();
                if (hour >= 6 && hour < 12) return '¡Hola, buenos días 🌤️,';
                if (hour >= 12 && hour < 19) return '¡Hola, buenas tardes 🌇,';
                if (hour >= 19 || hour < 6) return '¡Hola, buenas noches 🌙,';
            };

            const setWhatsappLink = (elementId, phoneNumber, message) => {
                const element = document.getElementById(elementId);
                if (element) {
                    const encodedMessage = encodeURIComponent(message);
                    element.href = `https://api.whatsapp.com/send?phone=${phoneNumber}&text=${encodedMessage}`;
                }
            };

            const greetingMessage = getGreeting();
            const baseMessage = `${greetingMessage} Farmacia 701! 💙\nQuería saber si tienen este medicamento disponible 💊 y, de ser así, cuál es su costo.\n💰 ¡Muchas gracias! 🙏\n\n *Medicamento/Producto:*`;
            const phoneNumber = '584141850671';

            setWhatsappLink('whatsapp-link', phoneNumber, baseMessage);
            setWhatsappLink('whatsapp-icon-footer', phoneNumber, baseMessage);
        });
    </script>

    @stack('scripts')
</body>

</html>