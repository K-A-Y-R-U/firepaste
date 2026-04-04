@extends('layouts.app')

@section('content')
<div class="container mt-4 pt-2 p-0">
    <div class="row mb-4">
        <!-- Menú lateral -->
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card card-body">
                <div class="list-group">
                    <a href="{{ url('dashboard') }}" class="list-group-item list-group-item-action principaltxt text-dark"><strong>Mi cuenta</strong></a> <br>
                    <a href="{{ url('membresias') }}" class="list-group-item list-group-item-action principaltxt text-dark"><strong>Membresías</strong></a> <br>
                    <a href="{{ url('/usuario/cuenta/membresias/canjear') }}" class="list-group-item list-group-item-action principaltxt text-dark"><strong>Canjear Código</strong></a> <br>
                    <a href="{{ url('reportes') }}" class="list-group-item list-group-item-action principaltxt text-dark"><strong>Reportes</strong></a>
                </div>
            </div>
        </div>

        <!-- Contenido dinámico -->
        <div class="col-md-9">
            @yield('cuenta-content')
        </div>
    </div>
</div>
@endsection
