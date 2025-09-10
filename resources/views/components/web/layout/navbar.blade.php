<header class="navbar navbar-expand navbar-light bg-light shadow py-3">
    <nav class="container">
        <div class="col-3 d-flex align-items-center justify-content-center">
            <a class="navbar-brand" href="{{ route('web.home') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Farmacia 701" width="50" height="50" />
            </a>
        </div>

        <form class="d-flex align-items-center col-6 justify-content-center" action="{{ route('web.search') }}" method="GET">
            <input id="input_search" name="q" class="form-control form-control-dark input-search" type="search" placeholder="Buscar" aria-label="Search" value="{{ request('q') }}" />
            <button type="submit" id="search" class="btn button-blue button-search search">
                <i class="fa-solid fa-search"></i>
            </button>
        </form>

        <div class="navbar-nav col-3 d-flex align-items-center justify-content-center">
            <a href="{{ route('web.cart') }}" type="button" class="position-relative button-cart">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="cart-counter position-absolute top-0 start-100 translate-middle badge bg-danger rounded-circle bg-secondary" id="cart-count">0</span>
            </a>
        </div>
    </nav>

</header>