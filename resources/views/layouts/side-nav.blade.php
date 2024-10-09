<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">features</div>
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <a class="nav-link collapsed {{ ((request()->routeIs('parking.parking_public') ? 'active' : request()->routeIs('monthlyPass.monthly_pass_public')) ? 'active' : request()->routeIs('reserveBay.reserve_bay')) ? 'active' : '' }}"
                    href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false"
                    aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-wrench"></i></div>
                    Services
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ ((request()->routeIs('parking.parking_public') ? 'show' : request()->routeIs('monthlyPass.monthly_pass_public')) ? 'show' : request()->routeIs('reserveBay.reserve_bay')) ? 'show' : '' }}"
                    id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->routeIs('parking.parking_public') ? 'active' : '' }}"
                            href="{{ route('parking.parking_public') }}">Parking</a>
                        <a class="nav-link" href="">Compound</a>
                        <a class="nav-link {{ request()->routeIs('monthlyPass.monthly_pass_public') ? 'active' : '' }}"
                            href="{{ route('monthlyPass.monthly_pass_public') }}">Monthly Pass</a>
                        <a class="nav-link {{ request()->routeIs('reserveBay.reserve_bay') ? 'active' : '' }}"
                            href="{{ route('reserveBay.reserve_bay') }}">Reserve Bay</a>
                    </nav>
                </div>
                <div class="sb-sidenav-menu-heading">Promotion</div>
                <a class="nav-link {{ request()->routeIs('promotion.promotion.monthly_pass') ? 'active' : '' }}"
                    href="{{ route('promotion.promotion.monthly_pass') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-solid fa-tag"></i></div>
                    Monthly Pass
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ Auth::user()->name }}
        </div>
    </nav>
</div>
