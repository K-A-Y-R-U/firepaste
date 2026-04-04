@extends('layouts.app')

@section('title', 'Membresías')

@section('content')
<div class="container-fluid px-0">
    <section class="section pricing position-relative">
        <div class="hero-img bg-overlay h70"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center">
                    <div class="section-title">
                        <h2 class="text-white">Nuestros Precios</h2>
                        <h3 class="mt-3 text-white">Sin Cargos Ocultos. Elige tu Plan Perfecto</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                @php
                    $plans = [
                        ['name' => 'Lifetime', 'price' => 100, 'days' => 36500, 'id' => 6],
                        ['name' => 'Platinum', 'price' => 30, 'days' => 180, 'id' => 5],
                        ['name' => 'Gold', 'price' => 20, 'days' => 90, 'id' => 4],
                        ['name' => 'Silver', 'price' => 8, 'days' => 30, 'id' => 3],
                        ['name' => 'Bronze', 'price' => 3, 'days' => 7, 'id' => 2]
                    ];
                @endphp
                
                @foreach ($plans as $plan)
                    <div class="col pricing mb-4 position-relative">
                        <div class="card text-center mb-md-0 mb-3 bg-{{ $plan['name'] }}">
                            <div class="card-body py-5">
                                <div class="pricing-header mb-2">
                                    <div class="membership-name d-flex justify-content-center">
                                        <h5 class="font-weight-normal {{ $plan['name'] }}">{{ $plan['name'] }}</h5>
                                    </div>
                                    <h1>${{ $plan['price'] }}</h1>
                                    <div class="dias d-flex justify-content-center">
                                        <p class="text-muted">{{ $plan['days'] }} días</p>
                                    </div>
                                </div>
                                <strong>Incluye:</strong>
                                <ul class="list-unstyled lh-45 mt-3 text-black">
                                    <li>- Accede a todo el contenido del sitio!</li>
                                    <li>- Sin Anuncios Molestos</li>
                                </ul>
                                <a href="{{ url('/usuario/cuenta/membresias/comprar/' . $plan['id']) }}" class="btn btn-small btn-solid-border mt-3 btn-round-full">Comprar</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
