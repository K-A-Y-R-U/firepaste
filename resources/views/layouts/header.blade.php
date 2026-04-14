<header class="navigation">
  <div id="navbar">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <nav class="navbar navbar-expand-lg px-0 py-2">
            <a class="navbar-brand" href="{{ url('/') }}">
              Firepaste
            </a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
              <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse text-center" id="navbarsExample09">
              <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                  <a class="nav-link" href="{{ url('/') }}">{{ __('Inicio') }}</a>
                </li>

                @guest
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar Sesion') }}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Registrarse') }}</a>
                  </li>
                @else
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                      <a class="dropdown-item" href="{{ url('dashboard') }}">{{ __('Mi Cuenta') }}</a>
                      <a class="dropdown-item" href="{{ route('logout') }}"
                         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Cerrar sesión') }}
                      </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                      </form>
                    </div>
                  </li>
                @endguest

                {{-- ✅ Selector de idioma dinámico con banderas compatibles --}}
                @php
                  $activeLanguages = \App\Models\Language::getActive();
                  $currentLang = $activeLanguages->firstWhere('code', app()->getLocale());
                @endphp
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" style="display:flex;align-items:center;gap:6px;">
                    @if($currentLang?->flag_emoji)
                      <span class="fi fi-{{ $currentLang->flag_emoji }}" style="width:20px;height:15px;border-radius:2px;"></span>
                    @endif
                    {{ strtoupper(app()->getLocale()) }}
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    @foreach($activeLanguages as $language)
                      <a class="dropdown-item {{ app()->getLocale() === $language->code ? 'active' : '' }}"
                         href="{{ route('lang.switch', $language->code) }}"
                         style="display:flex;align-items:center;gap:8px;">
                        @if($language->flag_emoji)
                          <span class="fi fi-{{ $language->flag_emoji }}" style="width:20px;height:15px;border-radius:2px;flex-shrink:0;"></span>
                        @endif
                        {{ $language->native_name }}
                      </a>
                    @endforeach
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