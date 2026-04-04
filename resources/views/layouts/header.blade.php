

<header class="navigation">
  <div id="navbar">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <nav class="navbar navbar-expand-lg px-0 py-2">
            <a class="navbar-brand" href="{{ url('/') }}">
              {{ $siteName }}
            </a>

            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
              <span class="fa fa-bars"></span>
            </button>

            <div class="collapse navbar-collapse text-center" id="navbarsExample09">
              <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                  <a class="nav-link" href="{{ url('/') }}">Inicio</a>
                </li>

                @guest
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Registro</a>
                  </li>
                @else
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                      <a class="dropdown-item" href="{{ url('dashboard') }}">Mi Cuenta</a>
                      <a class="dropdown-item" href="{{ route('logout') }}"
                         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Cerrar sesión
                      </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                      </form>
                    </div>
                  </li>
                @endguest
              </ul>

              <div class="my-2 my-md-0 ml-lg-4 text-center">
                <a href="ff" class="btn btn-solid-border btn-round-full">Membresías</a>
              </div>     
            </div>
          </nav>
        </div>
      </div>
    </div>
  </div>
</header>
