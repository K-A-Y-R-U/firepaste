<header class="navigation">
    <div id="navbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg px-0 py-2">
                        <a class="navbar-brand" href="{{ url('/') }}" wire:navigate>
                            {{ $siteName }}
                        </a>

                        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fa fa-bars"></span>
                        </button>

                        <div class="collapse navbar-collapse text-center" id="navbarsExample09">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/') }}" wire:navigate>{{ __('Inicio') }}</a>
                                </li>

                                @guest
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}" wire:navigate>{{ __('Iniciar Sesion') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}" wire:navigate>{{ __('Registrarse') }}</a>
                                    </li>
                                @else
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ Auth::user()->name }}
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                                            <a class="dropdown-item" href="{{ url('dashboard') }}">{{ __('Mi Cuenta') }}</a>
                                            <a class="dropdown-item" href="#" wire:click="logout">{{ __('Cerrar sesion') }}</a>
                                        </div>
                                    </li>
                                @endguest

                                {{-- ✅ Selector de idioma --}}
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                        {{ app()->getLocale() === 'es' ? '🇪🇸 ES' : '🇺🇸 EN' }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item {{ app()->getLocale() === 'es' ? 'active' : '' }}"
                                           href="{{ route('lang.switch', 'es') }}">
                                            🇪🇸 Español
                                        </a>
                                        <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                                           href="{{ route('lang.switch', 'en') }}">
                                            🇺🇸 English
                                        </a>
                                    </div>
                                </li>
                            </ul>

                            <div class="my-2 my-md-0 ml-lg-4 text-center">
                                <a href="{{ url('/memberships') }}" class="btn btn-solid-border btn-round-full">{{ __('Membresías') }}</a>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>