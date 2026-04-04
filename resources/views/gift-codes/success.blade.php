<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Código Canjeado Exitosamente!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .celebration {
            animation: celebration 0.8s ease-out;
        }
        @keyframes celebration {
            0% { transform: scale(0.3) rotate(-180deg); opacity: 0; }
            50% { transform: scale(1.1) rotate(0deg); opacity: 1; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        .confetti {
            animation: confetti 3s ease-in-out infinite;
        }
        @keyframes confetti {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-10px) rotate(90deg); }
            50% { transform: translateY(0) rotate(180deg); }
            75% { transform: translateY(-5px) rotate(270deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 via-emerald-50 to-teal-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Success Animation -->
        <div class="text-center mb-8 celebration">
            <div class="inline-block p-8 bg-white rounded-full shadow-lg mb-6">
                <svg class="w-24 h-24 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">¡Código Canjeado!</h1>
            <p class="text-lg text-gray-600">Tu membresía VIP ha sido activada</p>
            
            <!-- Floating confetti elements -->
            <div class="relative overflow-hidden">
                <div class="absolute top-0 left-1/4 w-2 h-2 bg-yellow-400 rounded-full confetti" style="animation-delay: 0s;"></div>
                <div class="absolute top-0 left-3/4 w-2 h-2 bg-pink-400 rounded-full confetti" style="animation-delay: 0.5s;"></div>
                <div class="absolute top-0 left-1/2 w-2 h-2 bg-blue-400 rounded-full confetti" style="animation-delay: 1s;"></div>
            </div>
        </div>

        <!-- Success Details -->
        <div class="max-w-lg mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8 border-l-4 border-green-500">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                        ¡Has recibido {{ $vip_days }} días de VIP!
                    </h2>
                    <p class="text-gray-600">{{ $message }}</p>
                </div>

                <!-- VIP Details -->
                <div class="bg-gradient-to-r from-purple-100 to-blue-100 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalles de tu membresía VIP</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Estado:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Activo
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Días añadidos:</span>
                            <span class="font-semibold text-purple-600">{{ $vip_days }} días</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Válido hasta:</span>
                            <span class="font-semibold text-blue-600">
                                {{ \Carbon\Carbon::parse($expires_at)->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Días restantes:</span>
                            <span class="font-bold text-green-600">
                                {{ $vipStatus['days_remaining'] }} días
                            </span>
                        </div>
                    </div>
                </div>

                <!-- VIP Benefits -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Beneficios VIP activados</h3>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Acceso a contenido VIP exclusivo</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Descargas ilimitadas</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Soporte prioritario</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Sin publicidad</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('dashboard') }}" 
                       class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold text-center transition-all duration-200 hover:from-blue-700 hover:to-purple-700 focus:ring-4 focus:ring-blue-200 transform hover:scale-105">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                            </svg>
                            Ir al Dashboard
                        </span>
                    </a>
                    
                    <a href="{{ route('gift-codes.my-redemptions') }}" 
                       class="flex-1 bg-white text-purple-600 border-2 border-purple-600 py-3 px-6 rounded-lg font-semibold text-center transition-all duration-200 hover:bg-purple-600 hover:text-white focus:ring-4 focus:ring-purple-200">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Ver Mis Canjes
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Actions -->
        <div class="max-w-lg mx-auto mt-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">¿Tienes más códigos?</h3>
                <p class="text-gray-600 mb-4">Si tienes otro código de regalo, puedes canjearlo ahora mismo para extender tu membresía VIP.</p>
                <a href="{{ route('gift-codes.redeem') }}" 
                   class="w-full bg-gradient-to-r from-green-500 to-teal-500 text-white py-2 px-4 rounded-lg font-medium text-center transition-all duration-200 hover:from-green-600 hover:to-teal-600 focus:ring-4 focus:ring-green-200 inline-block">
                    Canjear Otro Código
                </a>
            </div>
        </div>
    </div>
</body>
</html>