@extends('layout')

@section('title', 'Editar Perfil - ' . Auth::user()->name)

@section('page-title', 'Editar Perfil')

@section('content')
    <!-- Header Card -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-full p-3 mr-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                            Gestiona tu Perfil
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Actualiza tu información personal y configuración de cuenta
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Miembro desde</div>
                    <div class="font-semibold text-gray-800 dark:text-gray-200">{{ Auth::user()->created_at->format('M Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Sidebar con información del usuario -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- User Info Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <!-- Avatar -->
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mx-auto flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    
                    <!-- User Details -->
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">{{ Auth::user()->email }}</p>
                    
                    <!-- Email Verification Status -->
                    @if(Auth::user()->email_verified_at)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Email Verificado
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Email No Verificado
                        </span>
                    @endif
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Estadísticas</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Días como miembro:</span>
                            <span class="font-semibold text-blue-600 dark:text-blue-400">{{ Auth::user()->created_at->diffInDays(now()) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Códigos canjeados:</span>
                            <span class="font-semibold text-purple-600 dark:text-purple-400">{{ Auth::user()->giftCodeRedemptions()->count() }}</span>
                        </div>
                        @php
                            $vipStatus = Auth::user()->getVipStatus();
                        @endphp
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Estado VIP:</span>
                            <span class="font-semibold {{ $vipStatus['is_active'] ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                                {{ $vipStatus['is_active'] ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Acciones Rápidas</h3>
                    <div class="space-y-3">
                        <a href="{{ route('gift-codes.redeem') }}" 
                           class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/50 dark:hover:bg-blue-900/70 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                            <span class="text-blue-800 dark:text-blue-200 font-medium">Canjear Código</span>
                        </a>
                        
                        <a href="{{ route('gift-codes.my-redemptions') }}" 
                           class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/50 dark:hover:bg-purple-900/70 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="text-purple-800 dark:text-purple-200 font-medium">Mis Canjes</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Profile Information Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Información Personal</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Actualiza tu información personal y dirección de correo electrónico</p>
                </div>
                <div class="p-6">
                    @if(class_exists('\Livewire\Component'))
                        @livewire('profile.update-profile-information-form')
                    @else
                        <form method="POST" action="#">
                            @csrf
                            @method('patch')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nombre Completo
                                    </label>
                                    <input type="text" 
                                           class="w-full px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-colors" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', Auth::user()->name) }}" 
                                           required>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Correo Electrónico
                                    </label>
                                    <input type="email" 
                                           class="w-full px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-colors" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', Auth::user()->email) }}" 
                                           required>
                                </div>
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Cambiar Contraseña</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Asegúrate de usar una contraseña larga y aleatoria para mantener tu cuenta segura</p>
                </div>
                <div class="p-6">
                    @if(class_exists('\Livewire\Component'))
                        @livewire('profile.update-password-form')
                    @else
                        <form method="POST" action="#">
                            @csrf
                            @method('put')
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Contraseña Actual
                                    </label>
                                    <input type="password" 
                                           class="w-full px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-400 focus:border-transparent transition-colors" 
                                           id="current_password" 
                                           name="current_password" 
                                           required>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Nueva Contraseña
                                        </label>
                                        <input type="password" 
                                               class="w-full px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-400 focus:border-transparent transition-colors" 
                                               id="password" 
                                               name="password" 
                                               required>
                                    </div>
                                    
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Confirmar Contraseña
                                        </label>
                                        <input type="password" 
                                               class="w-full px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-400 focus:border-transparent transition-colors" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-400 dark:hover:bg-yellow-500 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                    Actualizar Contraseña
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="bg-white dark:bg-gray-800 border border-red-200 dark:border-red-700/50 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-red-200 dark:border-red-700 px-6 py-4 bg-red-50 dark:bg-red-900/50">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-red-800 dark:text-red-200">Zona Peligrosa</h3>
                    </div>
                    <p class="text-sm text-red-700 dark:text-red-300 mt-1">Acciones irreversibles de tu cuenta</p>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Una vez que elimines tu cuenta, todos los recursos y datos serán eliminados permanentemente. 
                        Esta acción no se puede deshacer.
                    </p>
                    
                    @if(class_exists('\Livewire\Component'))
                        @livewire('profile.delete-user-form')
                    @else
                        <div class="bg-blue-50 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-blue-800 dark:text-blue-200 font-medium">Información</p>
                                    <p class="text-sm text-blue-700 dark:text-blue-300">Para eliminar tu cuenta, contacta al soporte técnico.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection