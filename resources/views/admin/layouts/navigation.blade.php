<div class="collapse navbar-collapse" id="navbar-menu">
    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center justify-content-center">
        <ul class="navbar-nav">
            <li class="nav-item @if(request()->routeIs('home')) active @endif">
                <a class="nav-link" href="{{ route('home') }}" >
                    <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                    </span>
                    <span class="nav-link-title">
                        Bosh menu
                    </span>
                </a>
            </li>

            <li class="nav-item @if(request()->routeIs('offers.*')) active @endif">
                <a class="nav-link" href="{{ route('offers.index') }}" >
                    <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                        <x-svg.folder></x-svg.folder>
                    </span>
                    <span class="nav-link-title">
                        Buyurtmalar
                    </span>
                </a>
            </li>

            <li class="nav-item dropdown @if(request()->routeIs('admin.*')) active @endif">
                <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="true">
                    <span class="nav-link-icon d-md-none d-lg-inline-block"> <x-svg.settings></x-svg.settings></span>
                    <span class="nav-link-title">Boshqaruv</span>
                </a>
                <div class="dropdown-menu" data-bs-popper="static">
                    <a class="dropdown-item @if(!request()->routeIs('admin.services.categories.*') && request()->routeIs('admin.services.*')) active @endif" href="{{route('admin.services.index')}}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><x-svg.wrench></x-svg.wrench></span>
                        <span class="nav-link-title">Xizmatlar tariflari</span>
                    </a>
                    <a class="dropdown-item @if(request()->routeIs('admin.services.categories')) active @endif" href="{{route('admin.services.categories.index')}}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><x-svg.tools></x-svg.tools></span>
                        <span class="nav-link-title">Xizmat turlari</span>
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a class="dropdown-item @if(request()->routeIs('admin.users.*')) active @endif" href="{{route('admin.users.index')}}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><x-svg.users></x-svg.users></span>
                            <span class="nav-link-title">Xodimlar</span>
                        </a>
                    @endif
                    <a class="dropdown-item" href="/log-viewer" target="_blank">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><x-svg.history></x-svg.history></span>
                        <span class="nav-link-title">Log-viewer</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</div>
