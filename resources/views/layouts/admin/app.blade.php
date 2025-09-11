<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="es">

    <title>@yield('title') | Farmacia 701</title>

    <link rel="icon" type="image/png" href="{{ asset('/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href=" {{ asset('/apple-touch-icon.png') }} " />
    <meta name="apple-mobile-web-app-title" content="Farmacia 701" />
    <link rel="manifest" href="{{ asset('/site.webmanifest') }}" />

    <meta name="robots" content="noindex, nofollow">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/assets/admin/css/app.min.css'])
</head>

<body class="g-sidenav-show" data-user-id="{{ Auth::id() }}">
    <div class="min-height-300 bg-primary position-absolute w-100"></div>

    <x-admin.layout.sidebar />

    <main class="main-content position-relative border-radius-lg">
        <x-admin.layout.navbar />

        <div class="container-fluid py-4">

            @if (session('success'))
            <div class="alert alert-success text-white" role="alert">
                {{ session('success') }}
            </div>
            @endif

            @yield('content')

            <footer class="footer pt-3  ">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                © <script>
                                    document.write(new Date().getFullYear())
                                </script>
                                made with <i class="fa fa-heart"></i> by
                                <a href="https://github.com/Arturo-Ruiz" class="font-weight-bold" target="_blank">Arturo Ruiz</a>
                                for Farmacia 701.
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                <li class="nav-item">
                                    <a href="{{ route('web.home') }}" class="nav-link">Farmacia 701</a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a href="#" class="nav-link text-muted" target="_blank">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link text-muted" target="_blank">Blog</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link pe-0 text-muted" target="_blank">License</a>
                                </li> -->
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <!--   Core JS Files   -->
    <script src="{{ asset('js/admin/popper.min.js') }}"></script>
    <script src="{{asset('js/admin/bootstrap.min.js')}}"></script>


    @vite(['resources/js/app.js','resources/assets/admin/js/app.min.js', 'resources/assets/admin/js/plugins/perfect-scrollbar.min.js', 'resources/assets/admin/js/plugins/smooth-scrollbar.min.js', 'resources/assets/admin/js/plugins/chartjs.min.js', 'resources/assets/admin/js/plugins/chartjs.min.js'])

    <script async defer src="https://buttons.github.io/buttons.js"></script>


    @stack('scripts')

    @vite(['resources/assets/admin/js/sidenav.js'])
</body>

</html>