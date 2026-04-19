@extends('layouts.app')

@section('title', 'Membresías')

@section('content')
<div class="container-fluid px-0">
    <section class="section pricing position-relative">

        {{-- ✅ Fondo con gradiente dinámico usando theme color --}}
        <div class="hero-img h70" style="
            background: linear-gradient(
                135deg,
                color-mix(in srgb, var(--theme-color) 70%, #0d0d14) 0%,
                color-mix(in srgb, var(--theme-color) 35%, #13161d) 60%,
                #0d0d14 100%
            ) !important;
        "></div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center">
                    <div class="section-title">
                        <h2 class="text-white">{{ __('Nuestros Precios') }}</h2>
                        <h3 class="mt-3 text-white">{{ __('Sin Cargos Ocultos. Elige tu Plan Perfecto') }}</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                @php
                    $plans = [
                        ['name' => 'Lifetime', 'price' => 100, 'days' => 36500, 'id' => 6],
                        ['name' => 'Platinum', 'price' => 30,  'days' => 180,   'id' => 5],
                        ['name' => 'Gold',     'price' => 20,  'days' => 90,    'id' => 4],
                        ['name' => 'Silver',   'price' => 8,   'days' => 30,    'id' => 3],
                        ['name' => 'Bronze',   'price' => 3,   'days' => 7,     'id' => 2],
                    ];

                    $planColors = [
                        'Lifetime' => '#04c1fb',
                        'Platinum' => '#0498fb',
                        'Gold'     => '#d4af37',
                        'Silver'   => '#c0c0c0',
                        'Bronze'   => '#3287cd',
                    ];
                @endphp

                @foreach ($plans as $plan)
                    @php $color = $planColors[$plan['name']]; @endphp
                    <div class="col pricing mb-4 position-relative">
                        <div class="card text-center mb-md-0 mb-3 bg-{{ $plan['name'] }}">
                            <div class="card-body py-5">
                                <div class="pricing-header mb-2">
                                    <div class="membership-name d-flex justify-content-center">
                                        <h5 class="font-weight-normal {{ $plan['name'] }}">{{ $plan['name'] }}</h5>
                                    </div>
                                    <h1>${{ $plan['price'] }}</h1>
                                    <div class="dias d-flex justify-content-center">
                                        <p class="text-muted">{{ $plan['days'] }} {{ __('días') }}</p>
                                    </div>
                                </div>
                                <strong>{{ __('Incluye:') }}</strong>
                                <ul class="list-unstyled lh-45 mt-3 text-black">
                                    <li>- {{ __('Accede a todo el contenido del sitio!') }}</li>
                                    <li>- {{ __('Sin Anuncios Molestos') }}</li>
                                </ul>
                                {{-- ✅ Botón estático sin efecto hover --}}
                                <a href="{{ url('/usuario/cuenta/membresias/comprar/' . $plan['id']) }}"
                                   class="btn btn-small btn-round-full mt-3 plan-btn-static">
                                    {{ __('Comprar') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

<style>
    /* ── Fondo hero sin overlay oscuro extra ── */
    .pricing .hero-img.bg-overlay::before {
        display: none !important;
    }

    /* ── Botón estático con color del plan, sin hover ── */
    .plan-btn-static {
        background: transparent !important;
        color: #242424 !important;
        transition: none !important;
        min-width: 120px;
    }

    /* ── Sobrescribir cualquier hover de btn-small ── */
    .plan-btn-static:hover,
    .plan-btn-static:focus,
    .plan-btn-static:active {
        background: transparent !important;
        color: #242424 !important;
        filter: none !important;
        transform: none !important;
        box-shadow: none !important;
    }

    /* Colores de borde por plan */
    .bg-Lifetime .plan-btn-static { border: 2px solid #04c1fb !important; }
    .bg-Platinum .plan-btn-static { border: 2px solid #0498fb !important; }
    .bg-Gold     .plan-btn-static { border: 2px solid #d4af37 !important; }
    .bg-Silver   .plan-btn-static { border: 2px solid #c0c0c0 !important; }
    .bg-Bronze   .plan-btn-static { border: 2px solid #3287cd !important; }
</style>
@endsection