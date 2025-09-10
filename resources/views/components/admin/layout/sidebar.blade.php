<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('img/logo.png') }}" class="navbar-brand-img me-2" alt="main_logo" style="max-width: 36px; height: auto;">
            <span class="ms-1 font-weight-bold">Farmacia 701</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-desktop text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Panel de control</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-users text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Usuarios</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.day-rates.*') ? 'active' : '' }}" href="{{ route('admin.day-rates.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-chart-line text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Tasa del Día</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-tags text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Categorías</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.taxes.*') ? 'active' : '' }}" href="{{ route('admin.taxes.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-file-invoice-dollar text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Impuestos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-box text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Productos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.ads.*') ? 'active' : '' }}" href="{{ route('admin.ads.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-rectangle-ad text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Publicidades</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.carousels.*') ? 'active' : '' }}" href="{{ route('admin.carousels.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-images text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Carousel</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.laboratories.*') ? 'active' : '' }}" href="{{ route('admin.laboratories.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-flask text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Laboratorios</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}" href="{{ route('admin.clients.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-users text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Clientes</span>
                </a>
            </li>
            <!-- <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
            </li> -->

        </ul>
    </div>
    <div class="sidenav-footer mx-3 ">
        <div class="card card-plain shadow-none" id="sidenavCard">
            <img class="w-50 mx-auto" src="https://demos.creative-tim.com/argon-dashboard/assets/img/illustrations/icon-documentation.svg" alt="sidebar_illustration">
            <div class="card-body text-center p-3 w-100 pt-0">
                <div class="docs-info">
                    <h6 class="mb-0">Necesitas ayuda?</h6>
                    <p class="text-xs font-weight-bold mb-0">C</p>
                </div>
            </div>
        </div>
        <a href="https://www.creative-tim.com/learning-lab/bootstrap/license/argon-dashboard" target="_blank" class="btn btn-dark btn-sm w-100 mb-3">Documentation</a>
        <a class="btn btn-primary btn-sm mb-0 w-100" href="https://www.creative-tim.com/product/argon-dashboard-pro?ref=sidebarfree" type="button">Upgrade to pro</a>
    </div>
</aside>